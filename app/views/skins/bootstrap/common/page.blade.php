<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8">
	<title>{{ $site->general->title }}</title>

	<link href="{{ View::asset('img/favicon.png') }}" rel="shortcut icon" />
	<link href="{{ View::asset('css/bootstrap.min.css') }}" rel="stylesheet" />
	<link href="{{ View::asset('css/stickynotes.css') }}" rel="stylesheet" />

	<script type="text/javascript" src="{{ View::asset('js/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ View::asset('js/jquery.cookie.js') }}"></script>
	<script type="text/javascript" src="{{ View::asset('js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ View::asset('js/stickynotes.js') }}"></script>

	<script type="text/javascript">
		var ajaxUrl = "{{ url('ajax') }}";
	</script>
</head>

<body>
	<header class="navbar navbar-default navbar-static-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<a class="navbar-brand" href="{{ url() }}">{{ $site->general->title }}</a>
			</div>

			<nav class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav navbar-right">
					{{ View::menu('navigation') }}
				</ul>
			</nav>
		</div>
	</header>

	<div class="container">
		@yield('body')
	</div>

	<footer>
		<p>{{ $site->general->copyright }}</p>

		<!-- Please retain the following copyright notice. See http://opensource.org/licenses/BSD-3-Clause for details -->
		<p><a href="http://sayakbanerjee.com/sticky-notes">Sticky Notes</a> &copy; 2013 <a href="http://sayakbanerjee.com">Sayak Banerjee</a>.</p>

		@if (Antispam::flags()->php)
			<!-- Honeypot, do not click! -->
			<a href="http://www.ssdfreaks.com/household.php?to=857"><!-- agreement --></a>
		@endif
	</footer>
</body>

</html>
