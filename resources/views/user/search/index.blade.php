@extends('layouts.master')

@section('page-title')
    History
@endsection

@section('sub-header')
    History Search
@endsection

@section('content')
    @include('includes.info-box')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Search Details
                            </h3>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
            <!-- <form class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed"
                      method="get"
                      action="{{ route('searchUserHistory') }}" enctype="multipart/form-data"> -->
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <div class="col-lg-6">
                            <label for="date">
                                Date (from):
                            </label>
                        <!-- <input id="date" type="date" name="from" class="form-control m-input"
                                       value="{{ old('from') }}"> -->
                            <input name="min" id="min" type="text" class="form-control m-input">
                            <span class="m-form__help">
                                    Please enter valid date
                                </span>
                        </div>
                        <div class="col-lg-6">
                            <label for="date">
                                Date (to):
                            </label>
                        <!-- <input id="date" type="date" name="to" class="form-control m-input"
                                       value="{{ old('to') }}"> -->
                            <input name="max" id="max" type="text" class="form-control m-input">
                            <span class="m-form__help">
                                    Please enter valid date
                                </span>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid">
                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-8">
                                <!-- <button type="submit" class="btn btn-primary">
                                    Search
                                </button> -->
                            <!-- <a href="{{ route('user-profile') }}" class="btn btn-secondary">
                                        Cancel
                                    </a> -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- </form> -->
                <!--end::Form-->
            </div>
            <!--end::Portlet-->
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Charter Orders
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin: Datatable -->

            <table id="charter-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Company</th>
                    <th>Price</th>
                    <th>Commission</th>
                    <th>PNR</th>
                    <th>Phone</th>
                    <th>Note</th>
                    <th>Created at</th>
                    <th>Flight Class</th>
                </tr>
                </thead>
            </table>
            <!--end: Datatable -->
        </div>
    </div>

    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Visa Orders
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
                    <th>Name</th>
                    <th>Created at</th>
                    <th>Birth date</th>
                    <th>Nationality</th>
                    <th>Passport number</th>
                    <th>Passport expire date</th>
                    <th>Price</th>
                    <th>Delivered By</th>
                    <th>Delivered To</th>
                    <th>Status</th>
                </tr>
                </thead>
            </table>
            <!--end: Datatable -->
        </div>
    </div>

    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        ({{ Auth::user()->name }}) Transactions History
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin: Datatable -->

            <table id="transactions-table" class="table table-striped table-bordered nowrap" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Pnr</th>
                    <th>Transaction Type</th>
                    <th>Related Process</th>
                    <th>Opening Balance</th>
                    <th>Amount</th>
                    <th>End Balance</th>
                    <th>comment</th>
                    <th>Date</th>
                </tr>
                </thead>
            </table>
            <!--end: Datatable -->
        </div>
    </div>


@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script>

        var transactions = $('#transactions-table').DataTable({
            serverSide: true,
            processing: true,
            scrollX: true,
            ajax: "{{route('historyData', ['order_type' => 'transactions'])}}",
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0,
            },
            columns: [
                {data: 'id'},
                {data: 'from'},
                {data: 'to'},
                {data: 'pnr'},
                {data: 'type'},
                {data: 'connectedID'},
                {data: 'creditBefore'},
                {data: 'amount'},
                {data: 'creditAfter'},
                {data: 'comment'},
                {
                    data: 'created_at',
                    render: function (data) {
                        return moment(data).format('DD/MM/YYYY');
                    }
                },
            ]
        });

        var table = $('#data-tables').DataTable({
            serverSide: true,
            processing: true,
            scrollX: true,
            ajax: "{{route('historyData', ['order_type' => 'visa'])}}",
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0,
            },
            columns: [
                {data: 'id'},
                {data: 'name'},
                {
                    data: 'created_at',
                    render: function (data) {
                        return moment(data).format('DD/MM/YYYY');
                    }
                },
                {
                    data: 'birth_date',
                    render: function (data) {
                        return moment(data).format('DD/MM/YYYY');
                    }
                },
                {data: 'nationality'},
                {data: 'passport_number'},
                {
                    data: 'passport_expire_date',
                    render: function (data) {
                        return moment(data).format('DD/MM/YYYY');
                    }
                },
                {data: 'price'},
                {data: 'delivered_by'},
                {data: 'user'},
                {data: 'status'},
            ]
        });

        var charter = $('#charter-table').DataTable({
            serverSide: true,
            processing: true,
            scrollX: true,
            ajax: "{{route('historyData', ['order_type' => 'charter'])}}",
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0,
            },
            columns: [
                {data: 'id'},
                {data: 'user_id'},
                {data: 'price'},
                {data: 'commission'},
                {data: 'pnr'},
                {data: 'phone'},
                {data: 'note'},
                {
                    data: 'created_at',
                    render: function (data) {
                        return moment(data).format('DD/MM/YYYY');
                    }
                },
                {data: 'flight_class'},
            ]
        });

        // $(document).ready(function(){
        //     $('table.display').DataTable({
        //         dom: 'Bfrtip',
        //         responsive: true,
        //         scrollX: true,
        //         buttons: [
        //             'excel', 'pdf', 'print'
        //         ],
        //         "order" : [[0,'desc']]
        //     });
        //     $.fn.dataTable.ext.search.push(
        //         function (settings, data, dataIndex) {
        //             var min = $('#min').datepicker("getDate");
        //             var max = $('#max').datepicker("getDate");
        //             var startDate = new Date(data[5]);
        //             if (min == null && max == null) { return true; }
        //             if (min == null && startDate <= max) { return true;}
        //             if(max == null && startDate >= min) {return true;}
        //             if (startDate <= max && startDate >= min) { return true; }
        //             return false;
        //         }
        //
        //     );
        //
        //
        //     $("#min").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });
        //     $("#max").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });
        //     var table = $('.example').DataTable();
        //
        //     // Event listener to the two range filtering inputs to redraw on input
        //     $('#min, #max').change(function () {
        //         table.draw();
        //     });
        //
        //
        //     });

    </script>
@endsection