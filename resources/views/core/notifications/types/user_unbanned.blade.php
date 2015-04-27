<li class="notification">
    <a href="/">
        <span class="avatar">{{{ strtoupper(substr(strip_tags($notification->sender->name), 0, 1)) }}}</span>
        {{{ $notification->sender->name }}} has unbanned you.
        <small class="time pull-right">{{{ $notification->created_at->diffForHumans() }}}</small>
    </a>
</li>