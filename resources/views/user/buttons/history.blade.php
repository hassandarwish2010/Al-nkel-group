<table class="table table-bordered table-striped mb-0">
    <tr>
        <th scope="col">Action</th>
        <th scope="col">Date</th>
        <th scope="col">IP</th>
        <th scope="col">User Name</th>
    </tr>
    @foreach($order->history as $item)
    <tr>
        <td>{{$item->action}}</td>
        <td>{{$item->created_at}}</td>
        <td>{{$item->ip}}</td>
        <td>{{$item->user->name}}</td>
    </tr>
    @endforeach
</table>