@extends('skins.neverland.setup.page')

@section('body')
	<section id="update">
		<fieldset>
			<legend>{{ Lang::get('setup.u_stage3_title') }}</legend>

			<p>{{ Lang::get('setup.u_stage3_exp') }}</p>

			@if (count($messages) > 0)
				<h3>{{ Lang::get('setup.update_notifs') }}</h3>

				<p>{{ Lang::get('setup.update_notifs_exp') }}</p>

				<hr />

				@foreach ($messages as $version => $message)
					<h4>{{ sprintf(Lang::get('setup.notify_version'), $version) }}</h4>

					<p>{{ $message }}</p>

					<hr />
				@endforeach
			@endif

			{{
				Form::submit(Lang::get('setup.return_sn'), array(
					'name'    => '_finish',
					'class'   => 'btn btn-success'
				))
			}}
		</fieldset>
	</section>
@stop
