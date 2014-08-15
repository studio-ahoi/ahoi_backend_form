<?php
/**
 * Form Element
 *
 * @revision 131227
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */

namespace ahoi\Form\Elements;

class linkbutton extends ElementAbstract implements ElementInterface
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

		$output = '<div class="rex-form-row">';
		$output.=      $this->label();
		$output.=      \rex_var_link::getLinkButton(self::$button_id, $this->value, $category_id);
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
		$post = rex_post('LINK', 'array', array());
		return isset($post[self::$button_id]) ? $post[self::$button_id] : $this->attributes['value'];
	}
	public function get()
	{
		$post = rex_get('LINK', 'array', array());
		return isset($post[self::$button_id]) ? $post[self::$button_id] : $this->attributes['value'];
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
