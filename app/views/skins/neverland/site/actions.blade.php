@if ($role->admin OR ($role->user AND $auth->id == $paste->author_id))
	@if ($paste->password)
		<span class="btn btn-warning" title="{{ Lang::get('global.paste_pwd') }}" data-toggle="tooltip">
			<i class="icon-lock icon-white"></i>
		</span>
	@elseif ($paste->private)
		<span class="btn btn-warning" title="{{ Lang::get('global.paste_pvt') }}" data-toggle="tooltip">
			<i class="icon-eye-open icon-white"></i>
		</span>
	@endif

	<span class="btn-group align-left">
		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
			<i class="icon-cog icon-white"></i>
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
	</span>
@endif
