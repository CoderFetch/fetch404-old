<li class="notification">
    <a href="{{{ $notification->subject->Route }}}">
        <span class="avatar">{{{ strtoupper(substr(strip_tags($notification->sender->name), 0, 1)) }}}</span>
        {{{ $notification->sender->name }}}
         replied to the thread "{{{ $notification->subject->topic->title }}}".
        <small class="time pull-right">{{{ $notification->created_at->diffForHumans() }}}</small>
    </a>
</li>