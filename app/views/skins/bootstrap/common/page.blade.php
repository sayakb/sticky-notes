<!DOCTYPE html>

<html lang="{{ $site->general->lang }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $site->general->title }}</title>

	<link rel="alternate" type="application/rss+xml" title="RSS" href="{{ url('feed') }}" />

	<link href="{{ View::asset('img/favicon.png') }}" rel="shortcut icon" />
	<link href="{{ View::asset('css/bootstrap.min.css') }}" rel="stylesheet" />
	<link href="{{ View::asset('css/stickynotes.css') }}" rel="stylesheet" />

	<script type="text/javascript" src="{{ View::asset('js/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ View::asset('js/jquery.cookie.js') }}"></script>
	<script type="text/javascript" src="{{ View::asset('js/jquery.scrollto.js') }}"></script>
	<script type="text/javascript" src="{{ View::asset('js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ View::asset('js/stickynotes.js') }}"></script>
	<script type="text/javascript" src="//www.google.com/jsapi"></script>

	<script type="text/javascript">
		var ajaxUrl = "{{ url('ajax') }}";
		var ajaxNav = {{ $site->general->ajaxNav ? 'true' : 'false' }};
	</script>
</head>

<body data-toggle="ajax" data-context="{{ $context }}">
	<div class="loader">
		@include('skins.bootstrap.common.loader')
	</div>

	<nav class="navbar navbar-default navbar-static-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<a class="navbar-brand" href="{{ url() }}">{{ $site->general->title }}</a>
			</div>

			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav navbar-right">
					{{ View::menu('navigation') }}
				</ul>
			</div>
		</div>
	</nav>

	<div class="container">
		@if ( ! empty($site->general->bannerTop))
			<div class="row">
				<div class="col-sm-12">
					{{ $site->general->bannerTop }}
				</div>
			</div>
		@endif

		@yield('body')

		@if ( ! empty($site->general->bannerBottom))
			<div class="row">
				<div class="col-sm-12">
					{{ $site->general->bannerBottom }}
				</div>
			</div>
		@endif
	</div>

	<footer>
		<p>{{ $site->general->copyright }}</p>

		<!-- Do not the following copyright notice to avoid copyright violation. See http://opensource.org/licenses/BSD-2-Clause for details -->
		<!-- You may however choose to remove the hyperlinks and retain the copyright as plain text -->
		<p><a href="http://sayakbanerjee.com/sticky-notes">Sticky Notes</a> &copy; 2014 <a href="http://sayakbanerjee.com">Sayak Banerjee</a>.</p>

		@if ($active AND $role->admin)
			<small>{{ sprintf(Lang::get('global.statistics'), microtime(true) - LARAVEL_START, count(DB::getQueryLog()) - 1) }}</small>
		@endif

		@if (Antispam::flags()->php)
			<!-- Honeypot, do not click! -->
			<a href="http://www.ssdfreaks.com/household.php?to=857"><!-- agreement --></a>
		@endif
	</footer>
</body>

</html>
