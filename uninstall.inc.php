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
 
$REX['ADDON']['install'][Config::PAGE] = FALSE;