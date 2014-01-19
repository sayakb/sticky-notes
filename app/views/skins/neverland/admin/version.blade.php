@if ($updated)
	<i class="icon-ok-sign icon-blue" title="{{ Lang::get('ajax.version_ok') }}" data-toggle="tooltip"></i>
@else
	<i class="icon-exclamation-sign icon-blue" title="{{ Lang::get('ajax.version_old') }}" data-toggle="tooltip"></i>
@endif
