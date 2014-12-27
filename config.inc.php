<?php
/**
 * ahoi Backend Form
 *
 * @revision 141105
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */

use ahoi\Form\Autoload;
use ahoi\Form\Config\Config;

include 'compat.inc.php';

// Autoloader
Autoload::getInstance()->add(__DIR__.DS.'lib')->register();

// Configuration
$REX['ADDON']['rxid'][Config::PAGE]        = Config::PAGE;
$REX['ADDON']['page'][Config::PAGE]        = Config::PAGE;
$REX['ADDON']['version'][Config::PAGE]     = '1.3';
$REX['ADDON']['author'][Config::PAGE]      = 'Daniel Weitenauer, studio ahoi';
$REX['ADDON']['supportpage'][Config::PAGE] = 'www.studio-ahoi.de';

// Permissions
$REX['ADDON']['perm'][Config::PAGE] = $REX['PERM'][] = Config::PAGE.'[]';

if (!$REX['SETUP']) {
    // Backend
    if ($REX['REDAXO']) {
        // Translation
        $I18N->appendFile(__DIR__.DS.'lang');
        // Title
        $REX['ADDON']['name'][Config::PAGE] = $I18N->msg(Config::PAGE.'_title');
        // Pages
        $REX['ADDON']['pages'][Config::PAGE][] = array('', $I18N->msg(Config::PAGE.'_demo'));
        $REX['ADDON']['pages'][Config::PAGE][] = array('', $I18N->msg(Config::PAGE.'_help'));
        
        // Setup
        Config::initSettings();
    }
    // Frontend
    else {	
    }
}