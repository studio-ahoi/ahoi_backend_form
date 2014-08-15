<?php
/**
 * Dependencies
 *
 * @revision 140805
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */

namespace ahoi\Tools;

class Dependencies 
{
	public function __construct($page) 
	{
		$this->page = $page;
	}
	
	/**
	 * System checks
	 */
	public function checkPhp($php_required)
	{
		global $REX;
		
		$install = TRUE;
		if (version_compare(PHP_VERSION, $php_required, '<')) {
			$REX['ADDON']['installmsg'][$this->page] = 'The Addon "'.$this->page.'" requires PHP Version '.$php_required.' or higher. Currently installed version: '.PHP_VERSION.'.';
			$install = FALSE;
		}
        
		return $install;
	}

	public function checkRedaxo($redaxo_required)
	{
		global $REX;
        
		$install = TRUE;
		if ($install && version_compare($REX['VERSION'].'.'.$REX['SUBVERSION']/*.'.'.$REX['MINORVERSION']*/, $redaxo_required) < 0) {
			$REX['ADDON']['installmsg'][$this->page] = 'The addon "'.$this->page.'" requires Redaxo version '.$redaxo_required.' or higher. Currently installed version: '.$REX['VERSION'].'.'.$REX['SUBVERSION'].'.'.$REX['MINORVERSION'].'.';
			$install = FALSE;
		}
        
		return $install;
	}

	public function checkAddons($addons_required)
	{
		global $REX;
		
		$install = TRUE;
		foreach ($addons_required as $a) {
			if (!\OOAddon::isActivated($a['name']) || \OOAddon::getVersion($a['name']) < $a['version']) {
				$REX['ADDON']['installmsg'][$this->page] = 'The addon "'.$this->page.'" requires the addon "'.$a['name'].'" version '.$a['version'].' to be installed and activated.';
				$install = FALSE;
			}
		}
        
		return $install;
	}
}