<link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/bootstrap-3.min.css">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" />

	<script src="http://demo.itsolutionstuff.com/plugin/jquery.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<section class="container">
    <div class="msg-height">
        <h5 class="mt-5 text-center">To all Sales outlets</h5>
        Please ensure to incorporate the passenger contact details in the PNR at the time of booking, failure to do so we will be obliged to charge you the OW fare for each segment in case the passenger being reported NOSHO.
    </div>
</section>
<section>
    <div class="container">

       
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#home">One Way</a></li>
      <li><a data-toggle="tab" href="#menu1">Two Way</a></li>

    </ul>
    <div class="tab-content">
  <div id="home" class="tab-pane fade in active">
    <form class="form-inline" action="{{url('/oneWay/charter/search')}}"   method="POST">
       <div class="row">
        {{ csrf_field() }}
           <div class="form-group col-sm-3">
               <p class=" mt-2">@lang('alnkel.from') @required()</p>
               <select class="form-control sel-status" name="startFrom" >
                   @foreach($countries as $country)
                       <option value="{{ $country->id }}"
                       >{{ $country->name['ar'] }}
                       </option>
                   @endforeach
               </select>
           </div>

           <div class="form-group col-sm-3">
               <p class=" mt-2">@lang('alnkel.to') @required()</p>

               <select class="form-control sel-status" name="endTo">
                   @foreach($countries as $country)
                       <option value="{{ $country->id }}"
                       >{{ $country->name['ar'] }}
                       </option>
                   @endforeach
               </select>
           </div>

           <div class="form-group col-sm-2" >
               <p class="">@lang('alnkel.Adults')@required() </p>
               <input type="number" required name="adults" class="sty-option form-control width70" min="0" max="9">
           </div>
 
           <div class="form-group col-sm-2">
               <p class=''>@lang('alnkel.Children')@required() </p>
               <input type="number" required name="children" class="sty-option form-control width70" min="0" max="9">
           </div>

           <div class="form-group col-sm-2">
               <p class="">@lang('alnkel.infants') @required() </p>
               <input type="number" name="infants" required class="sty-option form-control width70" min="0" max="9">

           </div>
           <div class="form-group col-sm-2">
               <p class="mt-1">@lang('alnkel.cabin_class') @required() </p>
               <select name="cabin_class" class="select-opt p-1 form-control h-34 width70">
                   <option value="economy">Economy</option>
                   <option value="business">Business</option>
               </select>
           </div>

           <div class="form-group col-sm-2.5">
               <p class=" mt-2 ml-2">Date @required()</p>
               <input type="date" required class="form-control  "   name="traveldate" >
           </div>

           <div class="form-group col-sm-2">
               <p class="">@lang('alnkel.departure')@required()</p>

               <span><select name="available" class="select-opt h-34 mt-1 form-control">
                    <option value="0" class="text-center">0</option>
                    <option value="1" class="text-center" selected>+/-1</option>
                    <option value="2" class="text-center">+/-2</option>
                    <option value="3" class="text-center">+/-3</option>
                </select></span>
           </div>
           <div class="col-sm-3 ml-5 mt-5">
               <input type="submit" class=" form-control btn btn-success p-2 width70" value="@lang('alnkel.search')">
           </div>


       </div>
        </form>
  </div>
  <div id="menu1" class="tab-pane fade">
      <form class="form-inline" action="{{url('/oneWay/charter/search')}}"   method="post">
          <div class="row">
              {{ csrf_field() }}
              <input type="hidden" name="twoway" value="twoway">
              <div class="form-group col-sm-3">
                  <p class=" mt-2">@lang('alnkel.from') @required()</p>
                  <select class="form-control sel-status" name="startFrom" >
                      @foreach($countries as $country)
                          <option value="{{ $country->id }}"
                          >{{ $country->name['ar'] }}
                          </option>
                      @endforeach
                  </select>
              </div>

              <div class="form-group col-sm-3">
                  <p class=" mt-2">@lang('alnkel.to') @required()</p>

                  <select class="form-control sel-status" name="endTo">
                      @foreach($countries as $country)
                          <option value="{{ $country->id }}"
                          >{{ $country->name['ar'] }}
                          </option>
                      @endforeach
                  </select>
              </div>

              <div class="form-group col-sm-2">
                  <p class="mt-4">@lang('alnkel.Adults') @required()</p>
                  <input type="number" name="adults" class="sty-option form-control" min="0" max="9" required>
              </div>


              <div class="form-group col-sm-2">
                  <p class="mt-4">@lang('alnkel.Children') @required()</p>
                  <input type="number" name="children" class="sty-option form-control" min="0" max="9" required>
              </div>

              <div class="form-group col-sm-2">
                  <p class="mt-4">@lang('alnkel.infants')@required() </p>
                  <input type="number" name="infants" class="sty-option form-control" min="0" max="9" required>

              </div>

              <div class="form-group col-sm-2">
                  <p class=" mt-2">Date @required()</p>
                  <input type="date" required name="traveldate" class="form-control">
              </div>
              <div class="form-group col-sm-2">
                  <p class="">@lang('alnkel.departure')@required()</p>

                  <span><select name="available_go" class="select-opt form-control h-34">
                      <option value="0">0</option>
                      <option value="1" selected>+/-1</option>
                      <option value="2">+/-2</option>
                      <option value="3">+/-3</option>
                  </select></span>
              </div>

              <div class="form-group col-sm-2">
                  <p class=" mt-2">Return Date @required()</p>
                  <input type="date" name="returndate" class="form-control" required>
              </div>

              <div class="form-group col-sm-2">
                  <p class="">@lang('alnkel.departure') @required()</p>

                  <span><select name="available" class="select-opt form-control h-34">
                      <option value="0">0</option>
                      <option value="1" selected>+/-1</option>
                      <option value="2">+/-2</option>
                      <option value="3">+/-3</option>
                  </select></span>
              </div>

              <div class="form-group col-sm-2">
                  <p class="">@lang('alnkel.cabin_class') </p>
                  <select name="cabin_class" class="form-control h-34">
                      <option value="economy">Economy</option>
                      <option value="business">Business</option>
                  </select>
              </div>

              <div class="col-sm-3 mt-5">
                  <input type="submit" class=" form-control btn btn-success p-2 width70" value="@lang('alnkel.search')">
              </div>
          </div>

      </form>
  </div>
 
</div>
    </div>

</section>

<div class="container m-t100">
@if(isset($result))
    <div class="text-center">
        <h3 class="text-center p-3 btn btn-primary">Flights going</h3>
        <hr>
    </div>
@if(count($result)>0)

 
<div class="table-responsive text-nowrap ">
        <!--Table-->
        <table class="table table-striped table-bordered">

          <!--Table head-->
          <thead class="thead-dark">
            <tr>
              
              <th>Flight Date</th>
              <th>Flight Number	</th>
              <th>Airline</th>

              <th>From</th>
              <th>TO</th>

              <th>Economy Seats</th>
             <th>Business Seats</th>

             <th>Departure Time</th>
             <th>Arriaval Time</th>


              <th>Economy Adult </th>
              <th>Economy Price</th>
              <th>Economy Price</th>
              <th>Business Adult </th>
              <th>Business Price</th>
              <th>Business Price</th>
              <th>{{__('charter.reserve')}}</th>
             
            </tr>
          </thead>
          <!--Table head-->

          <!--Table body-->
          <tbody>
          @foreach($result as $item)
            <tr>
              <td>{{$item->flight_date}}</td>
              <td>{{$item->flight_number}}</td>
              <td>{{$item->aircraft}}</td>

              <td>{{$startCountry}}</td>
              <td>{{$endCountry}}</td>

              <td>{{$item->economy_seats}}</td>
              <td>{{$item->business_seats}}</td>

              <td>{{$item->departure_time}}</td>
              <td>{{$item->arrival_time}}</td>

              <td>{{$item->price_adult}}</td>
              <td>{{$item->price_child}}</td>
              <td>{{$item->price_baby}}</td>
              <td>{{$item->business_adult}}</td>
              <td>{{$item->business_child}}</td>
              <td>{{$item->business_baby}}</td>
 
              <td>
{{--                <form method="POST" action="{{route('charterCheckout')}}">--}}
{{--                {{ csrf_field() }}--}}

{{--                   <input type="hidden" name="charter" value="{{$item->id}}">--}}

{{--                   <input type="hidden" name="flight_class" value="{{$flight_class}}">--}}
{{--                   <input type="hidden" name="reserve_adults" value="{{$reserve_adults}}">--}}
{{--                   <input type="hidden" name="reserve_children" value="{{$reserve_children}}">--}}
{{--                   <input type="hidden" name="reserve_babies" value="{{$reserve_babies}}">--}}
                   
                    @auth
                        <a class="main-button  charter-reserve" href="#" data-title="Charter #{{$item->id}} ({{$item->name}})" data-id="{{$item->id}}" data-checkout="{{route("charterCheckout")}}">
                            {{__('charter.reserve')}}
                        </a>
                        @endauth
{{--                </form>--}}
              </td>
           
            </tr>
          @endforeach
          </tbody>
          <!--Table body-->


        </table>
        <!--Table-->
      </div>

@else
    <h3 class="text-center mt-5">No Result</h3>
    @endif
@endif

@if(isset($return_result) )
        <div class="text-center">
            <h3 class="text-center p-3 btn btn-success">Return flights</h3>
            <hr>
        </div>
@if(count($return_result) > 0)

<div class="table-responsive text-nowrap mt-4">
        <!--Table-->
        <table class="table table-striped table-bordered">
        
                  <!--Table head-->
                  <thead class="thead-dark">
                    <tr>
                      
                      <th>Flight Date</th>
                      <th>Flight Number	</th>
                      <th>AirLine</th>

                      <th>From</th>
                      <th>TO</th>

                      <th>Economy Seats</th>
                      <th>business Seats</th>

                      <th>Departure Time</th>
                      <th>Arriaval Time</th>

                        <th>Economy Adult </th>
                        <th>Economy Price</th>
                        <th>Economy Price</th>
                        <th>Business Adult </th>
                        <th>Business Price</th>
                        <th>Business Price</th>
                        <th>{{__('charter.reserve')}}</th>
                    </tr>
                  </thead>
                  <!--Table head-->
        
                  <!--Table body-->
                  <tbody>
                  @foreach($return_result as $return_item)
                    <tr>
                      <td>{{$return_item->flight_date}}</td>
                      <td>{{$return_item->flight_number}}</td>
                      <td>{{$return_item->aircraft}}</td>

                    
                      <td>{{$endCountry}}</td>
                      <td>{{$startCountry}}</td>

                      <td>{{$return_item->economy_seats}}</td>
                     <td>{{$return_item->business_seats}}</td>

                     <td>{{$return_item->departure_time}}</td>
                     <td>{{$return_item->arrival_time}}</td>

                        <td>{{$return_item->price_adult}}</td>
                        <td>{{$return_item->price_child}}</td>
                        <td>{{$return_item->price_baby}}</td>
                        <td>{{$return_item->business_adult}}</td>
                        <td>{{$return_item->business_child}}</td>
                        <td>{{$return_item->business_baby}}</td>
                        <td>@auth
                                <a class="main-button  charter-reserve" href="#" data-title="Charter #{{$return_item->id}} ({{$return_item->name}})" data-id="{{$return_item->id}}" data-checkout="{{route("charterCheckout")}}">
                                    {{__('charter.reserve')}}
                                </a>
                            @endauth
                        </td>
                      
                    </tr>
                  @endforeach
                  </tbody>
                  <!--Table body-->
        
        
                </table>


        </table>
        <!--Table-->
      </div>
@else
    <div>
        <h3 class="text-center">No Result</h3>
    </div>
<!--Section: Live preview-->
@endif
@endif
</div>

@section('styles')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <style>
        body{
            background-color: #ececec;;
        }
        .m-t100{
            margin-top: 100px;
        }
        .h-34{
            height: 34px !important;
        }
        .width70{
            width: 70% !important;
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
            width: 124px;
            height: 33px;

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

<script type="text/javascript">

$(document).ready(function() {

  $(".sel-status").select2();

    $('input').each(function (i, field) {
        if($(field).hasClass('datepicker')) {
            $(field).val('30/09/2019');

        }});

    $(".datepicker").datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

});

</script>
@stop