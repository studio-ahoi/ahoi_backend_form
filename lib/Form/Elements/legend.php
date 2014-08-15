<?php
/**
 * Form
 *
 * @revision 140526
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */

namespace ahoi\Form\Elements;

class legend extends ElementAbstract implements ElementInterface
{
	public function __construct($id, $attributes)
	{
		parent::__construct($id, $attributes);
	}

	public function parse()
	{
		if (!array_key_exists('class', $this->attributes)) {
            $this->attributes['class'] = '';
        }
		$this->attributes['class'] .= ' rex-form-row'; 

		return '<legend'.$this->parseAttributes().'>'.$this->attributes['label'].'</legend>'."\n";
	}
			
	public function allowed()
	{
		return array('class', 'data', 'hidden', 'id', 'style', 'tabindex', 'title');

	}
	
	public function post()
	{
		return $this->attributes['label'];
	}
	public function get()
	{
		return $this->attributes['label'];
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
