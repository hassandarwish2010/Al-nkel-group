@extends('layouts.master-front')

@section('title')
    Ticket Search
@endsection

@section('page-header')
    <!-- Start page-heder -->
    <div class="page-header">
        <!-- Start container-fluid -->
        <div class="container-fluid">
            <!-- Start d-flex -->
            <div class="d-flex align-items-center justify-content-center">
                <i class="icons8-plane"></i>
                <span>Ticket Search</span>
            </div>
            <!-- End d-flex -->
        </div>
        <!-- End container-fluid -->
    </div>
    <!-- End page-header -->
@endsection

@section('style')
    <style>
        .search, .main-news-title, .general-news {
            display: none;
        }
    </style>
@endsection

@section('content')

    <!-- Start container-fluid -->
    <div class="container-fluid">
        <!-- Start row -->
        <div class="row" style="padding: 20px;">
            <form style="margin: 0 auto; width: 430px;">
                <button class="btn btn-danger float-right">Search</button>
                <div class="input-gp" style="width: 320px; float: right;">
                    <i class="icons8-search"></i>
                    <input type="text" placeholder="Search with PNR" name="pnr" value="{{Request()->get("pnr")}}"
                           style="background: #112740;color: #fff;">
                </div>
            </form>
        </div>
    </div>

    <div style="padding: 10px;">
        @if( isset($ticket))

            @if($isAdmin)
            <a href="https://alnkhel.com/admin/charter/{{$order->charter->id}}/orders?pnr={{Request()->get("pnr")}}" class="btn btn-sm btn-warning" style="margin: 20px auto 50px auto;display: table;">
                التذكرة في لوحة التحكم
            </a>
            @endif

            {!!$ticket!!}
        @else
            <div style="text-align: center;">No Tickets Found</div>
        @endif
    </div>

@endsection