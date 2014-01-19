@extends("skins.neverland.common.{$container}")

@section('body')
	@include('skins.neverland.common.alerts')

	<section id="list">
		@if ($search)
			<div class="row">
				<div class="span12 align-right">
					{{
						Form::open(array(
							'action' => 'ListController@postSearch',
							'class' => 'no-margin'
						))
					}}

					{{
						Form::text('search', Input::get('q'), array(
							'class'         => 'input-xlarge',
							'placeholder'   => Lang::get('list.search'),
							'maxlength'     => 500
						))
					}}

					{{ Form::close() }}
				</div>
			</div>
		@endif

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
			@include('skins.neverland.site.paste')
		@endforeach

		{{ $pages }}
	</section>
@stop
