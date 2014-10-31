@extends("skins.bootstrap.common.{$container}")

@section('body')
	<section id="register">
		{{
			Form::open(array(
				'autocomplete'   => 'off',
				'role'           => 'form',
			))
		}}

		<div class="row">
			<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
				<fieldset>
					<legend>{{ Lang::get('user.your_profile') }}</legend>

					@if ($site->auth->method != 'db')
						<div class="alert alert-danger">
							{{ Lang::get('user.feature_disabled') }}
						</div>
					@endif

					@include('skins.bootstrap.common.alerts')

					<div class="form-group">
						{{ Form::label('username', Lang::get('global.username')) }}

						{{
							Form::text('username', $auth->username, array(
								'class'       => 'form-control',
								'disabled'    => ! $auth->admin ?: NULL
							))
						}}
					</div>

					<div class="form-group">
						{{ Form::label('email', Lang::get('global.email')) }}

						{{
							Form::text('email', $auth->email, array(
								'class'       => 'form-control',
								'maxlength'   => 100,
							))
						}}
					</div>

					<div class="form-group">
						{{ Form::label('dispname', Lang::get('global.full_name')) }}

						{{
							Form::text('dispname', $auth->dispname, array(
								'class'       => 'form-control',
								'maxlength'   => 100,
							))
						}}
					</div>

					@if ($site->auth->method == 'db')
						<div class="form-group">
							{{ Form::label('password', Lang::get('user.new_password')) }}

							{{
								Form::password('password', array(
									'class'   => 'form-control',
								))
							}}
						</div>
					@endif

					{{
						Form::submit(Lang::get('global.save'), array(
							'name'      => '_save',
							'class'     => 'btn btn-primary',
							'disabled'  => $site->auth->method != 'db' ?: NULL,
						))
					}}

					{{
						link_to("user/u{$auth->id}/pastes", Lang::get('user.my_pastes'), array(
							'class' => 'btn btn-link',
						))
					}}
				</fieldset>
			</div>
		</div>

		{{ Form::close() }}
	</section>
@stop
