@extends("skins.neverland.common.{$container}")

@section('body')
	<section id="password">
		{{
			Form::open(array(
				'autocomplete'   => 'off',
				'role'           => 'form'
			))
		}}

		<div class="row-fluid">
			<div class="span12">
				<div class="hero-unit align-center">
					{{ HTML::image(View::asset('img/lock.png')) }}
					<h2>{{ Lang::get('global.paste_pwd') }}</h2>

					<div class="input-append">
						{{
							Form::password('password', array(
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
		</div>

		{{ Form::close() }}
	</section>
@stop
