@extends('skins.neverland.admin.layout')

@section('module')
	<section id="admin-skin">
		<fieldset>
			<legend>{{ Lang::get('admin.skin_chooser') }}</legend>

			@foreach ($skins as $skin)
				<div class="row-fluid">
					<div class="span12">
						{{
							HTML::image(url('admin/skin/preview/'.urlencode($skin->key)), NULL, array(
								'class'   => 'thumbnail pull-left',
								'width'   => 310,
								'height'  => 190,
							))
						}}

						<h4>
							{{{ $skin->name }}}

							<small>
								{{ Lang::get('admin.version') }}:
								{{{ $skin->version }}}
							</small>
						</h4>

						@if ( ! empty($skin->author))
							<p>
								{{ Lang::get('global.author') }}:
								{{ $skin->author }}
							</p>
						@endif

						<p>{{{ $skin->description }}}</p>

						@if ($site->general->skin == $skin->key)
							<button class="btn btn-success disabled" disabled="disabled">
								<i class="icon-ok icon-white"></i>
								{{ Lang::get('admin.active') }}
							</button>
						@else
							{{
								link_to(url('admin/skin/set/'.urlencode($skin->key)), Lang::get('admin.use_theme'), array(
									'class' => 'btn btn-primary',
								))
							}}
						@endif
					</div>
				</div>
			@endforeach
		</fieldset>
	</section>
@stop
