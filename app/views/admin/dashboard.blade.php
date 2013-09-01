@extends('admin.layout')

@section('module')
	<section id="admin-dashboard">
		<div class="row">
			<div class="col-sm-12">
				<h4>
					<span class="glyphicon glyphicon-time"></span>
					{{ Lang::get('admin.versions') }}
				</h4>

				<div class="row">
					<div class="col-sm-6">
						<div class="well well-sm well-white">
							<b>{{ Lang::get('admin.php_version') }}:</b>
							{{ $php_version }}
						</div>
					</div>

					<div class="col-sm-6">
						<div class="well well-sm well-white">
							<b>{{ Lang::get('admin.stickynotes_version') }}:</b>
							{{ $sn_version }}

							<span class="ajax" data-realtime="false" data-onload="false" data-component="version">
								<span class="glyphicon glyphicon-refresh text-muted"></span>
							</span>
						</div>
					</div>
				</div>

				<h4>
					<span class="glyphicon glyphicon-stats"></span>
					{{ Lang::get('admin.statistics') }}
				</h4>

				<div class="row">
					<div class="col-sm-6">
						<div class="well well-sm well-white">
							<b>{{ Lang::get('admin.users') }}:</b>
							{{ $users }}
						</div>
					</div>

					<div class="col-sm-6">
						<div class="well well-sm well-white">
							<b>{{ Lang::get('admin.pastes') }}:</b>
							{{ $pastes }}
						</div>
					</div>
				</div>

				<h4>
					<span class="glyphicon glyphicon-briefcase"></span>
					{{ Lang::get('admin.system') }}
				</h4>

				<div class="row">
					<div class="col-sm-6">
						<div class="well well-sm well-white">
							<b>{{ Lang::get('admin.db_driver') }}:</b>
							{{ $db_driver }}
						</div>
					</div>

					<div class="col-sm-6">
						<div class="well well-sm well-white">
							<b>{{ Lang::get('admin.system_load') }}:</b>

							<span class="ajax" data-realtime="true" data-onload="true" data-component="sysload">
								<span class="glyphicon glyphicon-refresh text-muted"></span>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@stop
