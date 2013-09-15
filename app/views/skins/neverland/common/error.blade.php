@extends('skins.neverland.common.page')

@section('body')
	<section id="error">
		<div class="row-fluid">
			<div class="span12">
				<div class="hero-unit align-center">
					{{ HTML::image(View::asset('img/exclamation-sign.png')) }}
					<h1>{{ Lang::get('errors.'.$errCode) }}</h1>
				</div>
			</div>
		</div>
	</section>
@stop
