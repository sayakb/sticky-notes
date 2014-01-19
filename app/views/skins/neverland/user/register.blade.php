@extends("skins.neverland.common.{$container}")

@section('body')
	<section id="register">
		{{
			Form::open(array(
				'autocomplete'   => 'off',
				'role'           => 'form'
			))
		}}

		<div class="row-fluid">
			<div class="span6 offset3">
				<fieldset>
					<legend>{{ Lang::get('user.create_acct') }}</legend>

					@if ($site->auth->method != 'db' OR ! $site->auth->dbAllowReg)
						<div class="alert alert-danger">
							{{ Lang::get('user.reg_disabled') }}
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
					</div>

					<div class="form-group">
						{{ Form::label('email', Lang::get('global.email')) }}

						{{
							Form::text('email', NULL, array(
								'class'       => 'input-stretch',
								'maxlength'   => 100,
							))
						}}
					</div>

					<div class="form-group">
						{{ Form::label('dispname', Lang::get('global.full_name')) }}

						{{
							Form::text('dispname', NULL, array(
								'class'       => 'input-stretch',
								'maxlength'   => 100,
							))
						}}
					</div>

					<div class="form-group">
						{{ Form::label('password', Lang::get('global.password')) }}

						{{
							Form::password('password', array(
								'class'   => 'input-stretch',
							))
						}}
					</div>

					@if ($site->auth->dbShowCaptcha)
						<div class="form-group form-captcha">
							{{ Form::label('captcha', Lang::get('user.human_verify')) }}

							<div class="input-prepend">
								<span class="add-on">
									{{ HTML::image(Captcha::img()) }}
								</span>

								{{ Form::text('captcha', NULL) }}
							</div>
						</div>
					@endif

					<div class="form-actions">
						{{
							Form::submit(Lang::get('user.register'), array(
								'name'      => '_register',
								'class'     => 'btn btn-primary',
								'disabled'  => ($site->auth->method != 'db' OR ! $site->auth->dbAllowReg) ?: NULL,
							))
						}}
					</div>
				</fieldset>
			</div>
		</div>

		{{ Form::close() }}
	</section>
@stop
