<?php
/**
 * Form Element
 *
 * @revision 131227
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */

namespace ahoi\Form\Elements;

class mediabutton extends ElementAbstract implements ElementInterface
{
	static protected $button_id = 1;
	
	public function __construct($id, $attributes)
	{
		parent::__construct($id, $attributes);
	}

	public function parse()
	{
		if (isset($this->attributes['category'])) {
			$category_id = $this->attributes['category'];  
		} else {
			$category_id = '';  
        }
        
        $args = array();
        $button = \rex_var_media::getMediaButton(self::$button_id, $category_id, $args);
        $button = str_replace('REX_MEDIA['.self::$button_id.']', $this->value, $button);
		
		$output = '<div class="rex-form-row">';
		$output.=      $this->label();
		$output.=      $button;
        $output.=      $this->description();
        $output.= '</div>'."\n";

		self::$button_id++;     

		return $output;
	}
			
	public function allowed()
	{
		return array();
	}
	
	public function post()
	{
		$post = rex_post('MEDIA', 'array', array());
		return array_key_exists(self::$button_id, $post) ? $post[self::$button_id] : $this->attributes['value'];
	}
	public function get()
	{
		$post = rex_get('MEDIA', 'array', array());
		return array_key_exists(self::$button_id, $post) ? $post[self::$button_id] : $this->attributes['value'];
	}
    
	public function validate()
    {
        // todo...
    }
    
	public function submission()
    {
        return TRUE; // Store data
    }
}
