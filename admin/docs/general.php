<h3><?php _e('General Settings', 'wp_event_booking');?></h3>
<div>
<h3><?php _e('General Settings', 'wp_event_booking');?></h3>
  <p><code><?php _e('Default currency symbol', 'wp_event_booking');?></code> - <?php _e('Here you can set currency symbol for event cost.', 'wp_event_booking');?>(<?php _e('Eg : $', 'wp_event_booking');?>)</p>
  <p><code><?php _e('Thousand separator', 'wp_event_booking');?></code> - <?php _e('Here you can set thousand separator symbol for event price.', 'wp_event_booking');?>(<?php _e('Eg : ,', 'wp_event_booking');?>)</p>
  <p><code><?php _e('Decimal separator', 'wp_event_booking');?></code> - <?php _e('Here you can set decimal separator symbol for event price.', 'wp_event_booking');?>(<?php _e('Eg : .', 'wp_event_booking');?>)</p>
  <p><code><?php _e('Date with year', 'wp_event_booking');?></code> - <?php _e('Here you can set date with year format.', 'wp_event_booking');?>(<?php _e('Eg : F j, Y', 'wp_event_booking');?>). <?php _e('For more details <a href="http://php.net/manual/en/function.date.php">Click Here</a>', 'wp_event_booking');?></p>
  <p><code><?php _e('Date without year', 'wp_event_booking');?></code> - <?php _e('Here you can set date without year format.', 'wp_event_booking');?>(<?php _e('Eg : F j', 'wp_event_booking');?>). <?php _e('For more details <a href="http://php.net/manual/en/function.date.php">Click Here</a>', 'wp_event_booking');?></p>
  <p><code><?php _e('Month and year format', 'wp_event_booking');?></code> - <?php _e('Here you can set month and year format.', 'wp_event_booking');?>(<?php _e('Eg : F Y', 'wp_event_booking');?>). <?php _e('For more details <a href="http://php.net/manual/en/function.date.php">Click Here</a>', 'wp_event_booking');?></p>
  <p><code><?php _e('Date time separator', 'wp_event_booking');?></code> - <?php _e('Here you can set date time separator symbol for event.', 'wp_event_booking');?>(<?php _e('Eg : @', 'wp_event_booking');?>)</p>
  <p><code><?php _e('Time Format', 'wp_event_booking');?></code> - <?php _e('Here you can set time format for event.', 'wp_event_booking');?> (<?php _e('Eg : h:ia', 'wp_event_booking');?>) <?php _e('For more details <a target="_blank" href="http://php.net/manual/en/datetime.formats.time.php">Click Here</a>', 'wp_event_booking');?></p>
  <p><code><?php _e('Time range separator', 'wp_event_booking');?></code> - <?php _e('Here you can set time range separator for event.', 'wp_event_booking');?>(<?php _e('Eg : -', 'wp_event_booking');?>)</p>
  <p><code><?php _e('Datepicker Date Format', 'wp_event_booking');?></code> - <?php _e('Here you can set datepicker date format for event.', 'wp_event_booking');?>(<?php _e('WordPress dashboard Date picker.', 'wp_event_booking');?>)</p>
  <p><code><?php _e('Show Region', 'wp_event_booking');?></code> - <?php _e('If this enabled, events will be grouped inside event in event list page. ', 'wp_event_booking');?></p>
  <p><code><?php _e('Event Template', 'wp_event_booking');?></code> - <?php _e('Available options: `Grid layout with image`,  `List layout without city` and  `List layout with city`. All those results different structure in event listing page. In order to make  `List layout with city work`, Show Region need to be enabled.', 'wp_event_booking');?></p>
  <p><code><?php _e('Show City', 'wp_event_booking');?></code> - <?php _e('If this enabled, City name will display instead of event title in event listing page. City should not be empty for location otherwise event will be ignored. ', 'wp_event_booking');?></p>
  <p><code><?php _e('Extra participants in checkout', 'wp_event_booking');?></code> - <?php _e(' If enabled, customer can add more than one participants in checkout. ', 'wp_event_booking');?></p>
  <p><code><?php _e('Display available seats in sign up form', 'wp_event_booking');?></code> - <?php _e(' If enabled, the available seats will show up in the sign up form. ', 'wp_event_booking');?></p>
  <p><code><?php _e('Seats Available Format', 'wp_event_booking');?></code> - <?php _e('Here you can st available seat format of an event that displayed in frontend. Example : %total_booking% out of %total_seats% remaining.', 'wp_event_booking');?></p>
  <p><?php _e('If empty, it shows like "99 seats available." Tags: %total_booking%,%total_seats%,%seats_available% ', 'wp_event_booking')?></p>
  <p><code><?php _e('Administrator Email', 'wp_event_booking');?></code> - <?php _e('Notification emails will receive to this email address.', 'wp_event_booking');?></p>
  <p><code><?php _e('Sender Email Address', 'wp_event_booking');?></code> - <?php _e('Emails will send from this email address.', 'wp_event_booking');?> <code> E-mail address registred with the current domain is recomended to avoid issues. </code></p>
  <p><code><?php _e('Sender Name', 'wp_event_booking');?></code> - <?php _e('Sender name that appeared in notification emails can set from here.', 'wp_event_booking');?></p>
  <h3><?php _e('Settings: Event manager', 'wp_event_booking');?></h3>
<p><code><?php _e('Phone', 'wp_event_booking');?></code> - <?php _e('If enabled, display event manager phone number in front end.', 'wp_event_booking');?></p>
<p><code><?php _e('Email', 'wp_event_booking');?></code> - <?php _e(' If enabled, display event manager email in front end.', 'wp_event_booking');?></p>
<p><code><?php _e('Website', 'wp_event_booking');?></code> - <?php _e(' If enabled, display event manager website in front end.', 'wp_event_booking');?></p>
<?php do_action('wpeb_after_general_doc');?>
</div>


