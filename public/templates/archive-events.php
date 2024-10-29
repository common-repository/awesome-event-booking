<?php
/**
 *Events archive template.
 * Executes when user runs domainname.ext/events
 */
get_header();?>
<div class="wrap">
	<div id="container" class="events-container">
		<div id="content" role="main" class="events-content">
<?php
$event_region = get_option('show_event_region');
if ($event_region == 'true') {
	// WP_Query arguments
	$args = array(
		'post_type' => array('location_region'),
		'posts_per_page' => '-1',
		'order' => 'ASC',
		'orderby' => 'title',
	);
	// The Query
	$query = new WP_Query($args);
	// The Loop
	if ($query->have_posts()) {
		echo '<div class="accordion">';
		while ($query->have_posts()) {
			$query->the_post();?>
							<h3 class="region_head" alt="<?php the_ID();?>"><?php the_title();?></h3>
							<div class="region_events region_events_<?php the_ID();?>" ></div>
						<?php
}
	}
} else {
	$meta_query[] = array(
		'key' => '_eventStartDate',
		'value' => date('Y-m-d H:i:s', strtotime("now")),
		'compare' => '>',
		'type' => 'DATETIME',
	);
	$args = array(
		'post_type' => array('cpt_events'),
		'meta_query' => $meta_query,
		'meta_key' => '_eventStartDate',
		'orderby' => array('meta_value' => 'ASC'),
	);

	// The Query
	$the_query = new WP_Query($args);
	echo fnc_build_events_table($the_query);
}
?>

	</div><!-- #content -->
	</div><!-- #container -->
</div>
<?php get_footer();?>
