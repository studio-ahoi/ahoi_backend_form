<?php
/**
 * Form Element
 *
 * @version 1.01 rev 140526
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */

namespace ahoi\Form\Elements;

class medialistbutton extends ElementAbstract implements ElementInterface
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
        
        $values = htmlspecialchars(implode(',', $this->value));
        $args = array(); // URl arguments
        
		$output = '<div class="rex-form-row">';
		$output.=      $this->label();
		$output.=      \rex_var_media::getMediaListButton(self::$button_id, $values, $category_id, $args);
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
		return rex_post('MEDIALIST', 'array', $this->attributes['value']);
	}
	public function get()
	{
		return rex_get('MEDIALIST', 'array', $this->attributes['value']);
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
