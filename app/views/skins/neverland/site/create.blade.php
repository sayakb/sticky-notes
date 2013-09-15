@extends('skins.neverland.common.page')

@section('body')
	@include('skins.neverland.common.alerts')

	<section id="create">
		{{
			Form::open(array(
				'action'         => $action,
				'autocomplete'   => 'off',
				'role'           => 'form'
			))
		}}

		<div class="row-fluid">
			<div class="span6">
				{{
					Form::text('title', $paste->title, array(
						'class'         => 'input-xlarge',
						'maxlength'     => 30,
						'placeholder'   => Lang::get('global.paste_title'),
						$disabled       => $disabled
					))
				}}
			</div>

			<div class="span6 align-right">
				{{
					Form::select('language', $languages, $paste->language ?: $language, array(
						'class'    => 'input-xlarge',
						$disabled  => $disabled
					))
				}}
			</div>
		</div>

		<div class="row-fluid">
			<div class="span12">
				{{
					Form::textarea('data', $paste->data, array(
						'class'         => 'input-stretch',
						'rows'          => 18,
						'placeholder'   => Lang::get('global.paste_data')
					))
				}}
			</div>
		</div>

		<div class="row-fluid form-inline">
			<div class="span6">
				<div class="control-group">
					<div class="input-prepend">
						<span class="add-on">
							<i class="icon-lock"></i>
						</span>

						{{
							Form::password('password', array(
								'class'         => 'input-xlarge -right',
								'placeholder'   => Lang::get('global.password'),
								$disabled       => $disabled
							))
						}}
					</div>

					<label class="checkbox">
						{{
							Form::checkbox('private', NULL, NULL, array(
								'id'       => 'private',
								$disabled  => $disabled
							))
						}}

						{{ Lang::get('create.mark_private') }}
					</label>
				</div>
			</div>

			<div class="span6 align-right">
				<div class="input-prepend">
					{{
						Form::submit(Lang::get('global.paste'), array(
							'name'    => '_submit',
							'class'   => 'btn btn-primary'
						))
					}}

					{{
						Form::select('expire', Paste::getExpiration(), $site->general->pasteAge, array(
							'class' => 'input-xlarge'
						))
					}}
				</div>

				@if ($paste->id > 0)
					{{ Form::hidden('id', $paste->id) }}
				@endif
			</div>
		</div>

		{{ Form::close() }}
	</section>
@stop
