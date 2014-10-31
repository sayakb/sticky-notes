@if ($updated)
	<span class="glyphicon glyphicon-ok-sign text-success" title="{{ Lang::get('ajax.version_ok') }}" data-toggle="tooltip"></span>
@else
	<span class="glyphicon glyphicon-exclamation-sign text-danger" title="{{ Lang::get('ajax.version_old') }}" data-toggle="tooltip"></span>
@endif

