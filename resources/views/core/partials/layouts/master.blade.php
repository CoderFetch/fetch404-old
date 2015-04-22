<!DOCTYPE html>
<html lang="en">
	<head>
		<title>
			{{{ $site_title }}} | @yield('title', 'Home')
		</title>
		<meta charset="UTF-8"></meta>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link href="/assets/css/themes/{{{ $theme_id }}}.css" rel="stylesheet" type="text/css" media="all" />
		<link href="/assets/css/summernote.css" rel="stylesheet" type="text/css" media="all" />
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all">
		<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700|PT+Sans:400,700|Roboto:400,100,300,500,700" rel="stylesheet" type="text/css" media="all">
		<link href="//vjs.zencdn.net/4.12/video-js.css" rel="stylesheet">
		@if (Request::is('admin*'))
		<link href="/assets/css/admin/main.css" rel="stylesheet" type="text/css" media="all" />
		@endif
		<link href="/assets/css/select2.min.css" rel="stylesheet" />
		<link href="/assets/css/main.css" rel="stylesheet" type="text/css" />
		
		<script src="/assets/js/jquery-1.11.2.min.js"></script>
		<script src="/assets/js/bootstrap.min.js"></script>
		<script src="/assets/js/summernote.js"></script>
		<script src="/assets/js/modernizr.custom.03766.js"></script>
		<script src="//vjs.zencdn.net/4.12/video.js"></script>
		@if (Request::is('admin*'))
		<script src="/assets/js/admin/main.js"></script>
		@endif
		<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/js/select2.min.js"></script>
		<script src="/assets/js/main.js"></script>

		@if ($recaptcha_enabled)
		<script src='https://www.google.com/recaptcha/api.js'></script>
		@endif

		<script src="/assets/js/dropzone.js"></script>
	</head>
	
	<body style="position: relative; padding-top: 60px; font-family: 'Source Sans Pro'; font-weight: 300;"@yield('extra_attributes')>
		<!-- Navigation -->
		<nav class="navbar navbar-{{{ $navbar_style == 1 ? 'inverse' : 'default' }}} navbar-fixed-top" role="navigation">
			<div class="container">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/">
						{{{ $site_title }}}
					</a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li{{{ Request::is('/') ? ' class=active' : '' }}}>
							<a href="/"><i class="fa fa-home fa-fw"></i> Home</a>
						</li>
						<li class="dropdown{{{ Request::is('forum*') ? ' active' : '' }}}">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-comments fa-fw"></i>
								Forum
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li><a href="{{{ route('forum.get.index') }}}">Forums</a></li>
								@if (Auth::check())
								<li><a href="{{{ route('forum.get.my.posts') }}}">My posts</a></li>
								@endif
								<li><a href="{{{ route('forum.get.show.top.posters') }}}">Top posters</a></li>
							</ul>
						</li>
					</ul>
					{!! Form::open(['route' => 'search.send', 'class' => 'navbar-form navbar-right']) !!}
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Search" name="query" value="{{{ isset($searchQuery) ? $searchQuery : '' }}}"/>
						</div>
					{!! Form::close() !!}
					<ul class="nav navbar-nav navbar-right">
						@if (!Auth::check())
						<li>
							<a href="{{{ route('auth.get.login') }}}"><i class="fa fa-sign-in fa-fw"></i> Log in</a>
						</li>
						<li>
							<a href="{{{ route('auth.get.register') }}}"><i class="fa fa-user-plus fa-fw"></i> Sign up</a>
						</li>
						@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								{{{ Auth::user()->name }}}
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li><a href="{{{ route('profile.get.show', ['slug' => Auth::user()->slug, 'id' => Auth::id()]) }}}"><i class="fa fa-user fa-fw"></i> Profile</a></li>
								<li><a href="{{{ route('conversations') }}}"><i class="fa fa-envelope fa-fw"></i> Conversations <span class="badge" style="background-color: gray; color: white;">{{{ Auth::user()->newMessagesCount() }}}</span></a></li>
								<li><a href="{{{ route('tickets') }}}"><i class="fa fa-list-alt fa-fw"></i>Tickets <span class="badge" style="background-color: gray; color: white;">{{{ Auth::user()->unreadTicketsCount() }}}</span> </a></li>
								<li><a href="{{{ route('account.get.show.settings') }}}"><i class="fa fa-cog fa-fw"></i> Settings</a></li>
								<li><a href="{{{ route('auth.get.logout') }}}"><i class="fa fa-sign-out fa-fw"></i> Log out</a></li>
							</ul>
						</li>
						@if (Auth::user()->can('accessAdminPanel'))
						<li>
							<a href="{{{ route('admin.get.index') }}}">
								<i class="fa fa-cog"></i>
								Admin
							</a>
						</li>
						@endif
						@endif
					</ul>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container -->
		</nav>
		<div class="container">
			@if (Auth::check() && !Auth::user()->isConfirmed())
			<br>
			<div class="alert alert-info">
				<i class="fa fa-exclamation fa-fw"></i>
				 Your account has not been confirmed! An email should have been sent to <b>{{{ Auth::user()->email }}}</b>.
				 If you did not get an email, <a href="{{{ URL::to('/account/confirm/' . Auth::user()->getAccountConfirmation()->code) }}}" style="color: #ccc; font-weight: bold;">click here</a> to confirm your account.
			</div>
			@endif
			@if (Session::has('flash_notification.message'))
			<br>
			<div class="alert alert-{{ Session::get('flash_notification.level') == 'error' ? 'danger' : Session::get('flash_notification.level') }}">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				{!! Session::get('flash_notification.message') !!}
			</div>
			@endif
			<br />
			@yield('content')
			<hr>
		</div>

		@yield('scripts')
	</body>
</html>