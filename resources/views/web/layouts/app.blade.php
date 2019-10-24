<!DOCTYPE html>
<html lang="ar">
<head>
    <title>مجموعة النخيل</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Cairo&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/css/style.css')}}">

    <style>
        .visa-dropdown {
            @if(App::getLocale() == "en")
left: -560px !important;
            @else
right: -560px !important;
            @endif
transform: translate3d(0, 45px, 0px) !important;
        }

        .visa-dropdown a {
            width: 33%;
            float: right;
            margin-bottom: 10px;
            text-align: right;
            font-size: 13px;
            margin-right: 0!important;
        }

        a.btn.show-all-visa {
            margin-bottom: 0 !important;
            background: #112740;
            border-color: #112740;
            color: #fff!important;
            float: none;
        }

        .visa-dropdown a img {
            @if(App::getLocale() == "en")
margin-left: 10px;
            @else
margin-right: 10px;
        @endif


}

        .msg{
            padding: 40 auto;
            background-color: #fff;

            margin: 170px auto ;
            width: 600px;
            border-radius: 5px;
            position: absolute;
            top:-580px;
        }

        .title-msg{
            color: #fe0068;
            font-size: 18px;
            text-align: center;
            font-weight: bold;
        }

        .read{
            color: aqua;
            font-style: italic;
            margin: 30px auto;
            padding: 10px;
            border-radius: 8px;
            margin-left: 221px;
            background-color: black;
        }



    </style>
</head>

@include('web.include.header')
<body>
@yield('content')
</body>
@include('web.include.footer')

</html>

