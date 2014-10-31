@extends("skins.bootstrap.common.{$container}")

@section('body')
	@include('skins.bootstrap.common.alerts')

	<section id="list">
		@if ($search)
			<div class="row">
				<div class="col-sm-4 col-md-3 col-sm-offset-8 col-md-offset-9">
					{{
						Form::open(array(
							'action' => 'ListController@postSearch',
							'role'   => 'form'
						))
					}}

					<div class="form-group">
						{{
							Form::text('search', Input::get('q'), array(
								'class'         => 'form-control',
								'placeholder'   => Lang::get('list.search'),
								'maxlength'     => 500
							))
						}}
					</div>

					{{ Form::close() }}
				</div>
			</div>
		@endif

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
			@include('skins.bootstrap.site.paste')
		@endforeach

		{{ $pages }}
	</section>
@stop
