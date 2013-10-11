{{ '<?xml version="1.0" encoding="UTF-8"?>' }}

<result>
	<{{ $param }}>
		@foreach ($values as $value)
			<value>{{ $value }}</value>
		@endforeach
	</{{ $param }}>
</result>
