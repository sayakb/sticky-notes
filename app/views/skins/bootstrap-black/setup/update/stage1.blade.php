@extends('skins.bootstrap.setup.page')

@section('body')
	<section id="update">
		@if ($unstable)
			<div class="alert alert-danger">
				{{ sprintf(Lang::get('setup.develop_warn'), $site->services->downloadUrl) }}
			</div>
		@endif

		<fieldset>
			<legend>{{ Lang::get('setup.u_stage1_title') }}</legend>

			<p>{{ Lang::get('setup.u_stage1_exp') }}</p>

			<p>{{ Lang::get('setup.update_config') }}</p>

			<div class="form-group">
				{{
					Form::label('version', Lang::get('setup.update_from'), array(
						'class' => 'control-label'
					))
				}}

				{{
					Form::select('version', $versions, Site::config('general')->version, array(
						'class' => 'form-control'
					))
				}}
			</div>

			{{
				Form::submit(Lang::get('setup.start_update'), array(
					'name'    => '_update',
					'class'   => 'btn btn-primary'
				))
			}}
		</fieldset>
	</section>
@stop
