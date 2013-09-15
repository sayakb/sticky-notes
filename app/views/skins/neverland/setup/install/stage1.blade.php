@extends('skins.neverland.setup.page')

@section('body')
	<section id="install">
		@if (empty($error))
			<div class="alert alert-info">
				{{ Lang::get('setup.welcome') }}
			</div>
		@endif

		<fieldset>
			<legend>{{ Lang::get('setup.i_stage1_title') }}</legend>

			<p>{{ Lang::get('setup.i_stage1_exp') }}</p>

			<p>{{ Lang::get('setup.click_check') }}</p>

			{{
				Form::submit(Lang::get('setup.test_connection'), array(
					'name'    => '_test',
					'class'   => 'btn btn-primary'
				))
			}}
		</fieldset>
	</section>
@stop
