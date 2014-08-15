<?php
/**
 * Settings
 *
 * Parses $REX values to files and vice versa
 * Proivides a static handler for module presets
 *
 * @revision 140726
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */

namespace ahoi\Tools;

class Settings 
{
	protected $debug = FALSE;
	
	protected $page;
	protected $path;
	protected $filename;

    /**
     * Constructor
     *
     * @param   $page   The addon name is required to determine the path
     * @return  void
     */
	public function __construct($page) 
	{
		$this->page = $page;
		$this->path();
		$this->filename();
	}
	
	/**
	 * Debug flag
     *
     * @param   $debug  TRUE enables debug output
     * @return  object  For method chaining
	 */
	public function debug($debug = FALSE)
	{
		$this->debug = $debug;
		
		return $this;
	}
	
	/**
	 * Set path to settings file 
     *
     * @param   $path   If NULL, the data folder is set as default
     * @return  object  For method chaining
	 */
	public function path($path = NULL)
	{ 
		global $REX;
		
		if (!isset($path)) $path = $REX['INCLUDE_PATH'].DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'addons';
		$this->path = (string) $path;
		
		return $this;
	}
	
	/**
	 * Set filename
     *
     * @param   $filename   Default is settings.inc.php to fit Redaxo filename structure
     * @return  object      For method chaining
	 */
	public function filename($filename = 'settings.inc.php')
	{
		$this->filename = (string) $filename;
		
		return $this;
	}

	/**
	 * Returns the set file path, including an optional filename attached
     *
     * @param   $filename   Filename to attach
     * @return  string      The stored path
	 */
	public function getPath($filename = NULL)
	{
		$path = $this->path.DIRECTORY_SEPARATOR.$this->page.($filename ? DIRECTORY_SEPARATOR.$filename : '');
		
		return $path;
	}

	/**
	 * Init the Settings, create a settings file or return the saved settings
     *
     * @param   $presets    An array of presets for the settings
     * @return  array       The settings array
	 */
	public function initSettings(array $presets = array())
	{
		$filename = $this->getPath($this->filename);

		// Load existing
		if (file_exists($filename)) {
			$settings = $this->loadSettings();
		} 
		// Write presets
        else {
		    $settings = $presets;	
			$this->saveSettings($settings);
		}
        
		return $settings;
	}

	/**
	 * Stores an array to a file
     *
     * @param   $settings   An array of settings
     * @return  object      For method chaining
	 */
	public function saveSettings(array $settings)
	{	
		$filename = $this->getPath($this->filename);
		
		// Create dir
		if (!file_exists(dirname($filename))) {
			mkdir(dirname($filename), 0777, true);
		}
		// Create empty file
		if (!file_exists($filename)) {
			if (!rex_put_file_contents($filename, "<?php".PHP_EOL."// --- DYN".PHP_EOL."// --- /DYN")) {
				echo rex_warning($I18N->msg($this->page.'_config_not_initialized', $filename));
			}
		}

		$content = '$settings = array('.PHP_EOL;

		foreach ($settings as $k => $v) {
			$this->setProperty($k, $v);
			$content .= "\t".$this->encode($k, $v).','.PHP_EOL;
		}
		
		$content .= ");".PHP_EOL;

		if ($this->debug) {
			echo "Filename: <pre>".$filename."</pre><br/>\n";
			echo "Content: <pre>".preg_replace('%<\?php%', '&lt?php', $content)."</pre><br/>\n";
			echo "Settings: <pre>";
			print_r($settings);
			echo "</pre><br/>\n";
		}

		if (rex_replace_dynamic_contents($filename, $content)) {
			$return = TRUE;
		} else {
			$return = FALSE;
		}

		return $this;
	}

	/**
	 * Loads an array from a file
     *
     * @return  array       The settings array
	 */
	public function loadSettings()
	{
		global $REX, $I18N;
		
		$filename = $this->getPath($this->filename);
		
		$settings = array();

		if (file_exists($filename)) {
			include($filename);

			foreach ($settings as $k => $v) {
				$this->setProperty($k, $v);
			}
		} else {
			echo rex_warning($I18N->msg($this->page.'_cant_read_file', $filename));
		}
		
		if ($this->debug) {
			echo "Filename: <pre>".$filename."</pre><br/>\n";
			echo "Settings: <pre>";
			print_r($settings);
			echo "</pre><br/>\n";
		}

		return $settings;
	}
	
	/**
	 * Check if a file exists
     *
     * @return  boolean     TRUE if file exists
	 */
	public function fileExists()
	{
		$filename = $this->getPath($this->filename);

		return file_exists($filename);
	}

	/**
	 * Encode values to a string
     *
     * @return  string  A string, that can be written to a file
	 */
	protected function encode($key, $value)
	{
		if (is_numeric($value)) {
			return '"'.$key.'" => '.$value;
		} elseif (is_array($value)) {
			return '"'.$key.'" => unserialize("'.addslashes(serialize($value)).'")';
		} else {
			return '"'.$key.'" => "'.$value.'"';
		}
	}

	/**
	 * Check if a Redaxo property is set
     *
     * @param   $key        The key to check
     * @return  boolean     TRUE is the property exists
	 */
	public function isPropertySet($key)
	{
		global $REX;
		
		return isset($REX['ADDON'][$this->page][$key]) ;
	}
	
	/**
	 * Get a Redaxo property
     *
     * @param   $key        The key to get
     * @return              The property value
	 */
	public function getProperty($key)
	{
		global $REX;
		
		if ($this->isPropertySet($key)) {
			return $REX['ADDON'][$this->page][$key];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Set a Redaxo property
     *
     * @param   $key        The key to set
     * @return  object      For method chaining
	 */
	public function setProperty($key, $value)
	{
		global $REX;
		
		$REX['ADDON'][$this->page][$key] = $value;
		
		return $this;
	}
    
    /**
     * Module preset handler
     *
     * Provides a central place to set REX-variables for modules
     * the settings file is assumed at "data/presets/settings.inc.php"
     *
     * @param   $key        The key to set, preferably formatted as 'module:module_name'
     * @param   $settings   The Module presets to be overwritten
     * @param   $debug      TRUE = debugging output
     * @return  array       Array with overwritten presets
     */
    public static function getModulePresets($key, $settings = array(), $debug = FALSE)
    {
        $file = new self('presets'); // Virtual addon 'presets'
        $presets = $file->loadSettings();

        if ($debug) echo '<pre>Preset File:<br/>'.print_r($presets, TRUE).'</pre>';
        
        if (isset($presets[$key])) {
            $settings = array_merge($settings, $presets[$key]);
        }
        
        if ($debug) echo '<pre>Settings<br/>'.print_r($settings, TRUE).'</pre>';

        return $settings;
    }
}