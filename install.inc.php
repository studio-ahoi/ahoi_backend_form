<?php
/**
 * ahoi Backend Form
 *
 * @version	1.0 rev 140503
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */

use ahoi\Autoload;
use ahoi\Tools\Dependencies;
use ahoi\Form\Utilities;

$page = 'ahoi_backend_form';

// Autoloader
if (!class_exists('ahoi\Autoload')) {
	require_once (__DIR__.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Autoload.php');
}
Autoload::add(__DIR__.DIRECTORY_SEPARATOR.'lib');
Autoload::register();

// Check for minimum requirementss
$php_required = '5.3';
$redaxo_required = '4.5';
$pages_required = array();

$install = TRUE;
$dependencies = new Dependencies($page); 
if ($install) $install = $dependencies->checkPhp($php_required);
if ($install) $install = $dependencies->checkRedaxo($redaxo_required);
if ($install) $install = $dependencies->checkAddons($pages_required);

if ($install) {
	Utilities::prefix($page);
    Utilities::initSettings();
}

$REX['ADDON']['install'][$page] = $install;