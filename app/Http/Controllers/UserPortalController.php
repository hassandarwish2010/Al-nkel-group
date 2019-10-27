<?php

namespace App\Http\Controllers;

use App\Charter;
use App\CharterOrderFlights;
use App\CharterOrders;
use App\FlightOrders;
use App\Mail\TicketEmail;
use App\Notifications\Notifier;
use App\Transaction;
use App\Travel;
use App\TravelOrders;
use App\VisaOrders;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class UserPortalController extends Controller {
	/**
	 * UserPortalController constructor.
	 */
	public function __construct() {
		$this->middleware( 'auth' );
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index() {
		Session::flash( 'sidebar', 'user-portal-index' );

		$isAdmin = false;

		if ( Auth::check() and Auth::user()->type == 'Super Admin' ) {
			$isAdmin = true;
		}

		return view( 'admin.profile', compact( 'isAdmin' ) );
	}

	public function changeAvatar( Request $request ) {
		$image = Storage::disk( 'public' )->put( 'visa/logos', $request->file( 'avatar' ) );

		$user         = Auth::user();
		$user->avatar = $image;
		$user->save();

		return back()->with( 'success', 'You have successfully changed your logo.' );
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function travels() {
		Session::flash( 'sidebar', 'user-portal-travels' );

		$travels = Auth::user()->travelPurchases;

		return view( 'user.travels', compact( 'travels' ) );
	}

	/**
	 * @param TravelOrders $order
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function cancelTravel( TravelOrders $order ) {
		if ( $order->status === 'received' ) {
			return redirect()->back()->with( [ 'fail' => 'you can\'t cancel for now because your ticket request now is on progress.' ] );
		}

		if ( $order->status === 'rejected' ) {
			return redirect()->back()->with( [ 'fail' => 'you can\'t cancel for now because your ticket request has been rejected.' ] );
		}

		if ( $order->status === '1' ) {
			return redirect()->back()->with( [ 'fail' => 'you can\'t cancel for now because your ticket has been Delivered.' ] );
		}

		$order->update( [ 'status' => 'canceled' ] );

		// Save to transactions
		$new_balance = Auth::user()->balance + $order->price;

		if ( $order->travel->commission > 0 ) {
			$commissionObject = getCommission( $order->travel );
			$commission       = $commissionObject['commission'];

			if ( $commissionObject['is_percent'] ) {
				$commission = ( $order->price * $commissionObject['commission'] ) / 100;
			}
		}

		$new_balance -= $commission;

		Auth::user()->userTransactions()->create( [
			'to'             => $order->user_id,
			'amount'         => $order->price,
			'comment'        => "Travel cancellation refund",
			'type'           => "DepositOfCredit",
			'creditBefore'   => Auth::user()->balance,
			'creditAfter'    => $new_balance,
			'connectedID'    => $order->id,
			'connectedTable' => 'travel'
		] );

		Auth::user()->update( [ 'balance' => $new_balance ] );

		return redirect()->back()->with( [ 'success' => 'you successfully canceled this travel ticket' ] );
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function flights() {
		Session::flash( 'sidebar', 'user-portal-flights' );

		$flights = Auth::user()->flightPurchases;

		return view( 'user.flights', compact( 'flights' ) );
	}

	/**
	 * @param FlightOrders $order
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function cancelFlight( FlightOrders $order ) {
		if ( $order->status === 'received' ) {
			return redirect()->back()->with( [ 'fail' => 'you can\'t cancel for now because your flight ticket request now is on progress.' ] );
		}

		if ( $order->status === 'rejected' ) {
			return redirect()->back()->with( [ 'fail' => 'you can\'t cancel for now because your flight ticket request has been rejected.' ] );
		}

		if ( $order->status === '1' ) {
			return redirect()->back()->with( [ 'fail' => 'you can\'t cancel for now because your flight ticket has been Delivered.' ] );
		}

		$order->update( [ 'status' => 'canceled' ] );

		// Save to transactions
		$new_balance = Auth::user()->balance + $order->price;

		if ( $order->flight->commission > 0 ) {
			$commissionObject = getCommission( $order->flight );
			$commission       = $commissionObject['commission'];
			if ( $commissionObject['is_percent'] ) {
				$commission = ( $order->price * $commissionObject['commission'] ) / 100;
			}
		}

		$new_balance -= $commission;

		Auth::user()->userTransactions()->create( [
			'to'             => $order->user_id,
			'amount'         => $order->price,
			'comment'        => "Flight cancellation refund",
			'type'           => "DepositOfCredit",
			'creditBefore'   => Auth::user()->balance,
			'creditAfter'    => $new_balance,
			'connectedID'    => $order->id,
			'connectedTable' => 'flight'
		] );

		Auth::user()->update( [ 'balance' => $new_balance ] );

		return redirect()->back()->with( [ 'success' => 'you successfully canceled this travel ticket' ] );
	}


	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function charter() {
		Session::flash( 'sidebar', 'user-portal-charter' );

		$orders = Auth::user()->charterPurchases;

		return view( 'user.charter', compact( 'orders' ) );
	}

	public function charterDetails( CharterOrders $order ) {
		Session::flash( 'sidebar', 'user-portal-charter' );

		$isCancelled = false;
		if ( $order->status == "cancelled" || $order->status == "rejected" ) {
			$isCancelled = true;
		}

		$canSplit = $order->passengers()->where( "age", "adult" )->count() > 1;

		$canVoid   = false;
		$canCancel = false;

		$charter = $order->charter;

		if ( $charter->void_max > 0 ) {
			$hours = $order->created_at->diffInHours();

			if ( $charter->void_max > $hours ) {
				$canVoid = true;
			}
		}

		if ( $charter->can_cancel and ! $canVoid ) {
			$canCancel = true;
		}

		if ($isCancelled) {
			$canVoid = false;
			$canCancel = false;
		}

		if ( ! request()->has( "secret" ) ) {
//			return null;
		}

		return view( 'user.charter_details', compact( 'order', 'canSplit', 'canCancel', 'canVoid', 'isCancelled' ) );
	}

	public function charterButtons( CharterOrders $order, Request $request ) {
		$action   = $request->get( "action" );
		$adults   = $order->passengers()->where( "age", "adult" )->count();
		$children = $order->passengers()->where( "age", "child" )->count();
		$babies   = $order->passengers()->where( "age", "baby" )->count();

		if ( $action == "download_option" ) {
			$type = $request->get( "type" );
			addCharterHistory( $order->id, "$type Ticket" );

			return view( "user.buttons.download_options" )->render();
		}

		if ( $action == "history" ) {
			return view( "user.buttons.history", compact( 'order' ) )->render();
		}

		if ( $action == "reschedule" or $action == "search_flights" ) {
			$isSearch     = $action == "search_flights";
			$flight_class = $request->get( "flight_class" );
			$flight_date  = $request->get( "flight_date" );
			$id           = $request->get( "id" );

			$currentFlight = CharterOrderFlights::find( $id );

			$flights = [];
			if ( $isSearch ) {

				$flights_search = Charter::where( "flight_date", $flight_date )->get();

				foreach ( $flights_search as $flight ) {
					if ( $flight_class == "Economy" ) {
						$price = ( $adults * $flight->price_adult );
						$price += ( $children * $flight->price_adult );
						$price += ( $babies * $flight->price_adult );
					} else {
						$price = ( $adults * $flight->business_adult );
						$price += ( $children * $flight->business_child );
						$price += ( $babies * $flight->business_baby );
					}

					$flight->price = $price;
					$flights[]     = $flight;
				}
			}

			$nextDay = Carbon::parse($flight_date)->addDays(1)->format("Y-m-d");
			$prevDay = Carbon::parse($flight_date)->subDays(1)->format("Y-m-d");

			return view( "user.buttons.reschedule", compact( 'order', 'isSearch', 'flight_class', 'flight_date', 'flights', 'currentFlight', 'nextDay', 'prevDay' ) )->render();
		}

		if ( $action == "reschedule_process" ) {
			$id           = $request->get( "id" );
			$newFlightId  = $request->get( "selectedFlight" );
			$payment      = $request->get( "payment" );
			$flight_class = $request->get( "flight_class" );

			$newFlight = Charter::find( $newFlightId );

			if ( $flight_class == "Economy" ) {
				$price = ( $adults * $newFlight->price_adult );
				$price += ( $children * $newFlight->price_adult );
				$price += ( $babies * $newFlight->price_adult );
			} else {
				$price = ( $adults * $newFlight->business_adult );
				$price += ( $children * $newFlight->business_child );
				$price += ( $babies * $newFlight->business_baby );
			}

			$order->flights()->find( $id )->update( [
				"charter_id"   => $newFlightId,
				"price"        => $price,
				"flight_class" => $flight_class
			] );

			$new_balance = Auth::user()->balance - $payment;

			Auth::user()->userTransactions()->create( [
				'to'             => $order->user_id,
				'amount'         => $payment,
				'comment'        => "Charter change fees",
				'type'           => "withdrawal",
				'creditBefore'   => Auth::user()->balance,
				'creditAfter'    => $new_balance,
				'connectedID'    => $order->id,
				'connectedTable' => 'charter'
			] );

			Auth::user()->update( [ 'balance' => $new_balance ] );

			addCharterHistory( $order->id, "Reschedule Flight" );

			return response()->json( [
				"done"  => true,
				"error" => false,
			] );
		}

		if ( $action == "send_email_form" ) {
			return view( "user.buttons.send_email", compact( 'order' ) )->render();
		}

		if ( $action == "send_email" ) {

			Mail::to( $request->get( "email" ) )->send( new TicketEmail( $request, $order ) );

			addCharterHistory( $order->id, "Send Ticket" );

			return response()->json( [
				"sent"  => true,
				"error" => false,
			] );
		}

		if ( $action == "edit_details" ) {
			$form = $request->get( "form" );
			if ( $form ) {
				return view( "user.buttons.edit_details_form", compact( 'order', 'form' ) )->render();
			}

			if ( $request->has( "note" ) ) {
				$order->note = $request->get( "note" );
				addCharterHistory( $order->id, "Edit Note" );
			} else {
				$order->phone = $request->get( "phone" );
				$order->email = $request->get( "email" );
				addCharterHistory( $order->id, "Edit Contact Details" );
			}

			$order->save();
		}

		if ( $action == "cancel_void_form" ) {
			$isVoid      = $request->get( "isVoid" ) == "true";
			$cancel_fees = $isVoid ? 0 : $order->charter->cancel_fees;

			return view( "user.buttons.cancel_void_form", compact( 'order', 'cancel_fees' ) )->render();
		}

		if ( $action == "cancel_void" ) {
			$isVoid = $request->get( "isVoid" ) == "true";

			$order->status = "cancelled";
			$order->save();

			$new_balance = Auth::user()->balance + $order->price;
			$new_balance -= calculateCommission( $order->charter, $order->price );

			if ( ! $isVoid ) {
				$new_balance -= $order->cancel_fees;
			}

			Auth::user()->userTransactions()->create( [
				'to'             => $order->user_id,
				'amount'         => $order->price,
				'comment'        => "Charter cancellation refund",
				'type'           => "DepositOfCredit",
				'creditBefore'   => Auth::user()->balance,
				'creditAfter'    => $new_balance,
				'connectedID'    => $order->id,
				'connectedTable' => 'charter'
			] );

			Auth::user()->update( [ 'balance' => $new_balance ] );

			addCharterHistory( $order->id, "Cancel Ticket" );
		}

		return null;
	}

	/**
	 * @param CharterOrders $order
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function cancelCharterTicket( CharterOrders $order ) {
		$order->update( [ 'status' => 'canceled' ] );

		// Save to transactions
		$minutes = ( time() - strtotime( $order->created_at ) ) / 60;

		if ( $minutes <= 120 ) {
			$new_balance = Auth::user()->balance + $order->price;

			if ( $order->charter->commission > 0 ) {
				$commissionObject = getCommission( $order->charter );
				$commission       = $commissionObject['commission'];
				if ( $commissionObject['is_percent'] ) {
					$commission = ( $order->price * $commissionObject['commission'] ) / 100;
				}
			}

			$new_balance -= $commission;

			Auth::user()->userTransactions()->create( [
				'to'             => $order->user_id,
				'amount'         => $order->price,
				'comment'        => "Charter cancellation refund",
				'type'           => "DepositOfCredit",
				'creditBefore'   => Auth::user()->balance,
				'creditAfter'    => $new_balance,
				'connectedID'    => $order->id,
				'connectedTable' => 'charter'
			] );

			Auth::user()->update( [ 'balance' => $new_balance ] );
		}

		return redirect()->back()->with( [ 'success' => 'you successfully canceled this charter ticket' ] );
	}

	public function ticketCharter( Request $request ) {
		Session::flash( 'sidebar', 'ticket-charter' );
		$orders = [];
		if ( $request->search ) {
			$orders = CharterOrders::where( "pnr", $request->search )->get();
		}

		return view( 'user.ticket', compact( 'orders' ) );
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function visa() {
		Session::flash( 'sidebar', 'user-portal-visa' );

		$visas = Auth::user()->visaPurchases;

		return view( 'user.visa', compact( 'visas' ) );
	}

	/**
	 * @param FlightOrders $order
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function cancelVisa( VisaOrders $order ) {
		if ( $order->status === 'received' ) {
			return redirect()->back()->with( [ 'fail' => 'you can\'t cancel for now because your visa request now is on progress.' ] );
		}

		if ( $order->status === 'rejected' ) {
			return redirect()->back()->with( [ 'fail' => 'you can\'t cancel for now because your visa request has been rejected.' ] );
		}

		if ( $order->status === '1' ) {
			return redirect()->back()->with( [ 'fail' => 'you can\'t cancel for now because your visa has been Delivered.' ] );
		}

		$order->update( [ 'status' => 'canceled' ] );

		// Save to transactions
		$new_balance = Auth::user()->balance + $order->price;

		if ( $order->visa->commission > 0 ) {
			$commissionObject = getCommission( $order->visa );
			$commission       = $commissionObject['commission'];
			if ( $commissionObject['is_percent'] ) {
				$commission = ( $order->price * $commissionObject['commission'] ) / 100;
			}
		}

		$new_balance -= $commission;

		Auth::user()->userTransactions()->create( [
			'to'             => $order->user_id,
			'amount'         => $order->price,
			'comment'        => "Visa cancellation refund",
			'type'           => "DepositOfCredit",
			'creditBefore'   => Auth::user()->balance,
			'creditAfter'    => $new_balance,
			'connectedID'    => $order->id,
			'connectedTable' => 'visa'
		] );

		Auth::user()->update( [ 'balance' => $new_balance ] );

		return redirect()->back()->with( [ 'success' => 'you successfully canceled this travel ticket' ] );
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function history() {
		Session::flash( 'sidebar', 'user-portal-history' );

		$travels      = Auth::user()->travelPurchases()->orderBy( 'id', 'DESC' )->limit( 5 )->get();
		$flights      = Auth::user()->flightPurchases()->orderBy( 'id', 'DESC' )->limit( 5 )->get();
		$visas        = Auth::user()->visaPurchases()->orderBy( 'id', 'DESC' )->limit( 5 )->get();
		$transactions = Auth::user()->userTransactions()->orderBy( 'id', 'DESC' )->limit( 5 )->get();

		if ( Auth::user()->type === 'Super Admin' ) {
			$travels      = TravelOrders::orderBy( 'id', 'DESC' )->limit( 5 )->get();
			$flights      = FlightOrders::orderBy( 'id', 'DESC' )->limit( 5 )->get();
			$visas        = VisaOrders::orderBy( 'id', 'DESC' )->limit( 5 )->get();
			//$transactions = Transaction::orderBy( 'id', 'DESC' )->limit( 5 )->get();
		}
//dd($travels);
		return view( 'user.search.index', compact( 'travels', 'flights', 'visas', 'transactions' ) );
	}

	public function historyData( Datatables $datatables, Request $request ) {

		$isAdmin    = Auth::user()->type === 'Super Admin';
		$order_type = $request->get( 'order_type' );

		if ( $order_type == "travel" ) {
			$builder = $isAdmin ? TravelOrders::orderBy( 'id', 'DESC' ) : Auth::user()->travelPurchases()->orderBy( 'id', 'DESC' );

			return $datatables
				->of( $builder )
				->rawColumns( [ 'special_commission', 'locked', 'actions', 'economy_seats', 'business_seats' ] )
				->make();
		}

		if ( $order_type == "flight" ) {
			$builder = $isAdmin ? FlightOrders::orderBy( 'id', 'DESC' ) : Auth::user()->flightPurchases()->orderBy( 'id', 'DESC' );

			return $datatables
				->of( $builder )
				->rawColumns( [ 'special_commission', 'locked', 'actions', 'economy_seats', 'business_seats' ] )
				->make();
		}

		if ( $order_type == "visa" ) {
			$builder = $isAdmin ? VisaOrders::orderBy( 'id', 'DESC' ) : Auth::user()->visaPurchases()->orderBy( 'id', 'DESC' );

			return $datatables
				->of( $builder )
				->editColumn( 'delivered_by', function ( VisaOrders $order ) {
					return $order->deliveredBy ? $order->deliveredBy->name : '---';
				} )
				->editColumn( 'name', function ( VisaOrders $order ) {
					return $order->first_name . ' ' . $order->last_name;
				} )
				->editColumn( 'user', function ( VisaOrders $order ) {
					return $order->user->name;
				} )
				->editColumn( 'status', function ( VisaOrders $order ) {
					return $order->status == '1' ? 'Delivered <a href="' . route( 'visaDownloadPdf', [ 'visa' => $order->id ] ) . '" class="btn btn-sm btn-brand"><i class="fa fa-download"></i></a>' : ( $order->status == '0' ? 'Pending' : $order->status );
				} )
				->rawColumns( [ 'status' ] )
				->make();
		}

		if ( $order_type == "charter" ) {
			$builder = $isAdmin ? CharterOrders::orderBy( 'id', 'DESC' ) : Auth::user()->charterPurchases()->orderBy( 'id', 'DESC' );

			return $datatables
				->of( $builder )
				->editColumn( 'flights', function ( CharterOrders $order ) {
					return '<button class="btn btn-sm btn-accent show-flights" data-id="' . $order->id . '"><strong>(' . $order->flights()->count() . ')</strong> <i class="fa fa-plane" style="top: 2px;position: relative;left: 2px;"></i></button>';
				} )
				->editColumn( 'user_id', function ( CharterOrders $order ) {
					return $order->user->name;
				} )
				->rawColumns( [ 'actions', 'passengers', 'flights' ] )
				->make();
		}

		if ( $order_type == "transactions" ) {
			$builder = $isAdmin ? Transaction::orderBy( 'id', 'DESC' ) : Auth::user()->userTransactions()->orderBy( 'id', 'DESC' );

			return $datatables
				->of( $builder )
				->editColumn( 'from', function ( Transaction $order ) {
					return $order->fromUser->name;
				} )
				->editColumn( 'to', function ( Transaction $order ) {
					return $order->toUser->name;
				} )
				->editColumn( 'type', function ( Transaction $order ) {
					return $order->type == "withdrawal" ? '<span class="text-danger">Withdrawal</span>' : '<span class="text-success">Deposit</span>';
				} )
				->editColumn( 'connectedID', function ( Transaction $order ) {
					if ( $order->connectedID ) {
						switch ( $order->connectedTable ) {
							case( "visa" ):
								return 'Visa # ' . $order->connectedID;
								break;
							case( "flight" ):
								return 'Flight # ' . $order->connectedID;
								break;
							case( "travel" ):
								return 'Travel # ' . $order->connectedID;
								break;
						}
					} else {
						return 'Direct Transaction';
					}
				} )
				->rawColumns( [ 'type' ] )
				->make();
		}


		return false;
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function search( Request $request ) {
//	    dd($request->all());
		$travels      = Auth::user()->travelPurchases()
		                    ->where( 'created_at', '>=', Carbon::parse( $request->from )->format( 'Y-m-d' ) )
		                    ->where( 'created_at', '<=', Carbon::parse( $request->to )->format( 'Y-m-d' ) )
		                    ->orderBy( 'id', 'DESC' )
		                    ->get();
		$flights      = Auth::user()->flightPurchases()
		                    ->where( 'created_at', '>=', Carbon::parse( $request->from )->format( 'Y-m-d' ) )
		                    ->where( 'created_at', '<=', Carbon::parse( $request->to )->format( 'Y-m-d' ) )
		                    ->orderBy( 'id', 'DESC' )
		                    ->get();
		$visas        = Auth::user()->visaPurchases()->whereDate( 'created_at', '=', $request->from )
		                    ->where( 'created_at', '>=', Carbon::parse( $request->from )->format( 'Y-m-d' ) )
		                    ->where( 'created_at', '<=', Carbon::parse( $request->to )->format( 'Y-m-d' ) )
		                    ->orderBy( 'id', 'DESC' )
		                    ->get();
		$transactions = Auth::user()->charterPurchases()
		                    ->where( 'created_at', '>=', Carbon::parse( $request->from )->format( 'Y-m-d' ) )
		                    ->where( 'created_at', '<=', Carbon::parse( $request->to )->format( 'Y-m-d' ) )

		                    ->orderBy( 'id', 'DESC' )
		                    ->get();
		$charters = Auth::user()->userTransactions()
                                ->where( 'created_at', '>=', Carbon::parse( $request->from )->format( 'Y-m-d' ) )
                                ->where( 'created_at', '<=', Carbon::parse( $request->to )->format( 'Y-m-d' ) )
                                ->orderBy( 'id', 'DESC' )
                                ->get();

		if ( Auth::user()->type === 'Super Admin' ) {
			$travels      = TravelOrders::where( 'created_at', '>=', Carbon::parse( $request->from )->format( 'Y-m-d' ) )
			                            ->where( 'created_at', '<=', Carbon::parse( $request->to )->format( 'Y-m-d' ) )
                                         ->where('user_id',$request->user_id)
                                        ->orderBy( 'id', 'DESC' )->get();
			$flights      = FlightOrders::where( 'created_at', '>=', Carbon::parse( $request->from )->format( 'Y-m-d' ) )
			                            ->where( 'created_at', '<=', Carbon::parse( $request->to )->format( 'Y-m-d' ) )
                                        ->where('user_id',$request->user_id)
                                        ->orderBy( 'id', 'DESC' )->get();
			$visas        = VisaOrders::where( 'created_at', '>=', Carbon::parse( $request->from )->format( 'Y-m-d' ) )
			                          ->where( 'created_at', '<=', Carbon::parse( $request->to )->format( 'Y-m-d' ) )
                                        ->where('user_id',$request->user_id)
                                        ->orderBy( 'id', 'DESC' )->get();
			$transactions = Transaction::where( 'created_at', '>=', Carbon::parse( $request->from )->format( 'Y-m-d' ) )
			                           ->where( 'created_at', '<=', Carbon::parse( $request->to )->format( 'Y-m-d' ) )
                                        ->where('from',$request->user_id)->orWhere('to',$request->user_id)
                                       ->orderBy( 'id', 'DESC' )->get();
            $charters = CharterOrders::where( 'created_at', '>=', Carbon::parse( $request->from )->format( 'Y-m-d' ) )
                ->where( 'created_at', '<=', Carbon::parse( $request->to )->format( 'Y-m-d' ) )
                ->where('user_id',$request->user_id)
                ->orderBy( 'id', 'DESC' )->get();
		}
 //dd($transactions,$travels,$flights,$visas,$charters);
		return view( 'user.search.search', compact( 'travels', 'flights', 'visas', 'transactions','charters' ) );
	}
}
