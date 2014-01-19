@extends("skins.bootstrap.common.{$container}")

@section('body')
	<section id="password">
		{{
			Form::open(array(
				'autocomplete'   => 'off',
				'role'           => 'form'
			))
		}}

		<div class="row">
			<div class="col-sm-12">
				<div class="jumbotron text-center form-inline">
					<h1><span class="glyphicon glyphicon-lock text-success"></span></h1>
					<h2>{{ Lang::get('global.paste_pwd') }}</h2>

					{{
						Form::password('password', array(
							'class'         => 'form-control',
							'placeholder'   => Lang::get('global.password')
						))
					}}

					{{
						Form::submit(Lang::get('global.submit'), array(
							'name'    => '_submit',
							'class'   => 'btn btn-primary'
						))
					}}
				</div>
			</div>
		</div>

		{{ Form::close() }}
	</section>
@stop
