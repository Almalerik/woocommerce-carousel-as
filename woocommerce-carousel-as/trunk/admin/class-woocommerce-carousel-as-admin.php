<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    woocommerce-carousel-as
 * @subpackage woocommerce-carousel-as/admin
 * @author     Your Name <email@example.com>
 */
class Woocommerce_Carousel_AS_Admin {

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
	 * @var      string    $plugin_name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Carousel_AS_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Carousel_AS_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-carousel-as-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Carousel_AS_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Carousel_AS_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script ( 'jquery-ui-core' );
		wp_enqueue_script ( 'jquery-ui-accordion' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-carousel-as-admin.js', array( 'jquery' ), $this->version, false );

	}
    
	/**
	 * Return the plugin action links.  This will only be called if the plugin
	 * is active.
	 *
	 * @since 1.0.0
	 * @param array $actions associative array of action names to anchor tags
	 * @return array associative array of plugin action links
	 */
	public function add_plugin_action_links( $actions ) {

		$custom_actions = array(
			'add' => sprintf( '<a href="%s">%s</a>', admin_url( 'edit.php?post_type=woocarouselas' ), __( 'Add Carousel', $this->plugin_name ) ),
			'support'   => sprintf( '<a href="%s">%s</a>', 'https://github.com/Almalerik/woocommerce-carousel-as/issues', __( 'Support', $this->plugin_name ) ),
		);

		// add the links to the front of the actions list
		return array_merge( $custom_actions, $actions );
	}

	/**
	 * Define carousel tyoe
	 *
	 * @since     1.0.0
	 */
	public function woocommerce_carousel_as_type() {
		$labels = array(
			'name'               => __( 'Woo Carousels', $this->plugin_name ),
			'singular_name'      => __( 'Woo Carousel', $this->plugin_name ),
			'add_new'            => __( 'Add'),
			'add_new_item'       => __( 'Add New Carousel', $this->plugin_name ),
			'edit_item'          => __( 'Edit Carousel', $this->plugin_name ),
			'new_item'           => __( 'New Carousel', $this->plugin_name ),
			'all_items'          => __( 'All Carousels', $this->plugin_name ),
			'view_item'          => __( 'View Carousel', $this->plugin_name ),
			'search_items'       => __( 'Search Carousels', $this->plugin_name ),
			'not_found'          => __( 'No carousels found', $this->plugin_name ),
			'not_found_in_trash' => __( 'No carousels found in the Trash', $this->plugin_name ),
			'parent_item_colon'  => '',
			'menu_name'          => 'WooCommerce Carousels'
		);
		$args = array(
			'labels'        => $labels,
			'description'   => __( 'Holds WooCommerce Carousels', $this->plugin_name ),
			'public'        => true,
			'menu_position' => 56,
			'supports'      => array( 'title', 'editor' ),
			'has_archive'   => true,
		);
		register_post_type( 'woocarouselas', $args );
	}

	public function carousel_meta_box() {
		add_meta_box ( 'woocarouselas_meta_box', __ ( 'Carousel Settings', $this->plugin_name ), array (
				$this,
				'render_carousel_meta_box_content' 
		), 'woocarouselas', 'advanced', 'high' );
	}

	public function render_carousel_meta_box_content($post) {
		
		// Add an nonce field so we can check for it later.
		wp_nonce_field ( 'woocarouselas_inner_meta_box', 'woocarouselas_inner_meta_box_nonce' );
		
		$xx = Carousel::loadByID( $this->plugin_name, $post->ID );
		$xx->edit_form ( $this->plugin_name );
	}

}
