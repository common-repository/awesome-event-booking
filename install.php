<?php
if (!get_option('event_manager_register_user')) {
	update_option('event_manager_register_user', 'yes');
}
/*
Installing Settings  - Start
 */
if (!get_option('defaultCurrencySymbol')) {
	update_option('defaultCurrencySymbol', '$');
}
if (!get_option('thousandSeparator')) {
	update_option('thousandSeparator', ',');
}
if (!get_option('decimalSeparator')) {
	update_option('decimalSeparator', '.');
}
if (!get_option('dateWithYearFormat')) {
	update_option('dateWithYearFormat', 'F j, Y');
}
if (!get_option('dateWithoutYearFormat')) {
	update_option('dateWithoutYearFormat', 'F j');
}
if (!get_option('monthAndYearFormat')) {
	update_option('monthAndYearFormat', 'F Y');
}

if (!get_option('dateTimeSeparator')) {
	update_option('dateTimeSeparator', '@');
}
if (!get_option('timeFormat')) {
	update_option('timeFormat', 'h:ia');
}
if (!get_option('timeRangeSeparator')) {
	update_option('timeRangeSeparator', '-');
}
if (!get_option('datepickerFormat')) {
	update_option('datepickerFormat', '0');
}
if (!get_option('show_event_region')) {
	update_option('show_event_region', '');
}
if (!get_option('event_template')) {
	update_option('event_template', '');
}
if (!get_option('show_event_city')) {
	update_option('show_event_city', '');
}
if (!get_option('allow_multiple_participants')) {
	update_option('allow_multiple_participants', 'true');
}

if (!get_option('available_seat_display')) {
	update_option('available_seat_display', '%total_booking%/%total_seats% seats taken.');
}
if (!get_option('Administrator_Email')) {
	update_option('Administrator_Email', get_option('admin_email'));
}

if (!get_option('senderFromEmail')) {
	// Get the site domain and get rid of www.
	$sitename = strtolower($_SERVER['SERVER_NAME']);
	if (substr($sitename, 0, 4) == 'www.') {
		$sitename = substr($sitename, 4);
	}

	$from_email = 'wordpress@' . $sitename;
	update_option('senderFromEmail', $from_email);
}
if (!get_option('senderFromName')) {
	$from_name = get_bloginfo('name');
	update_option('senderFromName', $from_name);
}
if (!get_option('AvailableSeats')) {
	update_option('AvailableSeats', 'true');
}

if (!get_option('show_event_manager_phone')) {
	update_option('show_event_manager_phone', 'true');
}
if (!get_option('show_event_manager_email')) {
	update_option('show_event_manager_email', 'true');
}
if (!get_option('show_event_manager_website')) {
	update_option('show_event_manager_website', 'true');
}
if (!get_option('sendReminderBefore')) {
	update_option('sendReminderBefore', '3');
}
if (!get_option('event_manager_register_user')) {
	update_option('event_manager_register_user', 'yes');
}
if (!get_option('show_attendant_first_name')) {
	update_option('show_attendant_first_name', 'true');
}
if (!get_option('mandatory_attendant_first_name')) {
	update_option('mandatory_attendant_first_name', 'true');
}
if (!get_option('show_attendant_last_name')) {
	update_option('show_attendant_last_name', 'true');
}
if (!get_option('mandatory_attendant_last_name')) {
	update_option('mandatory_attendant_last_name', 'true');
}
if (!get_option('show_attendant_e_mail_address')) {
	update_option('show_attendant_e_mail_address', 'true');
}
if (!get_option('mandatory_attendant_e_mail_address')) {
	update_option('mandatory_attendant_e_mail_address', 'true');
}
if (!get_option('submitButtonColor')) {
	update_option('submitButtonColor', '#008000');
}
if (!get_option('submitButtonTextColor')) {
	update_option('submitButtonTextColor', '#FFFFFF');
}

// Email template detaults

if (!get_option('signUpAdminNotificationSubject')) {
	update_option('signUpAdminNotificationSubject', 'New signup received');
}
if (!get_option('signUpAdminNotification')) {
	update_option('signUpAdminNotification', 'Hi Admin

There has been a new signup for the following event: %event_title% which takes place on the %event_date_time%.

Attendant details:

%user_details%

Kind regards,

Admin');
}
if (!get_option('signUpCustomerNotificationSubject')) {
	update_option('signUpCustomerNotificationSubject', 'Thanks for your signup for %event_title%');
}
if (!get_option('signUpCustomerNotification')) {
	update_option('signUpCustomerNotification', 'Hi %attendant_first_name%.

Thank you for signing up for the event:
%event_title%.

%credentials%

It takes place on %event_date_time% and will be held at %event_location%.

Details:
ID: %booking_id%
Name: %attendant_first_name% %attendant_last_name%
Phone: %attendant_phone_number%
E-mail: %attendant_e_mail_address%

Company information:
%company_name%
%company_address%
%company_zip_code% %company_city%

VAT number: %company_vat_number%
Invoice e-mail: %company_invoice_e_mail_address%

Contact person: %company_contact_person%
Phone: %company_contact_person_phone_number%
Reference: %company_reference%

Kind regards,

Admin');
}
if (!get_option('AdminAddCustomerAdminNotificationSubject')) {
	update_option('AdminAddCustomerAdminNotificationSubject', 'New signup for %event_title%.');
}
if (!get_option('AdminAddCustomerAdminNotification')) {
	update_option('AdminAddCustomerAdminNotification', 'Hi admin.

New signup has been created for %event_title%.

The event takes place on %event_date_time% at %event_location%.

Details about the attendant:
%user_details%

Kind regards,

Admin');
}
if (!get_option('AdminAddCustomerCustomerNotificationSubject')) {
	update_option('AdminAddCustomerCustomerNotificationSubject', 'You have been signed up for %event_title%');
}
if (!get_option('AdminAddCustomerCustomerNotification')) {
	update_option('AdminAddCustomerCustomerNotification', 'Hi %attendant_first_name%.

You have been signed up for the event:
%event_title%.

%credentials%

It takes place on %event_date_time% and will be held at %event_location%.

Details:
ID: %booking_id%
Name: %attendant_first_name% %attendant_last_name%
Phone: %attendant_phone_number%
E-mail: %attendant_e_mail_address%

Company information:
%company_name%
%company_address%
%company_zip_code% %company_city%

VAT number: %company_vat_number%
Invoice e-mail: %company_invoice_e_mail_address%

Contact person: %company_contact_person%
Phone: %company_contact_person_phone_number%
Reference: %company_reference%

Kind regards,

Admin');
}
if (!get_option('cancelAdminNotificationSubject')) {
	update_option('cancelAdminNotificationSubject', 'Signup for %event_title% has been cancelled');
}
if (!get_option('cancelAdminNotification')) {
	update_option('cancelAdminNotification', 'Hi %attendant_first_name%.

We hereby confirm, that your signup for the following event has been cancelled:

%event_title%
Date: %event_date_time%

Kind regards,

Admin');
}
if (!get_option('cancelCustomerNotificationSubject')) {
	update_option('cancelCustomerNotificationSubject', 'Your signup for %event_title% has been cancelled');
}
if (!get_option('cancelCustomerNotification')) {
	update_option('cancelCustomerNotification', 'Hi admin

The following singup has been cancelled:

ID:  %booking_id%
%attendant_first_name% %attendant_last_name%

%event_title%
Date: %event_date_time%

Kind regards,

Admin');
}
if (!get_option('usercancelAdminNotificationSubject')) {
	update_option('usercancelAdminNotificationSubject', 'Your signup for %event_title% has been cancelled');
}
if (!get_option('usercancelAdminNotification')) {
	update_option('usercancelAdminNotification', 'Hi admin

The following signup has been cancelled by the attendant:

%event_title%
Date: %event_date_time%

Details:
ID: %booking_id%
Name: %attendant_first_name% %attendant_last_name%
Phone: %attendant_phone_number%
E-mail: %attendant_e_mail_address%

Company details:
%company_name%
%company_address%
%company_zip_code% %company_city%

VAT number: %company_vat_number%
Invoice e-mail: %company_invoice_e_mail_address%

Contact person: %company_contact_person%
Phone: %company_contact_person_phone_number%
Reference: %company_reference%

Kind regards,

Admin');
}
if (!get_option('sendReminderBeforeSubject')) {
	update_option('sendReminderBeforeSubject', 'Reminder regarding  %event_title%');
}
if (!get_option('sendReminderBeforeMessage')) {
	update_option('sendReminderBeforeMessage', 'Hi.

This e-mail is just to remind you that the %event_title% is coming up.

The event takes place on %event_date_time% at %event_location%.

We look forward to seeing you.

Kind regards,

%site_title%');
}
if (!get_option('usercancelCustomerNotificationSubject')) {
	update_option('usercancelCustomerNotificationSubject', 'Your signup has been cancelled');
}
if (!get_option('usercancelCustomerNotification')) {
	update_option('usercancelCustomerNotification', 'Hi %attendant_first_name%

We hereby confirm that you have cancelled you signup for the following event:

%event_title%
Date: %event_date_time%

Kind regards,

Admin');
}
//update_option('code', 'val');

/*
Installing Settings  - Start
 */
//flush_rewrite_rules();
if (!get_option('wpeb_checkout_page')) {
	// Create checkout page
	$checkout_page = array(
		'post_type' => 'page',
		'post_title' => 'Sign Up',
		'post_content' => '[wpeb_checkout]',
		'post_status' => 'publish',
		'post_author' => 1,
	);
	// Insert the post into the database
	$checkout_page_id = wp_insert_post($checkout_page);
	update_option('wpeb_checkout_page', $checkout_page_id);

}
if (!get_option('wpeb_my_accounts_page')) {
	// Create my_accounts page
	$my_accounts_page = array(
		'post_type' => 'page',
		'post_title' => 'My Accounts',
		'post_content' => '[wpeb_my_accounts]',
		'post_status' => 'publish',
		'post_author' => 1,
	);
	// Insert the post into the database
	$my_accounts_page_id = wp_insert_post($my_accounts_page);
	update_option('wpeb_my_accounts_page', $my_accounts_page_id);
}
if (!get_option('wpeb_events_page')) {
	// Create events page
	$events_page = array(
		'post_type' => 'page',
		'post_title' => 'Events',
		'post_content' => '[wpeb_events_page]',
		'post_status' => 'publish',
		'post_author' => 1,
	);
	// Insert the post into the database
	$events_page_id = wp_insert_post($events_page);
	update_option('wpeb_events_page', $events_page_id);
}
