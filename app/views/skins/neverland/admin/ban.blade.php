@extends('skins.neverland.admin.layout')

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

			<div class="row-fluid">
				<div class="span12 form-inline">
					{{
						Form::text('ip', NULL, array(
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

			{{ Form::close() }}

			<div class="row-fluid">
				<div class="span12">
					@if ($bans->count() > 0)
						<table class="table table-striped table-striped-dark">
							<colgroup>
								<col class="span11" />
								<col class="span1" />
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

										<td class="align-right">
											{{ link_to('admin/ban/remove/'.urlencode($ban->ip), Lang::get('admin.unban')) }}
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					@else
						<div class="hero-unit align-center">
							{{ HTML::image(View::asset('img/info-sign.png')) }}
							<h2>{{ Lang::get('admin.no_banned_ip') }}</h2>
						</div>
					@endif
				</div>
			</div>
		</fieldset>
	</section>
@stop
