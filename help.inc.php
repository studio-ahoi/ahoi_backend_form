<?php
/**
 * ahoi Backend Form
 *
 * @revision 140805
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */

use ahoi\Tools\Autoload;
use ahoi\Tools\Page\Help;

include 'compat.inc.php';

$page = 'ahoi_backend_form';

// Autoloader
Autoload::getInstance()->add(__DIR__.DS.'lib'.DS.'Form')->register();


// Page
$help = new Help();
$help->run(FALSE);
