<p>{{ sprintf(Lang::get('mail.hello_user'), $name) }}</p>

<p>
	{{ sprintf(Lang::get('mail.password_reset'), $site['general']['fqdn']) }}
	<br />
	{{ sprintf(Lang::get('mail.new_password'), $password) }}
</p>

<p>{{ sprintf(Lang::get('mail.click_login'), link_to('user/login')) }}</p>
<hr />

<i>{{ Lang::get('mail.autogen_mail') }}</i>
