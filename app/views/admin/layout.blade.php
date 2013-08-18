@extends('common.page')

@section('body')
	<div class="row">
		<div class="col-sm-3">
			<ul class="nav nav-pills nav-stacked">
				{{ Site::getMenu('admin') }}
			</ul>
		</div>

		<div class="col-sm-9">
			@yield('module')
		</div>
	</div>
@stop
