@extends('layouts.master-front')

@section('title')
    {{ __('alnkel.flights') }}
@endsection

@section('style')
    <style>
        .search {
            display: none;
        }
    </style>
@endsection

@section('page-header')
    <!-- Start page-heder -->
    <div class="page-header">
        <!-- Start container-fluid -->
        <div class="container-fluid">
            <!-- Start d-flex -->
            <div class="d-flex align-items-center justify-content-center">
                <i class="icons8-plane"></i>
                <span>{{ __('charter.my_locked') }}</span>
            </div>
            <!-- End d-flex -->
        </div>
        <!-- End container-fluid -->
    </div>
    <!-- End page-header -->
@endsection

@section('content')
    <!-- Start categorylist -->
    <div class="categorylist" style="margin: 20px 0">
        <!-- Start container-fluid -->
        <div class="container-fluid">
            <!-- Start row -->
            <div class="row">

                <table class="table table-sm charter-table" data-col="7">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('charter.reserve') }}</th>
                        <th scope="col">{{ __('charter.from') }}</th>
                        <th scope="col">{{ __('charter.to') }}</th>
                        <th scope="col">{{ __('charter.flight_number') }}</th>
                        <th scope="col">{{ __('charter.airline') }}</th>
                        <th scope="col">{{ __('charter.flight_date') }}</th>
                        <th scope="col">{{ __('charter.flight_time') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($flights as $item)
						<?php
						$index = 0;
						$flight = $item['flight'];
						?>
                        @foreach($flight->price as $day)
                            @if($index == $item['locked']->day)
								<?php
								$timestamp = strtotime( $day['date'] );
								$date = date( "d/m/Y", $timestamp );
								$day_name = __( 'days.' . date( 'l', $timestamp ) );

								$available_seats = $day['seats'];

								if ( isset( $day['reserved_seats'] ) ) {
									$available_seats = $day['seats'] - $day['reserved_seats'];
								}

								$commission = 0;
								if ( $flight->commission > 0 ) {
									$commissionObject = getCommission( $flight );
									$commission       = $commissionObject['commission'];

									if ( $commissionObject['is_percent'] ) {
										$commission = ( $day['adult'] * $commissionObject['commission'] ) / 100;
									}
								}

								$total_company = $day['adult'] - $commission;

								$reservedSeats = $item['locked']['seats'] - $item['locked']['reserved'];
								?>
                                @if($reservedSeats > 0 and $timestamp > time())
                                    <tr>
                                        <td data-time="{{$timestamp}}">
                                            <a href="#!" class="reserve btn btn-sm btn-warning" data-toggle="modal"
                                               data-target="#m_modal_3"
                                               data-id="{{ $item['locked']->charter_id }}"
                                               data-day="{{ $index }}"
                                               data-locked="{{$item['locked']->id}}"
                                            >
                                                {{__('charter.reserve')}}
                                            </a>

                                            <a class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top"
                                              >
                                                <i class="icons8-password" style="color: #fff"></i>
                                            </a>
                                        </td>
                                        <td>{{\App\Country::find( $flight->trip_information['common']['going']['from_country'] )->name[ App::getLocale() ]}}</td>
                                        <td>{{\App\Country::find( $flight->trip_information['common']['going']['to_country'] )->name[ App::getLocale() ]}}</td>
                                        <td>{{$flight->flight_number}}</td>
                                        <td>{{$flight->aircraft_operator[App::getLocale()]}}</td>
                                        <td>{{$day_name. ' ' .$date}}</td>
                                        <td>{{$day['time']}}</td>
                                    </tr>
                                @endif
                            @endif
							<?php $index ++; ?>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!--begin::Modal-->
        <div class="modal fade" id="m_modal_3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="reserve-form" action="">
                        <input type="hidden" name="locked" value="0">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                {{__("charter.select_passengers")}}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="day">
                                <!-- Start input-gp -->
                                <div class="col-md-3">
                                    <label for="">{{ __('charter.adult') }}:</label>
                                    <select class="passengers" name="adult">
                                        @for($i=0;$i<=9;$i++)
                                            <option value="{{ $i }}" {{ old('adult') == $i or $i == 1 ? 'selected' : ''}}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <!-- End input-gp -->
                                <!-- Start input-gp -->
                                <div class="col-md-3">
                                    <label for="">{{ __('charter.children') }}:</label>
                                    <select class="passengers" name="children">
                                        @for($i=0;$i<=5;$i++)
                                            <option value="{{ $i }}" {{ old('children') == $i ? 'selected' : ''}}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <!-- End input-gp -->
                                <!-- Start input-gp -->
                                <div class="col-md-3">
                                    <label for="">{{ __('charter.baby') }}:</label>
                                    <select class="passengers" name="baby">
                                        @for($i=0;$i<=5;$i++)
                                            <option value="{{ $i }}" {{ old('baby') == $i ? 'selected' : ''}}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <!-- End input-gp -->
                                <!-- Start input-gp -->
                                <div class="col-md-3">
                                    <label for="">{{ __('charter.business') }}:</label>
                                    <select class="passengers" name="business">
                                        @for($i=0;$i<=9;$i++)
                                            <option value="{{ $i }}" {{ old('business') == $i or $i == 1 ? 'selected' : ''}}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <!-- End input-gp -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                Close
                            </button>

                            <button type="submit" class="reserve-btn btn btn-warning">
                                {{__('charter.reserve')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--end::Modal-->

        <!-- End container-fluid -->
    </div>
    <!-- End categorylist -->
@endsection

@section('scripts')
    <script>
        $('#charter-tab').trigger('click');

        $('.reserve').on('click', function () {
            var day = $(this).data("day");
            var id = $(this).data("id");
            var locked = $(this).data("locked");
            var action = "{{ route('charter-pre-checkout',['flight' => 'xx']) }}";

            $("[name=day]").val(day);
            $("[name=locked]").val(locked);

            $('#reserve-form').attr('action', action.replace("xx", id));
        });

        $('.reserve-btn').on('click', function (e) {
            e.preventDefault();

            var adult = $('#reserve-form [name=adult]').val();
            var business = $('#reserve-form [name=business]').val();
            var children = $('#reserve-form [name=children]').val();
            var baby = $('#reserve-form [name=baby]').val();

            if (adult == 0 && business == 0 && children == 0 && baby == 0) {
                alert("{{__("charter.select_min_one")}}");
                return false;
            }

            $('#reserve-form').submit();
            return true;
        });

        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endsection