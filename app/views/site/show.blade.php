@extends('common.page')

@section('content')
	<section class="show">
		<div class="row">
			<div class="col-lg-12">
				<h3>
					@if (empty($paste->title))
						{{ Lang::get('global.paste') }}
						#{{ $paste->urlkey }}
					@else
						{{{ $paste->title }}}
					@endif
				</h3>

				{{ Highlighter::parse($paste->data, $paste->language) }}
			</div>
		</div>
	</section>
@stop
