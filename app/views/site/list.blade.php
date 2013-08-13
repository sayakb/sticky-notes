@extends('common.page')

@section('content')
	<section class="list">
		@foreach ($pastes as $paste)
			<div class="row">
				<div class="col-lg-12">
					<h3>
						{{
							link_to("show/{$paste->urlkey}", Lang::get('list.show_paste'), array(
								'class' => 'btn btn-link btn-small pull-right'
							))
						}}

						@if (empty($paste->title))
							{{ Lang::get('global.paste') }}
							#{{ $paste->urlkey }}
						@else
							{{{ $paste->title }}}
						@endif
					</h3>

					{{ Highlighter::parse(Paste::getAbstract($paste->data), $paste->language) }}
				</div>
			</div>
		@endforeach

		{{ $pages }}
	</section>
@stop
