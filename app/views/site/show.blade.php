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

									@if ($paste->urlkey)
										#p{{ $paste->urlkey }}
									@else
										#{{ $paste->id }}
									@endif
								@else
									{{{ $paste->title }}}
								@endif
							</h4>
						</div>

						<div class="col-sm-5 text-right">
							@if ($paste->password)
								<span class="btn btn-warning" title="{{ Lang::get('global.paste_pwd') }}">
									<span class="glyphicon glyphicon-lock"></span>
								</span>
							@elseif ($paste->private)
								<span class="btn btn-warning" title="{{ Lang::get('global.paste_pvt') }}">
									<span class="glyphicon glyphicon-eye-open"></span>
								</span>
							@endif

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
								link_to($paste->urlkey ? "p{$paste->urlkey}/{$paste->hash}/raw" : "{$paste->id}/{$paste->hash}/raw", Lang::get('show.raw'), array(
									'class' => 'btn btn-success'
								))
							}}
						</div>
					</div>
				</div>

				{{ Highlighter::make()->parse($paste->data, $paste->language) }}

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
