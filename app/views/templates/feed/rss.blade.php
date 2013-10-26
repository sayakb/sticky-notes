{{ '<?xml version="1.0" encoding="UTF-8"?>' }}
<rss version="2.0">
	<channel>
		<title>{{ Lang::get('global.feed') }} - {{ $site->general->title }}</title>
		<link>{{ url('all') }}</link>
		<language>{{ $site->general->lang }}</language>
		<lastBuildDate>{{ date(DATE_RSS) }}</lastBuildDate>
		<copyright>Sayak Banerjee (mail@sayakbanerjee.com)</copyright>

		@foreach ($pastes as $paste)
			<item>
				<title>
					@if (empty($paste['title']))
						{{ Lang::get('global.paste') }}
						#{{ $paste['urlkey'] }}
					@else
						{{{ $paste['title'] }}}
					@endif
				</title>

				<link>{{ url($paste['urlkey']) }}</link>
				<description><![CDATA[{{ Paste::getAbstract($paste['data']) }}]]></description>
				<pubDate>{{ date(DATE_RSS, $paste['timestamp']) }}</pubDate>
				<guid>{{ url($paste['urlkey']) }}</guid>
			</item>
		@endforeach
	</channel>
</rss>
