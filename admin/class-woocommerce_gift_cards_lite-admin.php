<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_gift_cards_lite
 * @subpackage Woocommerce_gift_cards_lite/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_gift_cards_lite
 * @subpackage Woocommerce_gift_cards_lite/admin
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Woocommerce_Gift_Cards_Lite_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The object of common class file.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $mwb_common_fun    The current version of this plugin.
	 */
	public $mwb_common_fun;
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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		require_once MWB_WGC_DIRPATH . 'includes/class-woocommerce-gift-cards-common-function.php';
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->mwb_common_fun = new Woocommerce_Gift_Cards_Common_Function();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function mwb_wgm_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_gift_cards_lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_gift_cards_lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_style( 'select2' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce_gift_cards_lite-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
		wp_enqueue_style( 'woocommerce_admin_menu_styles' );

		wp_enqueue_style( 'woocommerce_admin_styles' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function mwb_wgm_enqueue_scripts() {
		$screen = get_current_screen();
		wp_enqueue_script( 'thickbox' );
		if ( isset( $screen->id ) ) {
			$pagescreen = $screen->id;
			if ( 'product' == $pagescreen || 'shop_order' == $pagescreen || 'woocommerce_page_mwb-wgc-setting-lite' == $pagescreen ) {

				$mwb_wgm_general_settings = get_option( 'mwb_wgm_general_settings', false );

				$giftcard_tax_cal_enable = $this->mwb_common_fun->mwb_wgm_get_template_data( $mwb_wgm_general_settings, 'mwb_wgm_general_setting_tax_cal_enable' );

				$mwb_wgc = array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'is_tax_enable_for_gift' => $giftcard_tax_cal_enable,

				);

				wp_enqueue_script( 'mwb_lite_select2', plugin_dir_url( __FILE__ ), 'js/select2.min.js', array( 'jquery' ) );

				wp_register_script( $this->plugin_name . 'clipboard', plugin_dir_url( __FILE__ ) . 'js/clipboard.min.js', array(), '1.2.1' );

				wp_enqueue_script( $this->plugin_name . 'clipboard' );
				wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce_gift_cards_lite-admin.js', array( 'jquery', 'mwb_lite_select2', 'wc-enhanced-select', 'wp-color-picker' ), '1.2.1', true );

				wp_localize_script( $this->plugin_name, 'mwb_wgc', $mwb_wgc );

				wp_enqueue_script( $this->plugin_name );

				wp_register_script( 'woocommerce_admin', WC()->plugin_url() . '/assets/js/admin/woocommerce_admin.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip' ), WC_VERSION, false );
				wp_register_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip.js', array( 'jquery' ), WC_VERSION, false );
				$locale  = localeconv();
				$decimal = isset( $locale['decimal_point'] ) ? $locale['decimal_point'] : '.';
				$params = array(
					/* translators: %s: decimal */
					'i18n_decimal_error'                => sprintf( __( 'Please enter in decimal (%s) format without thousand separators.', 'woocommerce_gift_cards_lite' ), $decimal ),
					/* translators: %s: price decimal separator */
					'i18n_mon_decimal_error'            => sprintf( __( 'Please enter in monetary decimal (%s) format without thousand separators and currency symbols.', 'woocommerce_gift_cards_lite' ), wc_get_price_decimal_separator() ),
					'i18n_country_iso_error'            => __( 'Please enter in country code with two capital letters.', 'woocommerce_gift_cards_lite' ),
					'i18_sale_less_than_regular_error'  => __( 'Please enter in a value less than the regular price.', 'woocommerce_gift_cards_lite' ),
					'decimal_point'                     => $decimal,
					'mon_decimal_point'                 => wc_get_price_decimal_separator(),
					'strings' => array(
						'import_products' => __( 'Import', 'woocommerce_gift_cards_lite' ),
						'export_products' => __( 'Export', 'woocommerce_gift_cards_lite' ),
					),
					'urls' => array(
						'import_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_importer' ) ),
						'export_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_exporter' ) ),
					),
				);

				wp_localize_script( 'woocommerce_admin', 'woocommerce_admin', $params );
				wp_enqueue_script( 'woocommerce_admin' );
				wp_enqueue_script( 'media-upload' );
			}
		}

		// enqueue script for admin notices.
		$mwb_wgm_notice = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'mwb_wgm_nonce' => wp_create_nonce( 'mwb-wgm-verify-notice-nonce' ),
		);
		wp_register_script( $this->plugin_name . 'admin-notice', plugin_dir_url( __FILE__ ) . 'js/mwb-wgm-gift-card-notices.js', array( 'jquery' ), '1.2.1', false );

		wp_localize_script( $this->plugin_name . 'admin-notice', 'mwb_wgm_notice', $mwb_wgm_notice );
		wp_enqueue_script( $this->plugin_name . 'admin-notice' );
	}

	/**
	 * Add a submenu inside the Woocommerce Menu Page
	 *
	 * @since 1.0.0
	 * @name mwb_wgm_admin_menu()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_admin_menu() {
		add_submenu_page( 'woocommerce', __( 'Gift Card', 'woocommerce_gift_cards_lite' ), __( 'Gift Card', 'woocommerce_gift_cards_lite' ), 'manage_options', 'mwb-wgc-setting-lite', array( $this, 'mwb_wgm_admin_setting' ) );
		// hooks to add sub menu.
		do_action( 'mwb_wgm_admin_sub_menu' );
	}

	/**
	 * Including a File for displaying the required setting page for setup the plugin
	 *
	 * @since 1.0.0
	 * @name mwb_wgm_admin_setting()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_admin_setting() {
		include_once MWB_WGC_DIRPATH . '/admin/partials/woocommerce-gift-cards-lite-admin-display.php';
	}

	/**
	 * Create a custom Product Type for Gift Card
	 *
	 * @since 1.0.0
	 * @name mwb_wgm_gift_card_product()
	 * @param array $types product types.
	 * @return $types.
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_gift_card_product( $types ) {
		$mwb_wgc_enable = mwb_wgm_giftcard_enable();
		if ( $mwb_wgc_enable ) {
			$types['wgm_gift_card'] = __( 'Gift Card', 'woocommerce_gift_cards_lite' );
		}
		return $types;
	}

	/**
	 * Provide multiple Price variations for Gift Card Product
	 *
	 * @since 1.0.0
	 * @name mwb_wgm_get_pricing_type()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_get_pricing_type() {
		$pricing_options = array(
			'mwb_wgm_default_price' => __( 'Default Price', 'woocommerce_gift_cards_lite' ),
			'mwb_wgm_range_price' => __( 'Price Range', 'woocommerce_gift_cards_lite' ),
			'mwb_wgm_selected_price' => __( 'Selected Price', 'woocommerce_gift_cards_lite' ),
			'mwb_wgm_user_price' => __( 'User Price', 'woocommerce_gift_cards_lite' ),
		);
		return apply_filters( 'mwb_wgm_pricing_type', $pricing_options );
	}

	/**
	 * Add some required fields (data-tabs) for Gift Card product
	 *
	 * @since 1.0.0
	 * @name mwb_wgm_woocommerce_product_options_general_product_data()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_woocommerce_product_options_general_product_data() {
		global $post;
		$product_id = $post->ID;
		if ( isset( $product_id ) ) {
			if ( ! current_user_can( 'edit_post', $product_id ) ) {
				return;
			}
		}
		$mwb_wgm_pricing = get_post_meta( $product_id, 'mwb_wgm_pricing', true );
		$selected_pricing = isset( $mwb_wgm_pricing['type'] ) ? $mwb_wgm_pricing['type'] : false;
		$giftcard_enable = mwb_wgm_giftcard_enable();

		$default_price = '';
		$from = '';
		$to = '';
		$price = '';
		$default_price  = isset( $mwb_wgm_pricing['default_price'] ) ? $mwb_wgm_pricing['default_price'] : 0;
		$selectedtemplate  = isset( $mwb_wgm_pricing['template'] ) ? $mwb_wgm_pricing['template'] : false;

		$default_selected = isset( $mwb_wgm_pricing['by_default_tem'] ) ? $mwb_wgm_pricing['by_default_tem'] : false;
		if ( $selected_pricing ) {
			switch ( $selected_pricing ) {
				case 'mwb_wgm_range_price':
					$from = isset( $mwb_wgm_pricing['from'] ) ? $mwb_wgm_pricing['from'] : 0;
					$to = isset( $mwb_wgm_pricing['to'] ) ? $mwb_wgm_pricing['to'] : 0;
					break;
				case 'mwb_wgm_selected_price':
					$price = isset( $mwb_wgm_pricing['price'] ) ? $mwb_wgm_pricing['price'] : 0;
					break;
				default:
					// Nothing for default.
			}
		}
		if ( $giftcard_enable ) {
			$src = MWB_WGC_URL . 'assets/images/loading.gif';
			?>
			<div class="options_group show_if_wgm_gift_card"><div id="mwb_wgm_loader" style="display: none;">
				<img src="<?php echo esc_url( $src ); ?>">
			</div>
			<?php
			woocommerce_wp_text_input(
				array(
					'id' => 'mwb_wgm_default',
					'value' => "$default_price",
					'label' => __( 'Default Price', 'woocommerce_gift_cards_lite' ),
					'placeholder' => wc_format_localized_price( 0 ),
					'description' => __( 'Gift card default price.', 'woocommerce_gift_cards_lite' ),
					'data_type' => 'price',
					'desc_tip' => true,
				)
			);
			woocommerce_wp_select(
				array(
					'id' => 'mwb_wgm_pricing',
					'value' => "$selected_pricing",
					'label' => __( 'Pricing type', 'woocommerce_gift_cards_lite' ),
					'options' => $this->mwb_wgm_get_pricing_type(),
				)
			);
			 // Range Price.
			 // StartFrom.
			woocommerce_wp_text_input(
				array(
					'id' => 'mwb_wgm_from_price',
					'value' => "$from",
					'label' => __( 'From Price', 'woocommerce_gift_cards_lite' ),
					'placeholder' => wc_format_localized_price( 0 ),
					'description' => __( 'Gift card price range start from.', 'woocommerce_gift_cards_lite' ),
					'data_type' => 'price',
					'desc_tip' => true,
				)
			);
			 // EndTo.
			woocommerce_wp_text_input(
				array(
					'id' => 'mwb_wgm_to_price',
					'value' => "$to",
					'label' => __( 'To Price', 'woocommerce_gift_cards_lite' ),
					'placeholder' => wc_format_localized_price( 0 ),
					'description' => __( 'Gift card price range end to.', 'woocommerce_gift_cards_lite' ),
					'data_type' => 'price',
					'desc_tip' => true,
				)
			);
			 // Selected Price.
			woocommerce_wp_textarea_input(
				array(
					'id' => 'mwb_wgm_selected_price',
					'value' => "$price",
					'label' => __( 'Price', 'woocommerce_gift_cards_lite' ),
					'desc_tip' => 'true',
					'description' => __( 'Enter an price using seperator |. Ex : (10 | 20)', 'woocommerce_gift_cards_lite' ),
					'placeholder' => '10|20|30',
				)
			);
			// Regular Price.
			?>
			<p class="form-field mwb_wgm_default_price_field">
				<label for="mwb_wgm_default_price_field"><b><?php esc_html_e( 'Instruction', 'woocommerce_gift_cards_lite' ); ?></b></label>
				<span class="description"><?php esc_html_e( 'WooCommerce Product regular price is used as a gift card price.', 'woocommerce_gift_cards_lite' ); ?></span>
			</p>
			
			<p class="form-field mwb_wgm_user_price_field ">
				<label for="mwb_wgm_user_price_field"><b><?php esc_html_e( 'Instruction', 'woocommerce_gift_cards_lite' ); ?></b></label>
				<span class="description"> <?php esc_html_e( 'User can purchase any amount of Gift Card.', 'woocommerce_gift_cards_lite' ); ?></span>
			</p>

			<?php
			$is_customizable = get_post_meta( $product_id, 'woocommerce_customizable_giftware', true );
			$mwb_get_pro_templates = get_option( 'mwb_uwgc_templateid', array() );
			$mwb_get_lite_templates = $this->mwb_wgm_get_all_lite_templates();
			if ( empty( $is_customizable ) ) {
				?>
				<p class="form-field mwb_wgm_email_template">
					<label class ="mwb_wgm_email_template" for="mwb_wgm_email_template"><?php esc_html_e( 'Email Template', 'woocommerce_gift_cards_lite' ); ?></label>
					<?php
					if ( mwb_uwgc_pro_active() ) {
						?>
						<select id="mwb_wgm_email_template" multiple="multiple" name="mwb_wgm_email_template[]" class="mwb_wgm_email_template">
						<?php
					} else {
						?>
						<select id="mwb_wgm_email_template" name="mwb_wgm_email_template[]" class="mwb_wgm_email_template">
						<?php
					}
					$args = array(
						'post_type' => 'giftcard',
						'posts_per_page' => -1,
					);
					$loop = new WP_Query( $args );
					$template = array();
					foreach ( $loop->posts as $key => $value ) {
						$template_id = $value->ID;
						$template_title = $value->post_title;
						$template[ $template_id ] = $template_title;
						$tempselect = '';
						if ( mwb_uwgc_pro_active() ) {
							if ( is_array( $selectedtemplate ) && ( null != $selectedtemplate ) && in_array( $template_id, $selectedtemplate ) ) {
								$tempselect = "selected='selected'";
							}
							?>
							<option value="<?php echo esc_attr( $template_id ); ?>"<?php echo esc_attr( $tempselect ); ?>><?php echo esc_attr( $template_title ); ?></option>
							<?php
						} else {
							if ( in_array( $template_title, $mwb_get_lite_templates ) ) {
								if ( is_array( $selectedtemplate ) && ! empty( $selectedtemplate ) ) {
									if ( '1' < count( $selectedtemplate ) ) {
										if ( ! empty( $mwb_get_pro_templates ) ) {
											$mwb_get_lite_temp = array_diff( $selectedtemplate, $mwb_get_pro_templates );
											$mwb_index = array_keys( $mwb_get_lite_temp )[0];
											if ( 0 !== count( $mwb_get_lite_temp ) ) {
												$choosed_temp = $mwb_get_lite_temp[ $mwb_index ];
											}
										} else {
											$choosed_temp = $selectedtemplate[0];
										}
									} else {
										$choosed_temp = $selectedtemplate[0];
									}
								}
								if ( $choosed_temp == $template_id ) {
									$tempselect = "selected='selected'";
								}
								if ( ! in_array( $template_id, $mwb_get_pro_templates ) ) {
									?>
									<option value="<?php echo esc_attr( $template_id ); ?>"<?php echo esc_attr( $tempselect ); ?>><?php echo esc_attr( $template_title ); ?></option>
									<?php
								}
							}
						}
					}
					?>
					</select>
				</p>
				<?php
			}
			wp_nonce_field( 'mwb_wgm_lite_nonce', 'mwb_wgm_product_nonce_field' );
			do_action( 'mwb_wgm_giftcard_product_type_field', $product_id );
			echo '</div>';
		}
	}

	/**
	 * Saves the all required details for each product
	 *
	 * @since 1.0.0
	 * @name mwb_wgm_save_post()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_save_post() {
		global $post;
		if ( isset( $post->ID ) ) {
			if ( ! current_user_can( 'edit_post', $post->ID ) ) {
				return;
			}
			$product_id = $post->ID;
			$product = wc_get_product( $product_id );
			if ( isset( $product ) && is_object( $product ) ) {
				if ( $product->get_type() == 'wgm_gift_card' ) {
					if ( isset( $_POST['mwb_wgm_product_nonce_field'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mwb_wgm_product_nonce_field'] ) ), 'mwb_wgm_lite_nonce' ) ) {
						return;
					}
					$general_settings = get_option( 'mwb_wgm_general_settings', array() );
					$mwb_wgm_categ_enable = $this->mwb_common_fun->mwb_wgm_get_template_data( $general_settings, 'mwb_uwgc_general_setting_categ_enable' );
					if ( empty( $mwb_wgm_categ_enable ) ) {
						$term = __( 'Gift Card', 'woocommerce_gift_cards_lite' );
						$taxonomy = 'product_cat';
						$term_exist = term_exists( $term, $taxonomy );
						if ( 0 == $term_exist || null == $term_exist ) {
							$args['slug'] = 'mwb_wgm_giftcard';
							$term_exist = wp_insert_term( $term, $taxonomy, $args );
						}
						wp_set_object_terms( $product_id, 'wgm_gift_card', 'product_type' );
						wp_set_post_terms( $product_id, $term_exist, $taxonomy );
					}
					$mwb_wgm_pricing = array();
					$selected_pricing = isset( $_POST['mwb_wgm_pricing'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_wgm_pricing'] ) ) : false;
					if ( $selected_pricing ) {
						$default_price = isset( $_POST['mwb_wgm_default'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_wgm_default'] ) ) : 0;
						update_post_meta( $product_id, '_regular_price', $default_price );
						update_post_meta( $product_id, '_price', $default_price );
						$mwb_wgm_pricing['default_price'] = $default_price;
						$mwb_wgm_pricing['type'] = $selected_pricing;
						if ( ! isset( $_POST['mwb_wgm_email_template'] ) || empty( $_POST['mwb_wgm_email_template'] ) ) {
							$args = array(
								'post_type' => 'giftcard',
								'posts_per_page' => -1,
							);
							$loop = new WP_Query( $args );
							$template = array();
							if ( $loop->have_posts() ) :
								while ( $loop->have_posts() ) :
									$loop->the_post();
									$template_id = $loop->post->ID;
									$template[] = $template_id;
								endwhile;
							endif;

							$pro_template = get_option( 'mwb_uwgc_templateid', array() );
							$temp_array = array();
							if ( ! mwb_uwgc_pro_active() && is_array( $pro_template ) && ! empty( $pro_template ) ) {
								foreach ( $template as $value ) {
									if ( ! in_array( $value, $pro_template ) ) {
										$temp_array[] = $value;
									}
								}
								if ( isset( $temp_array ) && ! empty( $temp_array ) ) {
									$mwb_wgm_pricing['template'] = array( $temp_array[0] );
								}
							} else {
								$mwb_wgm_pricing['template'] = array( $template[0] );
							}
						} else {
							$mwb_wgm_pricing['template'] = map_deep( wp_unslash( $_POST['mwb_wgm_email_template'] ), 'sanitize_text_field' );
						}
						if ( ! isset( $_POST['mwb_wgm_email_defualt_template'] ) || empty( $_POST['mwb_wgm_email_defualt_template'] ) ) {
							$mwb_wgm_pricing['by_default_tem'] = $mwb_wgm_pricing['template'];
						} else {
							$mwb_wgm_pricing['by_default_tem'] = sanitize_text_field( wp_unslash( $_POST['mwb_wgm_email_defualt_template'] ) );
						}
						switch ( $selected_pricing ) {
							case 'mwb_wgm_range_price':
								$from = isset( $_POST['mwb_wgm_from_price'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_wgm_from_price'] ) ) : 0;
								$to = isset( $_POST['mwb_wgm_to_price'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_wgm_to_price'] ) ) : 0;
								$mwb_wgm_pricing['type'] = $selected_pricing;
								$mwb_wgm_pricing['from'] = $from;
								$mwb_wgm_pricing['to'] = $to;
								break;
							case 'mwb_wgm_selected_price':
								$price = isset( $_POST['mwb_wgm_selected_price'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_wgm_selected_price'] ) ) : 0;
								$mwb_wgm_pricing['type'] = $selected_pricing;
								$mwb_wgm_pricing['price'] = $price;
								break;

							case 'mwb_wgm_user_price':
								$mwb_wgm_pricing['type'] = $selected_pricing;
								break;
							default:
								// nothing for default.
						}
					}
					do_action( 'mwb_wgm_product_pricing', $mwb_wgm_pricing );
					$mwb_wgm_pricing = apply_filters( 'mwb_wgm_product_pricing', $mwb_wgm_pricing );
					update_post_meta( $product_id, 'mwb_wgm_pricing', $mwb_wgm_pricing );
					do_action( 'mwb_wgm_giftcard_product_type_save_fields', $product_id );
				}
			}
		}
	}

	/**
	 * Hides some of the tabs if the Product is Gift Card
	 *
	 * @since 1.0.0
	 * @name mwb_wgm_woocommerce_product_data_tabs()
	 * @param array $tabs product tabs.
	 * @return $tabs.
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_woocommerce_product_data_tabs( $tabs ) {
		if ( isset( $tabs ) && ! empty( $tabs ) ) {
			foreach ( $tabs as $key => $tab ) {
				if ( 'general' != $key && 'advanced' != $key && 'shipping' != $key ) {
					$tabs[ $key ]['class'][] = 'hide_if_wgm_gift_card';
				}
			}
			$tabs = apply_filters( 'mwb_wgm_product_data_tabs', $tabs );
		}
		return $tabs;
	}

	/**
	 * Add the Gift Card Coupon code as an item meta for each Gift Card Order
	 *
	 * @since 1.0.0
	 * @name mwb_wgm_woocommerce_after_order_itemmeta()
	 * @param int   $item_id item id.
	 * @param array $item item.
	 * @param array $_product product.
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_woocommerce_after_order_itemmeta( $item_id, $item, $_product ) {
		if ( ! current_user_can( 'edit_shop_orders' ) ) {
			return;
		}
		$mwb_wgc_enable = mwb_wgm_giftcard_enable();
		if ( $mwb_wgc_enable ) {
			if ( isset( $_GET['post'] ) ) {
				$order_id = sanitize_text_field( wp_unslash( $_GET['post'] ) );
				$order = new WC_Order( $order_id );
				$order_status = $order->get_status();
				if ( 'completed' == $order_status || 'processing' == $order_status ) {
					if ( null != $_product ) {
						$product_id = $_product->get_id();
						if ( isset( $product_id ) && ! empty( $product_id ) ) {
							$product_types = wp_get_object_terms( $product_id, 'product_type' );
							if ( isset( $product_types[0] ) ) {
								$product_type = $product_types[0]->slug;
								if ( 'wgm_gift_card' == $product_type ) {
									$giftcoupon = get_post_meta( $order_id, "$order_id#$item_id", true );
									if ( empty( $giftcoupon ) ) {
										$giftcoupon = get_post_meta( $order_id, "$order_id#$product_id", true );
									}
									if ( is_array( $giftcoupon ) && ! empty( $giftcoupon ) ) {
										?>
										<p style="margin:0;"><b><?php esc_html_e( 'Gift Coupon', 'woocommerce_gift_cards_lite' ); ?> :</b>
											<?php
											foreach ( $giftcoupon as $key => $value ) {
												?>
												<span style="background: rgb(0, 115, 170) none repeat scroll 0% 0%; color: white; padding: 1px 5px 1px 6px; font-weight: bolder; margin-left: 10px;"><?php echo esc_attr( $value ); ?></span>
												<?php
											}
											?>
										</p>
										<?php
									}
									do_action( 'mwb_wgm_after_order_itemmeta', $item_id, $item, $_product );
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Hides the non-required Item Meta
	 *
	 * @since 1.0.0
	 * @name mwb_wgm_woocommerce_hidden_order_itemmeta()
	 * @param array $order_items order items.
	 * @return $order_items.
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_woocommerce_hidden_order_itemmeta( $order_items ) {
		if ( ! current_user_can( 'edit_shop_orders' ) ) {
			return;
		}
		array_push( $order_items, 'Delivery Method', 'Original Price', 'Selected Template' );
		$order_items = apply_filters( 'mwb_wgm_giftcard_hidden_order_itemmeta', $order_items );
		return $order_items;
	}

	/**
	 * Create custom post name Giftcard for creating Giftcard Template
	 *
	 * @name mwb_wgm_giftcard_custompost
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgm_giftcard_custom_post() {
		$labels = array(
			'name'               => esc_html__( 'Gift Cards', 'woocommerce_gift_cards_lite' ),
			'singular_name'      => esc_html__( 'Gift Card', 'woocommerce_gift_cards_lite' ),
			'menu_name'          => esc_html__( 'Gift Cards', 'woocommerce_gift_cards_lite' ),
			'name_admin_bar'     => esc_html__( 'Gift Card', 'woocommerce_gift_cards_lite' ),
			'add_new'            => esc_html__( 'Add New', 'woocommerce_gift_cards_lite' ),
			'add_new_item'       => esc_html__( 'Add New Gift Card', 'woocommerce_gift_cards_lite' ),
			'new_item'           => esc_html__( 'New Gift Card', 'woocommerce_gift_cards_lite' ),
			'edit_item'          => esc_html__( 'Edit Gift Card', 'woocommerce_gift_cards_lite' ),
			'view_item'          => esc_html__( 'View Gift Card', 'woocommerce_gift_cards_lite' ),
			'all_items'          => esc_html__( 'All Gift Cards', 'woocommerce_gift_cards_lite' ),
			'search_items'       => esc_html__( 'Search Gift Cards', 'woocommerce_gift_cards_lite' ),
			'parent_item_colon'  => esc_html__( 'Parent Gift Cards:', 'woocommerce_gift_cards_lite' ),
			'not_found'          => esc_html__( 'No giftcards found.', 'woocommerce_gift_cards_lite' ),
			'not_found_in_trash' => esc_html__( 'No giftcards found in Trash.', 'woocommerce_gift_cards_lite' ),
		);
		$mwb_wgm_template = array(
			'create_posts' => false,
		);
		$mwb_wgm_template = apply_filters( 'mwb_wgm_template_capabilities', $mwb_wgm_template );
		$args = array(
			'labels'             => $labels,
			'description'        => esc_html__( 'Description.', 'woocommerce_gift_cards_lite' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'giftcard' ),
			'capability_type'    => 'post',
			'capabilities'       => $mwb_wgm_template,
			'map_meta_cap'       => true,
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-format-gallery',
			'supports'           => array( 'title', 'editor', 'thumbnail' ),
		);
		register_post_type( 'giftcard', $args );
	}

	/**
	 * This function is to add meta field like field for instruction how to use shortcode in email template
	 *
	 * @name mwb_wgm_edit_form_after_title
	 * @param object $post post.
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgm_edit_form_after_title( $post ) {
		$mwb_wgm_post_type = get_post_type( $post );
		if ( isset( $mwb_wgm_post_type ) && 'giftcard' == $mwb_wgm_post_type ) {
			?>
				<div class="postbox" id="mwb_wgm_mail_instruction" style="display: block;">
					<h2 class="mwb_wgm_handle"><span><?php esc_html_e( 'Instruction for using Shortcode', 'woocommerce_gift_cards_lite' ); ?></span></h2>
					<div class="mwb_wgm_inside">
						<table  class="form-table">
							<tr>
								<th><?php esc_html_e( 'SHORTCODE', 'woocommerce_gift_cards_lite' ); ?></th>
								<th><?php esc_html_e( 'DESCRIPTION.', 'woocommerce_gift_cards_lite' ); ?></th>			
							</tr>
							<tr>
								<td>[LOGO]</td>
								<td><?php esc_html_e( 'Replace with logo of company on email template.', 'woocommerce_gift_cards_lite' ); ?></td>			
							</tr>
							<tr>
								<td>[TO]</td>
								<td><?php esc_html_e( 'Replace with email of user to which giftcard send.', 'woocommerce_gift_cards_lite' ); ?></td>
							</tr>
							<tr>
								<td>[FROM]</td>
								<td><?php esc_html_e( 'Replace with email/name of the user who send the giftcard.', 'woocommerce_gift_cards_lite' ); ?></td>
							</tr>
							<tr>
								<td>[MESSAGE]</td>
								<td><?php esc_html_e( 'Replace with Message of user who send the giftcard.', 'woocommerce_gift_cards_lite' ); ?></td>
							</tr>
							<tr>
								<td>[AMOUNT]</td>
								<td><?php esc_html_e( 'Replace with Giftcard Amount.', 'woocommerce_gift_cards_lite' ); ?></td>
							</tr>
							<tr>
								<td>[COUPON]</td>
								<td><?php esc_html_e( 'Replace with Giftcard Coupon Code.', 'woocommerce_gift_cards_lite' ); ?></td>
							</tr>
							<tr>
								<td>[DEFAULTEVENT]</td>
								<td><?php esc_html_e( 'Replace with Default event image set on Setting.', 'woocommerce_gift_cards_lite' ); ?></td>
							</tr>
							<tr>
								<td>[EXPIRYDATE]</td>
								<td><?php esc_html_e( 'Replace with Giftcard Expiry Date.', 'woocommerce_gift_cards_lite' ); ?></td>
							</tr>
						<?php
						do_action( 'mwb_wgm_template_custom_shortcode' );
						?>
						</table>
					</div>
				</div>
				<?php
		}
	}

	/**
	 * Added Mothers Day Template
	 *
	 * @name mwb_wgm_mothers_day_template
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgm_mothers_day_template() {

		$mwb_wgm_template = get_option( 'mwb_wgm_new_mom_template', '' );
		// $mwb_wgm_template_already_exist = get_option( 'mwb_gw_new_mom_temp', false );
		// if ( false == $mwb_wgm_template_already_exist ) {
		if ( empty( $mwb_wgm_template ) ) {
			update_option( 'mwb_wgm_new_mom_template', true );
			$filename = array( MWB_WGC_URL . 'assets/images/mom.png' );

			if ( is_array( $filename ) && ! empty( $filename ) ) {
				foreach ( $filename as $key => $value ) {
					$upload_file = wp_upload_bits( basename( $value ), null, file_get_contents( $value ) );
					if ( ! $upload_file['error'] ) {
						$filename = $upload_file['file'];
						// The ID of the post this attachment is for.

						$parent_post_id = 0;

						// Check the type of file. We'll use this as the 'post_mime_type'.
						$filetype = wp_check_filetype( basename( $filename ), null );
						// Get the path to the upload directory.
						$wp_upload_dir = wp_upload_dir();
						// Prepare an array of post data for the attachment.
						$attachment = array(
							'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
							'post_mime_type' => $filetype['type'],
							'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),

							'post_status'    => 'inherit',
						);
						// Insert the attachment.

						$attach_id = wp_insert_attachment( $attachment, $filename, 0 );
						// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
						require_once( ABSPATH . 'wp-admin/includes/image.php' );

						// Generate the metadata for the attachment, and update the database record.
						$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

						wp_update_attachment_metadata( $attach_id, $attach_data );
						$arr[] = $attach_id;

					}
				}
			}
			$mwb_wgm_new_mom_template = '<div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">(Optional) This text will appear in the inbox preview, but not the email body.</div><table class="email-container table-wrap" style="margin: auto;" role="presentation" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#efefef;"><tbody><tr><td dir="ltr" style="padding: 10px;" align="center" bgcolor="#efefef" width="100%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="logo-content-wrap"><tbody><tr><td class="stack-column-center logo-wrap" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td dir="ltr" style="padding: 0px 25px; padding-left: 0;" valign="top"></td></tr></tbody></table></td><td class="stack-column-center content-wrap" style="" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; line-height: 20px; color: #ffffff; text-align: right !important; padding: 0px 10px;" valign="top"><span class="mwb_receiver" style="color: #535151; font-size: 14px; line-height: 18px; display:block;">From- [FROM]</span><span style="color: #535151; font-size: 14px; line-height: 18px; display:block;">TO- [TO]</span></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><table class="email-container table-wrap" style="margin: auto;" role="presentation" border="0" width="600" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td dir="ltr" style="padding-top: 15px;" align="center" valign="top" bgcolor="#00897B" width="100%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="img-content-wrap"><tbody><tr><td class="stack-column-center" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td dir="ltr" style="padding: 0px 25px; padding-left: 0;" valign="top"><span class="img-wrap">[FEATUREDIMAGE]</span></td></tr></tbody></table></td><td class="stack-column-center" style="vertical-align: top;" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #ffffff; padding: 0px 30px; text-align: left; " valign="top"><p style="color: rgb(255, 255, 255); font-size: 46px; line-height: 60px; margin-top: 15px; margin-bottom: 15px;">I LOVE YOU MOM</p></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td class="mwb_coupon_div" dir="ltr" align="center" valign="top" bgcolor="#fff" width="100%" style="position: relative;"><span class="back_bubble_img">[BACK]</span><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="stack-column-center" style="vertical-align: top;" width="50%"><table class="mwb_mid_table" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center" style="position:relative; z-index:999;"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; line-height: 20px; color: #ffffff; padding: 0px 30px; text-align: left; background-color: #efefef;" valign="top"><p class="mwb_message" style="text-align: center; line-height: 25px;white-space: pre-line; font-size: 16px; padding: 20px;">[MESSAGE]</p></td></tr></tbody></table></td></tr><tr><td class="mwb_coupon_code" style="padding: 15px 10px; font-size: 26px; text-transform: uppercase; text-align: center; font-weight: bold; color: rgb(39, 39, 39); font-family: sans-serif;"><p style="letter-spacing: 1px; padding: 10px 10px; margin: 0px; text-transform: uppercase; text-align: center; color: #00897b; font-weight: bold; font-size: 13px;">coupon code</p>[COUPON]<p style="letter-spacing: 1px; padding: 15px 10px; margin: 0px; text-transform: uppercase; text-align: center; color: #00897b; font-weight: bold; font-size: 13px;">ED:[EXPIRYDATE]</p></td></tr></tbody></table></td></tr><tr><td dir="ltr" style="padding-top: 12px; padding-bottom: 12px; background-color: #efefef;" align="center" valign="top" bgcolor="#fff" width="100%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="stack-column-center" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td dir="ltr" style="padding: 0px 25px; padding-right: 0;" valign="top"><p style="font-family: sans-serif; font-size: 25px; font-weight: bold; margin: 0px; padding: 5px; color: #272727; text-align: right;">[AMOUNT]</p></td></tr></tbody></table></td><td class="stack-column-center" style="vertical-align: top;" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #ffffff; padding: 0px 30px; text-align: left; margin-top: 15px;" class="center-on-narrow arrow-img" valign="top">[ARROWIMAGE]</td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><table role="presentation" border="0" cellspacing="0" cellpadding="0" style="position:relative; z-index:999; background: rgb(0, 137, 123) none repeat scroll 0% 0%; color: rgb(255, 255, 255);" width="600" class="table-wrap footer-wrap"><tbody><tr><td class="mwb_disclaimer" style="padding: 10px; text-align: center; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly;"><p style="font-weight: bold; padding-top: 15px; padding-bottom: 15px; font-size: 16px;">[DISCLAIMER]</p></td></tr></tbody></table><style>.mwb_mid_table {position: relative;z-index: 999;}.back_bubble_img img {width: 100%;}.img-wrap img {width: 100%;}.mwb_coupon_div {position: relative;}.mwb_coupon_code {position: relative; z-index: 99;}.mwb_message {color: rgb(21, 21, 21);}.mwb_disclaimer {background: rgb(0, 137, 123) none repeat scroll 0% 0%;color: rgb(255, 255, 255);}.mwb_receiver { display: block;}.img-wrap > img{width:100%;}.back_bubble_img{bottom: 0;content: "";left: 0;margin: 0 auto;position: absolute;right: 0;}.back_bubble_img >img{width:100%;}@media screen and (max-width: 600px){.email-container{width: 100% !important;margin: auto !important;}/* What it does: Forces elements to resize to the full width of their container. Useful for resizing images beyond their max-width. */.fluid{max-width: 90% !important;height: auto !important;margin-left: auto !important;margin-right: auto !important;}/* What it does: Forces table cells into full-width rows. */<br/>.stack-column,.stack-column-center{display: block !important;width: 100% !important;max-width: 100% !important;direction: ltr !important;}/* And center justify these ones. */.stack-column-center{text-align: center !important;}/* What it does: Generic utility class for centering. Useful for images, buttons, and nested tables. */.center-on-narrow{text-align: center !important;display: block !important;margin-left: auto !important;margin-right: auto !important;float: none !important;}table.center-on-narrow{display: inline-block !important;}.footer-wrap{width:100%;}}@media screen and (max-width: 500px){.img-content-wrap .stack-column-center{display: block;width: 100%;}.table-wrap{width:100%;}.logo-content-wrap .content-wrap{width:70%;}.logo-content-wrap .logo-wrap{width:30%;}.center-on-narrow.arrow-img{padding: 0 !important;}}html,body{margin: 0 auto !important;padding: 0 !important;height: 100% !important;width: 100% !important;}/* What it does: Stops email clients resizing small text. */*{-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;}/* What is does: Centers email on Android 4.4 */div[style*="margin: 16px 0"]{margin:0 !important;}/* What it does: Stops Outlook from adding extra spacing to tables. */table,td{mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;}/* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */table{border-spacing: 0 !important;border-collapse: collapse !important;table-layout: fixed !important;margin: 0 auto !important;}table table table{table-layout: auto;}/* What it does: Uses a better rendering method when resizing images in IE. */img{-ms-interpolation-mode:bicubic;}/* What it does: A work-around for iOS meddling in triggered links. */.mobile-link--footer a,a[x-apple-data-detectors]{color:inherit !important;text-decoration: underline !important;}/* What it does: Prevents underlining the button text in Windows 10 */.button-link{text-decoration: none !important;}.button-td,.button-a{transition: all 100ms ease-in;}.button-td:hover,.button-a:hover{background: #555555 !important;border-color: #555555 !important;}</style>';

			$gifttemplate_new = array(
				'post_title' => __( 'Love You Mom', 'woocommerce_gift_cards_lite' ),
				'post_content' => $mwb_wgm_new_mom_template,
				'post_status' => 'publish',
				'post_author' => get_current_user_id(),
				'post_type'     => 'giftcard',
			);
			$parent_post_id = wp_insert_post( $gifttemplate_new );
			set_post_thumbnail( $parent_post_id, $arr[0] );
		}
		// }
	}

	/**
	 * Added New Template
	 *
	 * @name mwb_wgm_new_template
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgm_new_template() {

		$mwb_wgm_template = get_option( 'mwb_wgm_gift_for_you', '' );
		if ( empty( $mwb_wgm_template ) ) {
			update_option( 'mwb_wgm_gift_for_you', true );
			$filename = array( MWB_WGC_URL . 'assets/images/giftcard.jpg' );
			if ( isset( $filename ) && is_array( $filename ) && ! empty( $filename ) ) {
				foreach ( $filename as $key => $value ) {
					$upload_file = wp_upload_bits( basename( $value ), null, file_get_contents( $value ) );
					if ( ! $upload_file['error'] ) {
						$filename = $upload_file['file'];
						// The ID of the post this attachment is for.

						$parent_post_id = 0;

						// Check the type of file. We'll use this as the 'post_mime_type'.
						$filetype = wp_check_filetype( basename( $filename ), null );
						// Get the path to the upload directory.
						$wp_upload_dir = wp_upload_dir();
						// Prepare an array of post data for the attachment.
						$attachment = array(
							'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
							'post_mime_type' => $filetype['type'],
							'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),

							'post_status'    => 'inherit',
						);
						// Insert the attachment.

						$attach_id = wp_insert_attachment( $attachment, $filename, 0 );
						// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
						require_once( ABSPATH . 'wp-admin/includes/image.php' );

						// Generate the metadata for the attachment, and update the database record.
						$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

						wp_update_attachment_metadata( $attach_id, $attach_data );
						$arr[] = $attach_id;
					}
				}
			}

			$mwb_wgm_gift_temp_for_you = '<style>/* What it does: Remove spaces around the email design added by some email clients. */ /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */ html, body{margin: 0 auto !important; padding: 0 !important; height: 100% !important; width: 100% !important;}body *{box-sizing: border-box;}/* What it does: Stops email clients resizing small text. */ *{-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;}/* What is does: Centers email on Android 4.4 */ div[style*="margin: 16px 0"]{margin:0 !important;}/* What it does: Stops Outlook from adding extra spacing to tables. */ table, td{mso-table-lspace: 0pt !important; mso-table-rspace: 0pt !important;}/* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */ table{border-spacing: 0 !important; border-collapse: collapse !important; table-layout: fixed !important; margin: 0 auto !important;}table table table{table-layout: auto;}/* What it does: Uses a better rendering method when resizing images in IE. */ img{-ms-interpolation-mode:bicubic; width: 100%;}/* What it does: A work-around for iOS meddling in triggered links. */ .mobile-link--footer a, a[x-apple-data-detectors]{color:inherit !important; text-decoration: underline !important;}/* What it does: Prevents underlining the button text in Windows 10 */ .button-link{text-decoration: none !important;}</style><style>/* What it does: Hover styles for buttons */ .button-td, .button-a{transition: all 100ms ease-in;}.button-td:hover, .button-a:hover{background: #555555 !important; border-color: #555555 !important;}/* Media Queries */ @media screen and (max-width: 599px){.email-container{width: 100% !important; margin: auto !important;}/* What it does: Forces elements to resize to the full width of their container. Useful for resizing images beyond their max-width. */ .fluid{max-width: 100% !important; height: auto !important; margin-left: auto !important; margin-right: auto !important;}/* What it does: Forces table cells into full-width rows. */ .stack-column, .stack-column-center{display: block !important; width: 100% !important; max-width: 100% !important; direction: ltr !important;}/* And center justify these ones. */ .stack-column-center{text-align: center !important;}/* What it does: Generic utility class for centering. Useful for images, buttons, and nested tables. */ .center-on-narrow{text-align: center !important; display: block !important; margin-left: auto !important; margin-right: auto !important; float: none !important;}table.center-on-narrow{display: inline-block !important;}}</style><center style="width: 100%; background: #222222;"></center><div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">(Optional) This text will appear in the inbox preview, but not the email body.</div><table class="email-container" style="margin: auto;" role="presentation" border="0" width="585" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td align="center" bgcolor="#ffffff">[FEATUREDIMAGE]</td></tr><tr><td dir="ltr" align="center" valign="top" bgcolor="#ffffff" width="100%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td style="line-height: 0; overflow: hidden; height: 30px;"></td></tr><tr><td class="stack-column-center" style="padding: 20px 0px; vertical-align: top; border-right: 1px solid #dddddd !important;" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #fff; padding: 0 20px 20px;" valign="top"><p style="margin: 10px 0 30px 0; text-align: left; font-weight: bold; font-size: 28px;"><span style="color: #333333; margin: 20px 0;">[AMOUNT]</span></p></td></tr><tr><td dir="ltr" style="padding: 30px 20px 0 20px;" valign="top"><p style="color: #333333; font-family: sans-serif; margin: 0px; font-size: 16px;"><span style="font-weight: bold; display: inline-block; text-align: left; font-size: 14px; width: 130px;">COUPON CODE:</span><span style="font-weight: bold; text-transform: uppercase; display: inline-block; text-align: left; font-size: 14px;">[COUPON]</span></p><p style="color: #333333; font-family: sans-serif; margin-bottom: 30px; font-size: 16px;"><span style="font-weight: bold; display: inline-block; text-align: left; font-size: 14px; width: 130px;">EXPIRY DATE:</span><span style="font-weight: bold; text-transform: uppercase; display: inline-block; text-align: left; font-size: 14px;">[EXPIRYDATE]</span></p></td></tr></tbody></table></td><td class="stack-column-center" style="padding: 20px 0px;" valign="top" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #fff; padding: 0px 30px 0 20px; min-height: 170px; height: auto;" valign="top"><p style="color: #333333; font-size: 15px;margin-bottom: 30px">[MESSAGE]</p></td></tr><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; padding: 0 0 0 20px; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #333333;" valign="top"><p style="margin-bottom: 0px; font-size: 16px; margin-top: 20px"><span style="font-weight: bold; display: inline-block; width: 20%; font-size: 15px;">From-</span><span style="display: inline-block; width: 75%; text-align: left; font-size: 14px;">[FROM]</span></p></td></tr><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; padding: 0 0 0 20px; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #333333;" valign="top"><p style="margin-top: 0px; font-size: 16px; line-height: 25px;"><span style="font-weight: bold; display: inline-block; width: 20%; font-size: 15px;">To-</span><span style="display: inline-block; width: 75%; text-align: left; font-size: 14px;">[TO]</span></p></td></tr></tbody></table></td></tr><tr><td style="line-height: 0; overflow: hidden; height: 30px;"></td></tr></tbody></table></td></tr><tr><td bgcolor="#ffffff"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="text-align: center; padding: 10px; border-top: 1px solid #dddddd !important; font-family: sans-serif; font-size: 16px; mso-height-rule: exactly; line-height: 20px; color: #333333;">[DISCLAIMER]</td></tr></tbody></table></td></tr></tbody></table>';

			$gifttemplate_new = array(
				'post_title' => __( 'Gift for You', 'woocommerce_gift_cards_lite' ),
				'post_content' => $mwb_wgm_gift_temp_for_you,
				'post_status' => 'publish',
				'post_author' => get_current_user_id(),
				'post_type'     => 'giftcard',
			);
			$parent_post_id = wp_insert_post( $gifttemplate_new );
			set_post_thumbnail( $parent_post_id, $arr[0] );
		}

	}

	/**
	 * Added custom Template
	 *
	 * @name mwb_wgm_insert_custom_template
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgm_insert_custom_template() {
		$mwb_wgm_template = get_option( 'mwb_wgm_insert_custom_template', '' );
		if ( empty( $mwb_wgm_template ) ) {
			update_option( 'mwb_wgm_insert_custom_template', true );
			$filename = array( MWB_WGC_URL . 'assets/images/custom_template.png' );
			if ( isset( $filename ) && is_array( $filename ) && ! empty( $filename ) ) {
				foreach ( $filename as $key => $value ) {
					$upload_file = wp_upload_bits( basename( $value ), null, file_get_contents( $value ) );
					if ( ! $upload_file['error'] ) {
						$filename = $upload_file['file'];
						// The ID of the post this attachment is for.

						$parent_post_id = 0;

						// Check the type of file. We'll use this as the 'post_mime_type'.
						$filetype = wp_check_filetype( basename( $filename ), null );
						// Get the path to the upload directory.
						$wp_upload_dir = wp_upload_dir();
						// Prepare an array of post data for the attachment.
						$attachment = array(
							'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
							'post_mime_type' => $filetype['type'],
							'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),

							'post_status'    => 'inherit',
						);
						// Insert the attachment.

						$attach_id = wp_insert_attachment( $attachment, $filename, 0 );
						// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
						require_once( ABSPATH . 'wp-admin/includes/image.php' );

						// Generate the metadata for the attachment, and update the database record.
						$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

						wp_update_attachment_metadata( $attach_id, $attach_data );
						$arr[] = $attach_id;
					}
				}
			}

			$mwb_wgm_custom_template_html = '<table class="email-container" style="margin: auto;" border="0" width="600" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td style="text-align: center; background: #0e0149;"><p style="color: #0e0149; font-size: 25px; font-family: sans-serif; margin: 20px; text-align: left;"><strong>[LOGO]</strong></p></td></tr></tbody></table><table class="email-container" style="margin: auto;" border="0" width="600" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td style="padding-bottom: 0px;" bgcolor="#f6f6f6"></td></tr><tr><td style="padding: 19px 30px; text-align: center; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; color: #555555;" bgcolor="#d6ccfd"></td></tr><tr style="background-color: #0e0149;"><td style="color: #fff; font-size: 20px; letter-spacing: 0px; margin: 0; text-transform: uppercase; background-color: #0e0149; padding: 20px 10px; line-height: 0;"><p style="border: 2px dashed #ffffff; color: #fff; font-size: 20px; letter-spacing: 0px; padding: 30px 10px; line-height: 30px; margin: 0; text-transform: uppercase; background-color: #0e0149; text-align: center;">Coupon Code<span style="display: block; font-size: 25px;">[COUPON]</span><span style="display: block;">Ed:[EXPIRYDATE]</span></p></td></tr><tr><td dir="ltr" style="padding-bottom: 34px;" align="center" valign="top" bgcolor="#d7ceff" width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="stack-column-center" style="vertical-align: top;" width="50%"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td dir="ltr" style="padding: 15px 25px 0;" valign="top">[DEFAULTEVENT]</td></tr></tbody></table></td><td class="stack-column-center" style="vertical-align: top;" width="50%"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #ffffff; padding: 15px; text-align: left;" valign="top"><p style="font-size: 15px; line-height: 24px; text-align: justify; color: #535151; min-height: 150px; white-space: pre-line;">[MESSAGE]</p></td></tr><tr><td class="mail-content" style="word-wrap: break-word; font-family: sans-serif; padding: 6px 15px;"><span style="color: #535151; font-size: 15px; float: left; vertical-align: top; display-inline: block;width: 60px;">From- </span> <span style="color: #535151; font-size: 14px; vertical-align: top; display: inline-block; float: left;">[FROM]</span></td></tr><tr><td style="word-wrap: break-word; font-family: sans-serif; padding: 6px 15px;"><span style="color: #535151; font-size: 15px; float: left;width: 60px; display: inline-block; vertical-align: top;">To- </span> <span style="color: #535151; font-size: 14px; float: left; vertical-align: top;">[TO]</span></td></tr><tr><td style="padding: 5px 15px; word-wrap: break-word;"><span style="color: #0e0149; font-size: 23.96px; vertical-align: top;"><strong>[AMOUNT]/-</strong> </span></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td bgcolor="#0e0149"><table border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="padding: 20px 30px; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #ffffff;"><p style="font-weight: bold; text-align: center;"></p></td></tr></tbody></table></td></tr></tbody></table>';

			$mwb_wgm_template = array(
				'post_title' => __( 'Custom Template', 'woocommerce_gift_cards_lite' ),
				'post_content' => $mwb_wgm_custom_template_html,
				'post_status' => 'publish',
				'post_author' => get_current_user_id(),
				'post_type'     => 'giftcard',
			);
			$parent_post_id = wp_insert_post( $mwb_wgm_template );
			set_post_thumbnail( $parent_post_id, $arr[0] );
		}
	}

	/**
	 * Added Christmas Template
	 *
	 * @name mwb_wgm_insert_christmas_template
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgm_insert_christmas_template() {
		$mwb_wgm_template = get_option( 'mwb_wgm_merry_christmas_template', '' );
		if ( empty( $mwb_wgm_template ) ) {
			update_option( 'mwb_wgm_merry_christmas_template', true );
			$filename = array( MWB_WGC_URL . 'assets/images/merry_christmas.png' );
			if ( isset( $filename ) && is_array( $filename ) && ! empty( $filename ) ) {
				foreach ( $filename as $key => $value ) {
					$upload_file = wp_upload_bits( basename( $value ), null, file_get_contents( $value ) );
					if ( ! $upload_file['error'] ) {
						$filename = $upload_file['file'];
						// The ID of the post this attachment is for.

						$parent_post_id = 0;

						// Check the type of file. We'll use this as the 'post_mime_type'.
						$filetype = wp_check_filetype( basename( $filename ), null );
						// Get the path to the upload directory.
						$wp_upload_dir = wp_upload_dir();
						// Prepare an array of post data for the attachment.
						$attachment = array(
							'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
							'post_mime_type' => $filetype['type'],
							'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),

							'post_status'    => 'inherit',
						);
						// Insert the attachment.

						$attach_id = wp_insert_attachment( $attachment, $filename, 0 );
						// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
						require_once( ABSPATH . 'wp-admin/includes/image.php' );

						// Generate the metadata for the attachment, and update the database record.
						$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

						wp_update_attachment_metadata( $attach_id, $attach_data );
						$arr[] = $attach_id;
					}
				}
			}

			$mwb_wgm_merry_christmas_template = '<center style="width: 100%; text-align: left;"> <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;"> Christmas-gift-card </div><table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="margin: auto;" class="email-container"> <tr> <td aria-hidden="true" height="5" style="font-size: 0; line-height: 0;"> &nbsp; </td></tr></table> <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="margin: auto;" class="email-container"> <tr> <td bgcolor="#A10005" align="center"> [HEADER] </td></tr><!--===================================logo-section====================================--><tr> <td dir="ltr" style="padding-bottom: 10px; padding-top:0px;" width="100%" valign="top" align="center" bgcolor="#A10005"> <table role="presentation" width="100%" align="center" cellspacing="0" cellpadding="0" border="0"> <tbody> <tr> <td class="stack-column-center" width="100%"> <table role="presentation" width="100%" align="center" cellspacing="0" cellpadding="0" border="0"> <tbody> <tr> <td bgcolor="#A10005" align="center">[LOGO] </td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr><tr> <td bgcolor="#A10005" align="center"> [CHRISTMASTITLE] </td></tr><tr> <td bgcolor="#A10005" align="center" style="padding: 10px 20px 10px; text-align: center;"> <p style="text-align:center;margin: 0; font-family: sans-serif; font-size:18px; line-height: 125%; color: #fff; font-weight:normal;">[MESSAGE]</p> </td></tr><tr> <td bgcolor="#A10005" align="center" style="padding: 0px 20px 0px; text-align: center;"> <p style="margin: 0; font-family: sans-serif; font-size:26px; line-height: 125%; color: #fff; font-weight:600;display: inline-block;padding:8px 20px;">[AMOUNT]</p> </td></tr><!--=====================================================coupon-code and wishes section======================================================--><tr> <td dir="ltr" width="100%" valign="top" align="center" bgcolor="#a10005"> <table role="presentation" width="100%" align="center" cellspacing="0" cellpadding="0" border="0"> <tbody> <tr> <td class="stack-column-center" width="100%" style="background-color:#a10005; text-align: center;padding-bottom:10px;"> <table class="mwb-gc-coupon" role="presentation" width="40%" align="center" cellspacing="0" cellpadding="0" border="0"> <tbody> <tr> <td dir="ltr" valign="top" align="center" style="padding:10px 0;"> <div style="border:2px dashed #fff;"> <p style="letter-spacing: 1px; margin: 15px 0px 10px; text-transform: uppercase;font-family: sans-serif; font-weight:600; font-size: 12px; color:#fff;">coupon code </p><span class="mwb_coupon_code" style="padding: 10px 10px; text-transform: uppercase; font-size:18px;font-family: sans-serif;font-weight:600;color: rgb(255, 255, 255); font-family: sans-serif;"> [COUPON] </span> <p style="letter-spacing: 1px; text-transform: uppercase;font-family: sans-serif; font-weight: bold; font-size: 12px; margin: 10px 0px 15px; color:#fff;">(Ed:[EXPIRYDATE]) </p></div></td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr><tr> <td bgcolor="#A10005" align="center"> [FOOTER] </td></tr><tr> <td dir="ltr" style="padding-bottom: 10px; padding-top:10px;" width="100%" valign="top" align="center" bgcolor="#E3F3FD"> <table role="presentation" width="100%" align="center" cellspacing="0" cellpadding="0" border="0"> <tbody> <tr> <td class="mwb-woo-email-left" width="50%"> <table role="presentation" width="100%" align="left" cellspacing="0" cellpadding="0" border="0"> <tbody> <tr> <td class="mwb-gc-to" dir="ltr" style="text-align:left;padding-left:10px;" valign="top"> <p style="font-weight:bold;color: #A10005; font-size: 16px; font-family: sans-serif; margin: 0px;">To:</p><p style="text-decoration:none;padding-top:5px;padding-bottom:5px;font-weight:bold;color: #000; font-size: 13px; font-family: sans-serif; margin: 0px;">[TO]</p></td></tr></tbody> </table> </td><td class="mwb-woo-email-right" style="vertical-align: top;" width="50%"> <table role="presentation" width="100%" align="right" cellspacing="0" cellpadding="0" border="0"> <tbody> <tr> <td class="mwb-gc-from" dir="ltr" style="text-align:right;padding-right:10px;" valign="top"> <p style="font-weight:bold;color: #A10005; font-size: 16px; font-family: sans-serif; margin: 0px;">From:</p><p style="text-decoration:none;padding-top:5px;padding-bottom:5px;font-weight:bold;color: #000; font-size: 13px; font-family: sans-serif; margin: 0px;">[FROM]</p></td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr></table></center><style>html, body{margin: 0 auto !important; padding: 0 !important; height: 100% !important; width: 100% !important;}*{-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; box-sizing:border-box;}div[style*="margin: 16px 0"]{margin: 0 !important;}table, td{mso-table-lspace: 0pt !important; mso-table-rspace: 0pt !important;}table{border-spacing: 0 !important; border-collapse: collapse !important; table-layout: fixed !important; margin: 0 auto !important;}table table table{table-layout: auto;}img{-ms-interpolation-mode:bicubic;}*[x-apple-data-detectors], .x-gmail-data-detectors, .x-gmail-data-detectors *, .aBn{border-bottom: 0 !important; cursor: default !important; color: inherit !important; text-decoration: none !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important;}.a6S{display: none !important; opacity: 0.01 !important;}img.g-img + div{display: none !important;}.button-link{text-decoration: none !important;}@media only screen and (min-device-width: 501px) and (max-device-width: 599px){.mwb-woo-email-left{width:49.5% !important; display: inline-block !important; text-align: left !important;}.mwb-woo-email-right{width:49.5% !important; display: inline-block !important; text-align:right !important;}.mwb-gc-from{text-align: right !important; padding-left:10px; padding-top:5px;}}@media screen and (max-width: 500px){.mwb-woo-email-left{width:100% !important; display:block !important; text-align: left !important;}.mwb-woo-email-right{width:100% !important; display:block !important; text-align:left !important;}.mwb-gc-from{text-align: left !important; padding-left:10px; padding-top:5px;}.mwb-gc-from p{font-size:14px !important;}.mwb-gc-to{text-align: left !important; padding-left:10px; padding-top:5px;}.mwb-gc-to p{font-size:14px !important;}}@media only screen and (min-device-width: 375px) and (max-device-width: 413px){.email-container{min-width: 375px !important;}}@media screen and (max-width: 480px){u ~ div .email-container{min-width: 100vw; width: 100% !important;}.mwb-gc-coupon{width:80% !important;}}</style><style>.mwb_coupon_code {color: rgb(255, 255, 255);}.button-td, .button-a{transition: all 100ms ease-in;}.button-td:hover, .button-a:hover{background: #555555 !important; border-color: #555555 !important;}@media screen and (max-width: 600px){.email-container{width: 100% !important; margin: auto !important;}.fluid{max-width: 100% !important; height: auto !important; margin-left: auto !important; margin-right: auto !important;}.stack-column, .stack-column-center{display: block !important; width: 100% !important; max-width: 100% !important; direction: ltr !important;}.stack-column-center{text-align: center !important;}.center-on-narrow{text-align: center !important; display: block !important; margin-left: auto !important; margin-right: auto !important; float: none !important;}table.center-on-narrow{display: inline-block !important;}}</style>';
				$header_image = MWB_WGC_URL . 'assets/images/header1.png';
				$header_image = "<img src='$header_image' width='600' height='' alt='alt_text' border='0' align='center' style='width: 100%; max-width: 600px; height: auto; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; margin: auto;' class='g-img'/>";

				$christmas_title_image = MWB_WGC_URL . 'assets/images/christmas-title.png';
				$christmas_title_image = "<img src='$christmas_title_image' width='250' height='' alt='alt_text' border='0' align='center' style='padding:0 10px;width: 100%; max-width: 500px; height: auto; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; margin: auto;' class='g-img'>";

				$footer_image = MWB_WGC_URL . 'assets/images/footer1.png';
				$footer_image = "<img src='$footer_image' width='600' height='' alt='alt_text' border='0' align='center' style='width: 100%; max-width: 600px; height: auto; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; margin: auto;' class='g-img'>";
				// Replced with images.
				$mwb_wgm_merry_christmas_template = str_replace( '[HEADER]', $header_image, $mwb_wgm_merry_christmas_template );
				$mwb_wgm_merry_christmas_template = str_replace( '[CHRISTMASTITLE]', $christmas_title_image, $mwb_wgm_merry_christmas_template );
				$mwb_wgm_merry_christmas_template = str_replace( '[FOOTER]', $footer_image, $mwb_wgm_merry_christmas_template );

			$gifttemplate_new = array(
				'post_title' => __( 'Merry Christmas Template', 'woocommerce_gift_cards_lite' ),
				'post_content' => $mwb_wgm_merry_christmas_template,
				'post_status' => 'publish',
				'post_author' => get_current_user_id(),
				'post_type'     => 'giftcard',
			);
			$parent_post_id = wp_insert_post( $gifttemplate_new );
			set_post_thumbnail( $parent_post_id, $arr[0] );
		}
	}

	/**
	 * Add Preview button link in giftcard post
	 *
	 * @name mwb_wgm_preview_gift_template
	 * @param array  $actions actions.
	 * @param object $post post.
	 * @return $actions.
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgm_preview_gift_template( $actions, $post ) {
		if ( 'giftcard' == $post->post_type ) {
			$actions['mwb_wgm_quick_view'] = '<a href="' . admin_url( 'edit.php?post_type=giftcardpost&post_id=' . $post->ID . '&mwb_wgm_template=giftcard&TB_iframe=true&width=600&height=500' ) . '" rel="permalink" class="thickbox">' . __( 'Preview', 'woocommerce_gift_cards_lite' ) . '</a>';
		}
		return $actions;
	}

	/**
	 * Preview of email template
	 *
	 * @name mwb_wgm_preview_email_template
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgm_preview_email_template() {
		if ( isset( $_GET['mwb_wgm_template'] ) ) {
			if ( isset( $_GET['mwb_wgm_template'] ) == 'giftcard' ) {
				$post_id = isset( $_GET['post_id'] ) ? sanitize_text_field( wp_unslash( $_GET['post_id'] ) ) : '';
				$todaydate = date_i18n( 'Y-m-d' );
				$mwb_wgm_general_settings = get_option( 'mwb_wgm_general_settings', false );

				$expiry_date = $this->mwb_common_fun->mwb_wgm_get_template_data( $mwb_wgm_general_settings, 'mwb_wgm_general_setting_giftcard_expiry' );

				$expirydate_format = $this->mwb_common_fun->mwb_wgm_check_expiry_date( $expiry_date );
				$mwb_wgm_coupon_length_display = $this->mwb_common_fun->mwb_wgm_get_template_data( $mwb_wgm_general_settings, 'mwb_wgm_general_setting_giftcard_coupon_length' );

				if ( '' == $mwb_wgm_coupon_length_display ) {
					$mwb_wgm_coupon_length_display = 5;
				}
				$password = '';
				for ( $i = 0;$i < $mwb_wgm_coupon_length_display;$i++ ) {
					$password .= 'x';
				}
				$giftcard_prefix = $this->mwb_common_fun->mwb_wgm_get_template_data( $mwb_wgm_general_settings, 'mwb_wgm_general_setting_giftcard_prefix' );
				$coupon = $giftcard_prefix . $password;
				$templateid = $post_id;

				$args['from'] = esc_html__( 'from@example.com', 'woocommerce_gift_cards_lite' );
				$args['to'] = esc_html__( 'to@example.com', 'woocommerce_gift_cards_lite' );
				$args['message'] = esc_html__( 'Your gift message will appear here which you send to your receiver. ', 'woocommerce_gift_cards_lite' );
				$args['coupon'] = apply_filters( 'mwb_wgm_static_coupon_img', $coupon );
				$args['expirydate'] = $expirydate_format;
				$args['amount'] = wc_price( 100 );
				$args['templateid'] = $templateid;
				$style = '<style>
					table, th, tr, td {
						border: medium none;
					}
					table, th, tr, td {
						border: 0px !important;
					}
						#mwb_gw_email {
					width: 630px !important;
				}
				</style>';
				$message = $this->mwb_common_fun->mwb_wgm_create_gift_template( $args );
				$finalhtml = $style . $message;

				if ( mwb_uwgc_pro_active() ) {
					do_action( 'preview_email_template_for_pro', $finalhtml );
				} else {
					$allowed_tags = $this->mwb_common_fun->mwb_allowed_html_tags();
					//@codingStandardsIgnoreStart
					echo wp_kses($finalhtml,$allowed_tags);
					//@codingStandardsIgnoreEnd
					die();
				}
			}
		}
	}

	/**
	 * This is used to add row meta on plugin activation.
	 *
	 * @name mwb_custom_plugin_row_meta
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @param mixed $links Contains links.
	 * @param mixed $file Contains main file.
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_custom_plugin_row_meta( $links, $file ) {
		if ( strpos( $file, 'woo-gift-cards-lite/woocommerce_gift_cards_lite.php' ) !== false ) {
			$new_links = array(
				'doc' => '<a href="http://docs.makewebbetter.com/woocommerce-gift-cards-lite/?utm_source=MWB-giftcard-org&utm_medium=MWB-ORG-Page&utm_campaign=pluginDoc" target="_blank"><i class="far fa-file-alt" style="margin-right:3px;"></i>Documentation</a>',
				'support' => '<a href="https://makewebbetter.freshdesk.com/a/tickets/new" target="_blank"><i class="fas fa-user-ninja" style="margin-right:3px;"></i>Support</a>',
			);

			$links = array_merge( $links, $new_links );
		}
		return $links;
	}

	/**
	 * This function is used to get all the templates in giftcard lite plugin.
	 *
	 * @name mwb_wgm_get_all_lite_templates
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgm_get_all_lite_templates() {
		$mwb_lite_templates = array(
			'Love You Mom',
			'Gift for You',
			'Custom Template',
			'Merry Christmas Template',
		);
		return $mwb_lite_templates;
	}
}
?>
