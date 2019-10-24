<!-- Start item -->
<div class="item">
    <!-- Start card-block -->
    <a href="{{ route('singleVisa',['visa' => $visa->id]) }}" class="card-block">
        <!-- Start card-block-content -->
        <div class="card-block-content d-flex flex-wrap align-content-between">
            <!-- Start card-block-top -->
            <div class="card-block-top d-flex align-items-center justify-content-between">
                <span class="card-type">{{ $visa->visa_type[App::getLocale()] }}</span>
                <span class="card-price">${{ $visa->price }}</span>
            </div>
            <!-- End card-block-top -->
            <!-- Start card-block-center -->
            <div class="card-block-center">
                <span class="card-title">{{ $visa->name[App::getLocale()] }}</span>
                <p class="card-desc">
                    <b>{{ __('alnkel.papers') }}:</b>
                    {{ $visa->papers[App::getLocale()] }}
                </p>
            </div>
            <!-- End card-block-center -->
            <!-- Start card-block-bottom -->
            <div class="card-block-bottom d-flex align-items-center justify-content-end">
                <!-- Start card-section -->
                <div class="card-section d-flex align-items-center">
                    <i class="icons8-passport"></i>
                    <span>{{ __('alnkel.visa') }}</span>
                </div>
                <!-- End card-section -->
            </div>
            <!-- End card-block-bottom -->
        </div>
        <!-- End card-block-content -->
        <img src="{{ Storage::url($visa->thumb) }}" alt="test"
             class="img-fluid">
    </a>
    <!-- End card-block -->
</div>
<!-- End item -->