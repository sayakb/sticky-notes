<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8">
	<title>{{ Lang::get('global.sticky_notes') }}</title>

	<link href="//cdn.kde.org/img/favicon.png" rel="shortcut icon" />
	<link href="//cdn.kde.org/css/bootstrap.css" rel="stylesheet" />
	<link href="//cdn.kde.org/css/bootstrap-responsive.css" rel="stylesheet" />
	<link href="//cdn.kde.org/css/bootstrap-stickynotes.css" rel="stylesheet" />

	<script type="text/javascript" src="{{ View::asset('js/jquery.min.js') }}"></script>
	<script type="text/javascript" src="//cdn.kde.org/js/bootstrap.js"></script>
	<script type="text/javascript" src="{{ View::asset('js/stickynotes-setup.js') }}"></script>
</head>

<body>
	<header class="navbar navbar-static-top Neverland" role="navigation">
		<div class="navbar-inner">
			<div class="container">
				<div class="navbar-header">
					<a class="brand" href="{{ url() }}">
						{{ HTML::image('//cdn.kde.org/img/logo.plain.small.png') }}
						{{ Lang::get('setup.installer') }}
					</a>
				</div>
			</div>
		</div>
	</header>

	<div class="container">
		{{
			Form::open(array(
				'autocomplete'   => 'off',
				'role'           => 'form',
			))
		}}

		@include('skins.neverland.common.alerts')

		@yield('body')

		{{ Form::close() }}

		<footer class="align-center">
			<!-- Please retain the following copyright notice. See http://opensource.org/licenses/BSD-3-Clause for details -->
			<p><a href="http://sayakbanerjee.com/sticky-notes">Sticky Notes</a> &copy; 2013 <a href="http://sayakbanerjee.com">Sayak Banerjee</a>.</p>
		</footer>
	</div>
</body>

</html>
