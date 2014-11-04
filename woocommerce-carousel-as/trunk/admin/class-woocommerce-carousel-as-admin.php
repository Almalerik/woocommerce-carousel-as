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
		wp_enqueue_script ( 'jquery-ui-autocomplete' );
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

	public function set_custom_edit_carousel_columns($columns) {
		unset ( $columns ['date'] );
		$columns ['shortcut'] = __ ( 'Shortcut', $this->plugin_name );
		$columns ['date'] = _x ( 'Date', 'column name' );
		return $columns;
	}

	public function custom_carousel_column($column, $post_id) {
		switch ($column) {
			case 'shortcut' :
				echo "[woocas id=$post_id]";
				break;
		}
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id
	 *        	The ID of the post being saved.
	 */
	public function save($post_id) {
		
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */
		
		// Check if our nonce is set.
		if (! isset ( $_POST ['woocarouselas_inner_meta_box_nonce'] ))
			return $post_id;
		
		$nonce = $_POST ['woocarouselas_inner_meta_box_nonce'];
		
		// Verify that the nonce is valid.
		if (! wp_verify_nonce ( $nonce, 'woocarouselas_inner_meta_box' ))
			return $post_id;
			
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if (defined ( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)
			return $post_id;
			
		// Check the user's permissions.
		if ('page' == $_POST ['post_type']) {
			
			if (! current_user_can ( 'edit_page', $post_id ))
				return $post_id;
		} else {
			
			if (! current_user_can ( 'edit_post', $post_id ))
				return $post_id;
		}
		
		/* OK, its safe for us to save the data now. */
		$carousel = Carousel::loadByID ( $this->plugin_name, $post_id );
		$carousel->save ( $post_id, $_POST );
		
		
		// Sanitize the user input.
		$woocas_filter_categories = sanitize_text_field( $_POST['woocas_filter_categories'] );
		$woocas_filter_show = sanitize_text_field( $_POST['woocas_filter_show'] );
		$woocas_filter_orderby = sanitize_text_field( $_POST['woocas_filter_orderby'] );
		$woocas_filter_order = sanitize_text_field( $_POST['woocas_filter_order'] );
		$woocas_filter_hide_free = sanitize_text_field( $_POST['woocas_filter_hide_free'] );
		$woocas_filter_show_hidden = sanitize_text_field( $_POST['woocas_filter_show_hidden'] );

		// Update the meta field.
		update_post_meta( $post_id, 'woocas_filter_categories', $woocas_filter_categories );
		update_post_meta( $post_id, 'woocas_filter_show', $woocas_filter_show );
		update_post_meta( $post_id, 'woocas_filter_orderby', $woocas_filter_orderby );
		update_post_meta( $post_id, 'woocas_filter_order', $woocas_filter_order );
		update_post_meta( $post_id, 'woocas_filter_hide_free', $woocas_filter_hide_free );
		update_post_meta( $post_id, 'woocas_filter_show_hidden', $woocas_filter_show_hidden );
		 
	}


	// [woocas]
	public function shortcode($atts) {
		$carousel_id = get_post ( $atts ['id'] );
		if (sizeof ( $carousel_id ) > 0) {
			// La Query
			$query_args = array (
					'post_type' => array (
							'product' 
					) 
			);
			$carousel_query = new WP_Query ( $query_args );
			
			echo '<div class="slider multiple-items">';
			
			// Il Loop
			while ( $carousel_query->have_posts () ) :
				$carousel_query->next_post ();
				echo '<div>';
				// echo '<li>' . get_the_title( $carousel_query->post->ID ) . '</li>';
				echo get_the_post_thumbnail ( $carousel_query->post->ID, 'thumbnail' );
				echo '<span class="title">' . get_the_title ( $carousel_query->post->ID ) . '</span>';
				echo '</div>';
			endwhile
			;
			
			// Ripristina Query & Post Data originali
			wp_reset_query ();
			wp_reset_postdata ();
			echo '</div>';
			
			// Generate js script
			$accessibility = ($carousel_id->accessibility == "1") ? "true" : "false";
			$autoplay = ($carousel_id->autoplay == "1") ? "true" : "false";
			$autoplaySpeed = $carousel_id->autoplaySpeed;
			$infinite = ($carousel_id->infinite == "1") ? "true" : "false";
			
			$carousel = Carousel::loadByID ( $this->plugin_name, $atts ['id'] );
			$slick_arg = $carousel->get_woocas_js_arg();

			
			echo '<script>';
			echo "jQuery(document).ready(function() {jQuery('.multiple-items').owlCarousel({" . $slick_arg . "});});";
			echo '</script>';
		}
	}

	public function woocas_carousel_meta_box() {
		add_meta_box ( 'woocarouselas_meta_box', __ ( 'Carousel Settings', $this->plugin_name ), array (
				$this,
				'render_woocas_carousel_meta_box_content' 
		), 'woocarouselas', 'advanced', 'high' );
		add_meta_box ( 'woocarouselas_donate_meta_box', __ ( 'Donate', $this->plugin_name ), array (
				$this,
				'render_woocas_donate_meta_box_content' 
		), 'woocarouselas', 'side', 'high' );
		add_meta_box ( 'woocarouselas_shortcode_meta_box', __ ( 'Shortcode' ), array (
				$this,
				'render_woocas_shortcode_meta_box_content' 
		), 'woocarouselas', 'side', 'high' );
	}

	public function render_woocas_carousel_meta_box_content($post) {
		
		// Add an nonce field so we can check for it later.
		wp_nonce_field ( 'woocarouselas_inner_meta_box', 'woocarouselas_inner_meta_box_nonce' );
		$carousel = Carousel::loadByID( $this->plugin_name, $post->ID );
		$carousel->edit_form ( $this->plugin_name );
	}

	public function render_woocas_donate_meta_box_content($post) {
		?>
		<h4><?php _e('If you find this plugin useful, buy me a beer!') ?></h4>
		<i>Thanks, Almalerik</i><br/><br/>
		<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8PW2YUXQZWYCJ" target="_blank">
			<img src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
			<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
	</a>
		<?php
	}

	public function render_woocas_shortcode_meta_box_content($post) {
		?>
		<p><?php _e('After saved, put this shortcode in a article or in a page.') ?></p>
		<h4><b>[woocas id=<?php echo $post->ID ?>]</b></h4>
		<?php
	}

}
