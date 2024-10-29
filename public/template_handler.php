<?php
/* Change Events archive template from plugin */

function fnc_change_events_archive_template($archive_template) {
	global $post;

	if (is_post_type_archive('cpt_events')) {
		$archive_template = WPEB_DIR . 'public/templates/archive-events.php';
		return $archive_template;
	}
}
add_filter('archive_template', 'fnc_change_events_archive_template');
function fnc_change_events_single_template($single_template) {
	global $post;

	if ($post->post_type == 'cpt_events') {
		$single_template = WPEB_DIR . 'public/templates/single-events.php';
	}
	return $single_template;
}
add_filter('single_template', 'fnc_change_events_single_template');

