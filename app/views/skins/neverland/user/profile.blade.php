@extends("skins.neverland.common.{$container}")

@section('body')
	<section id="register">
		{{
			Form::open(array(
				'autocomplete'   => 'off',
				'role'           => 'form',
			))
		}}

		<div class="row-fluid">
			<div class="span6 offset3">
				<fieldset>
					<legend>{{ Lang::get('user.your_profile') }}</legend>

					@if ($site->auth->method != 'db')
						<div class="alert alert-danger">
							{{ Lang::get('user.feature_disabled') }}
						</div>
					@endif

					@include('skins.neverland.common.alerts')

					<div class="form-group">
						{{ Form::label('username', Lang::get('global.username')) }}

						{{
							Form::text('username', $auth->username, array(
								'class'       => 'input-stretch',
								'disabled'    => ! $auth->admin ?: NULL
							))
						}}
					</div>

					<div class="form-group">
						{{ Form::label('email', Lang::get('global.email')) }}

						{{
							Form::text('email', $auth->email, array(
								'class'       => 'input-stretch',
								'maxlength'   => 100,
							))
						}}
					</div>

					<div class="form-group">
						{{ Form::label('dispname', Lang::get('global.full_name')) }}

						{{
							Form::text('dispname', $auth->dispname, array(
								'class'       => 'input-stretch',
								'maxlength'   => 100,
							))
						}}
					</div>

					@if ($site->auth->method == 'db')
						<div class="form-group">
							{{ Form::label('password', Lang::get('user.new_password')) }}

							{{
								Form::password('password', array(
									'class'   => 'input-stretch',
								))
							}}
						</div>
					@endif

					<div class="form-actions">
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
					</div>
				</fieldset>
			</div>
		</div>

		{{ Form::close() }}
	</section>
@stop
