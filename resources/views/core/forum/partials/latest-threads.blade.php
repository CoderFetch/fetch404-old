    <div class="well well-sm">
        <h4>Latest Threads</h4>
        <hr>
        @unless($threads->isEmpty())
        <ul class="list-group">
            @foreach($threads as $thread)
            <li class="list-group-item">
                <a href="{{{ $thread->getLatestPost()->user->profileURL }}}">
                    <img src="{{{ $thread->getLatestPost()->user->getAvatarURL(30) }}}" alt="{{{ $thread->getLatestPost()->user->name }}}" height="35" width="30" style="box-shadow: 0 0 1px 1px silver;" data-type="tooltip" data-original-title="{{ $thread->getLatestPost()->user->name }}" />
                </a>
                &nbsp;
                <a href="{{{ $thread->Route }}}">
                    {{{ $thread->title }}}
                </a>
                <small class="text-muted text-right">
                    {{{ $thread->getLatestPost()->formattedCreatedAt() }}}
                </small>
            </li>
            @endforeach
        </ul>
        @endunless

        @unless(!$threads->isEmpty())
        <p>
            There are no new threads.
        </p>
        @endunless
    </div>