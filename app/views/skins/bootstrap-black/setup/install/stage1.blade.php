@extends('skins.bootstrap.setup.page')

@section('body')
	<section id="install">
		@if (empty($error))
			<div class="alert alert-info">
				{{ Lang::get('setup.welcome') }}
			</div>

			@if ($unstable)
				<div class="alert alert-danger">
					{{ sprintf(Lang::get('setup.develop_warn'), $site->services->downloadUrl) }}
				</div>
			@endif
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
