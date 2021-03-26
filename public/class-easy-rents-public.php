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
class Easy_Rents_Public {

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
	public function __construct( $plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// easy rents add filters
		$this->er_add_fileters();
	}

	// easy rents add filters
	function er_add_fileters(){
		// webclass Load page template
		add_filter ('theme_page_templates', array($this,'webclass_templates'));
		add_filter( 'template_include', array($this,'wp_page_attributes' ));
		// webclass archive page include for projects
		add_filter('template_include', array($this,'projects_template'));

		// Profile page
		add_shortcode( 'er_profile', array($this, 'er_profile_page') );
		// Profile page
		add_shortcode( 'er_profile_trips', array($this, 'er_profile_trips') );
		// Profile page
		add_shortcode( 'er_payment', array($this, 'er_payment_page') );
		// Profile page
		add_shortcode( 'er_profile_settings', array($this, 'er_profile_settings') );
		// er_login_register
		add_shortcode( 'er_access_form', array($this, 'er_login_register') );

	}

	//Disabled adminbar
	function er_admin_bar_showing(){
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
	public function enqueue_styles() {

		// Profile style
		wp_register_style( 'er_profile_style', plugin_dir_url( __FILE__ ) . 'css/easy-rents-profile.css', array(), microtime(), 'all' );
		// addnewjob style
		wp_register_style( 'er_addjob_style', plugin_dir_url( __FILE__ ) . 'css/easy-rents-addjob.css', array(), microtime(), 'all' );
		// addnewjob style
		wp_register_style( 'er_jobs_style', plugin_dir_url( __FILE__ ) . 'css/easy-rents-jobs.css', array(), microtime(), 'all' );
		// select2 style
		wp_register_style( 'select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), microtime(), 'all' );

		// er_login_register
		wp_register_style( 'er_login_register', plugin_dir_url( __FILE__ ) . 'css/er_login_register.css', array(), microtime(), 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// Profile script
		wp_register_script( 'er_profile_script', plugin_dir_url( __FILE__ ) . 'js/easy-rents-profile.js', array( 'jquery' ), $this->version, true );
		
		// addjob script
		wp_register_script( 'er_addjob_script', plugin_dir_url( __FILE__ ) . 'js/easy-rents-addjob.js', array( 'jquery' ), $this->version, true );

		// addjob script
		wp_register_script( 'er_jobs_script', plugin_dir_url( __FILE__ ) . 'js/easy-rents-jobs.js', array( 'jquery' ), microtime(), true );

		// select2 script
		wp_register_script( 'select2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, true );

		// er_login_register
		wp_register_script( 'er_login_register', plugin_dir_url( __FILE__ ) . 'js/er_login_register.js', array( 'jquery' ), $this->version, true );

	}

	/**
	 * Get post slug by id
	 */
	function get_post_slug($post_id) {
		global $wpdb;
		if($slug = $wpdb->get_var("SELECT post_name FROM {$wpdb->prefix}posts WHERE ID = $post_id")) {
			return $slug;
		} else {
			return '';
		}
	}

	// delete post
	function job_post_delete($post_id){
		global $wpdb,$wp_query;
		delete_post_meta( $post_id, 'er_job_info' );
		$wpdb->query("DELETE FROM {$wpdb->prefix}easy_rents_applications WHERE post_id = $post_id");
	}

	// Thanks for this SCRIPT https://stackoverflow.com/a/18602474
	function time_elapsed_string($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
	
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
	
		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'min',
			's' => 'sec',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}
	
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

	/**
	 * Define template name
	 */
	function webclass_templates ($templates) {
		$templates['er_jobs.php'] = 'All jobs';
		$templates['er_addjob.php'] = 'Add job';
		
		return $templates;
	}
	

	// Set custom page attributes
	function wp_page_attributes( $template ) {
		if(  get_page_template_slug() === 'er_jobs.php' ) {

			if ( $theme_file = locate_template( array( 'er_jobs.php' ) ) ) {
				$template = $theme_file;
			} else {
				$template = ER_PATH . 'public/partials/er_jobs.php';
			}
		}
		
		if(  get_page_template_slug() === 'er_addjob.php' ) {

			if ( $theme_file = locate_template( array( 'er_addjob.php' ) ) ) {
				$template = $theme_file;
			} else {
				$template = ER_PATH . 'public/partials/er_addjob.php';
			}
		}

		if($template == '') {
			throw new \Exception('No template found');
		}

		return $template;
	}

	// Define custom post template with single page
	function projects_template( $template ) {
		
		// For custom archive page
		if ( is_post_type_archive('jobs') ) {
			$theme_files = array('er_jobs.php', '/partials/er_jobs.php');
			$exists_in_theme = locate_template($theme_files, false);
			if ( $exists_in_theme != '' ) {
				return $exists_in_theme;
			} else {
				return  dirname( __FILE__ ) . '/partials/er_jobs.php';
			}
		}

			// For custom single post
		if ( is_singular('jobs') ) {
			$theme_post = array('page-job.php', '/partials/page-job.php');
			$exists_in_theme_pst = locate_template($theme_post, false);
			if ( $exists_in_theme_pst != '' ) {
				return $exists_in_theme_pst;
			} else {
				return  dirname( __FILE__ ) . '/partials/page-job.php';
			}
		}
		return $template;
	}

	
	// Remove from cart
	function remove_jobfromcart(){
		check_ajax_referer('er_profile', 'security');
		if(isset($_POST['post_id']) && isset($_POST['customer_id'])){
			$post_id = $_POST['post_id'];
			$customer_id = $_POST['customer_id'];
			global $current_user,$wpdb;
			
			if($post_id != "" && $customer_id != ""){
				if(is_user_logged_in(  ) && $this->er_role_check( ['driver'] )){
					if($wpdb->query("DELETE FROM {$wpdb->prefix}easy_rents_applications WHERE post_id = $post_id AND driver_id = $current_user->ID AND customer_id = $customer_id")){
					    $redirect_page = $this->get_post_slug(get_option( 'profile_page', true ));
						echo home_url( '/'.$redirect_page );
						die;
					}
				}
			}
		}
	}

	// Ignore Request
	function ignorerequest(){
		check_ajax_referer('er_profile', 'security');
		if(isset($_POST['post_id']) && isset($_POST['driver_id'])){
			$post_id = $_POST['post_id'];
			$driver_id = $_POST['driver_id'];
			global $current_user,$wpdb;
			
			if($post_id != "" && $driver_id != ""){
				if(is_user_logged_in(  ) && $this->er_role_check( ['customer'] )){
					if($wpdb->query("DELETE FROM {$wpdb->prefix}easy_rents_applications WHERE post_id = $post_id AND driver_id = $driver_id AND customer_id = $current_user->ID")){
					    $redirect_page = $this->get_post_slug(get_option( 'profile_page', true ));
						echo home_url( '/'.$redirect_page );
						die;
					}
				}
			}
		}
	}

	// Accept Request
	function acceptrequest(){
		check_ajax_referer('er_profile', 'security');
		if(isset($_POST['post_id']) && isset($_POST['driver_id']) && isset($_POST['offer_id'])){
			$post_id = $_POST['post_id'];
			$driver_id = $_POST['driver_id'];
			$offer_id = $_POST['offer_id'];
			global $current_user,$wpdb;

			if($post_id != "" && $driver_id != ""){
				if(is_user_logged_in(  ) && $this->er_role_check( ['customer'] )){
					$redirect_page = $this->get_post_slug(get_option( 'profile_page', true ));
					if($wpdb->query("UPDATE {$wpdb->prefix}easy_rents_applications SET status = 2, apply_date = now() WHERE driver_id = $driver_id AND customer_id = $current_user->ID AND ID = $offer_id")){
						$job_info = get_post_meta( $post_id, 'er_job_info' );
						$job_info[0]['job_status'] = 'inprogress';

						if(update_post_meta( $post_id, 'er_job_info', $job_info[0] )){
							$wpdb->query("DELETE FROM {$wpdb->prefix}easy_rents_applications WHERE  driver_id = $driver_id AND ID = $offer_id AND status = 1");
							
							if(get_user_meta($driver_id, 'user_phone_number', true )){
								$to = get_user_meta($driver_id, 'user_phone_number', true );
								$dname = get_user_by("id",$driver_id)->user_nicename;
								$message = str_replace('%s',$dname,get_option('acceptjobmsg'));
	
								// if(Easy_Rents_Admin::message_to_user($to, $message)){
									echo " ";
									wp_die();
								// }
							}
							
							die;
						}
					}
				}
			}
		}
	}

	// Request for finished
	function requestforfinishedjob(){
		if(isset($_POST['post_id']) && isset($_POST['customer_id']) && isset($_POST['offer_id'])){
			$post_id = $_POST['post_id'];
			$customer_id = $_POST['customer_id'];
			$offer_id = $_POST['offer_id'];
			global $current_user,$wpdb;

			if(is_user_logged_in(  ) && $this->er_role_check( ['driver'] )){
				if($wpdb->query("UPDATE {$wpdb->prefix}easy_rents_applications SET status = 4  WHERE driver_id = $current_user->ID AND customer_id = $customer_id AND ID = $offer_id")){
					echo " ";
					wp_die();
				}
			}
			die;
		}
	}
	// Request confirmed
	function finishedconfirmed(){
		if(isset($_POST['post_id']) && isset($_POST['driver_id']) && isset($_POST['offer_id'])){
			$post_id = $_POST['post_id'];
			$driver_id = $_POST['driver_id'];
			$offer_id = $_POST['offer_id'];
			global $current_user,$wpdb;

			if(is_user_logged_in(  ) && $this->er_role_check( ['customer'] )){
				if($wpdb->query("UPDATE {$wpdb->prefix}easy_rents_applications SET status = 3, finished_date = now() WHERE customer_id = $current_user->ID AND driver_id = $driver_id AND ID = $offer_id")){
					$job_info = get_post_meta( $post_id, 'er_job_info' );
					$job_info[0]['job_status'] = 'ends';

					if(update_post_meta( $post_id, 'er_job_info', $job_info[0] )){
						// SENT SMS TO DRIVER
						if(get_user_meta($driver_id, 'user_phone_number', true )){
							$to = get_user_meta($driver_id, 'user_phone_number', true );
							$dname = get_user_by("id",$driver_id)->user_nicename;
							$message = str_replace('%s',$dname,get_option('jobconfirmationmsg'));

							// if(Easy_Rents_Admin::message_to_user($to, $message)){
								echo " ";
								wp_die();
							// }
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
	function er_role_check($roles = array()){
		global $current_user;
		$allowed_user = $roles;
		if(array_intersect($allowed_user, $current_user->roles)){
			// return true
			return true;
		}else{
			return false;
		}
	}

	// Profile page
	function er_profile_page( $atts ){
		if(is_user_logged_in(  ) && $this->er_role_check( ['customer','driver'] )){
			require_once(plugin_dir_path( __FILE__ ).'partials/shortcodes/er_dashboard.php');
		}else{
			echo 'Please Login to see';
		}
	}


	// er_profile_trips
	function er_profile_trips( $atts ){
		if(is_user_logged_in(  ) && $this->er_role_check( ['customer','driver'] )){
			require_once(plugin_dir_path( __FILE__ ).'partials/shortcodes/er_mytrips.php');
		}else{
			echo 'Please Login to see';
		}
	}

	// er_payment_page
	function er_payment_page( $atts ){
		if(is_user_logged_in(  ) && $this->er_role_check( ['customer','driver'] )){
			require_once(plugin_dir_path( __FILE__ ).'partials/shortcodes/er_payment.php');
		}else{
			echo 'Please Login to see';
		}
	}

	// er_profile_settings
	function er_profile_settings( $atts ){
		if(is_user_logged_in(  ) && $this->er_role_check( ['customer','driver'] )){
			require_once(plugin_dir_path( __FILE__ ).'partials/shortcodes/er_profile_settings.php');
		}else{
			echo 'Please Login to see';
		}
	}

	// er_register / login page
	function er_login_register( $atts ){
		if(is_user_logged_in(  ) && $this->er_role_check( ['customer','driver'] )){
			require_once(plugin_dir_path( __FILE__ ).'partials/shortcodes/er_login_register.php');
		}else{
			echo 'Please Login to see';
		}
	}

}
