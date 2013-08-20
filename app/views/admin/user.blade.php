@extends('admin.layout')

@section('module')
	<section id="admin-user">
		{{
			Form::open(array(
				'autocomplete'   => 'off',
				'role'           => 'form'
			))
		}}

		<div class="row">
			<div class="col-sm-8 form-inline">
				{{
					Form::text('search', NULL, array(
						'class'         => 'form-control',
						'maxlength'     => 50,
						'placeholder'   => Lang::get('global.username')
					))
				}}

				{{
					Form::submit(Lang::get('global.search'), array(
						'class'   => 'btn btn-primary'
					))
				}}
			</div>

			<div class="col-sm-4 text-right">
				{{
					link_to('admin/user/create', Lang::get('admin.user_create'), array(
						'class'  => 'btn btn-success'
					))
				}}
			</div>
		</div>
		<br />

		{{ Form::token() }}
		{{ Form::close() }}

		<div class="row">
			<div class="col-sm-12">
				@if ( ! empty($user))
					{{
						Form::model($user, array(
							'autocomplete'   => 'off'
						))
					}}

					<fieldset>
						<legend>{{ Lang::get('admin.user_editor') }}</legend>

						<div class="row">
							<div class="col-sm-8 col-md-7 col-lg-6">
								<div class="form-group">
									{{ Form::label('username', Lang::get('global.username')) }}

									{{
										Form::text('username', NULL, array(
											'class'         => 'form-control',
											'maxlength'     => 50,
										))
									}}
								</div>

								<div class="form-group">
									{{ Form::label('email', Lang::get('global.email')) }}

									{{
										Form::text('email', NULL, array(
											'class'         => 'form-control',
											'maxlength'     => 100,
										))
									}}
								</div>

								<div class="form-group">
									{{ Form::label('dispname', Lang::get('global.full_name')) }}

									{{
										Form::text('dispname', NULL, array(
											'class'         => 'form-control',
											'maxlength'     => 100,
										))
									}}
								</div>

								<div class="form-group">
									{{ Form::label('password', Lang::get('global.password')) }}

									{{
										Form::password('password', array(
											'class'         => 'form-control',
										))
									}}
								</div>

								<div class="checkbox">
									<label>
										{{
											Form::checkbox('admin', NULL, NULL, array(
												'id'         => 'admin',
												'disabled'   => $user->id == 1 ? 'disabled' : NULL
											))
										}}

										{{ Lang::get('global.admin') }}
									</label>
								</div>

								{{ Form::hidden('id') }}

								{{
									Form::submit(Lang::get('global.submit'), array(
										'name'    => 'save',
										'class'   => 'btn btn-primary'
									))
								}}
							</div>
						</div>
					</fieldset>

					{{ Form::token() }}
					{{ Form::close() }}
				@else
					<table class="table table-white table-user">
						<colgroup>
							<col class="col-sm-1" />
							<col class="col-sm-4" />
							<col class="col-sm-4" />
							<col class="col-sm-1" />
						</colgroup>

						<thead>
							<tr>
								<th></th>
								<th>{{ Lang::get('global.username') }}</th>
								<th>{{ Lang::get('global.email') }}</th>
								<th></th>
							</tr>
						</thead>

						<tbody>
							@foreach ($users as $user)
								<tr>
									<td class="text-right">
										<div class="thumbnail pull-right">
											{{ HTML::image('//www.gravatar.com/avatar/'.md5(strtolower($user->email)).'?s=28') }}
										</div>
									</td>

									<td>{{ $user->username }}</td>
									<td>{{{ $user->email }}}</td>

									<td class="text-center">
										{{ link_to('admin/user/edit/'.urlencode($user->username), Lang::get('global.edit')) }}
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>

					{{ $pages }}
				@endif
			</div>
		</div>

		{{ Form::token() }}
		{{ Form::close() }}
	</section>
@stop
