Sticky Notes pastebin v0.4
===========================

AWT is an automatic web testing utility that will help you test the basic site
features whenever you install an upgrade.

AWT (Automatic Web Testing Utility) is licensed under the BSD license. For
details, check the repository here: https://github.com/sayakb/awt-util

**IMPORTANT: Do not run AWT in your Sticky Notes production environment!!**


Testing
========

Step 1 - Set up AWT
--------------------
You must run this utility in your local server only. Copy the awt-utils folder
to your web root folder. You must have Apache and PHP set up on your machine.

Step 2 - Importing the template
--------------------------------
Open AWT in your browser and click on 'Click to browse...'
Select the template and click 'Import'

Step 3 - Disable the anti-spam filters
---------------------------------------
Sticky Notes comes with anti-spam filters that prevent creation of automated
pastes. Please disable the 'noflood' and 'token' services from the Site
Configuration section of Sticky Notes admin panel.

Step 4 - Setting the credentials
---------------------------------
In order to test the admin functions, you need to enter the admin credentials and
a name of a temp user. To do that, click on the 'Test metadata' button on the
test profile (meta 1 and 2 are the admin username and password, meta 3 is the
temp username). AWT will use meta 1 and 2 to login to your Sticky Notes admin
panel, so it must be a valid username/password combination. Meta 3 should be a
username that does NOT exist in your Sticky Notes setup. AWT will use meta 3 to
test the Sticky Notes user module. Since AWT stores the credentials as plaintext,
it is recommended that you create a separate admin user for testing purposes.

Step 5 - Preparing and running the tests
-----------------------------------------
Adjust the 'Base URL' value to point to your sticky-notes installation path.
Once done, simply click 'Run tests'
