<?php 
/*
 * Plugin Name: Betting Payback Calculator
 * Plugin URI: https://wordpress.org/plugins/betting-payback-calculator/
 * Description: Calculate the theoretical payback percentage for a set of odds
 * Version: 1.0
 * Author: https://www.oddsvalue.com
*/

add_action( 'admin_menu', 'betting_payback_calculator_prepare_admin_menu');


function betting_payback_calculator_prepare_admin_menu() {
	// REQUIRE ADMIN FUNCTIONS
	require_once __DIR__.'/assets/php/admin_functions.php';
	betting_payback_calculator_create_menu();
}

add_shortcode( 'betting_payback_calculator', 'output_betting_payback_calculator');

function output_betting_payback_calculator($atts) {
	require_once __DIR__.'/assets/php/output_class.php';
	
	$Output_Class = new Betting_Payback_Calculator_Output($atts);

	return $Output_Class->outputPage($atts);
}


?>