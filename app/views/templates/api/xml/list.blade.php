{{ '<?xml version="1.0" encoding="UTF-8"?>' }}

<result>
	<pastes>
		@foreach ($pastes as $paste)
			<paste>{{ $paste['urlkey'] }}</paste>
		@endforeach
	</pastes>
	<count>{{ $count }}</count>
	<pages>{{ $pages }}</pages>
</result>
