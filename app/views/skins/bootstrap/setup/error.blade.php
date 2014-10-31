@extends('skins.bootstrap.setup.page')

@section('body')
	<section id="install">
		<fieldset>
			<legend>{{ Lang::get('setup.error_title') }}</legend>

			<p>{{ Lang::get('setup.error_exp') }}</p>

			<div class="well well-sm well-white">
				{{ Session::get('setup.error') }}
			</div>
		</fieldset>
	</section>
@stop
