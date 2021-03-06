<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       example.com
 * @since      1.0.0
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/public
 * @author     Junayed <devjoo.contact@gmail.com>
 */
class Easy_Rents_Public
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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name = '', $version = '')
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        // easy rents add filters
        $this->er_add_fileters();
    }

    // easy rents add filters
    public function er_add_fileters()
    {
        // webclass Load page template
        add_filter('theme_page_templates', array($this, 'easy_rents_templates'));
        add_filter('template_include', array($this, 'wp_page_attributes'));
        // webclass archive page include for projects
        add_filter('template_include', array($this, 'projects_template'));

        // Profile page
        add_shortcode('er_profile', array($this, 'er_profile_page'));
        // Profile page
        add_shortcode('er_profile_trips', array($this, 'er_profile_trips'));
        // Profile page
        add_shortcode('er_payment', array($this, 'er_payment_page'));
        // Profile page
        add_shortcode('er_profile_settings', array($this, 'er_profile_settings'));
        // er_login_register
        add_shortcode('er_access_form', array($this, 'er_login_register'));
        // addjob_form
        add_shortcode('addjob_form', array($this, 'er_addjob_form'));
        // my_trucks_list
        add_shortcode('my_trucks_list', array($this, 'my_trucks_list'));

    }

    //Disabled adminbar
    public function er_admin_bar_showing()
    {
        global $current_user;
        if (!current_user_can('administrator') && !is_admin()) {
            show_admin_bar(false);
        }
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        // Profile style
        wp_register_style('er_profile_style', plugin_dir_url(__FILE__) . 'css/easy-rents-profile.css', array(), microtime(), 'all');
        // addnewjob style
        wp_register_style('er_jobs_style', plugin_dir_url(__FILE__) . 'css/easy-rents-jobs.css', array(), microtime(), 'all');
        // er_login_register
        wp_register_style('er_login_register', plugin_dir_url(__FILE__) . 'css/er_login_register.css', array(), microtime(), 'all');
        // er_jequery uri
        wp_register_style('jquery-ui', plugin_dir_url(__FILE__) . 'css/jquery-ui.css', array(), $this->version, 'all');
        // select2.min
        wp_register_style('select2.min', plugin_dir_url(__FILE__) . 'css/select2.min.css', array(), $this->version, 'all');
        // jquery.datetimepicker.min
        wp_register_style('jquery.timepicker.min', plugin_dir_url(__FILE__) . 'css/jquery.timepicker.min.css', array(), $this->version, 'all');
        // addnewjob style
        wp_register_style('er_addjob_style', plugin_dir_url(__FILE__) . 'css/easy-rents-addjob.css', array(), microtime(), 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        // Profile script
        wp_register_script('jquery.form.min', plugin_dir_url(__FILE__) . 'js/jquery.form.min.js', array('jquery'), $this->version, true);

        wp_register_script('er_profile_script', plugin_dir_url(__FILE__) . 'js/easy-rents-profile.js', array('jquery'), microtime(), true);
        // addjob script
        wp_register_script('er_jobs_script', plugin_dir_url(__FILE__) . 'js/easy-rents-jobs.js', array('jquery'), microtime(), true);
        // er_login_register
        wp_register_script('er_login_register', plugin_dir_url(__FILE__) . 'js/er_login_register.js', array('jquery'), microtime(), true);
        // jquery-ui
        wp_register_script('jquery-ui', plugin_dir_url(__FILE__) . 'js/jquery-ui.js', array('jquery'), $this->version, true);
        // select2.min
        wp_register_script('select2.min', plugin_dir_url(__FILE__) . 'js/select2.min.js', array('jquery'), $this->version, true);
        // er_login_register
        wp_register_script('jquery.timepicker.min', plugin_dir_url(__FILE__) . 'js/jquery.timepicker.min.js', array('jquery'), $this->version, true);
        // addjob script
        wp_register_script('er_addjob_script', plugin_dir_url(__FILE__) . 'js/easy-rents-addjob.js', array('jquery'), microtime(), true);

    }

    // Refer code check
    function er_refer_code_check(){
        global $wpdb;
        if(isset($_POST['code'])){
            $code = $_POST['code'];

            $results = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}easy_rents_refer_codes WHERE refer_code = '$code'");

            if($results){
                echo wp_json_encode( array('success' => "Success") );
                die;
            }else{
                echo wp_json_encode( array('error' => "Invalid Refer Code!") );
                die;
            }
        }
        
        die;
    }

    // User info updates
    public function update_user_info(){
        check_ajax_referer('ajax-nonce', 'nonce');

        global $current_user;
        $user_id = $current_user->ID;

        $user_name = $_POST['uname'];
        $avatar = $_FILES['avatar'];
        $email = $_POST['email'];
        $ubkash = $_POST['ubkash'];
        $nidnumber = $_POST['nidnumber'];
        $nidfront = $_FILES['nidfront'];
        $nidback = $_FILES['nidback'];
        $refer_code = $_POST['refer_code'];

        $presentAddrs = $_POST['presentAddrs'];
        $permanentAddrs = $_POST['permanentAddrs'];
        $billingAddrs = $_POST['billingAddrs'];

        $user_data = wp_update_user( array( 'ID' => $user_id, 'user_email' => $email, 'display_name' => ucfirst($user_name) ) );

        if(!empty($avatar)){
            $user_data = $this->er_upload_user_files($avatar,'user_avatar');
        }
        if(!empty($nidfront)){
            $user_data = $this->er_upload_user_files($nidfront,'user_nid_front');
        }
        if(!empty($nidback)){
            $user_data = $this->er_upload_user_files($nidback,'user_nid_back');
        }

        if(!empty($refer_code)){
            $user_data = update_user_meta( $user_id,'refer_code', $refer_code);
        }

        $user_data = update_user_meta( $user_id, 'bkash_number', $ubkash );
        $user_data = update_user_meta( $user_id, 'nid_number', $nidnumber );

        $allow_presentAddrs = true;
        foreach($presentAddrs as $add){
            if($add == ''){
                $allow_presentAddrs = false;
            }
        }
        if( $allow_presentAddrs == true ){
            $user_data = update_user_meta( $user_id, 'present__addr', $presentAddrs );
        }

        $allow_permanentAddrs = true;
        foreach($permanentAddrs as $add){
            if($add == ''){
                $allow_permanentAddrs = false;
            }
        }
        if( $allow_permanentAddrs == true ){
            $user_data = update_user_meta( $user_id, 'permanent__addr', $permanentAddrs );
        }

        $allow_billingAddrs = true;
        foreach($billingAddrs as $add){
            if($add == ''){
                $allow_billingAddrs = false;
            }
        }
        if( $allow_billingAddrs ){
            $user_data = update_user_meta( $user_id, 'billing__addr', $billingAddrs );
        }

        if ( is_wp_error( $user_data ) ) {
            // There was an error; possibly this user doesn't exist.
            echo wp_json_encode( array('error' => 'Error.') );
            die;
        } else {
            // Success!
            echo wp_json_encode( array('success' => 'User profile updated.') );
            die;
        }
        
        die;
    }

    // upload_avatar
    function er_upload_user_files($file, $name){
        global $current_user;

        if(!empty($file)){

            $upload_dir   = wp_upload_dir();
        
            $original_file = $file['name'];
            $extens = strtolower(pathinfo($original_file,PATHINFO_EXTENSION));

            $fileName = $current_user->user_login.'_'.$name;

            if ( ! empty( $upload_dir['path'] ) ) {
                $file_path = $upload_dir['path'].'/easy-rents/'.$current_user->user_login;
                if ( ! file_exists( $file_path ) ) {
                    wp_mkdir_p( $file_path );
                }
        
                $temp_name = $file['tmp_name'];
                $targetPath = $file_path.'/'.$fileName.'.'.$extens;

                if(is_uploaded_file($temp_name)) {
                    if(move_uploaded_file($temp_name,$targetPath)) {
                        $file_url = $upload_dir['url'].'/easy-rents/'.$current_user->user_login.'/'.$fileName.'.'.$extens;

                        // Check nid_front current nid_front has or not
                        $check_has = get_user_meta($current_user->ID, $name, true);
                        
                        if(!empty($check_has) || $check_has != 0){
                            update_user_meta($current_user->ID, $name, $file_url);

                            return $result = wp_json_encode(array("success" =>"updated"));
                        }else{
                            add_user_meta($current_user->ID, $name, $file_url);
                            
                            return $result = wp_json_encode(array("success" =>"inserted"));
                        }
                    }
                }
            }
        }else{
            return $result = wp_json_encode(array("error" =>"Select your photo!"));
        }
    }

    static public function er_prelocation_input($id,$placeholder, $classes = '', $required = ''){
    ?>
		<div class="input-group locationgroup">
			<select <?php echo $required; ?> style="height:50px" class="erdivision <?php echo $classes; ?>">
				<option value="-1">বিভাগ</option>
                <?php
                    global $wpdb;

                    $divisions = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}easy_rents_prelocations GROUP BY `division` ORDER BY ID DESC");
                    if($divisions){
                        foreach($divisions as $division){
                            echo '<option value="'.esc_html($division->division).'">'.esc_html($division->division).'</option>';
                        }
                    }
                ?>
			</select>
			
			<select <?php echo $required; ?> style="height:50px" class="erdistrict <?php echo $classes; ?>">
				<option value="-1">জেলা</option>
			</select>

			<select <?php echo $required; ?> style="height:50px" class="erp_station <?php echo $classes; ?>">
				<option value="-1">থানা</option>
			</select>
            
			<input <?php echo $required; ?> class='locationinput <?php echo $classes; ?>' id="<?php echo $id; ?>" type="text" Placeholder="<?php echo $placeholder; ?>" value="">
			
		</div>
	<?php
	}


    // Get districts by ajax
	function pbget_districts_under_division(){
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
			die ( 'Hey! What are you doing?');
		}

		if(isset($_POST['division'])){
			$division = sanitize_text_field( $_POST['division'] );
		
			global $wpdb;

			$districts = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}easy_rents_prelocations WHERE division = '$division' GROUP BY `district` ORDER BY ID DESC");
			if($districts){
                
				echo json_encode($districts);
				die;
			}
			die;
		}
	}

	// Get p_stations by ajax
	function pbget_p_stations_under_districts(){
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
			die ( 'Hey! What are you doing?');
		}

		if(isset($_POST['district'])){
			$district = sanitize_text_field( $_POST['district'] );
		
			global $wpdb;

			$p_stations = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}easy_rents_prelocations WHERE district = '$district' GROUP BY `p_station` ORDER BY ID DESC");

			if($p_stations){
				echo json_encode($p_stations);
				die;
			}
		}
	}

    // Create job
    function er_create_job(){
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
			die ( 'Hey! What are you doing?');
		}

        if (isset($_POST['loc1']) && isset($_POST['unload']) && isset($_POST['loading_time']) && isset($_POST['loading_date']) && isset($_POST['truck_type']) && isset($_POST['goods_type']) && isset($_POST['goods_weight'])) {
            
            global $wpdb,$post,$current_user;
            if (!is_user_logged_in() && !$this->er_role_check(['customer'])) {
                echo json_encode(array('faild' => "Sorry Only Customer Allowed!"));
                die;
            }
        
            $location_2 = '';
            $location_3 = '';
            $er_goodssizes = '';
    
            if (isset($_POST['loc2']) && $_POST['loc2'] != "-1") {
                $location_2 = sanitize_text_field($_POST['loc2']);
            }
            if (isset($_POST['loc3']) && $_POST['loc3'] != "-1") {
                $location_3 = sanitize_text_field($_POST['loc3']);
            }
            if (isset($_POST['er_goodssizes'])) {
                $er_goodssizes = intval($_POST['er_goodssizes']);
            }
    
            $location_1 = sanitize_text_field($_POST['loc1']);
            $unload_location = sanitize_text_field($_POST['unload']);
            $loading_time = sanitize_text_field($_POST['loading_time']);
            $loading_date = sanitize_text_field($_POST['loading_date']);
            $datetime = $loading_date . ' | ' . $loading_time;
            $truck_type = intval($_POST['truck_type']);
            $goods_type = sanitize_text_field($_POST['goods_type']);
            $goods_weight = sanitize_text_field($_POST['goods_weight']);
            $er_labore = intval($_POST['er_labore']);
    
            if($location_1 != '-1' && $unload_location != '-1' && $loading_time != '' && $loading_date != '' &&$truck_type != '' && $goods_type != '' && $goods_weight != ''){

                $invoice = new Easy_Rents();
                $invoice_nom = $invoice->get_invoice_id($current_user->ID);
                
                // Create post object
                $job_post = array(
                    'post_type' => 'jobs',
                    'post_status' => 'publish',
                    'post_title' => wp_strip_all_tags($invoice_nom),
                    'post_name' => wp_strip_all_tags($invoice_nom),
                    'post_content' => '',
                    'post_author' => $current_user->ID,
                );
        
                $post_id = wp_insert_post($job_post);
                $set_term = wp_set_post_terms($post_id, $truck_type, 'truckstype');
        
                $tbl = $wpdb->prefix.'easy_rents_trips';
                $newtrip = $wpdb->insert($tbl, array('user_id' => $current_user->ID, 'post_id'=> $post_id, 'location_1' => $location_1,'location_2' => $location_2,'location_3' => $location_3, 'unload_loc' => $unload_location, 'goods_type' => $goods_type, 'weight' => $goods_weight, 'laborer' => $er_labore, 'er_goodssizes' => $er_goodssizes, 'load_time' => $datetime, 'job_status' => 'running','create_at' => date('d.m.Y H:i:s', time() + 3 * 60 * 60)), array('%d', '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d','%d', '%s', '%s', '%s'));
                
                if($newtrip){
                    $slug = $this->get_post_slug(get_option('trips_page', true));
                    $redirect_page = home_url('/' . $slug);

                    echo json_encode(array('redirect' => $redirect_page));
                    die;
                }else{
                    echo json_encode(array('faild' => "Something went wrong! Try again"));
                    die;
                }
            }else{
                die;
            }
        }
        die;
    }

    /**
     * Get post slug by id
     */
    static public function get_post_slug($post_id)
    {
        global $wpdb;
        if ($slug = $wpdb->get_var("SELECT post_name FROM {$wpdb->prefix}posts WHERE ID = $post_id")) {
            return $slug;
        } else {
            return '';
        }
    }

    // delete post
    public function job_post_delete($post_id)
    {
        global $wpdb, $wp_query;
        $wpdb->query("DELETE FROM {$wpdb->prefix}easy_rents_trips WHERE post_id = $post_id");
        $wpdb->query("DELETE FROM {$wpdb->prefix}easy_rents_applications WHERE post_id = $post_id");
    }

    // Thanks for this SCRIPT https://stackoverflow.com/a/18602474
    public function time_elapsed_string($secs){
        $bit = array(
            'y' => $secs / 31556926 % 12,
            'w' => $secs / 604800 % 52,
            'd' => $secs / 86400 % 7,
            'h' => $secs / 3600 % 24,
            'm' => $secs / 60 % 60,
            's' => $secs % 60
            );
           
        foreach($bit as $k => $v)
            if($v > 0)$ret[] = $v . $k;
           
        return join(' ', $ret);
    }

    /**
     * Define template name
     */
    public function easy_rents_templates($templates)
    {
        $templates['er_jobs.php'] = 'All jobs';
        $templates['er_addjob.php'] = 'Add job';

        return $templates;
    }

    // Set custom page attributes
    public function wp_page_attributes($template)
    {
        if (get_page_template_slug() === 'er_jobs.php') {

            if ($theme_file = locate_template(array('er_jobs.php'))) {
                $template = $theme_file;
            } else {
                $template = ER_PATH . 'public/partials/er_jobs.php';
            }
        }

        if (get_page_template_slug() === 'er_addjob.php') {

            if ($theme_file = locate_template(array('er_addjob.php'))) {
                $template = $theme_file;
            } else {
                $template = ER_PATH . 'public/partials/er_addjob.php';
            }
        }

        if ($template == '') {
            throw new \Exception('No template found');
        }

        return $template;
    }

    // Define custom post template with single page
    public function projects_template($template)
    {

        // For custom archive page
        if (is_post_type_archive('jobs')) {
            $theme_files = array('er_jobs.php', '/partials/er_jobs.php');
            $exists_in_theme = locate_template($theme_files, false);
            if ($exists_in_theme != '') {
                return $exists_in_theme;
            } else {
                return dirname(__FILE__) . '/partials/er_jobs.php';
            }
        }

        // For custom single post
        if (is_singular('jobs')) {
            $theme_post = array('page-job.php', '/partials/page-job.php');
            $exists_in_theme_pst = locate_template($theme_post, false);
            if ($exists_in_theme_pst != '') {
                return $exists_in_theme_pst;
            } else {
                return dirname(__FILE__) . '/partials/page-job.php';
            }
        }
        return $template;
    }

    // Remove from cart
    public function remove_jobfromcart()
    {
        check_ajax_referer('er_profile', 'security');
        if (isset($_POST['post_id']) && isset($_POST['customer_id'])) {
            $post_id = $_POST['post_id'];
            $customer_id = $_POST['customer_id'];
            global $current_user, $wpdb;

            if ($post_id != "" && $customer_id != "") {
                if (is_user_logged_in() && $this->er_role_check(['driver'])) {
                    if ($wpdb->query("DELETE FROM {$wpdb->prefix}easy_rents_applications WHERE post_id = $post_id AND driver_id = $current_user->ID AND customer_id = $customer_id")) {
                        $redirect_page = $this->get_post_slug(get_option('profile_page', true));
                        echo home_url('/' . $redirect_page);
                        die;
                    }
                }
            }
        }
    }

    // Ignore Request
    public function ignorerequest()
    {
        check_ajax_referer('er_profile', 'security');
        if (isset($_POST['post_id']) && isset($_POST['driver_id'])) {
            $post_id = $_POST['post_id'];
            $driver_id = $_POST['driver_id'];
            global $current_user, $wpdb;

            if ($post_id != "" && $driver_id != "") {
                if (is_user_logged_in() && $this->er_role_check(['customer'])) {
                    if ($wpdb->query("DELETE FROM {$wpdb->prefix}easy_rents_applications WHERE post_id = $post_id AND driver_id = $driver_id AND customer_id = $current_user->ID")) {
                        $redirect_page = $this->get_post_slug(get_option('profile_page', true));
                        echo home_url('/' . $redirect_page);
                        die;
                    }
                }
            }
        }
    }

    // Accept Request
    public function acceptrequest()
    {
        check_ajax_referer('er_profile', 'security');
        if (isset($_POST['post_id']) && isset($_POST['driver_id']) && isset($_POST['offer_id'])) {
            $post_id = $_POST['post_id'];
            $driver_id = $_POST['driver_id'];
            $offer_id = $_POST['offer_id'];
            global $current_user, $wpdb;

            if ($post_id != "" && $driver_id != "") {
                if (is_user_logged_in() && $this->er_role_check(['customer'])) {
                    $redirect_page = $this->get_post_slug(get_option('profile_page', true));
                    $tm = time();
                    if ($wpdb->query("UPDATE {$wpdb->prefix}easy_rents_applications SET status = 2, apply_date = $tm WHERE driver_id = $driver_id AND customer_id = $current_user->ID AND ID = $offer_id")) {

                        if ($wpdb->query("UPDATE {$wpdb->prefix}easy_rents_trips SET job_status = 'inprogress' WHERE post_id = $post_id")) {

                            $wpdb->query("DELETE FROM {$wpdb->prefix}easy_rents_applications WHERE  customer_id = $current_user->ID AND post_id = $post_id AND status = 1");

                            $user = get_user_by( 'id', $driver_id );

                            if ($user) {
                                $to = $user->user_login;
                                $dname = $user->display_name;
                                $texts = str_replace('%s', $dname, get_option('acceptjobmsg'));
                                date_default_timezone_set('Asia/Dhaka');
                                $secret = get_option('er_smstoken');

                                $message = [
                                    "phone" => $to,
                                    "message" => "$texts",
                                    "secret" => $secret,
                                    "receive_date" => date('m/d/Y h:i:s a', time())
                                ];

                                if(Easy_Rents_Admin::message_to_user($message)){
                                    echo " ";
                                    wp_die();
                                }
                            }

                            die;
                        }
                    }
                }
            }
        }
    }

    // Request for finished
    public function requestforfinishedjob()
    {
        if (isset($_POST['post_id']) && isset($_POST['customer_id']) && isset($_POST['offer_id'])) {
            $post_id = $_POST['post_id'];
            $customer_id = $_POST['customer_id'];
            $offer_id = $_POST['offer_id'];
            global $current_user, $wpdb;

            if (is_user_logged_in() && $this->er_role_check(['driver'])) {
                if ($wpdb->query("UPDATE {$wpdb->prefix}easy_rents_applications SET status = 4  WHERE driver_id = $current_user->ID AND customer_id = $customer_id AND ID = $offer_id")) {
                    echo " ";
                    wp_die();
                }
            }
            die;
        }
    }
    // Request confirmed
    public function finishedconfirmed()
    {
        if (isset($_POST['post_id']) && isset($_POST['driver_id']) && isset($_POST['offer_id'])) {
            $post_id = $_POST['post_id'];
            $driver_id = $_POST['driver_id'];
            $offer_id = $_POST['offer_id'];
            global $current_user, $wpdb;

            if (is_user_logged_in() && $this->er_role_check(['customer'])) {
                if ($wpdb->query("UPDATE {$wpdb->prefix}easy_rents_applications SET status = 3, finished_date = now() WHERE customer_id = $current_user->ID AND driver_id = $driver_id AND ID = $offer_id")) {

                    if ($wpdb->query("UPDATE {$wpdb->prefix}easy_rents_trips SET job_status = 'ends' WHERE post_id = $post_id")) {
                        $user = get_user_by( 'id', $driver_id );
                        // SENT SMS TO DRIVER
                        if ($user) {
                            $to = $user->user_login;
                            $dname = $user->display_name;
                            $texts = str_replace('%s', $dname, get_option('jobconfirmationmsg'));
                            $secret = get_option('er_smstoken');

                            $message = [
                                "phone" => $to,
                                "message" => "$texts",
                                "secret" => $secret,
                                "receive_date" => date('m/d/Y h:i:s a', time())
                            ];

                            if(Easy_Rents_Admin::message_to_user($message)){
                                echo " ";
                                wp_die();
                            }
                        }
                        echo " ";
                        wp_die();
                    }
                }
            }
            die;
        }
    }

    /**
     * Webclass multiple role check
     */
    static public function er_role_check($roles = array())
    {
        global $current_user;
        $allowed_user = $roles;
        if (array_intersect($allowed_user, $current_user->roles)) {
            // return true
            return true;
        } else {
            return false;
        }
    }

    // Profile page
    public function er_profile_page($atts)
    {
        if(current_user_can( 'administrator' )){
            print_r("Sorry as a admin you can't see this page!");
            die;
        }
        if (is_user_logged_in() && $this->er_role_check(['customer', 'driver'])) {
            require_once plugin_dir_path(__FILE__) . 'partials/shortcodes/er_dashboard.php';
        } else {
            wp_redirect( home_url('/' . $this->get_post_slug(get_option('access_page', true))) );
        }
    }

    // er_profile_trips
    public function er_profile_trips($atts)
    {
        if(current_user_can( 'administrator' )){
            print_r("Sorry as a admin you can't see this page!");
            die;
        }
        if (is_user_logged_in() && $this->er_role_check(['customer', 'driver'])) {
            require_once plugin_dir_path(__FILE__) . 'partials/shortcodes/er_mytrips.php';
        } else {
            wp_redirect( home_url('/' . $this->get_post_slug(get_option('access_page', true))) );
        }
    }

    // er_payment_page
    public function er_payment_page($atts)
    {
        if(current_user_can( 'administrator' )){
            print_r("Sorry as a admin you can't see this page!");
            die;
        }
        if (is_user_logged_in() && $this->er_role_check(['customer', 'driver'])) {
            require_once plugin_dir_path(__FILE__) . 'partials/shortcodes/er_payment.php';
        } else {
            wp_redirect( home_url('/' . $this->get_post_slug(get_option('access_page', true))) );
        }
    }

    // er_profile_settings
    public function er_profile_settings($atts)
    {
        if(current_user_can( 'administrator' )){
            print_r("Sorry as a admin you can't see this page!");
            die;
        }
        if (is_user_logged_in() && $this->er_role_check(['customer', 'driver'])) {
            require_once plugin_dir_path(__FILE__) . 'partials/shortcodes/er_profile_settings.php';
        } else {
            wp_redirect( home_url('/' . $this->get_post_slug(get_option('access_page', true))) );
        }
    }

    // er_register / login page
    public function er_login_register($atts)
    {
        if (!is_user_logged_in()) {
            require_once plugin_dir_path(__FILE__) . 'partials/shortcodes/er_login_register.php';
        } else {
            if(current_user_can( 'administrator' )){
                print_r("Sorry as a admin you can't see this page!");
                die;
            }
            wp_safe_redirect(home_url('/' . $this->get_post_slug(get_option('profile_page', true))));
            exit();
        }
    }
    // er_register / login page
    public function er_addjob_form($atts)
    {
        if (is_user_logged_in() && $this->er_role_check(['customer', 'driver'])) {
            require_once plugin_dir_path(__FILE__) . 'partials/shortcodes/er_add_job_shorcode.php';
        }
    }
    // er_register / login page
    public function er_my_trucks_list($atts)
    {
        if (is_user_logged_in() && $this->er_role_check(['driver'])) {
            require_once plugin_dir_path(__FILE__) . 'partials/shortcodes/er_my_trucks_list.php';
        }
    }

}