<?php
/**
 * Help
 *
 * @revision 141105
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */
 
namespace ahoi\Form\Page;
    
class Help
{
    public function __construct()
    {    
    }  

    public function run($wrap = TRUE)
    {
        $this->page = rex_request('addonname', 'string');
        if (!$this->page) {
            $this->page = rex_request('page', 'string');
        }
        
        $output = $this->output();
        if ($wrap) {
            $output = $this->wrap($output);
        }
        echo $output;
    }
    
    protected function wrap($output)
    {
        return '
        <div class="rex-addon-output">
            <h2 class="rex-hl2">'.$this->page.'</h2>
            <div class="rex-addon-content">'.$this->output().'</div>
        </div>'.PHP_EOL;
    }
    
    protected function output()
    {
        global $REX;
                
        $file = $REX['INCLUDE_PATH'].DIRECTORY_SEPARATOR.'addons'.DIRECTORY_SEPARATOR.$this->page.DIRECTORY_SEPARATOR.'README.textile';
        $text = '';

        if (file_exists($file)) {
            $text = file_get_contents($file);

            if(\OOAddon::isActivated("textile")) {
                $text = htmlspecialchars_decode($text);
                $text = str_replace("<br />", "", $text);
                $text = str_replace("&#039;", "'", $text);
                $text = rex_a79_textile($text);
            } else {
                $text = str_replace(PHP_EOL, '<br />', $text);
                $text = '<pre>'.$text.'</pre>';
            }
        } else {
            $text = 'No help file found.';
        }
        return $text;
    }
}
