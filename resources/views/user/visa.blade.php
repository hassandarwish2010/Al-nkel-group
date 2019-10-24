@extends('layouts.master')

@section('page-title')
    Visa
@endsection

@section('sub-header')
    Visa
@endsection

@section('content')
    @include('includes.info-box')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Visa order history
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                <div class="row align-items-center">
                    <div class="col-xl-8 order-2 order-xl-1">
                        <div class="form-group m-form__group row align-items-center">
                            <div class="col-md-4">
                                <div class="m-input-icon m-input-icon--left">
                                    <input type="text" class="form-control m-input m-input--solid"
                                           placeholder="Search..." id="generalSearch">
                                    <span class="m-input-icon__icon m-input-icon__icon--left">
                                        <span>
                                            <i class="la la-search"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end: Search Form -->
            <!--begin: Datatable -->
            <table class="m-datatable" id="html_table" width="100%">
                <thead>
                <tr>
                    <th>
                        Visa Type
                    </th>
                    <th>
                        First name
                    </th>
                    <th>
                        Last name
                    </th>
                    <th>
                        Birth date
                    </th>
                    <th>
                        Passport number
                    </th>
                    <th>
                        Passport issuance date
                    </th>
                    <th>
                        Passport expire date
                    </th>
                    <th>
                        Price
                    </th>
                    <th>
                        Created at
                    </th>
                    <th>
                        Passport image
                    </th>
                    <th>
                        Personal image
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($visas as $visa)
                    @if($visa->visa)
                    <tr>
                        <td>
                            {{ $visa->visa->visa_type['en'] }}
                        </td>
                        <td>
                            {{ $visa->first_name }}
                        </td>
                        <td>
                            {{ $visa->last_name }}
                        </td>
                        <td>
                            {{ $visa->birth_date->format('Y-m-d') }}
                        </td>
                        <td>
                            {{ $visa->passport_number }}
                        </td>
                        <td>
                            {{ $visa->passport_issuance_date->format('Y-m-d') }}
                        </td>
                        <td>
                            {{ $visa->passport_expire_date->format('Y-m-d') }}
                        </td>
                        <td>
                            {{ $visa->price }}
                        </td>
                        <td>
                            {{ $visa->created_at->format('Y-m-d (h:s A)') }}
                        </td>
                        <td>
                            <img src="{{ Storage::url($visa->passport_image) }}" alt=""
                                 style="width: 100px;height: 100px;">
                        </td>
                        <td>
                            <img src="{{ Storage::url($visa->personal_image) }}" alt=""
                                 style="width: 100px;height: 100px;">
                        </td>
                        <td>
                            @if($visa->status === '1')
                                Delivered
                                <a href="{{ route('visaDownloadPdf',['visa' => $visa->id]) }}">Download pdf</a>
                            @else
                                {{ $visa->status === '0' ? 'Pending' : $visa->status }}
                            @endif
                        </td>

                        <td>
                            @if($visa->status !== 'canceled')
                                <a href="#!" class="cancel-modal trip-info-modal btn btn-danger" data-toggle="modal"
                                   data-target="#m_modal_1"
                                   data-cancel-url="{{ route('cancelVisa',['order' => $visa->id]) }}">
                                    Cancel
                                </a>
                            @else
                                already canceled
                            @endif
                        </td>
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
            <!--end: Datatable -->
        </div>
    </div>

    <!--begin::Modal-->
    <div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Cancel Visa
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Are you sure that you want to cancel this visa ?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-danger" id="cancel">
                        Yes, Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->

@endsection

@section('scripts')
    <script src="{{ asset('default-assets/demo/default/custom/components/datatables/base/html-table.js') }}"
            type="text/javascript"></script>
    <script>
        $(document).on('click', '.cancel-modal', function () {
            $('#cancel').val($(this).data('cancel-url'));
        });

        $("#cancel").click(function () {
            window.location = $(this).val();
        });
    </script>
@endsection