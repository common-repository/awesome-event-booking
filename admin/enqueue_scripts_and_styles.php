<?php

/** Enqueue scripts and styles for plugin.
 *
 * @since    1.0.0
 */
function enqueue_admin_scripts_and_styles() {

	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('wp-color-picker');
	wp_enqueue_script('jquery-ui-accordion');

	wp_enqueue_style('jquery-ui-accordion', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', array(), WPEB_VERSION, 'all');

	wp_enqueue_script('select2-min', WPEB_URL . 'src/js/select2.js', array('jquery'), WPEB_VERSION, true);
	wp_enqueue_script('tablesorter', WPEB_URL . 'src/js/jquery.tablesorter.min.js', array('jquery'), WPEB_VERSION, true);
	
	/* enqueue styles */

	wp_enqueue_style(WPEB_NAME, WPEB_URL . 'src/css/admin-styles.css', array(), WPEB_VERSION, 'all');
	wp_enqueue_style('select2-min', WPEB_URL . 'src/css/select2.min.css', array(), WPEB_VERSION, 'all');
	wp_enqueue_style('bootstrap', WPEB_URL . 'src/css/font-awesome.min.css', array(), WPEB_VERSION, 'all');
	// You need styling for the datepicker. For simplicity I've linked to Google's hosted jQuery UI CSS.
	//wp_register_style( 'jquery-ui', 'http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
	//wp_enqueue_style( 'jquery-ui' );
	wp_enqueue_style('jquery-timepicker-min', WPEB_URL . 'src/css/jquery.timepicker.min.css', array(), WPEB_VERSION, 'all');
	wp_enqueue_style('bootstrap-datepicker', WPEB_URL . 'src/css/bootstrap-datepicker.css', array(), WPEB_VERSION, 'all');
	wp_enqueue_style('tablesorter', WPEB_URL . 'src/css/tablesorter.css', array(), WPEB_VERSION, 'all');
}
add_action('admin_enqueue_scripts', 'enqueue_admin_scripts_and_styles');

function enqueue_admin_scripts_bootstrap_datepicker()
{
	// Load the datepicker script (pre-registered in WordPress).
	//	wp_enqueue_script( 'jquery-ui-datepicker' );
	
	wp_enqueue_script('jquery-timepicker-min', WPEB_URL . 'src/js/jquery.timepicker.js', array('jquery'), WPEB_VERSION, true);
	wp_enqueue_script('datepair', WPEB_URL . 'src/js/datepair.js', array('jquery'), rand(), true);
	wp_enqueue_script('jquery-datepair-js', WPEB_URL . 'src/js/jquery.datepair.js', array('jquery'), WPEB_VERSION, true);
	wp_enqueue_script('bootstrap-datepicker-js', WPEB_URL . 'src/js/bootstrap-datepicker.js', array('jquery'), WPEB_VERSION, true);
	wp_enqueue_script(WPEB_NAME, WPEB_URL . 'src/js/admin-scripts.js', array('jquery', 'wp-color-picker'), rand(), true);

	/*<script type="text/javascript" src="datepair.js"></script>
	<script type="text/javascript" src="jquery.datepair.js"></script>*/

}
add_action('admin_enqueue_scripts', 'enqueue_admin_scripts_bootstrap_datepicker', 100);


