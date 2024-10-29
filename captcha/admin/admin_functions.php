<?php 
/**
* add new settings tab for google recaptcha
*/
add_action('wpeb_settings_new_menu_item', 'fnc_wpeb_google_menu',999);
function fnc_wpeb_google_menu($active_tab) {?>
    <a href="?post_type=cpt_events&page=wp_event_booking_settings&tab=recaptcha" class="nav-tab <?php echo $active_tab == 'recaptcha' ? 'nav-tab-active' : ''; ?>"><?php _e('Google Recaptcha Settings', 'wp_event_booking');?></a> 
<?php }
add_action('wpeb_settings_content', 'fnc_wpeb_google_recaptcha');

function fnc_wpeb_google_recaptcha() {
    $aeb_settings = get_option( 'wp_event_booking-settings' ); 
    $language = $aeb_settings['recaptcha']['language'] ? $aeb_settings['recaptcha']['language'] : 'en';
    ?>
    <form method="post" action="">
     <div class="settings_panel" id="settings-google-recaptcha" style="<?php echo (isset($_GET['page']) && isset($_GET['page']) && $_GET['page']=='wp_event_booking_settings'&&$_GET['tab']=='recaptcha') ?'display: block;':'display: none;'; ?>">
      <table class="form-table">

        <tr valign="top" class="">
         <th scope="row" colspan=""><label><?php _e( "Enable google recaptcha", "wp_event_booking" ); ?></label></th>
         <td scope="row" >
           <input type="checkbox" class="status_checkbox" value="1" name="wp_event_booking[recaptcha][status]" <?php echo $aeb_settings['recaptcha']['status']==1?'checked':''; ?>>
       </td>
   </tr>
   <tbody class="<?php echo $aeb_settings['recaptcha']['status']!=1?'disabled-div':''; ?> recaptcha_body">
      <tr valign="top" class="">
         <th scope="row" colspan=""><label><?php _e( "Site key", "wp_event_booking" ); ?></label></th>
         <td scope="row" >
           <input type="text" name="wp_event_booking[recaptcha][site_key]" placeholder="<?php _e('Site key', 'wp_event_booking') ?>" value="<?php echo $aeb_settings['recaptcha']['site_key']; ?>" size="60">
       </td>
   </tr>
   <tr valign="top" class="">
     <th scope="row" colspan=""><label><?php _e( "Secret key", "wp_event_booking" ); ?></label></th>
     <td scope="row">
       <input type="text" name="wp_event_booking[recaptcha][secret_key]" placeholder="<?php _e('Secret key', 'wp_event_booking') ?>" value="<?php echo $aeb_settings['recaptcha']['secret_key']; ?>" size="60">
   </td>
</tr>
<tr valign="top" class="">
 <th scope="row" colspan=""><label><?php _e( "Language code", "wp_event_booking" ); ?></label></th>
 <td scope="row">
   <input type="text" name="wp_event_booking[recaptcha][language]" placeholder="<?php _e('Language code', 'wp_event_booking') ?>" value="<?php echo $aeb_settings['recaptcha']['language']; ?>" size="60"><br>
   <span style="font-style: italic;"><?php _e( 'Get reCaptcha keys from  ', 'wp_event_booking' ) ?><a href="https://www.google.com/recaptcha/admin" target="_blank"><?php _e( 'Google', 'wp_event_booking' ) ?></a></span>
</td>
</tr>
</tbody>
<tr valign="top" class="">
   <th scope="row" colspan=""></th>
   <td scope="row">
    <input type="submit" class="button button-primary" name="submit_save_changes" value="<?php _e( 'Save Changes', 'wp_event_booking' ) ?>"><br>

</td>
</tr>
</tbody>
</table>
</div>
</form>

<?php
}
add_action( 'wp_enqueue_scripts', 'rfqgra_enqueue_scripts' );
function rfqgra_enqueue_scripts() {
    $aeb_settings = get_option( 'wp_event_booking-settings' );
    if($aeb_settings){
        $language = $aeb_settings['recaptcha']['language'] != '' ? $aeb_settings['recaptcha']['language'] : 'en';
    
        wp_register_script("google-recaptchas", "https://www.google.com/recaptcha/api.js?render=" . $aeb_settings['recaptcha']['site_key'] );
        wp_enqueue_script("google-recaptchas");
    }

}

add_action('admin_init','save_settings');
function save_settings() {
    if(isset($_POST['submit_save_changes'])) {
        $aeb_settings = get_option( 'wp_event_booking-settings' );
        $aeb_settings['recaptcha']['site_key']=$_POST['wp_event_booking']['recaptcha']['site_key'];
        $aeb_settings['recaptcha']['secret_key'] =$_POST['wp_event_booking']['recaptcha']['secret_key'];
        $aeb_settings['recaptcha']['language'] = $_POST['wp_event_booking']['recaptcha']['language'];
        $aeb_settings['recaptcha']['status'] = $_POST['wp_event_booking']['recaptcha']['status'];

        update_option('wp_event_booking-settings',$aeb_settings); 
    }
}

add_action('wp_head','header_scripts');

function header_scripts() {
    $aeb_settings =  get_option( 'wp_event_booking-settings' ); 
    if($aeb_settings && $aeb_settings['recaptcha']['status']==1) {

        ?>
    <script>
      grecaptcha.ready(function() {
      grecaptcha.execute('<?php echo $aeb_settings['recaptcha']['site_key']; ?>', {action: 'homepage'}).then(function(token) {
        jQuery('#g-recaptcha-hd').val(token);
      });
  });
  </script>
    <style type="text/css">
        .disabled-div {
            display: none !important;
        }
    </style>
    <?php
}
}
add_action('admin_footer','admin_footer_scripts');
function admin_footer_scripts() {
    ?>
    <script type="text/javascript">
      jQuery(document).ready(function () {       

      });
      jQuery('.status_checkbox').on('click', function(){
        if(jQuery(this).prop("checked") == true){
           jQuery('.recaptcha_body').removeClass('disabled-div');
       }
       else if(jQuery(this).prop("checked") == false){
        jQuery('.recaptcha_body').addClass('disabled-div');
    }
});
</script>
<style type="text/css">
    .disabled-div {
        display: none !important;
    }
</style>
<?php
}

