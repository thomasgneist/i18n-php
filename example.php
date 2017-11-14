<?php
/*
 * First of all, include the class and set $username, $password and $database.
 * $host is optional, 'localhost' will be used as a default.
 */
require_once 'i18n.php';
$i18n = new i18n('USERNAME', 'PASSWORD', 'DATABASE', 'HOST (OPTIONAL)');

/*
 * Now you are ready to go! But you can still change some settings if you want to.
 * The following settings are available:
 *
 * - $i18n->enableFallbackLanguage(true);               Enable or disable the usage of the fallback language. Default: true
 * - $i18n->setLangDetectionType('session');            Set the language detection type. Available: cookie, domain, fallback, session. Default: cookie
 * - $i18n->setDatabaseTable('web_messages');           Set the table's name in the MySQL database. Default: messages
 * - $i18n->setFallbackLanguage('de');                  Set the fallback language. Default: en
 */
$i18n->setLangDetectionType('session');
$i18n->setDatabaseTable('web_messages');
$i18n->setFallbackLanguage('de');

/*
 * Alright, everything set up!
 * Let's see which settings apply to us.
 */
echo $i18n->getLanguage();                             // Depends on the user.
echo $i18n->getFallbackLanguage();                     // Language set with $i18n->Fallback.

/*
 * Time for some messages!
 * Use $i18n->msg('ID') to get a message from the database.
 * If you have one or more placeholder (%s) in your string, create an array as the second value and insert the
 * strings you want to replace the placeholder with. But be careful, the order is important!
 */
echo $i18n->msg('hello_world');                    // Outputs 'Hello world.' in our example.
echo $i18n->msg('my_name', 'Thomas');      // Outputs 'My name is Thomas.' in our example.

/*
 * Perfect! Now you can use the class to get any string out of your MySQL database and into your code.
 * Have fun and please make sure to report bugs and errors on GitHub if you catch one.
 * Let me know if you find this class useful, I'd like to know how to improve my work.
 *
 * Best regards,
 * Thomas
 */