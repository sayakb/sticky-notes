{{ '<?xml version="1.0" encoding="UTF-8"?>' }}

<result>
	<values>
		@foreach ($values as $value)
			<value>{{ $value }}</value>
		@endforeach
	</values>
</result>
