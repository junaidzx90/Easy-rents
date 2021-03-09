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

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/easy-rents-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/easy-rents-admin.js', array( 'jquery' ), $this->version, false );

		wp_register_script( 'trucktype_media', plugin_dir_url( __FILE__ ) . 'js/media-uploader.js', array( 'jquery' ), $this->version, true );

	}

	// General settings
	function easy_rents_setup(){
		if(is_admin(  )){
			add_menu_page( //Main menu register
				"ER Settings", //page_title
				"ER Settings", //menu title
				"manage_options", //capability
				"er-settings", //menu_slug
				array($this,"er_settings_cb"), //callback function
				"",
				65
			);
		}
	}

	// Register settings
	function er_page_settings_register(){
		add_settings_section( 'er_settings_section', 'Easy Rents Settings', '', 'er-settings' );
		// All trips page
		add_settings_field( 'trips_page', 'Trips page', array($this,'er_trips_page_cb'), 'er-settings', 'er_settings_section');
		register_setting( 'er_settings_section', 'trips_page');

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
		add_settings_field( 'job_commission', 'Commissions', array($this,'er_job_commission_cb'), 'er-settings', 'er_settings_section');
		register_setting( 'er_settings_section', 'job_commission');

	}

	// All trips page calback
	function er_trips_page_cb(){
		echo '<select name="trips_page">';
		if(get_option('trips_page') != ""){
			$page = get_post( intval(get_option( 'trips_page' )) )->post_title;
			echo '<option value="'.intval(get_option('trips_page')).'" selected>';
			echo	__($page,'easy-rents');
			echo '</option>';
		}else{
			echo '<option selected> Select a page </option>';
		}

		$posts = get_posts(['post_type' => 'page','post_status' => 'published']);
		if($posts){
			foreach($posts as $post){
				echo '<option value="'.intval( $post->ID ).'">';
				echo __($post->post_title, 'easy-rents');
				echo '</option>';
			}
		}
		echo '</select><br><br>';
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

		$posts = get_posts(['post_type' => 'page','post_status' => 'published']);
		if($posts){
			foreach($posts as $post){
				echo '<option value="'.intval( $post->ID).'">';
				echo __($post->post_title, 'easy-rents');
				echo '</option>';
			}
		}
		echo '</select><br><br>';
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

		$posts = get_posts(['post_type' => 'page','post_status' => 'published']);
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

		$posts = get_posts(['post_type' => 'page','post_status' => 'published']);
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

		$posts = get_posts(['post_type' => 'page','post_status' => 'published']);
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

		$posts = get_posts(['post_type' => 'page','post_status' => 'published']);
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
		echo '<input style="width:55px" type="number" name="job_commission" value="'.__($ommision,'easy-rents').'" placeholder="0"> %';
	}

	//webclass general settings
	function er_settings_cb(){
		require_once plugin_dir_path( __FILE__ ).'partials/easy-rents-admin-display.php';
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
			'supports'            => array( 'title', 'author', 'comments' ),
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
		$defaults['erpost_status'] = 'Status';
		return $defaults;
	}
	function wp_list_table_column_view($column_name, $post_ID) {
		if ($column_name == 'erpricet_status') {
			// show content of 'directors_name' column
			echo '2400 tk';
		}
		if ($column_name == 'erpost_status') {
			// show content of 'directors_name' column
			$postinfo = get_post_meta( $post_ID, 'er_job_info' );
			if($postinfo[0]['job_status'] == 'running'){
				echo '<span class="status_circle" style="background-color:#0280d2"></span>';
			}
			if($postinfo[0]['job_status'] == 'inprogress'){
				echo '<span class="status_circle" style="background-color:#13d202"></span>';
			}
			if($postinfo[0]['job_status'] == 'ends'){
				echo '<span class="status_circle" style="background-color:gray"></span>';
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
			<label for="">upload , image</label>
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
	
}
