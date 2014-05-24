@extends("skins.neverland.common.{$container}")

@section('body')
	@include('skins.neverland.common.alerts')

	<section id="show">
		@include('skins.neverland.site.paste')

		@if ($paste->attachment)
			<div class="row-fluid">
				<div class="span12">
					<div class="well well-small well-white">
						<i class="icon-file"></i>
						{{ link_to("attachment/{$paste->urlkey}/{$paste->hash}", $attachment) }}
					</div>
				</div>
			</div>
		@endif

		@if ($revisions->count() > 0)
			<div class="row-fluid">
				<div class="span12">
					<h4>
						<i class="icon-time icon-blue"></i>
						{{ Lang::get('show.version_history') }}
					</h4>

					<fieldset class="well well-small well-white well-history">
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
									@foreach ($revisions as $revision)
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
				</div>
			</div>
		@endif

		@if ($site->general->comments)
			<div class="row-fluid">
				<div class="span12">
					<h4>
						<i class="icon-comment icon-blue"></i>
						{{ Lang::get('global.comments') }}
					</h4>

					{{
						Form::open(array(
							'action'        => 'ShowController@postComment',
							'role'          => 'form',
							'data-navigate' => 'ajax'
						))
					}}

					<div class="control-group">
						{{
							Form::textarea('comment', NULL, array(
								'class'  => 'input-stretch',
								'rows'   => 2
							))
						}}
					</div>

					<div class="control-group">
						{{
							Form::submit(Lang::get('global.submit'), array(
								'name'   => '_submit',
								'class'  => 'btn btn-primary'
							))
						}}
					</div>

					{{ Form::hidden('id', $paste->id) }}
					{{ Form::close() }}

					@if ($comments->count() > 0)
						@foreach ($comments as $comment)
							<div class="well well-small well-white">
								<p>
									{{ $comment->data }}
								</p>

								<small>
									<div class="pull-right">
										@if ($role->admin OR ($role->user AND $auth->username == $comment->author))
											{{
												link_to("{$paste->urlkey}/{$paste->hash}/delete/{$comment->id}", Lang::get('global.delete'), array(
													'onclick'   => "return confirm('".Lang::get('global.action_confirm')."')",
												))
											}}
										@endif
									</div>

									<div class="muted">
										{{{ $comment->author }}}
										&bull;
										{{{ date('d M Y, H:i:s e', $comment->timestamp) }}}
									</div>
								</small>
							</div>
						@endforeach

						{{ $comments->links() }}
					@endif
				</div>
			</div>
		@endif
	</section>
@stop
