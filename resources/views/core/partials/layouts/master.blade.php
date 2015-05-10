<!DOCTYPE html>
<html lang="en">
	<head>
		<title>
			{{{ $site_title }}} | @yield('title', 'Home')
		</title>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		@yield('extra_tags')
		
		<link href="/assets/css/themes/{{{ $theme_id }}}.css" rel="stylesheet" type="text/css" media="all" />
		<link href="/assets/css/summernote.css" rel="stylesheet" type="text/css" media="all" />
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all">
		<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700|PT+Sans:400,700|Roboto:400,100,300,500,700" rel="stylesheet" type="text/css" media="all">
		<link href="//vjs.zencdn.net/4.12/video-js.css" rel="stylesheet">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.2/css/bootstrap2/bootstrap-switch.min.css" rel="stylesheet" />
		@if (Request::is('admin*'))
		<link href="/assets/css/admin/main.css" rel="stylesheet" type="text/css" media="all" />
		@endif
		<link href="/assets/css/select2.min.css" rel="stylesheet" />
		<link href="/assets/css/main.css" rel="stylesheet" type="text/css" />
		@yield('styles')
		
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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.2/js/bootstrap-switch.min.js"></script>

		<script type="text/javascript">
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			$('[data-method]:not(.disabled)').click(function(event) {
				$('<form action="' + $(this).attr('href') + '" method="POST">' +
				'<input type="hidden" name="_method" value="' + $(this).data('method') + '">' +
				'<input type="hidden" name="_token" value="{{{ csrf_token() }}}"' +
				'</form>').submit();

				event.preventDefault();
			});

			$('[data-submit]:not(.disabled)').click(function(event) {
				var form = $(this).closest('form');

				if (form.length > 0) {
					form.submit();
				}

				event.preventDefault();
			});

			$(document).ready(function() {
				$("[data-bswitch]").bootstrapSwitch();
			});
		</script>

		@if (Auth::check())
		<script src="/assets/js/notifications.js"></script>
		<script src="/assets/js/messages.js"></script>
		@endif
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
						<li{{{ Request::is('forum*') ? ' class=active' : '' }}}>
							<a href="{{{ route('forum.get.index') }}}">
								<i class="fa fa-comments fa-fw"></i>
								Forum
							</a>
						</li>
					</ul>
					{!! Form::open(['route' => 'search.send', 'class' => 'navbar-form navbar-right', 'role' => 'search']) !!}
						<div class="form-group">
							<input type="search" class="form-control" placeholder="Search" name="query" value="{{{ isset($searchQuery) ? $searchQuery : '' }}}"/>
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
								{{{ $user->name }}}
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li><a href="{{{ route('profile.get.show', ['slug' => $user->slug, 'id' => $user->id]) }}}"><i class="fa fa-user fa-fw"></i> Profile</a></li>
								<li><a href="{{{ route('conversations') }}}"><i class="fa fa-envelope fa-fw"></i> Conversations <span class="badge" style="background-color: gray; color: white;">{{{ $user->newMessagesCount() }}}</span></a></li>
								<li><a href="{{{ route('account.get.show.settings') }}}"><i class="fa fa-cog fa-fw"></i> Settings</a></li>
								<li><a href="{{{ route('auth.get.logout') }}}"><i class="fa fa-sign-out fa-fw"></i> Log out</a></li>
							</ul>
						</li>
						<li>
							<a href="#" id="messages">
								<i class="fa fa-envelope fa-fw"></i>
								<span class="badge" id="messagesCount">
									{{{ $messages->count() }}}
								</span>
							</a>
							<div id="messagesPopup" class="popup right" style="display: none; position: absolute; top: 49px; right: 0px;">
								<h3>Messages</h3>
								<div class="">
									<ul class="list messagesList">
										@if ($messages->isEmpty())
										<li>
											<p>
												You have no new messages.
											</p>
										</li>
										@else
										@foreach($messages as $pm)
										<li class="message">
											<a href="{{{ route('conversations.show', $pm) }}}">
												<span class="avatar">{{{ strtoupper(substr(strip_tags($pm->latestMessage->user->name), 0, 1)) }}}</span>
												{{{ $pm->latestMessage->user->name }}}
												<small class="time pull-right">{{{ $pm->latestMessage->created_at->diffForHumans() }}}</small>
												<br />
												Subject: {{{ str_limit($pm->subject, 12) }}}
											</a>
										</li>
										@endforeach
										@endif
										<li id="viewAllMessages"><a href="#">View all messages »</a></li>
									</ul>
								</div>
							</div>
						</li>
						<li>
							<a href="#" id="notifications">
								<i class="fa fa-bell fa-fw"></i>
								<span class="badge" id="notificationsCount">
									{{{ $notifications->where('is_read', '=', 0)->count() }}}
								</span>
							</a>
							<div id="notificationsPopup" class="popup right" style="display: none; position: absolute; top: 49px; right: 0px;">
								<h3>Notifications</h3>
								<div class="">
									<ul class="list notificationsList">
										@if ($notifications->isEmpty())
										<li>
											<p>
												You have no new notifications.
											</p>
										</li>
										@else
										@foreach($notifications as $notification)
										@if (view()->exists('core.notifications.types.' . $notification->name))
										@include('core.notifications.types.' . $notification->name)
										@endif
										@endforeach
										@endif
										<li id="viewAllNotifications"><a href="#">View all notifications »</a></li>
									</ul>
								</div>
							</div>
						</li>
						@if ($user->can('accessAdminPanel'))
						<li>
							<a href="{{{ route('admin.get.index') }}}">
								<i class="fa fa-cog"></i>
								Admin
							</a>
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
						@endif
						@endif
					</ul>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container -->
		</nav>
		<div class="container">
			@if (Auth::check() && !$user->isConfirmed())
			<br>
			<div class="alert alert-info">
				<i class="fa fa-exclamation fa-fw"></i>
				 Your account has not been confirmed! An email should have been sent to <b>{{{ $user->email }}}</b>.
				 If you did not get an email, <a href="{{{ URL::to('/account/confirm/' . $user->getAccountConfirmation()->code) }}}" style="font-weight: bold;">click here</a> to confirm your account.
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
		</div>

		@yield('scripts')
	</body>
</html>