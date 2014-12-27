<?php
/**
 * Preserve compatibility with Addon '_ahoi_tools'
 * 
 * @revision 141105
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */

// Shortcut for convenience
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
// Autoloader
if (!class_exists('ahoi\Form\Autoload')) require_once (__DIR__.DS.'lib'.DS.'Form'.DS.'Autoload.php');
