<body>
	<p>{{ sprintf(Lang::get('mail.dear_user'), $dispname) }}</p>
	<br />

	<p>{{ sprintf(Lang::get('mail.password_reset'), $site['general']['fqdn']) }}</p>
	<p>{{ sprintf(Lang::get('mail.new_password'), $password) }}</p>
	<br />

	<p>{{ sprintf(Lang::get('mail.click_login'), link_to('user/login')) }}</p>
	<hr />

	<i>{{ Lang::get('mail.autogen_mail') }}</i>
</body>
