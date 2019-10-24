@extends('layouts.master-front')

@section('title')
    {{ __('alnkel.flights') }}
@endsection

@section('page-header')
    <!-- Start page-heder -->
    <div class="page-header">
        <!-- Start container-fluid -->
        <div class="container-fluid">
            <!-- Start d-flex -->
            <div class="d-flex align-items-center justify-content-center">
                <i class="icons8-plane"></i>
                <span>{{ __('alnkel.flights') }}</span>
            </div>
            <!-- End d-flex -->
        </div>
        <!-- End container-fluid -->
    </div>
    <!-- End page-header -->
@endsection

@section('content')
    @auth
     <section class="container">
         <div class="row">
             <div class="col-sm-2"></div>
             <div class="col-sm-8 white-class">
                 <div class="row ">
                     <div class="col-sm-6 border-r">
                         <h5 class="text-center p-3">@lang('alnkel.create_booking')</h5>
                         <a href="{{route('charter.create')}}" class="text-center icon-search fa fa-plus-circle fa-3x">

                         </a>
                     </div>
                     <div class="col-sm-6">
                         <h5 class="text-center p-3">@lang('alnkel.search_booking')</h5>
                         <a href="{{route('charter.search')}}" class="text-center icon-search fa fa-search-plus fa-3x">

                         </a>
                     </div>
                 </div>
             </div>
             <div class="col-sm-2"></div>
         </div>
     </section>
        @else
        @include('front.charter.include_create_charter')
     @endauth
    <section class="garter-offers">
        <div class="slider-box text-center">
            <button class="gartert-btn slider-button">{{ __('charter.available_flights_oneway') }}</button>

            <div class="slider-content">
                <ul class="slider">
                    <li>
                        @foreach($oneWayFlights as $charter)
                            @include("includes.front.charter_item")
                        @endforeach
                    </li>
                    <li></li>
                </ul>
            </div>
        </div>

        <div class="slider-box text-center">
            <button class="gartert-btn slider-button">{{ __('charter.available_flights_twoway') }}</button>

            <div class="slider-content">
                <ul class="slider">
                    <li>
                        @foreach($twoWayFlights as $charter)
                            @include("includes.front.charter_item")
                        @endforeach
                    </li>
                    <li></li>
                </ul>
            </div>
        </div>
    </section>

@endsection

@section('styles')
   <style>
       .border-r{
           border-right: 1px solid #baba;
       }

       .icon-search{
           width: 100%;
           margin-top: 20px;
           padding-bottom: 20px;
       }
       .white-class{
           border: 1px solid #baba;
           background-color: #fff;
           padding: 10px;
          margin-top: 40px;
           border-radius: 4px;
       }
   </style>
@stop
@section('scripts')
    <script>
        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endsection