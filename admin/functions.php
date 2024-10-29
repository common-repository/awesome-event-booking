<?php
add_action('wp_ajax_pull_attendees', 'fnc_pull_attendees_callback');

function fnc_pull_attendees_callback()
{
    global $wpdb; // this is how you get access to the database

    $event_id = sanitize_text_field($_POST['event_id']);
    $output = $error = '';
    $booking_args = array(
        'post_type' => 'event_booking',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_event_id',
                'value' => $event_id,
                'compare' => '=',
                'type' => 'NUMERIC',
            ),
            array(
                'relation' => 'OR',
                array(
                    'key' => 'booking_status',
                    'value' => 'cancelled',
                    'compare' => '!=',
                ),
                array(
                    'key' => 'booking_status',
                    'compare' => 'NOT EXISTS',
                ),
            ),
        ),
    );
    // The Query
    $booking_query = new WP_Query($booking_args);
    $booking_ids = wp_list_pluck($booking_query->posts, 'ID');
    if (!empty($booking_ids)) {
        $customer_query = "SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = '_customer_id' and post_id IN ( " . implode(',', $booking_ids) . " )";
        $event_customers = $wpdb->get_results($customer_query, OBJECT);
        $customer_ids = wp_list_pluck($event_customers, 'meta_value');

        $user_args = array('include' => $customer_ids, 'orderby' => 'display_name', 'order' => 'ASC');
        $user_query = new WP_User_Query($user_args);
        if (!empty($user_query->get_results())) {
            foreach ($user_query->get_results() as $user) {
                $output .= '<p>' . $user->display_name . '</p>';
            }
        } else {
            $error = __('Noting found...', 'wp_event_booking');
        }
    } else {
        $error = __('Noting found...', 'wp_event_booking');
    }
    if (!empty($output)) {
        echo $output; //json_encode($user_args);
    } else {
        echo $error;
    }
    die();
}

add_action('wp_ajax_create_event_location', 'create_event_location_callback');

function create_event_location_callback()
{
    global $wpdb; // this is how you get access to the database

    $txt_location_title = sanitize_text_field($_POST['txt_location_title']);
    $txt_location_street = sanitize_text_field($_POST['txt_location_street']);
    $txt_location_zip = sanitize_text_field($_POST['txt_location_zip']);
    $txt_location_city = sanitize_text_field($_POST['txt_location_city']);
    $sel_location_country = sanitize_text_field($_POST['sel_location_country']);
    $sel_event_region = sanitize_text_field($_POST['sel_event_region']);

    $event_location_id = wp_insert_post(
        array(
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_author' => get_current_user_id(),
            'post_title' => $txt_location_title,
            'post_status' => 'publish',
            'post_type' => 'event_location',
        )
    );
    add_post_meta($event_location_id, 'location_street', $txt_location_street);
    add_post_meta($event_location_id, 'location_zip', $txt_location_zip);
    add_post_meta($event_location_id, 'location_city', $txt_location_city);
    add_post_meta($event_location_id, 'location_country', $sel_location_country);

    if (isset($_POST['sel_event_region'])) {
        update_post_meta($event_location_id, '_event_region', esc_attr($_POST['sel_event_region']));
    }

    echo json_encode(array('location_id' => $event_location_id, 'location_title' => $txt_location_title));

    wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_ajax_create_event_manager', 'create_event_manager_callback');
function create_event_manager_callback()
{
    global $wpdb; // this is how you get access to the database

    $txt_event_manager_title = sanitize_text_field($_POST['txt_event_manager_title']);
    $txt_event_manager_phone = sanitize_text_field($_POST['txt_event_manager_phone']);
    $txt_event_manager_email = sanitize_text_field($_POST['txt_event_manager_email']);
    $txt_event_manager_website = sanitize_text_field($_POST['txt_event_manager_website']);

    $event_manager_id = wp_insert_post(
        array(
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_author' => get_current_user_id(),
            'post_title' => $txt_event_manager_title,
            'post_status' => 'publish',
            'post_type' => 'event_manager',
        )
    );
    add_post_meta($event_manager_id, 'event_manager_title', $txt_event_manager_title);
    add_post_meta($event_manager_id, 'event_manager_phone', $txt_event_manager_phone);
    add_post_meta($event_manager_id, 'event_manager_email', $txt_event_manager_email);
    add_post_meta($event_manager_id, 'event_manager_website', $txt_event_manager_website);

    echo json_encode(array('event_manager_id' => $event_manager_id, 'event_manager_title' => $txt_event_manager_title));

    wp_die(); // this is required to terminate immediately and return a proper response
}
add_action('wp_ajax_create_location_region', 'create_location_region_callback');
function create_location_region_callback()
{
    global $wpdb; // this is how you get access to the database

    $txt_location_region_title = sanitize_text_field($_POST['txt_location_region_title']);
    $txt_location_region_description = sanitize_text_field($_POST['txt_location_region_description']);

    $location_region_id = wp_insert_post(
        array(
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_author' => get_current_user_id(),
            'post_title' => $txt_location_region_title,
            'post_content' => $txt_location_region_description,
            'post_status' => 'publish',
            'post_type' => 'location_region',
        )
    );

    echo json_encode(array('location_region_id' => $location_region_id, 'location_region_title' => $txt_location_region_title));

    wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_ajax_create_event_customer', 'create_event_customer_callback');
function create_event_customer_callback()
{
    global $wpdb; // this is how you get access to the database

    $txt_customer_first_name = sanitize_text_field($_POST['txt_attendant_first_name']);
    $txt_customer_last_name = sanitize_text_field($_POST['txt_attendant_last_name']);
    //$txt_customer_phone = sanitize_text_field($_POST['txt_customer_phone']);
    $user_name = $user_email = $txt_customer_email = sanitize_text_field($_POST['txt_attendant_e_mail_address']);
    //$txt_customer_description = sanitize_text_field($_POST['txt_customer_description']);
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
        //update_user_meta($user_id, '_customer_phone', $txt_customer_phone);
        //update_user_meta($user_id, '_customer_description', $txt_customer_description);
        $sign_up_fields = array('txt_attendant_phone_number', 'txt_attendant_address', 'txt_attendant_zip_code', 'txt_attendant_city', 'txt_attendant_state', 'txt_company_name', 'txt_company_address', 'txt_company_zip_code', 'txt_company_city', 'txt_company_state', 'txt_company_vat_number', 'txt_company_contact_person', 'txt_company_contact_person_phone_number',
            'txt_company_invoice_e_mail_address', 'txt_company_reference', 'txt_personal_identification_number');
        foreach ($sign_up_fields as $suf) {
            if (isset($_POST[$suf])) {
                update_user_meta($user_id, $suf, sanitize_text_field($_POST[$suf]));
            }
        }
    }
    $user_info = get_userdata($user_id);
    $u = new WP_User($user_id);
    // Remove role
    $u->remove_role('subscriber');
    // Add role
    $u->add_role('customer');
    echo json_encode(array('customer_id' => $user_id, 'customer_name' => $user_info->display_name));

    wp_die(); // this is required to terminate immediately and return a proper response
}
function add_customer_roles_on_init()
{
    $role_created = add_role(
        'customer',
        __('Customer'),
        array(
            'read' => true,
        )
    );
    add_role(
        'guest_customer',
        __('Guest Customer'),
        array(
            'read' => true,
        )
    );
}
add_action('init', 'add_customer_roles_on_init');

add_action('admin_head', 'fnc_admin_head');
function fnc_admin_head()
{
    echo '<script type="text/javascript">var datepickerFormat = "' . get_datepicker_format('js_format', get_option('datepickerFormat')) . '";
	var timepickrformat ="' . get_option('timeFormat') . '";
	var addNewLocationPlaceholder = "' . __('Begin typing location or add new location', 'wp_event_booking') . '";
	var addNewManagerPlaceholder = "' . __("Begin typing to search or add new Event Manager", 'wp_event_booking') . '";
	var addNewCustomerPlaceholder = "' . __("Begin typing customer or add new customer", 'wp_event_booking') . '";
	var selectEventPlaceholder = "' . __("Type event name to select an event.", 'wp_event_booking') . '";
	var addNewRegionPlaceholder = "' . __("Begin typing to search or add new Region", 'wp_event_booking') . '";
	var country_trans = "' . __("Country", 'wp_event_booking') . '";
	var region_trans = "' . __("Region", 'wp_event_booking') . '";
	</script>';
}
function get_datepicker_format($task = '', $key = '0')
{
    $datepicker_formats = array(
        '0' => array(0 => 'Y-m-d', 1 => 'yyyy-mm-dd'),
        '1' => array(0 => 'n/j/Y', 1 => 'm-/d/yyyy'),
        '2' => array(0 => 'm/d/Y', 1 => 'mm/dd/yyyy'),
        '3' => array(0 => 'j/n/Y', 1 => 'd/m/yyyy'),
        '4' => array(0 => 'd/m/Y', 1 => 'dd/mm/yyyy'),
        '5' => array(0 => 'n-j-Y', 1 => 'm-d-yyyy'),
        '6' => array(0 => 'm-d-Y', 1 => 'mm-dd-yyyy'),
        '7' => array(0 => 'j-n-Y', 1 => 'd-m-yyyy'),
        '8' => array(0 => 'd-m-Y', 1 => 'dd-mm-yyyy'),
        '9' => array(0 => 'Y.m.d', 1 => 'yyyy.mm.dd'),
        '10' => array(0 => 'm.d.Y', 1 => 'mm.dd.yyyy'),
        '11' => array(0 => 'd.m.Y', 1 => 'dd.mm.yyyy'),
    );
    if ($task == 'js_format') {
        return $datepicker_formats[$key][1];
    } elseif ($task == 'php_format') {
        return $datepicker_formats[$key][0];
    } else {
        return $datepicker_formats;
    }
}
/* Ajax callback function for user dynamic searching */
add_action('wp_ajax_get_event_customers', 'fnc_get_event_customers_callback'); // wp_ajax_{action}
function fnc_get_event_customers_callback()
{
    // we will pass post IDs and titles to this array
    $return = array();
    // WP_User_Query arguments
    $search_string = sanitize_text_field(trim($_GET['q']));

    $args1 = array('search' => "*{$search_string}*",
        'role__in' => array('customer', 'guest_customer'),
        'number' => '30',
        'search_columns' => array(
            'user_login',
            'user_nicename',
            'user_email',
            'user_url',
            'display_name',
        ));
    $args2 = array(
        'role__in' => array('customer', 'guest_customer'),
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'first_name',
                'value' => $search_string,
                'compare' => 'LIKE',
            ),
            array(
                'key' => 'last_name',
                'value' => $search_string,
                'compare' => 'LIKE',
            ),
        ),
    );
    $users1 = get_users($args1);
    $users2 = get_users($args2);
    $userid1 = wp_list_pluck($users1, 'ID');
    $userid2 = wp_list_pluck($users2, 'ID');
    $users_res = array_merge($userid1, $userid2);
    // The User Query
    if (!empty($users_res)) {
        $args = array('role__in' => array('customer', 'guest_customer'), 'include' => $users_res);
        $user_query = new WP_User_Query($args);
    } else {
        $user_query = new WP_User_Query(array('search' => 'testing@2hats'));
    }
    // The User Loop
    if (!empty($user_query->results)) {
        foreach ($user_query->results as $user) {
            $return[] = array($user->ID, $user->display_name); // array( Post ID, Post Title )
        }
    }
    echo json_encode($return);
    wp_die(); // this is required to terminate immediately and return a proper response
}
/* Ajax callback function for events dynamic searching */
add_action('wp_ajax_get_events', 'fnc_get_events_callback'); // wp_ajax_{action}
function fnc_get_events_callback()
{
    // we will pass post IDs and titles to this array
    $return = array();

    // you can use WP_Query, query_posts() or get_posts() here - it doesn't matter
    $search_results = new WP_Query(array(
        'post_type' => 'cpt_events',
        's' => $_GET['q'], // the search query
        'post_status' => 'publish', // if you don't want drafts to be returned
        'ignore_sticky_posts' => 1,
        'posts_per_page' => 50, // how much to show at once
    ));
    if ($search_results->have_posts()):
        while ($search_results->have_posts()): $search_results->the_post();
            // shorten the title a little
            $title = (mb_strlen($search_results->post->post_title) > 50) ? mb_substr($search_results->post->post_title, 0, 49) . '...' : $search_results->post->post_title;
            $return[] = array($search_results->post->ID, $title); // array( Post ID, Post Title )
        endwhile;
    endif;
    echo json_encode($return);
    wp_die(); // this is required to terminate immediately and return a proper response
}

// Load admin scripts & styles
function km_load_admin_scripts($hook)
{
    global $post;

    // The list of post types that we want to require post titles for
    $post_types = array('cpt_events', 'event_location', 'location_region', 'event_manager');
    // If the current post is not one of the chosen post types, exit this function
    if ($post && !in_array($post->post_type, $post_types)) {
        return;
    }

    // Load the scripts & styles below only if we're creating/updating the post
    if ($hook == 'post-new.php' || $hook == 'post.php') {
        wp_enqueue_script('km_dashboard_admin', WPEB_URL . 'src/js/km_dashboard_admin.js', array('jquery'));
    }
}
add_action('admin_enqueue_scripts', 'km_load_admin_scripts');

function fnc_country_drop_down($id = 'sel_location_country')
{
    ?>
	<select name="<?php echo $id; ?>" class="regular-text <?php echo $id; ?>" id="<?php echo $id; ?>">
		<option value=""></option><option value="&Aring;land Islands">&Aring;land Islands</option><option value="Afghanistan">Afghanistan</option><option value="Albania">Albania</option><option value="Algeria">Algeria</option><option value="American Samoa">American Samoa</option><option value="Andorra">Andorra</option><option value="Angola">Angola</option><option value="Anguilla">Anguilla</option><option value="Antarctica">Antarctica</option><option value="Antigua and Barbuda">Antigua and Barbuda</option><option value="Argentina">Argentina</option><option value="Armenia">Armenia</option><option value="Aruba">Aruba</option><option value="Australia">Australia</option><option value="Austria">Austria</option><option value="Azerbaijan">Azerbaijan</option><option value="Bahamas">Bahamas</option><option value="Bahrain">Bahrain</option><option value="Bangladesh">Bangladesh</option><option value="Barbados">Barbados</option><option value="Belarus">Belarus</option><option value="Belgium">Belgium</option><option value="Belize">Belize</option><option value="Benin">Benin</option><option value="Bermuda">Bermuda</option><option value="Bhutan">Bhutan</option><option value="Bolivia">Bolivia</option><option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option><option value="Botswana">Botswana</option><option value="Bouvet Island">Bouvet Island</option><option value="Brazil">Brazil</option><option value="British Indian Ocean Territory">British Indian Ocean Territory</option><option value="Brunei Darussalam">Brunei Darussalam</option><option value="Bulgaria">Bulgaria</option><option value="Burkina Faso">Burkina Faso</option><option value="Burundi">Burundi</option><option value="C&ocirc;te d&#039;Ivoire">C&ocirc;te d&#039;Ivoire</option><option value="Cambodia">Cambodia</option><option value="Cameroon">Cameroon</option><option value="Canada">Canada</option><option value="Cape Verde">Cape Verde</option><option value="Cayman Islands">Cayman Islands</option><option value="Central African Republic">Central African Republic</option><option value="Chad">Chad</option><option value="Chile">Chile</option><option value="China">China</option><option value="Christmas Island">Christmas Island</option><option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option><option value="Collectivity of Saint Martin">Collectivity of Saint Martin</option><option value="Colombia">Colombia</option><option value="Comoros">Comoros</option><option value="Congo">Congo</option><option value="Congo, Democratic Republic of the">Congo, Democratic Republic of the</option><option value="Cook Islands">Cook Islands</option><option value="Costa Rica">Costa Rica</option><option value="Croatia (Local Name: Hrvatska)">Croatia (Local Name: Hrvatska)</option><option value="Cuba">Cuba</option><option value="Cura&ccedil;ao">Cura&ccedil;ao</option><option value="Cyprus">Cyprus</option><option value="Czech Republic">Czech Republic</option><option value="Denmark">Denmark</option><option value="Djibouti">Djibouti</option><option value="Dominica">Dominica</option><option value="Dominican Republic">Dominican Republic</option><option value="East Timor">East Timor</option><option value="Ecuador">Ecuador</option><option value="Egypt">Egypt</option><option value="El Salvador">El Salvador</option><option value="Equatorial Guinea">Equatorial Guinea</option><option value="Eritrea">Eritrea</option><option value="Estonia">Estonia</option><option value="Ethiopia">Ethiopia</option><option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option><option value="Faroe Islands">Faroe Islands</option><option value="Fiji">Fiji</option><option value="Finland">Finland</option><option value="France">France</option><option value="French Guiana">French Guiana</option><option value="French Polynesia">French Polynesia</option><option value="French Southern Territories">French Southern Territories</option><option value="Gabon">Gabon</option><option value="Gambia">Gambia</option><option value="Georgia">Georgia</option><option value="Germany">Germany</option><option value="Ghana">Ghana</option><option value="Gibraltar">Gibraltar</option><option value="Greece">Greece</option><option value="Greenland">Greenland</option><option value="Grenada">Grenada</option><option value="Guadeloupe">Guadeloupe</option><option value="Guam">Guam</option><option value="Guatemala">Guatemala</option><option value="Guinea">Guinea</option><option value="Guinea-Bissau">Guinea-Bissau</option><option value="Guyana">Guyana</option><option value="Haiti">Haiti</option><option value="Heard and McDonald Islands">Heard and McDonald Islands</option><option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option><option value="Honduras">Honduras</option><option value="Hong Kong">Hong Kong</option><option value="Hungary">Hungary</option><option value="Iceland">Iceland</option><option value="India">India</option><option value="Indonesia">Indonesia</option><option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option><option value="Iraq">Iraq</option><option value="Ireland">Ireland</option><option value="Israel">Israel</option><option value="Italy">Italy</option><option value="Jamaica">Jamaica</option><option value="Japan">Japan</option><option value="Jordan">Jordan</option><option value="Kazakhstan">Kazakhstan</option><option value="Kenya">Kenya</option><option value="Kiribati">Kiribati</option><option value="Korea, Democratic People&#039;s Republic of">Korea, Democratic People&#039;s Republic of</option><option value="Korea, Republic of">Korea, Republic of</option><option value="Kuwait">Kuwait</option><option value="Kyrgyzstan">Kyrgyzstan</option><option value="Lao People&#039;s Democratic Republic">Lao People&#039;s Democratic Republic</option><option value="Latvia">Latvia</option><option value="Lebanon">Lebanon</option><option value="Lesotho">Lesotho</option><option value="Liberia">Liberia</option><option value="Libya">Libya</option><option value="Liechtenstein">Liechtenstein</option><option value="Lithuania">Lithuania</option><option value="Luxembourg">Luxembourg</option><option value="Macau">Macau</option><option value="Macedonia">Macedonia</option><option value="Madagascar">Madagascar</option><option value="Malawi">Malawi</option><option value="Malaysia">Malaysia</option><option value="Maldives">Maldives</option><option value="Mali">Mali</option><option value="Malta">Malta</option><option value="Marshall Islands">Marshall Islands</option><option value="Martinique">Martinique</option><option value="Mauritania">Mauritania</option><option value="Mauritius">Mauritius</option><option value="Mayotte">Mayotte</option><option value="Mexico">Mexico</option><option value="Micronesia, Federated States of">Micronesia, Federated States of</option><option value="Moldova, Republic of">Moldova, Republic of</option><option value="Monaco">Monaco</option><option value="Mongolia">Mongolia</option><option value="Montenegro">Montenegro</option><option value="Montserrat">Montserrat</option><option value="Morocco">Morocco</option><option value="Mozambique">Mozambique</option><option value="Myanmar">Myanmar</option><option value="Namibia">Namibia</option><option value="Nauru">Nauru</option><option value="Nepal">Nepal</option><option value="Netherlands">Netherlands</option><option value="New Caledonia">New Caledonia</option><option value="New Zealand">New Zealand</option><option value="Nicaragua">Nicaragua</option><option value="Niger">Niger</option><option value="Nigeria">Nigeria</option><option value="Niue">Niue</option><option value="Norfolk Island">Norfolk Island</option><option value="Northern Mariana Islands">Northern Mariana Islands</option><option value="Norway">Norway</option><option value="Oman">Oman</option><option value="Pakistan">Pakistan</option><option value="Palau">Palau</option><option value="Panama">Panama</option><option value="Papua New Guinea">Papua New Guinea</option><option value="Paraguay">Paraguay</option><option value="Peru">Peru</option><option value="Philippines">Philippines</option><option value="Pitcairn">Pitcairn</option><option value="Poland">Poland</option><option value="Portugal">Portugal</option><option value="Puerto Rico">Puerto Rico</option><option value="Qatar">Qatar</option><option value="Reunion">Reunion</option><option value="Romania">Romania</option><option value="Russian Federation">Russian Federation</option><option value="Rwanda">Rwanda</option><option value="S&atilde;o Tom&eacute; and Pr&iacute;ncipe">S&atilde;o Tom&eacute; and Pr&iacute;ncipe</option><option value="Saint Barth&eacute;lemy">Saint Barth&eacute;lemy</option><option value="Saint Helena">Saint Helena</option><option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option><option value="Saint Lucia">Saint Lucia</option><option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option><option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option><option value="Samoa">Samoa</option><option value="San Marino">San Marino</option><option value="Saudi Arabia">Saudi Arabia</option><option value="Senegal">Senegal</option><option value="Serbia">Serbia</option><option value="Seychelles">Seychelles</option><option value="Sierra Leone">Sierra Leone</option><option value="Singapore">Singapore</option><option value="Sint Maarten">Sint Maarten</option><option value="Slovakia (Slovak Republic)">Slovakia (Slovak Republic)</option><option value="Slovenia">Slovenia</option><option value="Solomon Islands">Solomon Islands</option><option value="Somalia">Somalia</option><option value="South Africa">South Africa</option><option value="South Georgia, South Sandwich Islands">South Georgia, South Sandwich Islands</option><option value="Spain">Spain</option><option value="Sri Lanka">Sri Lanka</option><option value="Sudan">Sudan</option><option value="Suriname">Suriname</option><option value="Svalbard and Jan Mayen Islands">Svalbard and Jan Mayen Islands</option><option value="Swaziland">Swaziland</option><option value="Sweden">Sweden</option><option value="Switzerland">Switzerland</option><option value="Syrian Arab Republic">Syrian Arab Republic</option><option value="Taiwan">Taiwan</option><option value="Tajikistan">Tajikistan</option><option value="Tanzania, United Republic of">Tanzania, United Republic of</option><option value="Thailand">Thailand</option><option value="Togo">Togo</option><option value="Tokelau">Tokelau</option><option value="Tonga">Tonga</option><option value="Trinidad and Tobago">Trinidad and Tobago</option><option value="Tunisia">Tunisia</option><option value="Turkey">Turkey</option><option value="Turkmenistan">Turkmenistan</option><option value="Turks and Caicos Islands">Turks and Caicos Islands</option><option value="Tuvalu">Tuvalu</option><option value="Uganda">Uganda</option><option value="Ukraine">Ukraine</option><option value="United Arab Emirates">United Arab Emirates</option><option value="United Kingdom">United Kingdom</option><option value="United States">United States</option><option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option><option value="Uruguay">Uruguay</option><option value="Uzbekistan">Uzbekistan</option><option value="Vanuatu">Vanuatu</option><option value="Venezuela">Venezuela</option><option value="Viet Nam">Viet Nam</option><option value="Virgin Islands (British)">Virgin Islands (British)</option><option value="Virgin Islands (U.S.)">Virgin Islands (U.S.)</option><option value="Wallis and Futuna Islands">Wallis and Futuna Islands</option><option value="Western Sahara">Western Sahara</option><option value="Yemen">Yemen</option><option value="Zambia">Zambia</option><option value="Zimbabwe">Zimbabwe</option></select>
		<?php
}

add_filter('manage_edit-event_booking_columns', 'fnc_edit_event_booking_columns');

function fnc_edit_event_booking_columns($columns)
{
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => __('Title'),
        'status' => __('Status'),
        'date' => __('Date'),
    );

    return $columns;
}

add_action('manage_event_booking_posts_custom_column', 'my_manage_event_booking_columns', 10, 2);

function my_manage_event_booking_columns($column, $post_id)
{
    global $post;

    switch ($column) {

/* If displaying the 'duration' column. */
        case 'status':

            /* Get the post meta. */
            $status = get_post_meta($post_id, 'booking_status', true);

            /* If no duration is found, output a default message. */
            if (empty($status)) {
                //echo __('Unknown');
                $_event_id = get_post_meta($post_id, '_event_id', true);
                if ($_event_id) {
                    $_eventStartDate = get_post_meta($_event_id, '_eventStartDate', true);
                    if (strtotime("now") < strtotime($_eventStartDate)) {
                        echo '<p class="success">' . __('Upcoming', 'wp_event_booking') . '</p>';
                    } else {
                        echo '<p class="warning">' . __('Past', 'wp_event_booking') . '</p>';
                    }
                }
            }

            /* If there is a duration, append 'minutes' to the text string. */
            else {
                    echo __('<p class="error">' . ucfirst($status) . '</p>');
                }

                break;

            /* Just break out of the switch statement for everything else. */
            default:
                break;
        }
    }
    /* Add custom bulk event cancel option to. */
    add_filter('bulk_actions-edit-event_booking', 'register_my_bulk_actions');

    function register_my_bulk_actions($bulk_actions)
{
        $bulk_actions['activate_booking'] = __('Activate Booking', 'wp_event_booking');
        $bulk_actions['cancel_booking'] = __('Cancel Booking', 'wp_event_booking');
        return $bulk_actions;
    }
    /* Process bulk action activate or cancel booking.*/
    add_filter('handle_bulk_actions-edit-event_booking', 'my_bulk_action_handler', 10, 3);
    function my_bulk_action_handler($redirect_to, $doaction, $post_ids)
{
        if ($doaction == 'activate_booking') {
            // Perform action for each post.
            foreach ($post_ids as $post_id) {
                update_post_meta($post_id, 'booking_status', '');
                $_customer_id = get_post_meta($post_id, '_customer_id', true);
                $_event_id = get_post_meta($post_id, '_event_id', true);
                if ($_customer_id) {
                    //callback_notification_email($_event_id, $_customer_id, $post_id, '', 'cancelCustomerNotificationSubject', 'cancelCustomerNotification');
                    //callback_notification_email($_event_id, $_customer_id, $post_id, 'admin', 'cancelAdminNotificationSubject', 'cancelAdminNotification');
                    do_action('wpeb_after_activate', $_event_id, $_customer_id, $post_id);
                }
            }
            $redirect_to = add_query_arg('booking_activated', count($post_ids), $redirect_to);
        }
        if ($doaction == 'cancel_booking') {
            foreach ($post_ids as $post_id) {
                update_post_meta($post_id, 'booking_status', 'cancelled');
                $_customer_id = get_post_meta($post_id, '_customer_id', true);
                $_event_id = get_post_meta($post_id, '_event_id', true);
                if ($_customer_id) {
                    //callback_notification_email($_event_id, $_customer_id, $post_id, '', 'cancelCustomerNotificationSubject', 'cancelCustomerNotification');
                    //callback_notification_email($_event_id, $_customer_id, $post_id, 'admin', 'cancelAdminNotificationSubject', 'cancelAdminNotification');
                    do_action('wpeb_after_cancel', $_event_id, $_customer_id, $post_id);
                }
            }
            $redirect_to = add_query_arg('booking_cancelled', count($post_ids), $redirect_to);
        }
        return $redirect_to;
    }

    add_filter('views_edit-event_booking', 'fnc_show_cancelled_link_unset_trash');
    function fnc_show_cancelled_link_unset_trash($views)
    {
        if ((is_admin()) && ($_GET['post_type'] == 'event_booking')) {

            unset($views['trash']);
            
            $query = new WP_Query(array('post_type' => 'event_booking', 'meta_key' => 'booking_status', 'meta_value' => 'cancelled'));
            
            $views['publish_f'] = sprintf(__('<a href="%s">Cancelled <span class="count">(%d)</span></a>', 'wp_event_booking'), admin_url('edit.php?booking_status=cancelled&post_type=event_booking'), $query->found_posts);
            return $views;
        }
    }
    add_filter('post_row_actions', 'remove_row_actions', 10, 2);
    function remove_row_actions($actions, $post)
    {
        if (get_post_type() === 'event_booking') {
            unset($actions['trash']);
            if (get_post_meta($post->ID, 'booking_status', true) == 'cancelled') {
                $qry_args = array('booking_id' => $post->ID, 'process' => 'activate_event_booking', 'post_type' => 'event_booking');
                if ($_GET && !empty($_GET['booking_status']) && $_GET['booking_status'] == 'cancelled') {
                    $qry_args['booking_status'] = 'cancelled';
                }
                $url = add_query_arg($qry_args, admin_url('edit.php'));
                //$actions['activate'] = '<a class="submitactivate" title="Activate event booking" href="' . admin_url('edit.php?post_type=event_booking&booking_id=' . $post->ID . '&process=activate_event_booking') . '">Activate</a>';
                $actions['activate'] = '<a class="submitactivate" title="' . __('Activate event booking', 'wp_event_booking') . '" href="' . $url . '">' . __('Activate', 'wp_event_booking') . '</a>';
            } else {
                //$actions['cancel'] = '<a class="submitcancel" title="Cancel event booking" href="' . admin_url('edit.php?post_type=event_booking&booking_id=' . $post->ID . '&process=cancel_event_booking') . '">Cancel</a>';
                $qry_args = array('booking_id' => $post->ID, 'process' => 'cancel_event_booking', 'post_type' => 'event_booking');
                if ($_GET && !empty($_GET['booking_status']) && $_GET['booking_status'] == 'cancelled') {
                    $qry_args['booking_status'] = 'cancelled';
                }
                $url = add_query_arg($qry_args, admin_url('edit.php'));
                $actions['cancel'] = '<a class="submitcancel" title="' . __('Cancel event booking', 'wp_event_booking') . '" href="' . $url . '">' . __('Cancel', 'wp_event_booking') . '</a>';
            }
        }
        return $actions;
    }

    add_action('admin_init', 'fnc_process_activate_cancel_click');
    function fnc_process_activate_cancel_click()
{
        global $typenow, $pagenow;
        if ($typenow == 'event_booking' && is_admin() && $pagenow == 'edit.php') {
            if ($_GET && !empty($_GET['process']) && !empty($_GET['booking_id']) && $_GET['process'] == 'activate_event_booking') {
                $booking_id = sanitize_text_field($_GET['booking_id']);
                update_post_meta($booking_id, 'booking_status', '');
                $_customer_id = get_post_meta($booking_id, '_customer_id', true);
                $_event_id = get_post_meta($booking_id, '_event_id', true);
                if ($_customer_id) {
                    do_action('wpeb_after_activate', $_event_id, $_customer_id, $booking_id);
                }
                
            } elseif ($_GET && !empty($_GET['process']) && $_GET['process'] == 'cancel_event_booking' && !empty($_GET['booking_id'])) {
            $booking_id = sanitize_text_field($_GET['booking_id']);
            update_post_meta($booking_id, 'booking_status', 'cancelled');
            $_customer_id = get_post_meta($booking_id, '_customer_id', true);
            $_event_id = get_post_meta($booking_id, '_event_id', true);
            do_action('wpeb_after_cancel', $_event_id, $_customer_id, $booking_id);
        }
    }
}
add_action('pre_get_posts', 'fnc_show_cancelled_booking');
function fnc_show_cancelled_booking($query)
{
    if (is_admin() && $_GET && !empty($_GET['booking_status']) && $_GET['booking_status'] == 'cancelled') {
        $query->set('meta_key', 'booking_status');
        $query->set('meta_value', 'cancelled');
    }
}
add_action('show_user_profile', 'extra_user_profile_fields');
add_action('edit_user_profile', 'extra_user_profile_fields');
function extra_user_profile_fields($user)
{
    ?>
<h3><?php _e("Extra profile information", "blank");?></h3>
<table class="form-table">
	<?php
$sign_up_fields = array(
        array('field_name' => 'txt_attendant_phone_number',
            'field_title' => __('Attendant phone number', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_name' => 'txt_attendant_address',
            'field_title' => __('Attendant address', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_name' => 'txt_attendant_zip_code',
            'field_title' => __('Attendant zip code', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_name' => 'txt_attendant_city',
            'field_title' => __('Attendant city', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_name' => 'txt_attendant_state',
            'field_title' => __('Attendant state', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_name' => 'txt_company_name',
            'field_title' => __('Company name', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_name' => 'txt_company_address',
            'field_title' => __('Company address', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_name' => 'txt_company_zip_code',
            'field_title' => __('Company zip code', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_name' => 'txt_company_city',
            'field_title' => __('Company city', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_name' => 'txt_company_state',
            'field_title' => __('Company state', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_name' => 'txt_company_vat_number',
            'field_title' => __('Company vat number', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_name' => 'txt_company_contact_person',
            'field_title' => __('Company contact person', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_name' => 'txt_company_contact_person_phone_number',
            'field_title' => __('Company contact person phone number', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_name' => 'txt_company_invoice_e_mail_address',
            'field_title' => __('Company invoice e-mail address', 'wp_event_booking'),
            'field_type' => 'email',
        ),
        array('field_name' => 'txt_company_reference',
            'field_title' => __('Company reference', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_name' => 'txt_personal_identification_number',
            'field_title' => __('Personal identification number', 'wp_event_booking'),
            'field_type' => 'text',
        ),
    );
    foreach ($sign_up_fields as $SignupFN) {
        ?>
	<tr>
		<th><label for="<?php echo $SignupFN['field_name']; ?>"><?php _e($SignupFN['field_title']);?></label></th>
		<td>
			<input type="<?php echo $SignupFN['field_type']; ?>" name="<?php echo $SignupFN['field_name']; ?>" id="<?php echo $SignupFN['field_name']; ?>" value="<?php echo esc_attr(get_the_author_meta($SignupFN['field_name'], $user->ID)); ?>" class="regular-text" />
		</td>
	</tr>
	<?php
}?>
</table>
<?php
}
add_action('personal_options_update', 'save_extra_user_profile_fields');
add_action('edit_user_profile_update', 'save_extra_user_profile_fields');
function save_extra_user_profile_fields($user_id)
{
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }
    /*
    update_user_meta($user_id, '_customer_phone', sanitize_text_field($_POST['_customer_phone']));
    update_user_meta($user_id, '_customer_zip', sanitize_text_field($_POST['_customer_zip']));
    update_user_meta($user_id, '_customer_city', sanitize_text_field($_POST['_customer_city']));
    update_user_meta($user_id, '_customer_address', sanitize_text_field($_POST['_customer_address']));
     */
    $sign_up_fields = array('txt_attendant_phone_number', 'txt_attendant_address', 'txt_attendant_zip_code', 'txt_attendant_city', 'txt_attendant_state', 'txt_company_name', 'txt_company_address',
        'txt_company_zip_code', 'txt_company_city', 'txt_company_state', 'txt_company_vat_number', 'txt_company_contact_person', 'txt_company_contact_person_phone_number', 'txt_company_invoice_e_mail_address', 'txt_company_reference', 'txt_personal_identification_number');
    foreach ($sign_up_fields as $suf) {
        if (isset($_POST[$suf])) {
            update_user_meta($user_id, $suf, sanitize_text_field($_POST[$suf]));
        }
    }
}

/* Event Signup function for basic plugin */
add_action('init', 'fnc_quick_signup_submit_callback');

function fnc_quick_signup_submit_callback()
{
    if (!wp_doing_ajax()) {
        if ($_POST && isset($_POST['event_id']) && $_POST['wpeb_signup_type'] && $_POST['wpeb_signup_type'] == 'quick_signup') {
            $aeb_settings =  get_option( 'wp_event_booking-settings' ); 
            $url = 'https://www.google.com/recaptcha/api/siteverify';
                $data=array(
                    'secret' => $aeb_settings['recaptcha']['secret_key'],
                    'response' => $_POST['g-recaptcha-hd'],
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                );
                $options=array(
                    'http'=> array(
                        'header'=>"Content-Type: application/x-www-form-urlencoded\r\n",
                        'method'=>'POST',
                        'content'=>http_build_query($data)
                    )
                );
                $context = stream_context_create($options);
                $response = file_get_contents($url,false,$context);
                $response = json_decode($response,true);


            if(($aeb_settings['recaptcha']['status']==1 && $response['success']!=true)) {
                 print __('Sorry, google recaptcha error shown', 'wp_event_booking');
                exit;
            } else if (!isset($_POST['verify_its_you']) || !wp_verify_nonce($_POST['verify_its_you'], '2h@tslogic')) {
                print __('Sorry, your nonce did not verify.', 'wp_event_booking');
                exit;
            } else {
                $event_id = sanitize_text_field($_POST['event_id']);
                $random_password = $user_id = $user_name = '';

                $status_bookings = get_event_booking_count($event_id);
                $total_seats = get_post_meta($event_id, '_available_spots', true);
                $available_seats = 0;
                if (!empty($total_seats)) {
                    $available_seats = $total_seats - $status_bookings;
                    if (sizeof($_POST['txt_attendant_first_name']) > $available_seats) {
                        print __('Sorry, not enough seats available.', 'wp_event_booking');
                        exit;
                    }
                }
                $j = 0;
                for ($i = 0; $i < sizeof($_POST['txt_attendant_first_name']); $i++) {
                    $txt_customer_first_name = sanitize_text_field($_POST['txt_attendant_first_name'][$i]);
                    if (isset($_POST['txt_attendant_last_name'][$i]) && !empty($_POST['txt_attendant_last_name'][$i])) {
                        $txt_customer_last_name = sanitize_text_field($_POST['txt_attendant_last_name'][$i]);
                    } else {
                        $txt_customer_last_name = '';
                    }
                    $user_name = $user_email = sanitize_text_field($_POST['txt_attendant_e_mail_address'][$i]);
                    $user_id = '';
                    $user_id = username_exists($user_name);

                    if (!$user_id and email_exists($user_email) == false) {
                        //$user_id = register_new_user($user_name, $user_email);
                        $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
                        $user_id = wp_create_user($user_name, $random_password, $user_email);
                    }
                    if (get_option('event_manager_register_user') == 'yes') {
                        $u = new WP_User($user_id);
                        // Remove role
                        $u->remove_role('subscriber');
                        // Add role
                        $u->add_role('customer');
                    } else {
                        $u = new WP_User($user_id);
                        // Remove role
                        $u->remove_role('subscriber');
                        // Add role
                        $u->add_role('guest_customer');
                    }
                    $userdata = array(
                        'ID' => $user_id,
                        'first_name' => $txt_customer_first_name,
                        'last_name' => $txt_customer_last_name,
                    );
                    if (!empty($txt_customer_first_name) || !empty($txt_customer_last_name)) {
                        $userdata['display_name'] = $txt_customer_first_name . ' ' . $txt_customer_last_name;
                    }
                    wp_update_user($userdata);
                    //update_user_meta($user_id, '_customer_phone', $txt_customer_phone);
                    //update_user_meta($user_id, '_customer_zip', $txt_zip);
                    //update_user_meta($user_id, '_customer_city', $txt_city);
                    //update_user_meta($user_id, '_customer_address', $txt_address);
                    $sign_up_fields = array('txt_attendant_phone_number', 'txt_attendant_address', 'txt_attendant_zip_code', 'txt_attendant_city', 'txt_attendant_state', 'txt_company_name', 'txt_company_address', 'txt_company_zip_code', 'txt_company_city', 'txt_company_state', 'txt_company_vat_number', 'txt_company_contact_person', 'txt_company_contact_person_phone_number',
                        'txt_company_invoice_e_mail_address', 'txt_company_reference', 'txt_personal_identification_number');
                    foreach ($sign_up_fields as $suf) {
                        if (isset($_POST[$suf][$i])) {
                            update_user_meta($user_id, $suf, sanitize_text_field($_POST[$suf][$i]));
                        }
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
                            array(
                                'key' => 'booking_status',
                                'value' => 'cancelled',
                                'type' => 'CHAR',
                                'compare' => '!=',
                            ),
                        ),
                    );

                    $prev_bookings = get_posts($prev_booking_arg);

                    // check user already have booking for perticular event id.
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
                            wp_update_post(array('ID' => $booking_id, 'post_title' => __('Booking #', 'wp_event_booking') . $booking_id . __(' - Event: ', 'wp_event_booking') . get_the_title($event_id) . __(' - Customer: ', 'wp_event_booking') . $user_info->display_name));
                            
                            do_action('wpeb_after_signup', $event_id, $user_id, $booking_id, $random_password);
                            $customerList[$j]['user_id'] = $user_id;
                            $customerList[$j]['booking_id'] = $booking_id;

                            $j++;

                            //do_action('wpeb_after_signup', $event_id, $user_id, $random_password);

                            //$_SESSION['messages'][] = '<p class="success">Success: Event booked for ' . $user_name . '</p>';
                            $status = 'success';
                        } else {
                            //$_SESSION['messages'][] = '<p class="error">Error: Event couldn\'t book for ' . $user_name . '</p>';
                            $status = 'error';
                        }
                    } else {

                        //$_SESSION['messages'][] = '<p class="error">Event already booked for ' . $user_name . '</p>';
                        $status = 'error';
                    }
                } // End here

                if (!empty($customerList)) {
                    CustomNotificationEmailTemplateCallback($event_id, $customerList);
                }
                $url = add_query_arg('status', $status, get_permalink(get_option('wpeb_checkout_page')));
                wp_redirect($url);
                die();
            }
        }
    }
}
/* Add event date time picker */
add_action('wpeb_event_date_time', 'fnc_wpeb_event_date_time');
function fnc_wpeb_event_date_time($post)
{
    global $typenow, $pagenow;
    $time_style = '';
    $start_date = $start_time = $end_date = $end_time = $all_day_event = '';
    $dateFormat = get_datepicker_format('php_format', get_option('datepickerFormat'));
    if (in_array($typenow, array('cpt_events')) && $pagenow == 'post-new.php') {
        if (empty($start_date)) {
            $start_date = date($dateFormat);
        }
        if (empty($end_date)) {
            $end_date = date($dateFormat);
        }
        if (empty($start_time)) {
            $start_time = '09:00am';
        }
        if (empty($end_time)) {
            $end_time = '05:00pm';
        }
    } else {
        $_EventStartDate = get_post_meta($post->ID, '_eventStartDate', true);
        $_EventEndDate = get_post_meta($post->ID, '_eventEndDate', true);
        $all_day_event = get_post_meta($post->ID, '_all_day_event', true);
        $time_style = '';
        $start_date = $start_time = $end_date = $end_time = '';
        $dateFormat = get_datepicker_format('php_format', get_option('datepickerFormat'));
        //date_create_from_format
        if ($_EventStartDate) {
            $start_date = date($dateFormat, strtotime($_EventStartDate));
            $start_time = date('H:i:s', strtotime($_EventStartDate));
        }
        if ($_EventEndDate) {
            $end_date = date($dateFormat, strtotime($_EventEndDate));
            $end_time = date('H:i:s', strtotime($_EventEndDate));
        }

        if ($all_day_event == 'yes') {
            if (empty($start_time)) {
                $start_time = '';
            }
            if (empty($end_time)) {
                $end_time = '';
            }
            $time_style = ' style="display:none;" ';
        }
    }?>
	<p class="div_date_time">
      <input type="text" name="txt_start_date[]" id="txt_start_date" class="date start" value="<?php echo $start_date; ?>" />
      <input type="text" name="txt_start_time[]" id="txt_start_time" <?php echo $time_style; ?> class="time start" value="<?php echo $start_time; ?>" /> to
      <input type="text" name="txt_end_time[]" id="txt_end_time" <?php echo $time_style; ?> class="time end" value="<?php echo $end_time; ?>" />
      <input type="text" name="txt_end_date[]" id="txt_end_date" class="date end" value="<?php echo $end_date; ?>" />
      <span><input type="checkbox" name="chk_all_day_event[]" id="chk_all_day_event" value="yes" onchange="show_hide_time_textbox()" <?php if ($all_day_event == 'yes') {
        echo 'checked';
    }?>><?php _e(' All Day Event', 'wp_event_booking');?></span>
    </p>
<?php
}
/*add_action('wpeb_event_date_list', 'fnc_wpeb_event_date_list_single_event');
function fnc_wpeb_event_date_list_single_event($output) {
return $output;
}*/
add_filter('wpeb_sign_up_AttendantFields', 'fnc_wpeb_sign_up_AttendantFields_callback');
function fnc_wpeb_sign_up_AttendantFields_callback($signup_AttendantFields)
{
    $AttendantFields = array(
        array('field_option' => 'show_attendant_first_name',
            'field_mandatory' => 'mandatory_attendant_first_name',
            'field_name' => 'txt_attendant_first_name',
            'field_title' => __('Attendant first name', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_option' => 'show_attendant_last_name',
            'field_mandatory' => 'mandatory_attendant_last_name',
            'field_name' => 'txt_attendant_last_name',
            'field_title' => __('Attendant last name', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_option' => 'show_attendant_phone_number',
            'field_mandatory' => 'mandatory_attendant_phone_number',
            'field_name' => 'txt_attendant_phone_number',
            'field_title' => __('Attendant phone number', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_option' => 'show_attendant_e_mail_address',
            'field_mandatory' => 'mandatory_attendant_e_mail_address',
            'field_name' => 'txt_attendant_e_mail_address',
            'field_title' => __('Attendant e-mail address', 'wp_event_booking'),
            'field_type' => 'email',
        ),
        array('field_option' => 'show_attendant_address',
            'field_mandatory' => 'mandatory_attendant_address',
            'field_name' => 'txt_attendant_address',
            'field_title' => __('Attendant address', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_option' => 'show_attendant_zip_code',
            'field_mandatory' => 'mandatory_attendant_zip_code',
            'field_name' => 'txt_attendant_zip_code',
            'field_title' => __('Attendant zip code', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_option' => 'show_attendant_city',
            'field_mandatory' => 'mandatory_attendant_city',
            'field_name' => 'txt_attendant_city',
            'field_title' => __('Attendant city', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_option' => 'show_attendant_state',
            'field_mandatory' => 'mandatory_attendant_state',
            'field_name' => 'txt_attendant_state',
            'field_title' => __('Attendant state', 'wp_event_booking'),
            'field_type' => 'text',
        ),
    );
    return array_merge($signup_AttendantFields, $AttendantFields);
}
add_filter('wpeb_sign_up_CompanyFields', 'fnc_wpeb_sign_up_CompanyFields_callback');
function fnc_wpeb_sign_up_CompanyFields_callback($signup_CompanyFields)
{
    $CompanyFields = array(
        array('field_option' => 'show_company_name',
            'field_mandatory' => 'mandatory_company_name',
            'field_name' => 'txt_company_name',
            'field_title' => __('Company name', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_option' => 'show_company_address',
            'field_mandatory' => 'mandatory_company_address',
            'field_name' => 'txt_company_address',
            'field_title' => __('Company address', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_option' => 'show_company_zip_code',
            'field_mandatory' => 'mandatory_company_zip_code',
            'field_name' => 'txt_company_zip_code',
            'field_title' => __('Company zip code', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_option' => 'show_company_city',
            'field_mandatory' => 'mandatory_company_city',
            'field_name' => 'txt_company_city',
            'field_title' => __('Company city', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_option' => 'show_company_state',
            'field_mandatory' => 'mandatory_company_state',
            'field_name' => 'txt_company_state',
            'field_title' => __('Company state', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_option' => 'show_company_vat_number',
            'field_mandatory' => 'mandatory_company_vat_number',
            'field_name' => 'txt_company_vat_number',
            'field_title' => __('Company vat number', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_option' => 'show_company_contact_person',
            'field_mandatory' => 'mandatory_company_contact_person',
            'field_name' => 'txt_company_contact_person',
            'field_title' => __('Company contact person', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_option' => 'show_company_contact_person_phone_number',
            'field_mandatory' => 'mandatory_company_contact_person_phone_number',
            'field_name' => 'txt_company_contact_person_phone_number',
            'field_title' => __('Company contact person phone number', 'wp_event_booking'),
            'field_type' => 'text',
        ),
        array('field_option' => 'show_company_invoice_e_mail_address',
            'field_mandatory' => 'mandatory_company_invoice_e_mail_address',
            'field_name' => 'txt_company_invoice_e_mail_address',
            'field_title' => __('Company invoice e-mail address', 'wp_event_booking'),
            'field_type' => 'email',
        ),
        array('field_option' => 'show_company_reference',
            'field_mandatory' => 'mandatory_company_reference',
            'field_name' => 'txt_company_reference',
            'field_title' => __('Company reference', 'wp_event_booking'),
            'field_type' => 'text',
        ),
    );
    return array_merge($signup_CompanyFields, $CompanyFields);
}
add_filter('wpeb_sign_up_PIN', 'fnc_wpeb_sign_up_PIN_callback');
function fnc_wpeb_sign_up_PIN_callback($signup_PINField)
{
    $PINField = array(array('field_option' => 'show_personal_identification_number',
        'field_mandatory' => 'mandatory_personal_identification_number',
        'field_name' => 'txt_personal_identification_number',
        'field_title' => __('Personal identification number', 'wp_event_booking'),
        'field_type' => 'text',
    ));
    return array_merge($signup_PINField, $PINField);
}
add_filter('wpeb_checkout_sign_up_fields', 'fnc_wpeb_checkout_sign_up_fields_callback');
function fnc_wpeb_checkout_sign_up_fields_callback()
{
    $AttendantFields = apply_filters('wpeb_sign_up_AttendantFields', array());
    $CompanyFields = apply_filters('wpeb_sign_up_CompanyFields', $AttendantFields);
    $sign_up_fields = apply_filters('wpeb_sign_up_PIN', $CompanyFields);
    return $sign_up_fields;
}
add_filter('wpeb_sign_up_fields', 'fnc_wpeb_sign_up_fields_callback');
function fnc_wpeb_sign_up_fields_callback()
{
    $AttendantFields = apply_filters('wpeb_sign_up_AttendantFields', array());
    $CompanyFields = apply_filters('wpeb_sign_up_CompanyFields', $AttendantFields);
    $sign_up_fields = apply_filters('wpeb_sign_up_PIN', $CompanyFields);
    return $sign_up_fields;
}

/*
Cancel Event notification
 */
add_action('wpeb_after_cancel', 'fncEventCancelNotifyCustomer', 10, 3);
function fncEventCancelNotifyCustomer($_event_id, $_customer_id, $post_id)
{
    callback_notification_email($_event_id, $_customer_id, $post_id, '', 'cancelCustomerNotificationSubject', 'cancelCustomerNotification');
}
add_action('wpeb_after_cancel', 'fncEventCancelNotifyAdmin', 20, 3);
function fncEventCancelNotifyAdmin($_event_id, $_customer_id, $post_id)
{
    callback_notification_email($_event_id, $_customer_id, $post_id, 'admin', 'cancelAdminNotificationSubject', 'cancelAdminNotification');
}

/* Signup event notification */
add_action('wpeb_after_signup', 'fncEventSignupNotifyCustomer', 10, 4);
function fncEventSignupNotifyCustomer($event_id, $user_id, $booking_id, $random_password = '')
{
    callback_notification_email($event_id, $user_id, $booking_id, '', 'signUpCustomerNotificationSubject', 'signUpCustomerNotification', $random_password);
}
//add_action('wpeb_after_signup', 'fncEventSignupNotifyAdmin', 10, 4);
function fncEventSignupNotifyAdmin($event_id, $user_id, $booking_id, $random_password = '')
{
    add_filter('notification_to_email', 'fncFetchAdminEmail');
    callback_notification_email($event_id, $user_id, $booking_id, 'admin', 'signUpAdminNotificationSubject', 'signUpAdminNotification');
    remove_filter('notification_to_email', 'fncFetchAdminEmail');
}
function fncFetchAdminEmail()
{
    return get_option('Administrator_Email');
}
// Modify dashboard post title.
add_filter('the_title', 'my_meta_on_title', 10, 2);
function my_meta_on_title($title, $id)
{
    global $pagenow;
    if (is_admin() && $pagenow == 'edit.php') {
        if ('event_location' == get_post_type($id)) {
            //return get_post_meta($id, 'booking_first_name', true) . ' ' . get_post_meta($id, 'booking_last_name', true);
            if (get_post_meta($id, 'location_city', true)) {
                $title = $title . ', ' . get_post_meta($id, 'location_city', true);
            }
        }
    }
    return $title;
}

function CustomNotificationEmailTemplateCallback($event_id, $customerList, $subject_field = 'signUpAdminNotificationSubject', $message_field = 'signUpAdminNotification', $mail_to = 'admin', $company_details = array())
{
    $event_title = $event_date = $event_time = $user_name = $user_phone = $user_address = $user_email = $event_price = $event_location = $credentials = '';
    $args = array(
        'post_type' => array('cpt_events'),
        'posts_per_page' => 1,
        'post__in' => array($event_id),
    );

    // The Query
    $the_query = new WP_Query($args);
    $output = '';
    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $_EventStartDate = get_post_meta(get_the_ID(), '_eventStartDate', true);
            $dateWithYearFormat = get_option('dateWithYearFormat');
            $event_title = get_the_title();
            $event_date = date($dateWithYearFormat, strtotime($_EventStartDate));
            $event_time = date(get_option('timeFormat'), strtotime($_EventStartDate));
            $event_price = esc_attr(get_option('defaultCurrencySymbol')) . get_post_meta(get_the_ID(), '_event_cost', true);
            $event_location = wpeb_event_location(get_the_ID(), 'details');
        }
        wp_reset_postdata();
    }
    $_eventDates = get_post_meta($event_id, '_eventDates', true);
    $dateWithYearFormat = get_option('dateWithYearFormat');
    $dateWithoutYearFormat = get_option('dateWithoutYearFormat');
    $monthAndYearFormat = get_option('monthAndYearFormat');
    $dateTimeSeparator = get_option('dateTimeSeparator');
    $timeRangeSeparator = get_option('timeRangeSeparator');
    $output = '';
    if (!empty($_eventDates)) {
        $_eventDates = unserialize($_eventDates);
        foreach ($_eventDates as $_eventDate) {
            $_EventStartDate = $_eventDate['_eventStartDate'];
            $_EventEndDate = $_eventDate['_eventEndDate'];
            $all_day_event = $_eventDate['_all_day_event'];
            if ($_EventStartDate) {
                $start_date = date($dateWithYearFormat, strtotime($_EventStartDate));
                $start_time = date(get_option('timeFormat'), strtotime($_EventStartDate));
            }
            if ($_EventEndDate) {
                $end_date = date($dateWithYearFormat, strtotime($_EventEndDate));
                $end_time = date(get_option('timeFormat'), strtotime($_EventEndDate));
            }
            if (date('Y', strtotime($_EventStartDate)) > date('Y')) {
                $return_format = $dateWithYearFormat;
            } else {
                $return_format = $dateWithoutYearFormat;
            }
            if ($all_day_event == 'yes') {
                if ($start_date == $end_date) {
                    $output .= date_i18n($return_format, strtotime($_EventStartDate)) . '<br />';
                } else {
                    $output .= date_i18n($return_format, strtotime($_EventStartDate)) . '-' . date_i18n($return_format, strtotime($_EventEndDate)) . '<br />';
                }
            } else {

                $output .= '<dl style="margin: 5px 0px;">
            <dd style="margin: 0px;">' . date_i18n($return_format, strtotime($_EventStartDate)) . ', ' . date_i18n(get_option('timeFormat'), strtotime($_EventStartDate)) . ' ' . $timeRangeSeparator . ' ' . date_i18n(get_option('timeFormat'), strtotime($_EventEndDate)) . '</dd></dl>';
            }
        }
        $event_date_time = $output;
    } else {
        $event_date_time = $event_date . ' ' . $event_time;
    }
    $user_details = '';
    foreach ($customerList as $CL) {
        $user_id = $CL['user_id'];
        $booking_id = $CL['booking_id'];
        $user_info = get_userdata($user_id);
        $user_name = $user_info->display_name;
        $user_phone = get_user_meta($user_id, 'txt_attendant_phone_number', true);
        $user_address = get_user_meta($user_id, 'txt_attendant_address', true);
        $user_email = $user_info->user_email;
        $user_login = $user_info->user_login;
        if ($user_name) {
            $user_details .= '<p>' . __('User Name', 'wp_event_booking') . ' : ' . $user_name . '</p>';
        }
        if ($user_phone) {
            $user_details .= '<p>' . __('Phone', 'wp_event_booking') . ' : ' . $user_phone . '</p>';
        }
        if ($user_address) {
            $user_details .= '<p>' . __('Address', 'wp_event_booking') . ' : ' . $user_address . '</p>';
        }
        if ($user_email) {
            $user_details .= '<p>' . __('Email', 'wp_event_booking') . ' : ' . $user_email . '</p>';
        }
        if ($booking_id) {
            $user_details .= '<p>' . __('Booking Id', 'wp_event_booking') . ' : ' . $booking_id . '</p>';
        }
        $user_details .= '<hr>';
    }
    if ($mail_to == 'admin') {
        $to = esc_attr(get_option('Administrator_Email'));
    } elseif ($mail_to == 'company') {
        $to = $company_details['txt_company_invoice_e_mail_address'];
    }
    if (!empty($to) && is_email($to)) {
        $placeholders = array('%event_title%', '%event_date_time%', '%event_price%', '%event_location%', '%user_details%');

        if (!empty($company_details)) {
            $placeholders[] = '%company_name%';
            $placeholders[] = '%company_address%';
            $placeholders[] = '%company_zip_code%';
            $placeholders[] = '%company_city%';
            $placeholders[] = '%company_state%';
            $placeholders[] = '%company_vat_number%';
            $placeholders[] = '%company_contact_person%';
            $placeholders[] = '%company_contact_person_phone_number%';
            $placeholders[] = '%company_invoice_e_mail_address%';
            $placeholders[] = '%company_reference%';
        }
        $values = array($event_title, $event_date_time, $event_price, $event_location, $user_details);
        if (!empty($company_details)) {
            $values[] = ($company_details['txt_company_name']) ? $company_details['txt_company_name'] : '';
            $values[] = ($company_details['txt_company_address']) ? $company_details['txt_company_address'] : '';
            $values[] = ($company_details['txt_company_zip_code']) ? $company_details['txt_company_zip_code'] : '';
            $values[] = ($company_details['txt_company_city']) ? $company_details['txt_company_city'] : '';
            $values[] = ($company_details['txt_company_state']) ? $company_details['txt_company_state'] : '';
            $values[] = ($company_details['txt_company_vat_number']) ? $company_details['txt_company_vat_number'] : '';
            $values[] = ($company_details['txt_company_contact_person']) ? $company_details['txt_company_contact_person'] : '';
            $values[] = ($company_details['txt_company_contact_person_phone_number']) ? $company_details['txt_company_contact_person_phone_number'] : '';
            $values[] = ($company_details['txt_company_invoice_e_mail_address']) ? $company_details['txt_company_invoice_e_mail_address'] : '';
            $values[] = ($company_details['txt_company_reference']) ? $company_details['txt_company_reference'] : '';
        }
        $subject = str_replace($placeholders, $values, get_option($subject_field));
        $body = str_replace($placeholders, $values, wpautop(get_option($message_field)));
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        //$headers[] = 'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>';
        $headers[] = 'From: ' . get_option('senderFromName') . ' <' . get_option('senderFromEmail') . '>';
        //get_option('Administrator_Email')
        add_filter('wp_mail_content_type', 'set_html_content_type');
        wp_mail($to, html_entity_decode($subject), $body, $headers);
        remove_filter('wp_mail_content_type', 'set_html_content_type');
    }
}
// Admin add customer, mail sending section.
add_action('wp_ajax_assing_customer_to_event', 'fnc_assing_customer_to_event_callback');
function fnc_assing_customer_to_event_callback()
{
    $post_id = $_POST['event_id'];

    foreach ($_POST['customers'] as $c) {
        $user = get_userdata($c);
        if (!empty($user)) {
            $prev_booking_arg = array(
                'post_type' => 'event_booking',
                'meta_query' => array(
                    array(
                        'key' => '_customer_id',
                        'value' => $c,
                        'type' => 'NUMERIC',
                        'compare' => '=',
                    ),
                    array(
                        'key' => '_event_id',
                        'value' => $post_id,
                        'type' => 'NUMERIC',
                        'compare' => '=',
                    ),
                    array(
                        'relation' => 'OR',
                        array(
                            'key' => '_event_booking_status',
                            'value' => 'cancelled',
                            'compare' => '!=',
                        ),
                        array(
                            'key' => 'booking_status',
                            'compare' => 'NOT EXISTS',
                        )
                    )
                ),
            );
            $prev_bookings = get_posts($prev_booking_arg);
            // check user already have booking
            if (empty($prev_bookings)) {
                $bookinng_details = array(
                    'post_type' => 'event_booking',
                    'post_status' => 'publish',
                    'post_author' => get_current_user_id(),
                    'comment_status' => 'closed', // if you prefer
                    'ping_status' => 'closed', // if you prefer
                );
                $booking_id = wp_insert_post($bookinng_details);
                if ($booking_id) {
                    // insert post meta
                    update_post_meta($booking_id, '_customer_id', $c);
                    update_post_meta($booking_id, '_event_id', $post_id);
                    $user_info = get_userdata($c);
                    // Update the booking info into the database
                    wp_update_post(array('ID' => $booking_id, 'post_title' => __('Booking #', 'wp_event_booking') . $booking_id . __(' - Event: ', 'wp_event_booking') . get_the_title($post_id) . __(' - Customer: ', 'wp_event_booking') . $user_info->display_name));

                    $customerList[$j]['user_id'] = $c;
                    $customerList[$j]['booking_id'] = $booking_id;
                    $j++;
                    callback_notification_email($post_id, $c, $booking_id, '', 'AdminAddCustomerCustomerNotificationSubject', 'AdminAddCustomerCustomerNotification');
                }
            }
        }
    }
    if (!empty($customerList)) {
        CustomNotificationEmailTemplateCallback($post_id, $customerList, 'AdminAddCustomerAdminNotificationSubject', 'AdminAddCustomerAdminNotification');
    }

    die();
}
/* there is a compatibility issue with older version of select 2 that used in wpml */
function remove_wpml_select_2()
{
    wp_dequeue_script('wpml-select-2');
}
add_action('admin_enqueue_scripts', 'remove_wpml_select_2', 999);

add_action('admin_menu', 'add_custom_link_to_list_attendees', 100);
function add_custom_link_to_list_attendees()
{
    if ($_GET && !empty($_GET['page']) && $_GET['page'] == 'attendees') {
        $page_parent = ''; //edit.php?post_type=cpt_events
    } else {
        $page_parent = '';
    }
    add_submenu_page(
        $page_parent,
        'Attendees List', /*page title*/
        'Attendees', /*menu title*/
        'manage_options', /*roles and capabiliyt needed*/
        'attendees',
        'render_custom_link_to_list_attendees'
    );
}
function render_custom_link_to_list_attendees()
{
    if ($_GET && !empty($_GET['event_id'])) {
        global $wpdb; // this is how you get access to the database
        $event_id = sanitize_text_field($_GET['event_id']);
        $output = $error = '';
        $booking_args = array(
            'post_type' => 'event_booking',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_event_id',
                    'value' => $event_id,
                    'compare' => '=',
                    'type' => 'NUMERIC',
                ),
                array(
                    'relation' => 'OR',
                    array(
                        'key' => 'booking_status',
                        'value' => 'cancelled',
                        'compare' => '!=',
                    ),
                    array(
                        'key' => 'booking_status',
                        'compare' => 'NOT EXISTS',
                    ),
                ),
            ),
        );
        // The Query
        $booking_query = new WP_Query($booking_args);
        $booking_ids = wp_list_pluck($booking_query->posts, 'ID');
        if (!empty($booking_ids)) {
            $customer_query = "SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = '_customer_id' and post_id IN ( " . implode(',', $booking_ids) . " )";
            $event_customers = $wpdb->get_results($customer_query, OBJECT);
            $customer_ids = wp_list_pluck($event_customers, 'meta_value');

            $user_args = array('include' => $customer_ids, 'orderby' => 'display_name', 'order' => 'ASC');
            $user_query = new WP_User_Query($user_args);
            if (!empty($user_query->get_results())) {
                $event = get_post($event_id);
                $event_title = $event->post_title;
                $date_format_for_display = get_option('dateWithYearFormat') ? get_option('dateWithYearFormat') : 'F j, Y';
                $time_format_for_display = get_option('timeFormat') ? get_option('timeFormat') : 'h:ia';
                $date_time_separator_for_display = get_option('dateTimeSeparator') ? get_option('dateTimeSeparator') : '@';
                $event_start_date = date($date_format_for_display . $date_time_separator_for_display . $time_format_for_display, strtotime(get_post_meta($event_id, '_eventStartDate', true)));
                $event_location_id = get_post_meta($event_id, '_event_location', true);
                $location_city = get_post_meta($event_location_id, 'location_city', true);?>
				<?php $permalink = admin_url('edit.php') . '?post_type=cpt_events';?>
				<?php // echo __('Event title', 'wp_event_booking');?>
				<?php // echo __('Start Date', 'wp_event_booking');?>
				<?php // echo __('Event City', 'wp_event_booking');?>
				<div class="wrap">
				<div class="event_signup_users">
				<p class="h3"><a href="<?php echo admin_url('post.php') . '?post=' . $event->ID . '&action=edit'; ?>"><?php echo $event_title; ?></a></p>
                    <p class="h4"><?php echo $event_start_date; ?></p>
                    <p class="h5"><?php _e('Total Booking :', 'wp_event_booking');?><?php echo $user_query->get_total(); ?></p>
					<p class="h5"><?php echo $location_city; ?></p>
                    <a class="button button-primary" href="<?php echo $permalink; ?>" role="button"><i class="fa fa-arrow-left aria-hidden="true"></i> Back</a>
                    <?php $current_url = "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>
                    <a class="button button-primary" target="_blank" href="<?php echo add_query_arg(array('wpeb_action' => 'download_list'), $current_url); ?>" role="button"><i class="fa fa-download" aria-hidden="true"></i> <?php _e('Download', 'wp_event_booking');?></a>
			<table class="tablesorter" id="user_details">
				<thead>
					<tr>
						<th class="header"><?php echo __('Attendant name', 'wp_event_booking'); ?></th>
						<th class="header"><?php echo __('Attendant Email', 'wp_event_booking'); ?></th>
                        <th class="header"><?php echo __('Phone Number', 'wp_event_booking'); ?></th>
                        <?php do_action('event_signup_user_user_details_th');?>
					</tr>
				</thead>
				<tbody>
			<?php foreach ($user_query->get_results() as $user) {

                    $bookingID = 0;
                    $customer_booking_query = "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = '_customer_id' and meta_value = " . $user->ID;
                    $customer_booking_result = $wpdb->get_results($customer_booking_query, OBJECT);
                    if ($customer_booking_result) {
                        $bookingID = $customer_booking_result[0]->post_id;
                    }
                    $phone = '';
                    if(!$phone){
                        $phone = get_user_meta($user->ID, 'phone', true);
                    }
                    if (!$phone) {
                        $phone = get_user_meta($user->ID, 'txt_attendant_phone_number', true);
                    }
                    if (!$phone  && $bookingID) {
                    $phone = get_post_meta($bookingID, 'phone', true);
                    }
                    
                    ?>
					<tr>
					<td><a href="<?php echo admin_url('user-edit.php') . '?user_id=' . $user->ID; ?>"><?php echo $user->display_name; ?></a></td>
					<td><?php echo $user->user_email; ?></td>
                    <td><?php echo $phone; ?></td>
                    <?php do_action('event_signup_user_user_details_td', $user->ID, $bookingID);?>
					</tr>
				<?php
}?>
</tbody></table>
</div>
				<?php
} else {
                $error = __('Noting found...', 'wp_event_booking');
            }
        } else {
            $error = __('Noting found...', 'wp_event_booking');
        }
        if (!empty($output)) {
            echo $output; //json_encode($user_args);
        } else {
            echo "<p>" . $error . "</p>";
        }
    } else {
        echo $error = __('Something Wrong...', 'wp_event_booking');?><a class="button button-primary" href="<?php echo $permalink; ?>" role="button"><i class="fa fa-arrow-left aria-hidden="true"></i> Back</a><?php
}?></div><?php
}

/** Code updates from 13/03/2019 */
/**
 * Code to remove the view link from bookings list, regions, event managers, event locations
 */
function event_backend_remove_row_actions($actions)
{
    if (get_post_type() === 'event_booking') {
        unset($actions['view']);
    }
    if (get_post_type() === 'location_region') {
        unset($actions['view']);
    }
    if (get_post_type() === 'event_manager') {
        unset($actions['view']);
    }
    if (get_post_type() === 'event_location') {
        unset($actions['view']);
    }
    return $actions;
}
add_filter('post_row_actions', 'event_backend_remove_row_actions', 10, 1);

/**
 * Code to remove the view link from event categories
 */
function event_backend_remove_row_actions_term($actions, $term)
{
    if ('category' === $term->taxonomy) {
        unset($actions['view']);
    }
    return $actions;
}
add_filter('tag_row_actions', 'event_backend_remove_row_actions_term', 10, 2);

/**
 * Code to remove the view link from event categories
 */
function event_backend_hide_permalinks($return, $post_id, $new_title, $new_slug, $post)
{
    if ($post->post_type == 'event_manager') {
        return '';
    }
    if ($post->post_type == 'location_region') {
        return '';
    }
    if ($post->post_type == 'event_location') {
        return '';
    }
    return $return;
}
add_filter('get_sample_permalink_html', 'event_backend_hide_permalinks', 10, 5);
add_action('init', 'wpeb_action_download_list_callback');
function wpeb_action_download_list_callback()
{
    if ($_GET && isset($_GET['wpeb_action']) && $_GET['wpeb_action'] == 'download_list') {
        if ($_GET && !empty($_GET['event_id'])) {
            global $wpdb; // this is how you get access to the database
            $event_id = sanitize_text_field($_GET['event_id']);
            $output = $error = '';
            $booking_args = array(
                'post_type' => 'event_booking',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => '_event_id',
                        'value' => $event_id,
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ),
                    array(
                        'relation' => 'OR',
                        array(
                            'key' => 'booking_status',
                            'value' => 'cancelled',
                            'compare' => '!=',
                        ),
                        array(
                            'key' => 'booking_status',
                            'compare' => 'NOT EXISTS',
                        ),
                    ),
                ),
            );
            // The Query
            $booking_query = new WP_Query($booking_args);
            $booking_ids = wp_list_pluck($booking_query->posts, 'ID');
            if (!empty($booking_ids)) {
                $customer_query = "SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = '_customer_id' and post_id IN ( " . implode(',', $booking_ids) . " )";
                $event_customers = $wpdb->get_results($customer_query, OBJECT);
                $customer_ids = wp_list_pluck($event_customers, 'meta_value');

                $user_args = array('include' => $customer_ids, 'orderby' => 'display_name', 'order' => 'ASC');
                $user_query = new WP_User_Query($user_args);
                if (!empty($user_query->get_results())) {
                    $event = get_post($event_id);
                    $event_title = $event->post_title;
                    $date_format_for_display = get_option('dateWithYearFormat') ? get_option('dateWithYearFormat') : 'F j, Y';
                    $time_format_for_display = get_option('timeFormat') ? get_option('timeFormat') : 'h:ia';
                    $date_time_separator_for_display = get_option('dateTimeSeparator') ? get_option('dateTimeSeparator') : '@';
                    $event_start_date = date($date_format_for_display . $date_time_separator_for_display . $time_format_for_display, strtotime(get_post_meta($event_id, '_eventStartDate', true)));
                    $event_location_id = get_post_meta($event_id, '_event_location', true);
                    $location_city = get_post_meta($event_location_id, 'location_city', true);
                    $output = '<div class="event_signup_users">
                        <h3 class="h3">' . $event_title . '</a></h3>
                        <h4 class="h4">' . $event_start_date . '</h4>
                        <h5 class="h5">' . __('Total Booking :', 'wp_event_booking') . $user_query->get_total() . '</h5>
                        <p class="">' . $location_city . '</p>
                    <table class="tablesorter" id="user_details">
                    <thead>
                        <tr>
                            <th class="header">' . __('Attendant name', 'wp_event_booking') . '</th>
                            <th class="header">' . __('Attendant Email', 'wp_event_booking') . '</th>
                            <th class="header">' . __('Phone Number', 'wp_event_booking') . '</th>
                            ' . apply_filters('filter_event_signup_user_user_details_th', $user->ID, $bookingID) . '
                        </tr>
                    </thead>
                    <tbody>';
                    foreach ($user_query->get_results() as $user) {

                        $bookingID = 0;
                        $customer_booking_query = "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = '_customer_id' and meta_value = " . $user->ID;
                        $customer_booking_result = $wpdb->get_results($customer_booking_query, OBJECT);
                        if ($customer_booking_result) {
                            $bookingID = $customer_booking_result[0]->post_id;
                        }
                        $phone = '';
                    if(!$phone){
                        $phone = get_user_meta($user->ID, 'phone', true);
                    }
                    if (!$phone) {
                        $phone = get_user_meta($user->ID, 'txt_attendant_phone_number', true);
                    }
                    if (!$phone  && $bookingID) {
                    $phone = get_post_meta($bookingID, 'phone', true);
                    }

                        $output .= '<tr>
                            <td>' . $user->display_name . '</a></td>
                            <td>' . $user->user_email . '</td>
                            <td>' . $phone . '</td>
                            ' . apply_filters('filter_event_signup_user_user_details_td', $user->ID, $bookingID) . '
                        </tr>';

                    }
                    $output .= '</tbody></table></div>';
                }
            }

        }
        $mpdf = new \Mpdf\Mpdf();

        $stylesheet = '
        .event_signup_users {
            text-align: center;
        }
        .event_signup_users .h3 {
            margin: 0;
            font-size: 26px;
            font-weight: 600;
        }
        .event_signup_users .h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }
        .event_signup_users .h5 {
            margin: 0 0 10px;
        }
        .event_signup_users table.tablesorter {
            background-color: #e6EEEE;
            border-radius: 3px;
            border-collapse: collapse;
            border: 1px solid #d1e0e0;
            width:100%;
        }
        .event_signup_users table.tablesorter {
            border-collapse: collapse;
        }
        .event_signup_users table.tablesorter {
            border-collapse: collapse;
        }
        .event_signup_users table.tablesorter {
            border-collapse: collapse;
        }
        .event_signup_users table.tablesorter thead tr th {
            padding: 5px 10px;
            border: 1px solid #d1e0e0;
        }
        .event_signup_users table.tablesorter {
            border-collapse: collapse;
        }
        .event_signup_users table.tablesorter {
            border-collapse: collapse;
        }
        .event_signup_users table.tablesorter tbody td {
            padding: 5px 10px;
            border: 1px solid #e6eeee;
        }
        ';
        //$stylesheet = file_get_contents('http://localhost/wordpresslab/wp-content/plugins/awesome-event-booking/src/css/admin-styles.css?ver=1.9');
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($output);
        $mpdf->Output();
        //echo '<style type="text/css">'.$stylesheet.'</style>';
        //echo $output;
        die();
    }
}

/**
 * Code to remove the cancelled bookings from the count of event bookings page - "All" tab 
 */

add_filter('views_edit-event_booking', 'fnc_change_count_event_bookings');
function fnc_change_count_event_bookings($views)
{
    if ((is_admin()) && ($_GET['post_type'] == 'event_booking') && (isset($_GET['booking_status']) && $_GET['booking_status'] != 'cancelled')) {

        $allQuery = new WP_Query(
            array(
                'post_type' => 'event_booking', 
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => 'booking_status',
                        'compare' => 'NOT EXISTS',
                    ),
                    array(
                        'key' => 'booking_status',
                        'value' => 'cancelled',
                        'type' => 'CHAR',
                        'compare' => '!=',
                    )
                )
            )
        );
        $publishQuery = new WP_Query(
            array(
                'post_type' => 'event_booking',
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => 'booking_status',
                        'compare' => 'NOT EXISTS',
                    ),
                    array(
                        'key' => 'booking_status',
                        'value' => 'cancelled',
                        'type' => 'CHAR',
                        'compare' => '!=',
                    )
                )
            )
        );

        $allCalss = '';
        $publishCalss = '';
        if ($_GET['all_posts'] == '1') {
            $allCalss = 'class="current"';
        } 
        if ($_GET['post_status'] == 'publish') {
            $publishCalss = 'class="current"';
        }

        $views['all'] = sprintf('<a href="%s" '.$allCalss.'>'.__('All').' <span class="count">(%d)</span></a>', admin_url('edit.php?post_type=event_booking&all_posts=1'), $allQuery->found_posts);
        $views['publish'] = sprintf('<a href="%s" '.$publishCalss.'>'.__('Published').' <span class="count">(%d)</span></a>', admin_url('edit.php?post_status=publish&post_type=event_booking'), $publishQuery->found_posts);

    }
    return $views;
}