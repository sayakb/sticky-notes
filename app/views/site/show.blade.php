@extends('common.page')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<fieldset>
				<legend>
					@if (empty($paste->title))
						{{ Lang::get('global.paste') }}
						#{{ $paste->urlkey }}
					@else
						{{{ $paste->title }}}
					@endif
				</legend>

				{{ Highlighter::parse($paste->data, $paste->language) }}
			</fieldset>
		</div>
	</div>
@stop
