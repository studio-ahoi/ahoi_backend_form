<?php
/**
 * ahoi Backend Form
 *
 * @revision 140805
 * @author Daniel Weitenauer
 * @copyright 2014 studio ahoi
 */

$page = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
if (!$subpage) $subpage = 'demo'; // Because rex_request doesn't overwrite existing but empty keys

include $REX['INCLUDE_PATH'].DS.'layout'.DS.'top.php';
echo rex_title($REX['ADDON']['name'][$page], $REX['ADDON']['pages'][$page]);
include $REX['INCLUDE_PATH'].DS.'addons'.DS.$page.DS.'pages'.DS.$subpage.'.inc.php';
include $REX['INCLUDE_PATH'].DS.'layout'.DS.'bottom.php';