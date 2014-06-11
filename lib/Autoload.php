<?php
/**
 * Autoload
 *
 * @version 1.0 rev 140226
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */

namespace ahoi;

class Autoload 
{
	protected static $paths = array();
	protected static $debug = FALSE;
	protected static $identifier = 'ahoi';
	protected static $registered = FALSE;
	
	/**
	 * Setters
	 */
	public static function debug($debug = FALSE)
	{
		self::$debug = (boolean) $debug;
	}
	
	public static function identifier($identifier = 'ahoi')
	{
		self::$identifier = (string) $identifier;
	}
	
	// Add a path to the search array
	public static function add($path) 
	{
		$path = realpath($path).DIRECTORY_SEPARATOR;
		if ($path && !in_array($path, self::$paths)) {
			self::$paths[] = $path;
		}
	}
	
	public static function register()
	{
		if (!self::$registered) {
			spl_autoload_register(array(__CLASS__, 'load'));
			self::$registered = TRUE;
		}
	}
	
	/**
	* Callback
	*/
	public static function load($class) 
	{
		try {
			$found = FALSE;
			
			// Remove basic name-space identifier
			$file = preg_replace('%.?'.self::$identifier.'.%U', '', $class);
			 
			// Map namespace to folder structure
			$file = str_replace('\\', DIRECTORY_SEPARATOR, $file).'.php';
			
			// Loop through registered paths
			foreach (self::$paths as $p) {
				if (is_file($p.$file)) {
					if (self::$debug) {
						echo 'Class: <pre>'.$class.'</pre>';
						echo 'Included: <pre>'.$p.$file.'</pre>';
					}
					require_once($p.$file);
					$found = TRUE;
					break;
				} 			
			}
			
			// Looped through all
			if (self::$debug && !$found) {
				echo 'FILE NOT FOUND: <pre>'.$file.'</pre>';
				echo 'Defined paths: <pre/>'.print_r(self::$paths, TRUE).'</pre>';
			}
		} catch (Exception $e) {
			echo $e->getMessage(); 
		}		
	}
}
