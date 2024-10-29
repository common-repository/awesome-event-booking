<?php

/**
 * Register meta box(es).
 */
function register_event_manager_meta_box() {

	/* Create meta box for event details */
	/* call back function event_meta_box_callback() */
	add_meta_box(
		'wp_event_booking-event_manager_details',
		__('Event Manager Details', 'wp_event_booking'),
		'event_manager_meta_box_callback',
		'event_manager',
		'normal',
		'high'
	);
}
add_action('add_meta_boxes', 'register_event_manager_meta_box');
/*** Location Meta Box Starts here ***/

/**
 * Meta box display callback.
 *
 * @param WP_Post $event Current post object.
 */
function event_manager_meta_box_callback($post) {
	$event_manager_phone = get_post_meta($post->ID, 'event_manager_phone', true);
	$event_manager_email = get_post_meta($post->ID, 'event_manager_email', true);
	$event_manager_website = get_post_meta($post->ID, 'event_manager_website', true);
	wp_nonce_field('_event_manager_nonce', 'event_manager_nonce');
	?>
  <div class="section group">
  <div class="col span_1_of_2">
    <input type="text" class="regular-text" name="txt_event_manager_phone" id="txt_event_manager_phone" placeholder="<?php _e('Phone', 'wp_event_booking');?>" value="<?php echo $event_manager_phone; ?>">
    <input type="email" class="regular-text" name="txt_event_manager_email" id="txt_event_manager_email" placeholder="<?php _e('Email', 'wp_event_booking');?>" value="<?php echo $event_manager_email; ?>">
    <input type="url" class="regular-text" name="txt_event_manager_website" id="txt_event_manager_website" placeholder="<?php _e('Website', 'wp_event_booking');?>" value="<?php echo $event_manager_website; ?>">
  </div>
  <div class="col span_1_of_2"></div>
</div>
<?php
}
/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function save_event_manager_meta_box($post_id) {
	// Save logic goes here. Don't forget to include nonce checks!
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	if (!isset($_POST['event_manager_nonce']) || !wp_verify_nonce($_POST['event_manager_nonce'], '_event_manager_nonce')) {
		return;
	}
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}
	if (isset($_POST['txt_event_manager_phone'])) {
		update_post_meta($post_id, 'event_manager_phone', sanitize_text_field($_POST['txt_event_manager_phone']));
	}
	if (isset($_POST['txt_event_manager_email'])) {
		update_post_meta($post_id, 'event_manager_email', sanitize_text_field($_POST['txt_event_manager_email']));
	}
	if (isset($_POST['txt_event_manager_website'])) {
		update_post_meta($post_id, 'event_manager_website', sanitize_text_field($_POST['txt_event_manager_website']));
	}
}
add_action('save_post', 'save_event_manager_meta_box');