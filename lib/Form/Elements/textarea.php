<?php
/**
 * Form Element
 *
 * @version 1.0 rev 131227
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */

namespace ahoi\Form\Elements;

class textarea extends ElementAbstract implements ElementInterface
{
	public function __construct($id, $attributes)
	{
		parent::__construct($id, $attributes);
	}

	public function parse()
	{
		$output = '<div class="rex-form-row">';
		$output.= '    <p class="rex-form-text">'."\n";
        $output.=          $this->label();
		$output.= '        <textarea name="'.$this->attributes['id'].'"'.$this->parseAttributes().' type="text">'.htmlentities($this->value()).'</textarea>'."\n";
		$output.= '    </p>'."\n";
        $output.=      $this->description();
        $output.= '</div>'."\n";

		return $output;
	}
			
	public function allowed()
	{
		return array('class', 'data', 'hidden', 'id', 'style', 'tabindex', 'title', 'rows', 'cols');
	}
	
	public function post()
	{
		return rex_post($this->id, 'string', $this->attributes['value']);
	}
	
	public function get()
	{
		return rex_get($this->id, 'string', $this->attributes['value']);
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
