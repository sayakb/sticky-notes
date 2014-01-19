@extends('skins.bootstrap.admin.layout')

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

							<span data-toggle="ajax" data-onload="true" data-component="version">
								<span class="glyphicon glyphicon-refresh"></span>
							</span>
						</div>
					</div>
				</div>

				<h4>
					<span class="glyphicon glyphicon-hdd"></span>
					{{ Lang::get('admin.data') }}
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

							<span data-toggle="ajax" data-realtime="true" data-onload="true" data-component="sysload">
								<span class="glyphicon glyphicon-refresh"></span>
							</span>
						</div>
					</div>
				</div>

				<h4>
					<span class="glyphicon glyphicon-stats"></span>
					{{ Lang::get('admin.paste_stats') }}
				</h4>

				<div class="row">
					<div class="col-sm-12">
						@if (count($stats) > 1)
							<div id="dashboard-stats" class="well well-sm well-white">
								@include('skins.bootstrap.common.loader')
							</div>

							<script type="text/javascript">
								// Load the Visualization API and the piechart package.
								google.load('visualization', '1.0', { packages: ['corechart'], language: '{{ $site->general->lang }}' });

								// Set a callback to run when the Google Visualization API is loaded.
								google.setOnLoadCallback(function()
								{
									// Create the data table.
									chartData = new google.visualization.DataTable();

									// Add columns
									chartData.addColumn('date', '{{ Lang::get('admin.date') }}');
									chartData.addColumn('number', '{{ Lang::get('admin.web') }}');
									chartData.addColumn('number', '{{ Lang::get('admin.api') }}');

									// Add rows
									@foreach ($stats as $stat)
										chartData.addRow([ new Date('{{ $stat['date'] }}'), {{ $stat['web'] }}, {{ $stat['api'] }} ]);
									@endforeach

									// Define the chart container
									chartContainer = document.getElementById('dashboard-stats');

									// Draw the chart
									initAreaChart();
								});
							</script>
						@else
							<div class="alert alert-info">
								{{ Lang::get('admin.stat_no_data') }}
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</section>
@stop
