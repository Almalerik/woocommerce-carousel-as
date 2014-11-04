<?php

/**
 * Define the carousel object
 *
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Carousel {

	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * An array with all the attribute of the carousel.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array $attrs_array Carousel attributes.
	 */
	private $attrs_array = array (
			"woocas_items" => 3, // The number of items you want to see on the screen.
			"woocas_margin" => 10, // Margin-right(px) on item.
			"woocas_loop" => true, // Inifnity loop. Duplicate last and first items to get loop illusion.
			"woocas_center" => false, // Center item. Works well with even an odd number of items.
			"woocas_mouseDrag" => true, // Mouse drag enabled.
			"woocas_touchDrag" => true, // Touch drag enabled.
			"woocas_pullDrag" => true, // Stage pull to edge.
			"woocas_freeDrag" => false, // Item pull to edge.
			"woocas_stagePadding" => 0, // Padding left and right on stage (can see neighbours).
			"woocas_autoWidth" => 0, // Set non grid content. Try using width style on divs.
			"woocas_startPosition" => 0, // Start position.
			"woocas_nav" => false, // Show next/prev buttons.
			"woocas_navRewind" => true, // Go to first/last.
			"woocas_navText_prev" => "", // Prev buttons label.
			"woocas_navText_next" => "", // Next buttons label.
			"woocas_dots" => true, // Show dots navigation.
			"woocas_autoplay" => false, // Autoplay.
			"woocas_autoplayTimeout" => 5000, // Autoplay interval timeout.
			"woocas_autoplayHoverPause" => false, // Pause on mouse hover.
			                                // Object containing responsive options. Can be set to false to remove responsive capabilities.
			"woocas_responsive" => array(
				240 => array(
					"woocas_items" => 2,
					"woocas_loop" => true
					),
				1024 => array(
					"woocas_items" => 6,
					"woocas_loop" => true
					)
				)
	);

	/**
	 * An array to store js carousel parameters init.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array $woocas_js_arg Js Carousel parameters.
	 */
	private $woocas_js_arg = array ();

	/**
	 * An array to store attributes that could be added in responsive section.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array $woocas_responsive_attr_avaible Carousel responsive attributes.
	 */
	private $woocas_responsive_attr_avaible = array (
			"woocas_items",
			"woocas_loop",
			"woocas_center",
			"woocas_mouseDrag",
			"woocas_touchDrag",
			"woocas_pullDrag",
			"woocas_freeDrag",
			"woocas_margin",
			"woocas_stagePadding",
			"woocas_autoWidth",
			"woocas_autoHeight",
			"woocas_nav",
			"woocas_navRewind",
			"woocas_slideBy",
			"woocas_dots",
			"woocas_autoplay",
			"woocas_autoplayTimeout",
			"woocas_autoplaySpeed",
			"woocas_animateOut",
			"woocas_animateIn",
			"woocas_fallbackEasing",
			"woocas_callbacks",
			"woocas_info" 
	);

	private $woocas_standard_options = array (
			"woocas_items", // The number of items you want to see on the screen.
			"woocas_margin", // Margin-right(px) on item.
			"woocas_loop", // Inifnity loop. Duplicate last and first items to get loop illusion.
			"woocas_center", // Center item. Works well with even an odd number of items.
			"woocas_stagePadding", // Padding left and right on stage (can see neighbours).
			"woocas_autoWidth", // Set non grid content. Try using width style on divs.
			"woocas_nav", // Show next/prev buttons.
			"woocas_dots", // Show dots navigation.
			"woocas_autoplay", // Autoplay.
			"woocas_autoplayTimeout", // Autoplay interval timeout.
			"woocas_autoplayHoverPause" // Pause on mouse hover.
	);

	private $woocas_advanced_options = array (
			"woocas_mouseDrag", // Mouse drag enabled.
			"woocas_touchDrag", // Touch drag enabled.
			"woocas_pullDrag", // Stage pull to edge.
			"woocas_freeDrag", // Item pull to edge.
			"woocas_startPosition", // Start position.
			"woocas_navText_prev", // Prev buttons label.
			"woocas_navText_next", // Next buttons label.
			"woocas_navRewind", // Go to first/last.
	);

	private $woocas_filter_categories = "";
	private $woocas_filter_show = "";
	private $woocas_filter_orderby = "date";
	private $woocas_filter_order = "desc";
	private $woocas_filter_hide_free = "0";
	private $woocas_filter_show_hidden = "0";

	private function __construct() {
	}

	/**
	 * Instance a carousel object passing the id.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function loadByID($plugin_name, $post_id) {
		$instance = new self();
		
		$instance->plugin_name = $plugin_name;

		$instance->woocas_filter_categories = get_post_meta ( $post_id, "woocas_filter_categories", true );
		$instance->woocas_filter_show = get_post_meta ( $post_id, "woocas_filter_show", true );
		$instance->woocas_filter_hide_free = get_post_meta ( $post_id, "woocas_filter_hide_free", true );
		$instance->woocas_filter_show_hidden = get_post_meta ( $post_id, "woocas_filter_show_hidden", true );
		$instance->woocas_filter_orderby = get_post_meta ( $post_id, "woocas_filter_orderby", true );
		$instance->woocas_filter_order = get_post_meta ( $post_id, "woocas_filter_order", true );
		
		$carousel_fields = get_post_meta ( $post_id, "woocas_data", false );

		if (sizeof($carousel_fields) > 0 ) {
			$carousel_fields = $carousel_fields[0];
			foreach ( $carousel_fields as $attribute_name => $value ) {
				//For bool attr set true or false
				if (is_bool($instance->attrs_array [$attribute_name])) {
					if ($value === "1") {
						$instance->attrs_array [$attribute_name] = true;
					} else {
						$instance->attrs_array [$attribute_name] = false;
					}

				} else {
					$instance->attrs_array [$attribute_name] = $value;
				}
				//Prepare here js carousel arg excluding default value
				if ($attribute_name != "woocas_responsive") {
					array_push ( $instance->woocas_js_arg, str_replace ( "woocas_", "", $attribute_name ) . ": " . $carousel_fields [$attribute_name] );
				} else {
					$width_array = array();
					foreach ( $carousel_fields [$attribute_name] as $width => $responsive_attr ) {
						$attr_array = array();
						foreach ( $carousel_fields [$attribute_name] [$width] as $attr => $attr_val ) {
							$attr_array[] = str_replace ("'","",str_replace ( "woocas_", "", $attr )) . " : " . $attr_val ;
						}
						$width_array[] = $width . " : {" . PHP_EOL . implode(",".PHP_EOL , $attr_array) . "}";
					}
					$responsive = "responsive : {" . PHP_EOL . implode(",".PHP_EOL , $width_array) ."}" . PHP_EOL;
					array_push ( $instance->woocas_js_arg, $responsive );
				}
			}
		}
		return $instance;
	}

	/**
	 * Return the label of an attribute.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param $attribute Attributes name
	 */
	public function get_label($attribute) {
		switch ($attribute) {
			case "woocas_items" :
				return __( "Items number", $this->plugin_name );
				break;
			case "woocas_margin" :
				return __( "Margin right", $this->plugin_name );
				break;
			case "woocas_loop" :
				return __( "Infinity loop", $this->plugin_name );
				break;
			case "woocas_center" :
				return __( "Center item", $this->plugin_name );
				break;
			case "woocas_mouseDrag" :
				return __( "Mouse drag", $this->plugin_name );
				break;
			case "woocas_touchDrag" :
				return __( "Touch drag", $this->plugin_name );
				break;
			case "woocas_pullDrag" :
				return __( "Pull drag", $this->plugin_name );
				break;
			case "woocas_freeDrag" :
				return __( "Free drag", $this->plugin_name );
				break;
			case "woocas_stagePadding" :
				return __( "Stage padding", $this->plugin_name );
				break;
			case "woocas_autoWidth" :
				return __( "Auto width", $this->plugin_name );
				break;
			case "woocas_startPosition" :
				return __( "Start position", $this->plugin_name );
				break;
			case "woocas_nav" :
				return __( "Nav", $this->plugin_name );
				break;
			case "woocas_navRewind" :
				return __( "Nav rewind", $this->plugin_name );
				break;
			case "woocas_navText_prev" :
				return __( "Nav previous text button", $this->plugin_name );
				break;
			case "woocas_navText_next" :
				return __( "Nav next text button", $this->plugin_name );
				break;
			case "woocas_slideBy" :
				return __( "Slide By", $this->plugin_name );
				break;
			case "woocas_dots" :
				return __( "Dots navigation", $this->plugin_name );
				break;
			case "woocas_autoplay" :
				return __( "Autoplay", $this->plugin_name );
				break;
			case "woocas_autoplayTimeout" :
				return __( "Autoplay timeout", $this->plugin_name );
				break;
			case "woocas_autoplayHoverPause" :
				return __( "Autoplay pause on over", $this->plugin_name );
				break;
			default :
				return $attribute;
		}
	}

	public function get_description($attribute) {
		switch ($attribute) {
			case "woocas_items" :
				return __( "The number of items you want to see on the screen.", $this->plugin_name );
				break;
			case "woocas_margin" :
				return __( "Margin right on every items.", $this->plugin_name );
				break;
			case "woocas_loop" :
				return __( "Duplicate last and first items to get loop illusion.", $this->plugin_name );
				break;
			case "woocas_center" :
				return __( "Works well with even an odd number of items.", $this->plugin_name );
				break;
			case "woocas_mouseDrag" :
				return __( "Mouse drag enabled.", $this->plugin_name );
				break;
			case "woocas_touchDrag" :
				return __( "Touch drag enabled.", $this->plugin_name );
				break;
			case "woocas_pullDrag" :
				return __( "Pull drag enabled.", $this->plugin_name );
				break;
			case "woocas_freeDrag" :
				return __( "Free drag enabled.", $this->plugin_name );
				break;
			case "woocas_stagePadding" :
				return __( "Padding left and right on stage (can see neighbours).", $this->plugin_name );
				break;
			case "woocas_autoWidth" :
				return __( "Auto width", $this->plugin_name );
				break;
			case "woocas_startPosition" :
				return __( "Start position", $this->plugin_name );
				break;
			case "woocas_nav" :
				return __( "Nav", $this->plugin_name );
				break;
			case "woocas_navRewind" :
				return __( "Nav rewind", $this->plugin_name );
				break;
			case "woocas_navText_prev" :
				return __( "Nav previous text button", $this->plugin_name );
				break;
			case "woocas_navText_next" :
				return __( "Nav next text button", $this->plugin_name );
				break;
			case "woocas_slideBy" :
				return __( "Slide By", $this->plugin_name );
				break;
			case "woocas_dots" :
				return __( "Dots navigation", $this->plugin_name );
				break;
			case "woocas_autoplay" :
				return __( "Autoplay", $this->plugin_name );
				break;
			case "woocas_autoplayTimeout" :
				return __( "Autoplay timeout", $this->plugin_name );
				break;
			case "woocas_autoplayHoverPause" :
				return __( "Autoplay pause on over", $this->plugin_name );
				break;
			default :
				return $attribute;
		}
	}

	public function edit_form() {
		?>
	<div id="woocas-admin-tab">
		<h2 class="nav-tab-wrapper">
			<a href="#woocas-woo-filter" class="nav-tab nav-tab-active"><?php _e( "WooCommerce Filter", $this->plugin_name ) ?></a>
			<a href="#woocas-responsice-tab" class="nav-tab"><?php _e( "Responsive", $this->plugin_name ) ?></a>
			<a href="#general" class="nav-tab"><?php _e( "Genral", $this->plugin_name ) ?></a>
		</h2>
		<div id="woocas-woo-filter">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="woocas_cat_filter"><?php _e("Category filter") ?>:</label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e("Category filter") ?>:</span>
							</legend>
							<select name="woocas_cat_filter" id="woocas_cat_filter" style="display: none;" >
							<?php 
								$categories = get_categories('taxonomy=product_cat'); 
								foreach (  $categories as $cat ) { ?>
								<option value="<?php echo $cat->name ?>"><?php echo $cat->name ?></option>
							<?php } ?>
							</select>
							<input id="woocas_filter_categories" name="woocas_filter_categories" size="50" value="<?php echo $this->woocas_filter_categories ?>">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_filter_show"><?php _e("Show") ?>:</label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e("Show") ?>:</span>
							</legend>
							<select name="woocas_filter_show" id="woocas_show_filter" >
								<option value="" <?php echo ( $this->woocas_filter_show == "" ) ? 'selected="selected"' : ''?> > <?php _e( 'All Products' )?></option>
								<option value="featured" <?php echo ( $this->woocas_filter_show == "featured" ) ? 'selected="selected"' : ''?> > <?php _e( 'Featured Products' )?></option>
								<option value="onsale" <?php echo ( $this->woocas_filter_show == "onsale" ) ? 'selected="selected"' : ''?>><?php _e( 'On-sale Products' )?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_filter_orderby"><?php _e("Order by") ?>:</label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e("Order by") ?>:</span>
							</legend>
							<select name="woocas_filter_orderby" id="woocas_filter_orderby" >
								<option value="date" <?php echo ( $this->woocas_filter_orderby == "date" ) ? 'selected="selected"' : ''?>><?php _e( 'Date' )?></option>
								<option value="price" <?php echo ( $this->woocas_filter_orderby == "price" ) ? 'selected="selected"' : ''?>><?php _e( 'Price' )?></option>
								<option value="rand" <?php echo ( $this->woocas_filter_orderby == "rand" ) ? 'selected="selected"' : ''?>><?php _e( 'Random' )?></option>
								<option value="sales" <?php echo ( $this->woocas_filter_orderby == "sales" ) ? 'selected="selected"' : ''?>><?php _e( 'Sales' )?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_filter_order"><?php _e("Order") ?>:</label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e("Order") ?>:</span>
							</legend>
							<select name="woocas_filter_order" id="woocas_filter_order" >
								<option value="asc" <?php echo ( $this->woocas_filter_order == "asc" ) ? 'selected="selected"' : ''?>><?php _e( 'ASC' )?></option>
								<option value="desc" <?php echo ( $this->woocas_filter_order == "desc" ) ? 'selected="selected"' : ''?>><?php _e( 'DESC' )?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_filter_hide_free"><?php _e("Hide free products") ?>:</label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e("Hide free products") ?>:</span>
							</legend>
							<select name="woocas_filter_hide_free" id="woocas_filter_hide_free" >
								<option value="0" <?php echo ( $this->woocas_filter_hide_free == "0" ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
								<option value="1" <?php echo ( $this->woocas_filter_hide_free == "1" ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_filter_show_hidden"><?php _e("Show hidden products") ?>:</label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e("Show hidden products") ?>:</span>
							</legend>
							<select name="woocas_filter_show_hidden" id="woocas_filter_show_hidden" >
								<option value="0" <?php echo ( $this->woocas_filter_show_hidden == "0" ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
								<option value="1" <?php echo ( $this->woocas_filter_show_hidden == "1" ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="hidden" id="woocas-responsice-tab">
			<table class="form-table woocas-responsice-table">
				<tbody>
					<tr>
						<td>
							<label><?php _e( "Options applicate to resolution upper", $this->plugin_name ) ?>:</label>
							<input type="text" class="small-text" id="woocas-add-breakpoint-value" value="" />
							px
							<input type="button" class="button button-small" value="<?php _e('Add breakpoint') ?>" id="woocas-add-breakpoint" />
						</td>
						<td></td>
					</tr>
				</tbody>
			</table>
			<div id="woocas-responsive-base" style="display: none;">
				<h3>
					<?php _e( "Resolution", $this->plugin_name ) ?> &gt;=
					<span></span>
					px
					<a href="#" class="dashicons dashicons-no woocas-responsive-delete"></a>
				</h3>
				<div>
					<input type="hidden" name="breakpoint-id[]" class="breakpoint-id" value="" />
					<label style="font-size: 14px"><?php _e( "Add option", $this->plugin_name ) ?>:</label> <select class="woocas-responsive-base-attributes-select">
	            <?php foreach ( $this->woocas_responsive_attr_avaible as $index => $value ) { ?>
	              <option value="<?php echo $value ?>"><?php echo $this->get_label($value) ?></option>
	            <?php }?>
	            </select>
					<a href="#" class="button-secondary woocas-responsive-add-attribute ">__( "Add" )</a>
					<table class="form-table">
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div id="woocas-responsive-accordion">
			<?php foreach ( $this->attrs_array["woocas_responsive"] as $index => $value ) { ?>
				<div id="woocas-responsive-<?php echo $index ?>">
					<h3>
						<?php _e( "Resolution", $this->plugin_name ) ?> &gt;=
						<span><?php echo $index ?></span>
						px
						<a href="<?php echo $index ?>" class="dashicons dashicons-no woocas-responsive-delete"></a>
					</h3>
					<div>
						<input type="hidden" name="breakpoint-id[]" class="breakpoint-id" value="<?php echo $index ?>">
						<label  style="font-size: 14px"><?php _e( "Add option", $this->plugin_name ) ?>:</label> <select class="woocas-responsive-attributes-select"></select>
						<a href="<?php echo $index ?>" class="button-secondary woocas-responsive-add-attribute "><?php _e( "Add" ) ?></a>
						<table class="form-table">
							<tbody>
								<?php foreach ( $value as $index_attr => $value_attr ) { ?>
								<tr class="<?php echo str_replace("'", "", $index_attr) ?>">
									<th scope="row">
										<label for="<?php echo str_replace("'", "", $index_attr) ?>"><?php echo $this->get_label(str_replace("'", "", $index_attr)) ?>:</label>
									</th>
									<td>
										<legend class="screen-reader-text">
											<span><?php echo $this->get_label(str_replace("'", "", $index_attr)) ?>:</span>
										</legend>
										<input type="text" class="" name="responsive[<?php echo $index ?>][<?php echo $index_attr ?>]" value="<?php echo $value_attr ?>">
									</td>
								</tr>
								<?php }?>
							</tbody>
						</table>
					</div>
				</div>
			<?php }?>
			</div>

		</div>

		<div class="hidden" id="general">
			<div id="woocas_advance_options">
				<h3><?php _e("Advanced Options") ?></h3>
				<div>
					<table class="form-table">
						<tbody>
							<?php foreach ( $this->woocas_advanced_options as $attr ) {
								echo $this -> get_attribute_html($attr);
							} ?>
						</tbody>
					</table>
				</div>
			</div>
			<table class="form-table">
				<tbody>
					<?php foreach ( $this->woocas_standard_options as $attr ) {
						echo $this -> get_attribute_html($attr);
					} ?>
				</tbody>
			</table>
		</div>
	</div>

	<?php
	}

	/**
	*	
	*
	*/
	public function save($post_id, $post) {
		$default = new Carousel ();
		$data = array ();
		foreach ( $default->attrs_array as $attribute_name => $value ) {
			if (isset ( $_POST [$attribute_name] )) {
				if (sanitize_text_field ( $post [$attribute_name] ) != $default->attrs_array [$attribute_name])
					$data [$attribute_name] = sanitize_text_field ( $post [$attribute_name] );
			}
		}
		
		foreach ( $post ['responsive'] as $width =>  $attributes ) {
			$data ['woocas_responsive'][$width] = array();
			foreach ( $attributes as $attribute => $value ) {
				$data ['woocas_responsive'][sanitize_text_field($width)][sanitize_text_field($attribute)] =sanitize_text_field($value);
			}
		}
		ksort($data ['woocas_responsive']);
		reset($data ['woocas_responsive']);
		update_post_meta ( $post_id, 'woocas_data', $data );
	}

	public function get_woocas_js_arg() {
		return implode ( ",", $this->woocas_js_arg );
	}

	private function get_attribute_html( $attr ) {
	?>
		<tr class="<?php echo $attr ?>">
			<th scope="row">
				<label for="<?php echo $attr ?>"><?php echo $this->get_label($attr) ?>: </label>
			</th>
			<td>
				<legend class="screen-reader-text">
					<span>><?php echo $this->get_label($attr) ?>: </span>
				</legend>
				<?php if (is_bool($this->attrs_array [$attr])) { ?>
				<select name="<?php echo $attr ?>">
					<option value="0" <?php echo ( !$this->attrs_array[$attr] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
					<option value="1" <?php echo ( $this->attrs_array[$attr] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
				</select>
				<?php } else { ?>
				<input type="text" class="width-50" name="<?php echo $attr ?>" value="<?php echo $this->attrs_array[$attr] ?>" />
				<?php } ?>
				<span class="description"><?php echo $this->get_description($attr) ?></span>
			</td>
		</tr>
	<?php
	}
}

