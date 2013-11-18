@extends('skins.neverland.common.page')

@section('body')
	@include('skins.neverland.common.alerts')

	<section id="show">
		<div class="row-fluid">
			<div class="span12">
				<div class="pre-info pre-header">
					<div class="row-fluid">
						<div class="span5">
							<h4>
								@if (empty($paste->title))
									{{ Lang::get('global.paste') }}
									#{{ $paste->urlkey }}
								@else
									{{{ $paste->title }}}
								@endif
							</h4>
						</div>

						<div class="span7 align-right">
							@if ( ! empty($site->services->googleApiKey))
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

							@include('skins.neverland.site.actions')
						</div>
					</div>
				</div>

				<div class="well well-sm well-white pre">
					{{ Highlighter::make()->parse($paste->id.'show', $paste->data, $paste->language) }}
				</div>

				<div class="pre-info pre-footer">
					<div class="row-fluid">
						<div class="span6">
							{{{ sprintf(Lang::get('global.posted_by'), $paste->author ?: Lang::get('global.anonymous'), date('d M Y, H:i:s e', $paste->timestamp)) }}}
						</div>

						<div class="span6 align-right">
							{{ sprintf(Lang::get('global.language'), $paste->language) }}
							&bull;
							{{ sprintf(Lang::get('global.views'), $paste->hits) }}
						</div>
					</div>
				</div>

				@if (count($paste->revisions) > 0)
					<fieldset class="well well-small well-white well-history">
						<h4>
							<i class="icon-time"></i>
							{{ Lang::get('show.version_history') }}
						</h4>

						<div class="viewport">
							<table class="table table-striped table-responsive">
								<colgroup>
									<col class="span3" />
									<col class="span3" />
									<col class="span5" />
									<col class="span1" />
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

											<td class="align-right">
												{{
													link_to("diff/{$revision->urlkey}/{$paste->urlkey}", Lang::get('show.diff'), array(
														'class' => 'btn btn-mini btn-default'
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
