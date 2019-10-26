@extends('layouts.master')

@section('page-title')
    Profile
@endsection

@section('sub-header')
    Profile
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="m-portlet m-portlet--full-height  ">
                <div class="m-portlet__body">
                    <div class="m-card-profile">
                        <div class="m-card-profile__title m--hide">
                            Your Profile
                        </div>
                        <div class="m-card-profile__pic">
                            <div class="m-card-profile__pic-wrapper">
                                <img src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('custom/images/avatar.jpg') }}" alt=""/>
                            </div>

                            <button class="btn btn-sm btn-info change-avatar" style="margin-bottom: 10px;">
                                Change Logo
                            </button>

                            <form action="{{route('changeAvatar')}}" method="post" style="margin-bottom: 15px;display: none;" enctype="multipart/form-data" class="change-avatar-form">
                                <input type="file" name="avatar" />
                                {!! csrf_field() !!}
                            </form>
                        </div>
                        <div class="m-card-profile__details">
                            <span class="m-card-profile__name">
                                {{ Auth::user()->name }}
                            </span>
                            <a class="m-card-profile__email m-link" style="display: block;">
                                {{ Auth::user()->email }}
                            </a>

                            <br>
                            <h4 class="m-card-profile__email m-link">
                                Balance: <span style="color:#fff; font-weight: bold;background-color:#4232ba" class="m-3 p-3">{{ Auth::user()->balance }}</span>
                            </h4><br><br>
                            <h4 class="m-card-profile__email m-link">
                                Commission: <span style="color:#fff; font-weight: bold;background-color:#4232ba" class="m-3 p-3">{{\App\CharterOrders::where('user_id',Auth::user()->id )->sum('commission')}}</span>
                            </h4>

                        </div>
                    </div>
                    <ul class="m-nav m-nav--hover-bg m-portlet-fit--sides">
                        <li class="m-nav__separator m-nav__separator--fit"></li>
                        <li class="m-nav__section m--hide">
                            <span class="m-nav__section-text">
                                Section
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-9 col-lg-8">
            <div class="m-portlet m-portlet--full-height m-portlet--tabs  ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-tabs m-tabs m-tabs-line   m-tabs-line--left m-tabs-line--primary"
                            role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_user_profile_tab_1"
                                   role="tab">
                                    <i class="flaticon-share m--hide"></i>
                                    Profile
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_user_profile_tab_2"
                                   role="tab">
                                    Password
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="m_user_profile_tab_1">
                        <form class="m-form m-form--fit m-form--label-align-right" method="post"
                              action="{{ route('updateProfile') }}">
                            <div class="m-portlet__body">
                                <div class="form-group m-form__group m--margin-top-10">
                                    @include('includes.info-box')
                                </div>
                                <div class="form-group m-form__group row">
                                    <label for="example-text-input" class="col-2 col-form-label">
                                        Name
                                    </label>
                                    <div class="col-7">
                                        <input class="form-control m-input" type="text"
                                               value="{{ Auth::user()->name }}" name="name" @foruser(readonly)>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label for="example-text-input" class="col-2 col-form-label">
                                        Email
                                    </label>
                                    <div class="col-7">
                                        <input class="form-control m-input" type="text"
                                               value="{{ Auth::user()->email }}" name="email" @foruser(readonly)>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label for="example-text-input" class="col-2 col-form-label">
                                        Company Name
                                    </label>
                                    <div class="col-7">
                                        <input class="form-control m-input" type="text"
                                               value="{{ Auth::user()->company }}" name="company" @foruser(readonly)>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label for="example-text-input" class="col-2 col-form-label">
                                        Address
                                    </label>
                                    <div class="col-7">
                                        <input class="form-control m-input" type="text"
                                               value="{{ Auth::user()->address }}" name="address" @foruser(readonly)>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label for="example-text-input" class="col-2 col-form-label">
                                        Phone
                                    </label>
                                    <div class="col-7">
                                        <input class="form-control m-input" type="text"
                                               value="{{ Auth::user()->phone }}" name="phone" @foruser(readonly)>
                                    </div>
                                </div>
                            </div>
                            {!! csrf_field() !!}
                            @isadmin()
                            <div class="m-portlet__foot m-portlet__foot--fit">
                                <div class="m-form__actions">
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-7">
                                            <button type="submit" class="btn btn-accent m-btn m-btn--air m-btn--custom">
                                                Save changes
                                            </button>
                                            &nbsp;&nbsp;
                                            <button type="reset"
                                                    class="btn btn-secondary m-btn m-btn--air m-btn--custom">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endisadmin
                        </form>
                    </div>
                    <div class="tab-pane" id="m_user_profile_tab_2">
                        <form class="m-form m-form--fit m-form--label-align-right" method="post"
                              action="{{ route('updatePassword') }}">
                            <div class="m-portlet__body">
                                <div class="form-group m-form__group m--margin-top-10">
                                    @include('includes.info-box')
                                </div>
                                <div class="form-group m-form__group row">
                                    <div class="col-10 ml-auto">
                                        <h3 class="m-form__section">
                                            Change password
                                        </h3>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label for="example-text-input" class="col-2 col-form-label">
                                        Password
                                    </label>
                                    <div class="col-7">
                                        <input class="form-control m-input" type="password" name="password">
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label for="example-text-input" class="col-2 col-form-label">
                                        New password
                                    </label>
                                    <div class="col-7">
                                        <input class="form-control m-input" type="password" name="new_password">
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label for="example-text-input" class="col-2 col-form-label">
                                        Re-enter your password
                                    </label>
                                    <div class="col-7">
                                        <input class="form-control m-input" type="password"
                                               name="new_password_confirmation">
                                    </div>
                                </div>
                            </div>
                            {!! csrf_field() !!}
                            <div class="m-portlet__foot m-portlet__foot--fit">
                                <div class="m-form__actions">
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-7">
                                            <button type="submit" class="btn btn-accent m-btn m-btn--air m-btn--custom">
                                                Save changes
                                            </button>
                                            &nbsp;&nbsp;
                                            <button type="reset"
                                                    class="btn btn-secondary m-btn m-btn--air m-btn--custom">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('[name=avatar]').change(function () {
            $(this).parent('form').submit();
        });

        $('.change-avatar').click(function () {
            $('.change-avatar-form').slideToggle();
        });
    </script>
@endsection