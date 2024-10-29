<h1><?php _e('Event Sign Up Field Settings', 'wp_event_booking');?></h1>
  <form class="wpeb-event-sign-up-settings" method="post" action="options.php">
    <?php settings_fields('wp-event-sign-up-settings');?>
    <?php do_settings_sections('wp-event-sign-up-settings');?>
    <table class="form-table">
	    <tbody>
        <tr valign="top">
          <th scope="row"><?php _e('Attendant first name', 'wp_event_booking');?></th>
          <td>
            <?php $safn = esc_attr(get_option('show_attendant_first_name'));?>
            <span><input type="checkbox" disabled="disabled" name="show_attendant_first_name" value="true" <?php echo ($safn == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $mafn = esc_attr(get_option('mandatory_attendant_first_name'));?>
            <span>
            <input type="checkbox" disabled="disabled" name="mandatory_attendant_first_name" value="true" <?php echo ($mafn == 'true' ? 'checked="checked"' : ''); ?> /><?php _e('Mandatory', 'wp_event_booking');?>
            </span>
            <input type="hidden" name="show_attendant_first_name" value="true">
            <input type="hidden" name="mandatory_attendant_first_name" value="true">
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Attendant last name', 'wp_event_booking');?></th>
          <td>
            <?php $safn = esc_attr(get_option('show_attendant_last_name'));?>
            <span><input type="checkbox" name="show_attendant_last_name" value="true" <?php echo ($safn == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $mafn = esc_attr(get_option('mandatory_attendant_last_name'));?>
            <span><input type="checkbox" name="mandatory_attendant_last_name" value="true" <?php echo ($mafn == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Attendant phone number', 'wp_event_booking');?></th>
          <td>
            <?php $saPhNo = esc_attr(get_option('show_attendant_phone_number'));?>
            <span><input type="checkbox" name="show_attendant_phone_number" value="true" <?php echo ($saPhNo == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $maPhNo = esc_attr(get_option('mandatory_attendant_phone_number'));?>
            <span><input type="checkbox" name="mandatory_attendant_phone_number" value="true" <?php echo ($maPhNo == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Attendant e-mail address', 'wp_event_booking');?></th>
          <td>
            <?php $saEmail = esc_attr(get_option('show_attendant_e_mail_address'));?>
            <span><input type="checkbox" disabled="disabled" name="show_attendant_e_mail_address" value="true" <?php echo ($saEmail == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $maEmail = esc_attr(get_option('mandatory_attendant_e_mail_address'));?>
            <span><input type="checkbox" disabled="disabled" name="mandatory_attendant_e_mail_address" value="true" <?php echo ($maEmail == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
            <input type="hidden" name="show_attendant_e_mail_address" value="true">
            <input type="hidden" name="mandatory_attendant_e_mail_address" value="true">
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Attendant address', 'wp_event_booking');?></th>
          <td>
            <?php $saAdd = esc_attr(get_option('show_attendant_address'));?>
            <span><input type="checkbox" name="show_attendant_address" value="true" <?php echo ($saAdd == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $maAdd = esc_attr(get_option('mandatory_attendant_address'));?>
            <span><input type="checkbox" name="mandatory_attendant_address" value="true" <?php echo ($maAdd == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Attendant zip code', 'wp_event_booking');?></th>
          <td>
            <?php $sazc = esc_attr(get_option('show_attendant_zip_code'));?>
            <span><input type="checkbox" name="show_attendant_zip_code" value="true" <?php echo ($sazc == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $mazc = esc_attr(get_option('mandatory_attendant_zip_code'));?>
            <span><input type="checkbox" name="mandatory_attendant_zip_code" value="true" <?php echo ($mazc == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Attendant city', 'wp_event_booking');?></th>
          <td>
            <?php $sacity = esc_attr(get_option('show_attendant_city'));?>
            <span><input type="checkbox" name="show_attendant_city" value="true" <?php echo ($sacity == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $macity = esc_attr(get_option('mandatory_attendant_city'));?>
            <span><input type="checkbox" name="mandatory_attendant_city" value="true" <?php echo ($macity == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Attendant state', 'wp_event_booking');?></th>
          <td>
            <?php $sastate = esc_attr(get_option('show_attendant_state'));?>
            <span><input type="checkbox" name="show_attendant_state" value="true" <?php echo ($sastate == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $mastate = esc_attr(get_option('mandatory_attendant_state'));?>
            <span><input type="checkbox" name="mandatory_attendant_state" value="true" <?php echo ($mastate == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Company name', 'wp_event_booking');?></th>
          <td>
            <?php $scname = esc_attr(get_option('show_company_name'));?>
            <span><input type="checkbox" name="show_company_name" value="true" <?php echo ($scname == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $mcname = esc_attr(get_option('mandatory_company_name'));?>
            <span><input type="checkbox" name="mandatory_company_name" value="true" <?php echo ($mcname == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Company address', 'wp_event_booking');?></th>
          <td>
            <?php $scadd = esc_attr(get_option('show_company_address'));?>
            <span><input type="checkbox" name="show_company_address" value="true" <?php echo ($scadd == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $mcadd = esc_attr(get_option('mandatory_company_address'));?>
            <span><input type="checkbox" name="mandatory_company_address" value="true" <?php echo ($mcadd == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Company zip code', 'wp_event_booking');?></th>
          <td>
            <?php $scZip = esc_attr(get_option('show_company_zip_code'));?>
            <span><input type="checkbox" name="show_company_zip_code" value="true" <?php echo ($scZip == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $mcZip = esc_attr(get_option('mandatory_company_zip_code'));?>
            <span><input type="checkbox" name="mandatory_company_zip_code" value="true" <?php echo ($mcZip == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Company city', 'wp_event_booking');?></th>
          <td>
            <?php $scCity = esc_attr(get_option('show_company_city'));?>
            <span><input type="checkbox" name="show_company_city" value="true" <?php echo ($scCity == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $mcCity = esc_attr(get_option('mandatory_company_city'));?>
            <span><input type="checkbox" name="mandatory_company_city" value="true" <?php echo ($mcCity == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Company state', 'wp_event_booking');?></th>
          <td>
            <?php $scState = esc_attr(get_option('show_company_state'));?>
            <span><input type="checkbox" name="show_company_state" value="true" <?php echo ($scState == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $mcState = esc_attr(get_option('mandatory_company_state'));?>
            <span><input type="checkbox" name="mandatory_company_state" value="true" <?php echo ($mcState == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Company vat number', 'wp_event_booking');?></th>
          <td>
            <?php $scVatNo = esc_attr(get_option('show_company_vat_number'));?>
            <span><input type="checkbox" name="show_company_vat_number" value="true" <?php echo ($scVatNo == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $mcVatNo = esc_attr(get_option('mandatory_company_vat_number'));?>
            <span><input type="checkbox" name="mandatory_company_vat_number" value="true" <?php echo ($mcVatNo == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Company contact person', 'wp_event_booking');?></th>
          <td>
            <?php $sccp = esc_attr(get_option('show_company_contact_person'));?>
            <span><input type="checkbox" name="show_company_contact_person" value="true" <?php echo ($sccp == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $mccp = esc_attr(get_option('mandatory_company_contact_person'));?>
            <span><input type="checkbox" name="mandatory_company_contact_person" value="true" <?php echo ($mccp == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Company contact person phone number', 'wp_event_booking');?></th>
          <td>
            <?php $sccpPh = esc_attr(get_option('show_company_contact_person_phone_number'));?>
            <span><input type="checkbox" name="show_company_contact_person_phone_number" value="true" <?php echo ($sccpPh == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $mccpPh = esc_attr(get_option('mandatory_company_contact_person_phone_number'));?>
            <span><input type="checkbox" name="mandatory_company_contact_person_phone_number" value="true" <?php echo ($mccpPh == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Company invoice e-mail address', 'wp_event_booking');?></th>
          <td>
            <?php $scIEmail = esc_attr(get_option('show_company_invoice_e_mail_address'));?>
            <span><input type="checkbox" name="show_company_invoice_e_mail_address" value="true" <?php echo ($scIEmail == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $mcIEmail = esc_attr(get_option('mandatory_company_invoice_e_mail_address'));?>
            <span><input type="checkbox" name="mandatory_company_invoice_e_mail_address" value="true" <?php echo ($mcIEmail == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Company reference', 'wp_event_booking');?></th>
          <td>
            <?php $sCmpRef = esc_attr(get_option('show_company_reference'));?>
            <span><input type="checkbox" name="show_company_reference" value="true" <?php echo ($sCmpRef == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $mCmpRef = esc_attr(get_option('mandatory_company_reference'));?>
            <span><input type="checkbox" name="mandatory_company_reference" value="true" <?php echo ($mCmpRef == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Personal identification number', 'wp_event_booking');?></th>
          <td>
            <?php $sPIN = esc_attr(get_option('show_personal_identification_number'));?>
            <span><input type="checkbox" name="show_personal_identification_number" value="true" <?php echo ($sPIN == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Enable/Disable', 'wp_event_booking');?></span>
            <?php $mPIN = esc_attr(get_option('mandatory_personal_identification_number'));?>
            <span><input type="checkbox" name="mandatory_personal_identification_number" value="true" <?php echo ($mPIN == 'true' ? 'checked="checked"' : ''); ?>>
            <?php _e('Mandatory', 'wp_event_booking');?></span>
          </td>
        </tr>
	    <?php do_action('wpeb_event_signup_fields');?>
	    </tbody>
	</table>

    <?php submit_button();?>
  </form>

 <?php /* Signup fields are hooked to do_action('wpeb_event_signup_fields') from admin_settings.php */