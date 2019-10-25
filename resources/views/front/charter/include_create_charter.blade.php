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
    <form class="form-inline" action="{{url('/oneWay/charter/search')}}"   method="post">

        {{ csrf_field() }}
        <div class="form-group mb-2">
        <p class=" mt-2">@lang('alnkel.from') *</p>
        <select class="form-control sel-status" name="startFrom">
            @foreach($countries as $country)
            <option value="{{ $country->id }}"
                >{{ $country->name['ar'] }}
            </option>
            @endforeach
        </select>
        </div>

       

        <div class="form-group mx-sm-3 mb-2">
        <p class=" mt-2">@lang('alnkel.to') *</p>
        
             <select class="form-control sel-status" name="endTo">
             @foreach($countries as $country)
             <option value="{{ $country->id }}"
                 >{{ $country->name['ar'] }}
             </option>
             @endforeach
             </select>
        </div>

        <div class="form-group mb-2">
        <p class=" mt-2">Date *</p>
        <input type="date" name="traveldate">
        </div>

        <div class="form-group mx-sm-3 mb-2">
        <p class="mt-4">@lang('alnkel.departure') *</p>
        
        <span><select name="available" class="select-opt">
                    <option value="0">0</option>
                    <option value="1">+/-1</option>
                    <option value="2">+/-2</option>
                    <option value="3">+/-3</option>
                </select></span>
        </div>

        <div class="form-group mx-sm-3 mb-2">
        <p class="mt-4">@lang('alnkel.Adults') </p>
        <input type="number" name="adults" class="sty-option" min="0">
        </div>


        <div class="form-group mx-sm-3 mb-2">
        <p class="mt-4">@lang('alnkel.Children') </p>
        <input type="number" name="children" class="sty-option" min="0">
        </div>

        <div class="form-group mx-sm-3 mb-2">
        <p class="mt-4">@lang('alnkel.baby') </p>
        <input type="number" name="infants" class="sty-option" min="0">

        </div>

        <div class="form-group mx-sm-3 mb-2">
        <p class="mt-4">@lang('alnkel.cabin_class') </p>
        <select name="cabin_class">
            <option value="economy">Economy</option>
            <option value="business">Business</option>
        </select>
        </div>




        <input type="submit" class=" btn btn-success p-3" value="@lang('alnkel.search')">
        </form>
  </div>
  <div id="menu1" class="tab-pane fade">
  <form class="form-inline" action="{{url('/oneWay/charter/search')}}"   method="post">
  
          {{ csrf_field() }}

          <input type="hidden" name="twoway" value="twoway">
          <div class="form-group mb-2">
          <p class=" mt-2">@lang('alnkel.from') *</p>
          <select class="form-control sel-status" name="startFrom">
              @foreach($countries as $country)
              <option value="{{ $country->id }}"
                  >{{ $country->name['ar'] }}
              </option>
              @endforeach
          </select>
          </div>
  
         
  
          <div class="form-group mx-sm-3 mb-2">
          <p class=" mt-2">@lang('alnkel.to') *</p>
          
               <select class="form-control sel-status" name="endTo">
               @foreach($countries as $country)
               <option value="{{ $country->id }}"
                   >{{ $country->name['ar'] }}
               </option>
               @endforeach
               </select>
          </div>
  
          <div class="form-group mb-2">
          <p class=" mt-2">Date *</p>
          <input type="date" name="traveldate">
          </div>

          <div class="form-group mb-2">
          <p class=" mt-2">Return Date *</p>
          <input type="date" name="returndate">
          </div>
  
          <div class="form-group mx-sm-3 mb-2">
          <p class="mt-4">@lang('alnkel.departure') *</p>
          
          <span><select name="available" class="select-opt">
                      <option value="0">0</option>
                      <option value="1">+/-1</option>
                      <option value="2">+/-2</option>
                      <option value="3">+/-3</option>
                  </select></span>
          </div>
  
          <div class="form-group mx-sm-3 mb-2">
          <p class="mt-4">@lang('alnkel.Adults') </p>
          <input type="number" name="adults" class="sty-option" min="0">
          </div>
  
  
          <div class="form-group mx-sm-3 mb-2">
          <p class="mt-4">@lang('alnkel.Children') </p>
          <input type="number" name="children" class="sty-option" min="0">
          </div>
  
          <div class="form-group mx-sm-3 mb-2">
          <p class="mt-4">@lang('alnkel.baby') </p>
          <input type="number" name="infants" class="sty-option" min="0">
  
          </div>
  
          <div class="form-group mx-sm-3 mb-2">
          <p class="mt-4">@lang('alnkel.cabin_class') </p>
          <select name="cabin_class">
              <option value="economy">Economy</option>
              <option value="business">Business</option>
          </select>
          </div>
  
  
  
  
          <input type="submit" class=" btn btn-success p-3" value="@lang('alnkel.search')">
          </form>
  </div>
 
</div>
    </div>


@if(isset($result))
<div class="table-responsive text-nowrap">
        <!--Table-->
        <table class="table table-striped">

          <!--Table head-->
          <thead>
            <tr>
              
              <th>Flight Date</th>
              <th>Flight Number	</th>
             
              <th>Adult Price</th>
              <th>Child Price</th>
              <th>Baby Price</th>
             
            </tr>
          </thead>
          <!--Table head-->

          <!--Table body-->
          <tbody>
          @foreach($result as $item)
            <tr>
              <td>{{$item->flight_date}}</td>
              <td>{{$item->flight_number}}</td>
 
              <td>{{$item->price_adult}}</td>
              <td>{{$item->price_child}}</td>
              <td>{{$item->price_baby}}</td>
 
              <th>Business Price</th>
            </tr>
          @endforeach
          </tbody>
          <!--Table body-->


        </table>
        <!--Table-->
      </div>
</section>
<!--Section: Live preview-->
@endif

@if(isset($return_result))
<br><br>
<h1 style="text-align:center;">Return flights</h1>
<div class="table-responsive text-nowrap">
        <!--Table-->
        <table class="table table-striped">
        
                  <!--Table head-->
                  <thead>
                    <tr>
                      
                      <th>Flight Date</th>
                      <th>Flight Number	</th>
                     
                      <th>Adult Price</th>
                      <th>Child Price</th>
                      <th>Baby Price</th>
                      
                    </tr>
                  </thead>
                  <!--Table head-->
        
                  <!--Table body-->
                  <tbody>
                  @foreach($return_result as $return_item)
                    <tr>
                      <td>{{$return_item->flight_date}}</td>
                      <td>{{$return_item->flight_number}}</td>
        
                     
                      <td>{{$return_item->price_adult}}</td>
                      <td>{{$return_item->price_child}}</td>
                      <td>{{$return_item->price_baby}}</td>
        
                      
                    </tr>
                  @endforeach
                  </tbody>
                  <!--Table body-->
        
        
                </table>


        </table>
        <!--Table-->
      </div>
</section>
<!--Section: Live preview-->
@endif
</section>

@section('styles')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <style>
        body{
            background-color: #ececec;;
        }
        .width50{
            width: 40%;
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

<script type="text/javascript">

$(document).ready(function() {

  $(".sel-status").select2();

});

</script>
@stop