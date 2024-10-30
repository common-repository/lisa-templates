<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://miniup.gl
 * @since      1.0.0
 *
 * @package    Lisa
 * @subpackage Lisa/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Lisa
 * @subpackage Lisa/public
 * @author     Pierre Minik Lynge <hello@miniup.gl>
 */
class Lisa_Public {

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

	public $conditions = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_sidebar_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lisa_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lisa_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sidebar.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lisa_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lisa_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/lisa-public.js', array( 'jquery' ), $this->version, false );

	}

	public function register_cpt() {
		$capabilities = apply_filters( 'lisa_capabilites', 'switch_themes' );

		$labels = array(
			'name'                  => _x( 'Lisa Templates', 'Post Type General Name', 'lisa' ),
			'singular_name'         => _x( 'Lisa Template', 'Post Type Singular Name', 'lisa' ),
			'menu_name'             => __( 'Lisa Templates', 'lisa' ),
			'name_admin_bar'        => __( 'Lisa Templates', 'lisa' ),
			'archives'              => __( 'Template Archives', 'lisa' ),
			'attributes'            => __( 'Template Attributes', 'lisa' ),
			'parent_item_colon'     => __( 'Parent Template:', 'lisa' ),
			'all_items'             => __( 'All Templates', 'lisa' ),
			'add_new_item'          => __( 'Add New Template', 'lisa' ),
			'add_new'               => __( 'Add New', 'lisa' ),
			'new_item'              => __( 'New Template', 'lisa' ),
			'edit_item'             => __( 'Edit Template', 'lisa' ),
			'update_item'           => __( 'Update Template', 'lisa' ),
			'view_item'             => __( 'View Template', 'lisa' ),
			'view_items'            => __( 'View Templates', 'lisa' ),
			'search_items'          => __( 'Search Template', 'lisa' ),
			'not_found'             => __( 'No templates found', 'lisa' ),
			'not_found_in_trash'    => __( 'No templates found in Trash', 'lisa' ),
			'insert_into_item'      => __( 'Insert into template', 'lisa' ),
			'uploaded_to_this_item' => __( 'Uploaded to this template', 'lisa' ),
			'items_list'            => __( 'Templates list', 'lisa' ),
			'items_list_navigation' => __( 'Templates list navigation', 'lisa' ),
			'filter_items_list'     => __( 'Filter templates list', 'lisa' ),
		);

		$args = array(
			'label'                 => __( 'Lisa Template', 'lisa' ),
			'description'           => __( 'Lisa Templates for Content.', 'lisa' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'page-attributes' ),
			'hierarchical'          => true,
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 100,
			'menu_icon'             => 'dashicons-editor-code',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => false,
			'capability_type'       => 'page',
			'capabilities' => array(
		    'edit_post'          => $capabilities,
		    'read_post'          => $capabilities,
		    'delete_post'        => $capabilities,
		    'edit_posts'         => $capabilities,
		    'edit_others_posts'  => $capabilities,
		    'delete_posts'       => $capabilities,
		    'publish_posts'      => $capabilities,
		    'read_private_posts' => $capabilities
			)
		);

		register_post_type( 'lisa_template', $args );
	}

}
