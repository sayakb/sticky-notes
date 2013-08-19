<div class="row">
	<div class="col-sm-12">
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
		@elseif ( ! empty($error))
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
