<?php
/**
 * Form Element
 *
 * @revision 131227
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */

namespace ahoi\Form\Elements;

class select extends ElementAbstract implements ElementInterface
{
	public function __construct($id, $attributes)
	{
		parent::__construct($id, $attributes);
	}

	public function parse()
	{
		$value = $this->value();
		
		if (array_key_exists('multiple', $this->attributes)) {
			$type = '[]';
		} else {
			$type = '';
		} 
		
		if (!array_key_exists('class', $this->attributes)) $this->attributes['class'] = '';
		$this->attributes['class'] .= ' rex-form-select'; 

		$output = '<div class="rex-form-row">';
		$output.=      $this->label();
		$output.= '    <select name="'.$this->attributes['id'].$type.'"'.$this->parseAttributes().'>'."\n";
        foreach($this->attributes['options'] as $o => $t) {
			$s = '';
			if ((is_array($value) && count($value) > 0 && in_array($o, $value)) || $value == $o) {
				$s = ' selected="selected"'; 
			}
			$output.= '<option value="'.$o.'"'.$s.'>'.$t.'</option>'."\n";
		} 
		$output.= '    </select>'."\n";
        $output.=      $this->description();
        $output.= '</div>'."\n";

		return $output;
	}
			
	public function allowed()
	{
		return array('class', 'data', 'hidden', 'id', 'multiple', 'size', 'style', 'tabindex', 'title');
	}
	
	public function post()
	{
		if (array_key_exists('multiple', $this->attributes)) {
			$type = 'array';
		} else {
			$type = 'string';
		} 
		
		return rex_post($this->id, $type, $this->attributes['value']);
	}
	public function get()
	{
		if (array_key_exists('multiple', $this->attributes)) {
			$type = 'array';
		} else {
			$type = 'string';
		} 
		
		return rex_get($this->id, $type, $this->attributes['value']);
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
