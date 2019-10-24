@extends('layouts.master')

@section('page-title')
    Edit Flight
@endsection

@section('sub-header')
    Edit Flight
@endsection

@section('content')
    @if(session()->has('success'))
        <div class="alert m-alert m-alert--default alert-success" role="alert">
            {{session()->get('success') }}
        </div>
    @endif
    @if(count($errors) > 0)
        <div class="m-alert m-alert--icon alert alert-danger" role="alert" id="m_form_1_msg">
            <div class="m-alert__icon">
                <i class="la la-warning"></i>
            </div>
            <div class="m-alert__text">
                Oh snap! Change a few things up and try submitting again.
            </div>
            <div class="m-alert__close">
                <button type="button" class="close" data-close="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <a href="{{route('deleteCharter', ['charter' => $charter->id])}}"
                           class="btn btn-danger pull-right mt-3">
                            <i class="fa fa-close"></i> Archive Flight
                        </a>

                        <a href="{{route('lockCharter', ['charter' => $charter->id])}}"
                           class="btn btn-brand pull-right mt-3 mr-2">
                            <i class="fa fa-{{$charter->locked ? "unlock" : "lock"}}"></i> {{$charter->locked ? "Unlock" : "Lock"}}
                            Charter
                        </a>

                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                             </span>
                            <h3 class="m-portlet__head-text">
                                Flight Details.
                            </h3>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="m-form m-form--label-align-right m-form--group-seperator-dashed"
                      method="post"
                      action="{{ route('updateCharter',['charter' => $charter->id]) }}" enctype="multipart/form-data">

                    <!--begin::Portlet-->
                    <div class="m-portlet">
                        <div class="m-portlet__body">
                            <div class="form-group m-form__group row going-section">
                                <div class="col-lg-12 mb-2">
                                    <h4>Main Information</h4>
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label>
                                        Flight <span class="text-danger">Name</span>:
                                    </label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" name="name"
                                               placeholder="Enter flight name" class="form-control m-input"
                                               value="{{ old('name', $charter->name) }}">
                                    </div>
                                    @if(isset($errors->messages()['name']))
                                        <div class="form-control-feedback" style="color: #f4516c;">
                                            {{  $errors->messages()['name'][0] }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-lg-3">
                                    <label><span class="text-danger">Flight</span> number</label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <span class="input-group-addon"><i class="fa fa-plane"></i></span>
                                        <input type="text" name="flight_number" class="form-control m-input"
                                               value="{{ old('flight_number', $charter->flight_number) }}"
                                               placeholder="Enter flight number">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label style="display: table">
                                        From where <span class="text-danger">to</span> where
                                    </label>

                                    <div class="input-group m-input-group m-input-group--square pull-left"
                                         style="width: 50%">
                                        <span class="input-group-addon"><i class="fa fa-plane"></i></span>
                                        <select class="form-control select2" id="m_select2_1"
                                                name="from_where">
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}"
                                                        {{ old('from_where', $charter->from_where) === $country->id ? 'selected' : ''}}
                                                >{{ $country->name['ar'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if(isset($errors->messages()['from_where']))
                                        <div class="form-control-feedback" style="color: #f4516c;">
                                            {{  $errors->messages()['from_where'][0] }}
                                        </div>
                                    @endif

                                    <div class="input-group m-input-group m-input-group--square" style="width: 50%">
                                        <span class="input-group-addon"> to </span>

                                        <select class="form-control select2" id="m_select2_1"
                                                name="to_where">
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}"
                                                        {{ old('to_where', $charter->to_where) === $country->id ? 'selected' : ''}}
                                                >{{ $country->name['ar'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if(isset($errors->messages()['to_where']))
                                        <div class="form-control-feedback" style="color: #f4516c;">
                                            {{  $errors->messages()['to_where'][0] }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label><span class="text-danger">Flight</span> aircraft</label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <span class="input-group-addon"><i class="fa fa-plane"></i></span>
                                        <select class="form-control select2" id="m_select2_1"
                                                name="aircraft_id">
                                            @foreach($aircrafts as $aircraft)
                                                <option value="{{ $aircraft->id }}" {{ old('aircraft_id', $charter->aircraft) === $aircraft->id ? 'selected' : ''}}>
                                                    {{ $aircraft->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <label><span class="text-danger">Flight</span> Date</label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="date" name="flight_date" class="form-control m-input date-picker"
                                               value="{{ old('flight_date', $charter->flight_date) }}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label><span class="text-danger">Flight</span> departure time</label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                        <input type="text" name="departure_time" class="form-control m-input timer"
                                               value="{{ old('departure_time', $charter->departure_time) }}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label><span class="text-danger">Flight</span> arrival time</label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                        <input type="text" name="arrival_time" class="form-control m-input timer"
                                               value="{{ old('arrival_time', $charter->arrival_time) }}">
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <label>Flight <span class="text-danger">economy</span> seats</label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <span class="input-group-addon">+</span>
                                        <input type="number" name="economy_seats" class="form-control m-input"
                                               value="{{ old('economy_seats', $charter->economy_seats) }}">
                                        <span class="input-group-addon">SEAT</span>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <label>Flight <span class="text-danger">business</span> seats</label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <span class="input-group-addon">+</span>
                                        <input type="number" name="business_seats" class="form-control m-input"
                                               value="{{ old('business_seats', $charter->business_seats) }}">
                                        <span class="input-group-addon">SEAT</span>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3"></div>

                                <div class="col">
                                    <label>Can <span class="text-danger">cancel?</span></label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <select name="can_cancel" class="form-control m-input">
                                            <option value="0" {{ old('can_cancel', $charter->can_cancel) == "0" ? 'selected' : ''}}>
                                                No
                                            </option>
                                            <option value="1" {{ old('can_cancel', $charter->can_cancel) == "1" ? 'selected' : ''}}>
                                                Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col">
                                    <label>Cancel <span class="text-danger">fees</span></label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input type="number" name="cancel_fees" class="form-control m-input"
                                               value="{{ old('cancel_fees', $charter->cancel_fees) }}">
                                    </div>
                                </div>

                                <div class="col">
                                    <label>Can <span class="text-danger">change?</span></label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <select name="can_change" class="form-control m-input">
                                            <option value="0" {{ old('can_change', $charter->can_change) == "0" ? 'selected' : ''}}>
                                                No
                                            </option>
                                            <option value="1" {{ old('can_change', $charter->can_change) == "1" ? 'selected' : ''}}>
                                                Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col">
                                    <label>Change <span class="text-danger">fees</span></label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input type="number" name="change_fees" class="form-control m-input"
                                               value="{{ old('change_fees', $charter->change_fees) }}">
                                    </div>
                                </div>

                                <div class="col">
                                    <label>Show in <span class="text-danger">home</span>?</label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <select name="show_in_home" class="form-control m-input">
                                            <option value="0" {{ old('show_in_home', $charter->show_in_home) == "0" ? 'selected' : ''}}>
                                                No
                                            </option>
                                            <option value="1" {{ old('show_in_home', $charter->show_in_home) == "1" ? 'selected' : ''}}>
                                                Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col">
                                    <label>Flight <span class="text-danger">type</span></label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <select name="flight_type" class="form-control m-input">
                                            <option value="OneWay" {{ old('flight_type', $charter->flight_type) == 'OneWay' ? 'selected' : ''}}>
                                                One Way
                                            </option>
                                            <option value="RoundTrip" {{ old('flight_type', $charter->flight_type) == 'RoundTrip' ? 'selected' : ''}}>
                                                Round Trip
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-2 round-trip" hidden>
                                    <hr/>

                                    <h4>Round Trip Details</h4>
                                </div>

                                <div class="col-lg-3 round-trip" hidden>
                                    <label><span class="text-danger">Flight</span> number</label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <span class="input-group-addon"><i class="fa fa-plane"></i></span>
                                        <input type="text" name="2way_flight_number" class="form-control m-input"
                                               value="{{ old('2way_flight_number', $charter->roundtrip ? $charter->roundtrip->flight_number : '') }}"
                                               placeholder="Enter flight number">
                                    </div>
                                </div>
                                <div class="col-lg-3 round-trip" hidden>
                                    <label><span class="text-danger">Flight</span> Date</label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="date" name="2way_flight_date"
                                               class="form-control m-input date-picker"
                                               value="{{ old('2way_flight_date', $charter->roundtrip ? $charter->roundtrip->flight_date  : '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 round-trip" hidden>
                                    <label><span class="text-danger">Flight</span> departure time</label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                        <input type="text" name="2way_departure_time" class="form-control m-input timer"
                                               value="{{ old('2way_departure_time', $charter->roundtrip ? $charter->roundtrip->departure_time  : '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-23 round-trip" hidden>
                                    <label><span class="text-danger">Flight</span> arrival time</label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                        <input type="text" name="2way_arrival_time" class="form-control m-input timer"
                                               value="{{ old('2way_arrival_time', $charter->roundtrip ? $charter->roundtrip->arrival_time : '') }}">
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-2">
                                    <hr/>

                                    <h4>Pricing</h4>
                                </div>

                                <div class="col-lg-12 mb-2 mt-4">
                                    <table class="table table-bordered mb-3">
                                        <tr class="bg-light">
                                            <th scope="col"></th>
                                            <th scope="col">Adult Price</th>
                                            <th scope="col">Child Price</th>
                                            <th scope="col">Baby Price</th>
                                        </tr>
                                        @foreach($pricing as $price)
                                            @if(isset($price['separator']))
                                                <tr>
                                                    <td colspan="4" class="p-1" style="
    background: #dadde2;
"></td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td class="bg-light">
                                                        <h6 class="mt-2"><span
                                                                    class="text-info">{{$price['title']}} <span
                                                                        class="text-danger">({{$price['special']}})</span></span>
                                                        </h6>
                                                    </td>
                                                    @foreach($ages as $age)
                                                        @php
                                                            $name = str_replace("[age]", $age, $price['name']);
                                                        @endphp
                                                        <td>
                                                            <div class="input-group m-input-group m-input-group--square">
                                                                <input type="number" name="{{$name}}"
                                                                       class="form-control m-input text-right"
                                                                       value="{{ old($name, $charter->{$name}) }}">
                                                                <span class="input-group-addon"><i
                                                                            class="fa fa-dollar"></i></span>
                                                            </div>
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                </div>

                                <div class="col-lg-12 mb-2">
                                    <hr/>

                                    <h4>Commission</h4>
                                </div>

                                <div class="col-lg-4">
                                    <label><span class="text-danger">Commission</span> Value:</label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <input type="text" name="commission" class="form-control m-input"
                                               placeholder="Enter commission value"
                                               value="{{ old('commission', $charter->commission) }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label><span class="text-danger">Commission</span> Calculation:</label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <select name="is_percent" class="form-control m-input">
                                            <option value="0" {{ old('is_percent', $charter->is_percent) == "0" ? 'selected' : ''}}>
                                                Fixed amount
                                            </option>
                                            <option value="1" {{ old('is_percent', $charter->is_percent) == "1" ? 'selected' : ''}}>
                                                Percentage
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-2">
                                    <hr/>

                                    <h4>Instructions</h4>
                                </div>

                                <div class="col-lg-12">
                                    <label><span class="text-danger">Flight</span> Instructions:</label>
                                    <div class="input-group m-input-group m-input-group--square">
                                        <textarea class="form-control" rows="6"
                                                  name="instructions">{{$charter->instructions}}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <hr class="mt-4 mb-4"/>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-lg-12 mb-2">
                                            <h4>Pay Later Settings</h4>
                                        </div>

                                        <div class="col-lg-6">
                                            <label><span class="text-danger">Maximum</span> hours for payment:</label>
                                            <div class="input-group m-input-group m-input-group--square">
                                                <span class="input-group-addon">+</span>
                                                <input type="number" name="pay_later_max" class="form-control m-input"
                                                       value="{{ old('pay_later_max', $charter->pay_later_max) }}">
                                                <span class="input-group-addon">HOURS</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-lg-12 mb-2">
                                            <h4>Void Settings</h4>
                                        </div>

                                        <div class="col-lg-6">
                                            <label><span class="text-danger">Maximum</span> hours for payment:</label>
                                            <div class="input-group m-input-group m-input-group--square">
                                                <span class="input-group-addon">+</span>
                                                <input type="number" name="void_max" class="form-control m-input"
                                                       value="{{ old('void_max', $charter->void_max) }}">
                                                <span class="input-group-addon">HOURS</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Portlet-->
                    {!! csrf_field() !!}
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-4">
                                    <button type="submit" class="btn btn-primary">
                                        Save Changes
                                    </button>
                                    <a href="{{ route('listCharter') }}" class="btn btn-secondary">
                                        Go Back
                                    </a>
                                </div>
                                <div class="col-lg-8"></div>
                            </div>
                        </div>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Portlet-->
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('default-assets/demo/default/custom/components/forms/widgets/ckeditor/ckeditor.js') }}"
            type="text/javascript"></script>

    <script src="{{ asset('default-assets/demo/default/custom/components/forms/widgets/select2.js') }}"
            type="text/javascript"></script>

    <script src="{{ asset('front-assets/bower_components/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"
            type="text/javascript"></script>

    <script>

        if ($('[name=flight_type]').val() === "RoundTrip") {
            $('.round-trip').removeAttr('hidden');
        }

        $('[name=flight_type]').on('change', function () {
            if ($(this).val() === "RoundTrip") {
                $('.round-trip').removeAttr('hidden');
            } else {
                $('.round-trip').attr('hidden', true);
            }
        });

        $('.timer').timepicker({
            icons: {
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down'
            }
        });
    </script>
@endsection