@extends('admin.layout')

@section('module')
	<section id="admin-auth">
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
					<legend>{{ Lang::get('admin.auth_settings') }}</legend>

					<div class="form-group">
						{{
							Form::label('method', Lang::get('admin.auth_method'), array(
								'class' => 'control-label col-sm-3 col-lg-2'
							))
						}}

						<div class="col-sm-9 col-lg-10">
							{{
								Form::select('method', array(
									'db'      => Lang::get('admin.db'),
									'ldap'    => Lang::get('admin.ldap'),
								), $auth->method, array(
									'class' => 'form-control'
								))
							}}
						</div>
					</div>

					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#auth-ldap" data-toggle="tab">{{ Lang::get('admin.ldap') }}</a>
						</li>

						<li>
							<a href="#auth-db" data-toggle="tab">{{ Lang::get('admin.db') }}</a>
						</li>
					</ul>

					<div class="tab-content">
						<div id="auth-ldap" class="tab-pane fade in active">
							<div class="form-group">
								{{
									Form::label('ldap_server', Lang::get('admin.ldap_server'), array(
										'class' => 'control-label col-sm-3 col-lg-2'
									))
								}}

								<div class="col-sm-9 col-lg-10">
									{{
										Form::text('ldap_server', $auth->ldapServer, array(
											'class' => 'form-control',
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.ldap_server_exp') }}
									</div>
								</div>
							</div>

							<div class="form-group">
								{{
									Form::label('ldap_port', Lang::get('admin.ldap_port'), array(
										'class' => 'control-label col-sm-3 col-lg-2'
									))
								}}

								<div class="col-sm-9 col-lg-10">
									{{
										Form::text('ldap_port', $auth->ldapPort, array(
											'class' => 'form-control',
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.ldap_port_exp') }}
									</div>
								</div>
							</div>

							<div class="form-group">
								{{
									Form::label('ldap_base_dn', Lang::get('admin.base_dn'), array(
										'class' => 'control-label col-sm-3 col-lg-2'
									))
								}}

								<div class="col-sm-9 col-lg-10">
									{{
										Form::text('ldap_base_dn', $auth->ldapBaseDn, array(
											'class' => 'form-control',
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.base_dn_exp') }}
									</div>
								</div>
							</div>

							<div class="form-group">
								{{
									Form::label('ldap_uid', Lang::get('admin.uid'), array(
										'class' => 'control-label col-sm-3 col-lg-2'
									))
								}}

								<div class="col-sm-9 col-lg-10">
									{{
										Form::text('ldap_uid', $auth->ldapUid, array(
											'class' => 'form-control',
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.uid_exp') }}
									</div>
								</div>
							</div>

							<div class="form-group">
								{{
									Form::label('ldap_filter', Lang::get('admin.user_filter'), array(
										'class' => 'control-label col-sm-3 col-lg-2'
									))
								}}

								<div class="col-sm-9 col-lg-10">
									{{
										Form::text('ldap_filter', $auth->ldapFilter, array(
											'class' => 'form-control',
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.user_filter_exp') }}
									</div>
								</div>
							</div>

							<div class="form-group">
								{{
									Form::label('ldap_user_dn', Lang::get('admin.user_dn'), array(
										'class' => 'control-label col-sm-3 col-lg-2'
									))
								}}

								<div class="col-sm-9 col-lg-10">
									{{
										Form::text('ldap_user_dn', $auth->ldapUserDn, array(
											'class' => 'form-control',
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.user_dn_exp') }}
									</div>
								</div>
							</div>

							<div class="form-group">
								{{
									Form::label('ldap_password', Lang::get('global.password'), array(
										'class' => 'control-label col-sm-3 col-lg-2'
									))
								}}

								<div class="col-sm-9 col-lg-10">
									{{
										Form::input('password', 'ldap_password', $auth->ldapPassword, array(
											'class' => 'form-control',
										));
									}}

									<div class="help-block">
										{{ Lang::get('admin.ldap_password_exp') }}
									</div>
								</div>
							</div>
						</div>

						<div id="auth-db" class="tab-pane fade">
							<div class="form-group">
								{{
									Form::label('db_allow_reg', Lang::get('admin.user_reg'), array(
										'class' => 'control-label col-sm-3 col-lg-2'
									))
								}}

								<div class="col-sm-9 col-lg-10">
									{{
										Form::select('db_allow_reg', array(
											'1' => Lang::get('admin.enabled'),
											'0' => Lang::get('admin.disabled'),
										), $auth->dbAllowReg, array(
											'class' => 'form-control'
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.user_reg_exp') }}
									</div>
								</div>
							</div>

							<div class="form-group">
								{{
									Form::label('db_show_captcha', Lang::get('admin.reg_captcha'), array(
										'class' => 'control-label col-sm-3 col-lg-2'
									))
								}}

								<div class="col-sm-9 col-lg-10">
									{{
										Form::select('db_show_captcha', array(
											'1' => Lang::get('admin.enabled'),
											'0' => Lang::get('admin.disabled'),
										), $auth->dbShowCaptcha, array(
											'class' => 'form-control'
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.reg_captcha_exp') }}
									</div>
								</div>
							</div>
						</div>
					</div>

					<hr />

					<div class="form-group">
						<div class="col-sm-12">
							{{
								Form::submit(Lang::get('admin.save_all'), array(
									'name'    => '_save',
									'class'   => 'btn btn-primary'
								))
							}}
						</div>
					</div>
				</fieldset>
			</div>
		</div>

		{{ Form::close() }}
	</section>
@stop
