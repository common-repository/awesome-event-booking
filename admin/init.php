<?php
/**
 * Files including for admin.
 */
require WPEB_DIR . 'admin/functions.php'; // Functions
require WPEB_DIR . 'admin/register_custom_post_types.php'; // Registering CPT
require WPEB_DIR . 'admin/register_custom_taxonomy.php'; // Files to add custom taxonomies

require WPEB_DIR . 'admin/enqueue_scripts_and_styles.php'; // Files that enqueuing scripts and styles.

require WPEB_DIR . 'admin/events_meta_box.php'; // File that add Meta box for Event CPT
require WPEB_DIR . 'admin/location_meta_box.php'; // File that add Meta box for Event Location CPT
require WPEB_DIR . 'admin/event_manager_meta_box.php'; // File that add Meta box for Event manager CPT
require WPEB_DIR . 'admin/event_booking_meta_box.php'; // File that add Meta box for Event booking CPT
require WPEB_DIR . 'admin/admin_settings.php'; // Plugin settings page.
require WPEB_DIR . 'admin/event_booking_filter.php'; // Filtering options added here. User based filtering.

require WPEB_DIR . 'admin/new_event_list.php'; // New Event Listing
