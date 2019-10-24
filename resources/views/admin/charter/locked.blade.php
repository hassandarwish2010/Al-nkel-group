@extends('layouts.master')

@section('page-title')
    Locked Seats
@endsection

@section('sub-header')
    Locked Seats
@endsection

@section('content')
    @include('includes.info-box')

    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        ({{$charter->name}}) Locked Seats
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <form class="m-form m-form--label-align-right m-form--group-seperator-dashed"
                  method="post"
                  action="{{ route('storeLocked', ['charter' => $charter->id]) }}" enctype="multipart/form-data">

                {!! csrf_field() !!}

                <input type="hidden" name="charter_id" value="{{$charter->id}}" />

                <div class="form-group m-form__group row going-section" style="background: #f7f7f7;margin: 0 0 20px;border: 1px solid #efeeee;">
                    <div class="col-lg-12 mb-3">
                        <h4>Add locked seats for company</h4>
                    </div>

                    <div class="col-lg-3">
                        <label>Select <span class="text-danger">Company</span></label>
                        <div class="input-group m-input-group m-input-group--square">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <select class="form-control select2" name="user_id">
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">[{{ $company->id }}] {{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <label>
                            Number of <span class="text-danger">seats</span>:
                        </label>
                        <div class="input-group m-input-group m-input-group--square">
                            <span class="input-group-addon"><i class="fa fa-plane"></i></span>
                            <input type="number" name="seats"
                                   placeholder="Number of seats" class="form-control m-input"
                                   value="{{ old('seats') }}" required>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <label>
                            Price:
                        </label>
                        <div class="input-group m-input-group m-input-group--square">
                            <span class="input-group-addon"><i class="fa fa-money"></i></span>
                            <input type="number" name="price"
                                   placeholder="Price of seats" class="form-control m-input"
                                   value="{{ old('price') }}" required>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <button type="submit" class="btn btn-primary" style="margin-top: 25px;">
                            Add locked seats
                        </button>
                    </div>

                    <div class="col-lg-12 mt-3">
                        <span class="text-muted">Note: If the company already has locked seats the number you entered will be added to it.</span>
                    </div>
                </div>

            </form>

            <table id="data-tables" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Company Name</th>
                    <th>Locked Seats</th>
                    <th>Actions </th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('footer')
    {!! deleteModalHTML('commission') !!}
@endsection

@section('scripts')
    <script>
        $('.select2').select2();

        var table = $('#data-tables').DataTable({
            serverSide: true,
            processing: true,
            scrollX: true,
            ajax: "{{route('lockedData', ['charter' => $charter->id])}}",
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0,
            },
            columns: [
                {data: 'id'},
                {data: 'user_id'},
                {data: 'seats'},
                {data: 'actions'},
            ],
        });
    </script>
@endsection