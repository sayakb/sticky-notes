@extends('common.page')

@section('body')
	<section id="create">
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					{{
						Form::text('title', Input::old('title'), array(
							'class'         => 'form-control',
							'maxlength'     => 30,
							'placeholder'   => Lang::get('create.paste_title')
						))
					}}
				</div>
			</div>

			<div class="col-sm-4"></div>

			<div class="col-sm-4">
				<div class="form-group">
					{{
						Form::select('language', $languages, Input::old('language'), array(
							'class' => 'form-control'
						))
					}}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					{{
						Form::textarea('data', Input::old('data'), array(
							'class'         => 'form-control',
							'rows'          => 18,
							'placeholder'   => Lang::get('create.paste_data')
						))
					}}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-lock"></span>
						</div>

						{{
							Form::password('password', array(
								'class'         => 'form-control',
								'placeholder'   => Lang::get('global.password')
							))
						}}
					</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group">
					<div class="checkbox">
						<label>
							{{
								Form::checkbox('private', null, Input::old('private'), array(
									'id' => 'private'
								))
							}}

							{{ Lang::get('create.mark_private') }}
						</label>
					</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-btn">
							{{
								Form::submit(Lang::get('global.paste'), array(
									'name'    => 'submit',
									'class'   => 'btn btn-primary'
								))
							}}
						</div>

						{{
							Form::select('expire', Config::get('expire'), Input::old('expire'), array(
								'class' => 'form-control'
							))
						}}
					</div>
				</div>
			</div>
		</div>
	</section>
@stop
