<?php

/**
 *
 * @link              http://www.togidata.com
 * @since             1.0.0
 * @package           Wp_event_booking
 *
 * @wordpress-plugin
 * Plugin Name:       Awesome Event Booking
 * Plugin URI:        http://awesometogi.com/awesome-event-booking/
 * Description:       You can now easily create events, accept bookings and manage these with our powerful Event Booking plugin.
 * Version:           2.6.5
 * Author:            Awesome TOGI
 * Author URI:        https://awesometogi.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp_event_booking
 * Domain Path:       /languages
 * Tested up to:      6.5.5
 *
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WPEB_VERSION', '2.6.5');

define('WPEB_NAME', __('Awesome Event Booking', 'wp_event_booking'));

define('WPEB_SLUG', 'BASIC'); // Never change this. It need to check addon versions.

define('WPEB_URL', plugin_dir_url(__FILE__));
define('WPEB_DIR', plugin_dir_path(__FILE__));

function fnc_activate_wpeb_callback()
{
    //do_action( 'my_plugin_activate' );
    require WPEB_DIR . 'install.php'; //Functions that need when installing plugin
}
register_activation_hook(__FILE__, 'fnc_activate_wpeb_callback');

/**
 * Include file includer
 */
require WPEB_DIR . 'init.php'; // Include all other files

add_action('plugins_loaded', 'wpeb_load_textdomain');
function wpeb_load_textdomain()
{
    load_plugin_textdomain('wp_event_booking', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
function WPEB()
{
    return true;
}

add_action( 'init', 'rfqgra_check_rfq_parent_active' );
function rfqgra_check_rfq_parent_active() {
    require 'captcha/admin/admin_functions.php'; //Include Admin files
}