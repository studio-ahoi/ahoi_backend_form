<?php
/**
 * Backend Base
 *
 * @revision 140202
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */

namespace ahoi\Tools\Page;

abstract class PageAbstract
{
	protected $languages;
	protected $debug = FALSE;
	protected $page;
    	
	public function __construct()
	{
        // Set addon name
        $this->page($this->getPage());	
	} 
	
	/**
	 * Setters, chainable
	 */
	public function debug($debug = FALSE)
	{
		$this->debug = $debug;
		return $this;
	}
	
	public function page($page = FALSE)
	{
		$this->page = $page;
		return $this;
	}
	
	/**
	 * Url request handlers
	 * As rex_request does not handle defaults for existing but empty keys, 
	 * these methods work as its substitutes
	 */
	public function getRequest($key, $type = 'string', $default = NULL)
	{
		$return = rex_request($key, $type, $default);
		if (!$return) $return = (string) $default;
		return $return;
	}

	public function getPage($default = NULL)
	{
		return $this->getRequest('page', 'string', $default);
	}	
		
	public function getSubpage($default = NULL)
	{
		return $this->getRequest('subpage', 'string', $default);
	}
	
	public function getFunc($default = NULL)
	{
		return $this->getRequest('func', 'string', $default);
	}
	
	public function getClang($default = NULL)
	{
		global $REX;
		if (!isset($default)) $default = $REX['CUR_CLANG'];
		return $this->getRequest('clang', 'int', $default);
	}

	public function getCategory($default = 0)
	{
		$return = rex_request('category', 'int');
		if (!$return) $return = $default;
		return $return;
	}
	
	public function getItem($default = 0)
	{
		$return = rex_request('item', 'int');
		if (!$return) $return = $default;
		return $return;
	}
    
    public function getMessage($default = '')
    {
		$return = rex_request($this->getPage().'_msg', 'string');
		if (!$return) $return = $default;
		return $return;
    }
    
	/**
	 * Language navigation
	 */
	public function generateLanguageLinks(array $request_array = array())
	{
		global $REX, $I18N;
		
		$request = array('page' => $this->getPage(), 'subpage' => $this->getSubpage());
		foreach ($request_array as $k => $v) {
			$request += array($k => $this->getRequest($k, $v));
		}

		// Build output
		$index = 0;
		$output = '
		<div id="rex-clang" class="rex-toolbar rex-content-header">
			<div class="rex-toolbar-content">
				<ul class="clearfix">
				<li>'.$I18N->msg($this->getPage().'_languages').'</li>'.PHP_EOL;
				
		foreach ($this->getLanguages() as $k => $v) {
			$class = $this->getClang() == $k ? 'class="rex-active" ' : '';
			$href = 'index.php?'.$this->encodeUrlParams($request + array('clang' => $k));
			
			$output .= '<li class="rex-navi-clang-'.$index.' '.(!$index ? 'rex-navi-first' : '').'"><a '.$class.'href="'.$href.'">'.$v.'</a></li>'.PHP_EOL;
			$index++;
		}
		
		$output .= '
				</ul>
			</div>
		</div>'.PHP_EOL;

		return $output;
	}

	/**
	 * Categories navigation
	 */
	public function generateCategoryLinks($table)
	{
		global $I18N;

		$category_names = array_merge(array(array('id' => 0, 'name_'.$this->getClang() => $I18N->msg($this->getPage().'_all_categories'))), $this->getData($table));

		$output = '
		<div class="rex-toolbar rex-content-header">
			<ul>
				<li>'.$I18N->msg($this->getPage().'_categories').':</li>'.PHP_EOL;
                
		foreach ($category_names as $k => $v) {
			$class = $this->getCategory() == $v['id'] ? 'class="rex-active"' : '';
			$url = 'index.php?'.$this->encodeUrlParams(array('page' => $this->getPage(), 'subpage' => $this->getSubpage(), 'category' => $v['id'], 'clang' => $this->getClang()));
			$output .= '<li'.(!$k ? ' class="rex-navi-first"' : '').'><a '.$class.' href="'.$url.'">'.($v['name_'.$this->getClang()] ? $v['name_'.$this->getClang()] : '['.$v['name_0'].']').'</a></li>'.PHP_EOL;
		}
		$output .= '
			</ul>
		</div>'.PHP_EOL;

		return $output;
	}

	/** 
	 * Get image thumbnail
	 */
	public function imagePreview()
	{
		global $REX;
		
		if (\OOAddon::isActivated('image_manager')) {
			$settings = 'index.php?rex_img_type=rex_mediapool_preview&rex_img_file=';
		} else { 
			$settings = $REX['MEDIA_DIR'].'/';
		}
		return $settings;
	}
	
	/**
	 * Get languages as array(id => name)
	 */
	public function getLanguages()
	{
		global $REX;
		
		if (!isset($this->languages)) {
			$sql = \rex_sql::factory();
			$data = $sql->getArray('SELECT id, name FROM '.$REX['TABLE_PREFIX'].'clang ORDER BY id');
			foreach ($data as $d) {
				$this->languages[$d['id']] = $d['name'];
			}
		}
		return $this->languages;
	}

	/**
	 * Get data of a table as array(id => name)
	 */
	public function getData($table)
	{
		$sql = \rex_sql::factory();
		$sql->setDebug($this->debug);
		$sql->setTable($table);
		$sql->setWhere("`status` = 1 ORDER BY `name_".$this->getClang()."` ASC");
		$sql->select("`id`, `name_".$this->getClang()."`".($this->getClang() ? ", `name_0`" : ""));
		$rows = $sql->getArray();
		return $rows;
	}
	
	/**
	 * Delete a row in a table
	 */
	public function deleteData($id, $table)
	{
        global $I18N;
        
		$sql = \rex_sql::factory(); 
		$sql->setDebug($this->debug);
		
		$sql->setTable($table);
		$sql->setWhere('`id` = '.$id);
        $message = $sql->delete($this->getMessage($I18N->msg($this->getPage().'_item_deleted')));
		return rex_info($message);
	}
	
	/**
	 * Switch value status of a table row
	 */
	public function toggleData($id, $table)
	{
		$sql = \rex_sql::factory(); 
		$sql->setDebug($this->debug);
		
		$sql->setTable($table);
		$sql->setWhere('`id` = '.$id);
		$sql->select('status');
		$toggle = (int) $sql->getValue('status') == 1 ? 0 : 1;
		
		$sql->setTable($table);
		$sql->setWhere('`id` = '.$id);
		$sql->setValue('status', $toggle);
		$sql->update();
	}

	/**
	 * Callback:
	 * Get a list of names
	 */
	public static function getNames($p)
	{
		$sql = \rex_sql::factory();
		$sql->setTable($p['params']['table']);
		$sql->setWhere("`status` = 1 ORDER BY `name_".$p['params']['lang']."` ASC");
		$sql->select("`id`, `name_".$p['params']['lang']."`".($p['params']['lang'] ? ", `name_0`" : ""));
		$categories = $sql->getArray();
				
		$ids = explode('|', $p['list']->getValue($p['params']['column']));

		$output = '';
		if (count($categories)) {
			foreach ($categories as $c) {
				if (in_array($c['id'], $ids)) { 
					$name_translated = $c['name_'.$p['params']['lang']];
					$output .= ($output ? ' | ' : '').($name_translated ? $name_translated : $c['name_0']);
				}
			}
		}
		return $output ? $output : '-';
	}
	
	/**
	 * Callback:
	 * Show status as a word
	 */
	public static function getStatus($p)
	{
		global $I18N;
		
		$page = rex_request('page', 'string');
				
		if ($p["list"]->getValue("status") == 1) {
			$text = "<span class='rex-status-link rex-online'>".$I18N->msg($page.'_online')."</span>";
		} else {
			$text = "<span class='rex-status-link rex-offline'>".$I18N->msg($page.'_offline')."</span>";
		}
		return $p["list"]->getColumnLink("status", $text, array("func" => "toggle"));
	}
	
	/**
	 * Encode url query params
	 */
	protected function encodeUrlParams($params, $divider = '&amp;')
	{
		$return = '';
		$i = 0;
		
		if (is_array($params)) {
			foreach ($params as $k => $v) {
				$return .= ($i ? $divider : '').urlencode($k).'='.urlencode($v);
				$i++;
			}
		} elseif ($params != '') {
			$return = $params;
		}
		
		return $return;
	}
}
