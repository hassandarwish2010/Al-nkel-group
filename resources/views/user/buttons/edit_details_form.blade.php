@if($form == "notes")

    <div class="form-group">
        <label class="d-block">Notes</label>
        <textarea class="form-control note">{{$order->note}}</textarea>
    </div>


@else

    <div class="form-group">
        <label class="d-block">Phone</label>
        <input type="text" class="form-control phone"
               placeholder="Your phone number" value="{{$order->phone}}"/>
    </div>

    <div class="form-group">
        <label class="d-block">Email</label>
        <input type="text" class="form-control email"
               placeholder="Your email to receive ticket" value="{{$order->email}}"/>
    </div>


@endif