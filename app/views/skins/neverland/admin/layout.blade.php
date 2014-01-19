@extends("skins.neverland.common.{$container}")

@section('body')
	<div class="row-fluid">
		<div class="span3">
			<ul class="nav nav-pills nav-stacked">
				{{ View::menu('admin') }}
			</ul>
		</div>

		<div class="span9">
			@include('skins.neverland.common.alerts')
			@yield('module')
		</div>
	</div>
@stop
