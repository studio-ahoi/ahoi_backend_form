<?php
/**
 * Preserve compatibility with Addon '_ahoi_tools'
 * for standalone version
 * 
 * @revision 140815
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */

use ahoi\Tools\Autoload;

if (!defined('AHOI_TOOLS')) {
    // Shortcut for convenience
    if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
    // Autoloader
    if (!class_exists('ahoi\Tools\Autoload')) require_once (__DIR__.DS.'vendor'.DS.'Tools'.DS.'Autoload.php');
    Autoload::getInstance()->add(__DIR__.DS.'vendor')->register();
} 