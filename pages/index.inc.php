<?php
/**
 * ahoi Backend Form
 *
 * @version 1.1 rev 140526
 * @author Daniel Weitenauer
 * @copyright 2014 studio ahoi
 */

$page = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
if (!$subpage) {
	$subpage = 'demo'; // Because rex_request doesn't overwrite existing but empty keys
}

include $REX['INCLUDE_PATH'].DIRECTORY_SEPARATOR.'layout'.DIRECTORY_SEPARATOR.'top.php';
echo rex_title($REX['ADDON']['name'][$page], $REX['ADDON']['pages'][$page]);
include $REX['INCLUDE_PATH'].DIRECTORY_SEPARATOR.'addons'.DIRECTORY_SEPARATOR.$page.DIRECTORY_SEPARATOR.'pages'.DIRECTORY_SEPARATOR.$subpage.'.inc.php';
include $REX['INCLUDE_PATH'].DIRECTORY_SEPARATOR.'layout'.DIRECTORY_SEPARATOR.'bottom.php';