@extends('web.layouts.app')
@section('title', 'النخيل |  الرئيسية')
@section('content')

    <!--
    **********************************
    Template:  garter offers
    Created at: 8/19/2019
    Author: Mohammed Hamouda
    **********************************

    -->

    @auth
        <?php
        $user_id=Auth::user()->id;
        $row=\App\UserMessages::where(['user_id'=>$user_id,'status'=>0])->orderBy('created_at','desc')->first()?>
    @if ($row)
        <div class="msg">
            <p class="title-msg mt-2" >@lang('elnkel.have_msg')</p>
            <hr>
            <p class="m-4">{{\App\Message::where('id',$row->message_id)->first()->title}}</p>
            <input type="button " class="read mb-4 mt-4" value="@lang('elnkel.read_msg')">
        </div>
    @endif
    @endauth
    <section class="garter-offers">
        <div class="slider-box text-center">
            <button class="gartert-btn slider-button">{{ __('alnkel.flights') }}</button>
            <div class="slider-content">
                <ul class="slider" id="js_garter_slider">
                    <li>
                        @foreach($charters as $charter)
                            @include("includes.front.charter_item")

                        @endforeach
                    </li>
                    <li></li>
                </ul>
            </div>
        </div>
    </section>

@endsection
