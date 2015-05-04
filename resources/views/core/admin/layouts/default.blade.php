<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title>{{{ $site_title }}} Administration | @yield('title', 'Home')</title>

		<!--  Mobile Viewport Fix -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

		<!-- CSS -->
		{!! HTML::style('assets/css/themes/' . $theme_id . '.css') !!}
		{!! HTML::style('assets/css/admin/main.css') !!}
		<link href="/assets/css/select2.min.css" rel="stylesheet" />
		<link href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" type="text/css" media="all" />
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all">
		<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700|PT+Sans:400,700|Roboto:400,100,300,500,700" rel="stylesheet" type="text/css" media="all">
		<!-- JS -->
		{!! HTML::script('assets/js/jquery-1.11.2.min.js') !!}
		{!! HTML::script('assets/js/bootstrap.min.js') !!}
		<script src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
		{!! HTML::script('assets/js/datatables-bootstrap.js') !!}
		<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/js/select2.min.js"></script>

		<style>
		body {
			font-family: 'Source Sans Pro';
			font-weight: 300;
		}
		</style>

		@yield('styles')

		@yield('scripts')
	</head>

	<body>
		<!-- Navbar -->
		<div class="navbar navbar-{{{ $navbar_style == 1 ? 'inverse' : 'default' }}}" style="z-index:1;">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/">
						{{{ $site_title }}}
					</a>
				</div>
				<div class="collapse navbar-collapse navbar-ex1-collapse">
					<ul class="nav navbar-nav">
						<li{{ (Request::is('admin') ? ' class=active' : '') }}><a href="{{{ URL::to('admin') }}}"><span class="fa fa-home"></span> Home</a></li>
						<li{{ (Request::is('admin/forum*') ? ' class=active' : '') }}><a href="{{{ URL::to('admin/forum') }}}"><span class="fa fa-comments fa-fw"></span> Forums</a></li>
						<li class="dropdown{{ (Request::is('admin/users*') || Request::is('admin/roles*') ? ' active' : '') }}">
							<a class="dropdown-toggle" data-toggle="dropdown" href="{{{ URL::to('admin/users') }}}">
								<span class="fa fa-user"></span> Users <span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li{{ (Request::is('admin/users*') ? ' class=active' : '') }}><a href="{{{ URL::to('admin/users') }}}"><span class="fa fa-user"></span> Users</a></li>
								<li{{ (Request::is('admin/roles*') ? ' class=active' : '') }}><a href="{{{ URL::to('admin/roles') }}}"><span class="fa fa-users"></span> Roles</a></li>
							</ul>
						</li>
						<li{{{ Request::is('admin/general') ? ' class=active' : '' }}}><a href="{{{ route('admin.get.general') }}}"><span class="fa fa-cog"></span> Settings</a></li>
					</ul>
					<ul class="nav navbar-nav pull-right">
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<span class="fa fa-user"></span> {{{ Auth::user()->name }}}
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li><a href="{{{ route('account.get.show.settings') }}}"><span class="fa fa-wrench"></span> Settings</a></li>
							</ul>
						</li>
						@if ($user->can('viewReports'))
							@unless($reports->isEmpty())
								<li>
									<a href="{{{ route('reports.index') }}}">
										<i class="fa fa-exclamation-circle"></i>
										<span class="badge">
											{{{ $reports->count() }}}
										</span>
									</a>
								</li>
							@endunless
						@endif
						<li class="divider-vertical"></li>
						<li><a href="{{{ route('home.show') }}}">View Site</a></li>
						<li><a href="{{{ route('auth.get.logout') }}}"><span class="fa fa-share"></span> Log out</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!-- ./ navbar -->
		<!-- Container -->
		<div class="container">
			@if (Session::has('flash_notification.message'))
			<br>
			<div class="alert alert-{{ Session::get('flash_notification.level') == 'error' ? 'danger' : Session::get('flash_notification.level') }}">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				{!! Session::get('flash_notification.message') !!}
			</div>
			@endif
			<!-- Content -->
			@yield('content')
		</div>
		<!-- ./ content -->
		<!-- Footer -->
		<footer class="clearfix">
			@yield('footer')
		</footer>
		<!-- ./ Footer -->
	</body>
</html>