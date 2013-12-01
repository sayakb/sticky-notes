@extends('skins.neverland.admin.layout')

@section('module')
	<section id="admin-services">
		{{
			Form::open(array(
				'autocomplete'   => 'off',
				'role'           => 'form',
			))
		}}

		<div class="row-fluid">
			<div class="span12">
				<fieldset>
					<legend>{{ Lang::get('admin.services') }}</legend>

					<ul id="tabs-services" class="nav nav-tabs">
						<li class="active">
							<a href="#services-google" data-toggle="tab">{{ Lang::get('admin.google') }}</a>
						</li>
					</ul>

					<div class="tab-content form-horizontal">
						<div id="services-google" class="tab-pane fade in active">
							<div class="control-group">
								{{
									Form::label('google_api_key', Lang::get('admin.google_api_key'), array(
										'class' => 'control-label span2'
									))
								}}

								<div class="span9">
									{{
										Form::text('google_api_key', $site->services->googleApiKey, array(
											'class' => 'input-xxlarge',
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.google_api_key_exp') }}
										{{ link_to('https://cloud.google.com/console', Lang::get('admin.google_cloud_console')) }}.
									</div>
								</div>
							</div>

							<div class="control-group">
								{{
									Form::label('google_analytics_id', Lang::get('admin.google_analytics'), array(
										'class' => 'control-label span2'
									))
								}}

								<div class="span9">
									{{
										Form::text('google_analytics_id', $site->services->googleAnalyticsId, array(
											'class' => 'input-xxlarge',
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.google_analytics_exp') }}
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="form-actions">
						{{
							Form::submit(Lang::get('admin.save_all'), array(
								'name'    => '_save',
								'class'   => 'btn btn-primary'
							))
						}}
					</div>
				</fieldset>
			</div>
		</div>

		{{ Form::close() }}
	</section>
@stop
