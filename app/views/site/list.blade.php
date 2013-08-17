@extends('common.page')

@section('content')
	<section class="list">
		@foreach ($pastes as $paste)
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
									link_to("show/{$paste->urlkey}", Lang::get('list.show_paste'), array(
										'class' => 'btn btn-success'
									))
								}}
							</div>
						</div>
					</div>

					{{ Highlighter::parse(Paste::getAbstract($paste->data), $paste->language) }}

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
		@endforeach

		{{ $pages }}
	</section>
@stop
