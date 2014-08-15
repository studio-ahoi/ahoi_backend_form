<?php
/**
 * Form Element
 *
 * @revision 131227
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */

namespace ahoi\Form\Elements;

class categorylist extends ElementAbstract implements ElementInterface
{
	public function __construct($id, $attributes)
	{
		parent::__construct($id, $attributes);
	}

	public function parse()
	{
		global $I18N;
		
		$category = array_key_exists('category', $this->attributes) ? $this->attributes['category'] : 0;
		$get_children = array_key_exists('get_children', $this->attributes) ? $this->attributes['get_children'] : TRUE;
		
		if (!array_key_exists('class', $this->attributes)) $this->attributes['class'] = '';
		$this->attributes['class'] .= ' rex-form-checkbox'; 

		$this->attributes['options'][0] = array('name' => $I18N->msg($this->page().'_root_category'), 'indent' => 0);
		$this->attributes['options'] += self::getCategoriesIndented($category, $get_children, 0);

		$type = array_key_exists('multiple', $this->attributes) ? '[]' : '';

		$output = '<div class="rex-form-row">';
		$output.=      $this->label();
		$output.= '    <select name="'.$this->attributes['id'].$type.'"'.$this->parseAttributes().'>'."\n";
							foreach($this->attributes['options'] as $o => $t) {
								if ((is_array($this->attributes['value']) && count($this->attributes['value']) > 0 && in_array($o, $this->attributes['value'])) || $this->value() == $o) {
									$s = ' selected="selected"'; 
								} else {
									$s = '';
								}
								$output.= '<option value="'.$o.'"'.$s.'>'.$t['name'].'</option>'."\n";
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
		return rex_post($this->id, 'array', $this->attributes['value']);
	}
	public function get()
	{
		return rex_get($this->id, 'array', $this->attributes['value']);
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
	 * Array of categories & indent
	 * This is useful for nested lists eg. in <select> fields
	 */
	protected static function getCategoriesIndented($category_id = 0, $get_articles = FALSE, $indent = 0)
	{
        $return = array();
        if ($category_id == 0) {
            $categories = \OOCategory::getRootCategories();
        } else {
            $categories = array(\OOCategory::getCategoryById($category_id));
        }

        foreach ($categories as $c) {
            $return[$c->getId()] = array(
                'name' => $c->getName().(!$c->isOnline() ? ' [offline]' : ''), 
                'indent' => $indent,
            );

            $children = $c->getChildren();
            if (is_array($children) && count($children) > 0) {
                $indent++;
                foreach ($children as $cc) {
                    $return += self::getCategoriesIndented($cc->getId(), $get_articles, $indent);
                }
                $indent--;
            }
        }
        return $return;
    }
}
