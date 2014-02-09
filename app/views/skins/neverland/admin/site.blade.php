@extends('skins.neverland.admin.layout')

@section('module')
	<section id="admin-site">
		{{
			Form::open(array(
				'autocomplete'   => 'off',
				'role'           => 'form',
			))
		}}

		<div class="row-fluid">
			<div class="span12">
				<fieldset>
					<legend>{{ Lang::get('admin.site_settings') }}</legend>

					<ul id="tabs-site" class="nav nav-tabs">
						<li class="active">
							<a href="#site-general" data-toggle="tab">{{ Lang::get('admin.general') }}</a>
						</li>

						<li>
							<a href="#site-content" data-toggle="tab">{{ Lang::get('admin.content') }}</a>
						</li>
					</ul>

					<div class="tab-content form-horizontal">
						<div id="site-general" class="tab-pane fade in active">
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

									<div class="help-block">
										{{ Lang::get('admin.copyright_exp') }}
									</div>
								</div>
							</div>

							<div class="control-group">
								{{
									Form::label('ajax_nav', Lang::get('admin.ajax_nav'), array(
										'class' => 'control-label span2'
									))
								}}

								<div class="span9">
									{{
										Form::select('ajax_nav', array(
											'1' => Lang::get('admin.enabled'),
											'0' => Lang::get('admin.disabled'),
										), $site->general->ajaxNav, array(
											'class' => 'input-xxlarge'
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.ajax_nav_exp') }}
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
									Form::label('csrf', Lang::get('admin.csrf_token'), array(
										'class' => 'control-label span2'
									))
								}}

								<div class="span9">
									{{
										Form::select('csrf', array(
											'1' => Lang::get('admin.enabled'),
											'0' => Lang::get('admin.disabled'),
										), $site->general->csrf, array(
											'class' => 'input-xxlarge'
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.csrf_token_exp') }}
									</div>
								</div>
							</div>
						</div>

						<div id="site-content" class="tab-pane fade">
							<div class="control-group">
								{{
									Form::label('guest_posts', Lang::get('admin.guest_posts'), array(
										'class' => 'control-label span2'
									))
								}}

								<div class="span9">
									{{
										Form::select('guest_posts', array(
											'1' => Lang::get('admin.enabled'),
											'0' => Lang::get('admin.disabled'),
										), $site->general->guestPosts, array(
											'class' => 'input-xxlarge'
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.guest_posts_exp') }}
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
									Form::label('no_expire', Lang::get('admin.expiration'), array(
										'class' => 'control-label span2'
									))
								}}

								<div class="span9">
									{{
										Form::select('no_expire', array(
											'0' => Lang::get('admin.pastes_expire'),
											'1' => Lang::get('admin.pastes_donot_expire'),
										), $site->general->noExpire, array(
											'class' => 'input-xxlarge'
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.expiration_exp') }}
									</div>
								</div>
							</div>

							<div class="control-group">
								{{
									Form::label('paste_search', Lang::get('admin.paste_search'), array(
										'class' => 'control-label span2'
									))
								}}

								<div class="span9">
									{{
										Form::select('paste_search', array(
											'1' => Lang::get('admin.enabled'),
											'0' => Lang::get('admin.disabled'),
										), $site->general->pasteSearch, array(
											'class' => 'input-xxlarge'
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.paste_search_exp') }}
									</div>
								</div>
							</div>

							<div class="control-group">
								{{
									Form::label('comments', Lang::get('global.comments'), array(
										'class' => 'control-label span2'
									))
								}}

								<div class="span9">
									{{
										Form::select('comments', array(
											'1' => Lang::get('admin.enabled'),
											'0' => Lang::get('admin.disabled'),
										), $site->general->comments, array(
											'class' => 'input-xxlarge'
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.comments_exp') }}
									</div>
								</div>
							</div>

							<div class="control-group">
								{{
									Form::label('share', Lang::get('admin.share'), array(
										'class' => 'control-label span2'
									))
								}}

								<div class="span9">
									{{
										Form::select('share', array(
											'1' => Lang::get('admin.enabled'),
											'0' => Lang::get('admin.disabled'),
										), $site->general->share, array(
											'class' => 'input-xxlarge'
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.share_exp') }}
									</div>
								</div>
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
