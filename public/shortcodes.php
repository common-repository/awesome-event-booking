<?php
// Shortcode for genarating user dasbhoard on frontend.
add_shortcode('wpeb_my_accounts', 'fnc_wpeb_my_accounts_callback');
function fnc_wpeb_my_accounts_callback() {
	$output = '<nav class="menu"><ul><li><a rel="nofollow" href="?show=future">' . __('Future Events', 'wp_event_booking') . '</a></li><li><a rel="nofollow" href="?show=past">' . __('Past Events', 'wp_event_booking') . '</a></li></nav><div class="events">';
	$_customer_id = '';
	if (is_user_logged_in()) {
		$_customer_id = get_current_user_id();
	} else {
		return;
	}
	$show = ($_GET && $_GET['show']) ? $_GET['show'] : 'future';

	$args = array(
		'post_type' => array('event_booking'),
		'meta_query' => array(
			array(
				'key' => '_customer_id',
				'value' => $_customer_id,
				'compare' => '=',
				'type' => 'NUMERIC',
			),
		),
	);
	$pre_query = get_posts($args);
	if ($pre_query) {
		foreach ($pre_query as $pq) {
			$events[] = get_post_meta($pq->ID, '_event_id', true);
		}

	}
	//$events = wp_list_pluck($pre_query, 'ID');
	/*if (!empty($locations)) {
		$meta_query = array(
			array(
				'key' => '_event_location',
				'value' => $events,
				'compare' => 'IN',
				'type' => 'NUMERIC',
			),
		);
	}*/
	if ($show == 'future') {
		$meta_query[] = array(
			'key' => '_eventStartDate',
			'value' => date('Y-m-d H:i:s', strtotime("now")),
			'compare' => '>',
			'type' => 'DATETIME',
		);
	} else {
		$meta_query[] = array(
			'key' => '_eventStartDate',
			'value' => date('Y-m-d H:i:s', strtotime("now")),
			'compare' => '<',
			'type' => 'DATETIME',
		);
	}
	$args = array(
		'post_type' => array('cpt_events'),
		'post__in' => $events,
		'meta_query' => $meta_query,
		'meta_key' => '_eventStartDate',
		'orderby' => array('meta_value' => 'ASC'),
	);

	// The Query
	$the_query = new WP_Query($args);

	// The Loop
	if ($the_query->have_posts()) {
		$output .= '<table class="tablesorter">
		<thead><th>' . __('Event', 'wp_event_booking') . '</th><th>' . __('Start', 'wp_event_booking') . '</th><th>' . __('Status', 'wp_event_booking') . '</th><th>' . __('Registration', 'wp_event_booking') . '</th></thead><tbody>';
		while ($the_query->have_posts()) {
			$the_query->the_post();
			$_EventStartDate = get_post_meta(get_the_ID(), '_eventStartDate', true);
			$dateWithYearFormat = get_option('dateWithYearFormat');
			$start_date = date($dateWithYearFormat, strtotime($_EventStartDate));

			$output .= '<tr>
			<td><p class="title">' . get_the_title() . '</p>
			</td>
			<td><p class="title">' . $start_date . '</p>
			</td>
			<td><p class="title">&nbsp;</p>
			</td>
			<td></td>
			</tr>';
		}
		$output .= '</tbody></table>';
		// Restore original Post Data
		wp_reset_postdata();
	} else {
		$output .= '<p>' . __('No Events Found...', 'wp_event_booking') . '</p>';
	}
	$output .= '</div>';
	return $output;
}