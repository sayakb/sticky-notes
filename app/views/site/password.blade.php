@extends('common.page')

@section('body')
	<section id="password">
		<div class="row">
			<div class="col-sm-12">
				<div class="jumbotron text-center">
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
							'name'    => 'submit',
							'class'   => 'btn btn-primary'
						))
					}}
				</div>
			</div>
		</div>
	</section>
@stop
