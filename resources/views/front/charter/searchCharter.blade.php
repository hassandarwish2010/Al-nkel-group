@extends('layouts.master-front')

@section('title')
    {{ __('alnkel.searchcharter') }}
@endsection

@section('page-header')
    <!-- Start page-heder -->
    <div class="page-header">
        <!-- Start container-fluid -->
        <div class="container-fluid">
            <!-- Start d-flex -->
            <div class="d-flex align-items-center justify-content-center">
                <i class="icons8-plane"></i>
                <span>{{ __('alnkel.searchcharter') }}</span>
            </div>
            <!-- End d-flex -->
        </div>
        <!-- End container-fluid -->
    </div>
    <!-- End page-header -->
@endsection
@section('content')
    <section class="container-fluid search-charter">
        <h5 class=" mb-5 text-center">{{ __('alnkel.searchcharter') }}</h5>
        <form action="{{route('get.result')}}" method="post">

      <div class="row">

          <input type="hidden" name="_token" value="{{csrf_token()}}">
          <div class="col-sm-4">
              <div class="row">
                  <div class="col-sm-6">
                      <p>@lang('alnkel.pnr')</p>
                  </div>
                  <div class="col-sm-6">
                      <input type="text" name="pnr">
                  </div>
                  <div class="col-sm-6">
                      <p>@lang('alnkel.ticketnum')</p>
                  </div>
                  <div class="col-sm-6">
                      <input type="text" name="ticket_num">
                  </div>
              </div>
          </div>
          <div class="col-sm-4">
              <div class="row">
                  <div class="col-sm-6">
                      <p>@lang('alnkel.first_name')</p>
                  </div>
                  <div class="col-sm-6">
                      <input type="text" name="first_name">
                  </div>
                  <div class="col-sm-6">
                      <p>@lang('alnkel.fromdate')</p>
                  </div>
                  <div class="col-sm-6">
                      <input type="date" name="from_date">

                  </div>
              </div>
          </div>
          <div class="col-sm-4">
              <div class="row">
                  <div class="col-sm-5">
                      <p>@lang('alnkel.last_name')</p>
                  </div>
                  <div class="col-sm-4">
                      <input type="text" name="last_name">
                  </div>
                  <div class="col-sm-5">
                      <p>@lang('alnkel.todate')</p>
                  </div>
                  <div class="col-sm-4">
                      <input type="date" name="to_date">
                  </div>

              </div>
          </div>
      </div>
        <div class="col-sm-12">
            <input type="submit" value="@lang('alnkel.search')" class="btn btn-primary flo search-charter">
        </div>
        </form>
    </section>
    @isset($rows)
    @if (count($rows)>0)
        <section class="mt-4 container table-st">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <table class="table table-striped" cellspacing="0">
                        <thead class="thead-dark">
                        <tr>
                            <th>@lang('alnkel.first_name')</th>
                            <th>@lang('alnkel.last_name')</th>
                            <th>@lang('alnkel.pnr')</th>
                            <th>@lang('alnkel.ticket_number')</th>
                            <th>@lang('alnkel.flight_class')</th>
                            <th>@lang('alnkel.created_at')</th>
                            <th>@lang('alnkel.age')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($rows as $row)
                        <tr>
                                <td class="">{{$row->first_name}}</td>
                                <td class="">{{$row->last_name}}</td>
                                <td class="">{{$row->pnr}}</td>
                                <td class="">{{$row->ticket_number}}</td>
                                <td class="">{{$row->flight_class}}</td>
                                <td class="">{{\Carbon\Carbon::parse($row->created_at)->format('d/m/Y')}}</td>
                                <td class="">{{$row->age}}</td>

                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </section>
    @endif
    @endisset

@endsection

@section('styles')
    <style>
        .table-st{
            background-color: #fff;
            margin-top: 140px !important;
            border-radius: 10px;
            padding: 30px 10px;
        }
        .search-charter{
            margin-top: 60px;
        }
        .flo{
            float: right;
        }
    </style>
    @stop