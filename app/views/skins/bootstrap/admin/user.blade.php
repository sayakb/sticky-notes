@extends('skins.bootstrap.admin.layout')

@section('module')
	<section id="admin-user">
		<fieldset>
			<legend>{{ Lang::get('admin.manage_users') }}</legend>

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

			{{ Form::close() }}

			<div class="row">
				<div class="col-sm-12">
					@if ( ! empty($user))
						{{
							Form::open(array(
								'autocomplete'   => 'off',
								'role'           => 'form',
								'class'          => 'form-horizontal',
							))
						}}

						<fieldset>
							<legend>{{ Lang::get('admin.user_editor') }}</legend>

							<div class="form-group">
								{{
									Form::label('username', Lang::get('global.username'), array(
										'class' => 'control-label col-sm-3 col-lg-2'
									))
								}}

								<div class="col-sm-9 col-lg-10">
									{{
										Form::text('username', $user->username, array(
											'class'         => 'form-control',
											'maxlength'     => 50,
										))
									}}
								</div>
							</div>

							<div class="form-group">
								{{
									Form::label('email', Lang::get('global.email'), array(
										'class' => 'control-label col-sm-3 col-lg-2'
									))
								}}

								<div class="col-sm-9 col-lg-10">
									{{
										Form::text('email', $user->email, array(
											'class'         => 'form-control',
											'maxlength'     => 100,
										))
									}}
								</div>
							</div>

							<div class="form-group">
								{{
									Form::label('dispname', Lang::get('global.full_name'), array(
										'class' => 'control-label col-sm-3 col-lg-2'
									))
								}}

								<div class="col-sm-9 col-lg-10">
									{{
										Form::text('dispname', $user->dispname, array(
											'class'         => 'form-control',
											'maxlength'     => 100,
										))
									}}
								</div>
							</div>

							<div class="form-group">
								{{
									Form::label('password', Lang::get('global.password'), array(
										'class' => 'control-label col-sm-3 col-lg-2'
									))
								}}

								<div class="col-sm-9 col-lg-10">
									{{
										Form::password('password', array(
											'class' => 'form-control',
										))
									}}
								</div>
							</div>

							@if ( ! $founder)
								<div class="form-group">
									{{
										Form::label('active', Lang::get('admin.status'), array(
											'class' => 'control-label col-sm-3 col-lg-2'
										))
									}}

									<div class="col-sm-9 col-lg-10">
										<div class="checkbox">
											<label>
												{{
													Form::checkbox('active', 1, $user->active, array(
														'id' => 'active',
													))
												}}

												{{ Lang::get('admin.active') }}
											</label>
										</div>
									</div>
								</div>

								<div class="form-group">
									{{
										Form::label('admin', Lang::get('admin.role'), array(
											'class' => 'control-label col-sm-3 col-lg-2'
										))
									}}

									<div class="col-sm-9 col-lg-10">
										<div class="checkbox">
											<label>
												{{
													Form::checkbox('admin', 1, $user->admin, array(
														'id' => 'admin',
													))
												}}

												{{ Lang::get('global.admin') }}
											</label>
										</div>
									</div>
								</div>
							@endif

							<div class="form-group">
								<div class="col-sm-offset-3 col-lg-offset-2 col-sm-9 col-lg-10">
									{{ Form::hidden('id', $user->id) }}

									{{
										Form::submit(Lang::get('global.submit'), array(
											'name'    => '_save',
											'class'   => 'btn btn-primary'
										))
									}}

									{{
										link_to('admin/user/delete/'.urlencode($user->username), Lang::get('global.delete'), array(
											'onclick' => 'return confirm("'.Lang::get('global.action_confirm').'")',
											'class'   => 'btn btn-danger'
										));
									}}
								</div>
							</div>
						</fieldset>

						{{ Form::close() }}
					@else
						@if ($site->auth->method != 'db')
							<div class="alert alert-info">
								{{ sprintf(Lang::get('admin.user_auth_method'), Lang::get("admin.{$site->auth->method}")) }}
							</div>
						@endif

						<table class="table table-striped table-striped-dark table-user">
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
											{{
												HTML::image('//www.gravatar.com/avatar/'.md5(strtolower($user->email)).'?s=28', NULL, array(
													'class'   => 'img-circle',
													'onerror' => 'this.style.display="none"',
												))
											}}
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

			{{ Form::close() }}
		</fieldset>
	</section>
@stop
