@extends("skins.neverland.common.{$container}")

<link href="{{ View::asset('css/phpdiff.css') }}" rel="stylesheet" />

@section('body')
	<section id="diff">
		<div class="row-fluid">
			<div class="span12">
				<div class="pre-info pre-header">
					<div class="row-fluid">
						<div class="span6">
							<h4>{{ Lang::get('show.revision_diff') }}</h4>
						</div>

						<div class="span6 align-right">
							{{
								link_to($newkey, Lang::get('show.return_paste'), array(
									'class' => 'btn btn-success'
								))
							}}
						</div>
					</div>
				</div>

				<div class="well well-small well-white">
					{{ $diff }}
				</div>
			</div>
		</div>
	</section>
@stop
