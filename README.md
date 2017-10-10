# i18n PHP class
This class is a simple and very basic tool to make your website available in multiple languages using a MySQL 
database. No special functions, just the basics. It automatically detects the correct language either because of 
SESSION- or GET parameters or via the domain.

## Setup

### The database
You need at least one table with the columns _id_ and _message_ named `lang_EN`:

```
CREATE TABLE `lang_EN` (
  `id` text COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```

To add more languages, simply create a new table in your database named `lang_NEWLANGUAGE` with the columns 
_id_ and _message_.

### Implementation in your code
```php
<?php
/*
 * First of all, include the class and set $username, $password and $database.
 * $host is optional, 'localhost' will be used as a default.
 */
require_once '/path/to/i18n.php';
$i18n = new i18n('USERNAME', 'PASSWORD', 'DATABASE', 'HOST (OPTIONAL)');

/*
 * Now you are ready to go! But you can still change some settings if you want to.
 * The following settings are available:
 *
 * - $i18n->Fallback = 'DE';                            Set another fallback language. Default: EN.
 * - $i18n->NoFallback(true);                           Disable the usage of a fallback language at all. Default: false.
 * - $i18n->ErrorLog(false);                            Disable error logging. Default: true.
 * - $i18n->Webmaster = 'webmaster@example.com';        Set a webmaster email address for error logging.
 */
$i18n->Fallback = 'DE';
$i18n->Webmaster = 'webmaster@example.com';
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
 * If you have one or more placeholder named %s in your string, create an array as the second value and insert the
 * strings you want to replace the placeholder with. But be careful, the order is important!
 */
echo $i18n->msg('hello_world');                    // Outputs 'Hello world.' in our example.
echo $i18n->msg('my_name', 'Thomas');      // Outputs 'My name is Thomas.' in our example.
?>
```

### Language detection
The following parameters tell the class in which language the message should be returned:
1. `$_GET['lang']`
2. `$_SESSION['lang']`
3. The domain (e.g. _de.example.com_ or _fr.example.com_)
4. Fallback language

`msg()` then selects the table using the detected language and returns the translated message.
If no message was found it then returns the message in the fallback language. To disable the fallback 
language, use `$i18n->NoFallback(true);`.

## License
This project is licensed under the MIT License. View [LICENSE.txt](/LICENSE.txt) for more information.

## You're ready!
Perfect Now you can use the class to get any string out of your MySQL database and into your code.
Have fun and please make sure to report bugs and errors if you catch one.
Let me know if you find this class useful, I'd like to know how to improve my work.

Best regards,
Thomas

Oh, and: Fork it! :)