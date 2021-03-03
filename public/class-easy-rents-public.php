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
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		// Profile style
		wp_register_style( 'er_profile_style', plugin_dir_url( __FILE__ ) . 'css/easy-rents-profile.css', array(), $this->version, 'all' );
		// addnewjob style
		wp_register_style( 'er_addjob_style', plugin_dir_url( __FILE__ ) . 'css/easy-rents-addjob.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// Profile script
		wp_register_script( 'er_profile_script', plugin_dir_url( __FILE__ ) . 'js/easy-rents-profile.js', array( 'jquery' ), $this->version, false );
		
		// addjob script
		wp_register_script( 'er_addjob_script', plugin_dir_url( __FILE__ ) . 'js/easy-rents-addjob.js', array( 'jquery' ), $this->version, false );

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
			// Features this CPT supports in Post Editor
			'supports'            => array( 'title', 'editor', 'author', 'comments' ),
			// You can associate this CPT with a taxonomy or custom taxonomy. 
			'taxonomies'          => array( 'jobs' ),
			/* A hierarchical CPT is like Pages and can have
			* Parent and child items. A non-hierarchical CPT
			* is like Posts.
			*/ 
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
		register_post_type( 'erjobs', $args );
	 
	}


	/**
	 * Define template name
	 */
	function webclass_templates ($templates) {
		$templates['er_jobs.php'] = 'All jobs';
		$templates['er_addjob.php'] = 'Add job';
		$templates['er_profile.php'] = 'Profile';
		
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
		
		if(  get_page_template_slug() === 'er_profile.php' ) {

			if ( $theme_file = locate_template( array( 'er_profile.php' ) ) ) {
				$template = $theme_file;
			} else {
				$template = ER_PATH . 'public/partials/er_profile.php';
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
		if ( is_post_type_archive('erjobs') ) {
			$theme_files = array('er_jobs.php', 'public/partials/er_jobs.php');
			$exists_in_theme = locate_template($theme_files, false);
			if ( $exists_in_theme != '' ) {
				return $exists_in_theme;
			} else {
				return  dirname( __FILE__ ) . 'public/partials/er_jobs.php';
			}
		}

			// For custom single post
		if ( is_singular('erjobs') ) {
			$theme_post = array('page-job.php', 'public/partials/page-job.php');
			$exists_in_theme_pst = locate_template($theme_post, false);
			if ( $exists_in_theme_pst != '' ) {
				return $exists_in_theme_pst;
			} else {
				return  dirname( __FILE__ ) . 'public/partials/page-job.php';
			}
		}
		return $template;
	}

	function test(){
		$location = array('loc1' => 'rajapur','loc2' => 'rayenda', 'loc3' => 'amragachia');
		$locs = get_post_meta(2,'location');
		$i=1;
		foreach($locs as $loc){
			foreach($loc as $place){
				// echo $place;
			}
		}
	}
	

}
