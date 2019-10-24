<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="content">
                <div class="container text-right">
                    <span>
                        {{$charter->flight_day}} {{$charter->flight_date}}
                    </span>
                    <div class="row">
                        <div class="col plane"><i class="fas fa-plane-departure"></i></div>
                        <div class="col">
                            <span class="bold">{{$charter->departure_time}}</span>
                            <span class="bold">{{$charter->from->code}}</span>
                        </div>
                        <div class="col">
                            <span class="border-span">{{$charter->flight_number}}</span>
                            <span>{{$charter->aircraft->name}}</span>
                        </div>
                        <div class="col">
                            <span class="bold">{{$charter->arrival_time}}</span>
                            <span class="bold">{{$charter->to->code}}</span>
                        </div>
                        <div class="col">
                            <span class="top-span">{{ __('charter.seats') }}</span>
                            <span class="bold">{{$charter->economy_seats > 9 ? 9 : $charter->economy_seats}}</span>
                        </div>
                        <div class="col">
                            <span class="top-span">{{ __('charter.price') }}</span>
                            <span class="bold">${{$charter->price_adult}}</span>
                        </div>
                        <div class="col">
                            <a class="main-button mt-3 charter-reserve" href="#" data-title="Charter #{{$charter->id}} ({{$charter->name}})" data-id="{{$charter->id}}" data-checkout="{{route("charterCheckout")}}">
                                {{__('charter.reserve')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>