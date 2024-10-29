<?php

/**
 * Register meta box(es).
 */
function register_event_location_meta_box() {

	/* Create meta box for location post type */
	/* call back function location_meta_box_callback() */
	add_meta_box(
		'wp_event_booking-locatoin_details',
		__('Location Details', 'wp_event_booking'),
		'location_meta_box_callback',
		'event_location',
		'normal',
		'high'
	);
}
add_action('add_meta_boxes', 'register_event_location_meta_box');

/*** Location Meta Box Starts here ***/

/**
 * Meta box display callback.
 *
 * @param WP_Post $location Current post object.
 */
function location_meta_box_callback($post) {
	/* Event Manager starts here */
	$location_street = get_post_meta($post->ID, 'location_street', true);
	$location_zip = get_post_meta($post->ID, 'location_zip', true);
	$location_city = get_post_meta($post->ID, 'location_city', true);
	$location_country = get_post_meta($post->ID, 'location_country', true);
	$event_region_id = get_post_meta($post->ID, '_event_region', true);
	wp_nonce_field('_event_region_nonce', 'event_region_nonce');?>
    <div class="section group">
    <div class="col span_1_of_2">
      <input type="text" class="regular-text" name="txt_location_street" id="txt_location_street" placeholder="<?php _e('Street and No.', 'wp_event_booking');?>" value="<?php echo $location_street; ?>">
      <input type="text" class="regular-text" name="txt_location_zip" id="txt_location_zip" placeholder="<?php _e('Zip code', 'wp_event_booking');?>" value="<?php echo $location_zip; ?>">
      <input type="text" class="regular-text" name="txt_location_city" id="txt_location_city" placeholder="<?php _e('City', 'wp_event_booking');?>" value="<?php echo $location_city; ?>">
      <?php
/* @parm $id,
	Default Value : sel_location_country */
	fnc_country_drop_down('sel_location_country');
	?>
    </div>
    <div class="col span_1_of_2"></div>
    </div>
      <?php if (!empty($location_country)) {?>
      <script type="text/javascript">
        jQuery(document).ready(function(){
          jQuery(".sel_location_country").val('<?php echo $location_country; ?>').trigger('change');
        });
      </script>
      <?php }?>
<h4><?php _e('Region', 'wp_event_booking');?></h4>
<hr />
<div class="section group">
  <div class="col span_1_of_2">
    <?php // WP_Query arguments
	echo '<select name="event_region" class="regular-text sel_event_region"><option></option>';
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
		while ($query->have_posts()) {
			$query->the_post();
			$selected = '';
			if (get_the_ID() == $event_region_id) {
				$selected = 'selected';
			} ?><option value="<?php echo get_the_ID(); ?>" <?php echo $selected; ?> ><?php the_title();?></option><?php
}
	} else {
		// no posts found
	}
	// Restore original Post Data
	wp_reset_postdata();
	echo '</select>';?>
  </div>
  <div class="col span_1_of_2">
  <a class="show_location_region button button-primary" href="javascript:void(0)" role="button"><i class="fa fa-plus-circle" aria-hidden="true"></i> <?php _e('Add Region', 'wp_event_booking');?></a>
  </div>
  <div class="section group div_location_region">
  <div class="col span_1_of_2">
    <p><?php _e('New Region', 'wp_event_booking');?></p>
    <input type="text" class="regular-text" name="txt_location_region_title" id="txt_location_region_title" placeholder="<?php _e('Title', 'wp_event_booking');?>">
    <textarea class="regular-text" name="txt_location_region_description" id="txt_location_region_description" placeholder="<?php _e('Description', 'wp_event_booking');?>"></textarea>
    <br /><br />
    <input type="button" class="button button-primary" id="btn_add_location_region" name="btn_add_location_region" value="<?php _e('Add', 'wp_event_booking');?>">
    <input type="button" class="button button-primary" id="btn_cancel_location_region" name="btn_cancel_location_region" value="<?php _e('Cancel', 'wp_event_booking');?>">
  </div>
  <div class="col span_1_of_2"></div>
</div>
</div>
<?php
/* Location meta box Ends here */
}

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function save_location_meta_box($post_id) {
	// Save logic goes here. Don't forget to include nonce checks!
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	if (!isset($_POST['event_region_nonce']) || !wp_verify_nonce($_POST['event_region_nonce'], '_event_region_nonce')) {
		return;
	}
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}
	if (isset($_POST['txt_location_street'])) {
		update_post_meta($post_id, 'location_street', sanitize_text_field($_POST['txt_location_street']));
	}
	if (isset($_POST['txt_location_zip'])) {
		update_post_meta($post_id, 'location_zip', sanitize_text_field($_POST['txt_location_zip']));
	}
	if (isset($_POST['txt_location_city'])) {
		update_post_meta($post_id, 'location_city', sanitize_text_field($_POST['txt_location_city']));
	}
	if (isset($_POST['sel_location_country'])) {
		update_post_meta($post_id, 'location_country', sanitize_text_field($_POST['sel_location_country']));
	}
	if (isset($_POST['event_region'])) {
		update_post_meta($post_id, '_event_region', esc_attr($_POST['event_region']));
	}
}
add_action('save_post', 'save_location_meta_box');

/*** Event Meta Box Ends here ***/
