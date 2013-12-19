<div class="row">
	<div class="col-sm-12">
		<div class="pre-info pre-header">
			<div class="row">
				<div class="col-sm-5">
					<h4>
						@if (empty($paste->title))
							{{ Lang::get('global.paste') }}
							#{{ $paste->urlkey }}
						@else
							{{{ $paste->title }}}
						@endif
					</h4>
				</div>

				<div class="col-sm-7 text-right">
					@if ($context == 'ShowController')
						@if ( ! empty($site->services->googleApiKey))
							{{
								link_to("#", Lang::get('show.short_url'), array(
									'class'          => 'btn btn-success',
									'data-toggle'    => 'ajax',
									'data-component' => 'shorten',
									'data-extra'     => $paste->urlkey.($paste->private ? '/'.$paste->hash : ''),
								))
							}}
						@endif

						{{
							link_to("#", Lang::get('show.wrap'), array(
								'class'        => 'btn btn-success',
								'data-toggle'  => 'wrap',
							))
						}}

						{{
							link_to("{$paste->urlkey}/{$paste->hash}/raw", Lang::get('show.raw'), array(
								'class' => 'btn btn-success'
							))
						}}

						{{
							link_to("rev/{$paste->urlkey}", Lang::get('show.revise'), array(
								'class' => 'btn btn-success'
							))
						}}
					@elseif ($context == 'ListController')
						{{
							link_to($paste->urlkey, Lang::get('list.show_paste'), array(
								'class' => 'btn btn-success'
							))
						}}
					@endif

					@if ($role->admin OR ($role->user AND $auth->id == $paste->author_id))
						@if ($paste->password)
							<span class="btn btn-warning" title="{{ Lang::get('global.paste_pwd') }}" data-toggle="tooltip">
								<span class="glyphicon glyphicon-lock"></span>
							</span>
						@elseif ($paste->private)
							<span class="btn btn-warning" title="{{ Lang::get('global.paste_pvt') }}" data-toggle="tooltip">
								<span class="glyphicon glyphicon-eye-open"></span>
							</span>
						@endif

						<div class="btn-group text-left">
							<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
								<span class="glyphicon glyphicon-cog"></span>
							</button>

							<ul class="dropdown-menu pull-right" role="menu">
								<li>
									{{
										link_to("{$paste->urlkey}/{$paste->hash}/toggle",
											$paste->private ? Lang::get('global.make_public') : Lang::get('global.make_private')
										)
									}}
								</li>

								@if ($role->admin)
									<li>{{ link_to("admin/paste/{$paste->urlkey}", Lang::get('global.edit_paste')) }}</li>
								@endif
							</ul>
						</div>
					@endif
				</div>
			</div>
		</div>

		<div class="well well-sm well-white pre">
			{{ Highlighter::make()->parse($paste->id.'show', $paste->data, $paste->language) }}
		</div>

		<div class="pre-info pre-footer">
			<div class="row">
				<div class="col-sm-6">
					{{{ sprintf(Lang::get('global.posted_by'), $paste->author ?: Lang::get('global.anonymous'), date('d M Y, H:i:s e', $paste->timestamp)) }}}
				</div>

				<div class="col-sm-6 text-right">
					{{ sprintf(Lang::get('global.language'), $paste->language) }}
					&bull;
					{{ sprintf(Lang::get('global.views'), $paste->hits) }}
				</div>
			</div>
		</div>
	</div>
</div>
