@extends('skins.bootstrap.setup.page')

@section('body')
	<section id="install">
		<fieldset>
			<legend>{{ Lang::get('setup.i_stage2_title') }}</legend>

			<div class="alert alert-danger">
				{{ sprintf(Lang::get('setup.install_warn'), link_to('setup/update', Lang::get('setup.update_util'))) }}
			</div>

			<p>{{ Lang::get('setup.i_stage2_exp') }}</p>

			{{
				Form::submit(Lang::get('setup.start_install'), array(
					'name'    => '_install',
					'class'   => 'btn btn-primary'
				))
			}}
		</fieldset>
	</section>
@stop
