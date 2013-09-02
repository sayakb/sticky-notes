@extends('setup.page')

@section('body')
	<section id="install">
		<fieldset>
			<legend>{{ Lang::get('setup.stage4_title') }}</legend>

			<p>{{ Lang::get('setup.stage4_exp') }}</p>

			<dl class="dl-horizontal well well-sm well-white">
				<dt>{{ Lang::get('global.username') }}</dt>
				<dd>{{ Session::get('install.username') }}</dd>

				<dt>{{ Lang::get('global.password') }}</dt>
				<dd>{{ Session::get('install.password') }}</dd>
			</dl>

			{{
				link_to('user/login', Lang::get('setup.proceed_login'), array(
					'class' => 'btn btn-success'
				))
			}}
		</fieldset>
	</section>
@stop
