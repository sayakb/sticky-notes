@extends("skins.bootstrap.common.{$container}")

@section('body')
	<div class="row">
		<div class="col-sm-3">
			<ul class="nav nav-pills nav-stacked">
				{{ View::menu('admin') }}
			</ul>
		</div>

		<div class="col-sm-9">
			@include('skins.bootstrap.common.alerts')
			@yield('module')
		</div>
	</div>
@stop
