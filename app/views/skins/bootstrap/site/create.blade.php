@extends("skins.bootstrap.common.{$container}")

@section('body')
	@include('skins.bootstrap.common.alerts')

	<section id="create">
		{{
			Form::open(array(
				'action'         => $action,
				'autocomplete'   => 'off',
				'role'           => 'form',
				'files'          => TRUE
			))
		}}

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					{{
						Form::text('title', $paste->title, array(
							'class'         => 'form-control',
							'maxlength'     => 30,
							'placeholder'   => Lang::get('global.paste_title'),
							'disabled'      => $disabled
						))
					}}
				</div>
			</div>

			<div class="col-sm-4"></div>

			<div class="col-sm-4">
				<div class="form-group">
					{{
						Form::select('language', $languages, $paste->language ?: $language, array(
							'class'    => 'form-control',
							'disabled' => $disabled
						))
					}}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					{{
						Form::textarea('data', $paste->data, array(
							'class'         => 'form-control',
							'rows'          => 18,
							'placeholder'   => Lang::get('global.paste_data')
						))
					}}
				</div>
			</div>
		</div>

		@if ($site->general->allowAttachment AND $attach)
			<div class="row">
				<div class="col-sm-12">
					<div class="well well-sm well-white">
						<span class="glyphicon glyphicon-paperclip"></span>
						{{ Form::file('attachment') }}
					</div>
				</div>
			</div>
		@endif

		<div class="row">
			<div class="col-sm-4">
				@if ($site->general->pasteVisibility != 'public')
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-lock"></span>
							</div>

							{{
								Form::password('password', array(
									'class'         => 'form-control',
									'placeholder'   => Lang::get('global.password'),
									'disabled'      => $disabled
								))
							}}
						</div>
					</div>
				@endif
			</div>

			<div class="col-sm-4">
				@if ($site->general->pasteVisibility == 'default')
					<div class="form-group">
						<div class="checkbox">
							<label>
								{{
									Form::checkbox('private', NULL, NULL, array(
										'id'       => 'private',
										'disabled' => $disabled
									))
								}}

								{{ Lang::get('create.mark_private') }}
							</label>
						</div>
					</div>
				@endif
			</div>

			<div class="col-sm-4">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-btn">
							{{
								Form::submit(Lang::get('global.paste'), array(
									'name'    => '_submit',
									'class'   => 'btn btn-primary'
								))
							}}
						</div>

						{{
							Form::select('expire', Paste::getExpiration(), $site->general->pasteAge, array(
								'class' => 'form-control'
							))
						}}

						@if ($paste->id > 0)
							{{ Form::hidden('id', $paste->id) }}
						@endif
					</div>
				</div>
			</div>
		</div>

		{{ Form::close() }}
	</section>
@stop
