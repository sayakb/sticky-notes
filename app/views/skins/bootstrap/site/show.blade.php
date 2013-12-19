@extends('skins.bootstrap.common.page')

@section('body')
	@include('skins.bootstrap.common.alerts')

	<section id="show">
		@include('skins.bootstrap.site.paste')

		<div class="row">
			<div class="col-sm12">
				<h4>
					<span class="glyphicon glyphicon-comment"></span>
					{{ Lang::get('show.comments') }}
				</h4>

				{{
					Form::open(array(
						'action'  => 'ShowController@postComment',
						'role'    => 'form'
					))
				}}

				<div class="form-group">
					{{
						Form::textarea('comment', NULL, array(
							'class'  => 'form-control',
							'rows'   => 2
						))
					}}
				</div>

				<div class="form-group">
					{{
						Form::submit(Lang::get('global.submit'), array(
							'name'   => '_submit',
							'class'  => 'btn btn-primary'
						))
					}}
				</div>

				{{ Form::hidden('id', $paste->id) }}
				{{ Form::close() }}

				@if (count($paste->comments) > 0)
					@foreach ($paste->comments as $comment)
						<div class="well well-sm well-white">
							<p>
								{{{ $comment->data }}}
							</p>

							<div class="small">
								<div class="pull-right">
									@if ($role->admin OR ($role->user AND $auth->username == $comment->author))
										{{
											link_to("{$paste->urlkey}/{$paste->hash}/delete/{$comment->id}", Lang::get('global.delete'), array(
												'onclick'   => "return confirm('".Lang::get('global.action_confirm')."')",
											))
										}}
									@endif
								</div>

								<div class="text-muted">
									{{{ sprintf(Lang::get('global.posted_by'), $comment->author, date('d M Y, H:i:s e', $comment->timestamp)) }}}
								</div>
							</div>
						</div>
					@endforeach
				@endif
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
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
