<?php
/**
 * Form element Element
 *
 * @revision 131227
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */

namespace ahoi\Form\Elements;

abstract class ElementAbstract implements ElementInterface
{
	protected $id;
	protected $attributes = array();
	protected $value;
	protected $method = 'post';
	
	protected $debug;
	
	public function __construct($id, $attributes)
	{
		$this->id = $id;
		$this->attributes = $attributes;
	}
    
	
    /**
     * Abstracts
     */
	abstract public function parse();
	abstract public function allowed();
	abstract public function post();
	abstract public function get();
	abstract public function submission();
	abstract public function validate();


	/**
	 * Getter
	 */
	public function id()
	{
		return $this->id;
	}
	
	public function attributes()
	{
		return $this->attributes;
	}
	
	public function value()
	{
		if (!isset($this->value)) $this->populate();
		return $this->value;
	}
	
	protected function page()
	{
		return rex_request('page', 'string');
	}

	/**
	 * Setter, chainable
	 */
	public function debug($debug)
	{
		$this->debug = (boolean) $debug;
		return $this;
	}
	public function method($method = 'post')
	{
		if (in_array($method, array('post', 'get'))) {
			$this->method = $method;
		} else {
			echo rex_warning('Wrong method "'.$method.'" for element.');
		}
		return $this;
	}
	
	/**
	 * Parser
	 */
	public function parseAttributes()
	{
		$output = '';
		foreach ($this->attributes as $k => $a) {
			if (in_array($k, $this->allowed())) {
				$output.= ' '.$k.'="'.$a.'"';
			}
		}
		return $output;
	}
	
	public function label()
	{
		return isset($this->attributes['label']) ? '<label for="'.$this->attributes['id'].'">'.$this->attributes['label'].'</label>'."\n" : '';
	}
    
    public function description()
    {
          return isset($this->attributes['description']) ? '<p class="rex-form-description"><label></label><span>'.$this->attributes['description'].'</span></p>'.PHP_EOL : '';
    }

	
	public function populate()
	{
		switch ($this->method) {
			case 'get':
				if (count($_GET) > 0) {
					$this->value = $this->get();
				} else {
					$this->value = $this->attributes['value'];
				}
				break;
			case 'post':
			default:
				if (count($_POST) > 0) {
					$this->value = $this->post();
				} else {
					$this->value = $this->attributes['value'];
				}
				break;
		}
		
		return $this->value;
	}
}
