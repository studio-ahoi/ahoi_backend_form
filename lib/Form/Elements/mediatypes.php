<?php
/**
 * Form Element
 *
 * @version 1.0 rev 131227
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */

namespace ahoi\Form\Elements;

use ahoi\Tools\Media;

class mediatypes extends ElementAbstract implements ElementInterface
{
	public function __construct($id, $attributes)
	{
		parent::__construct($id, $attributes);
	}

	public function parse()
	{
		global $I18N;
		
		$this->attributes['imagetype'] = $media_types = self::getMediaTypes($I18N->msg($this->page().'_original_image'));

		if (!array_key_exists('class', $this->attributes)) $this->attributes['class'] = '';
		$this->attributes['class'] .= ' rex-form-checkbox'; 

		$output = '<div class="rex-form-row">';
		$output.=      $this->label();
		$output.= '    <select name="'.$this->attributes['id'].'"'.$this->parseAttributes().'>'."\n";
        foreach($this->attributes['imagetype'] as $o => $t) {
			if ((is_array($this->attributes['value']) && count($this->attributes['value']) > 0 && in_array($o, $this->attributes['value'])) || $this->value() == $o) {
				$selected = ' selected="selected"'; 
			} else {
				$selected = '';
			}
			$output.= '<option value="'.$o.'"'.$selected.'>'.$t.'</option>'."\n";
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
    
    /**
	 * Get an array of available image types from image manager.
	 */
	protected static function getMediaTypes($no_type = FALSE)
	{
        if (\OOAddon::isAvailable('_ahoi_tools')) {
            return Media::getTypes($no_type);
        } else {
            global $REX, $I18N;
            
            $image_types = array();
            
            if (is_string($no_type)){
                $image_types['none'] = $no_type;
            }
            
            if (\OOAddon::isAvailable('image_manager')) {
                $sql = \rex_sql::factory();
                $sql->setQuery('SELECT * FROM '.$REX['TABLE_PREFIX'].'679_types ORDER BY status');
                $count = $sql->getRows();
        
                for ($i = 0; $i < $count; $i++) {
                    // Exclude systemtypes
                    if ($sql->getValue("status") != 1) {
                        $image_types[$sql->getValue('name')] = $sql->getValue('description').' ['.$sql->getValue('name').']';
                    }
                    $sql->next();
                }	
            } else {
                $image_types[0] = $i18N->msg($this->page().'image_manager_not_available');
            }
            return $image_types;
        }
    }
}
