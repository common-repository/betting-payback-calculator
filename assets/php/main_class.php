<?php

if(!class_exists('Betting_Payback_Calculator')) {
	class Betting_Payback_Calculator {
		
		private $prefix			= 'betting_payback_calculator';
		
	    private $language_arr = array(
			1	=> 'English',
			// 2	=> 'Spanish',
			// 3	=> 'Russian',
			// 4	=> 'Turkish',
			// 5	=> 'German',
			// 6	=> 'Portugese',
			// 7	=> 'Vietnamese',
			8	=> 'Macedonian',
			9	=> 'Serbian',
			// 10	=> 'Croatian',
			// 11	=> 'Bulgarian',
			12	=> 'Danish'
	    );
	    
	    private $font_family_arr = array('Trebuchet MS', 'Verdana', 'Tahoma', 'Calibri', 'Sans Serif', 'Arial');
	    
	    private $settings = array();
		
		function __construct() {
			if(($this->settings = get_option($this->getSettingsKey())) == false) {
				$this->settings = array();
			}
			
		}
		function getPrefix() {
			return $this->prefix;
		}
		function getSettings() {
			return $this->settings;
		}
		function getSettingsKey() {
			return $this->prefix.'_setting';
		}
		function getSetting($key) {
			return array_key_exists($key, $this->settings) ? $this->settings[$key] : null;
		}
		function getLanguageArray() {
			return $this->language_arr;
		}
		function getFontFamilyArray() {
			return $this->font_family_arr;
		}
	}
}

?>