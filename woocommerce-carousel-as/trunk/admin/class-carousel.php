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
			"woocas_loop" => 0, // Inifnity loop. Duplicate last and first items to get loop illusion.
			"woocas_center" => 0, // Center item. Works well with even an odd number of items.
			"woocas_mouseDrag" => true, // Mouse drag enabled.
			"woocas_touchDrag" => true, // Touch drag enabled.
			"woocas_pullDrag" => true, // Stage pull to edge.
			"woocas_freeDrag" => 0, // Item pull to edge.
			"woocas_stagePadding" => 0, // Padding left and right on stage (can see neighbours).
			"woocas_merge" => 0, // Merge items. Looking for data-merge='{number}' inside item..
			"woocas_mergeFit" => true, // Fit merged items if screen is smaller than items value.
			"woocas_autoWidth" => 0, // Set non grid content. Try using width style on divs.
			"woocas_startPosition" => 0, // Start position.
			"woocas_nav" => 0, // Show next/prev buttons.
			"woocas_navRewind" => true, // Go to first/last.
			"woocas_navText_prev" => "", // Prev buttons label.
			"woocas_navText_next" => "", // Next buttons label.
			"woocas_slideBy" => "1", // Navigation slide by x. 'page' string can be set to slide by page.
			"woocas_dots" => true, // Show dots navigation.
			"woocas_autoplay" => 0, // Autoplay.
			"woocas_autoplayTimeout" => 5000, // Autoplay interval timeout.
			"woocas_autoplayHoverPause" => 0, // Pause on mouse hover.
			                                // Object containing responsive options. Can be set to false to remove responsive capabilities.
			"woocas_responsive" => array(
				1024 => array(
					'items' => 5
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
			"woocas_merge",
			"woocas_mergeFit",
			"woocas_autoWidth",
			"woocas_autoHeight",
			"woocas_nav",
			"woocas_navRewind",
			"woocas_slideBy",
			"woocas_dots",
			"woocas_dotsEach",
			"woocas_autoplay",
			"woocas_autoplayTimeout",
			"woocas_smartSpeed",
			"woocas_fluidSpeed",
			"woocas_autoplaySpeed",
			"woocas_navSpeed",
			"woocas_dotsSpeed",
			"woocas_dragEndSpeed",
			"woocas_responsiveRefreshRate",
			"woocas_animateOut",
			"woocas_animateIn",
			"woocas_fallbackEasing",
			"woocas_callbacks",
			"woocas_info" 
	);

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
		
		$carousel_fields = get_post_meta ( $post_id, "woocas_data", false );

		if (sizeof($carousel_fields) > 0 ) {
			$carousel_fields = $carousel_fields[0];
			foreach ( $carousel_fields as $attribute_name => $value ) {
				$instance->attrs_array [$attribute_name] = $value;
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
					$responsive .= "responsive : {" . PHP_EOL . implode(",".PHP_EOL , $width_array) ."}" . PHP_EOL;
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
				return __( "woocas_items_label", $this->plugin_name );
				break;
			case "woocas_margin" :
				return __( "woocas_margin_label", $this->plugin_name );
				break;
			case "woocas_loop" :
				return __( "woocas_loop_label", $this->plugin_name );
				break;
			case "woocas_center" :
				return __( "woocas_center_label", $this->plugin_name );
				break;
			case "woocas_mouseDrag" :
				return __( "woocas_mouseDrag_label", $this->plugin_name );
				break;
			case "woocas_touchDrag" :
				return __( "woocas_touchDrag_label", $this->plugin_name );
				break;
			case "woocas_pullDrag" :
				return __( "woocas_pullDrag_label", $this->plugin_name );
				break;
			case "woocas_freeDrag" :
				return __( "woocas_freeDrag_label", $this->plugin_name );
				break;
			case "woocas_stagePadding" :
				return __( "woocas_stagePadding_label", $this->plugin_name );
				break;
			case "woocas_merge" :
				return __( "woocas_merge_label", $this->plugin_name );
				break;
			case "woocas_mergeFit" :
				return __( "woocas_mergeFit_label", $this->plugin_name );
				break;
			case "woocas_autoWidth" :
				return __( "woocas_autoWidth_label", $this->plugin_name );
				break;
			case "woocas_startPosition" :
				return __( "woocas_startPosition_label", $this->plugin_name );
				break;
			case "woocas_nav" :
				return __( "woocas_nav_label", $this->plugin_name );
				break;
			case "woocas_navRewind" :
				return __( "woocas_navRewind_label", $this->plugin_name );
				break;
			case "woocas_navText_prev" :
				return __( "woocas_navText_prev_label", $this->plugin_name );
				break;
			case "woocas_navText_next" :
				return __( "woocas_navText_next_label", $this->plugin_name );
				break;
			case "woocas_slideBy" :
				return __( "woocas_slideBy_label", $this->plugin_name );
				break;
			case "woocas_dots" :
				return __( "woocas_dots_label", $this->plugin_name );
				break;
			case "woocas_autoplay" :
				return __( "woocas_autoplay_label", $this->plugin_name );
				break;
			case "woocas_autoplayTimeout" :
				return __( "woocas_autoplayTimeout_label", $this->plugin_name );
				break;
			case "woocas_autoplayHoverPause" :
				return __( "woocas_autoplayHoverPause_label", $this->plugin_name );
				break;
			default :
				return $attribute;
		}
	}

	public function edit_form() {
		?>
	<div id="woocas-admin-tab">
		<h2 class="nav-tab-wrapper">
			<a href="#woocas-responsice-tab" class="nav-tab nav-tab-active">Responsive</a>
			<a href="#general" class="nav-tab">Tab #2</a>
			<a href="#frag2" class="nav-tab">Tab #3</a>
		</h2>
		<div id="woocas-responsice-tab">
			<table class="form-table woocas-responsice-table">
				<tbody>
					<tr>
						<td>
							<label>Options applicate to resolution upper :</label>
							<input type="text" class="small-text" id="woocas-add-breakpoint-value" value="" />
							px
							<input type="button" class="button button-small" value="Add breakpoint" id="woocas-add-breakpoint" />
						</td>
						<td></td>
					</tr>
				</tbody>
			</table>
			<div id="woocas-responsive-base" style="display: none;">
				<h3>
					Resolution >=
					<span></span>
					px
					<a href="#" class="dashicons dashicons-no woocas-responsive-delete"></a>
				</h3>
				<div>
					<input type="hidden" name="breakpoint-id[]" class="breakpoint-id" value="" />
					Add option: <select>
	            <?php foreach ( $this->woocas_responsive_attr_avaible as $index => $value ) { ?>
	              <option value="<?php echo $value ?>"><?php echo $this->get_label($value) ?></option>
	            <?php }?>
	            </select>
					<a href="#" class="woocas-responsive-add-attribute ">Add</a>
					<table class="form-table">
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div id="woocas-responsive-accordion">
				<div id="woocas-responsive-1024" style="display: block;">
					<h3>
						<span></span>
						Resolution &gt;=
						<span>1024</span>
						px
						<a href="1024" class="dashicons dashicons-no woocas-responsive-delete"></a>
					</h3>
					<div>
						<input type="hidden" name="breakpoint-id[]" class="breakpoint-id" value="1024">
						Add option: <select>

							<option value="woocas_loop">woocas_loop_label</option>
							<option value="woocas_center">woocas_center_label</option>
							<option value="woocas_mouseDrag">woocas_mouseDrag_label</option>
							<option value="woocas_touchDrag">woocas_touchDrag_label</option>
							<option value="woocas_pullDrag">woocas_pullDrag_label</option>
							<option value="woocas_freeDrag">woocas_freeDrag_label</option>
							<option value="woocas_margin">woocas_margin_label</option>
							<option value="woocas_stagePadding">woocas_stagePadding_label</option>
							<option value="woocas_merge">woocas_merge_label</option>
							<option value="woocas_mergeFit">woocas_mergeFit_label</option>
							<option value="woocas_autoWidth">woocas_autoWidth_label</option>
							<option value="woocas_autoHeight">woocas_autoHeight</option>
							<option value="woocas_nav">woocas_nav_label</option>
							<option value="woocas_navRewind">woocas_navRewind_label</option>
							<option value="woocas_slideBy">woocas_slideBy_label</option>
							<option value="woocas_dots">woocas_dots_label</option>
							<option value="woocas_dotsEach">woocas_dotsEach</option>
							<option value="woocas_autoplay">woocas_autoplay_label</option>
							<option value="woocas_autoplayTimeout">woocas_autoplayTimeout_label</option>
							<option value="woocas_smartSpeed">woocas_smartSpeed</option>
							<option value="woocas_fluidSpeed">woocas_fluidSpeed</option>
							<option value="woocas_autoplaySpeed">woocas_autoplaySpeed</option>
							<option value="woocas_navSpeed">woocas_navSpeed</option>
							<option value="woocas_dotsSpeed">woocas_dotsSpeed</option>
							<option value="woocas_dragEndSpeed">woocas_dragEndSpeed</option>
							<option value="woocas_responsiveRefreshRate">woocas_responsiveRefreshRate</option>
							<option value="woocas_animateOut">woocas_animateOut</option>
							<option value="woocas_animateIn">woocas_animateIn</option>
							<option value="woocas_fallbackEasing">woocas_fallbackEasing</option>
							<option value="woocas_callbacks">woocas_callbacks</option>
							<option value="woocas_info">woocas_info</option>
						</select>
						<a href="1024" class="woocas-responsive-add-attribute ">Add</a>
						<table class="form-table">
							<tbody>
								<tr class="woocas_items">
									<th scope="row">
										<label for="woocas_items">Items:</label>
									</th>
									<td>
										<legend class="screen-reader-text">
											<span>Items :</span>
										</legend>
										<input type="text" class="" name="responsive[1024]['woocas_items']" value="3">
										<span class="description">The number of items you want to see on the screen.</span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>

		<div class="hidden" id="general">
			<table class="form-table">
				<tbody>
					<tr class="woocas_items">
						<th scope="row">
							<label for="woocas_items"><?php _e( 'Items' ) ?>:</label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Items' ) ?> :</span>
							</legend>
							<input type="text" class="" name="woocas_items" value="<?php echo $this->attrs_array['woocas_items'] ?>" />
							<span class="description"><?php _e( 'The number of items you want to see on the screen.' ) ?></span>
						</td>
					</tr>
					<tr class="woocas_margin">
						<th scope="row">
							<label for="woocas_margin"><?php _e( 'Margin' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span>><?php _e( 'Items Margin Right' ) ?>: </span>
							</legend>
							<input type="text" class="" name="woocas_margin" value="<?php echo $this->attrs_array['woocas_margin'] ?>" />
							<span class="description"><?php _e( 'Margin-right in px on item.' ) ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_loop"><?php _e( 'Loop' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Infinity Loop' ) ?>: </span>
							</legend>
							<select name="woocas_loop">
								<option value="0" <?php echo ( !$this->attrs_array['woocas_loop'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
								<option value="1" <?php echo ( $this->attrs_array['woocas_loop'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
							</select>
							<span class="description"><?php _e( 'Duplicate last and first items to get loop illusion.' )?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_center"><?php _e( 'Center' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Center Item' ) ?>: </span>
							</legend>
							<select name="woocas_center">
								<option value="0" <?php echo ( !$this->attrs_array['woocas_center'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
								<option value="1" <?php echo ( $this->attrs_array['woocas_center'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
							</select>
							<span class="description"><?php _e( 'Works well with even an odd number of items.' ) ?> </span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_mouseDrag"><?php _e( 'Mouse Drag' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Mouse Drag' ) ?>: </span>
							</legend>
							<select name="woocas_mouseDrag">
								<option value="0" <?php echo ( !$this->attrs_array['woocas_mouseDrag'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
								<option value="1" <?php echo ( $this->attrs_array['woocas_mouseDrag'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
							</select>
							<span class="description"><?php _e( 'Mouse drag enabled.' ) ?> </span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_touchDrag"><?php _e( 'Touch Drag' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Touch Drag' ) ?>: </span>
							</legend>
							<select name="woocas_touchDrag">
								<option value="0" <?php echo ( !$this->attrs_array['woocas_touchDrag'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
								<option value="1" <?php echo ( $this->attrs_array['woocas_touchDrag'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
							</select>
							<span class="description"><?php _e( 'Touch drag enabled.' ) ?> </span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_pullDrag"><?php _e( 'Pull Drag' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Pull Drag' ) ?>: </span>
							</legend>
							<select name="woocas_pullDrag">
								<option value="0" <?php echo ( !$this->attrs_array['woocas_pullDrag'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
								<option value="1" <?php echo ( $this->attrs_array['woocas_pullDrag'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
							</select>
							<span class="description"><?php _e( 'Stage pull to edge.' ) ?> </span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_freeDrag"><?php _e( 'Free Drag' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Free Drag' ) ?>: </span>
							</legend>
							<select name="woocas_freeDrag">
								<option value="0" <?php echo ( !$this->attrs_array['woocas_freeDrag'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
								<option value="1" <?php echo ( $this->attrs_array['woocas_freeDrag'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
							</select>
							<span class="description"><?php _e( 'Item pull to edge.' ) ?> </span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_stagePadding"><?php _e( 'Stage Padding' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Stage Padding' ) ?>: </span>
							</legend>
							<input type="text" class="" name="woocas_stagePadding" value="<?php echo $this->attrs_array['woocas_stagePadding'] ?>" />
							<span class="description"><?php _e( 'Padding left and right on stage (can see neighbours).' ) ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_autoWidth"><?php _e( 'Auto Width' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Auto Width' ) ?>: </span>
							</legend>
							<select name="woocas_autoWidth">
								<option value="0" <?php echo ( !$this->attrs_array['woocas_autoWidth'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
								<option value="1" <?php echo ( $this->attrs_array['woocas_autoWidth'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
							</select>
							<span class="description"><?php _e( 'Set non grid content. Try using width style on divs.' ) ?> </span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_startPosition"><?php _e( 'Start position' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Start position' ) ?>: </span>
							</legend>
							<input type="text" class="" name="woocas_startPosition" value="<?php echo $this->attrs_array['woocas_startPosition'] ?>" />
							<span class="description"><?php _e( 'Item number to start' ) ?> </span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_nav"><?php _e( 'Navigation Buttons' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Navigation Buttons' ) ?>: </span>
							</legend>
							<select name="woocas_nav">
								<option value="0" <?php echo ( !$this->attrs_array['woocas_nav'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
								<option value="1" <?php echo ( $this->attrs_array['woocas_nav'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
							</select>
							<span class="description"><?php _e( 'Show next/prev buttons.' ) ?> </span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_navRewind"><?php _e( 'Navigation Button Loop' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Navigation Button Loop' ) ?>: </span>
							</legend>
							<select name="woocas_navRewind">
								<option value="0" <?php echo ( !$this->attrs_array['woocas_navRewind'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
								<option value="1" <?php echo ( $this->attrs_array['woocas_navRewind'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
							</select>
							<span class="description"><?php _e( 'Go to first/last.' ) ?> </span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_navText_prev"><?php _e( 'Label Previous Button' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Label Previous Button' ) ?>: </span>
							</legend>
							<input type="text" class="woocas_navText_prev" name="woocas_navText_prev" value="<?php echo $this->attrs_array['woocas_navText_prev'] ?>" />
							<span class="description"><?php _e( 'If empty, default value is &#x27;next&#x27;' ) ?> </span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_navText_next"><?php _e( 'Label Next Button' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Label Next Button' ) ?>: </span>
							</legend>
							<input type="text" class="woocas_navText_next" name="woocas_navText_next" value="<?php echo $this->attrs_array['woocas_navText_next'] ?>" />
							<span class="description"><?php _e( 'If empty, default value is &#x27;prev&#x27;' ) ?> </span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_slideBy"><?php _e( 'Slide by' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Slide by' ) ?>: </span>
							</legend>
							<input type="text" class="woocas_slideBy" name="woocas_slideBy" value="<?php echo $this->attrs_array['woocas_slideBy'] ?>" />
							<span class="description"><?php _e( 'Navigation slide by x. "Page" string can be set to slide by page.' ) ?> </span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_dots"><?php _e( 'Show dots navigation' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Show dots navigation' ) ?>: </span>
							</legend>
							<select name="woocas_dots">
								<option value="0" <?php echo ( !$this->attrs_array['woocas_dots'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
								<option value="1" <?php echo ( $this->attrs_array['woocas_dots'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_autoplay"><?php _e( 'Autoplay' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Autoplay' ) ?>: </span>
							</legend>
							<select name="woocas_autoplay">
								<option value="0" <?php echo ( !$this->attrs_array['woocas_autoplay'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
								<option value="1" <?php echo ( $this->attrs_array['woocas_autoplay'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_autoplayTimeout"><?php _e( 'Autoplay interval timeout' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Autoplay interval timeout' ) ?>: </span>
							</legend>
							<input type="text" class="woocas_autoplayTimeout" name="woocas_autoplayTimeout" value="<?php echo $this->attrs_array['woocas_autoplayTimeout'] ?>" />
							<span class="description"><?php _e( 'In milliseconds.' ) ?> </span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woocas_autoplayHoverPause"><?php _e( 'Autoplay pause' ) ?>: </label>
						</th>
						<td>
							<legend class="screen-reader-text">
								<span><?php _e( 'Autoplay pause' ) ?>: </span>
							</legend>
							<select name="woocas_autoplayHoverPause">
								<option value="0" <?php echo ( !$this->attrs_array['woocas_autoplayHoverPause'] ) ? 'selected="selected"' : ''?>><?php _e( 'No' )?></option>
								<option value="1" <?php echo ( $this->attrs_array['woocas_autoplayHoverPause'] ) ? 'selected="selected"' : ''?>><?php _e( 'Yes' )?></option>
							</select>
							<span class="description"><?php _e( 'Pause on mouse hover.' ) ?> </span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="hidden" id="frag2">
			<p>#2 - Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
		</div>
	</div>

	<?php
	}
}

