@extends('skins.bootstrap.setup.page')

@section('body')
	<section id="update">
		<fieldset>
			<legend>{{ Lang::get('setup.u_stage2_title') }}</legend>

			<p>{{ Lang::get('setup.u_stage2_exp') }}</p>

			<div class="row">
				<div class="col-sm-12">
					<div class="progress progress-striped active">
						<div id="bar" class="progress-bar progress-bar-info" role="progressbar" style="width: 0%"></div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-6">
					<i>
						<span id="message">
							{{ Lang::get('setup.initializing') }}
						</span>
					</i>
				</div>

				<div class="col-sm-6 text-right">
					<i>
						<span id="percent">0%</span>
						{{ Lang::get('setup.complete') }}
					</i>
				</div>
			</div>
		</fieldset>

		<script type="text/javascript">
			// Trigger the update process
			setup("{{ url('setup/update') }}", "{{ $version }}");
		</script>
	</section>
@stop
