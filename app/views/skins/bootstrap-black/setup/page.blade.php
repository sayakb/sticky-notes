<!DOCTYPE html>

<html lang="{{ $site->general->lang }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ Lang::get('global.sticky_notes') }}</title>

	<link href="{{ View::asset('img/favicon.png') }}" rel="shortcut icon" />
	<link href="{{ View::asset('css/bootstrap.min.css') }}" rel="stylesheet" />
	<link href="{{ View::asset('css/stickynotes.css') }}" rel="stylesheet" />

	<script type="text/javascript" src="{{ View::asset('js/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ View::asset('js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ View::asset('js/stickynotes-setup.js') }}"></script>
</head>

<body>
	<nav class="navbar navbar-default navbar-static-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="{{ url() }}">{{ Lang::get('setup.installer') }}</a>
			</div>
		</div>
	</nav>

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
		<!-- Do not the following copyright notice to avoid copyright violation. See http://opensource.org/licenses/BSD-2-Clause for details -->
		<!-- You may however choose to remove the hyperlinks and retain the copyright as plain text -->
		<p><a href="http://sayakbanerjee.com/sticky-notes">Sticky Notes</a> &copy; 2014 <a href="http://sayakbanerjee.com">Sayak Banerjee</a>.</p>
	</footer>
</body>

</html>
