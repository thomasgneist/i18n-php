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

### Required code
```php
<?php
 require_once 'path/to/i18n.php';
 $i18n = new i18n();
 
 $i18n->Username = 'USERNAME';
 $i18n->Password = 'PASSWORD';
 $i18n->Database = 'DATABASE';
?>
```

### Additional settings
```php
<?php
 $i18n->Host = 'example.com';                    # Default: 'localhost'
 $i18n->Fallback = 'DE';                         # Default: 'EN'
 $i18n->NoFallback(true);                        # Default: 'false' (Disable fallback language)
 $i18n->ErrorLog(false);                         # Default: 'true' (Disable error logging)
 $i18n->Webmaster = 'webmaster@example.com';
?>
```

## Get a message
To get a message from the database, use `msg($key)`. It automatically selects the right table and returns the 
message. E.g.:

```php
<?php
 echo $i18n->msg('hello_world');
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