<?php
/**
 * ahoi Backend Form
 *
 * @version	1.0 rev 140526
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */

use ahoi\Autoload;
use ahoi\Pages\Help;

$page = 'ahoi_backend_form';

// Autoloader
if (!class_exists('ahoi\Autoload')) {
	require_once (__DIR__.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Autoload.php');
}
Autoload::add(__DIR__.DIRECTORY_SEPARATOR.'lib');
Autoload::register();

/**
 * Page
 */
$help = new Help();
$help->run(FALSE);
