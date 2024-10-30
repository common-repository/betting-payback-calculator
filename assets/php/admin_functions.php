<?php

require_once __DIR__.'/admin_class.php';

function betting_payback_calculator_create_menu() {
	add_options_page(
		'Betting Payback Options',
		'Betting Payback',
		'manage_options',
		Betting_Payback_Calculator_Admin::getPage(),
		array(
			Betting_Payback_Calculator_Admin::getInstance(),
			'outputSettingsPage'
		)
	);
}


?>