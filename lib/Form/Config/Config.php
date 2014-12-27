<?php
/**
 * ahoi Backend Form
 * 
 * Set up the form presets. In this addon for demonstration purpose only.
 *
 * @revision 141105
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */
 
namespace ahoi\Form\Config;

use ahoi\Form\Config\Settings;

class Config
{
    const PAGE = 'ahoi_backend_form';
        
	public static function initSettings()
	{
        $settings = $settings = array(
            'hidden'             => 'This is a hidden field.',
            'textfield'          => '',
            'textarea'           => '',
            'readonly'           => '',
            'select'             => '',
            'checkbox'           => '',
            'medialistbutton'    => unserialize("a:1:{i:1;s:0:\"\";}"),
            'mediabutton'        => '',
            'linklistbutton'     => unserialize("a:1:{i:1;s:0:\"\";}"),
            'linkbutton'         => '',
            'mediatypes'         => '',
            'categorylist'       => unserialize("a:1:{i:1;s:0:\"\";}"),
        );
        
 		$file = new Settings(self::PAGE);

		return $file->initSettings($settings);
	}
}