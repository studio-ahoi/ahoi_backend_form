<?php
/**
 * Form class
 *
 * @version 1.0 rev 131227
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */

namespace ahoi\Form;

class Form 
{
	protected $form_id;
	protected $page;
	protected $subpage = 'settings';
	protected $action = 'index.php';
	protected $method = 'post';
	protected $submit = 'Senden';
	protected $abort = '';

	protected $debug = FALSE;

	protected $fieldsets = array();
	protected $active_fieldset; // The fieldset the next elements are added to 
	
	protected $values = array();
	
	public function __construct($form_id = NULL)
	{
		if (isset($form_id)) $this->id($form_id);
	}
	
	/**
	 * Setters, chainable
	 */	
	public function debug($debug)
	{
		$this->debug = (boolean) $debug;
		return $this;
	}
	
	public function id($form_id)
	{
		$this->form_id = $form_id;
		return $this;
	}
	
	public function page($page)
	{
		$this->page = $page;
		return $this;
	}
	
	public function subpage($subpage = 'settings')
	{
		$this->subpage = $subpage;
		return $this;
	}
	
	public function action($action = 'index.php')
	{
		$this->action = $action;
		return $this;
	}
	
	public function method($method = 'post')
	{
		if (in_array($method, array('post', 'get'))) {
			$this->method = $method;
		} else {
			die('ERROR IN '.__METHOD__.': Wrong method "'.$method.'" for form.');
		}
		return $this;
	}
	
	public function submit($submit = 'Senden')
	{
		$this->submit = $submit;
		return $this;
	}
	
	public function abort($abort = 'Abbrechen')
	{
		$this->abort = $abort;
		return $this;
	}
	
	/**
	 * Add a form-fieldset, chainable
	 */
	public function fieldset($id, $legend = '', $class = '')
	{
		global $I18N;
		
		if(!array_key_exists($id, $this->fieldsets)) {
			$this->fieldsets[$id] = array(
				'legend' => $legend,
				'class' => $class,
				'elements' => array(),
			);
		}
		$this->active_fieldset = $id;
		
		return $this;
	}
	
	/**
	 * Add a form-element, chainable
	 *
	 * @param $id			ID of the form-element
	 * @param $element		Type of the element
	 * @param $fieldsetId	ID of the fieldset the element is attached to. This way, elements don't have to be attached in an sequential order
	 * @param $attributes	Array of attributes of the element. There may be diferences between the elements.
	 */
	public function element($element, $id, $attributes = array())
	{
        // Linux filesystem is case-sensistive
        $element = strtolower($element);
        
		if (!isset($this->active_fieldset)) {
			die('ERROR IN '.__METHOD__.': Missing active fieldset id for element '.$element.' with id '.$id.'.');
		}
		$fieldset_id = $this->active_fieldset;

		try {				
			$class = __NAMESPACE__.'\\Elements\\'.$element; // Variable class names always have to be fully qualified
			if ($this->debug) {
				echo 'Form element: '.$class."<br/>\n";
			}
			
			// Add element to fieldset
			$attributes['id'] = $id;
			$new_element = new $class($id, $attributes);
			$new_element->debug($this->debug)->method($this->method);
			$this->fieldsets[$fieldset_id]['elements'][] = $new_element;
		} catch(Exception $e) { 
			throw new Exception('ERROR IN '.__METHOD__.', constructing form element <pre>'.$class.'</pre>: '.$e->getMessage().'.'); 
		}
		return $this;
	}
	
	/**
	 * Get the posted values
	 */
	public function submission()
	{
		if ($this->debug) {
			echo 'Post: <pre>'.print_r($_POST, TRUE).'</pre>';
		}

		$values = array();
		foreach ($this->fieldsets as $fk => $fv) {
			// Exclude form params
			if ($fk == 'form-params') continue;
			
			// Get values
			foreach ($fv['elements'] as $e) {
				if ($e->submission()) $values[$e->id()] = $e->value();
			}
		}
		if ($this->debug) {
			echo 'Values: <pre>'.print_r($values, TRUE).'</pre>';
		}

		return $values;
	}
	
	/**
	 * Parse form
	 */
	public function parse()
	{
		if (isset($this->page)) {
			// Add hidden settings
			$this->parseFormParams(); 
			// Get returned data
			$this->populateElements();
			
			// Header
			$output  = '<div class="rex-addon-output ahoi-backend-form">'."\n";
			$output .=      $this->parseHeadline($this->form_id);
			$output .= '    <div class="rex-form">'."\n";
			$output .= '        <form id="'.$this->specialChars(strtolower($this->form_id), TRUE).'" action="'.$this->action.'" method="'.$this->method.'">'."\n";
	
			// Body
			foreach ($this->fieldsets as $id => $f) {
				$output.= $this->parseFieldset($id, $f);
			}
			
			// Footer
			$output .=          $this->parseSubmit();
			$output .= '    </form>'."\n";
			$output .= '</div>'."\n".'</div>'."\n";
			
			if ($this->debug) {
				echo 'Form: <pre>'.print_r($this->fieldsets, TRUE).'</pre>';
			}
		} else {
			$output = 'ERROR: Parameter "page" not set.';
		}
		return $output;
	}
	
	protected function parseFormParams()
	{
		$form_params['page'] = array('value' => $this->page);
		$form_params['subpage'] = array('value' => $this->subpage);
		$form_params['func'] = array('value' => 'update');
		
		// Add a fieldset
		if (!array_key_exists('form-params', $this->fieldsets)) {
			$this->fieldset('form-params');
		} 
		
		foreach ($form_params as $k => $v) {
			// Check for presets and returned form-data
			if (array_key_exists($k, $this->values)) {
				$value = $this->values[$k];
			} else {
				$value = $v['value'];
			}
			// Set
			$this->fieldsets['form-params']['elements'][$k] = new Elements\hidden($k, array('id' => $k, 'value' => $value));
		}
	}
	
	protected function populateElements()
	{
		if ($this->debug) {
			echo 'Presets: <pre>'.print_r($this->values, TRUE).'</pre>';
		}

		foreach ($this->fieldsets as $fk => $fv) {
			// Exclude form params
			if ($fk == 'form-params') continue;
				
			// Get values
			foreach ($fv['elements'] as $e) {
				if ($e->submission()) $e->populate();
			}
		}
	}
	
	protected function parseFieldset($id, $fieldset)
	{
		$output = '<fieldset name="'.$id.'" class="'.$id.($fieldset['class']? ' '.$fieldset['class'] : '').'">'."\n";
		if ($fieldset['legend']) {
			$legend = new Elements\legend($id, array('label' => $fieldset['legend']));
			$legend->debug($this->debug);
			$output .= $legend->parse();
		}
		$output.= '     <div class="rex-form-wrapper">'."\n";
		
		// Elemente einfügen
		foreach ($fieldset['elements'] as $e) {
			$output.= $e->parse();
		}
		
		$output.= '    </div>'."\n";
		$output.= '</fieldset>'."\n";
		
		return $output;
	}
	
	protected function parseHeadline($headline, $level = 2, $class = '')
	{
		if ($level < 2 || $level > 6) $level = 2;
		return '<h'.$level.' class="rex-hl'.$level.($class ? ' '.$class : '').'">'.$headline.'</h'.$level.'>'."\n";
	}

	protected function parseSubmit()
	{
		$return = '
		<div class="rex-form-row">
			<p class="rex-form-submit">
				<input type="submit" class="rex-form-submit" name="submit" value="'.$this->submit.'" />
				&nbsp&nbsp';
        
        if ($this->abort) {
			$return .= '
                <a href="index.php?page='.rex_request('page', 'string').'&amp;subpage='.rex_request('subpage', 'string').'">
					'.$this->abort.'
				</a>';
        }
		
        $return .= '
            </p>
		</div>'."\n";
        
        return $return;
	}
	
	protected function specialChars($string, $replace_space = FALSE)
	{
		$replace = array ( 
			'&quot;' => '-',
			'&#039;' => '-',
			'ä' => 'ae', 	'&auml;' => 'ae', 
			'ö' => 'oe', 	'&ouml;' => 'oe', 
			'ü' => 'ue', 	'&uuml;' => 'ue', 
			'Ä' => 'ae', 	'&Auml;' => 'ae', 
			'Ö' => 'oe', 	'&Ouml;' => 'oe', 
			'Ü' => 'ue', 	'&Uuml;' => 'ue', 
			'ß' => 'ss', 	'&szlig;' => 'ss', 
			'\\' => '-', 
			'/' => '-', 
			'&' => '-',		'&amp;' => '-',
			'<' => '-',		'&lt;' => '-',
			'>' => '-',		'&gt;' => '-',
			'|' => '',
	
			chr(195).chr(132) => 'Ae'/*Ä*/,
			chr(195).chr(150) => 'Oe'/*Ö*/,
			chr(195).chr(156) => 'Ue'/*Ü*/,
			chr(195).chr(164) => 'ae'/*ä*/,
			chr(195).chr(182) => 'oe'/*ö*/,
			chr(195).chr(188) => 'ue'/*ü*/,
			chr(195).chr(159) => 'ss'/*ß*/,
		);
	
		if ($replace_space === TRUE) {
			$replace[' '] = '_';
		} elseif ($replace_space || $replace_space === '') {
			$replace[' '] = $replace_space;
		}
		$stringNew = strtr(htmlentities($string, ENT_IGNORE, 'UTF-8'), $replace);
		$stringNew = preg_replace('%([-]{2,})%', '-', $stringNew); // mehrfache '-' entfernen
		return $stringNew;
	}
}
