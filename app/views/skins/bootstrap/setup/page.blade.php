<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8">
	<title>{{ Lang::get('global.sticky_notes') }}</title>

	<link href="{{ View::asset('img/favicon.png') }}" rel="shortcut icon" />
	<link href="{{ View::asset('css/bootstrap.min.css') }}" rel="stylesheet" />
	<link href="{{ View::asset('css/stickynotes.css') }}" rel="stylesheet" />

	<script type="text/javascript" src="{{ View::asset('js/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ View::asset('js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ View::asset('js/stickynotes-setup.js') }}"></script>
</head>

<body>
	<header class="navbar navbar-default navbar-static-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="{{ url() }}">{{ Lang::get('setup.installer') }}</a>
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

		@include('skins.bootstrap.common.alerts')

		@yield('body')

		{{ Form::close() }}
	</div>

	<footer>
		<!-- Please retain the following copyright notice. See http://opensource.org/licenses/BSD-3-Clause for details -->
		<p><a href="http://sayakbanerjee.com/sticky-notes">Sticky Notes</a> &copy; 2013 <a href="http://sayakbanerjee.com">Sayak Banerjee</a>.</p>
	</footer>
</body>

</html>
