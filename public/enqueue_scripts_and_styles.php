<?php

/** Enqueue scripts and styles for plugin.
 *
 * @since    1.0.0
 */
function enqueue_scripts_and_styles() {

	/* enqueue scripts */

	wp_enqueue_script('jquery-ui-accordion');
	//wp_enqueue_script('jquery-ui-tabs');

	//wp_enqueue_script( 'select2-min', WPEB_URL. 'src/js/select2.min.js', array( 'jquery' ), WPEB_VERSION, true);
	wp_enqueue_script('tablesorter', WPEB_URL . 'src/js/jquery.tablesorter.min.js', array('jquery'), WPEB_VERSION, true);
	wp_enqueue_script(WPEB_NAME, WPEB_URL . 'src/js/scripts.js', array('jquery', 'tablesorter'), time(), true); //WPEB_VERSION
	wp_localize_script(WPEB_NAME, 'wpeb',
		array('ajax_url' => admin_url('admin-ajax.php')));

	// Load the datepicker script (pre-registered in WordPress).
	//	wp_enqueue_script( 'jquery-ui-datepicker' );
	//wp_enqueue_script( 'jquery-timepicker-min', WPEB_URL. 'src/js/jquery.timepicker.min.js', array( 'jquery' ),WPEB_VERSION , true );
	//wp_enqueue_script( 'datepair', WPEB_URL. 'src/js/datepair.js', array( 'jquery' ),WPEB_VERSION , true );
	//wp_enqueue_script( 'jquery-datepair-js', WPEB_URL. 'src/js/jquery.datepair.js', array( 'jquery' ),WPEB_VERSION , true );
	//wp_enqueue_script( 'bootstrap-datepicker-js', WPEB_URL. 'src/js/bootstrap-datepicker.js', array( 'jquery' ),WPEB_VERSION , true );

	/*<script type="text/javascript" src="datepair.js"></script>
	<script type="text/javascript" src="jquery.datepair.js"></script>*/
	/* enqueue styles */

	wp_enqueue_style(WPEB_NAME, WPEB_URL . 'src/css/styles.css', array(), time(), 'all');
	//wp_enqueue_style('select2-min', WPEB_URL . 'src/css/select2.min.css', array(), WPEB_VERSION, 'all' );
	//wp_enqueue_style('bootstrap', WPEB_URL . 'src/css/font-awesome.min.css', array(), WPEB_VERSION, 'all' );

	/* //You need styling for the datepicker. For simplicity I've linked to Google's hosted jQuery UI CSS.
	And Jquery UI for accordion*/
	//wp_register_style('jquery-ui', WPEB_URL . 'src/css/jquery-ui.css');

	wp_register_style('jquery-ui', '//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css');
	wp_enqueue_style('jquery-ui');
	//wp_enqueue_style('jquery-timepicker-min', WPEB_URL . 'src/css/jquery.timepicker.min.css', array(), WPEB_VERSION, 'all' );
	//wp_enqueue_style('bootstrap-datepicker', WPEB_URL . 'src/css/bootstrap-datepicker.css', array(), WPEB_VERSION, 'all' );
	wp_enqueue_style('tablesorter', WPEB_URL . 'src/css/tablesorter.css', array(), WPEB_VERSION, 'all');
}
add_action('wp_enqueue_scripts', 'enqueue_scripts_and_styles');
