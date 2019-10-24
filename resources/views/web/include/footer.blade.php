<!--
    **********************************
    Template:  footer
    Created at: 8/20/2019
    Author: Mohammed Hamouda
    **********************************

    -->
<footer class="main-footer text-right">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="site-logo text-center"> <img src="{{asset('public/assets/img/logo.png')}}" alt="logo"></div>
            </div>
            <div class="col-md-3">
                <div class="about">
                    <h4>عن مجموعة النخيل</h4>
                    <p>مجموعة النخيل تساعدك على ان تجد افضل عروض للرحلات و افضل الناشيرات</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="newslette">
                    <h4>النشرة البريدية</h4>
                    <p>{{ __('alnkel.footer-email') }}</p>
                    <div class="input-email">
                        <input class="form-control" type="text" placeholder="{{ __('alnkel.footer-write-email') }}"><i class="fas fa-envelope"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="contact">
                    <h4>تواصل معنا</h4>
                    <div class="whatsapp"><i class="fab fa-whatsapp"></i>{{ \App\Setting::first()->phone }}</div>
                    <div class="email"> <i class="fas fa-envelope"></i>info@alnkhel.com</div>
                    <div class="email"> <i class="fas fa-map-pin"></i>{{ \App\Setting::first()->address }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="info">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="copy">جميع الحقوق محفوظة 2019</div>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled site-map text-center">
                        <li><a href="#">سياسة الخصوصية</a></li>
                        <li><a href="#">من نحن</a></li>
                        <li><a href="#">اتصل بنا</a></li>
                        <li><a href="#">الشروط و التحكم</a></li>
                    </ul>
                </div>
                <div class="col-md-3 last">
                    <div class="follow text-left">تابعنا    <a href="#"><i class="fab fa-facebook-square">                                   </i></a><a href="#"> <i class="fab fa-instagram"></i></a><a href="#"> <i class="fab fa-twitter"> </i></a></div>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="modal fade" id="login_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container actions">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="login form-control">@lang('alnkel.login-login') </button>
                        </div>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="container add-new">
                    <form action="{{route('front_login')}}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="row">
                                <div class="col-md-12">
                                    <input class="login form-control" name="email" type="text" placeholder=" @lang('alnkel.register-email') "><i class="fas fa-user"></i>

                                </div>
                                <div class="col-md-12">
                                    <input class="login form-control" name="password" type="password" placeholder=" @lang('alnkel.register-password')"><i class="fas fa-lock"> </i>
                                </div>
                                <div class="col-md-12">
                                    <input type="submit" class="log form-control" value="@lang('alnkel.login-login')">

                                </div>
                        </div>
                    </form>
                </div>
                <div class="container rig-options">
                    <div class="row aligen-items">
{{--                        <div class="col-md-6 text-right small-center">--}}
{{--                            <label for="remember">@lang('alnkel.login-login')</label>--}}
{{--                            <input type="radio" id="remember" checked>--}}
{{--                        </div>--}}
                        <div class="col-md-6 text-left small-center">
                            <a href="#">@lang('alnkel.forget_password')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="register_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container actions">

                    <div class="row">

                        <div class="col-md-12">
                            <button class="login form-control">@lang('alnkel.register-register')</button>
                        </div>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($message = Session::get('register-success'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
                    </div>
                    </form>
                </div>
                <form action="{{route('user-register')}}" method="post">

                <div class="container add-new">
                    <div class="row">
                        {{ method_field('PUT') }}
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="col-md-12">
                            <input class="login form-control" type="text" name="name" placeholder="@lang('alnkel.register-name')"><i class="fas fa-user"></i>
                        </div>
                        <div class="col-md-12">
                            <input class="login form-control" type="text" name="register_email" placeholder=" @lang('alnkel.register-email')"><i class="fas fa-envelope"> </i>
                        </div>
                        <div class="col-md-12">
                            <input class="login form-control" type="text" name="company" placeholder=" @lang('alnkel.register-company')"><i class="fas fa-building"> </i>
                        </div>
                        <div class="col-md-12">
                            <input class="login form-control" type="text" name="phone" placeholder="  @lang('alnkel.register-phone')"><i class="fas fa-phone"> </i>
                        </div>
                        <div class="col-md-12">
                            <input class="login form-control" type="text" name="address" placeholder="  @lang('alnkel.register-address')"><i class="fas fa-address-book"> </i>
                        </div>
                        <div class="col-md-12">
                            <input class="login form-control" type="password" name="register_password" placeholder="  @lang('alnkel.register-password')"><i class="fas fa-lock"> </i>
                        </div>
                        <div class="col-md-12">
                            <input class="login form-control" type="password" name="register_password_confirmation" placeholder=" @lang('alnkel.register-password-confirmation')"><i class="fas fa-lock"> </i>
                        </div>
                        <div class="col-md-12">
                            <input type="submit" class="log form-control" value="@lang('alnkel.register-register')">
                        </div>
                    </div>
                </div></form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{asset('public/assets/js/main.js')}}"></script>
<script type="text/javascript">
    $(window).on('load',function(){
            var msg=$(".msg")
            $(".read").click(function(){
                $('.msg').slideUp(4000);

            })
        msg.animate({
                    top:'-100px',
                    left:'+=300px'
                }
                ,3000)
        msg.animate({

                borderRadius:'10px'
            },2000)
    });
</script>
</body>
</html>