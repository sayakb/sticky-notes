{{ '<?xml version="1.0" encoding="UTF-8"?>' }}

<result>
	<pastes>
		@for ($idx = 1; $idx <= count($pastes); $idx++)
			<paste_{{ $idx }}>{{ $pastes[$idx - 1]['urlkey'] }}</paste_{{ $idx }}>
		@endfor
	</pastes>
	<count>{{ $count }}</count>
	<pages>{{ $pages }}</pages>
</result>
