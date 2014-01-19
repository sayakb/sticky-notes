@extends("skins.neverland.common.{$container}")

@section('body')
	<section id="forgot">
		{{
			Form::open(array(
				'autocomplete'   => 'off',
				'role'           => 'form'
			))
		}}

		<div class="row-fluid">
			<div class="span6 offset3">
				<fieldset>
					<legend>{{ Lang::get('user.forgot_password') }}</legend>

					@if ($site->auth->method != 'db')
						<div class="alert alert-danger">
							{{ Lang::get('user.feature_disabled') }}
						</div>
					@endif

					@include('skins.neverland.common.alerts')

					<div class="form-group">
						{{ Form::label('username', Lang::get('global.username')) }}

						{{
							Form::text('username', NULL, array(
								'class'       => 'input-stretch',
								'maxlength'   => 50,
							))
						}}

						<div class="help-block">
							{{ Lang::get('user.forgot_exp') }}
						</div>
					</div>

					<div class="form-actions">
						{{
							Form::submit(Lang::get('user.reset_password'), array(
								'name'      => '_reset',
								'class'     => 'btn btn-primary',
								'disabled'  => $site->auth->method != 'db' ?: NULL,
							))
						}}
					</div>
				</fieldset>
			</div>
		</div>

		{{ Form::close() }}
	</section>
@stop
