<div class="row">
	<div class="col-sm-12">
		@if ( ! empty($global))
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
		@endif

		@if ( ! empty($success))
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
		@endif

		@if ( ! empty($error))
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
		@endif
	</div>
</div>
