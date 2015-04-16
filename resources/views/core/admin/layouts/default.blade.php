<!DOCTYPE html>

<html lang="en">

<head>

	<meta charset="UTF-8">

	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>MinerZone Administration</title>

	<meta name="keywords" content="minerzone,miner,zone,leorblx" />
	<meta name="author" content="LeoRBLX (MinerZone)" />
	
	<meta name="description" content="The best Minecraft server there is. Join now!" />

	<!--  Mobile Viewport Fix -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<!-- CSS -->
    {!! HTML::style('assets/css/cosmo.css') !!}
    {!! HTML::style('assets/css/admin/main.css') !!}		
    <link href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all">
	<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700|PT+Sans:400,700|Roboto:400,100,300,500,700" rel="stylesheet" type="text/css" media="all">
    <!-- JS -->
    {!! HTML::script('assets/js/jquery-1.11.2.min.js') !!}
    {!! HTML::script('assets/js/bootstrap.min.js') !!}
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
    {!! HTML::script('assets/js/datatables-bootstrap.js') !!}
	{!! HTML::script('assets/js/datatables.fnReloadAjax.js') !!}
		
	<style>
	body {
		padding: 60px 0;
		font-family: 'Source Sans Pro';
		font-weight: 300;
	}
	</style>

	@yield('styles')

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	@yield('scripts')
	
	<script>
		$(function()
		{
			$('[data-type=tooltip]').tooltip({
				animation: false,
				html: true
			});
		});
	</script>
</head>

<body>
	<!-- Container -->
	<div class="container">
		<!-- Navbar -->
		<div class="navbar navbar-default navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
    			<div class="collapse navbar-collapse navbar-ex1-collapse">
    				<ul class="nav navbar-nav">
    					<li{{ (Request::is('admin') ? ' class=active' : '') }}><a href="{{{ URL::to('admin') }}}"><span class="fa fa-home"></span> Home</a></li>
    					<li{{ (Request::is('admin/forum*') ? ' class=active' : '') }}><a href="{{{ URL::to('admin/forum') }}}"><span class="fa fa-comments fa-fw"></span> Forums</a></li>
    					<li class="dropdown{{ (Request::is('admin/users*') || Request::is('admin/roles*') ? ' active' : '') }}">
    						<a class="dropdown-toggle" data-toggle="dropdown" href="{{{ URL::to('admin/users') }}}">
    							<span class="glyphicon glyphicon-user"></span> Users <span class="caret"></span>
    						</a>
    						<ul class="dropdown-menu">
    							<li{{ (Request::is('admin/users*') ? ' class=active' : '') }}><a href="{{{ URL::to('admin/users') }}}"><span class="glyphicon glyphicon-user"></span> Users</a></li>
    							<li{{ (Request::is('admin/roles*') ? ' class=active' : '') }}><a href="{{{ URL::to('admin/roles') }}}"><span class="glyphicon glyphicon-user"></span> Roles</a></li>
    						</ul>
    					</li>
    				</ul>
    				<ul class="nav navbar-nav pull-right">
    					<li><a href="{{{ URL::to('/') }}}">View Homepage</a></li>
    					<li class="divider-vertical"></li>
    					<li class="dropdown">
    							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
    								<span class="glyphicon glyphicon-user"></span> {{{ Auth::user()->username }}}	<span class="caret"></span>
    							</a>
    							<ul class="dropdown-menu">
    								<li><a href="{{{ URL::to('account/settings') }}}"><span class="glyphicon glyphicon-wrench"></span> Settings</a></li>
    								<li class="divider"></li>
    								<li><a href="{{{ URL::to('auth/logout') }}}"><span class="glyphicon glyphicon-share"></span> Logout</a></li>
    							</ul>
    					</li>
    				</ul>
    			</div>
            </div>
		</div>
		<!-- ./ navbar -->
		@if (Session::has('flash_notification.message'))
		<br>
		<div class="alert alert-{{ Session::get('flash_notification.level') == 'error' ? 'danger' : Session::get('flash_notification.level') }}">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

			{!! Session::get('flash_notification.message') !!}
		</div>
		@endif
		<!-- Content -->
		@yield('content')
		<!-- ./ content -->

		<!-- Footer -->
		<footer class="clearfix">
			@yield('footer')
		</footer>
		<!-- ./ Footer -->

	</div>
	<!-- ./ container -->

</body>
</html>