<link href="{{asset('public/assets/css/styles.css')}}" rel="stylesheet">
<form action="{form-action}" id="cancel-form" method="post">
    {{ csrf_field() }}
    <div class="whole-notice bg-info text-white p-2">The whole flight will be canceled</div>
    <div class="partial-notice bg-danger text-white p-2" hidden>The only selected flights and passengers will be
        cancelled
    </div>

    <table class="table table-responsive table-bordered table-hover">
        <thead>
        <tr>
            <th>Passengers</th>
            @foreach($order->flights as $flight)
                <th>
                    <label class="mt-checkbox mt-checkbox-outline m-0">
                        <input type="checkbox" class="cancelable-checkbox cancelable-flight" name="flights[]"
                               data-flight_checkbox="{{$flight->charter->id}}" value="{{$flight->charter->id}}" checked/>
                        <span></span> {{$flight->charter->name}}
                    </label>
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($order->passengers as $passenger)
            <tr>
                <td>
                    <label class="mt-checkbox mt-checkbox-outline m-0">
                        <input type="checkbox" class="cancelable-checkbox cancelable-passenger" name="passengers[]"
                               data-passenger_checkbox="{{$passenger->id}}" value="{{$passenger->id}}" checked/>
                        <span></span> {{$passenger->first_name}} {{$passenger->last_name}}
                    </label>
                </td>
                @foreach($passenger->related as $related)
                    <td class="cancelable-price in-calculation text-danger" data-charter="{{$related->flight_id}}"
                        data-passenger="{{$passenger->id}}">{{$related->price}}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="form-group">
        <label>Refund amount</label>
        <input type="text" class="amount form-control text-danger" name="amount" required/>
    </div>

    <input type="hidden" name="cancel_all" value="1">
</form>

<script>
    $('.cancelable-checkbox').on('change', function () {
        var allFlights = $('[name="flights[]"]'),
            allPassengers = $('[name="passengers[]"]'),
            selectedFlights = $('[name="flights[]"]:checked'),
            selectedPassengers = $('[name="passengers[]"]:checked');

        var allCheckboxes = allFlights.length + allPassengers.length,
            selectedCheckboxes = selectedFlights.length + selectedPassengers.length;

        var wholeNotice = $('.whole-notice'),
            partialNotice = $('.partial-notice');

        var cancelAll = $('[name=cancel_all]');

        // All selected
        if (allCheckboxes === selectedCheckboxes) {
            wholeNotice.removeAttr('hidden');
            partialNotice.attr('hidden', true);

            cancelAll.val(1);
        }
        // Not all selected
        else {
            wholeNotice.attr('hidden', true);
            partialNotice.removeAttr('hidden');

            cancelAll.val(0);
        }

        // Calculate
        $('.cancelable-price').each(function () {
            var charter = $(this).data('charter'),
                passenger = $(this).data('passenger');

            var isCharterChecked = $('[data-flight_checkbox=' + charter + ']').is(':checked'),
                isPassengerChecked = $('[data-passenger_checkbox=' + passenger + ']').is(':checked');

            if (isCharterChecked && isPassengerChecked) {
                $(this).addClass('in-calculation text-danger').removeClass('text-success');
            } else {
                $(this).removeClass('in-calculation text-danger').addClass('text-success');
            }
        });

        calculateRefundAmount();
    });

    calculateRefundAmount();

    function calculateRefundAmount() {
        var amount = 0;
        $('.in-calculation').each(function () {
            amount += parseFloat($(this).text())
        });

        $('#cancel-form .amount').val(amount);
    }
</script>