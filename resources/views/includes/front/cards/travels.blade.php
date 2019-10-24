<div class="item">
    <!-- Start card-block -->
    <a href="{{ route('singleTravel',['flight' => $travel->id]) }}" class="card-block">
        <!-- Start card-block-content -->
        <div class="card-block-content d-flex flex-wrap align-content-between">
            <!-- Start card-block-top -->
            <div class="card-block-top d-flex align-items-center justify-content-between">
                <span class="card-type">{{ $travel->period[App::getLocale()] }}</span>
                <span class="card-price">${{ $travel->price[0]['adult'] }}</span>
            </div>
            <!-- End card-block-top -->
            <!-- Start card-block-center -->
            <div class="card-block-center">
                <span class="card-title">{{ $travel->name[App::getLocale()] }}</span>
                <!-- Start card-list -->
                <p class="card-desc">
                    {{ strip_tags($travel->description[App::getLocale()]) }}
                </p>
                <!-- End card-list -->
            </div>
            <!-- End card-block-center -->
            <!-- Start card-block-bottom -->
            <div class="card-block-bottom d-flex align-items-center justify-content-between">
                <!-- Start card-section -->
                <div class="card-section d-flex align-items-center">
                    <i class="icons8-country"></i>
                    <span>{{ __('alnkel.travels') }}</span>
                </div>
                <!-- End card-section -->
                <!-- Start card-section -->
                <div class="card-section d-flex align-items-center">
                    <i class="icons8-calendar"></i>
                    <span>{{ __('alnkel.from') }} {{ $travel->from_date->format('d') }}
                        {{ __('alnkel.'.$travel->from_date->format('M')) }} {{ $travel->from_date->format('Y') }}
                        {{ __('alnkel.to') }} {{ $travel->to_date->format('d') }} {{ __('alnkel.'.$travel->from_date->format('M')) }}</span>
                </div>
                <!-- End card-section -->
            </div>
            <!-- End card-block-bottom -->
        </div>
        <!-- End card-block-content -->
        <img src="{{ Storage::url($travel->thumb) }}" alt="test"
             class="img-fluid">
    </a>
    <!-- End card-block -->
</div>