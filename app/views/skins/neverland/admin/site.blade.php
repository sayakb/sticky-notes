@extends('skins.neverland.admin.layout')

@section('module')
	<section id="admin-site">
		{{
			Form::open(array(
				'autocomplete'   => 'off',
				'role'           => 'form',
				'class'          => 'form-horizontal',
			))
		}}

		<div class="row-fluid">
			<div class="span12">
				<fieldset>
					<legend>{{ Lang::get('admin.site_settings') }}</legend>

					<div class="control-group">
						{{
							Form::label('fqdn', Lang::get('admin.fqdn'), array(
								'class' => 'control-label span2'
							))
						}}

						<div class="span9">
							{{
								Form::text('fqdn', $site->general->fqdn, array(
									'class' => 'input-xxlarge',
								))
							}}

							<div class="help-block">
								{{ Lang::get('admin.fqdn_exp') }}
							</div>
						</div>
					</div>

					<div class="control-group">
						{{
							Form::label('title', Lang::get('admin.site_title'), array(
								'class' => 'control-label span2'
							))
						}}

						<div class="span9">
							{{
								Form::text('title', $site->general->title, array(
									'class'      => 'input-xxlarge',
									'maxlength'  => 20
								))
							}}
						</div>
					</div>

					<div class="control-group">
						{{
							Form::label('copyright', Lang::get('admin.copyright'), array(
								'class' => 'control-label span2'
							))
						}}

						<div class="span9">
							{{
								Form::textarea('copyright', $site->general->copyright, array(
									'class' => 'input-xxlarge',
									'rows'  => 4,
								))
							}}
						</div>
					</div>

					<div class="control-group">
						{{
							Form::label('lang', Lang::get('admin.language'), array(
								'class' => 'control-label span2'
							))
						}}

						<div class="span9">
							{{
								Form::select('lang', $langs, $site->general->lang, array(
									'class' => 'input-xxlarge'
								))
							}}
						</div>
					</div>

					<div class="control-group">
						{{
							Form::label('paste_age', Lang::get('admin.paste_age'), array(
								'class' => 'control-label span2'
							))
						}}

						<div class="span9">
							{{
								Form::select('paste_age', Paste::getExpiration('admin'), $site->general->pasteAge, array(
									'class' => 'input-xxlarge'
								))
							}}

							<div class="help-block">
								{{ Lang::get('admin.paste_age_exp') }}
							</div>
						</div>
					</div>

					<div class="control-group">
						{{
							Form::label('per_page', Lang::get('admin.list_length'), array(
								'class' => 'control-label span2'
							))
						}}

						<div class="span9">
							{{
								Form::text('per_page', $site->general->perPage, array(
									'class' => 'input-xxlarge',
								))
							}}

							<div class="help-block">
								{{ Lang::get('admin.list_length_exp') }}
							</div>
						</div>
					</div>

					<div class="control-group">
						{{
							Form::label('google_api', Lang::get('admin.google_api_key'), array(
								'class' => 'control-label span2'
							))
						}}

						<div class="span9">
							{{
								Form::text('google_api', $site->general->googleApi, array(
									'class' => 'input-xxlarge',
								))
							}}

							<div class="help-block">
								{{ Lang::get('admin.google_api_exp') }}
								{{ link_to('https://code.google.com/apis/console', Lang::get('admin.google_api_console')) }}.
							</div>
						</div>
					</div>

					<div class="control-group">
						{{
							Form::label('proxy', Lang::get('admin.ip_tracking'), array(
								'class' => 'control-label span2'
							))
						}}

						<div class="span9">
							{{
								Form::select('proxy', array(
									'0' => Lang::get('admin.ignore_proxy'),
									'1' => Lang::get('admin.trust_proxy'),
								), $site->general->proxy, array(
									'class' => 'input-xxlarge'
								))
							}}

							<div class="help-block">
								{{ Lang::get('admin.ip_tracking_exp') }}
							</div>
						</div>
					</div>

					<div class="control-group">
						{{
							Form::label('private_site', Lang::get('admin.private_site'), array(
								'class' => 'control-label span2'
							))
						}}

						<div class="span9">
							{{
								Form::select('private_site', array(
									'0' => Lang::get('admin.allow_public'),
									'1' => Lang::get('admin.enforce_private'),
								), $site->general->privateSite, array(
									'class' => 'input-xxlarge'
								))
							}}

							<div class="help-block">
								{{ Lang::get('admin.private_site_exp') }}
							</div>
						</div>
					</div>

					<div class="form-actions">
						{{
							Form::submit(Lang::get('global.save'), array(
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
