@extends('common.page')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<div class="jumbotron">
				<h1>{{ Lang::get('errors.'.$errCode) }}</h1>
			</div>
		</div>
	</div>
@stop
