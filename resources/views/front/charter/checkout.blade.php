@extends('layouts.master-front')

@section('title')
    - Checkout
@endsection

@section('styles')
    <style>
        .card-header {
            cursor: pointer;
        }

        .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
            background-color: #fe0068;
        }

        .nav-fill .nav-item {
            background: #fff;
            margin-right: 5px;
            margin-left: 5px;
        }

        div#nav-tab {
            margin: 0 -5px;
        }
    </style>
@endsection

@section('content')
    @if(session()->has('fail'))
        <div class="alert m-alert m-alert--default alert-danger" role="alert">
            {{ session()->get('fail') }}
        </div>
    @elseif(session()->has('success'))
        <div class="alert m-alert m-alert--default alert-success" role="alert" style="margin: 20px">
            {{session()->get('success') }}
        </div>

        <a href="{{route('download-charter-ticket', ['order' => session()->get('pnr')])}}" class="btn btn-primary"
           style="margin: 0 20px;">{{__('charter.download')}}</a>
    @endif

    <section class="pt-3">
        <div class="container">
            @if(Auth::check() and !session()->has('success'))
                <div class="row">
                    <div class="col-8">
                        <h5 class="bg-dark text-light p-2">Flight Details</h5>

                        <table class="table table-bordered table-striped table-sm hidden-xs hidden-sm">
                            <tr>
                                <th scope="col">{{ __('charter.from') }}</th>
                                <td>{{$charter->from->code}} - {{ $charter->from->name[App::getLocale()]}}</td>
                            </tr>
                            <tr>
                                <th scope="col">{{ __('charter.to') }}</th>
                                <td>{{$charter->to->code}} - {{$charter->to->name[App::getLocale()]}}</td>
                            </tr>
                            <tr>
                                <th scope="col">{{ __('charter.flight_date') }}</th>
                                <td>{{$charter->flight_day}} {{$charter->flight_date}}</td>
                            </tr>
                            <tr>
                                <th scope="col">{{ __('charter.flight_time') }}</th>
                                <td>{{$charter->departure_time}}</td>
                            </tr>
                            <tr>
                                <th scope="col">{{ __('charter.airline') }}</th>
                                <td>{{$charter->aircraft->name}}</td>
                            </tr>
                            <tr>
                                <th scope="col">Flight Class</th>
                                <td>
                                    <span class="badge-pill badge-info d-inline-block small">{{$request->flight_class}}</span>
                                </td>
                            </tr>
                        </table>

                        <h5 class="bg-dark text-light p-2">Travelers</h5>

                        <form id="travelers-form">
                            @foreach($travelers as $title => $traveler)
                                <h5 class="bg-secondary p-2 border text-white text-center">{{$title}}</h5>

                                @for($index=0; $index < $traveler; $index++)
                                    <div class="accordion mb-2" id="accordion-{{$title.$index}}">
                                        <div class="card">
                                            <div class="card-header" hidden>
                                                <h6 class="mb-0 full-name" data-toggle="collapse"
                                                    data-target="#collapse-{{$title.$index}}"></h6>
                                            </div>

                                            <div id="collapse-{{$title.$index}}" class="collapse show"
                                                 data-parent="#accordion-{{$title.$index}}">
                                                <div class="card-body">
                                                    <input type="hidden" name="age[]" value="{{$title}}">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label class="d-block">Title @required()</label>
                                                                <select name="title[]"
                                                                        class="form-control select2-nosearch">
                                                                    <option>MR</option>
                                                                    <option>MRS</option>
                                                                    <option>INF</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label class="d-block">First Name @required()</label>
                                                                <input type="text" class="form-control" name="first_name[]"
                                                                       placeholder="First Name"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label class="d-block">Last Name @required()</label>
                                                                <input type="text" class="form-control" name="last_name[]"
                                                                       placeholder="Last Name"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label class="d-block">Birth Date @required()</label>
                                                                <input type="text" class="form-control datepicker date-mask"
                                                                       name="birth_date[]" placeholder="__/__/____"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label class="d-block">Nationality @required()</label>
                                                                <select class="form-control select2" name="nationality[]">
                                                                    <option></option>
                                                                    @foreach($nationalities as $country)
                                                                        <option value="{{ $country->id }}"
                                                                                {{ ($country->id == 104 or old('nationality.'.$index) == $country->id) ? 'selected' : ''}}>
                                                                            {{ $country->name["en"] }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label class="d-block">Passport Number @required()</label>
                                                                <input type="text" class="form-control"
                                                                       name="passport_number[]"
{{--                                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"--}}
                                                                       placeholder="Passport Number" id="passport"/>
                                                                <input type="hidden" value="{{$charter->id}}" id="charter_id">
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label class="d-block">Passport Expiration Date @required()</label>
                                                                <input type="text" class="form-control datepicker date-mask"
                                                                       placeholder="__/__/____"
                                                                       name="passport_expire_date[]"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            @endforeach
                        </form>
                    </div>
                    <div class="col-4">

                        <div class="summary sticky-top">
                            <h5 class="bg-dark text-light p-2">Order Summary</h5>

                            <div class="bg-white p-2 border">
                                <table class="table table-bordered table-sm m-0">
                                    <tr>
                                        <td>Adults</td>
                                        <td class="text-center">
                                        <span>
                                            ${{$isEconomy ? $charter->price_adult : $charter->business_adult}}
                                        </span>
                                            x
                                            <span class="adult_count">{{$travelers['Adults']}}</span>
                                        </td>
                                        <td class="text-right total-adult">${{$prices['adults']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Children</td>
                                        <td class="text-center">
                                        <span>
                                            ${{$isEconomy ? $charter->price_child : $charter->business_child}}
                                        </span>
                                            x
                                            <span class="child_count">{{$travelers['Children']}}</span>
                                        </td>
                                        <td class="text-right total-children">${{$prices['children']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Babies</td>
                                        <td class="text-center">
                                        <span>
                                            ${{$isEconomy ? $charter->price_baby : $charter->business_baby}}
                                        </span>
                                            x
                                            <span class="babies_count">{{$travelers['Babies']}}</span>
                                        </td>
                                        <td class="text-right total-babies">${{$prices['babies']}}</td>
                                    </tr>
                                    <tr class="bg-light">
                                        <td colspan="3">
                                            <strong class="text-info">Total</strong>
                                            <strong class="text-info float-right total-price">${{$total}}</strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <h5 class="bg-dark text-light p-2 mt-2">Agent Details</h5>

                            <form id="agent-form">
                                <div class="bg-white p-2 border mt-2">
                                    <div class="form-group">
                                        <label class="d-block">Contact Phone</label>
                                        <input type="text" class="form-control" name="phone" placeholder="Your phone"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="d-block">Contact Email</label>
                                        <input type="text" class="form-control" name="email" placeholder="Your email"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="d-block">Note</label>
                                        <textarea type="text" class="form-control" name="note" placeholder="Write your note here"></textarea>
                                    </div>
                                </div>
                            </form>

                            <h5 class="bg-dark text-light p-2 mt-2">Payment Options</h5>

                            <nav>
                                <div class="nav nav-pills nav-fill mt-2" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="pay-now-tab" data-toggle="tab" href="#nav-pay-now-tab" role="tab">Pay Now</a>
                                    @if($charter->pay_later_max > 0)
                                    <a class="nav-item nav-link" id="pay-later-tab" data-toggle="tab" href="#nav-pay-later-tab" role="tab">Pay Later</a>
                                    @endif
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-pay-now-tab" role="tabpanel" aria-labelledby="pay-now-tab">
                                    <div class="bg-white p-2 border mt-2">
                                        <strong class="text-dark">You Balance</strong>
                                        <strong class="text-info float-right total-price">${{$balance}}</strong>
                                    </div>

                                    @if($canPlaceOrder)
                                        <button class="btn btn-info btn-block mt-2 checkout">
                                            Complete Order
                                        </button>
                                    @else
                                        <div class="bg-warning p-2 mt-2">
                                            You don't have sufficient balance
                                        </div>
                                    @endif
                                </div>

                                @if($charter->pay_later_max > 0)
                                <div class="tab-pane fade" id="nav-pay-later-tab" role="tabpanel" aria-labelledby="nav-profile-tab">
                                    <div class="bg-white p-2 border mt-2">
                                        <strong class="text-dark">Complete order and pay later</strong>
                                        <button class="btn btn-info btn-block mt-2 checkout">
                                            Pay Later Order
                                        </button>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="instructions">
                                {!! nl2br($charter->instructions) !!}
                            </div>
                        </div>

                    </div>
                </div>

            @else
                @if(!Auth::check())
                    <div class="alert m-alert m-alert--default alert-danger" role="alert">
                        {{ __('alnkel.single-travel-please') }}, <a
                                href="{{ route('front-login') }}">{{ __('alnkel.single-travel-login') }}</a> {{ __('alnkel.single-travel-for-reservation') }}
                    </div>
                @endif
            @endif
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('front-assets/js/jquery.blockUI.js') }}"></script>
    <script src="{{ asset('front-assets/js/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/jquery.serialize-object.min.js') }}"></script>

    <script>
        // $(document).ready(function(){
        //     $('input, text').each(function() {
        //         this.value = '';
        //     });
        // });
        // Prefill
        $('input').each(function (i, field) {
            if($(field).hasClass('datepicker')) {
                $(field).val('30/09/2019');
            }else{
                if($(field).attr("type") !== "hidden") {
                    $(field).val('assdfsdf');
                }
            }
        });

        $('body').on('click', '.checkout', function (e) {
            e.preventDefault();
            var $this = $(this);

            // Validate
            var fields = $('#travelers-form').serializeArray(),
                errors = false;

            $(fields).each(function (i, field) {
                if(!field.value) {
                    errors = true;
                }
            });

            if(errors) {
                $.alert({
                    title: 'Alert',
                    content: 'Please fill all required fields first!'
                });

                return;
            }

            $.confirm({
                title: 'Confirm',
                content: 'Your balance will be credited and the order will be placed, are you sure?',
                buttons: {
                    sure: {
                        btnClass: 'btn-success',
                        text: 'Yes, Sure',
                        action: function () {
                            $.confirm({
                                title: 'Order Completed Successfully',
                                columnClass: 'col-md-6',
                                buttons: {
                                    ok: {
                                        btnClass: 'btn-info',
                                        text: 'Redirect to orders panel',
                                        action: function () {

                                        }
                                    },
                                    cancel: {
                                        btnClass: 'btn-secondary',
                                        text: 'Redirect to home page',
                                        action: function () {
                                            window.location = '{{url("/")}}';
                                        }
                                    },
                                },
                                content: function () {
                                    var self = this;
                                    return $.ajax({
                                        url: '{{route('completeCharterOrder')}}',
                                        method: 'post',
                                        data: {
                                            charter: {{$charter->id}},
                                            flight_class: '{{$request->flight_class}}',
                                            fields: $('#travelers-form').serializeObject(),
                                            agent: $('#agent-form').serializeObject(),
                                            travelers: {
                                                adults: {{$travelers['Adults']}},
                                                children: {{$travelers['Children']}},
                                                babies: {{$travelers['Babies']}},
                                            }
                                        }
                                    }).done(function (response) {
                                        self.setContent(response.html);
                                    }).fail(function () {
                                        self.setContent('Something went wrong.');
                                    });
                                },
                                onContentReady: function () {
                                    // bind to events
                                }
                            })
                        }
                    },
                    cancel: {}
                }
            });
        });
    </script>

    <script>

        $('[name="first_name[]"], [name="last_name[]"], [name="title[]"]').on('change', function () {
            var value = $(this).val(),
                cardHeader = $(this).closest('.card').find(".card-header"),
                card = $(this).closest('.card');

            var fullName = card.find('[name="title[]"]').val() + '/ ' + card.find('[name="first_name[]"]').val() + ' ' + card.find('[name="last_name[]"]').val();

            if (value) {
                cardHeader.find(".full-name").text(fullName);
                cardHeader.removeAttr("hidden");
            } else {
                cardHeader.attr("hidden", true);
            }
        });

        $('#blockButton').click(function () {
            $.blockUI({
                css: {
                    border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff'
                }
            });
        });

        $('.date-mask').on('focus', function () {
            $(this).mask('00/00/0000', {placeholder: "__/__/____"});
        }).on('change', function () {
            var val = $(this).val();
            var datParts = val.split("/");

            var dateCheck = moment(datParts[1] + "/" + datParts[0] + "/" + datParts[2]);

            if (!dateCheck.isValid()) {
                console.log(val);
                alert("Date is invalid, please enter a valid date");
                $(this).val("")
            }
        });

        $(".datepicker").datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true
        });

        $('.birthday-adult').datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true
        });

        @if(isset($timestamp))
		<?php
		$childrenMinDate = date( "m", $timestamp ) . "/" . ( intval( date( "d", $timestamp ) ) + 1 ) . "/" . ( intval( date( "Y", $timestamp ) ) - 12 );
		$babyMinDate = date( "m", $timestamp ) . "/" . ( intval( date( "d", $timestamp ) ) + 1 ) . "/" . ( intval( date( "Y", $timestamp ) ) - 2 );
		?>

        $('.birthday-children').datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            minDate: new Date('{{$childrenMinDate}}'),
            maxDate: "+1D"
        }).on('blur', function () {
            var date = $(this).val();
            if (moment(date, "DD/MM/YYYY").isBefore('{{$childrenMinDate}}')) {
                alert("Child age can't be more than 12 years.");
                $(this).val("");
            }
        });

        $('.birthday-baby').datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            minDate: new Date('{{$babyMinDate}}'),
            maxDate: "+1D"
        }).on('blur', function () {
            var date = $(this).val();
            if (moment(date, "DD/MM/YYYY").isBefore('{{$babyMinDate}}')) {
                alert("Child age can't be more than 2 years.");
                $(this).val("");
            }
        });
        @endif
    </script>

    <script type="text/javascript">



        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });



        $('body').on('change', '#passport', function (e) {

            e.preventDefault();
           var passport=$(this).val();
           var charter=$('#charter_id').val();
           //alert(passport);
            $.ajax({

                type: 'POST',

                url: '{{route('checkPassport')}}',

                data: {passport: passport, charter: charter},

                success: function (data) {
                    if (data == "true") {

                        $.confirm({
                            title: 'تم الحجز لهذا العميل على نفس الرحلة من قبل ',
                            columnClass: 'col-md-6',
                            buttons: {
                                ok: {
                                    btnClass: 'btn-info',
                                    text: 'الاستمرار على اى حال',
                                    action: function () {

                                    }
                                },
                                cancel: {
                                    btnClass: 'btn-secondary',
                                    text: 'العودة للصفحة الرئيسيه',
                                    action: function () {
                                        window.location = '{{url("/")}}';
                                    }
                                },
                            }
                        })

                    }
                }
            });





        });

    </script>
@endsection