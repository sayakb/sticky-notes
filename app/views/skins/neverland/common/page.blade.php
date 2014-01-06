<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8">
	<title>{{ $site->general->title }}</title>

	<link rel="alternate" type="application/rss+xml" title="RSS" href="{{ url('feed') }}" />

	<link href="//cdn.kde.org/img/favicon.png" rel="shortcut icon" />
	<link href="//cdn.kde.org/css/bootstrap.css" rel="stylesheet" />
	<link href="//cdn.kde.org/css/bootstrap-responsive.css" rel="stylesheet" />
	<link href="//cdn.kde.org/css/bootstrap-stickynotes.css" rel="stylesheet" />

	<script type="text/javascript" src="//cdn.kde.org/js/jquery.js"></script>
	<script type="text/javascript" src="//cdn.kde.org/js/jquery.cookie.js"></script>
	<script type="text/javascript" src="//cdn.kde.org/js/bootstrap.js"></script>
	<script type="text/javascript" src="{{ View::asset('js/stickynotes.js') }}"></script>

	<script type="text/javascript">
		var ajaxUrl = "{{ url('ajax') }}";
	</script>
</head>

<body>
	<header class="navbar navbar-static-top Neverland" role="navigation">
		<div class="navbar-inner">
			<div class="container">
				<div class="navbar-header">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>

					<a class="brand" href="{{ url() }}">
						{{ HTML::image('//cdn.kde.org/img/logo.plain.small.png') }}
						{{ $site->general->title }}
					</a>
				</div>

				<div class="nav-collapse">
					<ul class="nav pull-right">
						{{ View::menu('navigation') }}
					</ul>
				</div>
			</div>
		</div>
	</header>

	<div class="container">
		@yield('body')

		<footer class="align-center">
			<p>{{ $site->general->copyright }}</p>

			<!-- Do not the following copyright notice to avoid copyright violation. See http://opensource.org/licenses/BSD-2-Clause for details -->
			<!-- You may however choose to remove the hyperlinks and retain the copyright as plain text -->
			<p><a href="http://sayakbanerjee.com/sticky-notes">Sticky Notes</a> &copy; 2013 <a href="http://sayakbanerjee.com">Sayak Banerjee</a>.</p>

			@if ($active AND $role->admin)
				<small>{{ sprintf(Lang::get('global.statistics'), microtime(true) - LARAVEL_START, count(DB::getQueryLog())) }}</small>
			@endif

			@if (Antispam::flags()->php)
				<!-- Honeypot, do not click! -->
				<a href="http://www.ssdfreaks.com/household.php?to=857"><!-- agreement --></a>
			@endif
		</footer>
	</div>
</body>

</html>
