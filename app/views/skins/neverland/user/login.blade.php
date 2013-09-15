@extends('skins.neverland.common.page')

@section('body')
	<section id="login">
		{{
			Form::open(array(
				'autocomplete'   => 'off',
				'role'           => 'form'
			))
		}}

		<div class="row-fluid">
			<div class="span6 offset3">
				<fieldset>
					<legend>{{ sprintf(Lang::get('user.login_to'), $site->general->title) }}</legend>

					@include('skins.neverland.common.alerts')

					<div class="form-group">
						{{ Form::label('username', Lang::get('global.username')) }}

						{{
							Form::text('username', NULL, array(
								'class'       => 'input-stretch',
								'maxlength'   => 50
							))
						}}
					</div>

					<div class="form-group">
						{{ Form::label('password', Lang::get('global.password')) }}

						{{
							Form::password('password', array(
								'class' => 'input-stretch'
							))
						}}
					</div>

					<div class="checkbox">
						<label>
							{{
								Form::checkbox('remember', NULL, NULL, array(
									'id' => 'remember'
								))
							}}

							{{ Lang::get('user.remember') }}
						</label>
					</div>

					<div class="form-actions">
						{{
							Form::submit(Lang::get('user.login'), array(
								'name'    => '_login',
								'class'   => 'btn btn-primary'
							))
						}}

						@if ($site->auth->method == 'db')
							@if ($site->auth->dbAllowReg)
								{{
									link_to('user/register', Lang::get('user.create_acct'), array(
										'class'   => 'btn btn-link',
									))
								}}
							@endif

							{{
								link_to('user/forgot', Lang::get('user.forgot_password'), array(
									'class'   => 'btn btn-link',
								))
							}}
						@endif
					</div>
				</fieldset>
			</div>
		</div>

		{{ Form::close() }}
	</section>
@stop
