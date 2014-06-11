<?php
/**
 * Form Element
 *
 * @version 1.0 rev 131227
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */

namespace ahoi\Form\Elements;

class hidden extends ElementAbstract implements ElementInterface
{
	public function __construct($id, $attributes)
	{
		parent::__construct($id, $attributes);
	}

	public function parse()
	{
		return '<input name="'.$this->attributes['id'].'"'.$this->parseAttributes().' type="hidden" value="'.$this->value().'" />'."\n";
	}
			
	public function allowed()
	{
		return array('class', 'data', 'id', 'title');
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
