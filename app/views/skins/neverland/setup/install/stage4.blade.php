@extends('skins.neverland.setup.page')

@section('body')
	<section id="install">
		<fieldset>
			<legend>{{ Lang::get('setup.i_stage4_title') }}</legend>

			<p>{{ Lang::get('setup.i_stage4_exp') }}</p>

			<dl class="dl-horizontal well well-small well-white">
				<dt>{{ Lang::get('global.username') }}</dt>
				<dd>{{ Session::get('install.username') }}</dd>

				<dt>{{ Lang::get('global.password') }}</dt>
				<dd>{{ Session::get('install.password') }}</dd>
			</dl>

			{{
				Form::submit(Lang::get('setup.proceed_login'), array(
					'name'    => '_finish',
					'class'   => 'btn btn-success'
				))
			}}
		</fieldset>
	</section>
@stop
