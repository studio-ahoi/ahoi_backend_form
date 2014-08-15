<?php
/**
 * Form Element
 *
 * @revision 131227
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */

namespace ahoi\Form\Elements;

class readonly extends ElementAbstract implements ElementInterface
{
	public function __construct($id, $attributes)
	{
		parent::__construct($id, $attributes);
	}

	public function parse()
	{
		$output = '<div class="rex-form-row">';
		$output.= '    <p class="rex-form-text">'."\n";
        $output.=          isset($this->attributes['label']) ? $this->label() : '<label></label>';
		$output.= '        <span name="'.$this->attributes['id'].'"'.$this->parseAttributes().'>'.$this->value().'</span>'."\n";
		$output.= '    </p>'."\n";
        $output.=      $this->description();
        $output.= '</div>'."\n";

		return $output;
	}
			
	public function allowed()
	{
		return array('class', 'data', 'hidden', 'id', 'style', 'tabindex', 'title');
	}
	
	public function post()
	{
		return $this->attributes['value'];
	}
	public function get()
	{
		return $this->attributes['value'];
	}
	
	public function validate()
    {
        // todo...
    }
    
	public function submission()
    {
        return FALSE; // Do not store data
    }
}
