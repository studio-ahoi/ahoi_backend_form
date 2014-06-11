<?php
/**
 * ahoi Backend Form
 *
 * @version 1.1 rev 140526
 * @author Daniel Weitenauer
 * @copyright (c) 2014 studio ahoi
 */

namespace ahoi\Form;

use ahoi\Pages\Page_Abstract;
use ahoi\Form\Form;
use ahoi\Tools\Settings;

class Config extends Page_Abstract
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function run()
	{
		global $I18N;
        
        $page = $this->getPage();
        $subpage = $this->getSubpage();
		
		// Settings
		$settings_file = new Settings($page);
		$settings = $settings_file->loadSettings();

		// Form
		$form = new Form();
		$form
			->id($I18N->msg($page.'_configuration'))
			->page($page)
			->subpage($this->getSubpage())
            ->submit($I18N->msg($page.'_submit'))
            ->abort($I18N->msg($page.'_abort'))
            			
		    ->fieldset($this->getPage().'_demo') 		 
			    ->element('hidden', 'hidden', array('label' => $I18N->msg($page.'_hidden'), 'value' => $settings['hidden']))
		    ->fieldset($this->getPage().'_text', $I18N->msg($page.'_text')) 		 
			    ->element('textfield', 'textfield', array('label' => $I18N->msg($page.'_textfield'), 'description' => $I18N->msg($page.'_textfield_description'), 'value' => $settings['textfield']))
			    ->element('textarea', 'textarea', array('label' => $I18N->msg($page.'_textarea'), 'class' => 'tinyMCEEditor ckeditor', 'value' => $settings['textarea']))
			    ->element('readonly', 'readonly', array('value' => $I18N->msg($page.'_readonly_text')))
		    ->fieldset($this->getPage().'_selection', $I18N->msg($page.'_selection')) 		 
			    ->element('select', 'select', array('label' => $I18N->msg($page.'_select'), 'options' => array('ja' => $I18N->msg($page.'_ja'), 'nein' => $I18N->msg($page.'_nein')), 'value' => $settings['select']))
			    ->element('checkbox', 'checkbox', array('label' => $I18N->msg($page.'_checkbox'), 'value' => $settings['checkbox']))
		    ->fieldset($this->getPage().'_media', $I18N->msg($page.'_media')) 		 
			    ->element('mediabutton', 'mediabutton', array('label' => $I18N->msg($page.'_mediabutton'), 'value' => $settings['mediabutton']))
			    ->element('medialistbutton', 'medialistbutton', array('label' => $I18N->msg($page.'_medialistbutton'), 'value' => $settings['medialistbutton']))
		    ->fieldset($this->getPage().'_links', $I18N->msg($page.'_links')) 		 
			    ->element('linkbutton', 'linkbutton', array('label' => $I18N->msg($page.'_linkbutton'), 'value' => $settings['linkbutton']))
			    ->element('linklistbutton', 'linklistbutton', array('label' => $I18N->msg($page.'_linklistbutton'), 'value' => $settings['linklistbutton']))
		    ->fieldset($this->getPage().'_special', $I18N->msg($page.'_special')) 		 
			    ->element('mediatypes', 'mediatypes', array('label' => $I18N->msg($page.'_mediatypes'), 'value' => $settings['mediatypes']))
			    ->element('categorylist', 'categorylist', array('label' => $I18N->msg($page.'_categorylist'), 'multiple' => 1, 'value' => $settings['categorylist']))
                ;
				
		if ($this->getFunc() == 'update') {
			if ($settings_file->saveSettings($form->submission())) {
				echo rex_info($I18N->msg($page.'_config_saved'));
			} else {
				echo rex_warning($I18N->msg($page.'_config_not_saved'));
			}
		}
		echo $form->parse();	
	}
}


/**
 * Page
 */
$backend = new Config();
$backend->run();
