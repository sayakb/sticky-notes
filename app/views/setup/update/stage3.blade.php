@extends('setup.page')

@section('body')
	<section id="update">
		<fieldset>
			<legend>{{ Lang::get('setup.u_stage3_title') }}</legend>

			<p>{{ Lang::get('setup.u_stage3_exp') }}</p>

			{{
				link_to('/', Lang::get('setup.return_sn'), array(
					'class' => 'btn btn-success'
				))
			}}
		</fieldset>
	</section>
@stop
