@extends('layouts.master')

@section('page-title')
    Manage Order #{{$order->id}}
@endsection

@section('content')
    @include('includes.info-box')

    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Manage Order #{{$order->id}} - <span class="text-success" data-toggle="tooltip"
                                                             data-placement="top" title="PNR">{{$order->pnr}}</span> - <span class="ml-3">Status: <span class="@if($isCancelled) text-danger @else text-success @endif">{{ucfirst($order->status)}}</span></span>
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="{{route("download-charter-ticket", ["pnr" => $order->pnr])}}"
                           class="btn btn-brand btn-sm" data-toggle="tooltip" data-placement="top"
                           title="Print Ticket" id="print-ticket">
                            <i class="la la-print"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="{{route("download-charter-ticket", ["pnr" => $order->pnr])}}"
                           class="btn btn-brand btn-sm" data-toggle="tooltip" data-placement="top"
                           title="Download Ticket" id="download-ticket">
                            <i class="la la-download"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" class="btn btn-brand btn-sm" data-toggle="tooltip" data-placement="top"
                           title="Send Ticket" id="send-ticket">
                            <i class="la la-envelope"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" class="btn btn-brand btn-sm" data-toggle="tooltip" data-placement="top"
                           title="Contact Details" id="edit-contact">
                            <i class="la la-user"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" class="btn btn-brand btn-sm" data-toggle="tooltip" data-placement="top"
                           title="Edit Notes" id="edit-note">
                            <i class="la la-edit"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" class="btn btn-brand btn-sm" data-toggle="tooltip" data-placement="top"
                           title="History" id="history">
                            <i class="la la-history"></i>
                        </a>
                    </li>
                    @if($canVoid)
                        <li class="m-portlet__nav-item">
                            <a href="#" class="btn btn-brand btn-sm" data-toggle="tooltip" data-placement="top"
                               title="Void" id="void-ticket">
                                <i class="la la-times-circle"></i>
                            </a>
                        </li>
                    @endif
                    @if($canCancel)
                        <li class="m-portlet__nav-item">
                            <a href="#" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top"
                               title="Cancel Ticket" id="cancel-ticket">
                                <i class="la la-close"></i>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Flights Details
                    </h3>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-xl-12">
                <table class="table table-bordered table-striped mb-0">
                    <tr>
                        <th scope="col" width="50"></th>
                        <th scope="col">{{ __('charter.from') }}</th>
                        <th scope="col">{{ __('charter.to') }}</th>
                        <th scope="col">{{ __('charter.flight_date') }}</th>
                        <th scope="col">{{ __('charter.flight_time') }}</th>
                        <th scope="col">{{ __('charter.airline') }}</th>
                        <th scope="col">Flight Class</th>
                    </tr>
                    @if($order->flights()->count() > 0)
                        @foreach($order->flights as $flight)
                            <tr>
                                <td>
                                    @if($flight->charter->can_change and !$isCancelled)
                                    <a href="#" class="btn btn-brand btn-sm reschedule" data-toggle="tooltip" data-placement="top"
                                       title="Re-Schedule" data-id="{{$flight->id}}">
                                        <i class="la la-calendar"></i>
                                    </a>
                                    @endif
                                </td>
                                <td>{{$flight->charter->from->code}}
                                    - {{ $flight->charter->from->name[App::getLocale()]}}</td>
                                <td>{{$flight->charter->to->code}}
                                    - {{$flight->charter->to->name[App::getLocale()]}}</td>
                                <td>{{$flight->charter->flight_day}} {{$flight->charter->flight_date}}</td>
                                <td>{{$flight->charter->departure_time}}</td>
                                <td>{{$flight->charter->aircraft->name}}</td>
                                <td>
                                    <span class="badge-pill badge-info d-inline-block">{{$flight->flight_class}}</span>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="float-right m-portlet__head-tools pt-4">
                    @foreach(["baby" => "BABIES","child" => "CHILDREN","adult" => "ADULTS"] as $age=>$title)
                        <h5  class="m-portlet__head-text float-right mt-1 ml-4">{{$order->passengers()->where("age", $age)->count() > 0 ? $title. ": " . $order->passengers()->where("age", $age)->count() : null}}</h5>
                        <input type="hidden" id="count" value="{{$order->passengers()->where("age", $age)->count()}}">
                    @endforeach
                </div>
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Passengers Details
                    </h3>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-xl-12">
                <table class="table table-bordered table-striped mb-0">
                    <tr>
                        <th></th>
                        @if($canSplit)
                            <th></th>@endif
                        <th>Passenger Name</th>
                        <th>Birth Date</th>
                        <th>Nationality</th>
                        <th>Passport Number</th>
                        <th>Passport Expire Date</th>
                        <th>Ticket Number</th>
                    </tr>
                    @foreach($order->passengers as $passenger)
                        <tr>
                            <td>
                                <span class="badge-pill badge-warning d-inline-block">{{ucfirst($passenger->age)}}</span>
                            </td>
                            @if($canSplit)
                                <td>
                                    @if($passenger->age == "adult")
                                        <label class="mt-checkbox mt-checkbox-outline">
                                            <input type="checkbox" name="selected_passengers"
                                                   value="{{$passenger->id}}">
                                            <span></span>
                                        </label>
                                    @endif
                                </td>
                            @endif
                            <td>{{$passenger->name}}</td>
                            <td>{{$passenger->birth_date}}</td>
                            <td>{{$passenger->passenger_nationality}}</td>
                            <td>{{$passenger->passport_number}}</td>
                            <td>{{$passenger->passport_expire_date}}</td>
                            <td>{{$passenger->ticket_number[0]}}</td>
                        </tr>
                    @endforeach
                </table>

                @if($canSplit)
                    <button class="btn btn-info btn-sm m-3" id="split-passengers">
                        <i class="fa fa-random"></i> Split selected passengers
                    </button>
                @endif

            </div>
        </div>
    </div>

    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Agent Details
                    </h3>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-xl-12">
                <table class="table table-bordered table-striped mb-0">
                    <tr>
                        <th scope="col">Agent</th>
                        <th scope="col">Contact Phone</th>
                        <th scope="col">Email</th>
                        <th scope="col">Notes</th>
                    </tr>
                    <tr>
                        <td>{{$order->user->name}}</td>
                        <td>{{$order->phone}}</td>
                        <td>{{$order->email}}</td>
                        <td>{{$order->note}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Pricing
                    </h3>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-xl-12">
                <table class="table table-bordered table-striped mb-0">
                    <tr>
                        <th scope="col">Total Price</th>
                        <th scope="col">Commission</th>
                    </tr>
                    <tr>
                        <?php
                        $charter = \App\Charter::find( $order->charter_id );
                        ?>
                        <td>${{$order->price}}</td>
                        <td>${{calculateCommission( $charter,$order->price )}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://printjs-4de6.kxcdn.com/print.min.css">

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        // ReSchedule
        $('body').on('click', '.next-btn, .previous-btn', function (e) {
            e.preventDefault();

            $('.flight_date').val($(this).data("date"));
            $('.search-btn').trigger("click");
        });

        $('.reschedule').on('click', function (e) {
            e.preventDefault();

            var id = $(this).data("id");

            $.confirm({
                title: `Reschedule Trip`,
                columnClass: 'col-md-12',
                icon: `fa fa-calender`,
                type: 'purple',
                buttons: {
                    continue: {
                        isHidden: true,
                        btnClass: 'btn-info',
                        text: 'Continue',
                        action: function () {
                            let self = this,
                                content = self.$content;

                            let selectedFlight = content.find('.flight_id:checked').val(),
                                agree = content.find('.agree:checked').val(),
                                flight_class = content.find('.flight_class').val(),
                                payment = content.find('#new-price').text().replace("$", "");

                            if(!selectedFlight) {
                                $.alert("Please select a flight first", "Error");
                                return false;
                            }

                            if(!agree) {
                                $.alert("You have to agree on new pricing", "Error");
                                return false;
                            }

                            this.setContent(function () {

                                self.showLoading();

                                return $.ajax({
                                    url: '{{ route('charterButtons',['order' => $order->id]) }}',
                                    data: {
                                        action: 'reschedule_process',
                                        id,
                                        selectedFlight,
                                        flight_class,
                                        payment
                                    },
                                    method: 'POST'
                                }).done(function (response) {
                                    location.reload();
                                }).fail(function () {
                                    self.setContent('Something went wrong.');
                                    self.hideLoading();
                                });
                            });

                            return false;
                        }
                    },
                    search: {
                        btnClass: 'btn-brand search-btn',
                        text: 'Search',
                        action: function () {
                            let self = this,
                                flight_class = self.$content.find('.flight_class').val(),
                                flight_date = self.$content.find('.flight_date').val();

                            this.setContent(function () {

                                self.showLoading();

                                return $.ajax({
                                    url: '{{ route('charterButtons',['order' => $order->id]) }}',
                                    data: {
                                        action: 'search_flights',
                                        flight_class,
                                        flight_date,
                                        id
                                    },
                                    method: 'POST'
                                }).done(function (response) {
                                    self.buttons.continue.show();
                                    self.buttons.search.hide();
                                    self.buttons.cancel.setText("Cancel");

                                    self.setContent(response);
                                    self.hideLoading();
                                }).fail(function () {
                                    self.setContent('Something went wrong.');
                                    self.hideLoading();
                                });
                            });

                            return false;
                        }
                    },
                    cancel: {
                        text: 'Close'
                    },
                },
                content: function () {
                    var self = this;
                    return $.ajax({
                        url: '{{route('charterButtons', ["order" => $order->id])}}',
                        data: {
                            action: 'reschedule'
                        }
                    }).done(function (response) {
                        self.setContent(response);
                    }).fail(function () {
                        self.setContent('Something went wrong.');
                    });
                },
            });
        });
    </script>

    <script>
        // History
        $('#history').on('click', function (e) {
            e.preventDefault();

            $.confirm({
                title: `History of {{$order->pnr}}`,
                columnClass: 'col-md-8',
                icon: `fa fa-history`,
                type: 'purple',
                buttons: {
                    cancel: {
                        text: 'Close'
                    },
                },
                content: function () {
                    var self = this;
                    return $.ajax({
                        url: '{{route('charterButtons', ["order" => $order->id])}}',
                        data: {
                            action: 'history',
                        }
                    }).done(function (response) {
                        self.setContent(response);
                    }).fail(function () {
                        self.setContent('Something went wrong.');
                    });
                },
            });
        });
    </script>

    <script>
        // Cancel & Void
        $('#cancel-ticket, #void-ticket').on('click', function (e) {
            e.preventDefault();

            var isVoid = $(this).attr("id") === "void-ticket";

            $.confirm({
                title: `${isVoid ? 'Voiding' : 'Cancel'} Ticket`,
                columnClass: 'col-md-6',
                icon: `fa fa-${isVoid ? 'close' : 'download'}`,
                type: 'purple',
                buttons: {
                    confirm: {
                        btnClass: 'btn-brand',
                        text: `Confirm`,
                        action: function () {
                            var self = this;

                            this.setContent(function () {

                                self.showLoading();

                                return $.ajax({
                                    url: '{{ route('charterButtons',['order' => $order->id]) }}',
                                    data: {
                                        action: 'cancel_void',
                                        isVoid
                                    }
                                }).done(function (response) {
                                    self.buttons.confirm.hide();
                                    self.buttons.cancel.hide();
                                    self.buttons.done.show();

                                    self.setTitle("Success");

                                    self.setContent(`<div class="alert alert-success" role="alert">
  Ticket has been ${isVoid ? 'voided' : 'cancelled'} successfully!
</div>`);

                                    self.hideLoading();
                                }).fail(function () {
                                    self.setContent('Something went wrong.');
                                    self.hideLoading();
                                });
                            });

                            return false;
                        }
                    },
                    cancel: {
                        text: 'Cancel'
                    },
                    done: {
                        text: 'Done',
                        isHidden: true,
                        action: function () {
                            window.location = '{{route('listUserCharter')}}';
                        }
                    },
                },
                content: function () {
                    var self = this;
                    return $.ajax({
                        url: '{{route('charterButtons',['order' => $order->id])}}',
                        data: {
                            action: 'cancel_void_form',
                            isVoid
                        }
                    }).done(function (response) {
                        self.setContent(response);
                    }).fail(function () {
                        self.setContent('Something went wrong.');
                    });
                },
            });
        });
    </script>

    <script>
        // Split passengers
        $('#split-passengers').on('click', function () {
            var selectedPassengers = $("[name=selected_passengers]:checked");

            var number=$('#count').val();

            var passengers = selectedPassengers.map(function () {
                return $(this).val();
            }).get();
           // console.log(passengers);
            if (selectedPassengers.length === 0) {
                $.alert("You have to select at least one passenger to split!", "Error");
                return;
            }

            if (selectedPassengers.length === $("[name=selected_passengers]").length) {
                $.alert("Sorry, You can split all passengers, you have to leave at least one passenger in this order!", "Error");
                return;
            }

            var $this = $(this);

            $.confirm({
                title: `Warning`,
                content: 'Are you sure to split the selected passengers to new order?',
                columnClass: 'col-md-5',
                icon: `fa fa-random`,
                type: 'purple',
                buttons: {
                    confirm: {
                        text: 'Confirm',
                        action: function () {
                            var self = this;

                            this.setContent(function () {

                                self.showLoading();

                                return $.ajax({
                                    url: '{{ route('updateCharterOrder',['flight' => $order->charter->id,'order' => $order->id, 'do' => 'split']) }}',
                                    data: {passengers,number}
                                }).done(function (response) {
                                    self.buttons.confirm.hide();
                                    self.buttons.cancel.hide();
                                    self.buttons.done.show();

                                    self.setTitle("Success");

                                    self.setContent(`<div class="alert alert-success" role="alert">
  Split has been successfully processed.
</div>`);

                                    self.hideLoading();
                                }).fail(function () {
                                    self.setContent('Something went wrong.');
                                    self.hideLoading();
                                });
                            });

                            return false;
                        }
                    },
                    cancel: {
                        text: 'Cancel'
                    },
                    done: {
                        text: 'Done',
                        isHidden: true,
                        action: function () {
                            location.reload()
                        }
                    },
                }
            });

        });
    </script>

    <script>
        // Edit notes and contact details
        $('#edit-note, #edit-contact').on('click', function (e) {
            e.preventDefault();

            var editNotes = $(this).attr("id") === "edit-note";

            $.confirm({
                title: `Edit ${editNotes ? 'Notes' : 'Contact Details'}`,
                columnClass: 'col-md-5',
                icon: `fa fa-${editNotes ? 'edit' : 'user'}`,
                type: 'purple',
                buttons: {
                    save: {
                        btnClass: 'btn-brand',
                        text: `${editNotes ? 'Save Notes' : 'Save Contact Details'}`,
                        action: function () {
                            var self = this;
                            var email = self.$content.find('.email').val(),
                                phone = self.$content.find('.phone').val(),
                                note = self.$content.find('.note').val();

                            this.setContent(function () {

                                self.showLoading();

                                return $.ajax({
                                    url: '{{route('charterButtons', ['order' => $order->id])}}',
                                    data: {
                                        action: 'edit_details',
                                        email: email,
                                        phone: phone,
                                        note: note
                                    },
                                    method: 'POST'
                                }).done(function (response) {
                                    self.buttons.save.hide();
                                    self.buttons.cancel.hide();
                                    self.buttons.done.show();

                                    self.setContent(`<div class="alert alert-success" role="alert">
  Your details has been successfully saved.
</div>`);
                                    self.hideLoading();
                                }).fail(function () {
                                    self.setContent('Something went wrong.');
                                    self.hideLoading();
                                });
                            });

                            return false;
                        }
                    },
                    cancel: {
                        text: 'Cancel'
                    },
                    done: {
                        text: 'Done',
                        isHidden: true,
                        action: function () {
                            location.reload()
                        }
                    },
                },
                content: function () {
                    var self = this;
                    return $.ajax({
                        url: '{{route('charterButtons', ['order' => $order->id])}}',
                        data: {
                            action: `edit_details`,
                            form: `${editNotes ? 'notes' : 'contact'}`,
                        }
                    }).done(function (response) {
                        self.setContent(response);
                    }).fail(function () {
                        self.setContent('Something went wrong.');
                    });
                },
            });
        });
    </script>

    <script>
        // Send ticket via email
        $('#send-ticket').on('click', function (e) {
            e.preventDefault();

            $.confirm({
                title: `Send Ticket`,
                columnClass: 'col-md-4',
                icon: `fa fa-envelope}`,
                type: 'purple',
                buttons: {
                    download: {
                        btnClass: 'btn-brand',
                        text: `Send Ticket`,
                        action: function () {
                            var self = this;
                            var hide_prices = self.$content.find('.hide_prices').is(":checked"),
                                email = self.$content.find('.email').val();

                            this.setContent(function () {

                                self.showLoading();

                                return $.ajax({
                                    url: '{{route('charterButtons', ['order' => $order->id])}}',
                                    data: {
                                        action: 'send_email',
                                        email: email,
                                        hide_prices: hide_prices
                                    }
                                }).done(function (response) {
                                    self.buttons.download.hide();
                                    self.buttons.cancel.setText("Done");

                                    self.setContent(`<div class="alert alert-success" role="alert">
  Ticket has been successfully sent to your email.
</div>`);
                                    self.hideLoading();
                                }).fail(function () {
                                    self.setContent('Something went wrong.');
                                    self.hideLoading();
                                });
                            });


                            return false;
                        }
                    },
                    cancel: {
                        text: 'Cancel'
                    },
                },
                content: function () {
                    var self = this;
                    return $.ajax({
                        url: '{{route('charterButtons', ['order' => $order->id])}}',
                        data: {
                            action: 'send_email_form'
                        }
                    }).done(function (response) {
                        self.setContent(response);
                    }).fail(function () {
                        self.setContent('Something went wrong.');
                    });
                },
            });
        });
    </script>

    <script>
        // Download Ticket
        $('#download-ticket, #print-ticket').on('click', function (e) {
            e.preventDefault();

            var isPrint = $(this).attr("id") === "print-ticket";

            var link = $(this).attr("href");

            $.confirm({
                title: `${isPrint ? 'Print' : 'Download'} Ticket`,
                columnClass: 'col-md-4',
                icon: `fa fa-${isPrint ? 'print' : 'download'}`,
                type: 'purple',
                buttons: {
                    download: {
                        btnClass: 'btn-brand',
                        text: `${isPrint ? 'Print Ticket' : 'Download Now'}`,
                        action: function () {
                            var hide_prices = this.$content.find('.hide_prices').is(":checked");
                            link = hide_prices ? `${link}?hide_prices=yes` : link;

                            isPrint ? printJS({
                                printable: link,
                                showModal: true,
                                modalMessage: 'Preparing Ticket...'
                            }) : location.href = link;
                        }
                    },
                    cancel: {
                        text: 'Cancel'
                    },
                },
                content: function () {
                    var self = this;
                    return $.ajax({
                        url: '{{route('charterButtons', ["order" => $order->id])}}',
                        data: {
                            action: 'download_option',
                            type: isPrint ? 'Print' : 'Download'
                        }
                    }).done(function (response) {
                        self.setContent(response);
                    }).fail(function () {
                        self.setContent('Something went wrong.');
                    });
                },
            });
        });
    </script>
@endsection

@section("styles")
    <style>
        .m-datatable__pager {
            display: none !important;
        }

        span[data-toggle="tooltip"] {
            cursor: help;
        }
    </style>

    <link href="{{asset('public/assets/css/styles.css')}}" rel="stylesheet">
@endsection