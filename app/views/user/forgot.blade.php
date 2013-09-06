@extends('common.page')

@section('body')
	<section id="forgot">
		{{
			Form::open(array(
				'autocomplete'   => 'off',
				'role'           => 'form'
			))
		}}

		<div class="row">
			<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
				<fieldset>
					<legend>{{ Lang::get('user.forgot_password') }}</legend>

					@include('common.alerts')

					<div class="form-group">
						{{ Form::label('username', Lang::get('global.username')) }}

						{{
							Form::text('username', NULL, array(
								'class'       => 'form-control',
								'maxlength'   => 50
							))
						}}

						<div class="help-block">
							{{ Lang::get('user.forgot_exp') }}
						</div>
					</div>

					{{
						Form::submit(Lang::get('user.reset_password'), array(
							'name'    => '_reset',
							'class'   => 'btn btn-primary'
						))
					}}
				</fieldset>
			</div>
		</div>

		{{ Form::close() }}
	</section>
@stop
