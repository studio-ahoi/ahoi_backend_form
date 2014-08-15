<?php
/**
 * ahoi Backend Form
 *
 * @revision 140814
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */

use ahoi\Tools\Autoload;
use ahoi\Tools\Dependencies;
use ahoi\Form\Init;

include 'compat.inc.php';

$page = 'ahoi_backend_form';

// Autoloader
Autoload::getInstance()->add(__DIR__.DS.'lib')->register();

// Check for minimum requirementss
$php_required    = '5.3';
$redaxo_required = '4.5';
$pages_required  = array();
$install         = TRUE;

$dependencies = new Dependencies($page); 
if ($install) $install = $dependencies->checkPhp($php_required);
if ($install) $install = $dependencies->checkRedaxo($redaxo_required);
if ($install) $install = $dependencies->checkAddons($pages_required);
if ($install) Init::setup($page);

$REX['ADDON']['install'][$page] = $install;