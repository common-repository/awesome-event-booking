<h1><?php _e('Email Template Settings', 'wp_event_booking');?></h1>
  <form class="wpeb-email-template-settings" method="post" action="options.php">
    <?php settings_fields('wp-event-booking-email-settings');?>
    <?php do_settings_sections('wp-event-booking-email-settings');?>
    <table class="form-table">
	    <tbody>
	    <?php do_action('wpeb_event_booking_email_templates');?>
	    </tbody>
	</table>

    <?php submit_button();?>
  </form>

 <?php /* Email template customization fields are hooked to do_action('wpeb_event_booking_email_templates') from admin_settings.php */