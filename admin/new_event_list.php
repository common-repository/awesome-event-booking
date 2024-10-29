<?php
/*
New Event List Template
 */
global $date_format_for_display;
$date_format_for_display = get_option('dateWithYearFormat') ? get_option('dateWithYearFormat') : 'F j, Y';
global $time_format_for_display;
$time_format_for_display = get_option('timeFormat') ? get_option('timeFormat') : 'h:ia';
global $date_time_separator_for_display;
$date_time_separator_for_display = get_option('dateTimeSeparator') ? get_option('dateTimeSeparator') : '@';
add_action('admin_init', 'func_to_add_new_columns');
function func_to_add_new_columns() {
	add_filter('manage_edit-cpt_events_columns', 'my_cpt_events_columns');
	add_filter('manage_edit-cpt_events_sortable_columns', 'my_cpt_events_sortable_columns');
	add_action('manage_cpt_events_posts_custom_column', 'manage_events_columns', 10, 2);
}

add_action('pre_get_posts', 'my_sort_events');
function my_sort_events($query) {
	$orderby = $query->get('orderby');
	if (isset($_GET['post_type']) && 'cpt_events' == $_GET['post_type'] && $query->is_main_query()) {
		if (isset($orderby) && 'event_start_date' == $orderby) {
			$query->set('meta_key', '_eventStartDate_micro');
			$query->set('orderby', 'meta_value_num');
		}
	}
}

function my_cpt_events_columns($cpt_events_columns) {
	//unset($cpt_events_columns['categories']);
	$test = $cpt_events_columns['date'];
	unset($cpt_events_columns['date']);
	$cpt_events_columns['amount_of_signups'] = __('Amount of Signups', 'wp_event_booking');
	$cpt_events_columns['amount_of_seats_left'] = __('Available Seats Left', 'wp_event_booking');
	$cpt_events_columns['event_start_date'] = __('Event Start Date', 'wp_event_booking');
	$cpt_events_columns['Region'] = __('Region', 'wp_event_booking');
	$cpt_events_columns['city'] = __('City', 'wp_event_booking');
	$cpt_events_columns['date'] = $test;
	return $cpt_events_columns;
}

function my_cpt_events_sortable_columns($cpt_events_columns) {
	$cpt_events_columns['Date'] = 'Date';
	$cpt_events_columns['event_start_date'] = 'event_start_date';
	// $cpt_events_columns['amount_of_signups'] = 'amount_of_signups';
	return $cpt_events_columns;
}

function manage_events_columns($column_name, $id) {
	global $wpdb;
	switch ($column_name) {
	case 'id':
		echo $id;
		break;
	case 'Region':
		$event_loation_id = get_post_meta($id, '_event_location', true);

		$event_region_id = get_post_meta($event_loation_id, '_event_region', true);
		echo get_the_title($event_region_id);
		break;
	case 'event_start_date':
		$event_start_date = get_post_meta($id, '_eventStartDate', true);
		echo date_i18n($GLOBALS['date_format_for_display'], strtotime($event_start_date));
		break;
	case 'amount_of_signups':
		$bookings = get_event_booking_count($id);
		$permalink = admin_url('edit.php') . '?post_type=cpt_events&page=attendees&event_id=' . $id;
		echo '<a class="wpeb_tooltip" alt="' . $id . '" href="' . $permalink . '">' . $bookings . '<div class="wpeb_tooltipwrap"><div class="wpeb_tooltipcontent"></div></div></a>';
		break;
	case 'city':
		$event_loation_id = get_post_meta($id, '_event_location', true);
		$city = get_post_meta($event_loation_id, 'location_city', true);
		echo $city;
		break;
	case 'wc_checkout':
		$wc_addon = get_post_meta($id, 'enable_wc_addon', true);
		if($wc_addon){
			echo '<span class="wc-checkout-value" data-value="true">'. __('Enabled', 'wpeb_wc_addon').'</span>';
		}else{
			echo '<span class="required wc-checkout-value" data-value="false">'. __('Disabled', 'wpeb_wc_addon').'</span>';
		}
		break;
	case 'amount_of_seats_left':
		$total_seats = get_post_meta($id, '_available_spots', true);
		$bookings = get_event_booking_count($id);
		if ($total_seats && $bookings) {
			$seats_left = $total_seats - $bookings;
		} else {
			$seats_left = $total_seats;
		}

		echo $seats_left;
	default:
		break;
	} // end switch
}

add_action('restrict_manage_posts', 'wpse45436_admin_posts_filter_restrict_manage_cpt_events');

function wpse45436_admin_posts_filter_restrict_manage_cpt_events() {
	if (isset($_GET['post_type'])) {
		$type = $_GET['post_type'];
	}
	//only add filter to post type you want
	if ('cpt_events' == $type) {
		?>
      <!--   filter for event date -->
          <div class="date_time_picker new_filter_admin">
                <p class="new_filter_div_date">
              <span style="font-size: 17px;"><?php _e('From : ', 'wp_event_booking');?> </span>
              <input type="text" name="txt_start_date_listing" id="txt_start_date_listing" class="date start" value="<?php echo (isset($_GET['txt_start_date_listing'])) ? $_GET['txt_start_date_listing'] : ''; ?>">
              <!-- <input type="text" name="txt_start_time_listing" id="txt_start_time_listing" class="time start ui-timepicker-input" value="<?php echo (isset($_GET['txt_start_time_listing'])) ? $_GET['txt_start_time_listing'] : ''; ?>" autocomplete="off"> -->
               <span style="font-size: 17px;"> <?php _e('To : ', 'wp_event_booking');?> </span>
             <!--  <input type="text" name="txt_end_time_listing" id="txt_end_time_listing" class="time end ui-timepicker-input" value="<?php echo (isset($_GET['txt_end_time_listing'])) ? $_GET['txt_end_time_listing'] : ''; ?>" autocomplete="off"> -->
              <input type="text" name="txt_end_date_listing" id="txt_end_date_listing" class="date end" value="<?php echo (isset($_GET['txt_end_date_listing'])) ? $_GET['txt_end_date_listing'] : ''; ?>">
            </p>
            </div>
           <!--  filter for event date -->
          <!--  filter for region -->
          <?php
$args = array(
			'post_type' => array('location_region'),
			'posts_per_page' => '-1',
			'order' => 'ASC',
			'orderby' => 'title',
		);
		$query = new WP_Query($args);
		if ($query->have_posts()) {
			$values = array();
			while ($query->have_posts()) {
				$query->the_post();
				$values[get_the_title()] = get_the_ID();
			}
		}
		if (!empty($values)) {
			?>
          <select name="filter_by_region">
                <option value=""><?php _e('Select Region', 'wp_event_booking');?></option>
                <?php
$current_v = isset($_GET['filter_by_region']) ? $_GET['filter_by_region'] : '';
			foreach ($values as $label => $value) {
				printf
					(
					'<option value="%s"%s>%s</option>',
					$value,
					$value == $current_v ? ' selected="selected"' : '',
					$label
				);
			}
			?>
        </select>
         <!--  filter for region ends-->
        <?php
}
		/*   filter for city*/
		$values_for_locations = get_location_names_for_dropdown();
		//var_dump($values_for_locations);
		if (!empty($values_for_locations)) {
			?>
          <select name="filter_by_city">
                <option value=""><?php _e('Select City', 'wp_event_booking');?></option>
                <?php
$current_v = isset($_GET['filter_by_city']) ? $_GET['filter_by_city'] : '';
			foreach ($values_for_locations as $label => $value) {
				printf
					(
					'<option value="%s"%s>%s</option>',
					$value,
					$value == $current_v ? ' selected="selected"' : '',
					$label
				);
			}
			?>
        </select>
      <?php
}
		/*   filter for city ends*/
	}
}

add_filter('parse_query', 'my_custom_filter');

function my_custom_filter($query) {
	global $pagenow;
	$type = 'cpt_events';
	if (isset($_GET['post_type'])) {
		$type = $_GET['post_type'];
	}
	if ('cpt_events' == $type && is_admin() && $pagenow == 'edit.php' && isset($_GET['txt_start_date_listing']) && $_GET['txt_start_date_listing'] !== '' && $query->is_main_query()) {
		$strt_date = isset($_GET['txt_start_date_listing']) ? $_GET['txt_start_date_listing'] : '';
		$strt_time = isset($_GET['txt_start_time_listing']) ? $_GET['txt_start_time_listing'] : '00:00:00';
		$end_time = isset($_GET['txt_end_time_listing']) ? $_GET['txt_end_time_listing'] : '23:59:59';
		$end_date = isset($_GET['txt_end_date_listing']) ? $_GET['txt_end_date_listing'] : '';

		$start_date_query = date('Y-m-d H:i:s', strtotime($strt_date . $strt_time));
		$end_date_query = date('Y-m-d H:i:s', strtotime($end_date . $end_time));
		$query->set('meta_query', [
			[
				'key' => '_eventStartDate',
				'value' => array($start_date_query, $end_date_query),
				'compare' => 'BETWEEN',
			],
		]);
	}
	if ('cpt_events' == $type && is_admin() && $pagenow == 'edit.php' && isset($_GET['filter_by_region']) && $_GET['filter_by_region'] !== '' && $query->is_main_query()) {
		$events_for_region = get_event_ids_from_region_ids($_GET['filter_by_region']);
		if (empty($events_for_region)) {
			$events_for_region = array(0);
		}

		$query->set('post__in', $events_for_region);
	}
	if ('cpt_events' == $type && is_admin() && $pagenow == 'edit.php' && isset($_GET['filter_by_city']) && $_GET['filter_by_city'] !== '' && $query->is_main_query()) {
		$events_for_location = get_event_ids_from_city($_GET['filter_by_city']);
		if (empty($events_for_location)) {
			$events_for_location = array(0);
		}

		$query->set('post__in', $events_for_location);
	}
}

add_action('views_edit-cpt_events', 'remove_edit_post_views');
function remove_edit_post_views($views) {
	// if (($_GET['post_type'] == 'cpt_events' && isset($_GET['tab']) && $_GET['tab'] == '' && $_GET['post_status'] !== 'trash') || empty($_GET['tab'])) {
	if (($_GET['post_type'] == 'cpt_events' && isset($_GET['tab']) && $_GET['tab'] == 'future_events')) {
		$add_class_future = "aria-current='page' class='current' ";
	} else {
		$add_class_future = "";
	}
	if (isset($_GET['tab']) && $_GET['tab'] == 'past_events') {
		$add_class_past = "aria-current='page' class='current' ";
	} else {
		$add_class_past = "";
	}

	if (isset($views['trash'])) {
		$trash = $views['trash'];
		unset($views['trash']);
	}
	// unset($views['all']);
	unset($views['publish']);

	$future_count = '(' . get_future_events_count() . ')';

	// $views['all'] = '<a href="' . admin_url() . 'edit.php?post_type=cpt_events" ' . $add_class_future . '>' . __('Future Events', 'wp_event_booking') . '</a>' . $future_count;

	$views['upcoming'] = '<a href="' . admin_url() . 'edit.php?post_type=cpt_events&tab=future_events" ' . $add_class_future . '>' . __('Future Events', 'wp_event_booking') . '</a>' . $future_count;

	$prev_count = '(' . get_previous_events_count() . ')';
	$views['pre'] = '<a href="' . admin_url() . 'edit.php?post_type=cpt_events&tab=past_events" ' . $add_class_past . '>' . __('Previous Events', 'wp_event_booking') . '</a>' . $prev_count;

	if (isset($views['trash'])) {
		$views['trash'] = $trash;
	}
	return $views;
}

add_action('pre_get_posts', 'my_previous_events_list');

function my_previous_events_list($q) {
	require_once ABSPATH . 'wp-admin/includes/screen.php';
	if (is_admin()) {
		$scr = get_current_screen();
	}

	$today = date("Y-m-d H:i:s", strtotime('now'));
	if (is_admin() && isset($scr->base) &&  ($scr->base === 'edit') && $q->is_main_query()) {
		global $typenow, $pagenow;
		$meta_query =array();
		if ('cpt_events' == $typenow && is_admin() && $pagenow == 'edit.php' && isset($_GET['txt_start_date_listing']) && $_GET['txt_start_date_listing'] !== '' && $q->is_main_query()) {
			$strt_date = isset($_GET['txt_start_date_listing']) ? $_GET['txt_start_date_listing'] : '';
			$strt_time = isset($_GET['txt_start_time_listing']) ? $_GET['txt_start_time_listing'] : '00:00:00';
			$end_time = isset($_GET['txt_end_time_listing']) ? $_GET['txt_end_time_listing'] : '23:59:59';
			$end_date = isset($_GET['txt_end_date_listing']) ? $_GET['txt_end_date_listing'] : '';

			$start_date_query = date('Y-m-d H:i:s', strtotime($strt_date . $strt_time));
			$end_date_query = date('Y-m-d H:i:s', strtotime($end_date . $end_time));
			$meta_query[] = array(
				'key' => '_eventStartDate',
				'value' => array($start_date_query, $end_date_query),
				'compare' => 'BETWEEN',
			);
		}
		else
		{

			$pre = isset($_GET['tab']) ?sanitize_text_field($_GET['tab']):''; //filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_STRING);
			if ($pre === 'past_events') {
				$meta_query[] = array('key' => '_eventStartDate', 'value' => $today, 'compare' => '<');
			}
			if ($pre === 'future_events') {
				$meta_query[] = array('key' => '_eventStartDate', 'value' => $today, 'compare' => '>=');
			}
			if ($pre == '' && $_GET['post_type'] == 'cpt_events') {
				// $meta_query[] = array('key' => '_eventStartDate', 'value' => $today, 'compare' => '>=');
			}
		}
		$q->set('meta_query', $meta_query);
	}
}

function get_event_ids_from_region_ids($region_id) {
	$args = array(
		'post_type' => array('event_location'),
		'numberposts' => -1,
		'meta_query' => array(
			array(
				'key' => '_event_region',
				'value' => $region_id,
				'compare' => '=',
				'type' => 'NUMERIC',
			),
		),
	);
	$pre_query = get_posts($args);
	$locations = wp_list_pluck($pre_query, 'ID');

	if (!empty($locations)) {
		$meta_query_for_locations = array(
			array(
				'key' => '_event_location',
				'value' => $locations,
				'compare' => 'IN',
				'type' => 'NUMERIC',
			),
		);
	} else {
		// Hack to show no result if location and region doesn't have post.
		$meta_query = array(
			array(
				'key' => '_event_location',
				'value' => '2hatslogic',
			),
		);
	}
	$args_get_post_in_locations = array(
		'post_type' => array('cpt_events'),
		'posts_per_page' => -1,
		'meta_query' => $meta_query_for_locations,
		//'meta_key' => '_eventStartDate',
		//'orderby' => array('meta_value' => 'ASC'),
	);
	$events_of_current_regions = new WP_Query($args_get_post_in_locations);
	$event_ids = wp_list_pluck($events_of_current_regions->posts, 'ID');
	return $event_ids;
}

function get_location_names_for_dropdown() {
	$args_for_locations = array(
		'post_type' => array('event_location'),
		'posts_per_page' => '-1',
		'order' => 'ASC',
		'orderby' => 'title',
	);
	$values_for_locations = array();
	$query_for_locations = new WP_Query($args_for_locations);
	if ($query_for_locations->have_posts()) {

		while ($query_for_locations->have_posts()) {
			$query_for_locations->the_post();
			$values_for_locations[get_post_meta(get_the_ID(), 'location_city', true)] = get_post_meta(get_the_ID(), 'location_city', true);
		}
	}
	return $values_for_locations;
}

function get_event_ids_from_city($city_name) {
	$args = array(
		'post_type' => array('event_location'),
		'numberposts' => -1,
		'meta_query' => array(
			array(
				'key' => 'location_city',
				'value' => $city_name,
				'compare' => '=',
				'type' => 'CHAR',
			),
		),
	);
	$pre_query = get_posts($args);
	$locations = wp_list_pluck($pre_query, 'ID');
	if (!empty($locations)) {
		$meta_query = array(
			array(
				'key' => '_event_location',
				'value' => $locations,
				'compare' => 'IN',
				'type' => 'NUMERIC',
			),
		);
	} else {
		// Hack to show no result if location and region doesn't have post.
		$meta_query = array(
			array(
				'key' => '_event_location',
				'value' => '2hatslogic',
			),
		);
	}
	$args_get_post_in_city = array(
		'post_type' => array('cpt_events'),
		'posts_per_page' => -1,
		'meta_query' => $meta_query,
		//'meta_key' => '_eventStartDate',
		//'orderby' => array('meta_value' => 'ASC'),
	);
	$events_of_current_city = new WP_Query($args_get_post_in_city);
	$event_ids = wp_list_pluck($events_of_current_city->posts, 'ID');
	return $event_ids;
}

function get_previous_events_count() {
	$today = date("Y-m-d H:i:s", strtotime('now'));
	$meta_query_for_past = array(
		'key' => '_eventStartDate_micro',
		'value' => strtotime('now'),
		'compare' => '<',
		'type' => 'NUMERIC',
	);

	$args_for_past_events = array(
		'post_type' => 'cpt_events',
		'posts_per_page' => -1,
		'meta_query' => array($meta_query_for_past),
	);
	$ee = new WP_Query($args_for_past_events);
	$count = $ee->post_count;
	return $count;
}

function get_future_events_count() {
	$today = date("Y-m-d H:i:s", strtotime('now'));
	$meta_query_for_future = array(
		'key' => '_eventStartDate_micro',
		'value' => strtotime('now'),
		'compare' => '>=',
		'type' => 'NUMERIC',
	);

	$args_for_future_events = array(
		'post_type' => 'cpt_events',
		'posts_per_page' => -1,
		'meta_query' => array($meta_query_for_future),
	);
	$ee = new WP_Query($args_for_future_events);
	$count = $ee->post_count;
	return $count;
}

?>