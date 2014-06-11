<?php
/**
 * ahoi Backend Form
 *
 * @version	1.1 rev 140526
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */
 
use ahoi\Autoload;
use ahoi\Form\Utilities;

$page = 'ahoi_backend_form';

// Autoloader
if (!class_exists('ahoi\Autoload')) {
	require_once (__DIR__.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Autoload.php');
}
Autoload::add(__DIR__.DIRECTORY_SEPARATOR.'lib');
Autoload::register();

// Settings
Utilities::prefix($page);
Utilities::initSettings();

// Translation
if (isset($I18N)) {
	$I18N->appendFile(__DIR__.DIRECTORY_SEPARATOR.'lang');
    $REX['ADDON']['name'][$page] = $I18N->msg($page.'_title');
}

// Configuration
$REX['ADDON']['rxid'][$page] = $page;
$REX['ADDON']['page'][$page] = $page;
$REX['ADDON']['version'][$page] = '1.1';
$REX['ADDON']['author'][$page] = 'Daniel Weitenauer - studio ahoi';
$REX['ADDON']['supportpage'][$page] = 'www.studio-ahoi.de';

// Permissions
$REX['ADDON']['perm'][$page] = $REX['PERM'][] = $page.'[]';

// Backend
if ($REX['REDAXO']) {
		// Pages
		$REX['ADDON']['pages'][$page][] = array('', $I18N->msg($page.'_demo'));
}
// Frontend
else {	
}