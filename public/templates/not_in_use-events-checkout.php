<?php
/**
 *Events checkout template.
 * Executes when user runs domainname.ext/events
 */
get_header();?>
<div class="wrap">
<div id="container" class="events-container">
	<div id="content" role="main" class="events-content">
		<section id="primary" class="content-area">
		  <main id="main" class="site-main" role="main">

		  <?php if (have_posts()): ?>

		    <?php
// Start the Loop.
while (have_posts()): the_post();
	if ($_POST) {
		if (!isset($_POST['verify_its_you']) || !wp_verify_nonce($_POST['verify_its_you'], '2h@tslogic')) {
			print 'Sorry, your nonce did not verify.';
			exit;
		} else {

			$event_id = sanitize_text_field($_POST['event_id']);
			for ($i = 0; $i < sizeof($_POST['txt_first_name']); $i++) {
				//echo $_POST['txt_first_name'][$i];

				$txt_customer_first_name = sanitize_text_field($_POST['txt_first_name'][$i]);
				$txt_customer_last_name = sanitize_text_field($_POST['txt_last_name'][$i]);
				$txt_address = sanitize_text_field($_POST['txt_address'][$i]);
				$txt_zip = sanitize_text_field($_POST['txt_zip'][$i]);
				$txt_city = sanitize_text_field($_POST['txt_city'][$i]);
				$txt_customer_phone = sanitize_text_field($_POST['txt_phone_no'][$i]);
				$user_name = $user_email = sanitize_text_field($_POST['txt_email'][$i]);
				$user_id = '';
				if (get_option('event_manager_register_user') == 'yes') {
					$user_id = username_exists($user_name);
					if (!$user_id and email_exists($user_email) == false) {
						$user_id = register_new_user($user_name, $user_email);
						$userdata = array(
							'ID' => $user_id,
							'first_name' => $txt_customer_first_name,
							'last_name' => $txt_customer_last_name,
						);
						if (!empty($txt_customer_first_name) || !empty($txt_customer_last_name)) {
							$userdata['display_name'] = $txt_customer_first_name . ' ' . $txt_customer_last_name;
						}
						wp_update_user($userdata);
						update_user_meta($user_id, '_customer_phone', $txt_customer_phone);
						update_user_meta($user_id, '_customer_zip', $txt_zip);
						update_user_meta($user_id, '_customer_city', $txt_city);
						update_user_meta($user_id, '_customer_address', $txt_address);
						$u = new WP_User($user_id);
						// Remove role
						$u->remove_role('subscriber');
						// Add role
						$u->add_role('customer');
					}
					$prev_booking_arg = array(
						'post_type' => 'event_booking',
						'meta_query' => array(
							array(
								'key' => '_customer_id',
								'value' => $user_id,
								'type' => 'NUMERIC',
								'compare' => '=',
							),
							array(
								'key' => '_event_id',
								'value' => $event_id,
								'type' => 'NUMERIC',
								'compare' => '=',
							),
						),
					);
					$prev_bookings = get_posts($prev_booking_arg);
					// check user already have booking
					if (empty($prev_bookings)) {
						$bookinng_details = array(
							'post_type' => 'event_booking',
							'post_status' => 'publish',
							'post_author' => $user_id,
							'comment_status' => 'closed', // if you prefer
							'ping_status' => 'closed', // if you prefer
						);
						$booking_id = wp_insert_post($bookinng_details);
						if ($booking_id) {
							// insert post meta
							update_post_meta($booking_id, '_customer_id', $user_id);
							update_post_meta($booking_id, '_event_id', $event_id);
							$user_info = get_userdata($user_id);
							// Update the booking info into the database
							wp_update_post(array('ID' => $booking_id, 'post_title' => 'Booking #' . $booking_id . ' - Event: ' . get_the_title($event_id) . ' - Customer: ' . $user_info->display_name));
							echo '<p class="success">Success: Event booked for ' . $user_name . '</p>';
						} else {
							echo '<p class="error">Error: Event couldn\'t book for ' . $user_name . '</p>';
						}
					} else {
						echo '<p class="error">Event already booked for ' . $user_name . '</p>';
					}
				} else {
					$bookinng_details = array(
						'post_type' => 'event_booking',
						'post_status' => 'publish',
						//'post_author' => $user_id,
						'comment_status' => 'closed', // if you prefer
						'ping_status' => 'closed', // if you prefer
					);
					$booking_id = wp_insert_post($bookinng_details);
					if ($booking_id) {
						// insert post meta
						update_post_meta($booking_id, '_customer_id', '');
						update_post_meta($booking_id, '_event_id', $event_id);
						update_post_meta($booking_id, '_customer_first_name', $txt_customer_first_name);
						update_post_meta($booking_id, '_customer_last_name', $txt_customer_last_name);
						update_post_meta($booking_id, '_customer_address', $txt_address);
						update_post_meta($booking_id, '_customer_zip', $txt_zip);
						update_post_meta($booking_id, '_customer_city', $txt_city);
						update_post_meta($booking_id, '_customer_phone', $txt_customer_phone);
						update_post_meta($booking_id, '_customer_email', $user_email);
						//$user_info = get_userdata($user_id);
						// Update the booking info into the database
						wp_update_post(array('ID' => $booking_id, 'post_title' => 'Booking #' . $booking_id . ' - Event: ' . get_the_title($event_id) . ' - Customer: ' . $txt_customer_first_name . ' ' . $txt_customer_last_name));
						echo '<p class="success">Success: Event booked for ' . $user_name . '</p>';
					} else {
						echo '<p class="error">Error: Event couldn\'t book for ' . $user_name . '</p>';
					}
				}
			} // End here
		}
	} elseif ($_GET && !empty($_GET['status']) && $_GET['status'] == 'success') {
	echo $_GET['status'];
} elseif ($_GET && $_GET['event_id']) {
	$event_id = sanitize_text_field($_GET['event_id']);?>
<div class="section group">
	<?php $first_name = $last_name = $address = $phone = $zip = $city = $email = '';?>
	<form id="event_checkout" method="post" action="<?php echo get_permalink(get_option('wpeb_checkout_page')); ?>">
	<div class="col span_1_of_2">

	<div class="participant_details">
		<div id="participant_1" class="participant_clone">
			<p>Participant <span class="participant_count">1</span></p>
			<div class="validatoin_errors">

			</div>
		<p><label>First Name <span class="req">*</span></label><input type="text" name="txt_first_name[]" class="txt_first_name" value="<?php echo $first_name; ?>" required></p>
		<p><label>Last Name <span class="req">*</span></label><input type="text" name="txt_last_name[]" value="<?php echo $last_name; ?>" class="txt_last_name" required></p>
		<p><label>Address <span class="req">*</span></label><input type="text" name="txt_address[]" class="txt_address" value="<?php echo $address; ?>" required></p>
		<p><label>Zip <span class="req">*</span></label><input type="text" name="txt_zip[]" class="txt_zip" value="<?php echo $zip; ?>" required></p>
		<p><label>City <span class="req">*</span></label><input type="text" name="txt_city[]" class="txt_city" value="<?php echo $city; ?>" required></p>
		<p><label>Phone Number <span class="req">*</span></label><input type="text" name="txt_phone_no[]" class="txt_phone_no" value="<?php echo $phone; ?>" required></p>
		<p><label>Email <span class="req">*</span></label><input type="email" name="txt_email[]" class="txt_email" value="<?php echo $email; ?>" required></p>
		<p><label>Repeat Email <span class="req">*</span></label><input type="email" name="txt_repeat_email[]" class="txt_repeat_email" value="<?php echo $email; ?>" required></p>
		</div>
		</div>


		<input type="hidden" name="step" value="2" />
		<input type="hidden" name="event_id" value="<?php echo $event_id; ?>" />
		<input type="hidden" name="event_cost" value="<?php echo get_post_meta($event_id, '_event_cost', true); ?>">
		<input type="submit" name="btn_checkout" id="btn_checkout" value="Submit" <?php //if(empty($email)){ echo 'disabled'; } ?> />
		<?php wp_nonce_field('2h@tslogic', 'verify_its_you');?>

	</div>
	<div class="col span_1_of_2">
		<?php /*<button class="btn add_participants">Add Participants</button><br /><br />
	<button class="btn remove_participants">remove Participants</button>*/?>
		<div class="participant">
	Number of participants:
	<select name="sel_participant" id="sel_participant">
	<?php for ($i = 1; $i <= 16; $i++) {?>
	<option value="<?php echo $i; ?>" <?php if ($i == 1) {echo 'selected="selected"';}?>><?php echo $i; ?></option>
	<?php }?>
	</select>
	</div>
		<div class="price">
			<span class="currency">
				<?php echo esc_attr(get_option('defaultCurrencySymbol')); ?>
				</span>
				<span class="cost">
					<?php echo get_post_meta($event_id, '_event_cost', true); ?>
				</span>
			</div>
		<?php wpeb_event_details($event_id, 'details');?>
		<?php wpeb_event_location($event_id);?>
		<?php //wpeb_event_cost($event_id);?>
		<?php wpeb_event_seats($event_id);?>
		<?php wpeb_event_manager($event_id);?>
	</div>
	</form>
</div>
							<?php
} else {
	echo '<span class="error">Something wrong. Try again</span>';
}
// End the loop.
endwhile;

// If no content, include the "No posts found" template.
else:
	echo 'No events found.';
endif;
?>

		  </main><!-- .site-main -->
		</section><!-- .content-area -->

</div><!-- #content -->
</div><!-- #container -->
</div>
<?php get_footer();?>
