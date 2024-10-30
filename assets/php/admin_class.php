<?php

require_once __DIR__.'/main_class.php';

if(!class_exists('Betting_Payback_Calculator_Admin')) {
	
	class Betting_Payback_Calculator_Admin extends Betting_Payback_Calculator {
		
		private $page = 'betting_payback_calculator_options';
		
		private static $instance;
		
		function __construct() {
			parent::__construct();

			add_action('admin_enqueue_scripts', array( $this, 'addScripts' ) );
			
			
			// REGISTER SETTINGS
			register_setting( 'main_section', $this->getSettingsKey(), array( $this, 'sanitizeSettings' ) );
			register_setting( 'style_section', $this->getSettingsKey(), array( $this, 'sanitizeSettings' ) );
			
		}
		public static function getInstance() {
			
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			
			return self::$instance;
		}
		
		static function getPage() {
	    	return Betting_Payback_Calculator_Admin::getInstance()->page;
	    }

		/**
		 * Function that will add javascript file for Color Piker.
		 */
		public function addScripts($hook_suffix) {
			if($hook_suffix == 'settings_page_'.$this->getPage()) {
				// Make sure to add the wp-color-picker dependecy to js file
				wp_enqueue_script( 'cpa_custom_js', plugins_url('/betting-payback-calculator/jquery.custom.js'), array( 'jquery', 'wp-color-picker' ), '', true  );
			}
		}
		
		function sanitizeSettings($fields) {
			
			$valid_fields = $fields;
			foreach ($fields as $key => $value) {
				if($key == 'languageId') {
					if(!array_key_exists($value, $this->getLanguageArray())) {
						$valid_fields[$key] = 1;
					}
				} else if($key == 'authorLink') {
					if(!in_array($value, array(0, 1))) {
						$valid_fields[$key] = 0;
					}
				} else if($key == 'fontFamily') {
					if(!in_array($value, $this->getFontFamilyArray())) {
						$valid_fields[$key] = 'Arial';
					}
				} else if(substr($key, 0, 5) == 'color') {
					if(!$this->validateColor($value)) {
						$valid_fields[$key] = $this->getOptionColor($key);
					}
				} else {
					unset($valid_fields[$key]);
				}
			}
			
			return apply_filters( 'validateOptions', $valid_fields, $fields);
		}
		
		function addSettingsSections() {
		
			add_settings_section( 'main_section', 'General Settings', array( $this, 'addSettingsMainSection' ), $this->page );
			add_settings_section( 'style_section', 'Fonts and colors', array( $this, 'addSettingsStyleSection' ), $this->page );
		}
		
		function addSettingsMainSection() {
			
			// LANGUAGE
			add_settings_field( 'languageId', 'Language', array( $this, 'outputLanguageOptions' ), $this->page, 'main_section' );
			// AUTOHER CREDIT LINK
			add_settings_field( 'authorLink', 'Author Credit Link', array( $this, 'outputSettingAuthorLink' ), $this->page, 'main_section' );
			
			
		}
		
		function addSettingsStyleSection() {
			////////////////////////////////////////////////////////////////////////////////////////////////////////
			// FONT AND COLOR SETTINGS SECTION
			///////////////////////////////////////////////////////////////////////////////////////////////////////
		    
			add_settings_field( 'fontFamily', 'Font', array( $this, 'getFontFamilyOptions' ), $this->page, 'style_section' );
			add_settings_field( 'colorFont', 'Font Color', array( $this, 'getColorPicker' ), $this->page, 'style_section', array(
				'o_name' => 'colorFont', 'def_color' => $this->getOptionColor('colorFont'))
			);
			
			add_settings_field( 'colorBackgroundHeader', 'Background - Header', array( $this, 'getColorPicker' ), $this->page, 'style_section', array(
				'o_name' => 'colorBackgroundHeader', 'def_color' => $this->getOptionColor('colorBackgroundHeader'))
			);
			
			add_settings_field( 'colorFontHeader', 'Font Color - Header', array( $this, 'getColorPicker' ), $this->page, 'style_section', array(
				'o_name' => 'colorFontHeader', 'def_color' => $this->getOptionColor('colorFontHeader'))
			);
			
			settings_fields( 'style_section' );
			
			register_setting( 'style_section', $this->getSettingsKey(), array( $this, 'sanitizeSettings' ) );
		}
		
		
	    function outputSettingsPage() {
	    	

	    	$this->addSettingsSections();
	    	
	    	//settings_fields($this->page)
	    	
			echo	'<div class="wrap">'
				.		'<h2>Betting Payback Calculator Options</h2>'
				.		'<form method="post" action="options.php">';
				//.			settings_fields($this->page)
			
				settings_fields( 'main_section' );
				settings_fields( 'style_section' );
				
			echo			do_settings_sections($this->page)
				.			submit_button()
				.		'</form>'
				.		'<h2>Notes</h2>'
				.		'<ul style="font-weight:bold;">'
				.			'<li>How to use the plugin?'
				.				'<blockquote style="font-style:italic;font-weight:normal;">'
				.				'Use [betting_payback_calculator] shortcode where you want to display the calculator.'
				.				'</blockquote>'
				.			'</li>'
				.			'<li>How to show own colors?'
				.				'<blockquote style="font-style:italic;font-weight:normal;">'
				.				'Enable Author Credit Link'
				.				'</blockquote>'
				.			'</li>'
				.		'</ul>'
				.	'</div>';
	    }
	    
		public function outputSettingAuthorLink() { 
		
			$selected_key = $this->getSetting('authorLink');
			
			$value_arr = array(
				0	=> 'Off',
				1	=> 'On'
			);
			
			echo	'<select name="'.$this->getSettingsKey().'[authorLink]">';
			
			foreach ($value_arr as $key => $value) {
				echo	'<option value="'.$key.'" '.($key == $selected_key ? 'selected' : '').'>'.$value.'</option>';
			}
			
			echo	'</select>';
		}
		public function getFontFamilyOptions() {
			
			$selected_value = $this->getSetting('fontFamily'); //(is_array($this->options) && array_key_exists('fontFamily', $this->options)) ? $this->options['fontFamily'] : 0;
			
			echo	'<select name="'.$this->getSettingsKey().'[fontFamily]">';
			
			foreach ($this->getFontFamilyArray() as $value) {
				echo	'<option value="'.$value.'" '.($value == $selected_value ? 'selected' : '').'>'.$value.'</option>';
			}
			
			echo	'</select>';
		}
		public function outputLanguageOptions() { 
			
			$language_id = $this->getSetting('languageId'); //(is_array($this->options) && array_key_exists('languageId', $this->options)) ? $this->options['languageId'] : 0;
			
			echo	'<select name="'.$this->getSettingsKey().'[languageId]">';
			
			foreach ($this->getLanguageArray() as $id => $name) {
				echo	'<option value="'.$id.'" '.($id == $language_id ? 'selected' : '').'>'.$name.'</option>';
			}
			
			echo	'</select>';
		}
		public function getColorPicker(array $args) {
		
			$val = $args['def_color'];
			
			echo '<input type="text" name="'.$this->getSettingsKey().'['. $args['o_name'] .']" value="' . $val . '" class="cpa-color-picker" >';
		}
		private function validateColor($value) { 
			return preg_match( '/^#[a-f0-9]{6}$/i', $value );
		}
		private function getOptionColor($key) {
			
			if(is_null($color = $this->getSetting($key))) {
				switch ($key) {
					case 'colorFont':								$color = '#000000'; break;
					
					case 'colorBackgroundHeader':					$color = '#333333'; break;
					case 'colorFontHeader':							$color = '#EEEEEE'; break;
					
					default:										$color = '#000000';
				}
			}
			
			return $color;
		}
	}
	
}

?>