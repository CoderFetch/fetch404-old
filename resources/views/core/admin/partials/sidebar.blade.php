<div class="col-md-3">
	<div class="well well-sm">
		<ul class="nav nav-pills nav-stacked">
			<li{{{ Request::is('admin') ? ' class=active' : '' }}}><a href="{{{ route('admin.get.index') }}}">Overview</a></li>
			<li{{{ Request::is('admin/general*') ? ' class=active' : '' }}}><a href="/admin/general">General Settings</a></li>
			<li{{{ Request::is('admin/groups*') ? ' class=active' : '' }}}><a href="#">Groups</a></li>
			<li{{{ Request::is('admin/users*') ? ' class=active' : '' }}}><a href="/admin/users">Users</a></li>
			<li{{{ Request::is('admin/forum*') ? ' class=active' : '' }}}><a href="/admin/forum">Forum</a></li>
		</ul>
	</div>
	<div class="well well-sm">
		<label>Users:</label> {{{ User::count() }}}
		<br />
		<label>Posts:</label> {{{ Post::count() }}}
		<br />
		<label>Topics:</label> {{{ Topic::count() }}}
	</div>
</div>