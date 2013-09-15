@extends('skins.neverland.setup.page')

@section('body')
	<section id="install">
		<fieldset>
			<legend>{{ Lang::get('setup.error_title') }}</legend>

			<p>{{ Lang::get('setup.error_exp') }}</p>

			<div class="well well-small well-white">
				{{ Session::get('setup.error') }}
			</div>
		</fieldset>
	</section>
@stop
