
  <h1><?php _e('General Settings', 'wp_event_booking');?></h1>
  <form method="post" action="options.php">
    <?php settings_fields('wp-event-booking-settings');?>
    <?php do_settings_sections('wp-event-booking-settings');?>
    <table class="form-table wp-event-booking-settings">
      <tr valign="top">
        <th scope="row"><?php _e('Default currency symbol', 'wp_event_booking');?></th>
        <td>
          <input type="text" name="defaultCurrencySymbol" style="max-width:40px" value="<?php echo esc_attr(get_option('defaultCurrencySymbol')); ?>" />
          <p class="tooltip description"><?php _e('Set the default currency symbol for event costs.', 'wp_event_booking');?></p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php _e('Thousand separator', 'wp_event_booking');?></th>
        <td>
          <input type="text" name="thousandSeparator" style="max-width:40px" value="<?php echo esc_attr(get_option('thousandSeparator')); ?>" />
          <p class="tooltip description"><?php _e('This sets the thousand separator of displayed prices.', 'wp_event_booking');?></p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php _e('Decimal separator', 'wp_event_booking');?></th>
        <td>
          <input type="text" name="decimalSeparator" style="max-width:40px" value="<?php echo esc_attr(get_option('decimalSeparator')); ?>" />
          <p class="tooltip description"><?php _e('This sets the decimal separator of displayed prices.', 'wp_event_booking');?></p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php _e('Date with year', 'wp_event_booking');?></th>
        <td>
          <input type="text" name="dateWithYearFormat" value="<?php echo esc_attr(get_option('dateWithYearFormat')); ?>" />
          <code class="live-date-preview">
            <?php if (!empty(get_option('dateWithYearFormat'))) {
	echo date(get_option('dateWithYearFormat'));
}?>
          </code>
          <p class="tooltip description"><?php _e('Enter the format to use for displaying dates with the year. Used when displaying a date in a future year.', 'wp_event_booking');?></p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php _e('Date without year', 'wp_event_booking');?></th>
        <td>
          <input type="text" name="dateWithoutYearFormat" value="<?php echo esc_attr(get_option('dateWithoutYearFormat')); ?>" />
          <code class="live-date-preview">
            <?php if (!empty(get_option('dateWithoutYearFormat'))) {
	echo date(get_option('dateWithoutYearFormat'));
}?>
          </code>
          <p class="tooltip description"><?php _e('Enter the format to use for displaying dates without a year. Used when showing an event from the current year.', 'wp_event_booking');?></p>
        </td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Month and year format', 'wp_event_booking');?></th>
      <td>
        <input type="text" name="monthAndYearFormat" value="<?php echo esc_attr(get_option('monthAndYearFormat')); ?>" />
        <code class="live-date-preview">
        <?php if (!empty(get_option('monthAndYearFormat'))) {
	echo date(get_option('monthAndYearFormat'));
}?>
        </code>
        <p class="tooltip description"><?php _e('Enter the format to use for dates that show a month and year only. Used on month view.', 'wp_event_booking');?></p>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Date time separator', 'wp_event_booking');?></th>
      <td>
        <input type="text" name="dateTimeSeparator" value="<?php echo esc_attr(get_option('dateTimeSeparator')); ?>" />
        <p class="tooltip description"><?php _e('Enter the separator that will be placed between the date and time, when both are shown.', 'wp_event_booking');?></p>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Time Format', 'wp_event_booking');?></th>
      <td>
        <input type="text" name="timeFormat" value="<?php echo esc_attr(get_option('timeFormat')); ?>" />
        <code class="live-date-preview">
            <?php if (!empty(get_option('timeFormat'))) {
	echo date(get_option('timeFormat'));
}?>
        </code>
        <p class="tooltip description"><?php _e('Enter the time format that display in website. Eg: <code>h:ia</code> or <code>G:i</code>', 'wp_event_booking');?></p>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Time range separator', 'wp_event_booking');?></th>
      <td>
        <input type="text" name="timeRangeSeparator" value="<?php echo esc_attr(get_option('timeRangeSeparator')); ?>" />
        <p class="tooltip description"><?php _e('Enter the separator that will be used between the start and end time of an event.', 'wp_event_booking');?></p>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Datepicker Date Format', 'wp_event_booking');?></th>
      <td>
        <?php
$sample_date = strtotime('January 15 ' . date('Y'));
$datepicker_formats = get_datepicker_format();?>
        <select name="datepickerFormat" id='datepickerFormat-select'>
          <?php foreach ($datepicker_formats as $key => $value) {
	$selected = '';
	if ($key == get_option('datepickerFormat')) {
		$selected = ' selected';
	}?>
            <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo date($value[0], $sample_date) ?></option>
          <?php
}?>
        </select>
        <p class="tooltip description"><?php _e('Select the date format to use in datepickers', 'wp_event_booking');?></p>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Show Region', 'wp_event_booking');?></th>
      <td>
        <?php $ser = esc_attr(get_option('show_event_region'));?>
        <input type="checkbox" name="show_event_region" value="true" <?php echo ($ser == 'true' ? 'checked="checked"' : ''); ?> />
        <span class="tooltip description"><?php _e('If enabled, display region in front end.', 'wp_event_booking');?></span>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Event Template', 'wp_event_booking');?></th>
      <td>
        <?php $et = (get_option('event_template')) ? get_option('event_template') : '';?>
          <input type="radio" name="event_template" value="three" <?php echo ($et == 'three' ? 'checked="checked"' : ''); ?> /><?php _e('Grid layout with image', 'wp_event_booking');?>&nbsp;
          <input type="radio" name="event_template" value="" <?php echo ($et == '' ? 'checked="checked"' : ''); ?> /><?php _e('List layout without city', 'wp_event_booking');?>&nbsp;
          <input type="radio" name="event_template" value="two" <?php echo ($et == 'two' ? 'checked="checked"' : ''); ?> /><?php _e('List layout with city', 'wp_event_booking');?><br/>
          <span class="tooltip description"><?php _e('You can change Events page template. Template "List layout with city" only effective if "Show Region" is enabled.', 'wp_event_booking');?></span>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Show City', 'wp_event_booking');?></th>
      <td>
        <?php $sec = esc_attr(get_option('show_event_city'));?>
          <input type="radio" name="show_event_city" value="true" <?php echo ($sec == 'true' ? 'checked="checked"' : ''); ?> /><?php _e('Yes', 'wp_event_booking');?>&nbsp;
          <input type="radio" name="show_event_city" value="" <?php echo ($sec == '' ? 'checked="checked"' : ''); ?> /><?php _e('No', 'wp_event_booking');?>
          <span class="tooltip description"><?php _e('If enabled, display cities in event list.', 'wp_event_booking');?></span>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Extra participants in checkout', 'wp_event_booking');?></th>
      <td>
        <?php $amp = esc_attr(get_option('allow_multiple_participants'));?>
          <input type="radio" name="allow_multiple_participants" value="true" <?php echo ($amp == 'true' ? 'checked="checked"' : ''); ?> /><?php _e('Yes', 'wp_event_booking');?>&nbsp;
          <input type="radio" name="allow_multiple_participants" value="" <?php echo ($amp == '' ? 'checked="checked"' : ''); ?> /><?php _e('No', 'wp_event_booking');?>
          <span class="tooltip description"><?php _e('If enabled, customer can add more than one participants in checkout.', 'wp_event_booking');?></span>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Hide seats informations from frontend', 'wp_event_booking');?></th>
      <td>
        <?php $As = esc_attr(get_option('hideSeatsInfo'));?>
        <input type="radio" name="hideSeatsInfo" value="true" <?php echo ($As == 'true' ? 'checked="checked"' : ''); ?> /><?php _e('Yes', 'wp_event_booking');?>
        <input type="radio" name="hideSeatsInfo" value="false" <?php echo ($As == 'false' ? 'checked="checked"' : ''); ?> /><?php _e('No', 'wp_event_booking');?>
        <span class="tooltip description"><?php _e('If enabled, the seat informations will not show in frontend', 'wp_event_booking');?></span>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Display available seats in sign up form', 'wp_event_booking');?></th>
      <td>
        <?php $As = esc_attr(get_option('AvailableSeats'));?>
        <input type="radio" name="AvailableSeats" value="true" <?php echo ($As == 'true' ? 'checked="checked"' : ''); ?> /><?php _e('Yes', 'wp_event_booking');?>
        <input type="radio" name="AvailableSeats" value="false" <?php echo ($As == 'false' ? 'checked="checked"' : ''); ?> /><?php _e('No', 'wp_event_booking');?>
        <span class="tooltip description"><?php _e('If enabled, the available seats will show up in the sign up form.', 'wp_event_booking');?></span>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Seats Available Format', 'wp_event_booking');?></th>
      <td>
        <?php $asd = esc_attr(get_option('available_seat_display'));?>
        <input type="text" name="available_seat_display" value="<?php echo $asd; ?>" />
        <span class="tooltip description"><?php _e('Example : %total_booking% out of %total_seats% remaining. <br />
        If empty, it shows like "99 seats available."', 'wp_event_booking');?>
          <?php _e('Tags: %total_booking%,%total_seats%,%seats_available%', 'wp_event_booking');?>
        </span>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Administrator Email', 'wp_event_booking');?></th>
      <td>
        <?php $ae = esc_attr(get_option('Administrator_Email'));?>
        <input type="text" name="Administrator_Email" value="<?php echo $ae; ?>" />
        <span class="tooltip description"><?php _e('Notification emails will receive to this email address.', 'wp_event_booking');?></span>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Sender Email Address', 'wp_event_booking');?></th>
      <td>
        <?php $ae = esc_attr(get_option('senderFromEmail'));?>
        <input type="text" name="senderFromEmail" value="<?php echo $ae; ?>" /><code class="live-date-preview">
        <?php _e('Please add e-mail address with the same domain as the site', 'wp_event_booking');?></code>
        <p class="tooltip description"><?php _e('Emails will send from this email address.', 'wp_event_booking');?></p>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('Sender Name', 'wp_event_booking');?></th>
      <td>
        <?php $ae = esc_attr(get_option('senderFromName'));?>
        <input type="text" name="senderFromName" value="<?php echo $ae; ?>" />
        <span class="tooltip description"><?php _e('Sender name', 'wp_event_booking');?></span>
      </td>
    </tr>
    </table>
    <h3><?php _e('Settings: Event manager', 'wp_event_booking');?></h3>
    <table class="form-table">
      <tr valign="top">
        <th scope="row"><?php _e('Phone', 'wp_event_booking');?></th>
        <td>
          <?php $semp = esc_attr(get_option('show_event_manager_phone'));?>
          <input type="checkbox" name="show_event_manager_phone" value="true" <?php echo ($semp == 'true' ? 'checked="checked"' : ''); ?> />
          <span class="tooltip description"><?php _e('If enabled, display event manager phone number in front end.', 'wp_event_booking');?></span>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php _e('Email', 'wp_event_booking');?></th>
        <td>
          <?php $seme = esc_attr(get_option('show_event_manager_email'));?>
          <input type="checkbox" name="show_event_manager_email" value="true" <?php echo ($seme == 'true' ? 'checked="checked"' : ''); ?> />
          <span class="tooltip description"><?php _e('If enabled, display event manager email in front end.', 'wp_event_booking');?></span>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php _e('Website', 'wp_event_booking');?></th>
        <td>
          <?php $semw = esc_attr(get_option('show_event_manager_website'));?>
          <input type="checkbox" name="show_event_manager_website" value="true" <?php echo ($semw == 'true' ? 'checked="checked"' : ''); ?> />
          <span class="tooltip description"><?php _e('If enabled, display event manager website in front end.', 'wp_event_booking');?></span>
        </td>
      </tr>
    </table>
    <h3><?php _e('Settings: Reminder', 'wp_event_booking');?></h3>
    <table class="form-table">
      <tr valign="top">
        <th scope="row"><?php _e('Send reminder e-mail X days before event start date:', 'wp_event_booking');?></th>
        <td>
          <?php $sendReminderBefore = esc_attr(get_option('sendReminderBefore'));?>
          <input type="text" style="max-width:40px;" name="sendReminderBefore" value="<?php echo esc_attr($sendReminderBefore); ?>" />
          <span class="tooltip description"><?php _e('days. (Global settings: Email before this value).', 'wp_event_booking');?></span>
          <p><small><?php _e('This e-mail is sent out via the WordPress Cron job, which is activated when somebody visits this site, including bots. This means that the e-mail will probably be sent out each day without any issues, but if you want to be absolutely sure that the e-mail is sent out, please setup a crob job on your server, that runs the following url every day : ', 'wp_event_booking');
echo trailingslashit(get_bloginfo('url')) . 'wp-cron.php';?></small><p>
        </td>
      </tr>
    </table>
    <input type="hidden" name="event_manager_register_user" value="yes" />
    <?php do_action('wpeb_after_general_settings');?>

    <?php submit_button();?>

  </form>
