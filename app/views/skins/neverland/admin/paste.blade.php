@extends('skins.neverland.admin.layout')

@section('module')
	<section id="admin-paste">
		<fieldset>
			<legend>{{ Lang::get('admin.manage_pastes') }}</legend>

			{{
				Form::open(array(
					'autocomplete'   => 'off',
					'role'           => 'form'
				))
			}}

			<div class="row-fluid">
				<div class="span12 form-inline">
					{{
						Form::text('search', NULL, array(
							'maxlength'     => 30,
							'placeholder'   => Lang::get('global.paste_id')
						))
					}}

					{{
						Form::submit(Lang::get('global.search'), array(
							'class'   => 'btn btn-primary'
						))
					}}
				</div>
			</div>
			<br />

			<div class="row-fluid">
				<div class="span12 form-inline">
					@if ( ! empty($paste))
						<table class="table table-striped table-striped-dark">
							<colgroup>
								<col class="span2" />
								<col class="span10" />
							</colgroup>

							<thead>
								<tr>
									<th>{{ Lang::get('admin.field') }}</th>
									<th>{{ Lang::get('admin.value') }}</th>
								</tr>
							</thead>

							<tbody>
								<tr>
									<td>{{ Lang::get('global.paste_id') }}</td>
									<td>#{{ $paste->urlkey }}</td>
								</tr>

								<tr>
									<td>{{ Lang::get('global.paste_title') }}</td>
									<td>{{{ $paste->title ?: '-' }}}</td>
								</tr>

								<tr>
									<td>{{ Lang::get('global.paste_data') }}</td>
									<td>{{{ Paste::getAbstract($paste->data) }}}</td>
								</tr>

								<tr>
									<td>{{ Lang::get('global.author') }}</td>
									<td>{{{ $paste->author ?: Lang::get('global.anonymous') }}}</td>
								</tr>

								<tr>
									<td>{{ Lang::get('global.paste_lang') }}</td>
									<td>{{ $paste->language }}</td>
								</tr>

								<tr>
									<td>{{ Lang::get('admin.posted_at') }}</td>
									<td>{{ date('d M Y, H:i:s e', $paste->timestamp) }}</td>
								</tr>

								<tr>
									<td>{{ Lang::get('admin.expires_at') }}</td>
									<td>{{ $paste->expire > 0 ? date('d M Y, H:i:s e', $paste->expire) : '-' }}</td>
								</tr>

								<tr>
									<td>{{ Lang::get('admin.is_private') }}</td>
									<td>{{ $paste->private ? Lang::get('global.yes') : Lang::get('global.no') }}</td>
								</tr>

								<tr>
									<td>{{ Lang::get('admin.has_password') }}</td>
									<td>{{ $paste->password ? Lang::get('global.yes') : Lang::get('global.no') }}</td>
								</tr>

								<tr>
									<td>{{ Lang::get('admin.poster_ip') }}</td>
									<td>{{ $paste->ip }}</td>
								</tr>
							</tbody>
						</table>

						<div class="form-actions">
							@if ($paste->password)
								{{
									link_to("admin/paste/{$paste->urlkey}/rempass", Lang::get('admin.remove_password'), array(
										'class' => 'btn btn-primary'
									))
								}}
							@endif

							{{
								link_to("admin/paste/{$paste->urlkey}/toggle",
									$paste->private ? Lang::get('global.make_public') : Lang::get('global.make_private'),
									array(
										'class' => 'btn btn-primary'
									)
								)
							}}

							@if ($paste->attachment)
								{{
									link_to("admin/paste/{$paste->urlkey}/remattach", Lang::get('admin.remove_attachment'), array(
										'class' => 'btn btn-primary',
										'onclick'   => "return confirm('".Lang::get('global.action_confirm')."')",
									))
								}}
							@endif

							{{
								link_to("admin/paste/{$paste->urlkey}/delete", Lang::get('global.delete'), array(
									'class'     => 'btn btn-primary',
									'onclick'   => "return confirm('".Lang::get('global.action_confirm')."')",
								))
							}}
						</div>
					@else
						<div class="hero-unit align-center">
							{{ HTML::image(View::asset('img/search.png')) }}
							<h2>{{ Lang::get('admin.paste_exp') }}</h2>
						</div>
					@endif
				</div>
			</div>

			{{ Form::close() }}
		</fieldset>
	</section>
@stop
