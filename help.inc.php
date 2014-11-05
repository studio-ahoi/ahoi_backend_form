<?php
/**
 * ahoi Backend Form
 *
 * @revision 141105
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */

use ahoi\Form\Autoload;
use ahoi\Form\Page\Help;

// Autoloader
Autoload::getInstance()->add(__DIR__.DS.'lib')->register();

// Page
$help = new Help();
$help->run(FALSE);
