@extends('layouts.master')

@section('page-title')
    Flight ({{$charter->name}}) orders
@endsection

@section('sub-header')
    Orders
@endsection

@section('content')
    @include('includes.info-box')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <a href="{{route('chartOrdersDownload', ['charter' => $charter->id])}}"
                   class="btn btn-brand btn-sm pull-right mt-4">
                    <i class="fa fa-download" style="font-size: 14px;"></i> Download Passengers Data
                </a>

                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Flight ({{$charter->name}}) orders
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">

            <!--begin: Datatable -->
            <table id="data-tables" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Status</th>
                    <th>Company</th>
                    <th>Price</th>
                    <th>Commission</th>
                    <th>PNR</th>
                    <th>Phone</th>
                    <th>Note</th>
                    <th>Created at</th>
                    <th>Flight Class</th>
                    <th>Flights</th>
                    <th>Passengers</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
            <!--end: Datatable -->

            <div class="d-table w-100">
                <table class="table pull-right w-50 mt-4">
                    <tr>
                        <th>Buy/Total (Economy Seats)</th>
                        <th class="text-right">
                            <span class="text-danger">{{$stats['sold_economy_seats']}}</span>
                            /
                            <span class="text-success">{{$stats['total_economy_seats']}}</span>
                        </th>
                    </tr>
                    <tr>
                        <th>Buy/Total (Business Seats)</th>
                        <th class="text-right">
                            <span class="text-danger">{{$stats['sold_business_seats']}}</span>
                            /
                            <span class="text-success">{{$stats['total_business_seats']}}</span>
                        </th>
                    </tr>
                    <tr>
                        <th>Total Amount</th>
                        <th class="text-right">${{$stats['total_amount']}}</th>
                    </tr>
                    <tr>
                        <th>Total Agent Commission</th>
                        <th class="text-right">${{$stats['total_commission']}}</th>
                    </tr>
                    <tr>
                        <th>Total Price (Excluding Commissions)</th>
                        <th class="text-right">${{$stats['total_profit']}}</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!--begin::Modal-->
    <div class="modal fade" id="flights-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Order Flights
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body p-3">
                    <table class="table table-bordered" id="flight-table">
                        <thead>
                        <tr>
                            <th>Flight</th>
                            <th>Departure Date</th>
                            <th>Price</th>
                            <th>Commission</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->
@endsection

@section('styles')
    <link href="{{asset('public/assets/css/styles.css')}}" rel="stylesheet">
    <style>
        tr.cancelled {
            background: #f4516c59 !important;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('default-assets/demo/default/custom/components/forms/widgets/select2.js') }}"
            type="text/javascript"></script>

    <script>
        $('body').on('click', '.confirm-cancel', function () {
            var $this = $(this);
            $.confirm({
                columnClass: 'col-md-6 col-md-offset-4',
                buttons: {
                    formSubmit: {
                        text: 'Confirm',
                        action: function () {
                            this.$content.find('form')[0].submit();
                        }
                    },
                    cancel: {}
                },
                content: function () {
                    var self = this;
                    return $.ajax({
                        url: '{{route('cancelCharterForm')}}?id=' + $this.data('id'),
                        method: 'get'
                    }).done(function (response) {
                        response = response.replace('{form-action}', '{{route('cancel-charter-ticket', ['charter'=>$charter->id])}}?order=' + $this.data('id'));
                        self.setContent(response);
                    }).fail(function () {
                        self.setContent('Something went wrong.');
                    });
                },
                onContentReady: function () {
                    // bind to events
                }
            })
        });

        var table = $('#data-tables').DataTable({
            serverSide: true,
            processing: true,
            scrollX: true,
            ajax: "{{route('charterOrdersData', ['charter' => $charter->id])}}",
            fixedColumns: {
                leftColumns: 1,
                rightColumns: 3,
            },
            columns: [
                {data: 'id'},
                {data: 'status'},
                {data: 'user_id'},
                {data: 'price'},
                {data: 'commission'},
                {data: 'pnr'},
                {data: 'phone'},
                {data: 'note'},
                {data: 'created_at'},
                {data: 'flight_class'},
                {data: 'flights'},
                {data: 'passengers'},
                {data: 'actions'},
            ],
            rowCallback: function (row, data, index) {
                if (data.status === 'Cancelled') {
                    $(row).addClass('cancelled');
                }
            }
        });

        $('#data-tables tbody').on('click', '.show-flights', function () {
            var $this = $(this);
            $.ajax({
                url: "{{route('charterOrderFlights')}}?order=" + $this.data('id'), success: function (results) {
                    var flightTable = $("#flight-table");
                    flightTable.find('tbody').html('');

                    $.each(JSON.parse(results), function (index, result) {
                        var charter = result.charter;

                        flightTable.find('tbody').append('<tr>' +
                            '<td>' + charter.name + '</td>' +
                            '<td>' + charter.flight_date + '</td>' +
                            '<td>' + result.price + '</td>' +
                            '<td>' + result.commission + '</td>' +
                            '</tr>');
                    });

                    $("#flights-modal").modal('show');
                }
            });
        });
    </script>
@endsection