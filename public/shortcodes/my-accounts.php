<?php
// Shortcode for genarating user dasbhoard on frontend.
add_shortcode('wpeb_my_accounts', 'fnc_wpeb_my_accounts_callback');
function fnc_wpeb_my_accounts_callback() {
	$_customer_id = $show = $output = '';
	if (is_user_logged_in()) {
		$_customer_id = get_current_user_id();
	} else {
		return;
	}
	if ($_GET && isset($_GET['process']) && $_GET['process'] == 'cancel_event_booking') {

		$nonce = $_REQUEST['_wpnonce'];
		if (!wp_verify_nonce($nonce, '2h@tslogic')) {
			// This nonce is not valid.
			die('Security check');
		} else {
			$_event_id = sanitize_text_field($_GET['event_id']);
			$args = array(
				'post_type' => array('event_booking'),
				'posts_per_page' => '-1',
				'meta_query' => array(
					array(
						'key' => '_event_id',
						'value' => $_event_id,
						'compare' => '=',
						'type' => 'NUMERIC',
					),
					array(
						'key' => '_customer_id',
						'value' => get_current_user_id(),
						'compare' => '=',
						'type' => 'NUMERIC',
					),
				),
			);
			$bookings = get_posts($args);
			if (!empty($bookings)) {
				$booked_events = wp_list_pluck($bookings, 'ID');
				update_post_meta($booked_events[0], 'booking_status', 'cancelled');
				$output .= ' <p class="success">' . __('Cancelled..', 'wp_event_booking') . '</p>';
				//callback_notification_email($_event_id, get_current_user_id(), $booked_events[0], '', 'usercancelCustomerNotificationSubject', 'usercancelCustomerNotification');
				//callback_notification_email($_event_id, get_current_user_id(), $booked_events[0], 'admin', 'usercancelAdminNotificationSubject', 'usercancelAdminNotification');

				do_action('wpeb_customer_after_cancel', $_event_id, get_current_user_id(), $booked_events[0]);

			}
			// print_r($booked_events);
		}
	}
	$show = ($_GET && isset($_GET['show'])) ? $_GET['show'] : 'future';
	$output .= '<nav class="wpeb-accounts-menu">
	<ul>
	<li>';
	//$active_class = '';
	$active_class = ($show == 'future') ? 'active' : '';
	$output .= '<a rel="nofollow" class="' . $active_class . '" href="?show=future">' . __('Future Events', 'wp_event_booking') . '</a>';

	$output .= '</li>
	<li>';
	$active_class = ($show == 'past') ? 'active' : '';
	$output .= '<a rel="nofollow" class="' . $active_class . '" href="?show=past">' . __('Past Events', 'wp_event_booking') . '</a>';

	$output .= '</li><li>';
	$active_class = ($show == 'cancelled') ? 'active' : '';
	$output .= '<a rel="nofollow" class="' . $active_class . '" href="?show=cancelled">' . __('Cancelled Events', 'wp_event_booking') . '</a>';

	$output .= '</li></nav>';
	$output .= '<div class="events"><div id="wpeb-events-page">';
	$pre_meta_query[] = array(
		'key' => '_customer_id',
		'value' => $_customer_id,
		'compare' => '=',
		'type' => 'NUMERIC',
	);
	if ($show == 'cancelled') {
		$pre_meta_query[] = array(
			'key' => 'booking_status',
			'value' => 'cancelled',
			'compare' => '=',
		);
	} else {
		$pre_meta_query[] = array('relation' => 'OR',
			array(
				'key' => 'booking_status',
				'value' => 'cancelled',
				'compare' => '!='),
			array(
				'key' => 'booking_status',
				'compare' => 'NOT EXISTS'),
		);
	}

	$pre_args = array(
		'post_type' => array('event_booking'),
		'posts_per_page' => '-1',
		'meta_query' => $pre_meta_query,
	);
	$pre_query = get_posts($pre_args);
	if ($pre_query) {
		foreach ($pre_query as $pq) {
			$events[] = get_post_meta($pq->ID, '_event_id', true);
		}
	}
	if (empty($events)) {
		$events = array(0); // Setting event as blank array to avoid error.
	}
	if ($show == 'future') {
		$meta_query[] = array(
			'key' => '_eventStartDate',
			'value' => date('Y-m-d H:i:s', strtotime("now")),
			'compare' => '>',
			'type' => 'DATETIME',
		);
	} elseif ($show == 'past') {
		$meta_query[] = array(
			'key' => '_eventStartDate',
			'value' => date('Y-m-d H:i:s', strtotime("now")),
			'compare' => '<',
			'type' => 'DATETIME',
		);
	} else {
		$meta_query = array();
	}
	$args = array(
		'post_type' => array('cpt_events'),
		'posts_per_page' => '-1',
		'post__in' => $events,
		'meta_query' => $meta_query,
		'meta_key' => '_eventStartDate',
		'orderby' => array('meta_value' => 'ASC'),
	);

	// The Query
	$the_query = new WP_Query($args);

	$output .= fnc_build_events_table($the_query, 'my-accounts');
	$output .= '</div></div>';
	return $output;
}