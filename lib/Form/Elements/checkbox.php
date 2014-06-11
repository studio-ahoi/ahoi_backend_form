<?php
/**
 * Form Element
 *
 * @version 1.0 rev 131227
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */

namespace ahoi\Form\Elements;

class checkbox extends ElementAbstract implements ElementInterface
{
	public function __construct($id, $attributes)
	{
		parent::__construct($id, $attributes);
	}

	public function parse()
	{
		if (!array_key_exists('class', $this->attributes)) $this->attributes['class'] = '';
		$this->attributes['class'] .= ' rex-form-checkbox'; 

		$output = '<div class="rex-form-row">';
		$output.= '    <p class="rex-form-col-a rex-form-checkbox rex-form-label-right">'."\n";
		$output.= '        <input name="'.$this->attributes['id'].'"'.$this->parseAttributes().' type="checkbox" value="1" '.($this->value() == 1 ? 'checked="checked"' : '').'/>'."\n";
        $output.=          $this->label();
		$output.= '    </p>'."\n";
        $output.=      $this->description();
        $output.= '</div>'."\n";

		return $output;
	}
			
	public function allowed()
	{
		return array('class', 'data', 'hidden', 'id', 'multiple', 'style', 'tabindex', 'title');
	}
	
	public function post()
	{
		return rex_post($this->id(), 'int', 0);
	}
	public function get()
	{
		return rex_get($this->id(), 'int', 0);
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
