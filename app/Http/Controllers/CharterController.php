<?php

namespace App\Http\Controllers;

use App\Aircraft;
use App\CharterPassengersRelated;
use App\SpecialCommission;
use App\CharterOrder;
use App\CharterOrderFlights;
use App\CharterPassengers;
use App\Country;
use App\Charter;
use App\CharterOrders;
use App\DownloadExport;
use App\ExportSingleOrder;
use App\Http\Requests\editCharterOrderRequest;
use App\Http\Requests\StoreCharterRequest;
use App\Http\Requests\UpdateCharterRequest;
use App\Locked;
use App\Nationality;
use App\Notifications\Notifier;
use App\User;
use App\Visa;
use App\VisaOrders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class CharterController extends Controller {

	public function __construct() {
		$this->middleware( [ 'auth', 'dashboardAccess' ] );
	}

	public function index() {
		Session::flash( 'sidebar', 'charter' );
		$charters = Charter::all();

		return view( 'admin.charter.index', compact( 'charters' ) );
	}

	public function charterData( Datatables $datatables, Request $request ) {
		$builder = Charter::orderBy( 'id', 'desc' );

		return $datatables
			->of( $builder )
			->addColumn( 'special_commission', function ( Charter $charter ) {
				return '<a href="' . route( 'charterCommission', [ 'charter' => $charter->id ] ) . '" class="btn btn-brand btn-sm">Special Commission</a>';
			} )
			->addColumn( 'seats', function ( Charter $charter ) {
				return 'E:' . $charter->economy_seats . ' | B:' . $charter->business_seats;
			} )
			->addColumn( 'locked_seats', function ( Charter $charter ) {
				return '<a href="' . route( 'charterLocked', [ 'charter' => $charter->id ] ) . '" class="btn btn-info btn-sm">Locked Seats</a>';
			} )
			->addColumn( 'actions', function ( Charter $charter ) {
				return '<a href="' . route( 'charterOrders', [ 'charter' => $charter->id ] ) . '" class="btn btn-success btn-sm">PASSENGERS</a>
                            <a href="' . route( 'editCharter', [ 'charter' => $charter->id ] ) . '" class="btn btn-info btn-sm">Edit</a>
                            <button data-url="' . route( 'deleteCharter', [ 'charter' => $charter->id ] ) . '" class="delete-modal btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-charter"><i class="fa fa-trash" style="font-size: 14px;"></i></button>
                            <a href="' . route( 'lockCharter', [ 'charter' => $charter->id ] ) . '" class="btn btn-brand btn-sm"><i class="fa fa-'.($charter->locked ? 'unlock' : 'lock').'"></i> '.($charter->locked ? 'Unlock' : 'Lock').'</a>';
			} )
			->editColumn( 'economy_seats', function ( Charter $charter ) {
				$economy_seats = 0;
				$economy_orders              = $charter->orders()->where( 'flight_class', 'Economy' )->where('status', '!=', 'cancelled')->get();

				foreach ( $economy_orders as $economy_order ) {
					$economy_seats += $economy_order->passengers()->count();
				}

				return $charter->economy_seats ? "<ul><li>{$economy_seats}</li><li>".($charter->economy_seats - $economy_seats)."</li><li>{$charter->economy_seats}</li></ul>" : 0;
			} )
			->editColumn( 'business_seats', function ( Charter $charter ) {
				$business_seats = 0;
				$business_orders              = $charter->orders()->where( 'flight_class', 'Business' )->where('status', '!=', 'cancelled')->get();

				foreach ( $business_orders as $business_order ) {
					$business_seats += $business_order->passengers()->count();
				}

				return $charter->business_seats ? "<ul><li>{$business_seats}</li><li>".($charter->business_seats - $business_seats)."</li><li>{$charter->business_seats}</li></ul>" : 0;
			} )
			->editColumn( 'commission', function ( Charter $charter ) {
				return $charter->commission . ( $charter->is_percent ? '%' : '$' );
			} )
			->editColumn( 'flight_type', function ( Charter $charter ) {
				return $charter->flight_type == "RoundTrip" ? "Round Trip" : "One Way";
			} )
			->rawColumns( [ 'special_commission', 'locked_seats', 'actions', 'economy_seats', 'business_seats' ] )
			->filter(function ($query) {
				if (request('flight_type')) {
					$query->where('flight_type', request('flight_type'));
				}

				if (request('from_date')) {
					$query->where('flight_date', '>=', request('from_date'));
				}

				if (request('to_date')) {
					$query->where('flight_date', '<=', request('to_date'));
				}
			}, true)
			->make();
	}

	public function show( Charter $charter ) {
		Session::flash( 'sidebar', 'charter' );

		return response()->json( [ 'status' => 'success', 'charter' => $charter ] );
	}

	public function create() {
		Session::flash( 'sidebar', 'charter' );
		$countries = Country::all();
		$aircrafts = Aircraft::all();

		return view( 'admin.charter.create', compact( 'countries', 'aircrafts' ) );
	}

	public function store( Request $request ) {
		$charter = Charter::create( $request->all() );

		if ( $request->post( 'flight_type' ) == "RoundTrip" ) {
			$charter->roundtrip->create( [
				'flight_number'  => $request->post( '2way_flight_number' ),
				'flight_date'    => $request->post( '2way_flight_date' ),
				'departure_time' => $request->post( '2way_departure_time' ),
				'arrival_time'   => $request->post( '2way_arrival_time' ),
				'charter_id'     => $charter->id,
			] );
		}

		return redirect()->back()->with( [ 'success' => 'Charter Created Successfully.' ] );
	}


	public function edit( Charter $charter ) {
		Session::flash( 'sidebar', 'charter' );
		$countries = Country::all();
		$aircrafts = Aircraft::all();

		$pricing = [
			[ "title" => "Economy", "special" => "One Way", "name" => "price_[age]" ],
			[ "title" => "Economy", "special" => "Two Way", "name" => "price_[age]_2way" ],
			["separator" => true],
			[ "title" => "Business", "special" => "One Way", "name" => "business_[age]" ],
			[ "title" => "Business", "special" => "Two Way", "name" => "business_2way_[age]" ],
			["separator" => true],
			[ "title" => "Open Return", "special" => "1 Month", "name" => "open_return_1month_[age]" ],
			[ "title" => "Open Return", "special" => "3 Month", "name" => "open_return_3month_[age]" ],
			[ "title" => "Open Return", "special" => "6 Month", "name" => "open_return_6month_[age]" ],
			[ "title" => "Open Return", "special" => "1 Year", "name" => "open_return_1year_[age]" ],
		];

		$ages = ["adult", "child", "baby"];

		return view( 'admin.charter.update', compact( 'charter', 'countries', 'aircrafts', 'ages', 'pricing' ) );
	}

	public function update( Charter $charter, Request $request ) {
		$charter->update( $request->all() );

		if ( $request->post( 'flight_type' ) == "RoundTrip" ) {
			$data = [
				'flight_number'  => $request->post( '2way_flight_number' ),
				'flight_date'    => $request->post( '2way_flight_date' ),
				'departure_time' => $request->post( '2way_departure_time' ),
				'arrival_time'   => $request->post( '2way_arrival_time' ),
				'charter_id'     => $charter->id,
			];


			$charter->roundtrip ? $charter->roundtrip->update( $data ) : $charter->roundtrip()->create( $data );
		} else {
			$charter->roundtrip ? $charter->roundtrip->delete() : false;
		}


		return redirect()->back()->with( [ 'success' => 'Charter Updated Successfully.' ] );
	}

	public function lockCharter( Charter $charter ) {
		$message = $charter->locked == 1 ? 'Charter UnLocked Successfully.' : 'Charter Locked Successfully.';

		$charter->locked = $charter->locked == 1 ? 0 : 1;
		$charter->save();

		return redirect()->route( 'listCharter' )->with( [ 'success' => $message ] );
	}

	public function destroy( Charter $charter ) {
		$charter->delete();

		return redirect()->route( 'listCharter' )->with( [ 'success' => 'Charter Deleted Successfully.' ] );
	}

	############### Commission ###########################
	public function commission( Charter $charter ) {
		$excluded  = SpecialCommission::where( 'charter_id', $charter->id )->pluck( 'user_id' );
		$companies = User::whereNotIn( 'id', $excluded )->get();

		return view( 'admin.charter.commission', compact( 'charter', 'companies' ) );
	}

	public function storeCommission( Charter $charter, Request $request ) {
		SpecialCommission::create( $request->all() );

		return redirect()->back()->with( [ 'success' => 'Commission has been added Successfully.' ] );
	}

	public function commissionData( Datatables $datatables, Charter $charter, Request $request ) {
		$builder = SpecialCommission::where( 'charter_id', $charter->id )->orderBy( 'id', 'desc' );

		return $datatables
			->of( $builder )
			->editColumn( 'user_id', function ( SpecialCommission $commission ) {
				return User::find( $commission->user_id )->name;
			} )
			->editColumn( 'commission', function ( SpecialCommission $commission ) {
				return $commission->commission . ( $commission->is_percent ? '%' : '$' );
			} )
			->addColumn( 'actions', function ( SpecialCommission $commission ) use ( $charter ) {
				return '<button data-url="' . route( 'deleteCommission', [
						'charter'    => $charter->id,
						'commission' => $commission->id
					] ) . '" class="delete-modal btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-commission"><i class="fa fa-trash" style="font-size: 14px;"></i></button>';
			} )
			->rawColumns( [ 'actions' ] )
			->make();
	}

	public function deleteCommission( Charter $charter, SpecialCommission $commission ) {
		$commission->delete();

		return redirect()->back()->with( [ 'success' => 'Commission has been deleted Successfully.' ] );
	}

	############### End Commission ###########################

	############### Locked ###########################

	public function locked( Charter $charter ) {
		$companies = User::all();
		return view( 'admin.charter.locked', compact( 'charter', 'companies' ) );
	}

	public function storeLocked( Charter $charter, Request $request ) {
		$check = Locked::where([
			"user_id" => $request->user_id,
			"charter_id" => $charter->id
		])->first();

		if($check) {
			$check->update([
				"seats" => $check->seats + $request->seats,
				"price" => $request->price
			]);
		}else{
			Locked::create( $request->all() );
		}

		// Save to transactions
		$price       = $request->price;
		$user        = User::find( $request->user_id );
		$new_balance = $user->balance - $price;
		$user->userTransactions()->create( [
			'to'             => $user->id,
			'amount'         => $price,
			'comment'        => "Charter Locked Seats",
			'type'           => "withdrawal",
			'creditBefore'   => $user->balance,
			'creditAfter'    => $new_balance,
			'connectedID'    => $charter->id,
			'connectedTable' => 'charter'
		] );

		$user->update( [ 'balance' => $new_balance ] );

		return redirect()->back()->with( [ 'success' => 'Locked seats have been added successfully.' ] );
	}

	public function lockedData( Datatables $datatables, Charter $charter, Request $request ) {
		$builder = Locked::where( 'charter_id', $charter->id )->orderBy( 'id', 'desc' );

		return $datatables
			->of( $builder )
			->editColumn( 'user_id', function ( Locked $commission ) {
				return User::find( $commission->user_id )->name;
			} )
			->addColumn( 'actions', function ( Locked $locked ) use ( $charter ) {
				return '<a href="' . route( 'deleteLocked', [
						'charter'    => $charter->id,
						'locked' => $locked->id
					] ) . '" class="confirm-link btn btn-danger btn-sm"><i class="fa fa-trash" style="font-size: 14px;"></i></a>';
			} )
			->rawColumns( [ 'actions' ] )
			->make();
	}

	public function deleteLocked( Charter $charter, Locked $locked ) {
		$locked->delete();

		return redirect()->back()->with( [ 'success' => 'Locked seats has been deleted Successfully.' ] );
	}

	################## End Locked #################
	public function prices( Charter $charter ) {
		return view( 'admin.charter.prices', compact( 'charter' ) );
	}

	################## Orders #################
	public function charterOrders( Charter $charter, CharterOrders $order, Request $request ) {
		$stats = [];

		// Economy seats
		$stats['total_economy_seats'] = $charter->economy_seats;

		$stats['sold_economy_seats'] = 0;
		$economy_orders              = $charter->orders()->where('status', '!=', 'cancelled')->where( 'flight_class', 'Economy' )->get();
		foreach ( $economy_orders as $economy_order ) {
			$stats['sold_economy_seats'] += $economy_order->passengers()->count();
		}

		// Business seats
		$stats['total_business_seats'] = $charter->business_seats;

		$stats['sold_business_seats'] = 0;
		$business_orders              = $charter->orders()->where('status', '!=', 'cancelled')->where( 'flight_class', 'Business' )->get();
		foreach ( $business_orders as $business_order ) {
			$stats['sold_business_seats'] += $business_order->passengers()->count();
		}

		// Amount
		$stats['total_amount']     = $charter->orders()->sum( 'price' );
		$stats['total_commission'] = $charter->orders()->sum( 'commission' );
		$stats['total_profit']     = $stats['total_amount'] - $stats['total_commission'];


		return view( 'admin.orders.charter.charter', compact( 'charter', 'stats' ) );
	}

	public function charterOrdersData( Datatables $datatables, Charter $charter, Request $request ) {
		$builder = $charter->orders()->orderBy( 'id', 'DESC' )->get();

		return $datatables
			->of( $builder )
			->editColumn( 'status', function ( CharterOrders $order ) {
				return ucfirst($order->status);
			} )
			->editColumn( 'flights', function ( CharterOrders $order ) {
				return '<button class="btn btn-sm btn-accent show-flights" data-id="' . $order->id . '"><strong>(' . $order->flights()->count() . ')</strong> <i class="fa fa-plane" style="top: 2px;position: relative;left: 2px;"></i></button>';
			} )
			->editColumn( 'user_id', function ( CharterOrders $order ) {
				return $order->user->name;
			} )
			->addColumn( 'passengers', function ( CharterOrders $order ) use ( $charter ) {
				return '<a href="' . route( 'charterOrders', [ 'charter' => $charter->id ] ) . '" class="btn btn-success btn-sm">(' . $order->passengers()->count() . ') PASSENGERS</a> <a href="' . route( 'download-charter-ticket', [ 'pnr' => $order->pnr ] ) . '" class="btn btn-brand btn-sm"><i class="fa fa-ticket" style="font-size: 14px;"></i></a> <a href="' . route( 'chartOrdersDownload', [ 'charter' => $charter->id ] ) . '?order=' . $order->id . '" class="btn btn-brand btn-sm"><i class="fa fa-download" style="font-size: 14px;"></i></a>';
			} )
			->addColumn( 'actions', function ( CharterOrders $order ) use ( $charter ) {
				$buttons[] = '<a href="' . route( 'editCharterOrder', [ 'charter' => $charter->id, 'order' => $order->id ] ) . '" class="btn btn-info btn-sm">Edit</a> ';
				if($order->status == "cancelled") {
					$buttons[] = '<button data-href="' . route( 'cancel-charter-ticket', [
							'charter' => $charter->id,
							'order'   => $order->id,
						] ) . '" class="confirm-cancel btn btn-success btn-sm" data-id="'.$order->id.'" data-text="rebook" data-title="Rebook Order">ReBook</i></button>';
				}else{
					$buttons[] = '<button data-href="' . route( 'cancel-charter-ticket', [
							'charter' => $charter->id,
							'order'   => $order->id
						] ) . '" class="confirm-cancel btn btn-danger btn-sm" data-id="'.$order->id.'" data-text="refund" data-title="Cancel Order">Cancel</i></button>';
				}

				return join($buttons);
			} )
			->rawColumns( [ 'actions', 'passengers', 'flights' ] )
			->make();
	}

	public function charterPassengersData( Datatables $datatables, Charter $charter, CharterOrders $order ) {
		$builder = $order->passengers()->orderBy( 'id', 'DESC' )->get();

		return $datatables
			->of( $builder )
			->addColumn( 'actions', function ( CharterPassengers $passenger ) use ( $charter, $order ) {
				return '<a href="' . route( 'editCharterOrder', [
						'charter'   => $charter->id,
						'order'     => $order->id,
						'passengcancelCharterer' => $passenger->id
					] ) . '" class="btn btn-info btn-sm">Edit</a>
                            <button data-url="' . route( 'deleteCharter', [ 'charter' => $passenger->id ] ) . '" class="delete-modal btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-charter" hidden><i class="fa fa-trash" style="font-size: 14px;"></i></button>';
			} )
			->addColumn( 'check', function ( CharterPassengers $passenger ) use ( $charter, $order ) {
				return '<label class="mt-checkbox mt-checkbox-outline"><input type="checkbox" name="selected_passengers" value="' . $passenger->id . '" /><span></span></label>';
			} )
			->rawColumns( [ 'actions', 'check' ] )
			->make();
	}

	public function charterOrderFlights( Request $request ) {
		$order   = $request->get( 'order' );
		$flights = CharterOrderFlights::where( 'order_id', $order )->with( 'charter' )->get();

		return json_encode( $flights );
	}

	public function chartOrdersDownload( Charter $charter, Request $request ) {
		$order_id = $request->get( 'order' );

		if ( $order_id ) {
			$order    = CharterOrders::find( $order_id );
			$download = ( new ExportSingleOrder( $order ) );
		} else {
			$download = ( new DownloadExport( $charter ) );
		}

		return $download->download( 'PassengersData.xlsx' );
	}
	################## End Orders ###############

	/**
	 * @param Charter $charter
	 * @param CharterOrders $order
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function changeCharterStatus( Charter $charter, CharterOrders $order, Request $request ) {
		if ( $order->status !== 'rejected' ) {
			$this->validate( $request, [
				'pdf' => 'required|mimes:pdf'
			], [
				'pdf.mimes' => 'Charter document must be a file of type: pdf.'
			] );

			$pdf = Storage::disk( 'public' )->put( 'charter/pdf', $request->file( 'pdf' ) );

			$order->update( [ 'status' => '1', 'delivered_by' => Auth::user()->id, 'charter_pdf' => $pdf ] );

			Notification::send( User::find( $order->user_id ), new Notifier( [
				'message' => 'your charter ticket has been delivered',
				'url'     => route( 'listUserCharters' )
			], 'Charter Ticket Request' ) );
		} else {
			return redirect()->back()->with( [ 'fail' => 'charter already rejected!' ] );
		}

		return redirect()->back()->with( [ 'success' => 'charter approved successfully!' ] );
	}

	public function cancelCharterTicket( Charter $charter, Request $request ) {

		$order_id = $request->get('order');
		$order = CharterOrders::find($order_id);
		$isCancelled = $order->status == "cancelled";

		$cancel_all = $request->post("cancel_all");

		$amount = $request->post("amount");

		$flights = $request->post('flights');
		$passengers = $request->post('passengers');

		if($cancel_all == 0 and !$isCancelled) {
			// Cancel flights
			if(count($flights) > 0) {
				foreach($flights as $flight) {
					// Remove flights from order flights
					CharterOrderFlights::where([
						'order_id' => $order_id,
						'charter_id' => $flight
					])->delete();

					// Remove flights from passengers related
					CharterPassengersRelated::where([
						'order_id' => $order_id,
						'flight_id' => $flight
					])->delete();
				}
			}

			// Cancel passengers
			if(count($passengers) > 0) {
				foreach ($passengers as $passenger) {
					// Remove passenger from passengers
					CharterPassengers::where('id', $passenger)->delete();

					// Remove passenger from passengers related
					CharterPassengersRelated::where([
						'order_id' => $order_id,
						'passenger_id' => $passenger
					])->delete();
				}
			}
		}else{
			$order->update( [ 'status' => $isCancelled ? 'awaiting' : 'cancelled', 'delivered_by' => Auth::user()->id ] );
		}

		// Save to transactions
		$user        = User::find( $order->user_id );
		$new_balance = $isCancelled ? ($user->balance - $amount) : $user->balance + $amount;
		$user->userTransactions()->create( [
			'to'             => $user->id,
			'amount'         => $amount,
			'comment'        => $isCancelled ? "Charter ReBook" : "Refund charter",
			'type'           => $isCancelled ? "withdrawal" : "DepositOfCredit",
			'creditBefore'   => $user->balance,
			'creditAfter'    => $new_balance,
			'connectedID'    => $order->id,
			'connectedTable' => 'charter'
		] );

		$user->update( [ 'balance' => $new_balance ] );

		return redirect()->back()->with( [ 'success' => 'Charter order has been cancelled!' ] );
	}

	/**
	 * @param Charter $charter
	 * @param CharterOrders $order
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function changeCharterStatusToReject( Charter $charter, CharterOrders $order ) {
		if ( $order->status === '0' || $order->status === 'received' ) {
			$order->update( [ 'status' => 'rejected' ] );

			Notification::send( User::find( $order->user_id ), new Notifier( [
				'message' => 'your charter ticket request has been rejected',
				'url'     => route( 'listUserCharters' )
			], 'Charter Ticket Request' ) );
		} else {
			return redirect()->back()->with( [ 'fail' => 'charter already submitted!' ] );
		}

		return redirect()->back()->with( [ 'success' => 'charter rejected successfully!' ] );
	}

	/**
	 * @param Charter $charter
	 * @param CharterOrders $order
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function changeCharterStatusToReceive( Charter $charter, CharterOrders $order ) {
		if ( $order->status === '0' ) {
			$order->update( [ 'status' => 'received' ] );

			Notification::send( User::find( $order->user_id ), new Notifier( [
				'message' => 'your charter ticket request has been received',
				'url'     => route( 'listUserCharters' )
			], 'Charter Ticket Request' ) );
		} else {
			return redirect()->back()->with( [ 'fail' => 'charter already submitted!' ] );
		}

		return redirect()->back()->with( [ 'success' => 'charter canceled successfully!' ] );
	}

	public function editCharterOrder( Charter $charter, CharterOrders $order, Request $request ) {
		$countries = Nationality::all();

		$isEdit    = false;
		$passenger = CharterPassengers::first();

		if ( $request->get( 'passenger' ) ) {
			$isEdit    = true;
			$passenger = CharterPassengers::find( $request->get( 'passenger' ) );
		}

		if ( $request->get( 'do' ) == "split" ) {
			$passengers = $request->get( "passengers" );

			if ( ! $passengers or count( $passengers ) == 0 ) {
				return 'Error';
			}
            $priceForOne=$order->flight_class=='Economy'?$charter->price_adult:$charter->business_adult;
			//dd($priceForOne);
            $number=$request->get( "number" );
			$count=count($passengers);
          //  $priceForOne=$order->price/$number;
            $subPrice=$order->price - ($priceForOne*$count);
            //dd($priceForOne);
            $order->update(['price'=>$subPrice]);

			$pnr = generatePNR();
			$newPrice=$priceForOne*$count;

			$newOrder      = $order->replicate();
			$newOrder->pnr = $pnr;
			$newOrder->price = $newPrice;
			$newOrder->save();

			foreach ( $passengers as $passenger_id ) {
				$passenger           = CharterPassengers::find( $passenger_id );
				$passenger->order_id = $newOrder->id;
				$passenger->save();
			}

			Session::flash( 'success', 'Passenger has been transferred to new order successfully with PNR: ' . $pnr );

			return 'Done';
		}

		return view( 'admin.orders.charter.edit', compact( 'charter', 'order', 'countries', 'isEdit', 'passenger' ) );
	}

	/**
	 * @param Charter $charter
	 * @param CharterOrders $order
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function editOrder( Charter $charter, CharterOrders $order, Request $request ) {
		$do      = $request->post( "do" );
		$message = '';

		if ( $do == "passenger" ) {
			$message   = 'Passenger details has been updated successfully!';
			$passenger = CharterPassengers::find( $request->post( 'passenger' ) );
			$passenger->update( $request->all() );
		}

		if ( $do == "change" ) {
			$message = 'Order details has been updated successfully!';

			$newClass   = $request->post( "flight_class" );
			$flights    = $order->flights;
			$passengers = $order->passengers;

			$price = 0;

			foreach ( $flights as $flight ) {
				if ( $request->post( "flight_" . $order->charter_id ) == $request->post( "flight_" . $flight->charter_id ) ) {
					$order->charter_id = $request->post( "flight_" . $flight->charter_id );
				}

				$flight->charter_id = $request->post( "flight_" . $flight->charter_id );
				$flight->save();
			}

			foreach ( $passengers as $passenger ) {
				$flight_price = floatval( $request->post( "modified_price_" . $passenger->id ) );
				$price        += $flight_price;

				// Change passenger price
				$modifiedPassenger        = CharterPassengers::find( $passenger->id );
				$modifiedPassenger->price = $flight_price;
				$modifiedPassenger->save();
			}

			// Change order price
			$order->price        = $price;
			$order->flight_class = $newClass;
			$order->save();
		}

		return redirect()->route( 'editCharterOrder', [
			'charter' => $charter->id,
			'order'   => $order->id
		] )->with( [ 'success' => $message ] );
	}
}
