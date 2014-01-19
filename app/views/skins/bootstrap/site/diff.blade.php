@extends("skins.bootstrap.common.{$container}")

<link href="{{ View::asset('css/phpdiff.css') }}" rel="stylesheet" />

@section('body')
	<section id="diff">
		<div class="row">
			<div class="col-sm-12">
				<div class="pre-info pre-header">
					<div class="row">
						<div class="col-sm-6">
							<h4>{{ Lang::get('show.revision_diff') }}</h4>
						</div>

						<div class="col-sm-6 text-right">
							{{
								link_to($newkey, Lang::get('show.return_paste'), array(
									'class' => 'btn btn-success'
								))
							}}
						</div>
					</div>
				</div>

				<div class="well well-sm well-white">
					{{ $diff }}
				</div>
			</div>
		</div>
	</section>
@stop
