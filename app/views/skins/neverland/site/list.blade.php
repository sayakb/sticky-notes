@extends('skins.neverland.common.page')

@section('body')
	<section id="list">
		@if ($filters)
			<div class="row-fluid">
				<div class="span12">
					<div class="well well-small well-white">
						<ul class="nav nav-pills">
							<li class="nav-text">
								<i class="icon-filter"></i>
								{{ Lang::get('list.filter') }}:
							</li>

							{{ View::menu('filters') }}
						</ul>
					</div>
				</div>
			</div>
		@endif

		@foreach ($pastes as $paste)
			<div class="row-fluid">
				<div class="span12">
					<div class="pre-info pre-header">
						<div class="row-fluid">
							<div class="span7">
								<h4>
									@if (empty($paste->title))
										{{ Lang::get('global.paste') }}
										#{{ $paste->urlkey }}
									@else
										{{{ $paste->title }}}
									@endif
								</h4>
							</div>

							<div class="span5 align-right">
								{{
									link_to($paste->urlkey, Lang::get('list.show_paste'), array(
										'class' => 'btn btn-success'
									))
								}}

								@include('skins.neverland.site.actions')
							</div>
						</div>
					</div>

					<div class="well well-small well-white pre">
						{{ Highlighter::make()->parse($paste->id.'list', Paste::getAbstract($paste->data), $paste->language) }}
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
				</div>
			</div>
		@endforeach

		{{ $pages }}
	</section>
@stop
