<?php

/**
 * Register Custom Post Types Events, Location, Region, Manager, Booking
 *
 * @since    1.0.0
 */
function register_custom_post_types() {

	$labels = array(
		'name' => _x('Events', 'Post Type General Name', 'wp_event_booking'),
		'singular_name' => _x('Event', 'Post Type Singular Name', 'wp_event_booking'),
		'menu_name' => __('Events', 'wp_event_booking'),
		'name_admin_bar' => __('Events', 'wp_event_booking'),
		'archives' => __('Event Archives', 'wp_event_booking'),
		'attributes' => __('Event Attributes', 'wp_event_booking'),
		'parent_item_colon' => __('Parent Event:', 'wp_event_booking'),
		'all_items' => __('All Events', 'wp_event_booking'),
		'add_new_item' => __('Add New Event', 'wp_event_booking'),
		'add_new' => __('Add New Event', 'wp_event_booking'),
		'new_item' => __('New Event', 'wp_event_booking'),
		'edit_item' => __('Edit Event', 'wp_event_booking'),
		'update_item' => __('Update Event', 'wp_event_booking'),
		'view_item' => __('View Event', 'wp_event_booking'),
		'view_items' => __('View Events', 'wp_event_booking'),
		'search_items' => __('Search Event', 'wp_event_booking'),
		'not_found' => __('Not found', 'wp_event_booking'),
		'not_found_in_trash' => __('Not found in Trash', 'wp_event_booking'),
		'featured_image' => __('Featured Image', 'wp_event_booking'),
		'set_featured_image' => __('Set featured image', 'wp_event_booking'),
		'remove_featured_image' => __('Remove featured image', 'wp_event_booking'),
		'use_featured_image' => __('Use as featured image', 'wp_event_booking'),
		'insert_into_item' => __('Insert into event', 'wp_event_booking'),
		'uploaded_to_this_item' => __('Uploaded to this event', 'wp_event_booking'),
		'items_list' => __('Events list', 'wp_event_booking'),
		'items_list_navigation' => __('Events list navigation', 'wp_event_booking'),
		'filter_items_list' => __('Filter events list', 'wp_event_booking'),
	);
	$args = array(
		'label' => __('event', 'wp_event_booking'),
		'description' => __('Events post type for booking events', 'wp_event_booking'),
		'labels' => $labels,
		'supports' => array('title', 'editor', 'thumbnail'),
		'taxonomies' => array('category'),
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
		'menu_icon' => 'dashicons-calendar-alt',
	);
	register_post_type('cpt_events', $args);

	register_post_type('event_booking',
		array(
			'public' => true,
			'show_in_menu' => 'edit.php?post_type=cpt_events',
			'supports' => array(''),
			'labels' => array(
				'name' => _x('Event Booking', 'Post Type General Name', 'wp_event_booking'),
				'singular_name' => _x('Event Booking', 'Post Type Singular Name', 'wp_event_booking'),
				'menu_name' => __('Event Bookings', 'wp_event_booking'),
				'name_admin_bar' => __('Event Bookings', 'wp_event_booking'),
				'archives' => __('Event Booking Archives', 'wp_event_booking'),
				'attributes' => __('Event Booking Attributes', 'wp_event_booking'),
				'parent_item_colon' => __('Parent Event Booking:', 'wp_event_booking'),
				'all_items' => __('Event Bookings', 'wp_event_booking'),
				'add_new_item' => __('Add New Event Booking', 'wp_event_booking'),
				'add_new' => __('Add New Booking', 'wp_event_booking'),
				'new_item' => __('New Event Booking', 'wp_event_booking'),
				'edit_item' => __('Edit Event Booking', 'wp_event_booking'),
				'update_item' => __('Update Event Booking', 'wp_event_booking'),
				'view_item' => __('View Event Booking', 'wp_event_booking'),
				'view_items' => __('View Event Bookings', 'wp_event_booking'),
				'search_items' => __('Search Event Bookings', 'wp_event_booking'),
			),
		)
	);
	register_post_type('event_location',
		array(
			'public' => true,
			'show_in_menu' => 'edit.php?post_type=cpt_events',
			'supports' => array('title', 'editor'),
//						'taxonomies'            => array( 'event_region' ),
			'labels' => array(
				'name' => _x('Locations', 'Post Type General Name', 'wp_event_booking'),
				'singular_name' => _x('Location', 'Post Type Singular Name', 'wp_event_booking'),
				'menu_name' => __('Locations', 'wp_event_booking'),
				'name_admin_bar' => __('Locations', 'wp_event_booking'),
				'archives' => __('Location Archives', 'wp_event_booking'),
				'attributes' => __('Location Attributes', 'wp_event_booking'),
				'parent_item_colon' => __('Parent Location:', 'wp_event_booking'),
				'all_items' => __('Locations', 'wp_event_booking'),
				'add_new_item' => __('Add New Location', 'wp_event_booking'),
				'add_new' => __('Add New', 'wp_event_booking'),
				'new_item' => __('New Location', 'wp_event_booking'),
				'edit_item' => __('Edit Location', 'wp_event_booking'),
				'update_item' => __('Update Location', 'wp_event_booking'),
				'view_item' => __('View Location', 'wp_event_booking'),
				'view_items' => __('View Locations', 'wp_event_booking'),
				'search_items' => __('Search Location', 'wp_event_booking'),
			),
		)
	);
	register_post_type('location_region',
		array(
			'public' => true,
			'show_in_menu' => 'edit.php?post_type=cpt_events',
			'labels' => array(
				'name' => _x('Regions', 'Post Type General Name', 'wp_event_booking'),
				'singular_name' => _x('Region', 'Post Type Singular Name', 'wp_event_booking'),
				'menu_name' => __('Regions', 'wp_event_booking'),
				'name_admin_bar' => __('Regions', 'wp_event_booking'),
				'archives' => __('Region Archives', 'wp_event_booking'),
				'attributes' => __('Region Attributes', 'wp_event_booking'),
				'parent_item_colon' => __('Parent Region:', 'wp_event_booking'),
				'all_items' => __('Regions', 'wp_event_booking'),
				'add_new_item' => __('Add New Region', 'wp_event_booking'),
				'add_new' => __('Add New', 'wp_event_booking'),
				'new_item' => __('New Region', 'wp_event_booking'),
				'edit_item' => __('Edit Region', 'wp_event_booking'),
				'update_item' => __('Update Region', 'wp_event_booking'),
				'view_item' => __('View Region', 'wp_event_booking'),
				'view_items' => __('View Regions', 'wp_event_booking'),
				'search_items' => __('Search Region', 'wp_event_booking'),
			),
		)
	);
	register_post_type('event_manager',
		array(
			'public' => true,
			'show_in_menu' => 'edit.php?post_type=cpt_events',
			'supports' => array('title'),
			'labels' => array(
				'name' => _x('Event Manager', 'Post Type General Name', 'wp_event_booking'),
				'singular_name' => _x('Event Manager', 'Post Type Singular Name', 'wp_event_booking'),
				'menu_name' => __('Event Managers', 'wp_event_booking'),
				'name_admin_bar' => __('Event Managers', 'wp_event_booking'),
				'archives' => __('Event Manager Archives', 'wp_event_booking'),
				'attributes' => __('Event Manager Attributes', 'wp_event_booking'),
				'parent_item_colon' => __('Parent Event Manager:', 'wp_event_booking'),
				'all_items' => __('Event Managers', 'wp_event_booking'),
				'add_new_item' => __('Add New Event Manager', 'wp_event_booking'),
				'add_new' => __('Add New', 'wp_event_booking'),
				'new_item' => __('New Event Manager', 'wp_event_booking'),
				'edit_item' => __('Edit Event Manager', 'wp_event_booking'),
				'update_item' => __('Update Event Manager', 'wp_event_booking'),
				'view_item' => __('View Event Manager', 'wp_event_booking'),
				'view_items' => __('View Event Managers', 'wp_event_booking'),
				'search_items' => __('Search Event Manager', 'wp_event_booking'),
			),
		)
	);
}
add_action('init', 'register_custom_post_types');
