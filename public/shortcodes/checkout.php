<?php
// Shortcode for genarating user dasbhoard on frontend.
add_shortcode('wpeb_checkout', 'fnc_wpeb_checkout_callback', 10);
function fnc_wpeb_checkout_callback() {
	$aeb_settings =  get_option( 'wp_event_booking-settings' ); 
	
	$output = '';
	if ($_GET && !empty($_GET['status']) && $_GET['status'] == 'success') {
		echo '<p class="success">' . __('Success! Event successfully booked.', 'wp_event_booking') . '</p>';
	} else if ($_GET && !empty($_GET['status']) && $_GET['status'] == 'error') {
		echo '<p class="error">' . __('Failed! Event couldn\'t book or event already booked!', 'wp_event_booking') . '</p>';
	} else if ($_GET && $_GET['event_id']) {
		$output .= $script_output = '';
		$event_id = sanitize_text_field($_GET['event_id']);
		$output .= '<div class="section group">';
		$first_name = $last_name = $address = $phone = $zip = $city = $email = '';

		$status_bookings = get_event_booking_count($event_id);
		$total_seats = get_post_meta($event_id, '_available_spots', true);
		$available_seats = '';
		if (!empty($total_seats)) {
			$available_seats = $total_seats - $status_bookings;
		}
		$output .= '<form id="event_checkout" method="post" action="' . get_permalink(get_option('wpeb_checkout_page')) . '">';
		
		$var_participant_count = apply_filters('var_participant_count', '1'); //, $arg1, $arg2

		$output .= '<div class="col span_2_of_3 col_Left">
	<div class="participant_details">
<div id="participant_1" class="participant_clone">
<p class="participant_title">' . __('Participant', 'wp_event_booking') . ' <span class="participant_count">'.$var_participant_count.'</span>
</p>
<div class="validatoin_errors">
</div>';

		/* Checkout fields are adding from admin/functions.php */
		$sign_up_fields = apply_filters('wpeb_checkout_sign_up_fields', array());

		foreach ($sign_up_fields as $SignupFN) {
			$req = $req_html = $field_option = $field_name = $field_mandatory = $field_title = $field_type = '';
			$field_option = esc_attr(get_option($SignupFN['field_option']));
			$field_mandatory = esc_attr(get_option($SignupFN['field_mandatory']));
			$field_name = $SignupFN['field_name'];
			$field_title = $SignupFN['field_title'];
			$field_type = $SignupFN['field_type'];
			if ($field_name == 'txt_attendant_e_mail_address') {
				if ($field_option == 'true') {
					if ($field_mandatory == 'true') {
						$req = 'required';
						$req_html = '<span class="req">*</span>';
					}
					$MF = '<p><label>' . $field_title . ' ' . $req_html . '</label><input alt="' . $field_title . '" type="' . $field_type . '" name="' . $field_name . '[]" class="' . $field_name . ' validate_signup_field txt_email" value="" ' . $req . '></p>';
					$output .= $MF;
					$script_output .= $MF;

					$MF = '<p><label>' . __('Confirm Email address', 'wp_event_booking') . ' ' . $req_html . '</label><input alt="' . $field_title . '" type="' . $field_type . '" name="" class="' . $field_name . ' validate_signup_field txt_repeat_email" value="" ' . $req . '></p>';
					$output .= $MF;
					$script_output .= $MF;

				}
			} else {
				if ($field_option == 'true') {
					if ($field_mandatory == 'true') {
						$req = 'required';
						$req_html = '<span class="req">*</span>';
					}
					$MF = '<p><label>' . $field_title . ' ' . $req_html . '</label><input alt="' . $field_title . '" type="' . $field_type . '" name="' . $field_name . '[]" class="' . $field_name . ' validate_signup_field" value="" ' . $req . '></p>';
					$output .= $MF;
					$script_output .= $MF;
				}
			}
		}
		$wpeb_checkout_extra_field = apply_filters('wpeb_checkout_extra_field', '');
		$MF = '<div class="wpeb-checkout-extra-field">' . $wpeb_checkout_extra_field . '</div>';

		$output .= $MF;
		$script_output .= $MF;

		$output .= '</div>
</div>';
		$buttonColor = esc_attr(get_option('submitButtonColor'));
		$buttonTextColor = esc_attr(get_option('submitButtonTextColor'));

		$wpeb_add_new_attendant = apply_filters('wpeb_add_new_attendant', '<p class="btnAddParticipantHolder"><a rel="nofollow" class="btnAddParticipant"><svg version="1.1" id="plus_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 496.158 496.158" style="enable-background:new 0 0 496.158 496.158;" xml:space="preserve"> <path style="fill:' . $buttonColor . ';" d="M0,248.085C0,111.063,111.069,0.003,248.075,0.003c137.013,0,248.083,111.061,248.083,248.082 c0,137.002-111.07,248.07-248.083,248.07C111.069,496.155,0,385.087,0,248.085z" /> <path style="fill:' . $buttonTextColor . ';" d="M383.546,206.55H289.08v-93.938c0-3.976-3.224-7.199-7.201-7.199H213.75 c-3.977,0-7.2,3.224-7.2,7.199v93.938h-93.937c-3.977,0-7.2,3.225-7.2,7.2v69.187c0,3.976,3.224,7.199,7.2,7.199h93.937v93.41 c0,3.976,3.224,7.199,7.2,7.199h68.129c3.978,0,7.201-3.224,7.201-7.199v-93.41h94.466c3.976,0,7.199-3.224,7.199-7.199V213.75 C390.745,209.774,387.521,206.55,383.546,206.55z" /> </svg><span>' . __('Add new attendant', 'wp_event_booking') . '</span></a></p>');

		$output .= $wpeb_add_new_attendant . '<input type="hidden" name="step" value="2" />';
		$output .= '<div class="wpeb-after-checkout-fields">';
		$after_wpeb_checkout_fields = apply_filters('after_wpeb_checkout_fields', '');
		$output .= $after_wpeb_checkout_fields;
		$output .= '</div>';

		//$output .= '<input type="hidden" name="step" value="2" />';
		$output .= '<input type="hidden" name="event_id" value="' . $event_id . '" />';
		$output .= '<input type="hidden" name="event_cost" value="' . get_post_meta($event_id, '_event_cost', true) . '">';

		$wpeb_submit_type_value = apply_filters('wpeb_submit_type', 'quick_signup'); //, $arg1, $arg2

		$output .= '<input type="hidden" name="wpeb_signup_type" value="' . $wpeb_submit_type_value . '" />';
        if($aeb_settings && $aeb_settings['recaptcha']['status']==1) {
            $output .='<input type="hidden" value="" id="g-recaptcha-hd" name="g-recaptcha-hd">';
        }
		$output .= '<input type="submit" name="btn_checkout" class="wpcf7-form-control wpcf7-submit" id="btn_checkout" value="' . __('Submit', 'wp_event_booking') . '"/>';
		$output .= wp_nonce_field('2h@tslogic', 'verify_its_you', true, false);

		$output .= '</div>
<div class="col span_1_of_3 col_Right">';

		$wpeb_number_of_participants = '<div class="participant">';

		$wpeb_number_of_participants .= __('Number of participants', 'wp_event_booking') . ': <select name="sel_participant" id="sel_participant">';
		for ($i = 1; $i <= 16; $i++) {
			if ($i == 1) {$sel = 'selected="selected"';} else { $sel = '';}
			$wpeb_number_of_participants .= '<option value="' . $i . '" ' . $sel . '>' . $i . '</option>';
		}
		$wpeb_number_of_participants .= '</select>';

		/*$output .= '<span class="btn add_participants">Add Participants</span><br /><br />
    <span class="btn remove_participants">Remove Participants</span><br /><br />';*/
		$wpeb_number_of_participants .= '</div>';
		$output .= apply_filters('wpeb_number_of_participants', $wpeb_number_of_participants);
//		$output .= '<div class="price">' . wpeb_event_cost($event_id) . '</div>';
		$output .= apply_filters('wpeb_price_field', '<div class="price">' . wpeb_event_cost($event_id) . '</div>');
		//wpeb_event_details($event_id, 'details')
		$event_date_details = apply_filters('wpeb_event_date_list', $event_id);
		$output .= '<div class="event_extra_dtls"><div class="date_dtls">' . $event_date_details . '</div>';
		$output .= '<div class="location_dtls">' . wpeb_event_location($event_id) . '</div>';
		$As = esc_attr(get_option('AvailableSeats'));
		if ($As == 'true') {
			$output .= '<div class="seat_dtls">' . wpeb_event_seats($event_id, 'single') . '</div>';
		}
		$output .= '<div class="manager_dtls">' . wpeb_event_manager($event_id) . '</div></div>';
		$output .= '</div>
</form>
</div>';
		$output .= '<script type="text/javascript">var available_spots = "' . $available_seats . '";';
		$output .= "var participants_translation ='" . __('Participant', 'wp_event_booking') . "';";
		$output .= "var del_notice_tranlsation ='" . __('Removed', 'wp_event_booking') . "';";
		if (!empty($script_output)) {
			$output .= "var dynamic_fields='" . $script_output . "';";
		} else {
			$output .= 'var dynamic_fields="";';
		}
		if ($available_seats === 0) {
			$output .= 'alert("' . __('No seats available.', 'wp_event_booking') . '");
            jQuery("#btn_checkout").prop("disabled", true);';
		}
		$output .= '</script>';
		return $output;
	} else {
		$output .= '<span class="error">' . __("Something wrong. Try again", 'wp_event_booking') . '</span>';
		return $output;
	}
}
// Shortcode for genarating user dasbhoard on frontend.
add_shortcode('wp_event_form', 'fnc_wpeb_wp_event_form_callback', 10);
function fnc_wpeb_wp_event_form_callback($atts) {
    $aeb_settings = get_option( 'wp_event_booking-settings' ); 
	$event_id = $atts['id'];
	$output = '';
	if ($_GET && !empty($_GET['status']) && $_GET['status'] == 'success') {
		echo '<p class="success">' . __('Success! Event successfully booked.', 'wp_event_booking') . '</p>';
	} else if ($_GET && !empty($_GET['status']) && $_GET['status'] == 'error') {
		echo '<p class="error">' . __('Failed! Event couldn\'t book or event already booked!', 'wp_event_booking') . '</p>';
	} else if ($event_id) {
		$output .= $script_output = '';
		$event_id = sanitize_text_field($event_id);
		$output .= '<div class="section group">';
		$first_name = $last_name = $address = $phone = $zip = $city = $email = '';

		$status_bookings = get_event_booking_count($event_id);
		$total_seats = get_post_meta($event_id, '_available_spots', true);
		$available_seats = '';
		if (!empty($total_seats)) {
			$available_seats = $total_seats - $status_bookings;
		}
		$output .= '<form id="event_checkout" method="post" action="' . get_permalink(get_option('wpeb_checkout_page')) . '">';
		$output .= '<div class="col span_3_of_3">
	<div class="participant_details">
<div id="participant_1" class="participant_clone">
<div class="validatoin_errors">
</div>';

		/* Checkout fields are adding from admin/functions.php */
		$sign_up_fields = apply_filters('wpeb_checkout_sign_up_fields', array());

		foreach ($sign_up_fields as $SignupFN) {
			$req = $req_html = $field_option = $field_name = $field_mandatory = $field_title = $field_type = '';
			$field_option = esc_attr(get_option($SignupFN['field_option']));
			$field_mandatory = esc_attr(get_option($SignupFN['field_mandatory']));
			$field_name = $SignupFN['field_name'];
			$field_title = $SignupFN['field_title'];
			$field_type = $SignupFN['field_type'];
			if ($field_name == 'txt_attendant_e_mail_address') {
				if ($field_option == 'true') {
					if ($field_mandatory == 'true') {
						$req = 'required';
						$req_html = '<span class="req">*</span>';
					}
					$MF = '<p><label>' . $field_title . ' ' . $req_html . '</label><input alt="' . $field_title . '" type="' . $field_type . '" name="' . $field_name . '[]" class="' . $field_name . ' validate_signup_field txt_email" value="" ' . $req . '></p>';
					$output .= $MF;
					$script_output .= $MF;

					$MF = '<p><label>' . __('Confirm Email address', 'wp_event_booking') . ' ' . $req_html . '</label><input alt="' . $field_title . '" type="' . $field_type . '" name="" class="' . $field_name . ' validate_signup_field txt_repeat_email" value="" ' . $req . '></p>';
					$output .= $MF;
					$script_output .= $MF;

				}
			} else {
				if ($field_option == 'true') {
					if ($field_mandatory == 'true') {
						$req = 'required';
						$req_html = '<span class="req">*</span>';
					}
					$MF = '<p><label>' . $field_title . ' ' . $req_html . '</label><input alt="' . $field_title . '" type="' . $field_type . '" name="' . $field_name . '[]" class="' . $field_name . ' validate_signup_field" value="" ' . $req . '></p>';
					$output .= $MF;
					$script_output .= $MF;
				}
			}
		}
		$wpeb_checkout_extra_field = apply_filters('wpeb_checkout_extra_field', '');
		$MF = '<div class="wpeb-checkout-extra-field">' . $wpeb_checkout_extra_field . '</div>';

		$output .= $MF;
		$script_output .= $MF;

		$output .= '</div>
</div>';
		$buttonColor = esc_attr(get_option('submitButtonColor'));
		$buttonTextColor = esc_attr(get_option('submitButtonTextColor'));

		$output .= '<input type="hidden" name="step" value="2" />';
		$output .= '<div class="wpeb-after-checkout-fields">';
		$after_wpeb_checkout_fields = apply_filters('after_wpeb_checkout_fields', '');
		$output .= $after_wpeb_checkout_fields;
		$output .= '</div>';

		//$output .= '<input type="hidden" name="step" value="2" />';
		$output .= '<input type="hidden" name="event_id" value="' . $event_id . '" />';
		$output .= '<input type="hidden" name="event_cost" value="' . get_post_meta($event_id, '_event_cost', true) . '">';

		$wpeb_single_event_submit_type = apply_filters('wpeb_single_event_submit_type', 'quick_signup', $event_id); //, $arg1, $arg2

		$output .= '<input type="hidden" name="wpeb_signup_type" value="' . $wpeb_single_event_submit_type . '" />';
        if($aeb_settings && $aeb_settings['recaptcha']['status']==1) {
            $output .= '<input type="hidden" value="" id="g-recaptcha-hd"  name="g-recaptcha-hd">';
        }
		$output .= '<input type="submit" name="btn_checkout" class="wpcf7-form-control wpcf7-submit" id="btn_checkout" value="' . __('Submit', 'wp_event_booking') . '"/>';
		$output .= wp_nonce_field('2h@tslogic', 'verify_its_you', true, false);

		$output .= '</div>

</form>
</div>';
		$output .= '<script type="text/javascript">var available_spots = "' . $available_seats . '";';
		$output .= "var participants_translation ='" . __('Participant', 'wp_event_booking') . "';";
		$output .= "var del_notice_tranlsation ='" . __('Removed', 'wp_event_booking') . "';";
		if (!empty($script_output)) {
			$output .= "var dynamic_fields='" . $script_output . "';";
		} else {
			$output .= 'var dynamic_fields="";';
		}
		if ($available_seats === 0) {
			$output .= 'alert("' . __('No seats available.', 'wp_event_booking') . '");
            jQuery("#btn_checkout").prop("disabled", true);';
		}
		$output .= '</script>';
		return $output;
	} else {
		$output .= '<span class="error">' . __("Something wrong. Try again", 'wp_event_booking') . '</span>';
		return $output;
	}
}