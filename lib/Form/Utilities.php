<?php
/**
 * ahoi Backend Form
 *
 * @version	1.1 rev 140526
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */
 
namespace ahoi\Form;

use ahoi\Tools\Settings;

class Utilities
{
	protected static $prefix;

	public static function prefix($prefix)
	{
		self::$prefix = $prefix;
	}
	
	public static function initSettings()
	{
		$file = new Settings(self::$prefix);

        $settings = array(
            'additional_widgets' => '',
			'hidden' => 'This is a hidden field.',
		    'textfield' => '',
			'textarea' => '',
			'readonly' => '',
			'select' => '',
			'checkbox' => '',
            'medialistbutton' => unserialize("a:1:{i:1;s:0:\"\";}"),
			'mediabutton' => '',
			'linklistbutton' => unserialize("a:1:{i:1;s:0:\"\";}"),
			'linkbutton' => '',
			'mediatypes' => '',
			'categorylist' => unserialize("a:1:{i:1;s:0:\"\";}"),
        );

        if ($file->fileExists()) {
		    $return = $file->loadSettings();
        } else {
		    $return = $file->initSettings($settings);
        }
        
		return $return;
	}
}