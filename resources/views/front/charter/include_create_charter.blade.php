<section class="container">
    <div class="msg-height">
        <h5 class="mt-5 text-center">To all Sales outlets</h5>
        Please ensure to incorporate the passenger contact details in the PNR at the time of booking, failure to do so we will be obliged to charge you the OW fare for each segment in case the passenger being reported NOSHO.
    </div>
</section>
<section>
    <div class="container">

        <ul class="nav nav-tabs nav-sty">
            <li ><a data-toggle="tab" href="#one_way">@lang('alnkel.one_way')</a></li>
            <li><a data-toggle="tab" href="#return">@lang('alnkel.return')</a></li>
            <li><a data-toggle="tab" href="#open_return">@lang('alnkel.open_return')</a></li>
            {{--                <li style="float: right;"><a data-toggle="tab" >@lang('alnkel.availability')</a></li>--}}
        </ul>

        <div class="row">
            <form>
            <div class="col-sm-5">
                <div class="tab-content">
                    <div id="one_way" class="tab-pane fade in active">
                        <div class="row p-4">
                            <div class="co-sm-5">
                                <p class=" mt-2">@lang('alnkel.from') *</p>
                                <input type="text" name="from">
                            </div>
                            <div class="col-sm-1"></div>
                            <div class="co-sm-5">
                                <p class=" mt-2">@lang('alnkel.to') *</p>
                                <input type="text" name="to">
                            </div>
                            <div class="co-sm-5">
                                <p class="mt-4">@lang('alnkel.departure') *</p>
                                <input type="date" name="to"><span><select name="available" class="select-opt">
                                            <option value="">0</option>
                                            <option value="">+/-1</option>
                                            <option value="">+/-2</option>
                                            <option value="">+/-3</option>
                                        </select></span>
                            </div>
                        </div>
                    </div>
                    <div id="return" class="tab-pane fade ">
                        <div class="row p-4">
                            <div class="co-sm-5">
                                <p class=" mt-2">@lang('alnkel.from') *</p>
                                <input type="text" name="from">
                            </div>
                            <div class="col-sm-1"></div>
                            <div class="co-sm-5">
                                <p class=" mt-2">@lang('alnkel.to') *</p>
                                <input type="text" name="to">
                            </div>
                            <div class="co-sm-5">
                                <p class="mt-4">@lang('alnkel.departure') *</p>
                                <input type="date" name="to"><span><select name="available" class="select-opt">
                                            <option value="">0</option>
                                            <option value="">+/-1</option>
                                            <option value="">+/-2</option>
                                            <option value="">+/-3</option>
                                        </select></span>
                            </div>
                            <div class="co-sm-5">
                                <p class="mt-4">@lang('alnkel.return') *</p>
                                <input type="date" name="to"><span><select name="available" class="select-opt">
                                            <option value="0">0</option>
                                            <option value="1">+/-1</option>
                                            <option value="2">+/-2</option>

                                        </select></span>
                            </div>
                        </div>
                    </div>

                    <div id="open_return" class="tab-pane fade">
                        <div class="row p-4">
                            <div class="co-sm-5">
                                <p class=" mt-2">@lang('alnkel.from') *</p>
                                <input type="date" name="from">
                            </div>
                            <div class="col-sm-1"></div>
                            <div class="co-sm-5">
                                <p class=" mt-2">@lang('alnkel.to') *</p>
                                <input type="date" name="to">
                            </div>
                            <div class="co-sm-5">
                                <p class="mt-4">@lang('alnkel.departure') *</p>
                                <input type="date" name="to"><span><select name="available" class="select-opt">
                                            <option value="">0</option>
                                            <option value="">+/-1</option>
                                            <option value="">+/-2</option>
                                            <option value="">+/-3</option>
                                        </select></span>
                            </div>
                            <div class="col-sm-4">
                                <p class="mt-4">@lang('alnkel.open_r_Validity') </p>
                                <select name="count_month">
                                    @for($i=1;$i<=6;$i++)
                                    <option value="{{$i}}">{{$i}} month</option>
                                        @endfor

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-7">
                <div class="row">
                    <div class="">
                        <p class="mt-4">@lang('alnkel.Adults') </p>
                        <input type="number" name="adults" class="sty-option" min="0">
                    </div>
                    <div class="">
                        <p class="mt-4">@lang('alnkel.Children') </p>
                        <input type="number" name="children" class="sty-option" min="0">
                    </div>
                    <div class="">
                        <p class="mt-4">@lang('alnkel.baby') </p>
                        <input type="number" name="infants" class="sty-option" min="0">
                    </div>
                    <div class="">
                        <p class="mt-4">@lang('alnkel.cabin_class') </p>
                        <select name="cabin_class">
                            <option value="economy">Y-Economy</option>
                            <option value="business">Business</option>
                        </select>
                    </div>

                </div>
            </div>
            <div class="text-center col-sm-12">
                <input type="submit" class="p-3 btn btn-primary" value="@lang('alnkel.search')">
            </div>
            </form>
        </div>
    </div>

</section>

@section('styles')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <style>
        body{
            background-color: #ececec;;
        }
        .radio{
            margin-top: 42px;
            margin-left: 40px;
        }
        .sty-option{
            width: 100px;
            margin-right: 20px;
            margin-bottom: 20px;
        }
        .select-opt{
            width: 43px;
            height: 26px;
            margin-left:5px;
            margin-right:5px;
        }
        .nav-sty{
            background-color: #cbccce;;
        }
        .nav-sty li{
            background-color: #818588;
            color: #fff;
        }
        .nav-sty li a{
            color: #fff;
        }
        .msg-height{
            height: 200px;
            padding: 20px;
        }
        .msg-height h5{
            font-weight: bold;
        }
    </style>
@stop