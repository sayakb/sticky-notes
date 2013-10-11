{
	"result":
	{
		"pastes": [
			@foreach ($pastes as $paste)
				{{ $paste['urlkey'] }}{{ $iterator++ < count($pastes) - 1 ? ',' : NULL }}
			@endforeach
		],
		"count": {{ $count }},
		"pages": {{ $pages }}
	}
}
