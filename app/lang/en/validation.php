<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| such as the size rules. Feel free to tweak each of these messages.
	|
	*/

	"accepted"         => "The :attribute must be accepted.",
	"active_url"       => "The :attribute is not a valid URL.",
	"after"            => "The :attribute must be a date after :date.",
	"alpha"            => "The :attribute may only contain letters.",
	"alpha_dash"       => "The :attribute may only contain letters, numbers, and dashes.",
	"alpha_num"        => "The :attribute may only contain letters and numbers.",
	"array"            => "The :attribute must be an array.",
	"auth"             => "You must be logged in to complete this action.",
	"before"           => "The :attribute must be a date before :date.",
	"between"          => array(
		"numeric"      => "The :attribute must be between :min - :max.",
		"file"         => "The :attribute must be between :min - :max kilobytes.",
		"string"       => "The :attribute must be between :min - :max characters.",
		"array"        => "The :attribute must have between :min - :max items.",
	),
	"captcha"          => "The human verification failed.",
	"confirmed"        => "The :attribute confirmation does not match.",
	"date"             => "The :attribute is not a valid date.",
	"date_format"      => "The :attribute does not match the format :format.",
	"different"        => "The :attribute and :other must be different.",
	"digits"           => "The :attribute must be :digits digits.",
	"digits_between"   => "The :attribute must be between :min and :max digits.",
	"email"            => "The :attribute format is invalid.",
	"exists"           => "The selected :attribute is invalid.",
	"image"            => "The :attribute must be an image.",
	"in"               => "The selected :attribute is invalid.",
	"integer"          => "The :attribute must be an integer.",
	"ip"               => "The :attribute must be a valid IP address.",
	"max"              => array(
		"numeric"      => "The :attribute may not be greater than :max.",
		"file"         => "The :attribute may not be greater than :max kilobytes.",
		"string"       => "The :attribute may not be greater than :max characters.",
		"array"        => "The :attribute may not have more than :max items.",
	),
	"mbmax"            => "The :attribute may not be greater than :max bytes.",
	"mimes"            => "The :attribute must be a file of type: :values.",
	"min"              => array(
		"numeric"      => "The :attribute must be at least :min.",
		"file"         => "The :attribute must be at least :min kilobytes.",
		"string"       => "The :attribute must be at least :min characters.",
		"array"        => "The :attribute must have at least :min items.",
	),
	"not_in"           => "The selected :attribute is invalid.",
	"numeric"          => "The :attribute must be a number.",
	"regex"            => "The :attribute format is invalid.",
	"required"         => "The :attribute field is required.",
	"required_if"      => "The :attribute field is required when :other is :value.",
	"required_with"    => "The :attribute field is required when :values is present.",
	"required_without" => "The :attribute field is required when :values is not present.",
	"same"             => "The :attribute and :other must match.",
	"size"             => array(
		"numeric"      => "The :attribute must be :size.",
		"file"         => "The :attribute must be :size kilobytes.",
		"string"       => "The :attribute must be :size characters.",
		"array"        => "The :attribute must contain :size items.",
	),
	"unique"           => "The :attribute has already been taken.",
	"url"              => "The :attribute format is invalid.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	"custom" => array(

		"php_key"         => array(
			"required_if" => "The access key field is required if honeypot filter is enabled.",
		),

		"php_days"        => array(
			"required_if" => "The age threshold field is required if honeypot filter is enabled.",
			"integer"     => "The age threshold must be an integer.",
			"between"     => "The age threshold must be between :min - :max.",
		),

		"php_score"       => array(
			"required_if" => "The threat score field is required if honeypot filter is enabled.",
			"integer"     => "The threat score must be an integer.",
			"between"     => "The threat score must be between :min - :max.",
		),

		"php_type"        => array(
			"required_if" => "The visitor filter field is required if honeypot filter is enabled.",
			"integer"     => "The visitor filter must be an integer.",
			"between"     => "The visitor filter must be between :min - :max.",
		),

		"flood_threshold" => array(
			"required_if" => "The flood threshold field is required if flood filter is enabled.",
			"integer"     => "The flood threshold must be an integer.",
			"between"     => "The flood threshold must be between :min - :max.",
		),

		"akismet_key"     => array(
			"required_if" => "The Akismet key field is required if Akismet filter is enabled.",
			"akismet_key" => "The Akismet API key you entered is invalid.",
		),

		"ldap_server"     => array(
			"required_if" => "The LDAP server field is required if LDAP method is used.",
		),

		"ldap_base_dn"    => array(
			"required_if" => "The LDAP base dn field is required if LDAP method is used.",
		),

		"ldap_uid"        => array(
			"required_if" => "The LDAP uid field is required if LDAP method is used.",
		),

		"ldap_admin"      => array(
			"required_if" => "The LDAP admin group field is required if LDAP method is used.",
		),

	),

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	"attributes" => array(),

);
