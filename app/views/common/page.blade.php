<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8">
	<title>{{ $site->title }}</title>

	<link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/css/bootstrap-glyphicons.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/css/stickynotes.css') }}" rel="stylesheet" />

	<script src="//code.jquery.com/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="{{ asset('assets/js/bootstrap.js') }}"></script>
</head>

<body>
	<div class="navbar navbar-static-top">
		<div class="container">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			<a class="navbar-brand" href="{{ url() }}">{{ $site->title }}</a>

			<div class="nav-collapse collapse navbar-responsive-collapse">
				<ul class="nav navbar-nav pull-right">
					{{ Site::getMenu() }}
				</ul>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				@if ( ! empty($success))
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>

						@foreach ($success as $msg)
							{{ $msg }}
						@endforeach
					</div>
				@elseif ( ! empty($error))
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert">&times;</button>

						@foreach ($error as $msg)
							{{ $msg }}
						@endforeach
					</div>
				@endif
			</div>
		</div>

	<div class="container">
		{{ Form::open(array('autocomplete' => 'off')) }}
			@yield('content')
		{{ Form::close() }}
	</div>

	<footer>&copy; 2013 Sayak Banerjee</footer>
</body>
</html>
