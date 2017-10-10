<?php

// Include the i18n class
require_once 'i18n.php';
$i18n = new i18n();

// Set connection settings
$i18n->Username = 'USERNAME';
$i18n->Password = 'PASSWORD';
$i18n->Database = 'DATABASE';

$i18n->NoFallback(true);  # Why would you do this?!
$i18n->ErrorLog(false);  # Fortunately, this is just an example.
?>

<p>Current language: <?= $i18n->getLanguage(); ?></p>
<p>Fallback language: <?= $i18n->getFallbackLanguage(); ?></p>
<hr>
<p><?= $i18n->msg('hello_world'); ?></p>

<!-- Let's assume that 'hello' returns "Hello %s, nice to meet you! My name is %s." -->
<p><?= $i18n->msg('hello', array('Tom', 'Sarah')); ?></p>