<html>
<body>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<style>
    html, body {
        font-family: 'XB Riyaz', sans-serif;
        max-width: 800px;
    }

    table {
        width: 100%;
        text-align: left;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .table-title {
        text-align: center;
        background: #03B0F1;
        color: #fff;
        font-weight: bold;
    }

    h3 {
        text-align: center;
        margin-bottom: 5px;
    }

    th {
        background: #e0e0e0;
    }

    .page-break {
        page-break-after: always;
    }

    .instructions {
        text-align: center;
        font-weight: bold;
        margin: 20px 0;
    }

    img.logo {
        width: 140px;
        display: table;
        margin: 10px auto;
    }

    .text-danger {
        color: #ff0000 !important;
    }

    .text-info {
        color: #36a3f7 !important;
    }

    .text-success {
        color: #2c682c !important;
    }

    .all-caps {
        text-transform: uppercase;
    }

    .qrcode {
        width: 140px
    }

    .all-bold {
        font-weight: bold;
    }
</style>

<?php
$index = 0;
$charter = $order->charter;

$status = [
	"awaiting"  => "TEMPORARY",
	"delivered" => "CONFIRMED",
	"cancelled" => "CANCELLED"
];

$statusColors = [
	"awaiting"  => "info",
	"delivered" => "success",
	"cancelled" => "danger"
];
?>

<table>
    <tr>
        <td width="30%">
            <div style="text-align: center;float:left;">
                <img src="https://api.qrserver.com/v1/create-qr-code/?color=000000&bgcolor=FFFFFF&data={{$order->pnr}}&qzone=1&margin=0&size=140x140&ecc=L"
                     class="qrcode"/>
                <br/>
                <strong>PNR</strong>
                <br/>
                <strong class="all-caps text-danger">{{$order->pnr}}</strong>
            </div>
        </td>
        <td>
            <table>
                <tr>
                    <td width="120">Booking Status</td>
                    <td>: <strong
                                class="text-{{$statusColors[$order->status]}}">{{$status[$order->status]}}</strong>
                    </td>
                </tr>
                <tr>
                    <td>Booking Date</td>
                    <td>: {{\Carbon\Carbon::parse($order->created_at)->format("D, d M, Y")}}</td>
                </tr>
                <tr>
                    <td>Contact Name</td>
                    <td class="all-caps">
                        : {{$order->passengers[0]->first_name . ' ' . $order->passengers[0]->last_name}}</td>
                </tr>
                <tr>
                    <td>Contact Number</td>
                    <td>: {{$order->phone}}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>: <a href="mailto:{{$order->user->email}}">{{$order->user->email}}</a></td>
                </tr>
                <tr>
                    <td>Agent</td>
                    <td>: {{$order->user->company}}</td>
                </tr>
            </table>
        </td>
        <td width="25%">
            <img src="{{ str_replace("/storage", "http://asfar-iq.com/storage", Storage::url("app/public/".$order->charter->aircraft->logo)) }}"
                 class="logo"/>
        </td>
    </tr>
</table>

<table class="all-bold">
    <tr>
        <td colspan="7" class="table-title all-caps">Flight Information / معلومات الرحلة</td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td width="70">Departing</td>
                    <td>: {{$charter->from->code}}</td>
                </tr>
                <tr>
                    <td colspan="2">{{$charter->from->name['en']}}</td>
                </tr>
                <tr>
                    <td colspan="2">{{\Carbon\Carbon::parse($charter->flight_date)->format("D, d M, Y")}}</td>
                </tr>
                <tr>
                    <td colspan="2">{{$charter->departure_time}}</td>
                </tr>
            </table>
        </td>
        <td>
            <table>
                <tr>
                    <td width="70">Arriving</td>
                    <td>: {{$charter->to->code}}</td>
                </tr>
                <tr>
                    <td colspan="2">{{$charter->to->name['en']}}</td>
                </tr>
                <tr>
                    <td colspan="2">{{\Carbon\Carbon::parse($charter->flight_date)->format("D, d M, Y")}}</td>
                </tr>
                <tr>
                    <td colspan="2">{{$charter->arrival_time}}</td>
                </tr>
            </table>
        </td>
        <td>
            <table>
                <tr>
                    <td width="70">Carrier</td>
                    <td>: {{$charter->aircraft->name}}</td>
                </tr>
                <tr>
                    <td width="70">Flight No</td>
                    <td>: {{$charter->flight_number}}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table>
    <tr>
        <td colspan="4" class="table-title">Passenger Information / معلومات المسافر</td>
    </tr>
    <tr>
        <th>Passenger Name</th>
        <th>Ticket No</th>
        <th>Class</th>
    </tr>
    @foreach($order->passengers as $passenger)
        <tr>
            <td>{{strtoupper($passenger->title . ': ' . $passenger->first_name . ' ' . $passenger->last_name)}}</td>
            <td>{{$passenger->ticket_number[0]}}</td>
            <td>{{$order->flight_class}}</td>
        </tr>
    @endforeach
</table>

@if(!$hide_prices)
    <table>
        <tr>
            <td colspan="2" class="table-title">Payment Details / تفاصيل الدفع</td>
        </tr>
        <tr>
            <td width="50%" style="border-bottom: 1px solid #333;">
                <table>
                    <tr>
                        <td>Total Price (USD)</td>
                        <td>
                            {{$order->price}}
                        </td>
                    </tr>
                </table>
            </td>
            <td>

            </td>
        </tr>
    </table>
@endif

<div class="instructions">{!! nl2br($order->charter->instructions) !!}</div>

</body>
</html>