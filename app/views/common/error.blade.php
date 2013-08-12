@extends('common.page')

@section('content')
	<div class="jumbotron">
		<h1>{{ Lang::get('errors.'.$errCode) }}</h1>
	</div>
@stop
