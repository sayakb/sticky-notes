<div class="loader">
	@include('skins.bootstrap.common.loader')
</div>

<nav class="navbar navbar-default navbar-static-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			<a class="navbar-brand" href="{{ url() }}">{{ $site->general->title }}</a>
		</div>

		<div class="collapse navbar-collapse navbar-ex1-collapse">
			<ul class="nav navbar-nav navbar-right">
				{{ View::menu('navigation') }}
			</ul>
		</div>
	</div>
</nav>

<div class="container container-body">
	@if ( ! empty($site->general->bannerTop))
		<div class="row">
			<div class="col-sm-12">
				{{ $site->general->bannerTop }}
			</div>
		</div>
	@endif

	@yield('body')

	@if ( ! empty($site->general->bannerBottom))
		<div class="row">
			<div class="col-sm-12">
				{{ $site->general->bannerBottom }}
			</div>
		</div>
	@endif
</div>

<footer>
	<p>{{ $site->general->copyright }}</p>

	<!-- Do not the following copyright notice to avoid copyright violation. See http://opensource.org/licenses/BSD-2-Clause for details -->
	<!-- You may however choose to remove the hyperlinks and retain the copyright as plain text -->
	<p><a href="http://sayakbanerjee.com/sticky-notes">Sticky Notes</a> &copy; 2014 <a href="http://sayakbanerjee.com">Sayak Banerjee</a>.</p>

	@if ($active AND $role->admin)
		<small>{{ sprintf(Lang::get('global.statistics'), microtime(true) - LARAVEL_START, count(DB::getQueryLog())) }}</small>
	@endif

	@if (Antispam::flags()->php)
		<!-- Honeypot, do not click! -->
		<a href="http://www.ssdfreaks.com/household.php?to=857"><!-- agreement --></a>
	@endif
</footer>
