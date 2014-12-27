<?php
/**
 * Autoload
 *
 * @revision 141105
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */

namespace ahoi\Form;

class Autoload 
{
    protected static $instance = null;
    
    protected $paths      = array();
    protected $debug      = false;
    protected $identifier = 'ahoi';
    protected $registered = false;
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    protected function __construct() 
    {
    }

    public function debug($debug = false)
    {
        $this->debug = (boolean) $debug;
        
        return $this;
    }
    
    public function identifier($identifier = 'ahoi')
    {
        $this->identifier = (string) $identifier;
        
        return $this;
    }
    
    public function add($path) 
    {
        $path = realpath($path).DIRECTORY_SEPARATOR;
        if ($path && !in_array($path, $this->paths)) {
            $this->paths[] = $path;
        }
        
        return $this;
    }
    
    public function register()
    {
        if (!$this->registered) {
            spl_autoload_register(array($this, 'load'));
            $this->registered = true;
        }
    }
    
    protected function load($class) 
    {
        try {
            $found = false;
            
            // Remove basic name-space identifier
            $file = preg_replace('%.?'.$this->identifier.'.%U', '', $class);
             
            // Map namespace to folder structure
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $file).'.php';
            
            // Loop through registered paths
            foreach ($this->paths as $p) {
                if (is_file($p.$file)) {
                    if ($this->debug) {
                        echo '<pre><strong>CLASS:</strong> '.$class.' <strong>FILE:</strong> '.$p.$file.'</pre>';
                    }
                    require_once($p.$file);
                    $found = true;
                    break;
                }             
            }
            
            // Looped through all
            if ($this->debug && !$found) {
                echo '<pre><strong>FILE NOT FOUND:</strong> '.$file.'</pre>';
                echo '<pre><strong>PATHS:</strong><br/>'.print_r($this->paths, true).'</pre>';
            }
        } catch (\Exception $e) {
            echo $e->getMessage(); 
        }        
    }
}
