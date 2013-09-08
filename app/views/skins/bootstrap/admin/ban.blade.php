@extends('skins.bootstrap.admin.layout')

@section('module')
	<section id="admin-ban">
		<fieldset>
			<legend>{{ Lang::get('admin.ban_an_ip') }}</legend>

			{{
				Form::open(array(
					'autocomplete'   => 'off',
					'role'           => 'form'
				))
			}}

			<div class="row">
				<div class="col-sm-12 form-inline">
					{{
						Form::text('ip', NULL, array(
							'class'         => 'form-control',
							'placeholder'   => Lang::get('admin.ip_address')
						))
					}}

					{{
						Form::submit(Lang::get('admin.ban'), array(
							'class'   => 'btn btn-primary'
						))
					}}
				</div>
			</div>
			<br />

			{{ Form::close() }}

			<div class="row">
				<div class="col-sm-12">
					@if ($bans->count() > 0)
						<table class="table table-striped table-striped-dark">
							<colgroup>
								<col class="col-sm-11" />
								<col class="col-sm-1" />
							</colgroup>

							<thead>
								<tr>
									<th>{{ Lang::get('admin.ip_address') }}</th>
									<th></th>
								</tr>
							</thead>

							<tbody>
								@foreach ($bans as $ban)
									<tr>
										<td>{{ $ban->ip }}</td>

										<td class="text-center">
											{{ link_to('admin/ban/remove/'.urlencode($ban->ip), Lang::get('admin.unban')) }}
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					@else
						<div class="jumbotron text-center">
							<h1><span class="glyphicon glyphicon-info-sign text-info"></span></h1>
							<h2>{{ Lang::get('admin.no_banned_ip') }}</h2>
						</div>
					@endif
				</div>
			</div>
		</fieldset>
	</section>
@stop
