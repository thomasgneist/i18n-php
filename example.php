<?php

// Include the i18n class
require_once 'i18n.php';
$i18n = new i18n();

// Set connection settings
$i18n->Host = '12.345.67.89';
$i18n->Username = 'USERNAME';
$i18n->Password = 'PASSWORD';
$i18n->Database = 'DATABASE';

// Additional settings
$i18n->Host = 'example.com';                    # Default: 'localhost'
$i18n->Fallback = 'DE';                         # Default: 'EN'
$i18n->ErrorLog(true);
$i18n->Webmaster = 'webmaster@example.com';
?>

<p>Current language: <?= $i18n->getLanguage(); ?></p>
<p>Fallback language: <?= $i18n->getFallbackLanguage(); ?></p>
<hr>
<p><?= $i18n->msg('hello_world'); ?></p>