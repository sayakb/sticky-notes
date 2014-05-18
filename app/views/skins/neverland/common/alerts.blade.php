@if ( ! empty($global))
	<div class="row-fluid">
		<div class="span12">
			<div class="alert alert-warning">
				<button type="button" class="close" data-dismiss="alert">&times;</button>

				@if (is_array($global))
					@foreach ($global as $msg)
						{{ $msg }}
					@endforeach
				@else
					{{ $global }}
				@endif
			</div>
		</div>
	</div>
@endif

@if ( ! empty($success))
	<div class="row-fluid">
		<div class="span12">
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert">&times;</button>

				@if (is_array($success))
					@foreach ($success as $msg)
						{{ $msg }}
					@endforeach
				@else
					{{ $success }}
				@endif
			</div>
		</div>
	</div>
@endif

@if ( ! empty($error))
	<div class="row-fluid">
		<div class="span12">
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert">&times;</button>

				@if (is_array($error))
					@foreach ($error as $msg)
						{{ $msg }}
					@endforeach
				@else
					{{ $error }}
				@endif
			</div>
		</div>
	</div>
@endif
