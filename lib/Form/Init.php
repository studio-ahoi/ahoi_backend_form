<?php
/**
 * ahoi Backend Form
 * 
 * Set up the form presets. In this addon for demonstration purpose only.
 *
 * @revision 140814
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */
 
namespace ahoi\Form;

use ahoi\Tools\Settings;

class Init
{
	public static function setup($page)
	{
		$file = new Settings($page);

        $settings = array(
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

		return $file->initSettings($settings);
   	}
}