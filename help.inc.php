<?php
/**
 * ahoi Backend Form
 *
 * @revision 141227
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */

use ahoi\Tools\Autoload;

if (!defined('AHOI_TOOLS')) {
    die ('Addon <strong>_ahoi_tools</strong> is required for Addon <strong>ahoi_news</strong>!');
}

// Autoloader
require_once (AHOI_TOOLS.DS.'lib'.DS.'Tools'.DS.'Autoload.php');
Autoload::getInstance()->add(__DIR__.DS.'lib')->register();

include 'pages/help.inc.php';
