<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       example.com
 * @since      1.0.0
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/admin
 * @author     Junayed <devjoo.contact@gmail.com>
 */
class Easy_Rents_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        // ONLY MOVIE CUSTOM TYPE POSTS
        add_filter('manage_jobs_posts_columns', array($this, 'wp_list_table_columnname'));

        // Set custom column in job table
        if ($_GET['post_type'] == 'jobs') {
            $this->jobs_list_table_css();
        }
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        wp_register_style('jquery-ui', plugin_dir_url(__FILE__) . 'css/jquery-ui.css', array(), $this->version, 'all');

        wp_register_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/easy-rents-admin.css', array(), microtime(), 'all');

        wp_enqueue_style('er_bubble_notify', plugin_dir_url(__FILE__) . 'css/er_bubble_notify.css', array(), microtime(), 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        wp_register_script('jquery-ui', plugin_dir_url(__FILE__) . 'js/jquery-ui.js', array('jquery'), $this->version, false);

        wp_register_script('easy-rents-locations', plugin_dir_url(__FILE__) . 'js/easy-rents-locations.js', array('jquery'), microtime(), false);

        wp_register_script('easy-rents-payments', plugin_dir_url(__FILE__) . 'js/easy-rents-payments.js', array('jquery'), microtime(), false);

        wp_register_script('trucktype_media', plugin_dir_url(__FILE__) . 'js/media-uploader.js', array('jquery'), $this->version, true);

    }

    // General settings
    public function easy_rents_setup()
    {
        if (is_admin()) {
            global $wpdb, $wp_query;
            $billpay = $wpdb->query("SELECT COUNT(payment) FROM {$wpdb->prefix}easy_rents_applications WHERE status = 3 AND payment < 1");
            $bubble = sprintf(
                ' <span class="paymentstatus"><span class="count">%d</span></span>',
                $billpay//bubble contents
            );

            add_menu_page( //Main menu register
                "Easy Rents", //page_title
                "Easy Rents" . $bubble, //menu title
                "manage_options", //capability
                "er-settings", //menu_slug
                array($this, "er_settings_cb"), //callback function
                "",
                65
            );

            add_submenu_page("er-settings", "Settings", "Settings", "manage_options", "er-settings", array($this, "er_settings_cb")
            );

            add_submenu_page('er-settings', 'Payment', 'Payment' . $bubble, 'manage_options', 'payment', array($this, 'er_payment_confirm'));

            add_submenu_page('er-settings', 'Locations', 'Locations', 'manage_options', 'locations', array($this, 'er_locations_lists'));
        }
    }

    //er_reset_user_password
    function er_reset_user_password(){
        if (!wp_verify_nonce($_POST['security'], 'er_login_register')) {
            die('Hey! What are you doing?');
        }
        if(isset($_POST['number']) && isset($_POST['newpass']) && isset($_POST['cpass'])){
            $number = intval($_POST['number']);
            $newpass = sanitize_text_field($_POST['newpass']);
            $cpass = sanitize_text_field($_POST['cpass']);

            $user = get_user_by('login', $number);

            if($user){

                $user_id = $user->ID;
                $user_data = wp_set_password($newpass, $user_id);
                if ( is_wp_error( $user_data ) ) {
                    // There was an error; possibly this user doesn't exist.
                    echo wp_json_encode(array('faild' => 'faild'));
                    die;
                } else {
                    // Success!
                    echo wp_json_encode(array('success' => 'Password is updated!'));
                    die;
                }

            }
            die;
        }
    }

    // Login processing
    function er_user_login_process(){
        if (!wp_verify_nonce($_POST['security'], 'er_login_register')) {
            die('Hey! What are you doing?');
        }
        if(isset($_POST['phone']) && isset($_POST['pass'])){
            $number = intval($_POST['phone']);
            $password = sanitize_text_field($_POST['pass']);

            if ( is_numeric( $number ) ) {
                $user = get_user_by( 'login', $number );
            } else {
                echo wp_json_encode(array("error" => 'Invalid credentials.'));
                die;
            }
    
            if ( ! $user ) {
                echo wp_json_encode(array("error" => 'Invalid credentials.'));
                die;
            }
    
            // check the user's login with their password.
            if ( ! wp_check_password( $password, $user->user_pass, $user->ID ) ) {
                echo wp_json_encode(array("error" => 'Invalid credentials.'));
                die;
            }
            
            wp_clear_auth_cookie();
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);
    
            if(current_user_can('administrator')){
                $url = home_url('/wp-admin');
            }else{
                $url = home_url('/' . Easy_Rents_Public::get_post_slug(get_option('profile_page', true)));
            }
            echo wp_json_encode(array("success" => esc_url($url) ));
            die;
        }
    }

    // check_register_user_existing
    function check_register_user_existing(){
        if (!wp_verify_nonce($_POST['security'], 'er_login_register')) {
            die('Hey! What are you doing?');
        }
        if(isset($_POST['phone'])){
            $number = intval($_POST['phone']);
            $user = get_user_by('login', $number);

            if(!$user){
                echo wp_json_encode(array("approve" => "Ok"));
            }else{
                echo wp_json_encode(array("exist" => "This user is already exist!"));
            }
            die;
        }
    }

    // registeration as a user
    function register_access_need(){
        if (!wp_verify_nonce($_POST['security'], 'er_login_register')) {
            die('Hey! What are you doing?');
        }

        if(isset($_POST['phone']) && isset($_POST['password']) && isset($_POST['accountType'])){
            $number = intval($_POST['phone']);
            $password = sanitize_text_field($_POST['password']);
            $accountType = sanitize_text_field($_POST['accountType']);
            $role = "";
            if($accountType == 'driver'){
                $role = 'driver';
            }
            if($accountType == 'customer'){
                $role = 'customer';
            }
            
            if(!empty($number) && !empty($password) && !empty($role)){
                $userdata = array(
                    'user_login'    =>  $number,
                    'display_name'  => 'Unknown',
                    'user_pass'     =>  $password,
                    'role'          => $role,
                    'show_admin_bar_front' => false
                ); 
                
                $user_id = wp_insert_user( $userdata );
                echo $user_id;
                die;
            }
            die;
        }
    }

    // Message to user
    public function message_to_user($message)
    {
        try {
            $secret = get_option('er_smstoken');
        
            if(!empty($secret) && $message['secret'] == $secret):
                $to = $message['phone'];
                $texts = $message['message'];
                $name = $message['name'];
                $receive_date = $message['receive_date'];

                $url = "https://sms.youthfireit.com/api/send?key=".$secret."&phone=".$to."&message=".$texts."&sim=2&receive_date=".$receive_date."";

                $ch = curl_init(); // Initialize cURL
                curl_setopt($ch,CURLOPT_URL, $url);
                curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                curl_close($ch);
                $msg = json_decode($result, true);
                print($msg['message']); // print result as json string
            else:
                // Message not verified, ignore or log
                echo "Message not verified";
            endif;
        } catch (Exception $e) {
            // Something went wrong
            echo "Something went wrong";
        }
    }

    // Register settings
    public function er_page_settings_register()
    {
        add_settings_section('er_settings_section', 'Easy Rents Settings', '', 'er-settings');

        // Add new trip page
        add_settings_field('add_trip_page', 'Add trip page', array($this, 'er_add_trip_page_cb'), 'er-settings', 'er_settings_section');
        register_setting('er_settings_section', 'add_trip_page');

        // Profile page
        add_settings_field('profile_page', 'Profile page', array($this, 'er_profile_page_cb'), 'er-settings', 'er_settings_section');
        register_setting('er_settings_section', 'profile_page');

        // Profile trips
        add_settings_field('profile_trips', 'Profile Trips', array($this, 'er_profile_trips_cb'), 'er-settings', 'er_settings_section');
        register_setting('er_settings_section', 'profile_trips');

        // Profile payment
        add_settings_field('profile_payment', 'Payments', array($this, 'er_profile_payment_cb'), 'er-settings', 'er_settings_section');
        register_setting('er_settings_section', 'profile_payment');

        // Profile settings
        add_settings_field('erprofile_settings', 'Settings', array($this, 'er_profile_settings_cb'), 'er-settings', 'er_settings_section');
        register_setting('er_settings_section', 'erprofile_settings');

        // Profile settings
        add_settings_field('access_page', 'Access Form', array($this, 'er_access_page_cb'), 'er-settings', 'er_settings_section');
        register_setting('er_settings_section', 'access_page');

        // Set commission %
        add_settings_field('job_commission', 'Commissions Percents', array($this, 'er_job_commission_cb'), 'er-settings', 'er_settings_section');
        register_setting('er_settings_section', 'job_commission');

        // Set sms token
        add_settings_field('er_smstoken', 'Token Number', array($this, 'er_smstoken_cb'), 'er-settings', 'er_settings_section');
        register_setting('er_settings_section', 'er_smstoken');

        // Set finished job confirmation
        add_settings_field('jobconfirmationmsg', 'Trip Confirmation Message', array($this, 'er_jobconfirmation_cb'), 'er-settings', 'er_settings_section');
        register_setting('er_settings_section', 'jobconfirmationmsg');

        // Set accept job msg
        add_settings_field('acceptjobmsg', 'Trip Accept Message', array($this, 'er_acceptjobmsg_cb'), 'er-settings', 'er_settings_section');
        register_setting('er_settings_section', 'acceptjobmsg');

        // Payment request message
        add_settings_field('paymentrequestmsg', 'Payment Request Message', array($this, 'er_paymentrequestmsg_cb'), 'er-settings', 'er_settings_section');
        register_setting('er_settings_section', 'paymentrequestmsg');

        // OTP Configure
        add_settings_field('firebaseconfig', 'Firebase Config', array($this, 'er_firebaseconfig_cb'), 'er-settings', 'er_settings_section');
        register_setting('er_settings_section', 'firebaseconfig');
    }

    //Disabled wp backend access
    public function disable_backend_access()
    {
        global $current_user;
        $redirect = home_url('/');
        if (is_admin() && !defined('DOING_AJAX') && (!current_user_can('administrator'))) {
            wp_redirect(home_url());
            exit;
        }
    }

    /**
     * After login redirect user
     */
    public function er_login_redirects($url, $request, $user)
    {
        //is there a user to check?
        if (isset($user->roles) && is_array($user->roles)) {
            //check for admins
            if (in_array('administrator', $user->roles)) {
                // redirect them to the default place
                return admin_url();
            } else {
                return home_url('/' . Easy_Rents_Public::get_post_slug(get_option('profile_page', true)));
            }
        } else {
            return admin_url();
        }
    }

    // Logout redirects
    function er_logout_redirects($user_id){
        // Get the user object.
        $user = get_userdata( $user_id );
        // Get all the user roles as an array.
        $user_roles = $user->roles;
        
        if (in_array('administrator', $user->roles)) {
            // redirect them to the default place
            wp_redirect( home_url('/wp-login.php') );
        }else{
            wp_redirect( home_url('/' . Easy_Rents_Public::get_post_slug(get_option('access_page', true))) );
        }
        
        exit();
    }

    // Add new trip page calback
    public function er_add_trip_page_cb()
    {
        global $wp_query;
        $args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'name' => 'add_trip_page',
            'selected' => get_option('add_trip_page'),
            'show_option_none' => '',
            'show_option_no_change' => 'Select Page',
            'option_none_value' => '',
        );
        wp_dropdown_pages($args);
        echo '<br>';
    }

    // Profile page callback
    public function er_profile_page_cb()
    {
        global $wp_query;
        $args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'name' => 'profile_page',
            'selected' => get_option('profile_page'),
            'show_option_none' => '',
            'show_option_no_change' => 'Select Page',
            'option_none_value' => '',
        );
        wp_dropdown_pages($args);
        echo '<br>';
    }

    // Profile trips page callback
    public function er_profile_trips_cb()
    {
        global $wp_query;
        $args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'name' => 'profile_trips',
            'selected' => get_option('profile_trips'),
            'show_option_none' => '',
            'show_option_no_change' => 'Select Page',
            'option_none_value' => '',
        );
        wp_dropdown_pages($args);
        echo '<br>';
    }

    // Profile payment callback
    public function er_profile_payment_cb()
    {
        global $wp_query;
        $args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'name' => 'profile_payment',
            'selected' => get_option('profile_payment'),
            'show_option_none' => '',
            'show_option_no_change' => 'Select Page',
            'option_none_value' => '',
        );
        wp_dropdown_pages($args);
        echo '<br>';
    }

    // Profile settings
    public function er_access_page_cb()
    {
        global $wp_query;
        $args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'name' => 'access_page',
            'selected' => get_option('access_page'),
            'show_option_none' => '',
            'show_option_no_change' => 'Select Page',
            'option_none_value' => '',
        );
        wp_dropdown_pages($args);
        echo '<br>';
    }
    // Profile settings
    public function er_profile_settings_cb()
    {
        global $wp_query;
        $args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'name' => 'erprofile_settings',
            'selected' => get_option('erprofile_settings'),
            'show_option_none' => '',
            'show_option_no_change' => 'Select Page',
            'option_none_value' => '',
        );
        wp_dropdown_pages($args);
        echo '<br>';
    }

    // Profile page callback
    public function er_job_commission_cb()
    {
        $ommision = get_option('job_commission');
        echo '<input style="width:55px" type="number" name="job_commission" value="' . __($ommision, 'easy-rents') . '" placeholder="0"><br><h3>SMS <hr></h3>';
    }
    // er_smstoken_cb
    public function er_smstoken_cb()
    {
        echo '<input type="password" value="' . get_option('er_smstoken') . '" name="er_smstoken" placeholder="Add Token Number"><br>';
    }
    // er_jobconfirmation_cb
    public function er_jobconfirmation_cb()
    {
        echo '<textarea name="jobconfirmationmsg" type="text" placeholder="Trip Confirmation Message" cols="50" rows="2">' . get_option('jobconfirmationmsg') . '</textarea><br>';
    }
    // er_acceptjobmsg_cb
    public function er_acceptjobmsg_cb()
    {
        echo '<textarea name="acceptjobmsg" type="text" placeholder="Trip Accept Message" cols="50" rows="2">' . get_option('acceptjobmsg') . '</textarea><br>';
    }
    // er_paymentrequestmsg_cb
    public function er_paymentrequestmsg_cb()
    {
        echo '<textarea name="paymentrequestmsg" type="text" placeholder="Payment Request Message" cols="50" rows="2">' . get_option('paymentrequestmsg') . '</textarea><br><h3>OTP <hr></h3>';
    }
    // er_firebaseconfig_cb
    public function er_firebaseconfig_cb()
    {
        echo '<textarea name="firebaseconfig" type="text" placeholder="Firebase SDK snippet" cols="50" rows="10">' . get_option('firebaseconfig') . '</textarea>';
    }

    //er_settings_cb
    public function er_settings_cb()
    {
        require_once plugin_dir_path(__FILE__) . 'partials/easy-rents-admin-display.php';
    }
    //er_payment_confirm
    public function er_payment_confirm()
    {
        require_once plugin_dir_path(__FILE__) . 'partials/er_payment_confirm.php';
    }
    //er_locations_lists
    public function er_locations_lists()
    {
        require_once plugin_dir_path(__FILE__) . 'partials/er_locations_lists.php';
    }

    /*
     * Creating a function to create our CPT
     */

    public function er_job_post()
    {
        // Set UI labels for Custom Post Type
        $labels = array(
            'name' => _x('Jobs', 'Post Type General Name', 'easy-rents'),
            'singular_name' => _x('Job', 'Post Type Singular Name', 'easy-rents'),
            'menu_name' => __('Jobs', 'easy-rents'),
            'all_items' => __('All Jobs', 'easy-rents'),
            'view_item' => __('View Job', 'easy-rents'),
            'add_new_item' => __('Add New Job', 'easy-rents'),
            'add_new' => __('Add New', 'easy-rents'),
            'edit_item' => __('Edit Job', 'easy-rents'),
            'update_item' => __('Update Job', 'easy-rents'),
            'search_items' => __('Search Job', 'easy-rents'),
            'not_found' => __('Not Found', 'easy-rents'),
            'not_found_in_trash' => __('Not found in Trash', 'easy-rents'),
        );

        // Set other options for Custom Post Type

        $args = array(
            'label' => __('jobs', 'easy-rents'),
            'description' => __('Job news and reviews', 'easy-rents'),
            'labels' => $labels,
            'supports' => array('title', 'author'),
            'taxonomies' => array('jobs'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,

        );

        // Registering your Custom Post Type
        register_post_type('jobs', $args);

    }

    // Job car type taxonomy
    public function the_car_type_taxonomy()
    {

        $labels = array(
            'name' => _x('Trucks', 'trucks'),
            'singular_name' => _x('Truck', 'truck'),
            'search_items' => __('Search trucks'),
            'all_items' => __('All trucks'),
            'edit_item' => __('Edit Truck'),
            'update_item' => __('Update Truck'),
            'add_new_item' => __('Add New Truck'),
            'new_item_name' => __('New Truck Name'),
            'menu_name' => __('Trucks'),
        );

        // Now register the truck
        register_taxonomy('truckstype', array('jobs'), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'truck'),
        ));
    }

    // Wp list table css for jobs post
    public function jobs_list_table_css()
    {?>
		<style>
			th#erpost_status {
				width: 8%;
			}
			.status_circle{
				display: inline-block;
				border: none;
				outline:none;
				border-radius:50%;
				padding:10px;
				height:10px;
				width:10px;
			}
		</style>
	<?php
}
    // CREATE WP LIST TABLE COLUMN FOR STATUS
    public function wp_list_table_columnname($defaults)
    {
        $defaults['erprice_status'] = 'Price';
        $defaults['erdriver_status'] = 'Driver/Payment';
        $defaults['erpost_status'] = 'Status';
        return $defaults;
    }
    public function wp_list_table_column_view($column_name, $post_ID)
    {
        if ($column_name == 'erprice_status') {
            global $wpdb;

            $job_info = $wpdb->get_row("SELECT tr.*,ap.* FROM {$wpdb->prefix}easy_rents_trips tr, {$wpdb->prefix}easy_rents_applications ap WHERE tr.post_id = ap.post_id AND tr.post_id = {$post_ID} AND tr.job_status = 'inprogress' OR tr.job_status = 'ends' AND ap.status > 1");
            
            if ($job_info) {
                echo $job_info->price . ' tk';
            } else {
                print_r('N/A');
            }
        }

        if ($column_name == 'erdriver_status') {
            global $wpdb;

            $job_info = $wpdb->get_row("SELECT tr.*,ap.* FROM {$wpdb->prefix}easy_rents_trips tr, {$wpdb->prefix}easy_rents_applications ap WHERE tr.post_id = {$post_ID} AND tr.job_status = 'inprogress' OR tr.job_status = 'ends' AND ap.status > 1");

            if ($job_info) {
                $driver_id = $job_info->driver_id;
                
                if (!$driver_id) {
                    print_r('N/A');
                }else{
                    $drivername = get_user_by('id', $driver_id)->display_name;
                    echo '<span style="text-transform:capitalize;" class="drivername">' . $drivername . '</sapan>';

                    if ($job_info->net_price > 0) {
                        $paybill = $job_info->price - $job_info->net_price;
                    } else {
                        $comm = 100 + $job_info->commrate;
                        $commbill = $job_info->price / $comm * $job_info->commrate;
                        $paybill = round($commbill);
                    }

                    
                    echo '<span style="color:#0073aa" class="payment"><br>' . $job_info->commrate . '% (' . $paybill . ' tk)';
                    
                    if($job_info->job_status == 'ends'){
                        if ($job_info->payment == 1) {
                            echo '<span title="Paid"> ☑<span>';
                        } else {
                            echo '<span title="Unpaid"> ⛔<span>';
                        }
                    }
                    
                    echo '</sapan>';
                }
            } else {
                print_r('N/A');
            }
        }

        if ($column_name == 'erpost_status') {
            global $wpdb;
            
            $job_info = $wpdb->get_row("SELECT tr.*,ap.* FROM {$wpdb->prefix}easy_rents_trips tr, {$wpdb->prefix}easy_rents_applications ap WHERE tr.post_id = ap.post_id AND tr.post_id = {$post_ID} ORDER BY ap.ID ASC");

            if ( $job_info->job_status == 'running' && $job_info->status == 0) {
                echo '<span title="New" class="status_circle" style="background-color:#cccccc"></span>';
            }
            if ( $job_info->job_status == 'running' && $job_info->status == 1) {
                echo '<span title="Pending" class="status_circle" style="background-color:#0280d2"></span>';
            }
            if ( $job_info->job_status == 'inprogress' && $job_info->status >= 2) {
                echo '<span title="Inprogress" class="status_circle" style="background-color:#13d202"></span>';
            }
            if ( $job_info->job_status == 'ends' && $job_info->status == 3) {
                echo '<span title="End" class="status_circle" style="background-color:gray"></span>';
            }
        }
    }

    // Add taxonomy field
    public function add_term_image($taxonomy)
    {?>
		<div class="form-field term-group">
			<label for="txt_upload_image">Upload , image</label>
			<input type="text" name="txt_upload_image" id="txt_upload_image" value="" style="width: 77%">
			<input type="button" id="upload_image_btn" class="button" value="upload image" />
		</div>
	<?php
}

    // Edit taxonomy field
    public function edit_image_upload($term)
    {?>
		<div class="form-field term-group">
			<label for="">Upload image</label>
			<input type="text" name="txt_upload_image" id="txt_upload_image" value="<?php echo get_term_meta($term->term_id, 'term_image', true) ?>" style="width: 77%">
			<input type="button" id="upload_image_btn" class="button" value="upload image" />
		</div>
	<?php
}

    // Save taxonomy
    public function save_term_image($term_id)
    {
        if (isset($_POST['txt_upload_image']) && $_POST['txt_upload_image'] != '') {
            $group = sanitize_text_field($_POST['txt_upload_image']);
            add_term_meta($term_id, 'term_image', $group);
        }
    }

    // update taxonomy
    public function update_image_upload($term_id)
    {
        if (isset($_POST['txt_upload_image']) && $_POST['txt_upload_image'] != '') {
            $group = sanitize_text_field($_POST['txt_upload_image']);
            update_term_meta($term_id, 'term_image', $group);
        }
    }

    /*
     * Add script
     * @since 1.0.0
     */
    public function load_media()
    {
        wp_enqueue_media();
    }
    public function add_script()
    {
        if (isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'truckstype') {
            wp_enqueue_script('trucktype_media');
            wp_localize_script('trucktype_media', 'meta_image',
                array(
                    'title' => 'Upload an Image',
                    'button' => 'Use this Image',
                )
            );
        }
    }

    // Send sms to driver for payment
    public function send_sms_forpayment()
    {
        global $current_user;
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Hey! What are you doing?');
        }

        if (isset($_POST['driver_id']) && isset($_POST['amount'])) {
            $driver_id = intval($_POST['driver_id']);
            $amount = intval($_POST['amount']);

            if (get_user_meta($driver_id, 'user_phone_number', true)) {
                $to = get_user_meta($driver_id, 'user_phone_number', true);
                $dname = get_user_by("id", $driver_id)->display_name;
                $texts = esc_html(str_replace('%s', ucfirst($dname), get_option('paymentrequestmsg')));

                date_default_timezone_set('Asia/Dhaka');
                $secret = get_option('er_smstoken');

                $message = [
                  "phone" => $to,
                  "message" => "$texts",
                  "secret" => $secret,
                  "receive_date" => date('m/d/Y h:i:s a', time())
                ];
                
                $this->message_to_user($message);
                die;

            } else {
                mail(get_user_by("id", $current_user->ID)->user_email, 'Message faild!', 'Hi Admin, This Driver haven\'t any phone number!');
                wp_die();
            }
            die;
        }
    }

    // Adding location
    public function addNewLocation()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Hey! What are you doing?');
        }

        if (isset($_POST['division']) && isset($_POST['district']) && isset($_POST['p_station'])) {
            $division = sanitize_text_field($_POST['division']);
            $district = sanitize_text_field($_POST['district']);
            $p_station = sanitize_text_field($_POST['p_station']);

            global $wpdb;
            $tbl = $wpdb->prefix . 'easy_rents_prelocations';

            $lochas = $wpdb->get_var("SELECT ID FROM $tbl WHERE `division` = '$division' AND `district` = '$district' AND `p_station` = '$p_station'");

            if ($lochas) {
                echo json_encode(array('faild' => 'This Location already Exist!'));
                die;
            }

            $location = $wpdb->insert($tbl, array('division' => $division, 'district' => $district, 'p_station' => $p_station), array('%s', '%s', '%s'));

            if ($location) {
                echo json_encode(array('success' => 'Added Success!'));
                die;
            } else {
                echo json_encode(array('faild' => 'Try again!'));
                die;
            }
        }
    }

    // Get districts by ajax
    public function get_districts_under_division()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Hey! What are you doing?');
        }

        if (isset($_POST['division'])) {
            $division = sanitize_text_field($_POST['division']);

            global $wpdb;

            $districts = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}easy_rents_prelocations WHERE division = '$division' ORDER BY ID DESC");
            if ($districts) {
                echo json_encode($districts);
                die;
            }
            die;
        }
    }

    // Get p_stations by ajax
    public function get_p_stations_under_districts()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Hey! What are you doing?');
        }

        if (isset($_POST['district'])) {
            $district = sanitize_text_field($_POST['district']);

            global $wpdb;

            $p_stations = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}easy_rents_prelocations WHERE district = '$district' ORDER BY ID DESC");

            if ($p_stations) {
                echo json_encode($p_stations);
                die;
            }
        }
    }

    // Get able data by ajax
    public function get_all_table_data_for_refresh()
    {
        global $wpdb;

        $locations = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}easy_rents_prelocations ORDER BY ID DESC");

        if ($locations) {
            $i = 1;
            foreach ($locations as $location) {
                ?>
				<tr>
					<td class="slnum"><?php echo __($i, 'easy-rents'); ?></td>
					<td><?php echo __($location->division, 'easy-rents'); ?></td>
					<td><?php echo __($location->district, 'easy-rents'); ?></td>
					<td><?php echo __($location->p_station, 'easy-rents'); ?></td>
					<td>
						<button data-id="<?php echo esc_html($location->ID); ?>" name="delete_addr" class="delete_addr">X</button>
					</td>
				</tr>
				<?php
				$i++;
            }
        }
    }

    // Location table default values
    public function erLocationsList($filter = '')
    {
        global $wpdb;

        if ($filter != '') {
            $locations = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}easy_rents_prelocations WHERE division = '$filter' ORDER BY ID DESC");
        } else {
            $locations = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}easy_rents_prelocations ORDER BY ID DESC");
        }

        if ($locations) {
            $i = 1;
            foreach ($locations as $location) {
                ?>
				<tr>
					<td class="slnum"><?php echo __($i, 'easy-rents'); ?></td>
					<td><?php echo __($location->division, 'easy-rents'); ?></td>
					<td><?php echo __($location->district, 'easy-rents'); ?></td>
					<td><?php echo __($location->p_station, 'easy-rents'); ?></td>
					<td>
						<button data-id="<?php echo esc_html($location->ID); ?>" name="delete_addr" class="delete_addr">X</button>
					</td>
				</tr>
				<?php
                $i++;
            }
        }
    }

    // Delete location
    public function delete_easy_rents_location()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Hey! What are you doing?');
        }

        if (isset($_POST['addId'])) {
            $id = intval($_POST['addId']);
            global $wpdb, $wp_query;
            $lochas = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}easy_rents_prelocations WHERE `ID` = $id");

            if ($lochas) {
                if ($wpdb->query("DELETE FROM {$wpdb->prefix}easy_rents_prelocations WHERE ID = $id")) {
                    echo 'Delete Success!';
                    die;
                } else {
                    echo 'Try again!';
                    die;
                }
            } else {
                die;
            }
        }
    }

}
