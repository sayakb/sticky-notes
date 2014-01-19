@extends("skins.neverland.common.{$container}")

@section('body')
	<section id="error">
		<div class="row-fluid">
			<div class="span12">
				<div class="hero-unit align-center">
					{{ HTML::image(View::asset('img/exclamation-sign.png')) }}
					<h2>{{ Lang::get('errors.'.$errCode) }}</h2>
				</div>
			</div>
		</div>
	</section>
@stop
