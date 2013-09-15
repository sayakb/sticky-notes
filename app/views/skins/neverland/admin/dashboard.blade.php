@extends('skins.neverland.admin.layout')

@section('module')
	<section id="admin-dashboard">
		<div class="row-fluid">
			<div class="span12">
				<h5>
					<i class="icon-time"></i>
					{{ Lang::get('admin.versions') }}
				</h5>

				<div class="row-fluid">
					<div class="span6">
						<div class="well well-small well-white">
							<b>{{ Lang::get('admin.php_version') }}:</b>
							{{ $php_version }}
						</div>
					</div>

					<div class="span6">
						<div class="well well-small well-white">
							<b>{{ Lang::get('admin.stickynotes_version') }}:</b>
							{{ $sn_version }}

							<span data-toggle="ajax" data-onload="true" data-component="version">
								<i class="icon-refresh"></i>
							</span>
						</div>
					</div>
				</div>

				<h5>
					<i class="icon-signal"></i>
					{{ Lang::get('admin.statistics') }}
				</h5>

				<div class="row-fluid">
					<div class="span6">
						<div class="well well-small well-white">
							<b>{{ Lang::get('admin.users') }}:</b>
							{{ $users }}
						</div>
					</div>

					<div class="span6">
						<div class="well well-small well-white">
							<b>{{ Lang::get('admin.pastes') }}:</b>
							{{ $pastes }}
						</div>
					</div>
				</div>

				<h5>
					<i class="icon-briefcase"></i>
					{{ Lang::get('admin.system') }}
				</h5>

				<div class="row-fluid">
					<div class="span6">
						<div class="well well-small well-white">
							<b>{{ Lang::get('admin.db_driver') }}:</b>
							{{ $db_driver }}
						</div>
					</div>

					<div class="span6">
						<div class="well well-small well-white">
							<b>{{ Lang::get('admin.system_load') }}:</b>

							<span data-toggle="ajax" data-realtime="true" data-onload="true" data-component="sysload">
								<i class="icon-refresh"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@stop
