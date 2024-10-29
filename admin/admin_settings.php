<?php 
/* Admin settings page. */
/* This file used to create plugin settings page */

/* Create new menu item to settings page */
add_action('admin_menu', 'event_booking_settings_page');
function event_booking_settings_page() {
	add_submenu_page('edit.php?post_type=cpt_events', __('Settings', 'wp_event_booking'), __('Settings', 'wp_event_booking'), 'edit_posts', 'wp_event_booking_settings', 'fnc_wp_event_booking_settings');
	add_submenu_page('edit.php?post_type=cpt_events', __('Documentation', 'wp_event_booking'), __('Documentation', 'wp_event_booking'), 'edit_posts', 'wp_event_booking_doc', 'fnc_wp_event_booking_documentation');
	add_submenu_page('edit.php?post_type=cpt_events', __('Extensions', 'wp_event_booking'), __('Extensions', 'wp_event_booking'), 'edit_posts', 'http://awesometogi.com/awesome-event-booking/', '');
	add_action('admin_init', 'register_wp_event_booking_settings');
	add_action('admin_init', 'register_wp_event_booking_email_settings');
	add_action('admin_init', 'register_wp_event_signup_fields');
	add_action('admin_init', 'register_wp_event_styling');
}

function register_wp_event_booking_settings() {
	// register our settings
	register_setting('wp-event-booking-settings', 'defaultCurrencySymbol');
	register_setting('wp-event-booking-settings', 'thousandSeparator');
	register_setting('wp-event-booking-settings', 'decimalSeparator');
	register_setting('wp-event-booking-settings', 'dateWithYearFormat');
	register_setting('wp-event-booking-settings', 'dateWithoutYearFormat');
	register_setting('wp-event-booking-settings', 'monthAndYearFormat');
	register_setting('wp-event-booking-settings', 'dateTimeSeparator');
	register_setting('wp-event-booking-settings', 'timeFormat');
	register_setting('wp-event-booking-settings', 'timeRangeSeparator');
	register_setting('wp-event-booking-settings', 'datepickerFormat');
	register_setting('wp-event-booking-settings', 'show_event_region');
	register_setting('wp-event-booking-settings', 'event_template');
	register_setting('wp-event-booking-settings', 'show_event_city');
	register_setting('wp-event-booking-settings', 'allow_multiple_participants');
	register_setting('wp-event-booking-settings', 'show_event_manager_phone');
	register_setting('wp-event-booking-settings', 'show_event_manager_email');
	register_setting('wp-event-booking-settings', 'show_event_manager_website');
	register_setting('wp-event-booking-settings', 'sendReminderBefore');
	register_setting('wp-event-booking-settings', 'event_manager_register_user');
	register_setting('wp-event-booking-settings', 'available_seat_display');
	register_setting('wp-event-booking-settings', 'Administrator_Email');
	register_setting('wp-event-booking-settings', 'senderFromEmail');
	register_setting('wp-event-booking-settings', 'senderFromName');
	register_setting('wp-event-booking-settings', 'AvailableSeats');
	register_setting('wp-event-booking-settings', 'hideSeatsInfo');
}
function register_wp_event_booking_email_settings() {
	// register our settings
	// User signup event, mail template settings
	register_setting('wp-event-booking-email-settings', 'signUpAdminNotificationSubject');
	register_setting('wp-event-booking-email-settings', 'signUpAdminNotification');
	register_setting('wp-event-booking-email-settings', 'signUpCustomerNotificationSubject');
	register_setting('wp-event-booking-email-settings', 'signUpCustomerNotification');

	//Admin Sign Up from backend.

	register_setting('wp-event-booking-email-settings', 'AdminAddCustomerAdminNotificationSubject');
	register_setting('wp-event-booking-email-settings', 'AdminAddCustomerAdminNotification');
	register_setting('wp-event-booking-email-settings', 'AdminAddCustomerCustomerNotificationSubject');
	register_setting('wp-event-booking-email-settings', 'AdminAddCustomerCustomerNotification');

	// Admin cancel event, mail template settings
	register_setting('wp-event-booking-email-settings', 'cancelAdminNotificationSubject');
	register_setting('wp-event-booking-email-settings', 'cancelAdminNotification');
	register_setting('wp-event-booking-email-settings', 'cancelCustomerNotificationSubject');
	register_setting('wp-event-booking-email-settings', 'cancelCustomerNotification');

	// User cancel event, mail template settings
	register_setting('wp-event-booking-email-settings', 'usercancelAdminNotificationSubject');
	register_setting('wp-event-booking-email-settings', 'usercancelAdminNotification');
	register_setting('wp-event-booking-email-settings', 'usercancelCustomerNotificationSubject');
	register_setting('wp-event-booking-email-settings', 'usercancelCustomerNotification');
	register_setting('wp-event-booking-email-settings', 'sendReminderBeforeSubject');
	register_setting('wp-event-booking-email-settings', 'sendReminderBeforeMessage');

}
function register_wp_event_signup_fields() {
	// Register event signup fields */
	register_setting('wp-event-sign-up-settings', 'show_attendant_first_name');
	register_setting('wp-event-sign-up-settings', 'mandatory_attendant_first_name');
	register_setting('wp-event-sign-up-settings', 'show_attendant_last_name');
	register_setting('wp-event-sign-up-settings', 'mandatory_attendant_last_name');
	register_setting('wp-event-sign-up-settings', 'show_attendant_phone_number');
	register_setting('wp-event-sign-up-settings', 'mandatory_attendant_phone_number');
	register_setting('wp-event-sign-up-settings', 'show_attendant_e_mail_address');
	register_setting('wp-event-sign-up-settings', 'mandatory_attendant_e_mail_address');
	register_setting('wp-event-sign-up-settings', 'show_attendant_address');
	register_setting('wp-event-sign-up-settings', 'mandatory_attendant_address');
	register_setting('wp-event-sign-up-settings', 'show_attendant_zip_code');
	register_setting('wp-event-sign-up-settings', 'mandatory_attendant_zip_code');
	register_setting('wp-event-sign-up-settings', 'show_attendant_city');
	register_setting('wp-event-sign-up-settings', 'mandatory_attendant_city');
	register_setting('wp-event-sign-up-settings', 'show_attendant_state');
	register_setting('wp-event-sign-up-settings', 'mandatory_attendant_state');
	register_setting('wp-event-sign-up-settings', 'show_company_name');
	register_setting('wp-event-sign-up-settings', 'mandatory_company_name');
	register_setting('wp-event-sign-up-settings', 'show_company_address');
	register_setting('wp-event-sign-up-settings', 'mandatory_company_address');
	register_setting('wp-event-sign-up-settings', 'show_company_zip_code');
	register_setting('wp-event-sign-up-settings', 'mandatory_company_zip_code');
	register_setting('wp-event-sign-up-settings', 'show_company_city');
	register_setting('wp-event-sign-up-settings', 'mandatory_company_city');
	register_setting('wp-event-sign-up-settings', 'show_company_state');
	register_setting('wp-event-sign-up-settings', 'mandatory_company_state');
	register_setting('wp-event-sign-up-settings', 'show_company_vat_number');
	register_setting('wp-event-sign-up-settings', 'mandatory_company_vat_number');
	register_setting('wp-event-sign-up-settings', 'show_company_contact_person');
	register_setting('wp-event-sign-up-settings', 'mandatory_company_contact_person');
	register_setting('wp-event-sign-up-settings', 'show_company_contact_person_phone_number');
	register_setting('wp-event-sign-up-settings', 'mandatory_company_contact_person_phone_number');
	register_setting('wp-event-sign-up-settings', 'show_company_invoice_e_mail_address');
	register_setting('wp-event-sign-up-settings', 'mandatory_company_invoice_e_mail_address');
	register_setting('wp-event-sign-up-settings', 'show_company_reference');
	register_setting('wp-event-sign-up-settings', 'mandatory_company_reference');
	register_setting('wp-event-sign-up-settings', 'show_personal_identification_number');
	register_setting('wp-event-sign-up-settings', 'mandatory_personal_identification_number');
}
function register_wp_event_styling() {
	register_setting('wp-event-styling', 'submitButtonColor');
	register_setting('wp-event-styling', 'submitButtonTextColor');
	//register_setting('wp-event-styling', 'submitButtonBackgroundColor');
}
/* Available hooks, call back functions are currently in use.
add_action('wpeb_settings_menu', 'fnc_wpeb_settings_menu');
add_action('wpeb_settings_content', 'fnc_wpeb_settings_general');
add_action('wpeb_settings_new_menu_item', 'fnc_wpeb_settings_general_menu');
add_action('wpeb_settings_new_menu_item', 'fnc_wpeb_settings_email_menu');
 **/

function fnc_wp_event_booking_settings() {
	$active_tab = '';
	if (isset($_GET['tab'])) {
		$active_tab = $_GET['tab'];
	} // end if
	?>
<div class="wrap">
	<a href="http://awesometogi.com/awesome-event-booking/" target="_blank">
			<div style="text-align: center;">
				<img src="<?php echo WPEB_URL . 'src/img/banner-for-extensions.jpg' ?>">
			</div>
		</a>
<?php do_action('wpeb_settings_menu', $active_tab);
	do_action('wpeb_settings_content', $active_tab);
	?>
</div>
<?php
}
add_action('wpeb_settings_menu', 'fnc_wpeb_settings_menu');

function fnc_wpeb_settings_menu($active_tab) {
	?>
<h2 class="nav-tab-wrapper">
<?php do_action('wpeb_settings_new_menu_item', $active_tab);?>
</h2>
<?php
}
/* Add Tab - Start*/
add_action('wpeb_settings_new_menu_item', 'fnc_wpeb_settings_general_menu');
function fnc_wpeb_settings_general_menu($active_tab) {?>
<a href="?post_type=cpt_events&page=wp_event_booking_settings" class="nav-tab <?php echo $active_tab == '' ? 'nav-tab-active' : ''; ?>"><?php _e('General Settings', 'wp_event_booking');?></a>
<?php }

add_action('wpeb_settings_new_menu_item', 'fnc_wpeb_settings_email_menu');
function fnc_wpeb_settings_email_menu($active_tab) {?>
<a href="?post_type=cpt_events&page=wp_event_booking_settings&tab=email" class="nav-tab <?php echo $active_tab == 'email' ? 'nav-tab-active' : ''; ?>"><?php _e('Email Settings', 'wp_event_booking');?></a>
<?php }

add_action('wpeb_settings_new_menu_item', 'fnc_wpeb_settings_event');
function fnc_wpeb_settings_event($active_tab) {?>
<a href="?post_type=cpt_events&page=wp_event_booking_settings&tab=event" class="nav-tab <?php echo $active_tab == 'event' ? 'nav-tab-active' : ''; ?>"><?php _e('Event Settings', 'wp_event_booking');?></a>
<?php }

add_action('wpeb_settings_new_menu_item', 'fnc_wpeb_settings_styling');
function fnc_wpeb_settings_styling($active_tab) {?>
<a href="?post_type=cpt_events&page=wp_event_booking_settings&tab=styling" class="nav-tab <?php echo $active_tab == 'styling' ? 'nav-tab-active' : ''; ?>"><?php _e('Styling', 'wp_event_booking');?></a>
<?php }
/* Add Tab - End*/
/* Include Tab content - Start */
add_action('wpeb_settings_content', 'fnc_wpeb_settings_general');
function fnc_wpeb_settings_general($active_tab) {
	if (empty($active_tab)) {
		$general_template = WPEB_DIR . 'admin/templates/general.php';
		require_once $general_template;
	}
}

add_action('wpeb_settings_content', 'fnc_wpeb_settings_email');
function fnc_wpeb_settings_email($active_tab) {
	if ($active_tab == 'email') {
		$template = WPEB_DIR . 'admin/templates/email-template.php';
		require_once $template;
	}
}
add_action('wpeb_settings_content', 'fnc_wpeb_event_settings');
function fnc_wpeb_event_settings($active_tab) {
	if ($active_tab == 'event') {
		$template = WPEB_DIR . 'admin/templates/event.php';
		require_once $template;
	}
}
add_action('wpeb_settings_content', 'fnc_wpeb_event_styling');
function fnc_wpeb_event_styling($active_tab) {
	if ($active_tab == 'styling') {
		$template = WPEB_DIR . 'admin/templates/styling.php';
		require_once $template;
	}
}
/* Include Tab content - End */
/* Email template hooks - Start */
add_action('wpeb_event_booking_email_templates', 'fnc_new_event_sign_up_admin_notification');
function fnc_new_event_sign_up_admin_notification() {
	?>
  <tr valign="top">
      <th scope="row"><?php _e('Event sign up - Admin notification Subject', 'wp_event_booking');?></th>
      <td>
        <input type="text" name="signUpAdminNotificationSubject" value="<?php echo esc_attr(get_option('signUpAdminNotificationSubject')); ?>" />
        <p class="tooltip description"><?php _e('Possible shortcodes', 'wp_event_booking');?> :
        %event_title%, %event_date_time%, %event_price%, %event_location%

        </p>
      </td>
  </tr>
  <tr class="signUpAdminNotification-wrap">
    <th>
        <label for="signUpAdminNotification"><?php _e('Event sign up - Admin notification Body', 'wp_event_booking');?></label>
    </th>
    <td><?php wp_editor(get_option('signUpAdminNotification'), 'signUpAdminNotification', array('textarea_rows' => 10, 'teeny' => false, 'quicktags' => true));?>
        <p class="description"><?php _e('Possible shortcodes', 'wp_event_booking');?> : %event_title%, %event_title%, %event_date_time%, %event_price%,%event_location%, %user_details%</p>
    </td>
  </tr>
  <tr><td colspan="2"><hr /></td></tr>
  <?php
}
add_action('wpeb_event_booking_email_templates', 'fnc_new_event_sign_up_customer_notification');
function fnc_new_event_sign_up_customer_notification() {
	?>
  <tr valign="top">
      <th scope="row"><?php _e('Event sign up - Customer notification Subject', 'wp_event_booking');?></th>
      <td>
        <input type="text" name="signUpCustomerNotificationSubject" value="<?php echo esc_attr(get_option('signUpCustomerNotificationSubject')); ?>" />
        <p class="tooltip description"><?php _e('Possible shortcodes', 'wp_event_booking');?> : %event_title%, %event_date_time%, %event_price%, %event_location%, %booking_id%, %attendant_first_name%, %attendant_last_name%, %attendant_phone_number%. %attendant_e_mail_address%, %attendant_address%, %attendant_zip_code%, %attendant_city%, %attendant_state%, %company_name%, %company_address%, %company_zip_code%, %company_city%, %company_state%, %company_vat_number%, %company_contact_person%, %company_contact_person_phone_number%, %company_invoice_e_mail_address%, %company_reference%, %personal_identification_number%</p>
      </td>
  </tr>
<tr class="signUpCustomerNotification-wrap">
    <th>
        <label for="description"><?php _e('Event sign up - Customer notification Body', 'wp_event_booking');?></label>
    </th>
    <td><?php wp_editor(get_option('signUpCustomerNotification'), 'signUpCustomerNotification', array('textarea_rows' => 10, 'teeny' => false, 'quicktags' => false));?>
        <p class="description"><?php _e('Possible shortcodes', 'wp_event_booking');?> : %event_title%, %event_date_time%, %event_price%, %event_location%, %booking_id%, %attendant_first_name%, %attendant_last_name%, %attendant_phone_number%. %attendant_e_mail_address%, %attendant_address%, %attendant_zip_code%, %attendant_city%, %attendant_state%, %company_name%, %company_address%, %company_zip_code%, %company_city%, %company_state%, %company_vat_number%, %company_contact_person%, %company_contact_person_phone_number%, %company_invoice_e_mail_address%, %company_reference%, %personal_identification_number%, %credentials%</p>
    </td>
</tr>
<tr><td colspan="2"><hr /></td></tr>
<?php
}
/* Admin Add Customer - Admin Notifications */
add_action('wpeb_event_booking_email_templates', 'FncAdminAddCustomerAdminNotifications');
function FncAdminAddCustomerAdminNotifications() {
	?>
  <tr valign="top">
      <th scope="row"><?php _e('Event sign up(Dashboard) - Admin notification Subject', 'wp_event_booking');?></th>
      <td>
        <input type="text" name="AdminAddCustomerAdminNotificationSubject" value="<?php echo esc_attr(get_option('AdminAddCustomerAdminNotificationSubject')); ?>" />
        <p class="tooltip description"><?php _e('Possible shortcodes', 'wp_event_booking');?> :
        %event_title%, %event_date_time%, %event_price%, %event_location%

        </p>
      </td>
  </tr>
  <tr class="signUpAdminNotification-wrap">
    <th>
        <label for="signUpAdminNotification"><?php _e('Event sign up(Dashboard) - Admin notification Body', 'wp_event_booking');?></label>
    </th>
    <td><?php wp_editor(get_option('AdminAddCustomerAdminNotification'), 'AdminAddCustomerAdminNotification', array('textarea_rows' => 10, 'teeny' => false, 'quicktags' => true));?>
        <p class="description"><?php _e('Possible shortcodes', 'wp_event_booking');?> : %event_title%, %event_title%, %event_date_time%, %event_price%,%event_location%, %user_details%</p>
    </td>
  </tr>
  <tr><td colspan="2"><hr /></td></tr>
  <?php
}
/* Admin Add Customer - Customer Notifications */
add_action('wpeb_event_booking_email_templates', 'FncAdminAddCustomerCustomerNotifications');
function FncAdminAddCustomerCustomerNotifications() {
	?>
  <tr valign="top">
      <th scope="row"><?php _e('Event sign up(Dashboard) - Customer notification Subject', 'wp_event_booking');?></th>
      <td>
        <input type="text" name="AdminAddCustomerCustomerNotificationSubject" value="<?php echo esc_attr(get_option('AdminAddCustomerCustomerNotificationSubject')); ?>" />
        <p class="tooltip description"><?php _e('Possible shortcodes', 'wp_event_booking');?> : %event_title%, %event_date_time%, %event_price%, %event_location%, %booking_id%, %attendant_first_name%, %attendant_last_name%, %attendant_phone_number%. %attendant_e_mail_address%, %attendant_address%, %attendant_zip_code%, %attendant_city%, %attendant_state%, %company_name%, %company_address%, %company_zip_code%, %company_city%, %company_state%, %company_vat_number%, %company_contact_person%, %company_contact_person_phone_number%, %company_invoice_e_mail_address%, %company_reference%, %personal_identification_number%</p>
      </td>
  </tr>
<tr class="signUpCustomerNotification-wrap">
    <th>
        <label for="description"><?php _e('Event sign up(Dashboard) - Customer notification Body', 'wp_event_booking');?></label>
    </th>
    <td><?php wp_editor(get_option('AdminAddCustomerCustomerNotification'), 'AdminAddCustomerCustomerNotification', array('textarea_rows' => 10, 'teeny' => false, 'quicktags' => false));?>
        <p class="description"><?php _e('Possible shortcodes', 'wp_event_booking');?> : %event_title%, %event_date_time%, %event_price%, %event_location%, %booking_id%, %attendant_first_name%, %attendant_last_name%, %attendant_phone_number%. %attendant_e_mail_address%, %attendant_address%, %attendant_zip_code%, %attendant_city%, %attendant_state%, %company_name%, %company_address%, %company_zip_code%, %company_city%, %company_state%, %company_vat_number%, %company_contact_person%, %company_contact_person_phone_number%, %company_invoice_e_mail_address%, %company_reference%, %personal_identification_number%, %credentials%</p>
    </td>
</tr>
<tr><td colspan="2"><hr /></td></tr>
<?php
}
function fnc_event_cancel_admin_notification() {
	?>
  <tr valign="top">
      <th scope="row"><?php _e('Admin Cancel Sign Up - Admin notification Subject', 'wp_event_booking');?></th>
      <td>
        <input type="text" name="cancelAdminNotificationSubject" value="<?php echo esc_attr(get_option('cancelAdminNotificationSubject')); ?>" />
        <p class="tooltip description"><?php _e('Possible shortcodes', 'wp_event_booking');?> : %event_title%, %event_date_time%, %event_price%, %event_location%, %booking_id%, %attendant_first_name%, %attendant_last_name%, %attendant_phone_number%. %attendant_e_mail_address%, %attendant_address%, %attendant_zip_code%, %attendant_city%, %attendant_state%, %company_name%, %company_address%, %company_zip_code%, %company_city%, %company_state%, %company_vat_number%, %company_contact_person%, %company_contact_person_phone_number%, %company_invoice_e_mail_address%, %company_reference%, %personal_identification_number%</p>
      </td>
  </tr>
  <tr class="cancelAdminNotification-wrap">
    <th>
        <label for="cancelAdminNotification"><?php _e('Admin Cancel Sign Up - Admin notification Body', 'wp_event_booking');?></label>
    </th>
    <td><?php wp_editor(get_option('cancelAdminNotification'), 'cancelAdminNotification', array('textarea_rows' => 10, 'teeny' => false, 'quicktags' => true));?>
        <p class="description"><?php _e('Possible shortcodes', 'wp_event_booking');?> : %event_title%, %event_date_time%, %event_price%, %event_location%, %booking_id%, %attendant_first_name%, %attendant_last_name%, %attendant_phone_number%. %attendant_e_mail_address%, %attendant_address%, %attendant_zip_code%, %attendant_city%, %attendant_state%, %company_name%, %company_address%, %company_zip_code%, %company_city%, %company_state%, %company_vat_number%, %company_contact_person%, %company_contact_person_phone_number%, %company_invoice_e_mail_address%, %company_reference%, %personal_identification_number%</p>
    </td>
  </tr>
  <tr><td colspan="2"><hr /></td></tr>
  <?php
}
add_action('wpeb_event_booking_email_templates', 'fnc_event_cancel_admin_notification');

function fnc_event_cancel_customer_notification() {
	?>
  <tr valign="top">
      <th scope="row"><?php _e('Admin Cancel Sign Up - Customer notification Subject', 'wp_event_booking');?></th>
      <td>
        <input type="text" name="cancelCustomerNotificationSubject" value="<?php echo esc_attr(get_option('cancelCustomerNotificationSubject')); ?>" />
        <p class="tooltip description"><?php _e('Possible shortcodes', 'wp_event_booking');?> : %event_title%, %event_date_time%, %event_price%, %event_location%, %booking_id%, %attendant_first_name%, %attendant_last_name%, %attendant_phone_number%. %attendant_e_mail_address%, %attendant_address%, %attendant_zip_code%, %attendant_city%, %attendant_state%, %company_name%, %company_address%, %company_zip_code%, %company_city%, %company_state%, %company_vat_number%, %company_contact_person%, %company_contact_person_phone_number%, %company_invoice_e_mail_address%, %company_reference%, %personal_identification_number%</p>
      </td>
  </tr>
  <tr class="cancelCustomerNotification-wrap">
    <th>
        <label for="cancelCustomerNotification"><?php _e('Admin Cancel Sign Up - Customer notification Body', 'wp_event_booking');?></label>
    </th>
    <td><?php wp_editor(get_option('cancelCustomerNotification'), 'cancelCustomerNotification', array('textarea_rows' => 10, 'teeny' => false, 'quicktags' => true));?>
        <p class="description"><?php _e('Possible shortcodes', 'wp_event_booking');?> : %event_title%, %event_date_time%, %event_price%, %event_location%, %booking_id%, %attendant_first_name%, %attendant_last_name%, %attendant_phone_number%. %attendant_e_mail_address%, %attendant_address%, %attendant_zip_code%, %attendant_city%, %attendant_state%, %company_name%, %company_address%, %company_zip_code%, %company_city%, %company_state%, %company_vat_number%, %company_contact_person%, %company_contact_person_phone_number%, %company_invoice_e_mail_address%, %company_reference%, %personal_identification_number%</p>
    </td>
  </tr>
  <tr><td colspan="2">
  <hr /></td></tr>

  <?php
}
add_action('wpeb_event_booking_email_templates', 'fnc_event_cancel_customer_notification');

function fnc_event_cancel_2_admin_notification() {
	?>
  <tr valign="top">
      <th scope="row"><?php _e('User Cancel Signup - Admin notification Subject', 'wp_event_booking');?></th>
      <td>
        <input type="text" name="usercancelAdminNotificationSubject" value="<?php echo esc_attr(get_option('usercancelAdminNotificationSubject')); ?>" />
        <p class="tooltip description"><?php _e('Possible shortcodes', 'wp_event_booking');?> : %event_title%, %event_date_time%, %event_price%, %event_location%, %booking_id%, %attendant_first_name%, %attendant_last_name%, %attendant_phone_number%. %attendant_e_mail_address%, %attendant_address%, %attendant_zip_code%, %attendant_city%, %attendant_state%, %company_name%, %company_address%, %company_zip_code%, %company_city%, %company_state%, %company_vat_number%, %company_contact_person%, %company_contact_person_phone_number%, %company_invoice_e_mail_address%, %company_reference%, %personal_identification_number%</p>
      </td>
  </tr>
  <tr class="usercancelAdminNotification-wrap">
    <th>
        <label for="usercancelAdminNotification"><?php _e('User Cancel Signup - Admin notification Body', 'wp_event_booking');?></label>
    </th>
    <td><?php wp_editor(get_option('usercancelAdminNotification'), 'usercancelAdminNotification', array('textarea_rows' => 10, 'teeny' => false, 'quicktags' => true));?>
        <p class="description"><?php _e('Possible shortcodes', 'wp_event_booking');?> : %event_title%, %event_date_time%, %event_price%, %event_location%, %booking_id%, %attendant_first_name%, %attendant_last_name%, %attendant_phone_number%. %attendant_e_mail_address%, %attendant_address%, %attendant_zip_code%, %attendant_city%, %attendant_state%, %company_name%, %company_address%, %company_zip_code%, %company_city%, %company_state%, %company_vat_number%, %company_contact_person%, %company_contact_person_phone_number%, %company_invoice_e_mail_address%, %company_reference%, %personal_identification_number%</p>
    </td>
  </tr>
  <tr><td colspan="2"><hr /></td></tr>
  <?php
}
add_action('wpeb_event_booking_email_templates', 'fnc_event_cancel_2_admin_notification');

function fnc_event_cancel_2_customer_notification() {
	?>
  <tr valign="top">
      <th scope="row"><?php _e('User Cancel Signup - Customer notification Subject', 'wp_event_booking');?></th>
      <td>
        <input type="text" name="usercancelCustomerNotificationSubject" value="<?php echo esc_attr(get_option('usercancelCustomerNotificationSubject')); ?>" />
        <p class="tooltip description"><?php _e('Possible shortcodes', 'wp_event_booking');?> : %event_title%, %event_date_time%, %event_price%, %event_location%, %booking_id%, %attendant_first_name%, %attendant_last_name%, %attendant_phone_number%. %attendant_e_mail_address%, %attendant_address%, %attendant_zip_code%, %attendant_city%, %attendant_state%, %company_name%, %company_address%, %company_zip_code%, %company_city%, %company_state%, %company_vat_number%, %company_contact_person%, %company_contact_person_phone_number%, %company_invoice_e_mail_address%, %company_reference%, %personal_identification_number%</p>
      </td>
  </tr>
  <tr class="usercancelCustomerNotification-wrap">
    <th>
        <label for="usercancelCustomerNotification"><?php _e('User Cancel Signup - Customer notification Body', 'wp_event_booking');?></label>
    </th>
    <td><?php wp_editor(get_option('usercancelCustomerNotification'), 'usercancelCustomerNotification', array('textarea_rows' => 10, 'teeny' => false, 'quicktags' => true));?>
        <p class="description"><?php _e('Possible shortcodes', 'wp_event_booking');?> : %event_title%, %event_date_time%, %event_price%, %event_location%, %booking_id%, %attendant_first_name%, %attendant_last_name%, %attendant_phone_number%. %attendant_e_mail_address%, %attendant_address%, %attendant_zip_code%, %attendant_city%, %attendant_state%, %company_name%, %company_address%, %company_zip_code%, %company_city%, %company_state%, %company_vat_number%, %company_contact_person%, %company_contact_person_phone_number%, %company_invoice_e_mail_address%, %company_reference%, %personal_identification_number%</p>
    </td>
  </tr>
  <tr><td colspan="2">
  <hr /></td></tr>

  <?php
}
add_action('wpeb_event_booking_email_templates', 'fnc_sendReminderBefore_notification');

function fnc_sendReminderBefore_notification() {
	?>
  <tr valign="top">
      <th scope="row"><?php _e('Event Reminder Subject', 'wp_event_booking');?></th>
      <td>
        <input type="text" name="sendReminderBeforeSubject" value="<?php echo esc_attr(get_option('sendReminderBeforeSubject')); ?>" />
        <p class="tooltip description"><?php _e('Possible shortcodes', 'wp_event_booking');?> : %event_title%, %event_date_time%, %event_price%, %event_location%, %booking_id%, %attendant_first_name%, %attendant_last_name%, %attendant_phone_number%. %attendant_e_mail_address%, %attendant_address%, %attendant_zip_code%, %attendant_city%, %attendant_state%, %company_name%, %company_address%, %company_zip_code%, %company_city%, %company_state%, %company_vat_number%, %company_contact_person%, %company_contact_person_phone_number%, %company_invoice_e_mail_address%, %company_reference%, %personal_identification_number%, %site_title%</p>
      </td>
  </tr>
  <tr class="sendReminderBeforeMessage-wrap">
    <th>
        <label for="sendReminderBeforeMessage"><?php _e('Event Reminder Message', 'wp_event_booking');?></label>
    </th>
    <td><?php wp_editor(get_option('sendReminderBeforeMessage'), 'sendReminderBeforeMessage', array('textarea_rows' => 10, 'teeny' => false, 'quicktags' => true));?>
        <p class="description"><?php _e('Possible shortcodes', 'wp_event_booking');?> : %event_title%, %event_date_time%, %event_price%, %event_location%, %booking_id%, %attendant_first_name%, %attendant_last_name%, %attendant_phone_number%. %attendant_e_mail_address%, %attendant_address%, %attendant_zip_code%, %attendant_city%, %attendant_state%, %company_name%, %company_address%, %company_zip_code%, %company_city%, %company_state%, %company_vat_number%, %company_contact_person%, %company_contact_person_phone_number%, %company_invoice_e_mail_address%, %company_reference%, %personal_identification_number%, %site_title%</p>
    </td>
  </tr>
  <tr><td colspan="2">
  <hr /></td></tr>

  <?php
}
/* Email template hooks - End */
/* Sign up fields hooks - Start */
/*
add_action('wpeb_event_signup_fields', 'fnc_wpeb_event_signup_fields');
function fnc_wpeb_event_signup_fields()
{

}
 */
/* Sign up fields hooks - End */

/* Event booking documentation
Usage:
add_action('wpeb_documentation', 'callback_function');
function callback_function()
{
<h3>Title</h3>
<div><p>Description</p></div>
}
 */

add_action('wpeb_event_booking_email_templates', 'fnc_event_cancel_2_customer_notification');
function fnc_wp_event_booking_documentation() {
	echo '<div class="wrap">';
	echo '<div class="documentation-accordion">';
	do_action('wpeb_documentation');
	echo '</div>';
	echo '</div>';
}
function fnc_wp_event_booking_extention() {
	echo '<div class="wrap">';
	echo '<div class="documentation-accordion">';
	do_action('wpeb_documentation');
	echo '</div>';
	echo '</div>';
}

add_action('wpeb_documentation', 'fnc_callback_doc_general', 10);
function fnc_callback_doc_general() {
	require_once WPEB_DIR . 'admin/docs/general.php';
}
add_action('wpeb_documentation', 'fnc_callback_doc_email', 20);
function fnc_callback_doc_email() {
	require_once WPEB_DIR . 'admin/docs/email.php';
}
add_action('wpeb_documentation', 'fnc_callback_doc_event', 30);
function fnc_callback_doc_event() {
	require_once WPEB_DIR . 'admin/docs/event.php';
}
add_action('wpeb_documentation', 'fnc_callback_doc_style', 40);
function fnc_callback_doc_style() {
	require_once WPEB_DIR . 'admin/docs/style.php';
}
add_action('wpeb_documentation', 'fnc_callback_doc_shortcodes', 50);
function fnc_callback_doc_shortcodes() {
	require_once WPEB_DIR . 'admin/docs/shortcodes.php';
}

function wpse_my_custom_script() {
	?>
	<script type="text/javascript">
		jQuery(document).ready( function($) {
			$( "ul li a[href$='http://awesometogi.com/awesome-event-booking']" ).attr( 'target', '_blank' );
		});
	</script>
	<?php
}
add_action('admin_head', 'wpse_my_custom_script');
