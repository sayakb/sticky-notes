<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8">
	<title>{{ $site->title }}</title>

	<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/css/stickynotes.css') }}" rel="stylesheet" />

	<script src="//code.jquery.com/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/stickynotes.js') }}"></script>

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

				<a class="navbar-brand" href="{{ url() }}">{{ $site->title }}</a>
			</div>

			<nav class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav navbar-right">
					{{ Site::getMenu('navigation') }}
				</ul>
			</nav>
		</div>
	</header>

	<div class="container">
		@yield('body')
	</div>

	<footer>
		<p>{{ $site->copyright }}</p>

		<!-- Please retain the following copyright notice. See http://opensource.org/licenses/BSD-3-Clause for details -->
		<p><a href="http://sayakbanerjee.com/sticky-notes">Sticky Notes</a> &copy; 2013 <a href="http://sayakbanerjee.com">Sayak Banerjee</a>.</p>
	</footer>
</body>
</html>
