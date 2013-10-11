{
	"result":
	{
		"values": [
			@foreach ($values as $value)
				{{ $value }}{{ $iterator++ < count($values) - 1 ? ',' : NULL }}
			@endforeach
		]
	}
}
