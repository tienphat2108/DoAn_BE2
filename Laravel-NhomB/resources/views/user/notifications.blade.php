<h2>Thông báo của bạn</h2>

<ul>
@foreach ($notifications as $notification)
    <li>
        <strong>{{ $notification->data['title'] }}</strong><br>
        {{ $notification->data['message'] }}<br>
        <small>{{ $notification->created_at->diffForHumans() }}</small>
        <hr>
    </li>
@endforeach
</ul>
