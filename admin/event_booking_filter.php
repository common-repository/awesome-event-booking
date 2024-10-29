<?php

function fnc_event_booking_filters() {
	global $typenow;
	if ($typenow == 'event_booking') {
		$params = array(
			'name' => 'customers', // this is the "name" attribute for filter <select>
			'show_option_all' => __('All Customers', 'wp_event_booking'), // label for all authors (display posts without filter)
			'role' => 'customer',
		);

		if (isset($_GET['customers'])) {
			$params['selected'] = $_GET['customers'];
		}
		// choose selected user by $_GET variable

		wp_dropdown_users($params); // print the ready author list
		$params1 = array(
			'name' => 'guest', // this is the "name" attribute for filter <select>
			'show_option_all' => __('All Guest Customers', 'wp_event_booking'), // label for all authors (display posts without filter)
			'role' => 'guest_customer',
		);

		if (isset($_GET['guest'])) {
			$params1['selected'] = $_GET['guest'];
		}
		// choose selected user by $_GET variable

		wp_dropdown_users($params1); // print the ready author list
	}
}
add_action('restrict_manage_posts', 'fnc_event_booking_filters');

/* Filter event booking based on customer id id */
add_filter('pre_get_posts', 'fnc_filter_event_bookings', 2000);
function fnc_filter_event_bookings($query) {
	global $typenow, $pagenow;
	$meta_query = array();
	$main_meta_query = array();
	if ('cpt_events' == $typenow && is_admin() && $pagenow == 'edit.php' && isset($_GET['txt_start_date_listing']) && $_GET['txt_start_date_listing'] !== '' && $query->is_main_query()) {
		$strt_date = isset($_GET['txt_start_date_listing']) ? $_GET['txt_start_date_listing'] : '';
		$strt_time = isset($_GET['txt_start_time_listing']) ? $_GET['txt_start_time_listing'] : '00:00:00';
		$end_time = isset($_GET['txt_end_time_listing']) ? $_GET['txt_end_time_listing'] : '23:59:59';
		$end_date = isset($_GET['txt_end_date_listing']) ? $_GET['txt_end_date_listing'] : '';

		$start_date_query = date('Y-m-d H:i:s', strtotime($strt_date . $strt_time));
		$end_date_query = date('Y-m-d H:i:s', strtotime($end_date . $end_time));
		$meta_query = array(
			'key' => '_eventStartDate',
			'value' => array($start_date_query, $end_date_query),
			'compare' => 'BETWEEN',
		);
	}
	if ($typenow == 'event_booking' && is_admin() && $pagenow == 'edit.php' && isset($_GET['customers']) && $_GET['customers'] != '' && $_GET['customers'] != 0) {
		$meta_query[] = array(
			'key' => '_customer_id',
			'value' => $_GET['customers'],
			'compare' => '=',
			'type' => 'numeric',
		);
	}
	if ($typenow == 'event_booking' && is_admin() && $pagenow == 'edit.php' && isset($_GET['guest']) && $_GET['guest'] != '' && $_GET['guest'] != 0) {
		$meta_query[] = array(
			'key' => '_customer_id',
			'value' => $_GET['guest'],
			'compare' => '=',
			'type' => 'numeric',
		);
	}
	
	if (!empty($meta_query)) {
		$meta_query['relation'] = 'OR';
		
		if ($typenow == 'event_booking' && is_admin() && $pagenow == 'edit.php' && empty($_GET['booking_status']) && $query->is_main_query() )
		{ // Code to remove cancelled bookings from the list
			$main_meta_query[] = array(
				'relation' => 'OR',
				array(
					'key' => 'booking_status',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key' => 'booking_status',
					'value' => 'cancelled',
					'type' => 'CHAR',
					'compare' => '!=',
				)
			);

			$main_meta_query[] = $meta_query;

			$main_meta_query['relation'] = 'AND';
			
			$query->set('meta_query', $main_meta_query);
		} else {
			$query->set('meta_query', $meta_query);
		}
	} else {
		// Code to remove cancelled bookings from the list
		if ($typenow == 'event_booking' && is_admin() && $pagenow == 'edit.php' && empty($_GET['booking_status']) && $query->is_main_query() )
		{
			$meta_query[] = array(
				'key' => 'booking_status',
				'compare' => 'NOT EXISTS',
			);

			$meta_query[] = array(
				'key' => 'booking_status',
				'value' => 'cancelled',
				'type' => 'CHAR',
				'compare' => '!=',
			);
			$meta_query['relation'] = 'OR';
			$query->set('meta_query', $meta_query);
		}
	}
	return $query;
}
