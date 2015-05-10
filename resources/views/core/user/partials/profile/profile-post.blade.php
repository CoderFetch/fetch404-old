<article class="media status-media">
    <div class="pull-left">
        <a href="{{{ $profilePost->user->profileURL }}}">
            <img class="media-object" src="{{{ $profilePost->user->getAvatarURL(45) }}}" alt="{{ $profilePost->user->name }}" height="45" width="45">
        </a>
    </div>

    <div class="pull-right">
        <div class="btn-group">
            @if (Auth::check() && $profilePost->user->id == Auth::id())
                {!! Form::open(['route' => array('user.profile-posts.post.delete', $user, $profilePost)]) !!}
                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                {!! Form::close() !!}
            @endif
        </div>
    </div>

    <div class="media-body status-media-body">
        <h4 class="media-heading status-media-heading">
            {!! link_to_route('profile.get.show', $profilePost->user->name, [$profilePost->user->slug, $profilePost->user->id]) !!}
        </h4>
        <p><small class="status-media-time">{{{ $profilePost->formattedCreatedAt() }}}</small></p>
        {!! Purifier::clean($profilePost->body) !!}
    </div>
</article>