@extends('skins.bootstrap.admin.layout')

@section('module')
	<section id="admin-mail">
		{{
			Form::open(array(
				'autocomplete'   => 'off',
				'role'           => 'form',
				'class'          => 'form-horizontal',
			))
		}}

		<div class="row">
			<div class="col-sm-12">
				<fieldset>
					<legend>{{ Lang::get('admin.mail_settings') }}</legend>

					<div class="form-group">
						{{
							Form::label('driver', Lang::get('admin.driver'), array(
								'class' => 'control-label col-sm-3 col-lg-2'
							))
						}}

						<div class="col-sm-9 col-lg-10">
							{{
								Form::select('driver', array(
									'smtp'      => Lang::get('admin.smtp'),
									'mail'      => Lang::get('admin.mail'),
									'sendmail'  => Lang::get('admin.sendmail'),
								), $site->mail->driver, array(
									'class' => 'form-control'
								))
							}}
						</div>
					</div>

					<div class="form-group">
						{{
							Form::label('host', Lang::get('admin.smtp_host'), array(
								'class' => 'control-label col-sm-3 col-lg-2'
							))
						}}

						<div class="col-sm-9 col-lg-10">
							{{
								Form::text('host', $site->mail->host, array(
									'class' => 'form-control',
								))
							}}
						</div>
					</div>

					<div class="form-group">
						{{
							Form::label('port', Lang::get('admin.smtp_port'), array(
								'class' => 'control-label col-sm-3 col-lg-2'
							))
						}}

						<div class="col-sm-9 col-lg-10">
							{{
								Form::text('port', $site->mail->port, array(
									'class' => 'form-control',
								))
							}}
						</div>
					</div>

					<div class="form-group">
						{{
							Form::label('address', Lang::get('admin.from_address'), array(
								'class' => 'control-label col-sm-3 col-lg-2'
							))
						}}

						<div class="col-sm-9 col-lg-10">
							{{
								Form::text('address', $site->mail->address, array(
									'class' => 'form-control',
								))
							}}
						</div>
					</div>

					<div class="form-group">
						{{
							Form::label('name', Lang::get('admin.from_name'), array(
								'class' => 'control-label col-sm-3 col-lg-2'
							))
						}}

						<div class="col-sm-9 col-lg-10">
							{{
								Form::text('name', $site->mail->name, array(
									'class' => 'form-control',
								))
							}}
						</div>
					</div>

					<div class="form-group">
						{{
							Form::label('encryption', Lang::get('admin.encryption'), array(
								'class' => 'control-label col-sm-3 col-lg-2'
							))
						}}

						<div class="col-sm-9 col-lg-10">
							{{
								Form::select('encryption', array(
									''      => Lang::get('admin.none'),
									'ssl'   => Lang::get('admin.ssl'),
									'tls'   => Lang::get('admin.tls'),
								), $site->mail->encryption, array(
									'class' => 'form-control'
								))
							}}
						</div>
					</div>

					<div class="form-group">
						{{
							Form::label('username', Lang::get('admin.smtp_username'), array(
								'class' => 'control-label col-sm-3 col-lg-2'
							))
						}}

						<div class="col-sm-9 col-lg-10">
							{{
								Form::text('username', $site->mail->username, array(
									'class' => 'form-control',
								))
							}}
						</div>
					</div>

					<div class="form-group">
						{{
							Form::label('password', Lang::get('admin.smtp_password'), array(
								'class' => 'control-label col-sm-3 col-lg-2'
							))
						}}

						<div class="col-sm-9 col-lg-10">
							{{
								Form::input('password', 'password', $site->mail->password, array(
									'class' => 'form-control',
								));
							}}
						</div>
					</div>

					<div class="form-group">
						{{
							Form::label('sendmail', Lang::get('admin.sendmail_path'), array(
								'class' => 'control-label col-sm-3 col-lg-2'
							))
						}}

						<div class="col-sm-9 col-lg-10">
							{{
								Form::text('sendmail', $site->mail->sendmail, array(
									'class' => 'form-control',
								))
							}}
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-offset-3 col-lg-offset-2 col-sm-9 col-lg-10">
							<div class="inline">
								{{
									Form::submit(Lang::get('global.submit'), array(
										'name'    => '_save',
										'class'   => 'btn btn-primary'
									))
								}}

								{{
									Form::submit(Lang::get('admin.test_mail_settings'), array(
										'name'    => '_test',
										'class'   => 'btn btn-primary'
									))
								}}
							</div>
						</div>
					</div>
				</fieldset>
			</div>
		</div>

		{{ Form::close() }}
	</section>
@stop
