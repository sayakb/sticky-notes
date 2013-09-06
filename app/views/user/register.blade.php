@extends('common.page')

@section('body')
	<section id="register">
		{{
			Form::open(array(
				'autocomplete'   => 'off',
				'role'           => 'form'
			))
		}}

		<div class="row">
			<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
				<fieldset>
					<legend>{{ Lang::get('user.create_acct') }}</legend>

					@include('common.alerts')

					<div class="form-group">
						{{ Form::label('username', Lang::get('global.username')) }}

						{{
							Form::text('username', NULL, array(
								'class'       => 'form-control',
								'maxlength'   => 50,
							))
						}}
					</div>

					<div class="form-group">
						{{ Form::label('email', Lang::get('global.email')) }}

						{{
							Form::text('email', NULL, array(
								'class'       => 'form-control',
								'maxlength'   => 100,
							))
						}}
					</div>

					<div class="form-group">
						{{ Form::label('dispname', Lang::get('global.full_name')) }}

						{{
							Form::text('dispname', NULL, array(
								'class'       => 'form-control',
								'maxlength'   => 100,
							))
						}}
					</div>

					<div class="form-group">
						{{ Form::label('password', Lang::get('global.password')) }}

						{{
							Form::password('password', array(
								'class'   => 'form-control',
							))
						}}
					</div>

					@if ($auth->dbShowCaptcha)
						<div class="form-group form-captcha">
							{{ Form::label('captcha', Lang::get('user.human_verify')) }}

							<div class="input-group">
								<span class="input-group-addon">
									{{ HTML::image(Captcha::img()) }}
								</span>

								{{
									Form::text('captcha', NULL, array(
										'class'   => 'form-control',
									))
								}}
							</div>
						</div>
					@endif

					{{
						Form::submit(Lang::get('user.register'), array(
							'name'    => 'register',
							'class'   => 'btn btn-primary'
						))
					}}
				</fieldset>
			</div>
		</div>

		{{ Form::close() }}
	</section>
@stop
