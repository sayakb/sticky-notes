@if ($role->admin OR ($role->user AND $user->username == $paste->author))
	@if ($paste->password)
		<span class="btn btn-warning" title="{{ Lang::get('global.paste_pwd') }}">
			<span class="glyphicon glyphicon-lock"></span>
		</span>
	@elseif ($paste->private)
		<span class="btn btn-warning" title="{{ Lang::get('global.paste_pvt') }}">
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
