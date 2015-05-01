    <div class="well well-sm">
        <h4>Online Users ({{{ $users->count() }}})</h4>
        <hr>
        @unless($users->isEmpty())
            @foreach($users as $i => $user)
            <a href="{{{ $user->profileURL }}}">
                {{{ $user->name }}}
            </a>
            @if ($i != $users->count() - 1)
            ,
            @endif
            @endforeach
        @endunless

        @unless(!$users->isEmpty())
            <p>
                Nobody is online.
            </p>
        @endunless
    </div>