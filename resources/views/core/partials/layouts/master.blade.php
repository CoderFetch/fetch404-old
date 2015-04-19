<!DOCTYPE html>
<html lang="en">
	<head>
		<title>
			{{{ $site_title }}} | @yield('title', 'Home')
		</title>
		<meta charset="UTF-8"></meta>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link href="/assets/css/cosmo.css" rel="stylesheet" type="text/css" media="all" />
		<link href="/assets/css/summernote.css" rel="stylesheet" type="text/css" media="all" />
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all">
		<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700|PT+Sans:400,700|Roboto:400,100,300,500,700" rel="stylesheet" type="text/css" media="all">
		<link href="//vjs.zencdn.net/4.12/video-js.css" rel="stylesheet">
		@if (Request::is('admin*'))
		<link href="/assets/css/admin/main.css" rel="stylesheet" type="text/css" media="all" />
		@endif
		<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/css/select2.min.css" rel="stylesheet" />
		<link href="/assets/css/jquery.mentionsInput.css" rel="stylesheet" />
		
		<script src="/assets/js/jquery-1.11.2.min.js"></script>
		<script src="/assets/js/bootstrap.min.js"></script>
		<script src="/assets/js/summernote.js"></script>
		<script src="/assets/js/modernizr.custom.03766.js"></script>
		<script src="//vjs.zencdn.net/4.12/video.js"></script>
		@if (Request::is('admin*'))
		<script src="/assets/js/admin/main.js"></script>
		@endif
		<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/js/select2.min.js"></script>
		<script src="/assets/js/underscore-min.js"></script>
		<script src="/assets/js/jquery.elastic.js"></script>
		<script src="/assets/js/jquery.events.input.js"></script>
		<script src="/assets/js/jquery.mentionsInput.js"></script>

		<style>
		.navbar-top-links {
			margin-right: 0;
		}

		.navbar-top-links li {
			display: inline-block;
		}

		.navbar-top-links li:last-child {
			margin-right: 15px;
		}

		.navbar-top-links li a {
			padding: 15px;
			min-height: 50px;
		}

		.navbar-top-links .dropdown-menu li {
			display: block;
		}

		.navbar-top-links .dropdown-menu li:last-child {
			margin-right: 0;
		}

		.navbar-top-links .dropdown-menu li a {
			padding: 3px 20px;
			min-height: 0;
		}
		.navbar-top-links .dropdown-menu li a div {
			white-space: normal;
		}

		.navbar-top-links .dropdown-messages,
		.navbar-top-links .dropdown-tasks,
		.navbar-top-links .dropdown-alerts {
			width: 325px;
			min-width: 0;
		}

		.navbar-top-links .dropdown-messages {
			margin-left: 5px;
		}

		.navbar-top-links .dropdown-tasks {
			margin-left: -59px;
		}

		.navbar-top-links .dropdown-alerts {
			margin-left: -123px;
		}

		.navbar-top-links .dropdown-user {
			right: 0;
			left: auto;
		}
		@keyframes blink {  
			0% { color: green; }
			100% { color: white; }
		}
		@-webkit-keyframes blink {
			0% { color: green; }
			100% { color: white; }
		}
		.blink {
			-webkit-animation-name: blink;
			-webkit-animation-duration: 0.5s;
		    -webkit-animation-iteration-count: 5;
			
			-moz-animation-name: blink;
			-moz-animation-duration: 0.5s;
		    -moz-animation-iteration-count: 5;
			
			-ms-animation-name: blink;
			-ms-animation-duration: 0.5s;
		    -ms-animation-iteration-count: 5;
			
			-o-animation-name: blink;
			-o-animation-duration: 0.5s;
		    -o-animation-iteration-count: 5;
			
			animation-name: blink;
			animation-duration: 0.5s;
			animation-iteration-count: 5;
		}

		/* enable absolute positioning */
		.inner-addon {
			position: relative;
		}

		/* style glyph */
		.inner-addon .fa {
			position: absolute;
			padding: 10px;
			pointer-events: none;
		}

		/* align glyph */
		.left-addon .fa  { left:  0px; top: 4.5px;}
		.right-addon .fa { right: 0px; top: 4.5px;}

		/* add padding  */
		.left-addon input  { padding-left:  30px; }
		.right-addon input { padding-right: 30px; }
		</style>
		<script>
			var tooltipSettings = {html: true, animation: false};
			var summernoteSettings = {
				height: 300, 
				minHeight: null, 
				maxHeight: null,
				toolbar: [
					['style', ['bold', 'italic', 'underline', 'clear']],
					['font', ['strikethrough']],
					['fontsize', ['fontsize']],
					['color', ['color']],
					['para', ['ul', 'ol', 'paragraph']],
					['height', ['height']]
				]
			};
			$(document).ready(function()
			{
				$('[data-type=tooltip]').tooltip(tooltipSettings);
				$('[data-type=summernote]').summernote(summernoteSettings || {});
			});
		</script>
	</head>
	
	<body style="position: relative; padding-top: 60px; font-family: 'Source Sans Pro'; font-weight: 300;"@yield('extra_attributes')>
		<!-- Navigation -->
		<nav class="navbar navbar-default navbar-inverse navbar-fixed-top" role="navigation">
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
						minerzone
					</a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-top-links">
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
						<li>
							<a href="{{{ route('play.get.show') }}}"><i class="fa fa-gamepad fa-fw"></i> Play</a>
						</li>
					</ul>
					{!! Form::open(['route' => 'search.send', 'class' => 'navbar-form navbar-right']) !!}
						<div class="inner-addon right-addon form-group">
							<i class="fa fa-search fa-1x"></i>
							<input type="text" class="form-control" placeholder="Search" name="query" value="{{{ isset($searchQuery) ? $searchQuery : '' }}}"/>
						</div>
					{!! Form::close() !!}
					<ul class="nav navbar-nav navbar-top-links navbar-right">
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
								@if (Entrust::can('admin-panel'))
									<li>
										<a href="{{{ route('admin.get.index') }}}"><i class="fa fa-laptop fa-fw"></i> Admin Panel</a>
									</li>
								@endif
								<li><a href="{{{ route('auth.get.logout') }}}"><i class="fa fa-sign-out fa-fw"></i> Log out</a></li>
							</ul>
						</li>
						@endif
					</ul>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container -->
		</nav>
		<div class="@yield('container_class', 'container')">
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
		@yield('content')
			<hr>
	        <footer style="position: fixed; bottom: 0; width: 100%;">
				<span class="text-muted">
					{{{ Post::count() }}} posts
				</span>
				&nbsp;
				<span class="text-muted">
					{{{ Topic::count() }}} topics
				</span>
				&nbsp;
				<span class="text-muted">
					{{{ User::count() }}} users
				</span>
      		</footer>
      		@yield('footer')
		</div>
	</body>
</html>