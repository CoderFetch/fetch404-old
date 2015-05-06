<div class="col-md-3">
    <div class="well well-sm">
        <ul class="nav nav-pills nav-stacked">
            <li{{{ Request::is('account/settings') ? ' class=active' : '' }}}><a href="{{{ route('account.get.show.settings') }}}">General Settings</a></li>
            <li{{{ Request::is('account/privacy') ? ' class=active' : '' }}}><a href="{{{ route('account.get.show.settings.privacy') }}}">Privacy</a></li>
        </ul>
    </div>
</div>