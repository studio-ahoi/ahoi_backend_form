<?php
/**
 * ahoi Backend Form
 *
 * @revision 141105
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */

use ahoi\Form\Autoload;
use ahoi\Form\Config\Dependencies;
use ahoi\Form\Config\Config;

include 'compat.inc.php';

// Autoloader
Autoload::getInstance()->add(__DIR__.DS.'lib')->register();

// Check for minimum requirementss
$php_required    = '5.3';
$redaxo_required = '4.5';
$pages_required  = array();
$install         = TRUE;

if ($install) $install = Dependencies::checkPhp(Config::PAGE, $php_required);
if ($install) $install = Dependencies::checkRedaxo(Config::PAGE, $redaxo_required);
if ($install) $install = Dependencies::checkAddons(Config::PAGE, $pages_required);

$REX['ADDON']['install'][Config::PAGE] = $install;