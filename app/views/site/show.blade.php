@extends('common.page')

@section('body')
	<section id="show">
		<div class="row">
			<div class="col-sm-12">
				<div class="pre-info pre-header">
					<div class="row">
						<div class="col-sm-7">
							<h4>
								@if (empty($paste->title))
									{{ Lang::get('global.paste') }}
									#{{ $paste->urlkey }}
								@else
									{{{ $paste->title }}}
								@endif
							</h4>
						</div>

						<div class="col-sm-5 text-right">
							{{
								link_to("#", Lang::get('show.short_url'), array(
									'class' => 'btn btn-success'
								))
							}}

							{{
								link_to("#", Lang::get('show.wrap'), array(
									'class' => 'btn btn-success'
								))
							}}

							{{
								link_to("show/{$paste->urlkey}/{$paste->hash}/raw", Lang::get('show.raw'), array(
									'class' => 'btn btn-success'
								))
							}}
						</div>
					</div>
				</div>

				{{ Highlighter::parse($paste->data, $paste->language) }}

				<div class="pre-info pre-footer">
					<div class="row">
						<div class="col-sm-6">
							{{ sprintf(Lang::get('global.language'), $paste->language) }}
						</div>

						<div class="col-sm-6 text-right">
							{{ sprintf(Lang::get('global.posted_by'), $paste->author ?: Lang::get('global.anonymous'), date('d M Y, H:i:s e', $paste->timestamp)) }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@stop
