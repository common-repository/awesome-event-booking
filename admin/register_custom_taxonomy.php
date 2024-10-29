<?php // Register Custom Taxonomy event_region
/* function register_custom_taxonomy() {

$labels = array(
'name'                       => _x( 'Regions', 'Taxonomy General Name', 'wp_event_booking' ),
'singular_name'              => _x( 'Region', 'Taxonomy Singular Name', 'wp_event_booking' ),
'menu_name'                  => __( 'Region', 'wp_event_booking' ),
'all_items'                  => __( 'All Region', 'wp_event_booking' ),
'parent_item'                => __( 'Parent Region', 'wp_event_booking' ),
'parent_item_colon'          => __( 'Parent Region:', 'wp_event_booking' ),
'new_item_name'              => __( 'New Region', 'wp_event_booking' ),
'add_new_item'               => __( 'Add New Region', 'wp_event_booking' ),
'edit_item'                  => __( 'Edit Region', 'wp_event_booking' ),
'update_item'                => __( 'Update Region', 'wp_event_booking' ),
'view_item'                  => __( 'View Region', 'wp_event_booking' ),
'separate_items_with_commas' => __( 'Separate items with commas', 'wp_event_booking' ),
'add_or_remove_items'        => __( 'Add or remove Region', 'wp_event_booking' ),
'choose_from_most_used'      => __( 'Choose from the most used', 'wp_event_booking' ),
'popular_items'              => __( 'Popular Regions', 'wp_event_booking' ),
'search_items'               => __( 'Search Items', 'wp_event_booking' ),
'not_found'                  => __( 'Not Found', 'wp_event_booking' ),
'no_terms'                   => __( 'No Regions', 'wp_event_booking' ),
'items_list'                 => __( 'Region list', 'wp_event_booking' ),
'items_list_navigation'      => __( 'Regions list navigation', 'wp_event_booking' ),
);
$rewrite = array(
'slug'                       => 'region',
'with_front'                 => true,
'hierarchical'               => false,
);
$args = array(
'labels'                     => $labels,
'hierarchical'               => true,
'public'                     => true,
'show_ui'                    => true,
'show_admin_column'          => true,
'show_in_nav_menus'          => true,
'show_tagcloud'              => true,
'rewrite'                    => $rewrite,
);
register_taxonomy( 'event_region', array( 'event_location', 'events' ), $args );

}
add_action( 'init', 'register_custom_taxonomy', 0 );
 */
