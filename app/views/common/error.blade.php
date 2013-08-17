@extends('common.page')

@section('content')
	<div class="row">
		<div class="col-sm-12">
			<div class="jumbotron text-center">
				<h1><span class="glyphicon glyphicon-exclamation-sign text-danger"></span></h1>
				<h2>{{ Lang::get('errors.'.$errCode) }}</h2>
			</div>
		</div>
	</div>
@stop
