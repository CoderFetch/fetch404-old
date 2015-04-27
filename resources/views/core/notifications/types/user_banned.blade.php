<li class="notification">
    <a href="/">
        <span class="avatar">{{{ strtoupper(substr(strip_tags($notification->sender->name), 0, 1)) }}}</span>
        You have been banned by {{{ $notification->sender->name }}}.
        <small class="time pull-right">{{{ $notification->created_at->diffForHumans() }}}</small>
    </a>
</li>