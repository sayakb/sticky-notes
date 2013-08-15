@extends('common.page')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<fieldset>
				<legend>{{ sprintf(Lang::get('global.login_to'), $site->title) }}</legend>

				<div class="form-group">
					{{ Form::label('username', Lang::get('global.username')) }}

					{{
						Form::text('username', Input::old('username'), array(
							'class'       => 'form-control',
							'maxlength'   => 50
						))
					}}
				</div>

				<div class="form-group">
					{{ Form::label('password', Lang::get('global.password')) }}

					{{
						Form::password('password', array(
							'class' => 'form-control'
						))
					}}
				</div>

				<div class="checkbox">
					<label>
						{{
							Form::checkbox('remember', null, Input::old('remember'), array(
								'id' => 'remember'
							))
						}}

						{{ Lang::get('global.remember') }}
					</label>
				</div>

				{{
					Form::submit(Lang::get('global.login'), array(
						'name'    => 'login',
						'class'   => 'btn btn-primary'
					))
				}}
			</fieldset>
		</div>
	</div>
@stop
