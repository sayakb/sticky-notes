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

						<li>
							<a href="#site-banners" data-toggle="tab">{{ Lang::get('admin.banners') }}</a>
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
									Form::label('paste_visibility', Lang::get('admin.visibility'), array(
										'class' => 'control-label span2'
									))
								}}

								<div class="span9">
									{{
										Form::select('paste_visibility', array(
											'default' => Lang::get('admin.allow_all'),
											'public'  => Lang::get('admin.enforce_public'),
											'private' => Lang::get('admin.enforce_private'),
										), $site->general->pasteVisibility, array(
											'class' => 'input-xxlarge'
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.visibility_exp') }}
									</div>
								</div>
							</div>

							<div class="control-group">
								{{
									Form::label('flag_paste', Lang::get('admin.flagging'), array(
										'class' => 'control-label span2'
									))
								}}

								<div class="span9">
									{{
										Form::select('flag_paste', array(
											'all'  => Lang::get('admin.flag_all'),
											'user' => Lang::get('admin.flag_user'),
											'off'  => Lang::get('admin.flag_off'),
										), $site->general->flagPaste, array(
											'class' => 'input-xxlarge'
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.flagging_exp') }}
									</div>
								</div>
							</div>

							<div class="control-group">
								{{
									Form::label('allow_paste_del', Lang::get('admin.delete_pastes'), array(
										'class' => 'control-label span2'
									))
								}}

								<div class="span9">
									{{
										Form::select('allow_paste_del', array(
											'1' => Lang::get('admin.enabled'),
											'0' => Lang::get('admin.disabled'),
										), $site->general->allowPasteDel, array(
											'class' => 'input-xxlarge'
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.delete_pastes_exp') }}
									</div>
								</div>
							</div>

							<div class="control-group">
								{{
									Form::label('allow_attachment', Lang::get('admin.attachment'), array(
										'class' => 'control-label span2'
									))
								}}

								<div class="span9">
									{{
										Form::select('allow_attachment', array(
											'1' => Lang::get('admin.enabled'),
											'0' => Lang::get('admin.disabled'),
										), $site->general->allowAttachment, array(
											'class' => 'input-xxlarge'
										))
									}}

									<div class="help-block">
										{{ Lang::get('admin.attachment_exp') }}
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
									Form::label('max_paste_size', Lang::get('admin.size_limit'), array(
										'class' => 'control-label span2'
									))
								}}

								<div class="span9">
									<div class="input-append">
										{{
											Form::text('max_paste_size', $site->general->maxPasteSize, array(
												'class' => 'input-xxlarge',
											))
										}}

										<div class="add-on">
											{{ Lang::get('admin.bytes') }}
										</div>
									</div>

									<div class="help-block">
										{{ Lang::get('admin.size_limit_exp') }}
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
											'none' => Lang::get('admin.noexpire_none'),
											'user' => Lang::get('admin.noexpire_user'),
											'all'  => Lang::get('admin.noexpire_all'),
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

						<div id="site-banners" class="tab-pane fade">
							<div class="row-fluid">
								<div class="span12">
									<div class="alert alert-info">
										{{ Lang::get('admin.banners_exp') }}
									</div>

									<div class="alert alert-success">
										{{{ sprintf(Lang::get('admin.allowed_tags'), $site->general->allowedTags) }}}
									</div>
								</div>
							</div>

							<div class="control-group">
								{{
									Form::label('banner_top', Lang::get('admin.banner_top'), array(
										'class' => 'control-label span2'
									))
								}}

								<div class="span9">
									{{
										Form::textarea('banner_top', $site->general->bannerTop, array(
											'class' => 'input-xxlarge',
											'rows'  => 5,
										))
									}}
								</div>
							</div>

							<div class="control-group">
								{{
									Form::label('banner_bottom', Lang::get('admin.banner_bottom'), array(
										'class' => 'control-label span2'
									))
								}}

								<div class="span9">
									{{
										Form::textarea('banner_bottom', $site->general->bannerBottom, array(
											'class' => 'input-xxlarge',
											'rows'  => 5,
										))
									}}
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
