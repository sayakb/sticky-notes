@extends('skins.bootstrap.common.page')

@section('body')
	@include('skins.bootstrap.common.alerts')

	<section id="show">
		<div class="row">
			<div class="col-sm-12">
				<div class="pre-info pre-header">
					<div class="row">
						<div class="col-sm-5">
							<h4>
								@if (empty($paste->title))
									{{ Lang::get('global.paste') }}
									#{{ $paste->urlkey }}
								@else
									{{{ $paste->title }}}
								@endif
							</h4>
						</div>

						<div class="col-sm-7 text-right">
							@if ( ! empty($site->general->googleApi))
								{{
									link_to("#", Lang::get('show.short_url'), array(
										'class'          => 'btn btn-success',
										'data-toggle'    => 'ajax',
										'data-component' => 'shorten',
										'data-extra'     => $paste->urlkey.($paste->private ? '/'.$paste->hash : ''),
									))
								}}
							@endif

							{{
								link_to("#", Lang::get('show.wrap'), array(
									'class'        => 'btn btn-success',
									'data-toggle'  => 'wrap',
								))
							}}

							{{
								link_to("{$paste->urlkey}/{$paste->hash}/raw", Lang::get('show.raw'), array(
									'class' => 'btn btn-success'
								))
							}}

							{{
								link_to("rev/{$paste->urlkey}", Lang::get('show.revise'), array(
									'class' => 'btn btn-success'
								))
							}}

							@include('skins.bootstrap.site.actions')
						</div>
					</div>
				</div>

				<div class="well well-sm well-white pre">
					{{ Highlighter::make()->parse($paste->id.'show', $paste->data, $paste->language) }}
				</div>

				<div class="pre-info pre-footer">
					<div class="row">
						<div class="col-sm-6">
							{{ sprintf(Lang::get('global.language'), $paste->language) }}
						</div>

						<div class="col-sm-6 text-right">
							{{{ sprintf(Lang::get('global.posted_by'), $paste->author ?: Lang::get('global.anonymous'), date('d M Y, H:i:s e', $paste->timestamp)) }}}
						</div>
					</div>
				</div>

				@if (count($paste->revisions) > 0)
					<fieldset class="well well-sm well-white well-history">
						<h4>
							<span class="glyphicon glyphicon-time"></span>
							{{ Lang::get('show.version_history') }}
						</h4>

						<div class="viewport">
							<table class="table table-striped table-responsive">
								<colgroup>
									<col class="col-xs-3" />
									<col class="col-xs-3" />
									<col class="col-xs-5" />
									<col class="col-xs-1" />
								</colgroup>

								<thead>
									<tr>
										<th>{{ Lang::get('show.revision_id') }}</th>
										<th>{{ Lang::get('global.author') }}</th>
										<th>{{ Lang::get('show.created_at') }}</th>
										<th></th>
									</tr>
								</thead>

								<tbody>
									@foreach ($paste->revisions as $revision)
										<tr>
											<td>
												{{
													link_to($revision->urlkey, $revision->urlkey)
												}}
											</td>

											<td>
												{{{
													$paste->author ?: Lang::get('global.anonymous')
												}}}
											</td>

											<td>
												{{
													date('d M Y, H:i:s e', $revision->timestamp)
												}}
											</td>

											<td class="text-right">
												{{
													link_to("diff/{$revision->urlkey}/{$paste->urlkey}", Lang::get('show.diff'), array(
														'class' => 'btn btn-xs btn-default'
													))
												}}
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</fieldset>
				@endif
			</div>
		</div>
	</section>
@stop
