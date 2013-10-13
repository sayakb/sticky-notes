# Introduction

This is a simple little PHP5 class that enables you use the Akismet anti-spam service in your Laravel 4 application.
Most of the ground work was done by Alex Potsides, [http://www.achingbrain.net](http://www.achingbrain.net)

# Download

If you're using Laravel 4, you can skip the download proceedure and simply add the following line to your app's composer.json file in the require block

	"require": {
		"kenmoini/akismet": "dev-master",
	},
and then run from your Laravel 4 application directory:
	php composer.phar install

Or check out the git repository:

	git clone git@github.com:kenmoini/akismet.git

Or alternatively, download from Packagist:

	https://packagist.org/packages/kenmoini/akismet

# Installation

Once you have the package loaded into your application's file system, open the app/config/app.php file and add the following line to the 'providers' array:
	
	'Kenmoini\Akismet\AkismetServiceProvider',

Then, in that same file, add a new key such as the following:

	/* 
	 * Akismet API Key
	 */
	'akismet_api_key' => 'YOUR_KEY_HERE',

Don't have a key yet?  Goto the [Akismet site](https://akismet.com) and sign up for one.  It's free and takes a few moments of your time

# Usage

Before you can use Akismet, you need an Akismet API key (they are free and getting one takes about five minutes). Once you have one, let's test it with a new route.  In your routes.php file add the following:

	Route::get('testAkismet', function() {
		$apiKey = Config::get('app.akismet_api_key');
		$siteURL = Config::get('app.url');
		$akismet = new Akismet($siteURL, $apiKey);
		if ($akismet->isKeyValid()) { echo 'valid!'; } else { echo 'error!'; }
	});

And from there, load your browser to **example.com/testAkismet** (replacing _example.com_ with your domain of course).  If you see **"valid!"** then everything's installed and configured correctly and from there you can go about following the next bit of info to spam check submitted data

In an example application, one might have a simple Contact form with the inputs of *Name*, *Email Address*, *Author URL*, and *Comment Body*.  These inputs reassign to *$name*, *$email*, *$url*, and *$comment*, respectfully (after POST processing/sanitation/variable assigning/etc).
So let's submit that data to check for spammy-ness with Akismet:

	$apiKey = Config::get('app.akismet_api_key');
	$siteURL = Config::get('app.url');
	$akismet = new Akismet($siteURL ,$apiKey);
	$akismet->setCommentAuthor($name);
	$akismet->setCommentAuthorEmail($email);
	$akismet->setCommentAuthorURL($url);
	$akismet->setCommentContent($comment);
	$akismet->setPermalink('http://www.example.com/contact-form/');
	
	if($akismet->isCommentSpam())
	  // store the comment but mark it as spam (in case of a mis-diagnosis)
	else
	  // store the comment normally

That's just about it. In the event that the filter wrongly tags messages, you can at a later date create a new object and populate it from your database, overriding fields where necessary and then use the following two methods to train it:

	$akismet->submitSpam();

and

	$akismet->submitHam();

to submit mis-diagnosed spam and ham, which improves the system for everybody.

## Changelog

### Version 0.6

* Cleaned up README.md.  I know, a big change.  Tests coming next update.

### Version 0.5

* Internal testing version found operational. Deployed to GitHub
