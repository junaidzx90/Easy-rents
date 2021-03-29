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
class Easy_Rents_Admin {

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// ONLY MOVIE CUSTOM TYPE POSTS
		add_filter('manage_jobs_posts_columns', array($this, 'wp_list_table_columnname'));

		// Set custom column in job table
		if($_GET['post_type'] == 'jobs'){
			$this->jobs_list_table_css();
		}
	}
	

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_register_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css', array(), $this->version, 'all' );

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/easy-rents-admin.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'er_bubble_notify', plugin_dir_url( __FILE__ ) . 'css/er_bubble_notify.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script( 'jquery-ui', plugin_dir_url( __FILE__ ) . 'js/jquery-ui.js', array( 'jquery' ), $this->version, false );

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/easy-rents-admin.js', array( 'jquery' ), $this->version, false );

		wp_register_script( 'trucktype_media', plugin_dir_url( __FILE__ ) . 'js/media-uploader.js', array( 'jquery' ), $this->version, true );

	}

	// General settings
	function easy_rents_setup(){
		if(is_admin(  )){
			global $wpdb,$wp_query;
			$billpay = $wpdb->query("SELECT COUNT(payment) FROM {$wpdb->prefix}easy_rents_applications WHERE payment = 1");
			$bubble = sprintf(
				' <span class="paymentstatus"><span class="count">%d</span></span>',
				42 //bubble contents
			);

			add_menu_page( //Main menu register
				"Easy Rents", //page_title
				"Easy Rents".$bubble, //menu title
				"manage_options", //capability
				"er-settings", //menu_slug
				array($this,"er_settings_cb"), //callback function
				"",
				65
			);

			add_submenu_page( "er-settings", "Settings", "Settings", "manage_options", "er-settings", array($this,"er_settings_cb")
			);
			
			add_submenu_page( 'er-settings', 'Payment', 'Payment'.$bubble, 'manage_options', 'payment', array($this,'er_payment_confirm'));

			add_submenu_page( 'er-settings', 'Locations', 'Locations', 'manage_options', 'locations', array($this,'er_locations_lists'));
		}
	}

	// Message to user
	function message_to_user($toval,$messageval){
		if(get_option( 'er_smstoken' )){
			$to = '+88'.$toval;
			$token = get_option( 'er_smstoken' );
			$message = $messageval;

			$url = "http://api.greenweb.com.bd/api.php?json";

			$data= array(
				'to'=>"$to",
				'message'=>"$message",
				'token'=>"$token"
			); // Add parameters in key value
			$ch = curl_init(); // Initialize cURL
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_ENCODING, '');
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			if(curl_exec($ch)){
				//Result
				return $smsresult;
			}else{
				//Error Display
				return curl_error($ch);
			}
		}
	}

	// Register settings
	function er_page_settings_register(){
		add_settings_section( 'er_settings_section', 'Easy Rents Settings', '', 'er-settings' );

		// Add new trip page
		add_settings_field( 'add_trip_page', 'Add trip page', array($this,'er_add_trip_page_cb'), 'er-settings', 'er_settings_section');
		register_setting( 'er_settings_section', 'add_trip_page');

		// Profile page
		add_settings_field( 'profile_page', 'Profile page', array($this,'er_profile_page_cb'), 'er-settings', 'er_settings_section');
		register_setting( 'er_settings_section', 'profile_page');

		// Profile trips
		add_settings_field( 'profile_trips', 'Profile Trips', array($this,'er_profile_trips_cb'), 'er-settings', 'er_settings_section');
		register_setting( 'er_settings_section', 'profile_trips');

		// Profile payment
		add_settings_field( 'profile_payment', 'Payments', array($this,'er_profile_payment_cb'), 'er-settings', 'er_settings_section');
		register_setting( 'er_settings_section', 'profile_payment');

		// Profile settings
		add_settings_field( 'erprofile_settings', 'Settings', array($this,'er_profile_settings_cb'), 'er-settings', 'er_settings_section');
		register_setting( 'er_settings_section', 'erprofile_settings');

		// Set commission %
		add_settings_field( 'job_commission', 'Commissions Percents', array($this,'er_job_commission_cb'), 'er-settings', 'er_settings_section');
		register_setting( 'er_settings_section', 'job_commission');

		// Set sms token
		add_settings_field( 'er_smstoken', 'Token Number', array($this,'er_smstoken_cb'), 'er-settings', 'er_settings_section');
		register_setting( 'er_settings_section', 'er_smstoken');

		// Set finished job confirmation
		add_settings_field( 'jobconfirmationmsg', 'Trip Confirmation Message', array($this,'er_jobconfirmation_cb'), 'er-settings', 'er_settings_section');
		register_setting( 'er_settings_section', 'jobconfirmationmsg');

		// Set accept job msg
		add_settings_field( 'acceptjobmsg', 'Trip Accept Message', array($this,'er_acceptjobmsg_cb'), 'er-settings', 'er_settings_section');
		register_setting( 'er_settings_section', 'acceptjobmsg');

		// Payment request message
		add_settings_field( 'paymentrequestmsg', 'Payment Request Message', array($this,'er_paymentrequestmsg_cb'), 'er-settings', 'er_settings_section');
		register_setting( 'er_settings_section', 'paymentrequestmsg');
	}

	//Disabled wp backend access
	function disable_backend_access(){
		global $current_user;
		$redirect = home_url( '/' );
		if( is_admin() && !defined('DOING_AJAX') && ( !current_user_can('administrator'))){
			wp_redirect(home_url());
			exit;
		}
	}

	/**
	 * After login redirect user
	 */
	function er_login_redirects( $url, $request, $user ) {
		 //is there a user to check?
		 if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			//check for admins
			if ( in_array( 'administrator', $user->roles ) ) {
				// redirect them to the default place
				return admin_url();
			} else {
				return home_url('/'.Easy_Rents_Public::get_post_slug(get_option( 'profile_page', true )));
			}
		} else {
			return admin_url();
		}
	}

	// Add new trip page calback
	function er_add_trip_page_cb(){
		echo '<select name="add_trip_page">';
		if(get_option('add_trip_page') != ""){
			$page = get_post( intval(get_option( 'add_trip_page' )) )->post_title;
			echo '<option value="'.intval(get_option('add_trip_page')).'" selected>';
			echo	__($page,'easy-rents');
			echo '</option>';
		}else{
			echo '<option selected> Select a page </option>';
		}

		$posts = get_posts(['post_type' => 'page','post_status' => 'publish']);
		if($posts){
			foreach($posts as $post){
				echo '<option value="'.intval( $post->ID).'">';
				echo __($post->post_title, 'easy-rents');
				echo '</option>';
			}
		}
		echo '</select><br>';
	}

	// Profile page callback
	function er_profile_page_cb(){
		echo '<select name="profile_page">';
		if(get_option('profile_page') != ""){
			$page = get_post( intval(get_option( 'profile_page' )) )->post_title;
			echo '<option value="'.intval(get_option('profile_page')).'" selected>';
			echo	__($page,'easy-rents');
			echo '</option>';
		}else{
			echo '<option selected> Select a page </option>';
		}

		$posts = get_posts(['post_type' => 'page','post_status' => 'publish']);
		if($posts){
			foreach($posts as $post){
				echo '<option value="'.intval($post->ID).'">';
				echo __($post->post_title, 'easy-rents');
				echo '</option>';
			}
		}
		echo '</select><br>';
	}

	// Profile trips page callback
	function er_profile_trips_cb(){
		echo '<select name="profile_trips">';
		if(get_option('profile_trips') != ""){
			$page = get_post( intval(get_option( 'profile_trips' )) )->post_title;
			echo '<option value="'.intval(get_option('profile_trips')).'" selected>';
			echo	__($page,'easy-rents');
			echo '</option>';
		}else{
			echo '<option selected> Select a page </option>';
		}

		$posts = get_posts(['post_type' => 'page','post_status' => 'publish']);
		if($posts){
			foreach($posts as $post){
				echo '<option value="'.intval($post->ID).'">';
				echo __($post->post_title, 'easy-rents');
				echo '</option>';
			}
		}
		echo '</select><br>';
	}

	// Profile payment callback
	function er_profile_payment_cb(){
		echo '<select name="profile_payment">';
		if(get_option('profile_payment') != ""){
			$page = get_post( intval(get_option( 'profile_payment' )) )->post_title;
			echo '<option value="'.intval(get_option('profile_payment')).'" selected>';
			echo	__($page,'easy-rents');
			echo '</option>';
		}else{
			echo '<option selected> Select a page </option>';
		}

		$posts = get_posts(['post_type' => 'page','post_status' => 'publish']);
		if($posts){
			foreach($posts as $post){
				echo '<option value="'.intval($post->ID).'">';
				echo __($post->post_title, 'easy-rents');
				echo '</option>';
			}
		}
		echo '</select><br>';
	}

	// Profile settings
	function er_profile_settings_cb(){
		echo '<select name="erprofile_settings">';
		if(get_option('erprofile_settings') != ""){
			$page = get_post( intval(get_option( 'erprofile_settings' )) )->post_title;
			echo '<option value="'.intval(get_option('erprofile_settings')).'" selected>';
			echo	__($page,'easy-rents');
			echo '</option>';
		}else{
			echo '<option selected> Select a page </option>';
		}

		$posts = get_posts(['post_type' => 'page','post_status' => 'publish']);
		if($posts){
			foreach($posts as $post){
				echo '<option value="'.intval($post->ID).'">';
				echo __($post->post_title, 'easy-rents');
				echo '</option>';
			}
		}
		echo '</select><br>';
	}

	// Profile page callback
	function er_job_commission_cb(){
		$ommision = get_option('job_commission');
		echo '<input style="width:55px" type="number" name="job_commission" value="'.__($ommision,'easy-rents').'" placeholder="0"><br><h3>SMS <hr></h3>';
	}
	// er_smstoken_cb
	function er_smstoken_cb(){
		echo '<input type="password" value="'.get_option( 'er_smstoken' ).'" name="er_smstoken" placeholder="Add Token Number"><br>';
	}
	// er_jobconfirmation_cb
	function er_jobconfirmation_cb(){
		echo '<textarea name="jobconfirmationmsg" type="text" placeholder="Trip Confirmation Message" cols="50" rows="2">'.get_option('jobconfirmationmsg').'</textarea><br>';
	}
	// er_acceptjobmsg_cb
	function er_acceptjobmsg_cb(){
		echo '<textarea name="acceptjobmsg" type="text" placeholder="Trip Accept Message" cols="50" rows="2">'.get_option('acceptjobmsg').'</textarea><br>';
	}
	// er_paymentrequestmsg_cb
	function er_paymentrequestmsg_cb(){
		echo '<textarea name="paymentrequestmsg" type="text" placeholder="Payment Request Message" cols="50" rows="2">'.get_option('paymentrequestmsg').'</textarea>';
	}

	//er_settings_cb
	function er_settings_cb(){
		require_once plugin_dir_path( __FILE__ ).'partials/easy-rents-admin-display.php';
	}
	//er_payment_confirm
	function er_payment_confirm(){
		require_once plugin_dir_path( __FILE__ ).'partials/er_payment_confirm.php';
	}
	//er_locations_lists
	function er_locations_lists(){
		require_once plugin_dir_path( __FILE__ ).'partials/er_locations_lists.php';
	}

	/*
	* Creating a function to create our CPT
	*/
	
	function er_job_post() {
		// Set UI labels for Custom Post Type
		$labels = array(
			'name'                => _x( 'Jobs', 'Post Type General Name', 'easy-rents' ),
			'singular_name'       => _x( 'Job', 'Post Type Singular Name', 'easy-rents' ),
			'menu_name'           => __( 'Jobs', 'easy-rents' ),
			'all_items'           => __( 'All Jobs', 'easy-rents' ),
			'view_item'           => __( 'View Job', 'easy-rents' ),
			'add_new_item'        => __( 'Add New Job', 'easy-rents' ),
			'add_new'             => __( 'Add New', 'easy-rents' ),
			'edit_item'           => __( 'Edit Job', 'easy-rents' ),
			'update_item'         => __( 'Update Job', 'easy-rents' ),
			'search_items'        => __( 'Search Job', 'easy-rents' ),
			'not_found'           => __( 'Not Found', 'easy-rents' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'easy-rents' ),
		);
		 
		// Set other options for Custom Post Type
		 
		$args = array(
			'label'               => __( 'jobs', 'easy-rents' ),
			'description'         => __( 'Job news and reviews', 'easy-rents' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'author' ),
			'taxonomies'          => array( 'jobs' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest' => true,
	 
		);
		 
		// Registering your Custom Post Type
		register_post_type( 'jobs', $args );
	 
	}

	// Job car type taxonomy
	function the_car_type_taxonomy() {
		
		$labels = array(
			'name' => _x( 'Trucks', 'trucks' ),
			'singular_name' => _x( 'Truck', 'truck' ),
			'search_items' =>  __( 'Search trucks' ),
			'all_items' => __( 'All trucks' ),
			'edit_item' => __( 'Edit Truck' ), 
			'update_item' => __( 'Update Truck' ),
			'add_new_item' => __( 'Add New Truck' ),
			'new_item_name' => __( 'New Truck Name' ),
			'menu_name' => __( 'Trucks' ),
		);    
		
		// Now register the truck
		register_taxonomy('truckstype',array('jobs'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_in_rest' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'truck' ),
		));
	}

	// Wp list table css for jobs post
	function jobs_list_table_css(){ ?>
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
	function wp_list_table_columnname($defaults) {
		$defaults['erpricet_status'] = 'Price';
		$defaults['erdriver_status'] = 'Driver/Payment';
		$defaults['erpost_status'] = 'Status';
		return $defaults;
	}
	function wp_list_table_column_view($column_name, $post_ID) {
		if ($column_name == 'erpricet_status') {
			$postinfo = get_post_meta( $post_ID, 'er_job_info' );
			if($postinfo[0]['job_status'] == 'inprogress' || $postinfo[0]['job_status'] == 'ends'){
				global $wpdb;
				$job_price = $wpdb->get_var("SELECT price FROM {$wpdb->prefix}easy_rents_applications WHERE post_id = {$post_ID} AND status = 3");
				if($job_price){
					echo $job_price.' tk';
				}else{
					print_r('N/A');
				}
			}else{
				print_r('N/A');
			}
		}

		if ($column_name == 'erdriver_status') {
			$postinfo = get_post_meta( $post_ID, 'er_job_info' );
			if($postinfo[0]['job_status'] == 'inprogress' || $postinfo[0]['job_status'] == 'ends'){
				global $wpdb;
				$driver_id = $wpdb->get_var("SELECT driver_id FROM {$wpdb->prefix}easy_rents_applications WHERE post_id = {$post_ID}");
				if(!$driver_id){
					print_r('N/A');
				}

				$drivername = get_user_by( 'id', $driver_id )->user_nicename;
				echo '<span style="text-transform:capitalize;" class="drivername">'.$drivername.'</sapan>';
				
				$price = $wpdb->get_var("SELECT price FROM {$wpdb->prefix}easy_rents_applications WHERE post_id = {$post_ID} AND status = 3");
				
				$netprice = $wpdb->get_var("SELECT net_price FROM {$wpdb->prefix}easy_rents_applications WHERE post_id = {$post_ID} AND status = 3");

				$commission = $wpdb->get_var("SELECT commrate FROM {$wpdb->prefix}easy_rents_applications WHERE post_id = {$post_ID} AND status = 3");

				if($netprice > 0){
					$paybill = $price-$netprice;
				}else{
					$comm = 100 + $commission;
					$commbill =  $price/$comm * $commission;
					$paybill = round($commbill);
				}

				if($postinfo[0]['job_status'] == 'ends'){
					echo '<span style="color:#0073aa" class="payment"><br>'.$commission.'% ('.$paybill.' tk)';
					$payment = $wpdb->get_var("SELECT payment FROM {$wpdb->prefix}easy_rents_applications WHERE post_id = {$post_ID}");
					if($payment == 1){
						echo '<span title="Paid"> ☑<span>';
					}else{
						echo '<span title="Unpaid"> ⛔<span>';
					}
				}
				
				echo '</sapan>';
			}else{
				print_r('N/A');
			}
		}

		if ($column_name == 'erpost_status') {
			global $wpdb;
			// show content of 'directors_name' column
			$postinfo = get_post_meta( $post_ID, 'er_job_info' );
			$status_ = $wpdb->get_var("SELECT status FROM {$wpdb->prefix}easy_rents_applications WHERE post_id = {$post_ID}");

			if($postinfo[0]['job_status'] == 'running' && $status_ == 0){
				echo '<span title="New" class="status_circle" style="background-color:#cccccc"></span>';
			}
			if($postinfo[0]['job_status'] == 'running' && $status_ == 1){
				echo '<span title="Pending" class="status_circle" style="background-color:#0280d2"></span>';
			}
			if($postinfo[0]['job_status'] == 'inprogress' && $status_ == 2){
				echo '<span title="Inprogress" class="status_circle" style="background-color:#13d202"></span>';
			}
			if($postinfo[0]['job_status'] == 'ends' && $status_ == 3){
				echo '<span title="End" class="status_circle" style="background-color:gray"></span>';
			}
		}
	}

	// Add taxonomy field
	function add_term_image($taxonomy){ ?>
		<div class="form-field term-group">
			<label for="txt_upload_image">Upload , image</label>
			<input type="text" name="txt_upload_image" id="txt_upload_image" value="" style="width: 77%">
			<input type="button" id="upload_image_btn" class="button" value="upload image" />
		</div>
	<?php 
	}

	// Edit taxonomy field
	function edit_image_upload($term) { ?>
		<div class="form-field term-group">
			<label for="">Upload image</label>
			<input type="text" name="txt_upload_image" id="txt_upload_image" value="<?php echo get_term_meta( $term->term_id, 'term_image', true ) ?>" style="width: 77%">
			<input type="button" id="upload_image_btn" class="button" value="upload image" />
		</div>
	<?php 
	}

	// Save taxonomy
	function save_term_image($term_id) {
		if (isset($_POST['txt_upload_image']) && $_POST['txt_upload_image'] != ''){      
			$group = sanitize_text_field($_POST['txt_upload_image']);
			add_term_meta($term_id, 'term_image', $group);
		} 
	}

	// update taxonomy
	function update_image_upload($term_id) {
		if (isset($_POST['txt_upload_image']) && $_POST['txt_upload_image'] != '' ){
			$group = sanitize_text_field($_POST['txt_upload_image']);
			update_term_meta($term_id, 'term_image', $group);
		} 
	}

	/*
	* Add script
	* @since 1.0.0
	*/
	function load_media(){
		wp_enqueue_media();
	}
	function add_script() {
		if(isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'truckstype'){
			wp_enqueue_script('trucktype_media');
			wp_localize_script( 'trucktype_media', 'meta_image',
				array(
					'title' => 'Upload an Image',
					'button' => 'Use this Image',
				)
			);
		}
	}

	// Send sms to driver for payment
	function send_sms_forpayment(){
		if(isset($_POST['driver_id']) && isset($_POST['amount'])){
			$driver_id = intval($_POST['driver_id']);
			$amount = intval($_POST['amount']);
			
			if(get_user_meta($driver_id, 'user_phone_number', true )){
				$to = get_user_meta($driver_id, 'user_phone_number', true );
				$dname = get_user_by("id",$driver_id)->user_nicename;
				$message = str_replace('%s',$dname, get_option('paymentrequestmsg'));
				
				if(!get_option('paymentrequestmsg')){
					mail('imransepai1@gmail.com','Message faild!','Hi Admin, You haven\'t set any message for sent!');
					wp_die();
				}else{
					// if($this->message_to_user($to, $message)){
					echo "Sent Successfull";
					wp_die();
				// }else{
					// 	mail('imransepai1@gmail.com','Message faild!','Hi Admin, Payment request is not sent, Please try again!');
					// 	wp_die();
					// }
				}
				
			}else{
				mail('imransepai1@gmail.com','Message faild!','Hi Admin, This Driver haven\'t any phone number!');
				wp_die();
			}
			die;
		}
	}
	
}
