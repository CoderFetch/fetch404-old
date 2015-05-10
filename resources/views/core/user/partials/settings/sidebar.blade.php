<div class="col-md-3">
    <div class="well well-sm">
        <ul class="nav nav-pills nav-stacked">
            <li{{{ Request::is('account/settings') ? ' class=active' : '' }}}><a href="{{{ route('account.get.show.settings') }}}"><i class="fa fa-cog"></i> General Settings</a></li>
            <li{{{ Request::is('account/privacy') ? ' class=active' : '' }}}><a href="{{{ route('account.get.show.settings.privacy') }}}"><i class="fa fa-user-secret"></i> Privacy</a></li>
            <li{{{ Request::is('account/profile') ? ' class=active' : '' }}}><a href="{{{ route('account.get.show.settings.profile') }}}"><i class="fa fa-user"></i> Profile</a></li>
        </ul>
    </div>
</div>