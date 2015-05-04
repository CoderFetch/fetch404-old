<div class="well well-sm">
    <h4>Status Updates</h4>
    <hr>
    @unless($statuses->isEmpty())
        <ul class="list-group">
            @foreach($statuses as $status)
                <li class="list-group-item">
                    <a href="{{{ $status->user->profileURL }}}">
                        <img src="{{{ $status->user->getAvatarURL(30) }}}" alt="{{{ $status->user->name }}}" height="35" width="30" style="box-shadow: 0 0 1px 1px silver;" data-type="tooltip" data-original-title="{{ $status->user->name }}" />
                    </a>
                    &nbsp;
                    <a href="#">
                        {{{ str_limit($status->body, 50) }}}
                    </a>
                    <small class="text-muted text-right">
                        {{{ $status->created_at->diffForHumans() }}}
                    </small>
                </li>
            @endforeach
        </ul>
    @endunless

    @unless(!$statuses->isEmpty())
        <p>
            There are no new status updates.
        </p>
    @endunless
</div>