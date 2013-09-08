{
	"result":
	{
		"pastes":
		{
			@for ($idx = 1; $idx <= count($pastes); $idx++)
				"paste_{{ $idx }}": {{ $pastes[$idx - 1]['urlkey'] }}{{ $idx < count($pastes) ? ',' : NULL }}
			@endfor
		},
		"count": {{ $count }},
		"pages": {{ $pages }}
	}
}
