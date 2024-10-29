<?php
add_filter('wpeb_add_new_attendant', 'fnc_enable_disable_multiple_attendant_callback', 14);
add_filter('wpeb_number_of_participants', 'fnc_enable_disable_multiple_attendant_callback', 14);
add_filter('var_participant_count', 'fnc_enable_disable_multiple_attendant_callback', 14);
function fnc_enable_disable_multiple_attendant_callback($output) {
    $amp = esc_attr(get_option('allow_multiple_participants'));
    if (!empty($amp) && $amp == 'true') {
        return $output;
    } else {
        return '';
    }
}
add_action('pre_get_posts', 'fnc_filter_events_by_region');
function fnc_filter_events_by_region($query)
{
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    if (is_post_type_archive('cpt_events')) {
        $region = '';
        if ($_GET && !empty($_GET['region'])) {
            $region = sanitize_text_field($_GET['region']);
        }
        if (!empty($region)) {

            // Display 50 posts for a custom post type called 'movie'
            //$query->set( 'posts_per_page', 50 );

            // WP_Query arguments
            $args = array(
                'post_type' => array('event_location'),
                'numberposts' => -1,
                'meta_query' => array(
                    array(
                        'key' => '_event_region',
                        'value' => $region,
                        'compare' => '=',
                        'type' => 'NUMERIC',
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
                    /*array(
                                       'key' => '_event_location',
                                       'compare' => 'EXISTS' // this should work...
                    */
                );
                $query->set('meta_query', $meta_query);
            } else {
                /* Hack to show no result if location and region doesn't have post.*/
                $meta_query = array(
                    array(
                        'key' => '_event_location',
                        'value' => '2hatslogic',
                    ),
                );
            }

            /*$meta_query[] = array(
                              'key' => '_eventStartDate_micro',
                              'value' => strtotime("now"),
                              'compare' => '>',
                              'type' => 'NUMERIC'
            */
            $meta_query[] = array(
                'key' => '_eventStartDate',
                'value' => date('Y-m-d H:i:s', strtotime("now")),
                'compare' => '>',
                'type' => 'DATETIME',
            );
            $query->set('posts_per_page', '-1');
            $query->set('meta_query', $meta_query);
            $query->set('meta_key', '_eventStartDate');
            $query->set('orderby', array('meta_value' => 'ASC'));
            return;
        }
    }
}
function wpeb_event_details($_event_id = '', $return = 'quick')
{
    if (!$_event_id) {
        return;
    }
    $event_dtls = get_post($_event_id);
    $_EventStartDate = get_post_meta($_event_id, '_eventStartDate', true);
    $_EventEndDate = get_post_meta($_event_id, '_eventEndDate', true);
    $all_day_event = get_post_meta($_event_id, '_all_day_event', true);
    //echo '<br>';
    $dateWithYearFormat = get_option('dateWithYearFormat');
    $dateWithoutYearFormat = get_option('dateWithoutYearFormat');
    $monthAndYearFormat = get_option('monthAndYearFormat');
    $dateTimeSeparator = get_option('dateTimeSeparator');
    $timeRangeSeparator = get_option('timeRangeSeparator');

    if ($_EventStartDate) {
        $start_date = date_i18n($dateWithYearFormat, strtotime($_EventStartDate));
        $start_time = date_i18n(get_option('timeFormat'), strtotime($_EventStartDate));
    }
    if ($_EventEndDate) {
        $end_date = date_i18n($dateWithYearFormat, strtotime($_EventEndDate));
        $end_time = date_i18n(get_option('timeFormat'), strtotime($_EventEndDate));
    }
    $output = '';
    // Checking event year greater than current year to format output.
    if (date('Y', strtotime($_EventStartDate)) > date('Y')) {
        $return_format = $dateWithYearFormat;
    } else {
        $return_format = $dateWithoutYearFormat;
    }
    if ($all_day_event == 'yes') {
        if ($start_date == $end_date) {
            if ($return == 'quick') {
                $output .= date_i18n($return_format, strtotime($_EventStartDate));
            } else {
                $output .= '<ul>
                <li><strong>' . __('Date', 'wp_event_booking') . ': </strong> 
                ' . date_i18n($return_format, strtotime($_EventStartDate)) . '</li>
                </ul>';
            }
        } else {
            if ($return == 'quick') {
                $output .= date_i18n($return_format, strtotime($_EventStartDate));
                $output .= ' ' . $timeRangeSeparator . ' ';
                $output .= date_i18n($return_format, strtotime($_EventEndDate));
            } else {
                $output = '<ul>
            <li><strong>' . __('Start Date', 'wp_event_booking') . ': </strong> ' . date_i18n($return_format, strtotime($_EventStartDate)) . '</li>
            <li><strong>' . __('End Date', 'wp_event_booking') . ': </strong>' . date_i18n($return_format, strtotime($_EventEndDate)) . '</li>
            </ul>';
            }
        }
    } else {
        // Checking start date and end date are same
        if (date('d', strtotime($_EventStartDate)) == date('d', strtotime($_EventEndDate))) {
            if ($return == 'quick') {
                $output .= date_i18n($return_format, strtotime($_EventStartDate));
                $output .= ' ' . $dateTimeSeparator . ' ';
                $output .= date_i18n(get_option('timeFormat'), strtotime($_EventStartDate));
                $output .= ' ' . $timeRangeSeparator . ' ';
                $output .= date_i18n(get_option('timeFormat'), strtotime($_EventEndDate));
            } else {
                $output = '<ul>
            <li><strong>' . __('Date', 'wp_event_booking') . ': </strong>' . date_i18n($return_format, strtotime($_EventStartDate)) . '</li>
            <li><strong>' . __('Time', 'wp_event_booking') . ': </strong>' . date_i18n(get_option('timeFormat'), strtotime($_EventStartDate)) . ' ' . $timeRangeSeparator . ' ' . date_i18n(get_option('timeFormat'), strtotime($_EventEndDate)) . '</li>
            </ul>';
            }
        } else {
            if ($return == 'quick') {
                $output .= date_i18n($return_format, strtotime($_EventStartDate));
                $output .= ' ' . $dateTimeSeparator . ' ';
                $output .= date_i18n(get_option('timeFormat'), strtotime($_EventStartDate));
                $output .= ' ' . $timeRangeSeparator . ' ';
                $output .= date_i18n($return_format, strtotime($_EventEndDate));
                $output .= ' ' . $dateTimeSeparator . ' ';
                $output .= date_i18n(get_option('timeFormat'), strtotime($_EventEndDate));
            } else {
                $output = '<ul>
            <li><strong>' . __('Start', 'wp_event_booking') . ': </strong>' . date_i18n($return_format, strtotime($_EventStartDate)) . ' ' . $dateTimeSeparator . ' ' . date_i18n(get_option('timeFormat'), strtotime($_EventStartDate)) . '</li>
            <li><strong>' . __('Time', 'wp_event_booking') . ': </strong>' . date_i18n($return_format, strtotime($_EventEndDate)) . ' ' . $dateTimeSeparator . ' ' . date_i18n(get_option('timeFormat'), strtotime($_EventEndDate)) . '</li>
            </ul>';
            }
        }
    }
    if ($return == 'quick') {
        return '<h3>' . $output . '</h3>';
    } else {
        return $output;
    }
}
function wpeb_event_manager($_event_id = '')
{
    if (!$_event_id) {
        return;
    }
    $_event_manager = get_post_meta($_event_id, '_event_manager', true);
    if (!$_event_manager) {
        return;
    }
    $event_manager = get_post($_event_manager);
    if (!empty($event_manager)) {
        $output = '<ul>';
        $output .= '<li><strong>' . __('Event Manager', 'wp_event_booking') . ': </strong>' . $event_manager->post_title . '</li>';
        $show_event_manager_phone = get_option('show_event_manager_phone');
        $show_event_manager_email = get_option('show_event_manager_email');
        $show_event_manager_website = get_option('show_event_manager_website');
        if ($show_event_manager_phone == 'true') {
            if (get_post_meta($_event_manager, 'event_manager_phone', true)) {
                $output .= '<li><strong>' . __('Phone', 'wp_event_booking') . ': </strong><a rel="nofollow" href="tel:' . get_post_meta($_event_manager, 'event_manager_phone', true) . '">' . get_post_meta($_event_manager, 'event_manager_phone', true) . '</a></li>';
            }
        }
        if ($show_event_manager_email == 'true') {
            if (get_post_meta($_event_manager, 'event_manager_email', true)) {
                $output .= '<li><strong>' . __('Email', 'wp_event_booking') . ': </strong><a rel="nofollow" href="mailto:' . get_post_meta($_event_manager, 'event_manager_email', true) . '">' . get_post_meta($_event_manager, 'event_manager_email', true) . '</a></li>';
            }
        }
        if ($show_event_manager_website == 'true') {
            if (get_post_meta($_event_manager, 'event_manager_website', true)) {
                $output .= '<li><strong>' . __('Website', 'wp_event_booking') . ': </strong><a rel="nofollow" target="_blank" href="' . get_post_meta($_event_manager, 'event_manager_website', true) . '">' . get_post_meta($_event_manager, 'event_manager_website', true) . '</a></li>';
            }
        }
        $output .= '</ul>';
        return $output;
    }
}
function wpeb_event_location($_event_id = '', $format = 'full')
{
    if (!$_event_id) {
        return;
    }
    $_event_location = get_post_meta($_event_id, '_event_location', true);
    if (!$_event_location) {
        return;
    }
    $event_location = get_post($_event_location);
    if (!empty($event_location)) {
        $output = '';
        $output .= '<ul><li><strong>' . __('Location', 'wp_event_booking') . ': </strong>' . $event_location->post_title . '</li>';
        $arr_details[] = get_post_meta($event_location->ID, 'location_street', true);
        $arr_details[] = get_post_meta($event_location->ID, 'location_zip', true);
        //$arr_details[] = get_post_meta($event_location->ID, 'location_city', true);
        //$arr_details[] = get_post_meta($event_location->ID, 'location_country', true);
        if (!empty($arr_details)) {
            $details = array_filter($arr_details);
            if ($details) {
                $output .= '<li><strong>' . __('Address', 'wp_event_booking') . ': </strong>' .
                implode(', ', $details) . ' ' . get_post_meta($event_location->ID, 'location_city', true);
                '</li>';
            }
        }
        $output .= '</ul>';
        if ($format == 'details') {
            //if (!empty($arr_details)) {
            if (!empty($event_location->post_title)) {
                return $event_location->post_title; //. ' ' . implode(', ', array_filter($arr_details));
            } else {
                return;
            }
        } else {
            return $output;
        }
    }
}
function wpeb_event_cost($_event_id = '')
{
    if (get_post_meta($_event_id, '_event_cost', true)) {
        $decimal_points = (get_option('decimalSeparator')) ? 2 : 0;
        return '<ul><li><strong>' . __('Cost', 'wp_event_booking') . ': </strong>' . esc_attr(get_option('defaultCurrencySymbol')) . '&nbsp;<span class="wpeb_event_price" alt="' . get_post_meta($_event_id, '_event_cost', true) . '">' . number_format(get_post_meta($_event_id, '_event_cost', true), $decimal_points, esc_attr(get_option('decimalSeparator')), esc_attr(get_option('thousandSeparator'))) . '</span>' . '</li></ul>';
    } else {
        return;
    }
}
function wpeb_event_seats($_event_id = '', $layout = '')
{
    $output = '';
    if (get_option('hideSeatsInfo') == 'true') {
        return $output;
    }

    if($layout == 'single'){
        $bookings = get_event_booking_count($_event_id);
        $total_seats = get_post_meta($_event_id, '_available_spots', true);
        $available_seats = !empty($total_seats) ? ($total_seats - $bookings) : $bookings;
        if (get_post_meta($_event_id, '_available_spots', true)) {
            $output = '<ul><li><strong>' . __('Seats Available', 'wp_event_booking') . ': <strong>' . $available_seats .'('.get_post_meta($_event_id, '_available_spots', true) . ')</li></ul>';
        }
    }
    else
    {
        if (get_post_meta($_event_id, '_available_spots', true)) {
            $output = '<ul><li><strong>' . __('Seats Available', 'wp_event_booking') . ': <strong>' . get_post_meta($_event_id, '_available_spots', true) . '</li></ul>';
        }
    }
    return $output;
}
/* Ajax callback to fetch cities from region */
add_action('wp_ajax_pull_cities_from_region', 'fnc_pull_cities_from_region_callback');
add_action('wp_ajax_nopriv_pull_cities_from_region', 'fnc_pull_cities_from_region_callback');
function fnc_pull_cities_from_region_callback()
{
    $region = sanitize_text_field($_POST['region_id']);
    $sc_category_slug = sanitize_text_field($_POST['category_slug']);
    $sc_event_region = sanitize_text_field($_POST['event_region']);
    $sc_event_city = sanitize_text_field($_POST['event_city']);
    $sc_order_by = sanitize_text_field($_POST['order_by']);
    $sc_order = sanitize_text_field($_POST['order']);
    $args = array(
        'post_type' => array('event_location'),
        'numberposts' => -1,
        'meta_query' => array(
            array(
                'key' => '_event_region',
                'value' => $region,
                'compare' => '=',
                'type' => 'NUMERIC',
            ),
        ),
    );
    $args['orderby'] = 'title';
    $args['order'] = 'ASC';
    $pre_query = get_posts($args);
    $locations = wp_list_pluck($pre_query, 'ID');
    $cities = array();

    foreach ($locations as $location) {
        $location_city = get_post_meta($location, 'location_city', true);
        if (!in_array($location_city, $cities)) {
            $cities[] = $location_city;
        }
    }
    sort($cities);
    echo '<div class="accordionCity">';
    $ctr = 0;
    foreach ($cities as $city) {
        if (get_events_count_from_city($city, $sc_category_slug, $sc_event_region, $sc_event_city, $sc_order_by, $sc_order)) {
            echo '<h3 class="city_head" alt="' . $ctr . '" data-city="' . $city . '">' . $city . '</h3>';
            echo '<div class="region_city_events region_city_events_' . $ctr . '" ></div>';
            $ctr++;
        }
    }
    echo '</div>';
    die();
}
/* Get events count from  city title */
function get_events_count_from_city($data_city, $sc_category_slug, $sc_event_region, $sc_event_city, $sc_order_by, $sc_order)
{
    /*
        $sc_category_slug = $_SESSION['category_slug'];
        $sc_event_region = $_SESSION['event_region'];
        $sc_event_city = $_SESSION['event_city'];
        $sc_order_by = $_SESSION['order_by'];
        $sc_order = $_SESSION['order'];
    */
    $args = array(
        'post_type' => array('event_location'),
        'numberposts' => -1,
        'meta_query' => array(
            array(
                'key' => 'location_city',
                'value' => $data_city,
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
    $meta_query[] = array(
        'key' => '_eventStartDate',
        'value' => date('Y-m-d H:i:s', strtotime("now")),
        'compare' => '>',
        'type' => 'DATETIME',
    );
    $args = array(
        'post_type' => array('cpt_events'),
        'posts_per_page' => -1,
        'meta_query' => $meta_query,
    );
    if ($sc_order_by == 'event_date') {
        $args['meta_key'] = '_eventStartDate';
        $args['orderby'] = array('meta_value' => strtoupper($sc_order));
    }
    /*
         else {
            $args['orderby'] = $sc_order_by;
            $args['order'] = strtoupper($sc_order);
        }
    */
    if (!empty($sc_category_slug)) {
        $args['category_name'] = $sc_category_slug;
    }

    // The Query
    $the_query = new WP_Query($args);
    $_events_count = $the_query->found_posts;
    wp_reset_postdata();
    return $_events_count;
}
/* Ajax callback function to fetch events from cities */
add_action('wp_ajax_pull_events_from_city', 'fnc_pull_events_from_city_callback');
add_action('wp_ajax_nopriv_pull_events_from_city', 'fnc_pull_events_from_city_callback');
function fnc_pull_events_from_city_callback()
{
    $data_city = sanitize_text_field($_POST['data_city']);

    $sc_category_slug = sanitize_text_field($_POST['category_slug']);
    $sc_event_region = sanitize_text_field($_POST['event_region']);
    $sc_event_city = sanitize_text_field($_POST['event_city']);
    $sc_order_by = sanitize_text_field($_POST['order_by']);
    $sc_order = sanitize_text_field($_POST['order']);

    $args = array(
        'post_type' => array('event_location'),
        'numberposts' => -1,
        'meta_query' => array(
            array(
                'key' => 'location_city',
                'value' => $data_city,
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
    $meta_query[] = array(
        'key' => '_eventStartDate',
        'value' => date('Y-m-d H:i:s', strtotime("now")),
        'compare' => '>',
        'type' => 'DATETIME',
    );
    //$query->set('meta_query', $meta_query);
    //$query->set('meta_key', '_eventStartDate');
    //$query->set('orderby', array('meta_value' => 'ASC'));
    $args = array(
        'post_type' => array('cpt_events'),
        'posts_per_page' => -1,
        'meta_query' => $meta_query,
        'meta_key' => '_eventStartDate',
        'orderby' => array('meta_value' => 'ASC'),
    );
    if ($sc_order_by == 'event_date') {
        $args['meta_key'] = '_eventStartDate';
        $args['orderby'] = array('meta_value' => strtoupper($sc_order));
    } else {
        if (!empty($sc_order_by)) {
            $args['orderby'] = $sc_order_by;
            $args['order'] = strtoupper($sc_order);
        }
    }
    if (!empty($sc_category_slug)) {
        $args['category_name'] = $sc_category_slug;
    }

    // The Query
    $the_query = new WP_Query($args);

    echo fnc_build_events_table($the_query);
    die();
}


/* Get events count from  region title */
function get_events_count_from_region($region, $sc_category_slug, $sc_event_region, $sc_event_city, $sc_order_by, $sc_order)
{
    $args = array(
        'post_type' => array('event_location'),
        'numberposts' => -1,
        'meta_query' => array(
            array(
                'key' => '_event_region',
                'value' => $region,
                'compare' => '=',
                'type' => 'NUMERIC',
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
    $meta_query[] = array(
        'key' => '_eventStartDate',
        'value' => date('Y-m-d H:i:s', strtotime("now")),
        'compare' => '>',
        'type' => 'DATETIME',
    );

    $args = array(
        'post_type' => array('cpt_events'),
        'posts_per_page' => -1,
        'meta_query' => $meta_query,

    );
    if ($sc_order_by == 'event_date') {
        $args['meta_key'] = '_eventStartDate';
        $args['orderby'] = array('meta_value' => strtoupper($sc_order));
    }
    /*
         else {
            $args['orderby'] = $sc_order_by;
            $args['order'] = strtoupper($sc_order);
        }
    */
    if (!empty($sc_category_slug)) {
        $args['category_name'] = $sc_category_slug;
    }

    // The Query
    $the_query = new WP_Query($args);
    return $the_query->found_posts;
}
/* Ajax callback function to fetch events from region */
add_action('wp_ajax_pull_events_from_region', 'fnc_pull_events_from_region_callback');
add_action('wp_ajax_nopriv_pull_events_from_region', 'fnc_pull_events_from_region_callback');
function fnc_pull_events_from_region_callback()
{
    $region = sanitize_text_field($_POST['region_id']);

    $sc_category_slug = sanitize_text_field($_POST['category_slug']);
    $sc_event_region = sanitize_text_field($_POST['event_region']);
    $sc_event_city = sanitize_text_field($_POST['event_city']);
    $sc_order_by = sanitize_text_field($_POST['order_by']);
    $sc_order = sanitize_text_field($_POST['order']);
    $args = array(
        'post_type' => array('event_location'),
        'numberposts' => -1,
        'meta_query' => array(
            array(
                'key' => '_event_region',
                'value' => $region,
                'compare' => '=',
                'type' => 'NUMERIC',
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
    $meta_query[] = array(
        'key' => '_eventStartDate',
        'value' => date('Y-m-d H:i:s', strtotime("now")),
        'compare' => '>',
        'type' => 'DATETIME',
    );
    //$query->set('meta_query', $meta_query);
    //$query->set('meta_key', '_eventStartDate');
    //$query->set('orderby', array('meta_value' => 'ASC'));
    $args = array(
        'post_type' => array('cpt_events'),
        'posts_per_page' => -1,
        'meta_query' => $meta_query,
        //'meta_key' => '_eventStartDate',
        //'orderby' => array('meta_value' => 'ASC'),
    );
    if ($sc_order_by == 'event_date') {
        $args['meta_key'] = '_eventStartDate';
        $args['orderby'] = array('meta_value' => strtoupper($sc_order));
    } else {
        $args['orderby'] = $sc_order_by;
        $args['order'] = strtoupper($sc_order);
    }
    if (!empty($sc_category_slug)) {
        $args['category_name'] = $sc_category_slug;
    }

    // The Query
    $the_query = new WP_Query($args);

    echo fnc_build_events_table($the_query);

    wp_die();
}

function custom_meta_tag_in_head() {
    if(get_option('wpeb_events_page')==get_the_ID()){
        echo '<meta name="robots" content="noindex">';
    }
}
add_action('wp_head', 'custom_meta_tag_in_head', 1);

/** Function to genarate events table.
 * This function calles in events archive(for default and ajax methods.)
 */
function fnc_build_events_table($the_query, $page = '')
{
    $output = '';
    // The Loop
    if ($the_query->have_posts()) {
        if (get_option('show_event_city') == 'true') {
            $sec = '<th>' . __('City', 'wp_event_booking') . '</th>';
            $colspan = 4;
        } else {
            $sec = '<th>' . __('Event', 'wp_event_booking') . '</th>';
            $colspan = 4;
        }
        if ($page == 'my-accounts') {
            $output .= '<table class="tablesorter">
        <thead>' . $sec . '<th>' . __('Start', 'wp_event_booking') . '</th><th>' . __('Status', 'wp_event_booking') . '</th><th>&nbsp;</th></thead><tbody>';
        } else {
            $output .= '<div class="wpeb_tbl_event_list"><table class="tablesorter">
        <thead>' . $sec . '<th>' . __('Start', 'wp_event_booking') . '</th><th>' . __('Status', 'wp_event_booking') . '</th><th>' . __('Registration', 'wp_event_booking') . '</th></thead><tbody>';
        }
        $i = 0;
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $_EventStartDate = get_post_meta(get_the_ID(), '_eventStartDate', true);
            $dateWithYearFormat = get_option('dateWithYearFormat');
            $start_date = date_i18n($dateWithYearFormat, strtotime($_EventStartDate));
            if ($i % 2 == 1) {
                $trClass = 'even';
            } else {
                $trClass = 'odd';
            }
            $sec_details = '';
            if (get_option('show_event_city') == 'true') {
                $sec_details = '<p class="title">' . get_post_meta(get_post_meta(get_the_ID(), '_event_location', true), 'location_city', true) . '</p>';
            } else {
                $sec_details = '<p class="title">' . get_the_title() . '</p>';
            }
            $i++;
            $event_download_info = apply_filters('event_download_info', '', get_the_ID());
            $output .= '<tr class="' . $trClass . '">
            <td>' . $sec_details . '
                <div class="more">' .
            wpeb_event_cost(get_the_ID()) .
            wpeb_event_location(get_the_ID()) . '
                </div>
            </td>
            <td><p class="title">' . $start_date . '</p>' . $event_download_info . '
                <div class="more">
                    ';
            //wpeb_event_details(get_the_ID(), 'details')
            $event_date_details = apply_filters('wpeb_event_date_list', get_the_ID());
            $event_info_files = apply_filters('event_info_files', '', get_the_ID());
            $output .= $event_date_details . $event_info_files . '
                </div>
            </td>
            <td><p class="title">
            ' . fnc_get_remaining_seats(get_the_ID()) . '
            </p>
                <div class="more">
                ' . wpeb_event_seats(get_the_ID()) . '
                    ' . wpeb_event_manager(get_the_ID()) . '
                </div>
            </td>';
            $output .= '<td><p class="title">';
            if ($page == 'my-accounts') {
                $show = ($_GET && isset($_GET['show'])) ? $_GET['show'] : 'future';
                if ($show == 'future') {
                    $nonce = wp_create_nonce('2h@tslogic');
                    //do_something=some_action&_wpnonce=<?php echo $nonce;
                    $output .= '<a rel="nofollow" href="' . get_permalink(get_option('wpeb_my_accounts_page')) . '?process=cancel_event_booking&_wpnonce=' . $nonce . '&event_id=' . get_the_ID() . '" class="sign-up cancel-event-booking">' . __('Cancel', 'wp_event_booking') . '</a>';
                }
            } else {
                if (fnc_get_remaining_seat_status(get_the_ID())) {
                    $output .= '<a rel="nofollow" href="' . add_query_arg(array('event_id' => get_the_ID()), get_permalink(get_option('wpeb_checkout_page'))) . '" class="sign-up">' . __('Sign Up', 'wp_event_booking') . '</a>';
                } else {
                    $output .= '<a rel="nofollow" href="javascript:void(0);" class="no-more-sign-up sign-up">' . __('No seats left', 'wp_event_booking') . '</a>';
                }
            }
            $output .= '&nbsp;&nbsp;<span class="read-more" alt="' . get_the_ID() . '">' . __('Read More', 'wp_event_booking') . '</span></p>
                <div class="more" id="more-short-desc-' . get_the_ID() . '">';
            //add_filter('excerpt_more', 'new_excerpt_more');
            $output .= wpautop(get_post_meta(get_the_ID(), '_short_description', true));
            //remove_filter('excerpt_more', 'new_excerpt_more');
            $output .= '</div>
            </td>
            </tr>';
            $output .= '<tr id="more-desc-' . get_the_ID() . '" class="' . $trClass . ' more-desc">
            <td colspan="' . $colspan . '">
                <div>' . wpautop(get_the_content()) . '</div>
            </td>
            </tr>';
        }
        $output .= '</tbody></table>';
        // Restore original Post Data
        wp_reset_postdata();
    } else {
        $output .= '<p>' . __('No Events Found...', 'wp_event_booking') . '</p>';
    }
    $output .= '<script type="text/javascript">
    var read_more_lang = "' . __('Read More', 'wp_event_booking') . '";
    var close_lang = "' . __('Close', 'wp_event_booking') . '";
    </script>';
    return $output;
}

function fnc_build_events_table_for_template4($the_query, $page = '', $col = '3')
{
    $output = '';
    $event_output = '<div class="template_three_grid">';
    // The Loop
    if ($the_query->have_posts()) {
        $i = 0;
        $j = 1;
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $_EventStartDate = get_post_meta(get_the_ID(), '_eventStartDate', true);
            $dateWithYearFormat = get_option('dateWithYearFormat');
            $start_date = date_i18n($dateWithYearFormat, strtotime($_EventStartDate));

            $output .= '<div class="col span_1_of_' . $col . ' section-box template-wrapper">';
            $output .= '<div class="section-template_grid">';
            $output .= '<div class="section-box-img">';
            if (has_post_thumbnail()) {
                $output .= '<a rel="nofollow" href="' . get_the_permalink() . '">' . get_the_post_thumbnail(get_the_ID(), 'large') . '</a>';
            }
            $output .= '</div>';
            $output .= '<div class="section-box-content">';
            $output .= '<p class="title">' . get_the_title() . '</p>';
            $event_date_details = apply_filters('wpeb_event_date_list', get_the_ID());
            $output .= '<div class="content-wrap">' . $event_date_details . '</div>';
            $output .= '<div class="content-wrap">' . wpeb_event_location(get_the_ID()) . '</div>';
            $output .= '<div class="content-wrap">';
            if (fnc_get_remaining_seat_status(get_the_ID())) {
                $output .= '<a rel="nofollow" href="' . add_query_arg(array('event_id' => get_the_ID()), get_permalink(get_option('wpeb_checkout_page'))) . '" class="sign-up">' . __('Sign Up', 'wp_event_booking') . '</a>';
            } else {
                $output .= '<a rel="nofollow" href="javascript:void(0);" class="no-more-sign-up sign-up">' . __('No seats left', 'wp_event_booking') . '</a>';
            }
            $output .= '<a rel="nofollow" href="' . get_the_permalink() . '" class="more-details sign-up">' . __('More Details', 'wp_event_booking') . '</a>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';

            if (($j % $col == 0 && $j != 1) || $col == 1) {
                $event_output .= '<div class="section group">' . $output . '</div>';
                $output = '';
            }
            $i++;
            $j++;
        }
        // Restore original Post Data
        wp_reset_postdata();
    } else {
        $event_output .= '<p>' . __('No Events Found...', 'wp_event_booking') . '</p>';
    }
    if (!empty($output)) {
        $event_output .= '<div class="section group">' . $output . '</div>';
    }
    return $event_output . '</div>';
}

function new_excerpt_more($more)
{
    return '...';
}
/*
function custom_rewrite_tag() {
add_rewrite_tag('%gallery%', '([^&]+)');
add_rewrite_tag('%auth%', '([^&]+)');
add_rewrite_tag('%photographer%', '([^&]+)');
}
add_action('init', 'custom_rewrite_tag', 10, 0);

function custom_rewrite_rule() {
add_rewrite_rule('^articles/([^/]*)/?','index.php?page_id=13&gallery=$matches[1]','top');
add_rewrite_rule('^books/([^/]*)/?','index.php?page_id=26&auth=$matches[1]','top');
add_rewrite_rule('^photographers/([^/]*)/?','index.php?page_id=24&photographer=$matches[1]','top');
}
add_action('init', 'custom_rewrite_rule', 10, 0);*/
function fnc_get_remaining_seats($_event_id = '')
{
    if (!$_event_id) {
        return;
    }
    $bookings = get_event_booking_count($_event_id);
    $seats = get_post_meta($_event_id, '_available_spots', true);
    if (!empty($seats) && get_option('hideSeatsInfo') != 'true') {
        
        if (!empty(get_option('available_seat_display'))) {
            return str_replace(array('%total_booking%', '%total_seats%', '%seats_available%'), array($bookings, $seats, $seats - $bookings), get_option('available_seat_display'));
        } else {
            return $seats - $bookings . __(' seats available.', 'wp_event_booking');
        }
    } else {
        return __('Available', 'wp_event_booking'); //$bookings;
    }
}

function fnc_get_remaining_seat_status($_event_id = '')
{
    if (!$_event_id) {
        return;
    }
    $bookings = get_event_booking_count($_event_id);
    $seats = get_post_meta($_event_id, '_available_spots', true);
    if (!empty($seats)) {
        if (($seats - $bookings) > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}
function my_login_redirect($redirect_to, $request, $user)
{
    //is there a user to check?
    if (isset($user->roles) && is_array($user->roles)) {
        //check for admins
        if (in_array('administrator', $user->roles)) {
            // redirect them to the default place
            return $redirect_to;
        } elseif (in_array('customer', $user->roles)) {
            $redirect = esc_url(get_page_link(get_option('wpeb_my_accounts_page'))); //home_url();
            exit(wp_redirect($redirect));
        } else {
            return $redirect_to;
        }
    } else {
        return $redirect_to;
    }
}

add_filter('login_redirect', 'my_login_redirect', 10, 3);

/* Prevent user login for the users with role "guest_customer" */
add_filter('authenticate', 'chk_active_user', 100, 2);
function chk_active_user($user, $username)
{
    if (isset($user->roles) && is_array($user->roles)) {
        //check for admins
        if (in_array('guest_customer', $user->roles)) {
            return new WP_Error('disabled_account', __("You don't have access to this site", 'wp_event_booking'));
        } else {
            return $user;
        }
    }
    return $user;
}
function get_event_booking_count($event_id)
{
    $status_args = array(
        'post_type' => array('event_booking'),
        'posts_per_page' => '-1',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_event_id',
                'value' => $event_id,
                'compare' => '=',
                'type' => 'NUMERIC',
            ),
            array(
                'relation' => 'OR',
                array(
                    'key' => 'booking_status',
                    'value' => 'cancelled',
                    'compare' => '!=',
                ),
                array(
                    'key' => 'booking_status',
                    'compare' => 'NOT EXISTS',
                ),
            ),
        ),
    );
    return $status_bookings = sizeof(get_posts($status_args));
}

function set_html_content_type()
{
    return 'text/html';
}

function callback_notification_email($event_id = 0, $user_id = 0, $booking_id = 0, $mail_to = '', $subject_field = '', $message_field = '', $random_password = '')
{
    $event_title = $event_date = $event_time = $user_name = $user_phone = $user_address = $user_email = $event_price = $event_location = $credentials = '';
    $args = array(
        'post_type' => array('cpt_events'),
        'posts_per_page' => 1,
        'post__in' => array($event_id),
    );

    // The Query
    $the_query = new WP_Query($args);
    $output = '';
    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $_EventStartDate = get_post_meta(get_the_ID(), '_eventStartDate', true);
            $dateWithYearFormat = get_option('dateWithYearFormat');
            $event_title = get_the_title();
            $event_date = date_i18n($dateWithYearFormat, strtotime($_EventStartDate));
            $event_time = date(get_option('timeFormat'), strtotime($_EventStartDate));
            $event_price = esc_attr(get_option('defaultCurrencySymbol')) . get_post_meta(get_the_ID(), '_event_cost', true);
            $event_location = wpeb_event_location(get_the_ID(), 'details');
        }
        wp_reset_postdata();
    }
    $user_info = get_userdata($user_id);
    $user_name = $user_info->display_name;
    $user_phone = get_user_meta($user_id, 'txt_attendant_phone_number', true);
    $user_address = get_user_meta($user_id, 'txt_attendant_address', true);
    $user_email = $user_info->user_email;
    $user_login = $user_info->user_login;
    if (!empty($random_password)) {
        $credentials = '<p>' . __('Username', 'wp_event_booking') . ' : ' . $user_login . '</p><p>' . __('Password', 'wp_event_booking') . ' : ' . $random_password . '</p>';
    }
    // This need to change after adding hook to cancel functions.
    if ($mail_to == 'admin') {
        $to = esc_attr(get_option('Administrator_Email'));
    } else {
        $to = apply_filters('notification_to_email', $user_email, $user_id);
    }

    if (!empty($to) && is_email($to)) {
        $placeholders = array('%event_title%', '%event_date_time%', '%user_name%', '%user_phone%', '%user_address%', '%user_email%', '%event_price%', '%event_location%', '%booking_id%');
        $values = array($event_title, $event_date . ' ' . $event_time, $user_name, $user_phone, $user_address, $user_email, $event_price, $event_location, $booking_id);
        //%event_date% kl. %event_time%
        $placeholders[] = '%attendant_first_name%';
        $placeholders[] = '%attendant_last_name%';
        $placeholders[] = '%attendant_phone_number%';
        $placeholders[] = '%attendant_e_mail_address%';
        $placeholders[] = '%attendant_address%';
        $placeholders[] = '%attendant_zip_code%';
        $placeholders[] = '%attendant_city%';
        $placeholders[] = '%attendant_state%';

        $placeholders[] = '%company_name%';
        $placeholders[] = '%company_address%';
        $placeholders[] = '%company_zip_code%';
        $placeholders[] = '%company_city%';
        $placeholders[] = '%company_state%';
        $placeholders[] = '%company_vat_number%';
        $placeholders[] = '%company_contact_person%';
        $placeholders[] = '%company_contact_person_phone_number%';
        $placeholders[] = '%company_invoice_e_mail_address%';
        $placeholders[] = '%company_reference%';
        $placeholders[] = '%personal_identification_number%';

        $placeholders[] = '%credentials%';

        $placeholders[] = '%site_title%';

        $values[] = $user_info->first_name!=''?$user_info->first_name:$user_info->display_name;
        $values[] = $user_info->last_name;
        $values[] = get_user_meta($user_id, 'txt_attendant_phone_number', true)!=''?get_user_meta($user_id, 'txt_attendant_phone_number', true):get_post_meta($booking_id, 'phone', true);
        $values[] = $user_info->user_email;
        $values[] = get_user_meta($user_id, 'txt_attendant_address', true);
        $values[] = get_user_meta($user_id, 'txt_attendant_zip_code', true);
        $values[] = get_user_meta($user_id, 'txt_attendant_city', true);
        $values[] = get_user_meta($user_id, 'txt_attendant_state', true);

        $values[] = get_user_meta($user_id, 'txt_company_name', true);
        $values[] = get_user_meta($user_id, 'txt_company_address', true);
        $values[] = get_user_meta($user_id, 'txt_company_zip_code', true);
        $values[] = get_user_meta($user_id, 'txt_company_city', true);
        $values[] = get_user_meta($user_id, 'txt_company_state', true);
        $values[] = get_user_meta($user_id, 'txt_company_vat_number', true);
        $values[] = get_user_meta($user_id, 'txt_company_contact_person', true);
        $values[] = get_user_meta($user_id, 'txt_company_contact_person_phone_number', true);
        $values[] = get_user_meta($user_id, 'txt_company_invoice_e_mail_address', true);
        $values[] = get_user_meta($user_id, 'txt_company_reference', true);
        $values[] = get_user_meta($user_id, 'txt_personal_identification_number', true);
        if (get_option('event_manager_register_user') == 'yes') {
            $values[] = $credentials;
        } else {
            $values[] = '';
        }
        $values[] = get_option('blogname');

        $subject = str_replace($placeholders, $values, get_option($subject_field));
        $body = str_replace($placeholders, $values, wpautop(get_option($message_field)));

        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        //$headers[] = 'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>';
        $headers[] = 'From: ' . get_option('senderFromName') . ' <' . get_option('senderFromEmail') . '>';
        //get_option('Administrator_Email')
        add_filter('wp_mail_content_type', 'set_html_content_type');
        wp_mail($to, html_entity_decode($subject), $body, $headers);
        remove_filter('wp_mail_content_type', 'set_html_content_type');
    }
}
/**
 * Retrieve a region given its title.
 */
function get_region_id_from_name($page_title, $output = OBJECT)
{
    global $wpdb;
    $post = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='location_region'", $page_title)); //AND post_type='post'
    if ($post) {
        return $post;
    }
    //get_post($post, $output);

    return null;
}
/**
 * Retrieve a location given its title.
 */
function get_location_id_from_name($page_title, $output = OBJECT)
{
    global $wpdb;
    $post = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='event_location'", $page_title)); //AND post_type='post'
    if ($post) {
        return $post;
    }
    //get_post($post, $output);

    return null;
}
function register_my_session()
{
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'register_my_session');
add_filter('wpeb_event_date_list', 'fnc_event_single_date_callback');
function fnc_event_single_date_callback($_event_id = '', $return = 'details')
{
    if (!$_event_id) {
        return;
    }
    $event_dtls = get_post($_event_id);
    $_EventStartDate = get_post_meta($_event_id, '_eventStartDate', true);
    $_EventEndDate = get_post_meta($_event_id, '_eventEndDate', true);
    $all_day_event = get_post_meta($_event_id, '_all_day_event', true);
    //echo '<br>';
    $dateWithYearFormat = get_option('dateWithYearFormat');
    $dateWithoutYearFormat = get_option('dateWithoutYearFormat');
    $monthAndYearFormat = get_option('monthAndYearFormat');
    $dateTimeSeparator = get_option('dateTimeSeparator');
    $timeRangeSeparator = get_option('timeRangeSeparator');

    if ($_EventStartDate) {
        $start_date = date($dateWithYearFormat, strtotime($_EventStartDate));
        $start_time = date(get_option('timeFormat'), strtotime($_EventStartDate));
    }
    if ($_EventEndDate) {
        $end_date = date($dateWithYearFormat, strtotime($_EventEndDate));
        $end_time = date(get_option('timeFormat'), strtotime($_EventEndDate));
    }
    $output = '';
    // Checking event year greater than current year to format output.
    if (date('Y', strtotime($_EventStartDate)) > date('Y')) {
        $return_format = $dateWithYearFormat;
    } else {
        $return_format = $dateWithoutYearFormat;
    }
    if ($all_day_event == 'yes') {
        if ($start_date == $end_date) {
            if ($return == 'quick') {
                $output .= date_i18n($return_format, strtotime($_EventStartDate));
            } else {
                $output .= '<dl>
                <dt>' . __('Date', 'wp_event_booking') . ': </dt>
                <dd>' . date_i18n($return_format, strtotime($_EventStartDate)) . '</dd>
                </dl>';
            }
        } else {
            if ($return == 'quick') {
                $output .= date_i18n($return_format, strtotime($_EventStartDate));
                $output .= ' ' . $timeRangeSeparator . ' ';
                $output .= date_i18n($return_format, strtotime($_EventEndDate));
            } else {
                $output = '<dl>
            <dt>' . __('Start Date', 'wp_event_booking') . ': </dt>
            <dd>' . date_i18n($return_format, strtotime($_EventStartDate)) . '</dd>
            <dt>' . __('End Date', 'wp_event_booking') . ': </dt>
            <dd>' . date_i18n($return_format, strtotime($_EventEndDate)) . '</dd>
            </dl>';
            }
        }
    } else {
        // Checking start date and end date are same
        if (date('d', strtotime($_EventStartDate)) == date('d', strtotime($_EventEndDate))) {
            if ($return == 'quick') {
                $output .= date_i18n($return_format, strtotime($_EventStartDate));
                $output .= ' ' . $dateTimeSeparator . ' ';
                $output .= date_i18n(get_option('timeFormat'), strtotime($_EventStartDate));
                $output .= ' ' . $timeRangeSeparator . ' ';
                $output .= date_i18n(get_option('timeFormat'), strtotime($_EventEndDate));
            } else {
                $output = '<dl>
            <dt>' . __('Date', 'wp_event_booking') . ': </dt>
            <dd>' . date_i18n($return_format, strtotime($_EventStartDate)) . '</dd>
            <br><dt>' . __('Time', 'wp_event_booking') . ': </dt>
            <dd>' . date_i18n(get_option('timeFormat'), strtotime($_EventStartDate)) . ' ' . $timeRangeSeparator . ' ' . date_i18n(get_option('timeFormat'), strtotime($_EventEndDate)) . '</dd>
            </dl>';
            }
        } else {
            if ($return == 'quick') {
                $output .= date_i18n($return_format, strtotime($_EventStartDate));
                $output .= ' ' . $dateTimeSeparator . ' ';
                $output .= date_i18n(get_option('timeFormat'), strtotime($_EventStartDate));
                $output .= ' ' . $timeRangeSeparator . ' ';
                $output .= date_i18n($return_format, strtotime($_EventEndDate));
                $output .= ' ' . $dateTimeSeparator . ' ';
                $output .= date_i18n(get_option('timeFormat'), strtotime($_EventEndDate));
            } else {
                $output = '<dl>
            <dt>' . __('Start', 'wp_event_booking') . ': </dt>
            <dd>' . date_i18n($return_format, strtotime($_EventStartDate)) . ' ' . $dateTimeSeparator . ' ' . date_i18n(get_option('timeFormat'), strtotime($_EventStartDate)) . '</dd>
            <br><dt>' . __('Time', 'wp_event_booking') . ': </dt>
            <dd>' . date_i18n($return_format, strtotime($_EventEndDate)) . ' ' . $dateTimeSeparator . ' ' . date_i18n(get_option('timeFormat'), strtotime($_EventEndDate)) . '</dd>
            </dl>';
            }
        }
    }
    if ($return == 'quick') {
        return '<h3>' . $output . '</h3>';
    } else {
        return $output;
    }
}
add_action('wp_footer', 'wpeb_plugin_styles');
function wpeb_plugin_styles()
{
    $buttonColor = esc_attr(get_option('submitButtonColor'));
    $buttonTextColor = esc_attr(get_option('submitButtonTextColor')); ?>
    <style type="text/css">
    .tablesorter .sign-up,.content-wrap .sign-up, #btn_checkout {
        background: <?php echo $buttonColor; ?>;
        color: <?php echo $buttonTextColor; ?>;
        border: 1px solid <?php echo $buttonColor; ?>;
    }
    .content-wrap .sign-up{
        padding: 2px 3px;
        cursor: pointer;
        border-radius: 3px;
        font-size: 12px !important;
    }
    #event_checkout #btn_checkout{
        padding: 8px 12px;
        cursor: pointer;
        border-radius: 3px;
    }
    .tablesorter .read-more, .tablesorter .close-more{
        color: <?php echo $buttonColor; ?>;
    }
        a.no-more-sign-up.sign-up {
        background: red;
    color: #FFFFFF;
    border: 1px solid red;
}
    </style>
<?php
echo '<script type="text/javascript">
    var cancelEventPlaceholder = "' . __('Are you sure you want to cancel this?', 'wp_event_booking') . '";
    var noSeatsPlaceholder = "' . __('No seats available.', 'wp_event_booking') . '";
    var fewSeatsPlaceholder = "' . __('Only %d% seats available.', 'wp_event_booking') . '";
    </script>';
}

add_action('wpeb_customer_after_cancel', 'fncCustomerEventCancelNotifyCustomer', 10, 3);
function fncCustomerEventCancelNotifyCustomer($_event_id, $_customer_id, $post_id)
{
    callback_notification_email($_event_id, $_customer_id, $post_id, '', 'usercancelCustomerNotificationSubject', 'usercancelCustomerNotification');
}
add_action('wpeb_customer_after_cancel', 'fncCustomerEventCancelNotifyAdmin', 20, 3);
function fncCustomerEventCancelNotifyAdmin($_event_id, $_customer_id, $post_id)
{
    callback_notification_email($_event_id, $_customer_id, $post_id, 'admin', 'usercancelAdminNotificationSubject', 'usercancelAdminNotification');
}
add_action('wp_head', 'fnc_init_var_available_spots');
function fnc_init_var_available_spots()
{
    ?>
<script type="text/javascript">var available_spots = "";
var loading_text= "<?php echo __('Loading...', 'wp_event_booking') ?>";
</script>
<?php
}
//////////////////////////////////////////
// Add a new interval of 300 seconds
// See http://codex.wordpress.org/Plugin_API/Filter_Reference/cron_schedules
add_filter('cron_schedules', 'isa_add_every_five_minutes_interval');
function isa_add_every_five_minutes_interval($schedules)
{
    $schedules['every_five_minutes'] = array(
        'interval' => 300,
        'display' => __('Every 5 Minutes', 'wp_event_booking'),
    );
    return $schedules;
}

if (!wp_next_scheduled('every_five_minute_reminder')) {
    wp_schedule_event(time(), 'every_five_minutes', 'every_five_minute_reminder');
}
// Hook into that action that'll fire every five minutes
add_action('every_five_minute_reminder', 'every_five_minute_reminder_callback_func'); //isa_add_every_three_minutes

function every_five_minute_reminder_callback_func()
{
    //add_action('init', 'bl_print_tasks_ds');
    //function bl_print_tasks_ds() {
    //if (isset($_GET['down'])) {
    $sendReminderBefore = get_option('sendReminderBefore');
    if (empty($sendReminderBefore) || !is_numeric($sendReminderBefore)) {
        return false;
    }
    //wp_mail('2hats@mailinator.com', '5 min corn', '5 min corn seems working.');
    $meta_query[] = array(
        'key' => '_eventStartDate',
        'value' => date('Y-m-d H:i:s', strtotime("now")),
        'compare' => '>',
        'type' => 'DATETIME',
    );
    $args = array(
        'post_type' => array('cpt_events'),
        'posts_per_page' => '-1',
        'post_status' => 'publish',
        'meta_query' => $meta_query,
    );
    // The Query
    $query = new WP_Query($args);

    // The Loop
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $event_ID = get_the_ID();
            $_eventStartDate_micro = get_post_meta(get_the_ID(), '_eventStartDate_micro', true);
            if (!empty($sendReminderBefore) && is_numeric($sendReminderBefore)) {
                //wp_mail('hariprasad148@gmail.com', 'empty downloadRemStatus and Int', 'Working???');
                if (strtotime("now") > strtotime('-' . $sendReminderBefore . ' days', $_eventStartDate_micro)) {
                    $mq = array(
                        'relation' => 'AND',
                        array(
                            'key' => 'RemStatus',
                            'compare' => 'NOT EXISTS',
                        ),
                        array(
                            'key' => '_event_id',
                            'value' => get_the_ID(),
                            'compare' => '=',
                        ),
                        array(
                            'relation' => 'OR',
                            array(
                                'key' => 'booking_status',
                                'value' => 'cancelled',
                                'compare' => '!=',
                            ),
                            array(
                                'key' => 'booking_status',
                                'compare' => 'NOT EXISTS',
                            ),
                        ),
                    );
                    $args1 = array(
                        'post_type' => array('event_booking'),
                        'posts_per_page' => '-1',
                        'post_status' => 'publish',
                        'meta_query' => $mq,
                    );
                    // The Query
                    $query1 = new WP_Query($args1);
                    // The Loop
                    if ($query1->have_posts()) {
                        while ($query1->have_posts()) {
                            $query1->the_post();
                            $booking_ID = get_the_ID();
                            $_customer_id = get_post_meta($booking_ID, '_customer_id', true);
                            callback_notification_email($event_ID, $_customer_id, $booking_ID, '', 'sendReminderBeforeSubject', 'sendReminderBeforeMessage');
                            update_post_meta($booking_ID, 'RemStatus', 'completed');
                        }
                    }
                    wp_reset_postdata();
                }
            }
        }
    } else {
        // no posts found
    }
    // Restore original Post Data
    wp_reset_postdata();
    
}
