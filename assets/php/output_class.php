<?php


require_once __DIR__.'/main_class.php';

if(!class_exists('Betting_Payback_Calculator_Output')) {
	
	class Betting_Payback_Calculator_Output extends Betting_Payback_Calculator {

		private $translations;
		private $language_id = 1;
		private $author_link = 0;
		
		function __construct($atts) {
			parent::__construct();
			
			if(!is_null($language_id = $this->getSetting('languageId'))) {
				$this->language_id = $language_id;
			}
			if(!is_null($author_link = $this->getSetting('authorLink'))) {
				$this->author_link = $author_link;
			}
			
			$this->translations = $this->getTranslations();
		}
		function translateStatic($id, $basename = 'missing-translation', $replace_arr = array()) {
			
			if(is_null($ret = $this->translate('static', $id))) {
				$ret = $basename;
			}
				
				$pattern = array();
				$replace = array();
			
			if(count($replace_arr) > 0) {
			
				foreach ($replace_arr as $key => $value) {
					 array_push($pattern, '[%'.$key.'%]');
					 array_push($replace, $value);
				}
			}
			
			return str_replace($pattern, $replace, $ret);
		}

		function translate($type, $id) {
			
			$ret = null;
			
			if(property_exists($this->translations, $type)) {
				if(property_exists($this->translations->{$type}, $id)) {
					
					$translated_object = property_exists($this->translations->{$type}->{$id}, 'translations') ? $this->translations->{$type}->{$id}->{'translations'} : $this->translations->{$type}->{$id};

					if(property_exists($translated_object, $this->language_id)) {
						$ret = $translated_object->{$this->language_id};
					} else if(property_exists($translated_object, '1')) {
						$ret = $translated_object->{'1'};
					}
				}
			}
			return $ret;
		}
		
		function getTranslations() {
			
			$ret = (object) array();

			if(@($json = file_get_contents(plugin_dir_path(__DIR__).'translation/static.json'))) {
				$ret->static = json_decode($json);
			}
			
			return $ret;
		}
		
		function outputPage() {
			
			wp_register_script( 
				$this->getPrefix().'_script', 
				'https://www.oddsvalue.com/plugin/betting-payback-calculator/script/launch.js', 
				array( 'jquery' )
			);
			
			wp_enqueue_script( $this->getPrefix().'_script' );
			
			$options = array();		
			
			$html	=	'<div id="Oddsvalue-Betting-Payback-Percentage">'
					.		'<div class="header">'.$this->translateStatic('HEADER', 'Betting Payback Calculator').'</div>'
					.		'<div class="plugin-description">'.$this->translateStatic('PLUGIN_DESCRIPTION', 'Calculate the payback theoretical percentage for the selected number of outcomes').'</div>'
					.		'<div class="settings">'
					.			'<div class="row">'
					.				'<div class="text">'.$this->translateStatic('NUMBER_OF_ODDS', 'Number of odds').'</div>'
					.				'<div class="input">'
					.					'<select id="Outcome-Cnt">';
					
			for ($i = 2 ; $i <= 10 ; $i++) {
				$html	.=					'<option value="'.$i.'" '.($i == 3 ? 'selected' : '').'>'.$i.'</option>';
			}
			
			$html	.=					'</select>'
					.				'</div>'
					.			'</div>'
					.		'</div>'
					.		'<div class="calculation-box">';
			
			if($this->getSetting('authorLink') == 1) {
				$html	.=		'<a href="https://www.oddsvalue.com" title="WordPress Betting Plugin Provider">Oddsvalue.com</a>';
			}
			
			$html	.=		'</div>'
					.		'<div class="result"></div>'
					.	'</div>';

			return $html; 
			
		}
		
	}
	
}

?>