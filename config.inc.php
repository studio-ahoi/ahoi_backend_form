<?php
/**
 * ahoi Backend Form
 *
 * @revision 140814
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */

use ahoi\Tools\Autoload;
use ahoi\Form\Init;

include 'compat.inc.php';

$page = 'ahoi_backend_form';

// Autoloader
Autoload::getInstance()->add(__DIR__.DS.'lib')->register();

// Configuration
$REX['ADDON']['rxid'][$page]        = $page;
$REX['ADDON']['page'][$page]        = $page;
$REX['ADDON']['version'][$page]     = '1.2';
$REX['ADDON']['author'][$page]      = 'Daniel Weitenauer, studio ahoi';
$REX['ADDON']['supportpage'][$page] = 'www.studio-ahoi.de';

// Permissions
$REX['ADDON']['perm'][$page] = $REX['PERM'][] = $page.'[]';

if (!$REX['SETUP']) {
    // Backend
    if ($REX['REDAXO']) {
        // Translation
        $I18N->appendFile(__DIR__.DS.'lang');
        // Title
        $REX['ADDON']['name'][$page] = $I18N->msg($page.'_title');
        // Pages
        $REX['ADDON']['pages'][$page][] = array('', $I18N->msg($page.'_demo'));
        
        // Setup
        Init::setup($page);
    }
    // Frontend
    else {	
    }
}