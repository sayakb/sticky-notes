@extends('skins.bootstrap.common.page')

@section('body')
	<section id="list">
		@if ($filters)
			<div class="row">
				<div class="col-sm-12">
					<div class="well well-sm well-white">
						<ul class="nav nav-pills">
							<li class="disabled">
								<a>
									<span class="glyphicon glyphicon-filter"></span>
									{{ Lang::get('list.filter') }}:
								</a>
							</li>

							{{ View::menu('filters') }}
						</ul>
					</div>
				</div>
			</div>
		@endif

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
									link_to($paste->urlkey, Lang::get('list.show_paste'), array(
										'class' => 'btn btn-success'
									))
								}}

								@include('skins.bootstrap.site.actions')
							</div>
						</div>
					</div>

					<div class="well well-sm well-white pre">
						{{ Highlighter::make()->parse(Paste::getAbstract($paste->data), $paste->language) }}
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
				</div>
			</div>
		@endforeach

		{{ $pages }}
	</section>
@stop
