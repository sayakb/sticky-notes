{
	"result":
	{
		{{ $param }}: [
			@foreach ($values as $value)
				{{ $value }}

				@if ($iterator++ < count($values) - 1)
					,
				@endif
			@endforeach
		]
	}
}
