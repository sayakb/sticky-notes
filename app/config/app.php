<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Sticky Notes Core Version
	|--------------------------------------------------------------------------
	|
	| This is the sticky notes core version number. Please do not change
	| this value to have the update checker keep working as expected.
	|
	*/

	'version' => '1.9',

	/*
	|--------------------------------------------------------------------------
	| Application Debug Mode
	|--------------------------------------------------------------------------
	|
	| When your application is in debug mode, detailed error messages with
	| stack traces will be shown on every error that occurs within your
	| application. If disabled, a simple generic error page is shown.
	|
	*/

	'debug' => FALSE,

	/*
	|--------------------------------------------------------------------------
	| Application URL
	|--------------------------------------------------------------------------
	|
	| This URL is used by the console to properly generate URLs when using
	| the Artisan command line tool. You should set this to the root of
	| your application so that it is used when running Artisan tasks.
	|
	*/

	'url' => 'http://localhost',

	/*
	|--------------------------------------------------------------------------
	| Application Timezone
	|--------------------------------------------------------------------------
	|
	| Here you may specify the default timezone for your application, which
	| will be used by the PHP date and date-time functions. We have gone
	| ahead and set this to a sensible default for you out of the box.
	|
	*/

	'timezone' => 'UTC',

	/*
	|--------------------------------------------------------------------------
	| Application Locale Configuration
	|--------------------------------------------------------------------------
	|
	| The application locale determines the default locale that will be used
	| by the translation service provider. You are free to set this value
	| to any of the locales which will be supported by the application.
	|
	*/

	'locale' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Encryption Key
	|--------------------------------------------------------------------------
	|
	| This key is used by the Illuminate encrypter service and should be set
	| to a random, 32 character string, otherwise these encrypted strings
	| will not be safe. Please do this before deploying an application!
	|
	*/

	'key' => 'pKMSkxfvJnNg0c0soBcg7oRYa6NMrv31',

	/*
	|--------------------------------------------------------------------------
	| Stats collector Configuration
	|--------------------------------------------------------------------------
	|
	| This flag is used to determine if your site's URL will be included
	| in the statistics report. See PRIVACY.md for details on the information
	| that is sent over to the statistics server.
	|
	| Note: As a note of thanks to the developer, please keep this as true.
	| Your site's URL will NEVER be published and is kept secure.
	|
	*/

	'fullStats' => TRUE,

	/*
	|--------------------------------------------------------------------------
	| Autoloaded Service Providers
	|--------------------------------------------------------------------------
	|
	| The service providers listed here will be automatically loaded on the
	| request to your application. Feel free to add your own services to
	| this array to grant expanded functionality to your applications.
	|
	*/

	'providers' => array(

		// Illuminate
		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		'Illuminate\Auth\AuthServiceProvider',
		'Illuminate\Cache\CacheServiceProvider',
		'Illuminate\Session\CommandsServiceProvider',
		'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
		'Illuminate\Routing\ControllerServiceProvider',
		'Illuminate\Cookie\CookieServiceProvider',
		'Illuminate\Database\DatabaseServiceProvider',
		'Illuminate\Encryption\EncryptionServiceProvider',
		'Illuminate\Filesystem\FilesystemServiceProvider',
		'Illuminate\Hashing\HashServiceProvider',
		'Illuminate\Html\HtmlServiceProvider',
		'Illuminate\Log\LogServiceProvider',
		'Illuminate\Mail\MailServiceProvider',
		'Illuminate\Database\MigrationServiceProvider',
		'Illuminate\Pagination\PaginationServiceProvider',
		'Illuminate\Queue\QueueServiceProvider',
		'Illuminate\Redis\RedisServiceProvider',
		'Illuminate\Remote\RemoteServiceProvider',
		'Illuminate\Auth\Reminders\ReminderServiceProvider',
		'Illuminate\Database\SeedServiceProvider',
		'Illuminate\Session\SessionServiceProvider',
		'Illuminate\Translation\TranslationServiceProvider',
		'Illuminate\Validation\ValidationServiceProvider',
		'Illuminate\View\ViewServiceProvider',
		'Illuminate\Workbench\WorkbenchServiceProvider',

		// Sayakb
		'Sayakb\Akismet\AkismetServiceProvider',
		'Sayakb\Captcha\CaptchaServiceProvider',

		// StickyNotes
		'StickyNotes\Hashing\HashServiceProvider',

	),

	/*
	|--------------------------------------------------------------------------
	| Service Provider Manifest
	|--------------------------------------------------------------------------
	|
	| The service provider manifest is used by Laravel to lazy load service
	| providers which are not needed for each request, as well to keep a
	| list of all of the services. Here, you may set its storage spot.
	|
	*/

	'manifest' => storage_path().'/meta',

	/*
	|--------------------------------------------------------------------------
	| Class Aliases
	|--------------------------------------------------------------------------
	|
	| This array of class aliases will be registered when this application
	| is started. However, feel free to register as many as you wish as
	| the aliases are "lazy" loaded so they don't hinder performance.
	|
	*/

	'aliases' => array(

		// Illuminate
		'App'                           => 'Illuminate\Support\Facades\App',
		'Artisan'                       => 'Illuminate\Support\Facades\Artisan',
		'Blade'                         => 'Illuminate\Support\Facades\Blade',
		'ClassLoader'                   => 'Illuminate\Support\ClassLoader',
		'Config'                        => 'Illuminate\Support\Facades\Config',
		'Controller'                    => 'Illuminate\Routing\Controller',
		'Crypt'                         => 'Illuminate\Support\Facades\Crypt',
		'DB'                            => 'Illuminate\Support\Facades\DB',
		'Eloquent'                      => 'Illuminate\Database\Eloquent\Model',
		'Event'                         => 'Illuminate\Support\Facades\Event',
		'File'                          => 'Illuminate\Support\Facades\File',
		'Form'                          => 'Illuminate\Support\Facades\Form',
		'Guard'                         => 'Illuminate\Auth\Guard',
		'Hash'                          => 'Illuminate\Support\Facades\Hash',
		'HTML'                          => 'Illuminate\Support\Facades\HTML',
		'Input'                         => 'Illuminate\Support\Facades\Input',
		'Lang'                          => 'Illuminate\Support\Facades\Lang',
		'Log'                           => 'Illuminate\Support\Facades\Log',
		'Paginator'                     => 'Illuminate\Support\Facades\Paginator',
		'Password'                      => 'Illuminate\Support\Facades\Password',
		'Queue'                         => 'Illuminate\Support\Facades\Queue',
		'Redis'                         => 'Illuminate\Support\Facades\Redis',
		'Request'                       => 'Illuminate\Support\Facades\Request',
		'Route'                         => 'Illuminate\Support\Facades\Route',
		'Schema'                        => 'Illuminate\Support\Facades\Schema',
		'Seeder'                        => 'Illuminate\Database\Seeder',
		'SSH'                           => 'Illuminate\Support\Facades\SSH',
		'Str'                           => 'Illuminate\Support\Str',
		'URL'                           => 'Illuminate\Support\Facades\URL',
		'Validator'                     => 'Illuminate\Support\Facades\Validator',

		// PHPDiff
		'DiffRenderer'                  => 'Diff_Renderer_Html_SideBySide',

		// Sayakb
		'Captcha'                       => 'Sayakb\Captcha\Facades\Captcha',

		// StickyNotes
		'API'                           => 'StickyNotes\API',
		'Antispam'                      => 'StickyNotes\Antispam',
		'Auth'                          => 'StickyNotes\Auth',
		'Cache'                         => 'StickyNotes\Cache',
		'Config'                        => 'StickyNotes\Config',
		'Cookie'                        => 'StickyNotes\Cookie',
		'Cron'                          => 'StickyNotes\Cron',
		'Feed'                          => 'StickyNotes\Feed',
		'Highlighter'                   => 'StickyNotes\Highlighter',
		'Mail'                          => 'StickyNotes\Mail',
		'PHPass'                        => 'StickyNotes\PHPass',
		'PHPDiff'                       => 'StickyNotes\PHPDiff',
		'Redirect'                      => 'StickyNotes\Redirect',
		'Response'                      => 'StickyNotes\Response',
		'Service'                       => 'StickyNotes\Service',
		'Session'                       => 'StickyNotes\Session',
		'Setup'                         => 'StickyNotes\Setup',
		'System'                        => 'StickyNotes\System',
		'View'                          => 'StickyNotes\View',

		'StickyNotesDBUserProvider'     => 'StickyNotes\Auth\StickyNotesDBUserProvider',
		'StickyNotesLDAPUserProvider'   => 'StickyNotes\Auth\StickyNotesLDAPUserProvider',
		'StickyNotesOAuthUserProvider'  => 'StickyNotes\Auth\StickyNotesOAuthUserProvider',

	),

);
