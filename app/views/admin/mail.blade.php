@extends('admin.layout')

@section('module')
	<section id="admin-mail">
		{{
			Form::open(array(
				'autocomplete'   => 'off',
				'role'           => 'form'
			))
		}}

		<div class="row">
			<div class="col-sm-12">
				<fieldset>
					<legend>{{ Lang::get('admin.mail_settings') }}</legend>

					<div class="row">
						<div class="col-sm-8 col-md-7 col-lg-6">
							<div class="form-group">
								{{ Form::label('driver', Lang::get('admin.driver')) }}

								{{
									Form::select('driver', array(
										'smtp'      => Lang::get('admin.smtp'),
										'mail'      => Lang::get('admin.mail'),
										'sendmail'  => Lang::get('admin.sendmail'),
									), $mail->driver, array(
										'class' => 'form-control'
									))
								}}
							</div>

							<div class="form-group">
								{{ Form::label('host', Lang::get('admin.smtp_host')) }}

								{{
									Form::text('host', $mail->host, array(
										'class' => 'form-control',
									))
								}}
							</div>

							<div class="form-group">
								{{ Form::label('port', Lang::get('admin.smtp_port')) }}

								{{
									Form::text('port', $mail->port, array(
										'class' => 'form-control',
									))
								}}
							</div>

							<div class="form-group">
								{{ Form::label('address', Lang::get('admin.from_address')) }}

								{{
									Form::text('address', $mail->address, array(
										'class' => 'form-control',
									))
								}}
							</div>

							<div class="form-group">
								{{ Form::label('name', Lang::get('admin.from_name')) }}

								{{
									Form::text('name', $mail->name, array(
										'class' => 'form-control',
									))
								}}
							</div>

							<div class="form-group">
								{{ Form::label('encryption', Lang::get('admin.encryption')) }}

								{{
									Form::select('encryption', array(
										''      => Lang::get('admin.none'),
										'ssl'   => Lang::get('admin.ssl'),
										'tls'   => Lang::get('admin.tls'),
									), $mail->encryption, array(
										'class' => 'form-control'
									))
								}}
							</div>

							<div class="form-group">
								{{ Form::label('username', Lang::get('admin.smtp_username')) }}

								{{
									Form::text('username', $mail->username, array(
										'class' => 'form-control',
									))
								}}
							</div>

							<div class="form-group">
								{{ Form::label('password', Lang::get('admin.smtp_password')) }}

								{{
									Form::input('password', 'password', $mail->password, array(
										'class' => 'form-control',
									));
								}}
							</div>

							<div class="form-group">
								{{ Form::label('sendmail', Lang::get('admin.sendmail_path')) }}

								{{
									Form::text('sendmail', $mail->sendmail, array(
										'class' => 'form-control',
									))
								}}
							</div>

							{{
								Form::submit(Lang::get('global.submit'), array(
									'name'    => 'save',
									'class'   => 'btn btn-primary'
								))
							}}
						</div>
					</div>
				</fieldset>
			</div>
		</div>

		{{ Form::token() }}
		{{ Form::close() }}
	</section>
@stop
