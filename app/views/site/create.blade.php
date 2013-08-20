@extends('common.page')

@section('body')
	@include('common.alerts')

	<section id="create">
		{{
			Form::open(array(
				'action'         => 'CreateController@postCreate',
				'autocomplete'   => 'off',
				'role'           => 'form'
			))
		}}

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					{{
						Form::text('title', NULL, array(
							'class'         => 'form-control',
							'maxlength'     => 30,
							'placeholder'   => Lang::get('global.paste_title')
						))
					}}
				</div>
			</div>

			<div class="col-sm-4"></div>

			<div class="col-sm-4">
				<div class="form-group">
					{{
						Form::select('language', $languages, NULL, array(
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
						Form::textarea('data', NULL, array(
							'class'         => 'form-control',
							'rows'          => 18,
							'placeholder'   => Lang::get('global.paste_data')
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
								Form::checkbox('private', NULL, NULL, array(
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
							Form::select('expire', Config::get('expire'), NULL, array(
								'class' => 'form-control'
							))
						}}
					</div>
				</div>
			</div>
		</div>

		{{ Form::token() }}
		{{ Form::close() }}
	</section>
@stop
