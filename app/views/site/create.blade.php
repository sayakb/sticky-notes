@extends('common.page')

@section('content')
	<div class="row">
		<div class="col-lg-4">
			<div class="form-group">
				{{
					Form::text('title', Input::old('title'), array(
						'class'			=> 'form-control',
						'maxlength'		=> 30,
						'placeholder'	=> Lang::get('create.paste_title')
					))
				}}
			</div>
		</div>

		<div class="col-lg-4"></div>

		<div class="col-lg-4">
			<div class="form-group">
				{{
					Form::select('language', $languages, Input::old('language'), array(
						'class'	=> 'form-control'
					))
				}}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				{{
					Form::textarea('data', Input::old('data'), array(
						'class'			=> 'form-control',
						'rows'			=> 18,
						'placeholder'	=> Lang::get('create.paste_data')
					))
				}}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-4">
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon">
						<span class="glyphicon glyphicon-lock"></span>
					</div>

					{{
						Form::password('password', array(
							'class'			=> 'form-control',
							'placeholder'	=> Lang::get('global.password')
						))
					}}
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="form-group">
				<label for="private" class="checkbox">
					{{ Form::checkbox('private', null, Input::old('private'), array('id' => 'private')) }}
					{{ Lang::get('create.mark_private') }}
				</label>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-btn">
						{{
							Form::submit(Lang::get('global.paste'), array(
								'name'	=> 'paste_submit',
								'class'	=> 'btn btn-primary'
							))
						}}
					</div>

					{{
						Form::select('expire', Config::get('expire'), Input::old('expire'), array(
							'class'	=> 'form-control'
						))
					}}
				</div>
			</div>
		</div>
	</div>
@stop
