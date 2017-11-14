# i18n PHP class v1.3
This class is a simple and very basic tool to make your website available in multiple languages using a MySQL 
database. No special functions, just the basics.

## Setup

### The database
You need at least one table with the columns _id_ and _YOUR_LANGUAGE_CODE_ named `YOUR_TABLE`:

```
CREATE TABLE `YOUR_TABLE` (
  `id` text COLLATE utf8_unicode_ci NOT NULL,
  `YOUR_LANGUAGE_CODE` text COLLATE utf8_unicode_ci NOT NULL,
  `ANOTHER_LANGUAGE_CODE` text COLLATE utf8_unicode_ci NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```

To add more languages, simply create another row in your database named `ANOTHER_LANGUAGE_CODE`.

### Implementation in your code
```php
<?php
/*
 * First of all, include the class and set $username, $password and $database.
 * $host is optional, 'localhost' will be used as a default.
 */
require_once 'path/to/i18n.php';
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
?>
```

### Get a message
```php
<?php
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
?>
```

## License
This project is licensed under the MIT License. View [LICENSE.txt](/LICENSE.txt) for more information.

## You're ready!
Perfect Now you can use the class to get any string out of your MySQL database and into your code.
Have fun and please make sure to report bugs and errors if you catch one.
Let me know if you find this class useful, I'd like to know how to improve my work.

Best regards,
Thomas

Oh, and: Fork it! :)