@extends('setup.page')

@section('body')
	<section id="install">
		<fieldset>
			<legend>{{ Lang::get('setup.stage2_title') }}</legend>

			<p>{{ Lang::get('setup.stage2_exp') }}</p>

			{{
				Form::submit(Lang::get('setup.start_install'), array(
					'name'    => 'install',
					'class'   => 'btn btn-primary'
				))
			}}
		</fieldset>
	</section>
@stop
