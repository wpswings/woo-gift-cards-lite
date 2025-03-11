<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/admin
 * @author     WP Swings <webmaster@wpswings.com>
 */
use Automattic\WooCommerce\Utilities\OrderUtil;
/**
 * Class admin lite.
 */
class Woocommerce_Gift_Cards_Lite_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The object of common class file.
	 *
	 * @since    1.0.0
	 * @var      string    $wps_common_fun    The current version of this plugin.
	 */
	public $wps_common_fun;

	/**
	 * The onboard form data .
	 *
	 * @since    1.0.0
	 * @var      string    $onboard    onboard form data.
	 */

	 private $onboard;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
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

		require_once WPS_WGC_DIRPATH . 'includes/class-woocommerce-gift-cards-common-function.php';
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->wps_common_fun = new Woocommerce_Gift_Cards_Common_Function();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function wps_wgm_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Gift_Cards_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Gift_Cards_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$secure_nonce      = wp_create_nonce( 'wps-gc-auth-nonce' );
		$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-gc-auth-nonce' );
		if ( ! $id_nonce_verified ) {
				wp_die( esc_html__( 'Nonce Not verified', 'woo-gift-cards-lite' ) );
		}

		$screen = get_current_screen();
		wp_enqueue_script( 'thickbox' );
		if ( isset( $screen->id ) ) {
			$pagescreen = $screen->id;
		}

		if ( ( isset( $_GET['page'] ) && 'wps-wgc-setting-lite' === $_GET['page'] ) || ( isset( $_GET['post_type'] ) && 'product' === $_GET['post_type'] ) || ( isset( $_GET['post_type'] ) && 'giftcard' === $_GET['post_type'] ) || ( isset( $pagescreen ) && ( 'plugins' === $pagescreen || 'product' === $pagescreen ) ) ) {
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_style( 'select2' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce_gift_cards_lite-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), $this->version );
			wp_enqueue_style( 'woocommerce_admin_menu_styles' );
			wp_enqueue_style( 'woocommerce_admin_styles' );
			if ( ! wps_uwgc_pro_active() ) {

				wp_enqueue_style( $this->plugin_name . 'tmp', plugin_dir_url( __FILE__ ) . 'css/woocommerce_gift_cards_import_tmp.css', array(), time(), 'all' );

			}
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function wps_wgm_enqueue_scripts() {
		$screen = get_current_screen();

		wp_enqueue_script( 'thickbox' );
		if ( isset( $screen->id ) ) {
			if ( wps_uwgc_pro_active() ) {
				wp_enqueue_script( 'pro_tag_remove_class', plugin_dir_url( __FILE__ ) . '/js/wps_wgm_gift_card_pro_admin.js', array( 'jquery' ), $this->version, true );
			}
			$pagescreen = $screen->id;

			$wps_wgm_notice = array(
				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
				'wps_wgm_nonce' => wp_create_nonce( 'wps-wgm-verify-notice-nonce' ),
			);
			wp_register_script( $this->plugin_name . 'admin-notice', plugin_dir_url( __FILE__ ) . 'js/wps-wgm-gift-card-notices.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( $this->plugin_name . 'admin-notice', 'wps_wgm_notice', $wps_wgm_notice );
			wp_enqueue_script( $this->plugin_name . 'admin-notice' );
			if ( 'plugins' === $pagescreen ) {
				return;
			}

			if ( 'giftcard' === $pagescreen && ! is_plugin_active( 'giftware/giftware.php' ) ) {
				wp_enqueue_script( $this->plugin_name . 'wps_wgm_uneditable_template_name', plugin_dir_url( __FILE__ ) . 'js/wps_wgm_uneditable_template_name.js', array( 'jquery' ), $this->version, 'count' );
			}

			if ( 'product' === $pagescreen || 'shop_order' === $pagescreen || 'woocommerce_page_wc-orders' == $pagescreen || 'giftcard_page_wps-wgc-setting-lite' === $pagescreen || 'giftcard_page_uwgc-import-giftcard-templates' === $pagescreen || 'plugins' === $pagescreen || 'giftcard_page_giftcard-import-giftcard-templates' === $pagescreen ) {

				$wps_wgm_general_settings = get_option( 'wps_wgm_general_settings', false );
				$giftcard_tax_cal_enable  = $this->wps_common_fun->wps_wgm_get_template_data( $wps_wgm_general_settings, 'wps_wgm_general_setting_tax_cal_enable' );

				$wps_wgc = array(
					'ajaxurl'                => admin_url( 'admin-ajax.php' ),
					'is_tax_enable_for_gift' => $giftcard_tax_cal_enable,
					'wps_wgm_nonce'          => wp_create_nonce( 'wps-wgm-verify-nonce' ),
					'decimal_separator'      => get_option( 'woocommerce_price_decimal_sep' ),
				);
				$url     = plugins_url();
				wp_enqueue_script( 'wps_lite_select2', $url . '/woocommerce/assets/js/select2/select2.min.js', array( 'jquery' ), $this->version, true );
				wp_register_script( $this->plugin_name . 'clipboard', plugin_dir_url( __FILE__ ) . 'js/clipboard.min.js', array(), $this->version, true );

				wp_enqueue_script( $this->plugin_name . 'clipboard' );
				wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce_gift_cards_lite-admin.js', array( 'jquery', 'wps_lite_select2', 'wc-enhanced-select', 'wp-color-picker' ), $this->version, true );

				wp_localize_script( $this->plugin_name, 'wps_wgc', $wps_wgc );

				wp_enqueue_script( $this->plugin_name );

				wp_register_script( 'woocommerce_admin', WC()->plugin_url() . '/assets/js/admin/woocommerce_admin.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip' ), $this->version, false );
				wp_register_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip.js', array( 'jquery' ), $this->version, false );
				$locale  = localeconv();
				$decimal = isset( $locale['decimal_point'] ) ? $locale['decimal_point'] : '.';
				$params  = array(
					/* translators: %s: decimal */
					'i18n_decimal_error'               => sprintf( __( 'Please enter in decimal (%s) format without thousand separators.', 'woo-gift-cards-lite' ), $decimal ),
					/* translators: %s: price decimal separator */
					'i18n_mon_decimal_error'           => sprintf( __( 'Please enter in monetary decimal (%s) format without thousand separators and currency symbols.', 'woo-gift-cards-lite' ), wc_get_price_decimal_separator() ),
					'i18n_country_iso_error'           => __( 'Please enter in country code with two capital letters.', 'woo-gift-cards-lite' ),
					'i18_sale_less_than_regular_error' => __( 'Please enter in a value less than the regular price.', 'woo-gift-cards-lite' ),
					'decimal_point'                    => $decimal,
					'mon_decimal_point'                => wc_get_price_decimal_separator(),
					'strings'                          => array(
						'import_products' => __( 'Import', 'woo-gift-cards-lite' ),
						'export_products' => __( 'Export', 'woo-gift-cards-lite' ),
					),
					'urls'                             => array(
						'import_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_importer' ) ),
						'export_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_exporter' ) ),
					),
				);

				wp_localize_script( 'woocommerce_admin', 'woocommerce_admin', $params );
				wp_enqueue_script( 'woocommerce_admin' );
				wp_enqueue_script( 'media-upload' );
				/*sticky sidebar*/
				wp_enqueue_script( 'sticky_js', plugin_dir_url( __FILE__ ) . '/js/jquery.sticky-sidebar.min.js', array( 'jquery' ), $this->version, true );

			}

			if ( 'woocommerce_page_wc-reports' == $pagescreen ) {
				$wps_uwgc_report_array = array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'wps_uwgc_report_nonce' => wp_create_nonce( 'wps-uwgc-giftcard-report-nonce' ),
				);
				wp_enqueue_script( 'wps_uwgc_report_js', plugin_dir_url( __FILE__ ) . 'js/ultimate-woocommerce-giftcard-report.js', array( 'jquery' ), $this->version, false );
				wp_localize_script( 'wps_uwgc_report_js', 'ajax_object', $wps_uwgc_report_array );
				wp_enqueue_script( 'thickbox' );
				wp_enqueue_style( 'thickbox' );
			}
		}
	}

	/**
	 * Add a submenu inside the Giftcard CPT Menu.
	 *
	 * @since 2.0.0
	 * @name wps_wgm_admin_menu()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_admin_menu() {

		add_submenu_page( 'edit.php?post_type=giftcard', __( 'Settings', 'woo-gift-cards-lite' ), __( 'Settings', 'woo-gift-cards-lite' ), 'manage_options', 'wps-wgc-setting-lite', array( $this, 'wps_wgm_admin_setting' ) );
		if ( ! wps_uwgc_pro_active() ) {
			add_submenu_page( 'edit.php?post_type=giftcard', __( 'Premium Plugin', 'woo-gift-cards-lite' ), __( 'Premium Plugin', 'woo-gift-cards-lite' ), 'manage_options', 'wps-wgc-premium-plugin', array( $this, 'wps_wgm_premium_features' ) );
		}
		// hooks to add sub menu.
		do_action( 'wps_wgm_admin_sub_menu' );
	}

	/**
	 * Including a File for displaying the required setting page for setup the plugin
	 *
	 * @since 1.0.0
	 * @name wps_wgm_admin_setting()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_admin_setting() {

		include_once WPS_WGC_DIRPATH . '/admin/partials/woocommerce-gift-cards-lite-admin-display.php';
	}

	/**
	 * Contain all the giftcard premium features inside this panel.
	 *
	 * @since 2.0.0
	 * @name wps_wgm_premium_features()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_premium_features() {
		$secure_nonce      = wp_create_nonce( 'wps-gc-auth-nonce' );
		$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-gc-auth-nonce' );
		if ( ! $id_nonce_verified ) {
				wp_die( esc_html__( 'Nonce Not verified', 'woo-gift-cards-lite' ) );
		}
		if ( isset( $_GET['page'] ) && 'wps-wgc-premium-plugin' == $_GET['page'] ) {
			$wps_premium_page = esc_url_raw( 'https://wpswings.com/product/gift-cards-for-woocommerce-pro/?utm_source=wpswings-giftcards-pro&utm_medium=giftcards-org-backend&utm_campaign=go-pro' );
			wp_redirect( $wps_premium_page );
			exit;
		}
	}

	/**
	 * Create a custom Product Type for Gift Card
	 *
	 * @since 1.0.0
	 * @name wps_wgm_gift_card_product()
	 * @param array $types product types.
	 * @return $types.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_gift_card_product( $types ) {
		$wps_wgc_enable = wps_wgm_giftcard_enable();
		if ( $wps_wgc_enable ) {
			$types['wgm_gift_card'] = __( 'Gift Card', 'woo-gift-cards-lite' );
		}
		return $types;
	}

	/**
	 * Provide multiple Price variations for Gift Card Product
	 *
	 * @since 1.0.0
	 * @name wps_wgm_get_pricing_type()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_get_pricing_type() {
		$pricing_options = array(
			'wps_wgm_default_price'  => __( 'Default Price', 'woo-gift-cards-lite' ),
			'wps_wgm_range_price'    => __( 'Price Range', 'woo-gift-cards-lite' ),
			'wps_wgm_selected_price' => __( 'Selected Price', 'woo-gift-cards-lite' ),
			'wps_wgm_user_price'     => __( 'User Price', 'woo-gift-cards-lite' ),
			'wps_wgm_variable_price' => __( 'Variable Price', 'woo-gift-cards-lite' ),
		);
		return apply_filters( 'wps_wgm_pricing_type', $pricing_options );
	}

	/**
	 * Add some required fields (data-tabs) for Gift Card product
	 *
	 * @since 1.0.0
	 * @name wps_wgm_woocommerce_product_options_general_product_data()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_woocommerce_product_options_general_product_data() {
		global $post;
		$product_id = $post->ID;
		if ( isset( $product_id ) ) {
			if ( ! current_user_can( 'edit_post', $product_id ) ) {
				return;
			}
		}
		$wps_wgm_pricing  = get_post_meta( $product_id, 'wps_wgm_pricing', true );
		$selected_pricing = isset( $wps_wgm_pricing['type'] ) ? $wps_wgm_pricing['type'] : false;
		$giftcard_enable  = wps_wgm_giftcard_enable();

		$default_price  = '';
		$from           = '';
		$to             = '';
		$price          = '';
		$min_user_price = '';
		$default_price = isset( $wps_wgm_pricing['default_price'] ) ? $wps_wgm_pricing['default_price'] : 0;
		if ( is_array( $wps_wgm_pricing ) && ! empty( $wps_wgm_pricing ) ) {
			if ( array_key_exists( 'template', $wps_wgm_pricing ) ) {
				$selectedtemplate = isset( $wps_wgm_pricing['template'] ) ? $wps_wgm_pricing['template'] : false;
			}
		} else {
			$selectedtemplate = $this->wps_common_fun->wps_get_org_selected_template();
		}

		$default_selected = isset( $wps_wgm_pricing['by_default_tem'] ) ? $wps_wgm_pricing['by_default_tem'] : false;
		if ( $selected_pricing ) {
			switch ( $selected_pricing ) {
				case 'wps_wgm_range_price':
					$from = isset( $wps_wgm_pricing['from'] ) ? $wps_wgm_pricing['from'] : 0;
					$to   = isset( $wps_wgm_pricing['to'] ) ? $wps_wgm_pricing['to'] : 0;
					break;
				case 'wps_wgm_selected_price':
					$price = isset( $wps_wgm_pricing['price'] ) ? $wps_wgm_pricing['price'] : 0;
					break;
				case 'wps_wgm_user_price':
					$min_user_price = isset( $wps_wgm_pricing['min_user_price'] ) ? $wps_wgm_pricing['min_user_price'] : 0;
					break;
				case 'wps_wgm_selected_with_price_range':
					$from = isset( $wps_wgm_pricing['from'] ) ? $wps_wgm_pricing['from'] : 0;
					$to = isset( $wps_wgm_pricing['to'] ) ? $wps_wgm_pricing['to'] : 0;
					$price = isset( $wps_wgm_pricing['price'] ) ? $wps_wgm_pricing['price'] : 0;
					break;
				default:
					// Nothing for default.
			}
		}
		if ( $giftcard_enable ) {
			$src = WPS_WGC_URL . 'assets/images/loading.gif';
			?>
			<div class="options_group show_if_wgm_gift_card"><div id="wps_wgm_loader" style="display: none;">
				<img src="<?php echo esc_url( $src ); ?>">
			</div>
			<?php
			woocommerce_wp_text_input(
				array(
					'id'          => 'wps_wgm_default',
					'value'       => "$default_price",
					'label'       => __( 'Default Price', 'woo-gift-cards-lite' ),
					'placeholder' => wc_format_localized_price( 0 ),
					'description' => __( 'Gift card default price. if you are using Price range pricing type then try to add default price in between from and to range', 'woo-gift-cards-lite' ),
					'data_type'   => 'price',
					'desc_tip'    => true,
				)
			);
			woocommerce_wp_select(
				array(
					'id'      => 'wps_wgm_pricing',
					'value'   => "$selected_pricing",
					'label'   => __( 'Pricing type', 'woo-gift-cards-lite' ),
					'options' => $this->wps_wgm_get_pricing_type(),
				)
			);
			// Range Price.
			// StartFrom.
			woocommerce_wp_text_input(
				array(
					'id'          => 'wps_wgm_from_price',
					'value'       => "$from",
					'label'       => __( 'From Price', 'woo-gift-cards-lite' ),
					'placeholder' => wc_format_localized_price( 0 ),
					'description' => __( 'Gift card price range start from.', 'woo-gift-cards-lite' ),
					'data_type'   => 'price',
					'desc_tip'    => true,
				)
			);
			// EndTo.
			woocommerce_wp_text_input(
				array(
					'id'          => 'wps_wgm_to_price',
					'value'       => "$to",
					'label'       => __( 'To Price', 'woo-gift-cards-lite' ),
					'placeholder' => wc_format_localized_price( 0 ),
					'description' => __( 'Gift card price range end to.', 'woo-gift-cards-lite' ),
					'data_type'   => 'price',
					'desc_tip'    => true,
				)
			);
			// Selected Price.
			woocommerce_wp_textarea_input(
				array(
					'id'          => 'wps_wgm_selected_price',
					'value'       => "$price",
					'label'       => __( 'Price', 'woo-gift-cards-lite' ),
					'desc_tip'    => 'true',
					'description' => __( 'Enter price using separator |. Ex : (10 | 20)', 'woo-gift-cards-lite' ),
					'placeholder' => '10|20|30',
				)
			);
			// User Price set minimum amount.
			woocommerce_wp_text_input(
				array(
					'id'          => 'wps_wgm_min_user_price',
					'value'       => "$min_user_price",
					'label'       => __( 'Set Minimum Price', 'woo-gift-cards-lite' ),
					'placeholder' => wc_format_localized_price( 0 ),
					'description' => __( 'Leave Empty for No Minimum Gift card price.', 'woo-gift-cards-lite' ),
					'data_type'   => 'price',
					'desc_tip'    => true,
				)
			);
			// variable price.
			$variable_price_text = isset( $wps_wgm_pricing['wps_wgm_variation_text'] ) ? $wps_wgm_pricing['wps_wgm_variation_text'] : array();
			$variable_price_amt = isset( $wps_wgm_pricing['wps_wgm_variation_price'] ) ? $wps_wgm_pricing['wps_wgm_variation_price'] : array();
			?>
			<div id="wps_variable_gift">
				<div class="wps_variable_desc">
					<span><?php esc_html_e( 'Description', 'woo-gift-cards-lite' ); ?></span>
					<span><?php esc_html_e( 'Price', 'woo-gift-cards-lite' ); ?></span>
				</div>
				<?php
				if ( is_array( $variable_price_amt ) && empty( $variable_price_amt ) && count( $variable_price_amt ) == 0 ) {
					?>
					<div class="wps_wgm_variation_giftcard">
						<input type="text" class="wps_wgm_variation_text" name="wps_wgm_variation_text[]" placeholder="Enter Description" value="">
						<input type="text" class="wps_wgm_variation_price wc_input_price" name="wps_wgm_variation_price[]" placeholder="Enter Price" value="">
					</div>
					<?php
				} else {
					if ( is_array( $variable_price_amt ) && is_array( $variable_price_text ) && ! empty( $variable_price_amt ) && ! empty( $variable_price_text ) && count( $variable_price_amt ) >= 1 ) {
						foreach ( $variable_price_amt as $key => $value ) {
							?>
							<div class="wps_wgm_variation_giftcard">
								<input type="text" class="wps_wgm_variation_text" name="wps_wgm_variation_text[]" value="<?php echo esc_html( $variable_price_text[ $key ] ); ?>">
								<input type="text" class="wps_wgm_variation_price wc_input_price" name="wps_wgm_variation_price[]" value="<?php echo esc_html( $value ); ?>">
								<?php if ( $key > 0 ) { ?>
								<a class="wps_remove_more_price button" href="javascript:void(0)"><?php esc_html_e( 'Remove', 'woo-gift-cards-lite' ); ?></a>
							<?php } ?>
							</div>
							<?php
						}
					}
				}
				?>
				<a href="#" class="wps_add_more_price button"><?php esc_html_e( 'Add', 'woo-gift-cards-lite' ); ?></a>
			</div>
			<?php
			// Regular Price.
			?>
			<p class="form-field wps_wgm_default_price_field">
				<label for="wps_wgm_default_price_field"><b><?php esc_html_e( 'Instruction', 'woo-gift-cards-lite' ); ?></b></label>
				<span class="description"><?php esc_html_e( 'WooCommerce Product regular price is used as a gift card price.', 'woo-gift-cards-lite' ); ?></span>
			</p>

			<p class="form-field wps_wgm_user_price_field ">
				<label for="wps_wgm_user_price_field"><b><?php esc_html_e( 'Instruction', 'woo-gift-cards-lite' ); ?></b></label>
				<span class="description"> <?php esc_html_e( 'Users can purchase any amount of Gift Card.', 'woo-gift-cards-lite' ); ?></span>
			</p>

			<?php
			$is_customizable        = get_post_meta( $product_id, 'woocommerce_customizable_giftware', true );
			$wps_get_pro_templates  = get_option( 'wps_uwgc_templateid', array() );
			if ( empty( $wps_get_pro_templates ) ) {
				$wps_get_pro_templates = array();
			}
			$wps_get_lite_templates = $this->wps_wgm_get_all_lite_templates();
			?>
				<p class="form-field wps_wgm_email_template">
					<label class ="wps_wgm_email_template" for="wps_wgm_email_template"><?php esc_html_e( 'Email Template', 'woo-gift-cards-lite' ); ?></label>
					<?php
					if ( wps_uwgc_pro_active() ) {
						?>
						<select id="wps_wgm_email_template" multiple="multiple" name="wps_wgm_email_template[]" class="wps_wgm_email_template">
						<?php
					} else {
						?>
						<select id="wps_wgm_email_template" name="wps_wgm_email_template[]" class="wps_wgm_email_template">
						<?php
					}
					$args     = array(
						'post_type'      => 'giftcard',
						'posts_per_page' => -1,
					);
					$loop     = new WP_Query( $args );
					$template = array();
					foreach ( $loop->posts as $key => $value ) {
						$template_id              = $value->ID;
						$template_title           = $value->post_title;
						$template[ $template_id ] = $template_title;
						$tewgclelect              = '';
						if ( wps_uwgc_pro_active() ) {
							if ( is_array( $selectedtemplate ) && ( null != $selectedtemplate ) && in_array( $template_id, $selectedtemplate ) ) {
								$tewgclelect = "selected='selected'";
							}
							?>
							<option value="<?php echo esc_attr( $template_id ); ?>"<?php echo esc_attr( $tewgclelect ); ?>><?php echo esc_attr( $template_title ); ?></option>
							<?php
						} else {
							if ( in_array( $template_title, $wps_get_lite_templates ) ) {
								$choosed_temp = '';
								if ( is_array( $selectedtemplate ) && ! empty( $selectedtemplate ) ) {
									if ( '1' < count( $selectedtemplate ) ) {
										if ( ! empty( $wps_get_pro_templates ) ) {
											$wps_get_lite_temp = array_diff( $selectedtemplate, $wps_get_pro_templates );
											$wps_index         = array_keys( $wps_get_lite_temp )[0];
											if ( 0 !== count( $wps_get_lite_temp ) ) {
												$choosed_temp = $wps_get_lite_temp[ $wps_index ];
											}
										} else {
											$choosed_temp = $selectedtemplate[0];
										}
									} else {
										$choosed_temp = $selectedtemplate[0];
									}
								}
								if ( $choosed_temp == $template_id ) {
									$tewgclelect = "selected='selected'";
								}
								if ( ! in_array( $template_id, $wps_get_pro_templates ) ) {
									?>
									<option value="<?php echo esc_attr( $template_id ); ?>"<?php echo esc_attr( $tewgclelect ); ?>><?php echo esc_attr( $template_title ); ?></option>
									<?php
								}
							}
						}
					}
					?>
					</select>
				</p>
				<?php
				wp_nonce_field( 'wps_wgm_lite_nonce', 'wps_wgm_product_nonce_field' );
				do_action( 'wps_wgm_giftcard_product_type_field', $product_id );
				echo '</div>';
		}
	}

	/**
	 * Saves the all required details for each product
	 *
	 * @since 1.0.0
	 * @param int $post_id post id.
	 * @name wps_wgm_save_post()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_save_post( $post_id ) {
		global $post;
		if ( isset( $post_id ) ) {
			if ( ! current_user_can( 'edit_post', $post_id ) || ! is_admin() ) {
				return;
			}
			$product_id = $post_id;
			$product    = wc_get_product( $product_id );
			if ( isset( $product ) && is_object( $product ) ) {
				if ( $product->get_type() == 'wgm_gift_card' ) {
					if ( ! isset( $_POST['wps_wgm_product_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wps_wgm_product_nonce_field'] ) ), 'wps_wgm_lite_nonce' ) ) {
						return;
					}
					$general_settings     = get_option( 'wps_wgm_general_settings', array() );
					$wps_wgm_categ_enable = $this->wps_common_fun->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_categ_enable' );
					if ( '' === $wps_wgm_categ_enable || 'off' === $wps_wgm_categ_enable ) {
						$term       = __( 'Gift Card', 'woo-gift-cards-lite' );
						$taxonomy   = 'product_cat';
						$term_exist = term_exists( $term, $taxonomy );
						if ( 0 == $term_exist || null == $term_exist ) {
							$args['slug'] = 'wps_wgm_giftcard';
							$term_exist   = wp_insert_term( $term, $taxonomy, $args );
						}
						wp_set_object_terms( $product_id, 'wgm_gift_card', 'product_type' );
						wp_set_post_terms( $product_id, $term_exist, $taxonomy );
					}
					$wps_wgm_pricing  = array();
					$selected_pricing = isset( $_POST['wps_wgm_pricing'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_pricing'] ) ) : false;
					if ( $selected_pricing ) {
						$default_price = isset( $_POST['wps_wgm_default'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_default'] ) ) : 0;
						update_post_meta( $product_id, '_regular_price', $default_price );
						update_post_meta( $product_id, '_price', $default_price );
						$wps_wgm_pricing['default_price'] = $default_price;
						$wps_wgm_pricing['type']          = $selected_pricing;
						if ( ! isset( $_POST['wps_wgm_email_template'] ) || empty( $_POST['wps_wgm_email_template'] ) ) {
							$args     = array(
								'post_type'      => 'giftcard',
								'posts_per_page' => -1,
							);
							$loop     = new WP_Query( $args );
							$template = array();
							if ( $loop->have_posts() ) :
								while ( $loop->have_posts() ) :
									$loop->the_post();
									$template_id = $loop->post->ID;
									$template[]  = $template_id;
								endwhile;
							endif;

							$pro_template = get_option( 'wps_uwgc_templateid', array() );
							$temp_array   = array();
							if ( ! wps_uwgc_pro_active() && is_array( $pro_template ) && ! empty( $pro_template ) ) {
								foreach ( $template as $value ) {
									if ( ! in_array( $value, $pro_template ) ) {
										$temp_array[] = $value;
									}
								}
								if ( isset( $temp_array ) && ! empty( $temp_array ) ) {
									$wps_wgm_pricing['template'] = array( $temp_array[0] );
								}
							} else {
								$wps_wgm_pricing['template'] = array( $template[0] );
							}
						} else {
							$wps_wgm_pricing['template'] = map_deep( wp_unslash( $_POST['wps_wgm_email_template'] ), 'sanitize_text_field' );
						}
						if ( ! isset( $_POST['wps_wgm_email_defualt_template'] ) || empty( $_POST['wps_wgm_email_defualt_template'] ) ) {
							$wps_wgm_pricing['by_default_tem'] = $wps_wgm_pricing['template'];
						} else {
							$wps_wgm_pricing['by_default_tem'] = sanitize_text_field( wp_unslash( $_POST['wps_wgm_email_defualt_template'] ) );
						}
						switch ( $selected_pricing ) {
							case 'wps_wgm_range_price':
								$from                    = isset( $_POST['wps_wgm_from_price'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_from_price'] ) ) : 0;
								$to                      = isset( $_POST['wps_wgm_to_price'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_to_price'] ) ) : 0;
								$wps_wgm_pricing['type'] = $selected_pricing;
								$wps_wgm_pricing['from'] = $from;
								$wps_wgm_pricing['to']   = $to;
								break;
							case 'wps_wgm_selected_price':
								$price                    = isset( $_POST['wps_wgm_selected_price'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_selected_price'] ) ) : 0;
								$wps_wgm_pricing['type']  = $selected_pricing;
								$wps_wgm_pricing['price'] = $price;
								break;

							case 'wps_wgm_user_price':
								$wps_wgm_pricing['type']           = $selected_pricing;
								$wps_wgm_pricing['min_user_price'] = isset( $_POST['wps_wgm_min_user_price'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_min_user_price'] ) ) : 0;
								break;

							case 'wps_wgm_variable_price':
								$wps_wgm_pricing['wps_wgm_variation_text']  = isset( $_POST['wps_wgm_variation_text'] ) ? map_deep( wp_unslash( $_POST['wps_wgm_variation_text'] ), 'sanitize_text_field' ) : array();
								$wps_wgm_pricing['wps_wgm_variation_price'] = isset( $_POST['wps_wgm_variation_price'] ) ? map_deep( wp_unslash( $_POST['wps_wgm_variation_price'] ), 'sanitize_text_field' ) : array();
								break;

							case 'wps_wgm_selected_with_price_range':
								$from = isset( $_POST['wps_wgm_from_price'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_from_price'] ) ) : 0;
								$to = isset( $_POST['wps_wgm_to_price'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_to_price'] ) ) : 0;
								$price = isset( $_POST['wps_wgm_selected_price'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_selected_price'] ) ) : 0;
								$wps_wgm_pricing['type'] = $selected_pricing;
								$wps_wgm_pricing['from'] = $from;
								$wps_wgm_pricing['to'] = $to;
								$wps_wgm_pricing['price'] = $price;
								break;

							default:
								// nothing for default.
						}
					}
					// compatibility with product filter by price.
					if ( wps_uwgc_pro_active() ) {
						do_action( 'wps_wgm_set_dicount_price_for_filter', $product_id );
					} else {
						global $wpdb;
						$table_name = $wpdb->prefix . 'wc_product_meta_lookup';
						$sql        = 'UPDATE ' . $table_name . ' SET `min_price`=' . $default_price . ',`max_price`=' . $default_price . ' WHERE product_id = ' . $product_id;
						$results    = $wpdb->get_results( '%d', $sql );
					}
					do_action( 'wps_wgm_product_pricing', $wps_wgm_pricing );
					$wps_wgm_pricing = apply_filters( 'wps_wgm_product_pricing', $wps_wgm_pricing );
					update_post_meta( $product_id, 'wps_wgm_pricing', $wps_wgm_pricing );
					do_action( 'wps_wgm_giftcard_product_type_save_fields', $product_id );
				}
			}
		}
	}

	/**
	 * Hides some of the tabs if the Product is Gift Card
	 *
	 * @since 1.0.0
	 * @name wps_wgm_woocommerce_product_data_tabs()
	 * @param array $tabs product tabs.
	 * @return $tabs.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_woocommerce_product_data_tabs( $tabs ) {

		if ( isset( $tabs ) && ! empty( $tabs ) ) {
			foreach ( $tabs as $key => $tab ) {
				if ( 'general' != $key && 'advanced' != $key && 'shipping' != $key ) {
					if ( isset( $tabs[ $key ]['class'] ) && is_array( $tabs[ $key ]['class'] ) ) {
						array_push( $tabs[ $key ]['class'], 'hide_if_wgm_gift_card' );
					}
				}
			}
			$tabs = apply_filters( 'wps_wgm_product_data_tabs', $tabs );
		}

		return $tabs;
	}

	/**
	 * Add the Gift Card Coupon code as an item meta for each Gift Card Order
	 *
	 * @since 1.0.0
	 * @name wps_wgm_woocommerce_after_order_itemmeta()
	 * @param int   $item_id item id.
	 * @param array $item item.
	 * @param array $_product product.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_woocommerce_after_order_itemmeta( $item_id, $item, $_product ) {
		$secure_nonce      = wp_create_nonce( 'wps-gc-auth-nonce' );
		$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-gc-auth-nonce' );
		if ( ! $id_nonce_verified ) {
				wp_die( esc_html__( 'Nonce Not verified', 'woo-gift-cards-lite' ) );
		}
		if ( ! current_user_can( 'edit_shop_orders' ) ) {
			return;
		}
		$wps_wgc_enable = wps_wgm_giftcard_enable();
		if ( $wps_wgc_enable ) {
			if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
				// HPOS Enabled.
				$post_id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
			} else {
				$post_id = isset( $_GET['post'] ) ? sanitize_text_field( wp_unslash( $_GET['post'] ) ) : '';
			}
			$order_id     = $post_id;
			$order        = new WC_Order( $order_id );
			$order_status = $order->get_status();
			if ( 'completed' == $order_status || 'processing' == $order_status ) {
				if ( null != $_product ) {
					$product_id = $_product->get_id();
					if ( isset( $product_id ) && ! empty( $product_id ) ) {
						$product_types    = wp_get_object_terms( $product_id, 'product_type' );
						$wps_gift_product = wps_wgm_hpos_get_meta_data( $order_id, 'sell_as_a_gc' . $item_id, true );
						if ( isset( $product_types[0] ) || 'on' === $wps_gift_product ) {
							$product_type = isset( $product_types[0] ) ? $product_types[0]->slug : '';
							if ( 'wgm_gift_card' === $product_type || 'gw_gift_card' === $product_type || 'on' === $wps_gift_product ) {
								$giftcoupon = wps_wgm_hpos_get_meta_data( $order_id, "$order_id#$item_id", true );

								if ( empty( $giftcoupon ) ) {
									$giftcoupon = wps_wgm_hpos_get_meta_data( $order_id, "$order_id#$product_id", true );
								}
								if ( is_array( $giftcoupon ) && ! empty( $giftcoupon ) ) {
									?>
									<p style="margin:0;"><b><?php esc_html_e( 'Gift Coupon', 'woo-gift-cards-lite' ); ?> :</b>
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
								do_action( 'wps_wgm_after_order_itemmeta', $item_id, $item, $_product );
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
	 * @name wps_wgm_woocommerce_hidden_order_itemmeta()
	 * @param array $order_items order items.
	 * @return $order_items.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_woocommerce_hidden_order_itemmeta( $order_items ) {
		if ( ! current_user_can( 'edit_shop_orders' ) ) {
			return;
		}
		array_push( $order_items, 'Original Price', 'Selected Template' );
		$order_items = apply_filters( 'wps_wgm_giftcard_hidden_order_itemmeta', $order_items );
		return $order_items;
	}

	/**
	 * Create custom post name Giftcard for creating Giftcard Template
	 *
	 * @since 1.0.0
	 * @name wps_wgm_giftcard_custompost
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_giftcard_custom_post() {
		$labels           = array(
			'name'               => esc_html__( 'Gift Cards', 'woo-gift-cards-lite' ),
			'singular_name'      => esc_html__( 'Gift Card', 'woo-gift-cards-lite' ),
			'menu_name'          => esc_html__( 'Gift Cards', 'woo-gift-cards-lite' ),
			'name_admin_bar'     => esc_html__( 'Gift Card', 'woo-gift-cards-lite' ),
			'add_new'            => esc_html__( 'Add New', 'woo-gift-cards-lite' ),
			'add_new_item'       => esc_html__( 'Add New Gift Card', 'woo-gift-cards-lite' ),
			'new_item'           => esc_html__( 'New Gift Card', 'woo-gift-cards-lite' ),
			'edit_item'          => esc_html__( 'Edit Gift Card', 'woo-gift-cards-lite' ),
			'view_item'          => esc_html__( 'View Gift Card', 'woo-gift-cards-lite' ),
			'all_items'          => esc_html__( 'Templates', 'woo-gift-cards-lite' ),
			'search_items'       => esc_html__( 'Search Gift Cards', 'woo-gift-cards-lite' ),
			'parent_item_colon'  => esc_html__( 'Parent Gift Cards:', 'woo-gift-cards-lite' ),
			'not_found'          => esc_html__( 'No gift cards found.', 'woo-gift-cards-lite' ),
			'not_found_in_trash' => esc_html__( 'No gift cards found in Trash.', 'woo-gift-cards-lite' ),
		);
		$wps_wgm_template = array(
			'create_posts' => 'do_not_allow',
		);
		$wps_wgm_template = apply_filters( 'wps_wgm_template_capabilities', $wps_wgm_template );
		$args             = array(
			'labels'             => $labels,
			'description'        => esc_html__( 'Description.', 'woo-gift-cards-lite' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'giftcard' ),
			'capability_type'    => 'post',
			'capabilities'       => $wps_wgm_template,
			'map_meta_cap'       => true,
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-format-gallery',
			'supports'           => array( 'title', 'editor', 'thumbnail' ),
		);
		register_post_type( 'giftcard', $args );

		$this->wps_wgm_list_shortcode_in_gutenburg_block();
	}

	/**
	 * This function is to add meta field like field for instruction how to use shortcode in email template
	 *
	 * @since 1.0.0
	 * @name wps_wgm_edit_form_after_title
	 * @param object $post post.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_edit_form_after_title( $post ) {
		$wps_wgm_post_type = get_post_type( $post );
		if ( isset( $wps_wgm_post_type ) && 'giftcard' == $wps_wgm_post_type ) {
			?>
				<div class="postbox" id="wps_wgm_mail_instruction" style="display: block;">
					<h2 class="wps_wgm_handle"><span><?php esc_html_e( 'Instruction for using Shortcode', 'woo-gift-cards-lite' ); ?></span></h2>
					<div class="wps_wgm_inside">
						<table  class="form-table">
							<tr>
								<th><?php esc_html_e( 'SHORTCODE', 'woo-gift-cards-lite' ); ?></th>
								<th><?php esc_html_e( 'DESCRIPTION.', 'woo-gift-cards-lite' ); ?></th>			
							</tr>
							<tr>
								<td>[LOGO]</td>
								<td><?php esc_html_e( 'Replace with the logo of the company on the email template.', 'woo-gift-cards-lite' ); ?></td>			
							</tr>
							<tr>
								<td>[TO]</td>
								<td><?php esc_html_e( 'Replace with the email of the user to which gift card send.', 'woo-gift-cards-lite' ); ?></td>
							</tr>
							<tr>
								<td>[FROM]</td>
								<td><?php esc_html_e( 'Replace with email/name of the user who sends the gift card.', 'woo-gift-cards-lite' ); ?></td>
							</tr>
							<tr>
								<td>[MESSAGE]</td>
								<td><?php esc_html_e( 'Replace with Message of the user who sends the gift card.', 'woo-gift-cards-lite' ); ?></td>
							</tr>
							<tr>
								<td>[AMOUNT]</td>
								<td><?php esc_html_e( 'Replace with Gift Card Amount.', 'woo-gift-cards-lite' ); ?></td>
							</tr>
							<tr>
								<td>[COUPON]</td>
								<td><?php esc_html_e( 'Replace with Gift Card Coupon Code.', 'woo-gift-cards-lite' ); ?></td>
							</tr>
							<tr>
								<td>[DEFAULTEVENT]</td>
								<td><?php esc_html_e( 'Replace with Default event image set on Setting.', 'woo-gift-cards-lite' ); ?></td>
							</tr>
							<tr>
								<td>[EXPIRYDATE]</td>
								<td><?php esc_html_e( 'Replace with Gift Card Expiry Date.', 'woo-gift-cards-lite' ); ?></td>
							</tr>
							<tr>
								<td>[DISCLAIMER]</td>
								<td><?php esc_html_e( 'Replace with Disclaimer on Gift Card.', 'woo-gift-cards-lite' ); ?></td>
							</tr>
							<tr>
								<td>[PURCHASEDATE]</td>
								<td><?php esc_html_e( 'Replace with Gift Card Purchase Date.', 'woo-gift-cards-lite' ); ?></td>
							</tr>
							<tr>
								<td>[VARIABLEDESCRIPTION]</td>
								<td><?php esc_html_e( 'Replaced with Variable Product Description.', 'woo-gift-cards-lite' ); ?></td>
							</tr>
							<tr>
								<td>[DELIVERYMETHOD]</td>
								<td><?php esc_html_e( 'Replaced with giftcard delivery method.', 'woo-gift-cards-lite' ); ?></td>
							</tr>
							
						<?php
						do_action( 'wps_wgm_template_custom_shortcode' );
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
	 * @since 1.0.0
	 * @name wps_wgm_mothers_day_template
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_mothers_day_template() {

		$wps_wgm_template = get_option( 'wps_wgm_new_mom_template', '' );
		if ( empty( $wps_wgm_template ) ) {
			update_option( 'wps_wgm_new_mom_template', true );
			$filename = array( WPS_WGC_URL . 'assets/images/mom.png' );

			if ( isset( $filename ) && is_array( $filename ) && ! empty( $filename ) ) {
				foreach ( $filename as $key => $value ) {
					$upload_file = wp_upload_bits( basename( $value ), null, $this->wps_common_fun->wps_wgm_get_file_content( $value ) );
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
						require_once ABSPATH . 'wp-admin/includes/image.php';

						// Generate the metadata for the attachment, and update the database record.
						$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

						wp_update_attachment_metadata( $attach_id, $attach_data );
						$arr[] = $attach_id;

					}
				}
			}
			$wps_wgm_new_mom_template = "<style>@page{ margin: 20px;}</style><center><div style='max-width:600px;margin:auto'><div style='display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;'>(Optional) This text will appear in the inbox preview, but not the email body.</div><table class='logo-container' style='margin: auto;width: 100%;' role='presentation' border='0' cellspacing='0' cellpadding='0' align='center'><tbody><tr><td style='width:100%;text-align: center; background-color: #00897B; color: #fff; font-weight: bold; padding: 20px 0; font-size: 20px; font-family: sans-serif;'>[LOGO]</td></tr></tbody></table><table class='left-right-container' style='margin: auto;width: 100%;' role='presentation' border='0' cellspacing='0' cellpadding='0'><tbody><tr><td dir='ltr' valign='top' style='background:#00897B;width:100%;padding:20px;'><table role='presentation' border='0' cellspacing='0' cellpadding='0' style='max-width:600px;margin:auto;'><tbody><tr><td class='stack-column-center default-img-left' valign='middle' style='text-align: left;width:50%;padding:0 0 10px;'><img src='[FEATUREDIMAGE]' style='width: 100%;margin: auto;max-width:300px;'></td><td class='stack-column-center default-img-content' style='text-align: left;width:50%;padding:0;' valign='middle'><table><tbody><tr><td dir='ltr' style='text-align: left;padding: 0 20px 0;font-family: sans-serif; font-size: 60px; line-height: 70px; color: #fff; height: auto; word-wrap: break-word;' valign='top'>I LOVE YOU MOM</td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><table class='disclaimer-container' role='presentation' border='0' style='width: 100%;' cellspacing='0' cellpadding='0'><tbody><tr><td style='text-align: center;padding: 20px; font-family: sans-serif; font-size: 16px; mso-height-rule: exactly; line-height: 20px; color: #000; background: #efefef;'>[MESSAGE]</td></tr></tbody></table><table class='coupon-code-container' style='margin: auto;width: 100%;' role='presentation' border='0' cellspacing='0' cellpadding='0' align='center'><tbody><tr><td style='width:100%;'><table role='presentation' border='0' cellspacing='0' cellpadding='0' align='center' style='padding:30px 0;width:100%;margin: auto;background:url([BACK]);background-size: cover;'><tbody><tr><td dir='ltr' style='padding:10px;width: 100%;' valign='top'><table role='presentation' border='0' cellspacing='0' cellpadding='0' align='center' style='width: 100%;'><tbody><tr><td dir='ltr' style='font-size: 18px;text-align: center;font-weight: normal; font-family: sans-serif; padding: 2px 0; color: #00897B;' valign='top'>COUPON CODE </td></tr><tr><td dir='ltr' style='font-size: 32px;text-align: center;font-weight: normal; font-family: sans-serif; padding: 2px 0; color: #000;' valign='top'>[COUPON] </td></tr><tr><td dir='ltr' style='font-size: 18px;text-align: center;font-weight: normal; font-family: sans-serif; padding: 2px 0; color: #00897B;' valign='top'>ED: [EXPIRYDATE] </td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><table class='left-right-container' style='margin: auto;width: 100%;' role='presentation' border='0' cellspacing='0' cellpadding='0'><tbody><tr><td dir='ltr' valign='top' style='background:#efefef;width:100%;padding:20px;'><table role='presentation' border='0' cellspacing='0' cellpadding='0' style='width:80%;margin: auto;'><tbody><tr><td class='stack-column-center default-img-left' valign='top' style='text-align: right;width:50%;padding:0 0 10px;font-size: 32px;font-weight:bold; line-height: 48px; color: #000;'>[AMOUNT]</td><td class='stack-column-center default-img-content' style='text-align: left;width:50%;padding:0;' valign='top'><table><tbody><tr><td dir='ltr' style='text-align: left;' valign='top'><img src='[ARROWIMAGE]' style='max-width:100px;'></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><table class='disclaimer-container' role='presentation' border='0' style='width: 100%;' cellspacing='0' cellpadding='0'><tbody><tr><td style='text-align: center;padding: 20px; font-family: sans-serif; font-size: 16px; mso-height-rule: exactly; line-height: 20px; color: #fff; background: #00897B;'>[DISCLAIMER]</td></tr></tbody></table></div></center><style>html, body{ margin: 0 auto !important; padding: 0 !important; height: 100% !important; width: 100% !important;} *{ -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; box-sizing: border-box;} div[style*='margin: 16px 0']{ margin: 0 !important;} table, td{ mso-table-lspace: 0pt !important; mso-table-rspace: 0pt !important;} table{ border-spacing: 0 !important; border-collapse: collapse !important; table-layout: fixed !important; margin: 0 auto !important;} table table table{ table-layout: auto;} img{ -ms-interpolation-mode: bicubic;} *[x-apple-data-detectors], .x-gmail-data-detectors, .x-gmail-data-detectors *, .aBn{ border-bottom: 0 !important; cursor: default !important; color: inherit !important; text-decoration: none !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important;} .a6S{ display: none !important; opacity: 0.01 !important;} img.g-img+div{ display: none !important;} .button-link{ text-decoration: none !important;}</style>";

			$gifttemplate_new = array(
				'post_title'   => 'Love You Mom',
				'post_content' => $wps_wgm_new_mom_template,
				'post_status'  => 'publish',
				'post_author'  => get_current_user_id(),
				'post_type'    => 'giftcard',
			);
			$parent_post_id   = wp_insert_post( $gifttemplate_new );
			set_post_thumbnail( $parent_post_id, $arr[0] );
		}
	}

	/**
	 * Added New Template
	 *
	 * @since 1.0.0
	 * @name wps_wgm_new_template
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_new_template() {

		$wps_wgm_template = get_option( 'wps_wgm_gift_for_you', '' );
		if ( empty( $wps_wgm_template ) ) {
			update_option( 'wps_wgm_gift_for_you', true );
			$filename = array( WPS_WGC_URL . 'assets/images/giftcard.jpg' );
			if ( isset( $filename ) && is_array( $filename ) && ! empty( $filename ) ) {
				foreach ( $filename as $key => $value ) {
					$upload_file = wp_upload_bits( basename( $value ), null, $this->wps_common_fun->wps_wgm_get_file_content( $value ) );
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
						require_once ABSPATH . 'wp-admin/includes/image.php';

						// Generate the metadata for the attachment, and update the database record.
						$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

						wp_update_attachment_metadata( $attach_id, $attach_data );
						$arr[] = $attach_id;
					}
				}
			}

			$wps_wgm_gift_temp_for_you = "<style>@page{ margin: 20px}</style><center><div style='max-width:600px;margin:auto'><div style='display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;'>(Optional) This text will appear in the inbox preview, but not the email body.</div><table class='logo-container' style='margin: auto;width: 100%;' role='presentation' border='0' cellspacing='0' cellpadding='0' align='center'><tbody><tr><td style='width:100%;text-align: center; background-color: #fff; color: #000; font-weight: bold; padding: 20px 0; font-size: 20px; font-family: sans-serif;'>[LOGO]</td></tr></tbody></table><table class='featured-image-container' style='margin: auto;width: 100%;' role='presentation' border='0' cellspacing='0' cellpadding='0' align='center'><tbody><tr><td style='text-align: center;padding: 0 0 20px;width:100%;background:#fff;'><img src='[FEATUREDIMAGE]' style='margin:auto;width:100%;'></td></tr></tbody></table><table class='left-right-container' style='margin: auto;width: 100%;' role='presentation' border='0' cellspacing='0' cellpadding='0'><tbody><tr><td dir='ltr' valign='top' style='background:#fff;width:100%;padding:20px;'><table role='presentation' border='0' cellspacing='0' cellpadding='0' style='width:100%;'><tbody><tr><td class='stack-column-center default-img-left' valign='top' style='text-align: left;width:50%;padding:0 0 10px;border-right: 2px solid #ddd;'><table><tbody><tr><td dir='ltr' style='text-align: left;padding: 0 0 30px;font-family: sans-serif; font-size: 28px; line-height: 32px; color: #000; height: auto; word-wrap: break-word;' valign='top'><strong class='woocommerce-Price-amount amount'>[AMOUNT]/-</strong></td></tr><tr><td dir='ltr' style='font-size: 16px;text-align: left;font-weight: normal; font-family: sans-serif; padding: 5px 0; color: #000;' valign='top'>Coupon Code: <strong style='font-size: 18px;'>[COUPON] </strong></td></tr><tr><td dir='ltr' style='font-size: 16px;text-align: left;font-weight: normal; font-family: sans-serif; padding: 5px 0; color: #000;' valign='top'>Expiry Date: <strong style='font-size: 18px;'>[EXPIRYDATE] </strong></td></tr></tbody></table></td><td class='stack-column-center default-img-content' style='text-align: left;width:50%;padding:0;' valign='top'><table><tbody><tr><td dir='ltr' style='text-align: left;padding: 0 20px 0;font-family: sans-serif; font-size: 18px; line-height: 24px; color: #000; height: auto; word-wrap: break-word;' valign='top'>[MESSAGE]</td></tr><tr><td dir='ltr' style='text-align: left;padding: 30px 20px 0;font-family: sans-serif; font-size: 18px; line-height: 24px; color: #000; word-wrap: break-word;' valign='top'><span style='display: inline-block; font-size: 15px; vertical-align: top;'><strong>From-&nbsp;</strong>[FROM]</span></td></tr><tr><td dir='ltr' style='text-align: left;padding: 5px 20px 0;font-family: sans-serif; font-size: 18px; line-height: 24px; color: #000; word-wrap: break-word;' valign='top'><span style='display: inline-block; font-size: 15px; vertical-align: top;'><strong>To-&nbsp;</strong>[TO]</span></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><table class='disclaimer-container' role='presentation' border='0' style='width: 100%;' cellspacing='0' cellpadding='0'><tbody><tr><td style='text-align: center;padding: 20px; font-family: sans-serif; font-size: 16px; mso-height-rule: exactly; line-height: 20px; color: #000; border-top:2px solid #ddd;background:#fff;'>[DISCLAIMER]</td></tr></tbody></table></div></center><style>html, body{ margin: 0 auto !important; padding: 0 !important; height: 100% !important; width: 100% !important;} *{ -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; box-sizing: border-box;} div[style*='margin: 16px 0']{ margin: 0 !important;} table, td{ mso-table-lspace: 0pt !important; mso-table-rspace: 0pt !important;} table{ border-spacing: 0 !important; border-collapse: collapse !important; table-layout: fixed !important; margin: 0 auto !important;} table table table{ table-layout: auto;} img{ -ms-interpolation-mode: bicubic;} *[x-apple-data-detectors], .x-gmail-data-detectors, .x-gmail-data-detectors *, .aBn{ border-bottom: 0 !important; cursor: default !important; color: inherit !important; text-decoration: none !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important;} .a6S{ display: none !important; opacity: 0.01 !important;} img.g-img+div{ display: none !important;} .button-link{ text-decoration: none !important;}</style>";

			$gifttemplate_new = array(
				'post_title'   => 'Gift for You',
				'post_content' => $wps_wgm_gift_temp_for_you,
				'post_status'  => 'publish',
				'post_author'  => get_current_user_id(),
				'post_type'    => 'giftcard',
			);
			$parent_post_id   = wp_insert_post( $gifttemplate_new );
			set_post_thumbnail( $parent_post_id, $arr[0] );
		}

	}

	/**
	 * Added custom Template
	 *
	 * @since 1.0.0
	 * @name wps_wgm_insert_custom_template
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_insert_custom_template() {
		$wps_wgm_template = get_option( 'wps_wgm_insert_custom_template', '' );
		if ( empty( $wps_wgm_template ) ) {
			update_option( 'wps_wgm_insert_custom_template', true );
			$filename = array( WPS_WGC_URL . 'assets/images/custom_template.png' );
			if ( isset( $filename ) && is_array( $filename ) && ! empty( $filename ) ) {
				foreach ( $filename as $key => $value ) {
					$upload_file = wp_upload_bits( basename( $value ), null, $this->wps_common_fun->wps_wgm_get_file_content( $value ) );
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
						require_once ABSPATH . 'wp-admin/includes/image.php';

						// Generate the metadata for the attachment, and update the database record.
						$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

						wp_update_attachment_metadata( $attach_id, $attach_data );
						$arr[] = $attach_id;
					}
				}
			}

			$wps_wgm_custom_template_html = "<style>@page{ margin: 20px}</style><center><div style='max-width:600px;margin:auto'><div style='display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;'>(Optional) This text will appear in the inbox preview, but not the email body.</div><table class='logo-container' style='margin: auto;width: 100%;' role='presentation' border='0' cellspacing='0' cellpadding='0' align='center'><tbody><tr><td style='width:100%;text-align: center; background-color: #0e0149; color: #fff; font-weight: bold; padding: 20px 0; font-size: 20px; font-family: sans-serif;'>[LOGO]</td></tr></tbody></table><table class='coupon-code-container' style='margin: auto;width: 100%;' role='presentation' border='0' cellspacing='0' cellpadding='0' align='center'><tbody><tr><td style='padding:10px 0; background:#0e0149;width:100%;'><table role='presentation' border='0' cellspacing='0' cellpadding='0' align='center' style='width:95%;margin: auto;'><tbody><tr><td dir='ltr' style='border:2px dashed #fff; padding:10px;width: 100%;' valign='top'><table role='presentation' border='0' cellspacing='0' cellpadding='0' align='center' style='width: 100%;'><tbody><tr><td dir='ltr' style='font-size: 18px;text-align: center;font-weight: normal; font-family: sans-serif; padding: 5px 0; color: #fff;' valign='top'>COUPON CODE </td></tr><tr><td dir='ltr' style='font-size: 24px;text-align: center;font-weight: normal; font-family: sans-serif; padding: 5px 0; color: #fff;' valign='top'>[COUPON] </td></tr><tr><td dir='ltr' style='font-size: 20px;text-align: center;font-weight: normal; font-family: sans-serif; padding: 5px 0; color: #fff;' valign='top'>ED: [EXPIRYDATE] </td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><table class='left-right-container' style='margin: auto;width: 100%;' role='presentation' border='0' cellspacing='0' cellpadding='0'><tbody><tr><td dir='ltr' valign='top' style='background:#d7ceff;width:100%;padding:20px;'><table role='presentation' border='0' cellspacing='0' cellpadding='0' style='width:100%;'><tbody><tr><td class='stack-column-center default-img-left' valign='top' style='text-align: left;width:50%;padding:0 0 10px;'><img src='[DEFAULTEVENT]' style='width: 100%;margin: auto;max-width:350px;'></td><td class='stack-column-center default-img-content' style='text-align: left;width:50%;padding:0;' valign='top'><table><tbody><tr><td dir='ltr' style='text-align: left;padding: 0 20px 0;font-family: sans-serif; font-size: 18px; line-height: 24px; color: #000; height: auto; word-wrap: break-word;' valign='top'>[MESSAGE]</td></tr><tr><td dir='ltr' style='text-align: left;padding: 30px 20px 0;font-family: sans-serif; font-size: 18px; line-height: 24px; color: #000; word-wrap: break-word;' valign='top'><span style='display: inline-block; font-size: 15px; vertical-align: top;'><strong>From-&nbsp;</strong>[FROM]</span></td></tr><tr><td dir='ltr' style='text-align: left;padding: 5px 20px 0;font-family: sans-serif; font-size: 18px; line-height: 24px; color: #000; word-wrap: break-word;' valign='top'><span style='display: inline-block; font-size: 15px; vertical-align: top;'><strong>To-&nbsp;</strong>[TO]</span></td></tr><tr><td dir='ltr' style='text-align: left;padding: 30px 20px 25px;font-family: sans-serif; font-size: 28px; line-height: 32px; color: #0e0149; height: auto; word-wrap: break-word;' valign='top'><strong class='woocommerce-Price-amount amount'>[AMOUNT]/-</strong></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><table class='disclaimer-container' role='presentation' border='0' style='width: 100%;' cellspacing='0' cellpadding='0'><tbody><tr><td style='text-align: center;padding: 20px; font-family: sans-serif; font-size: 16px; mso-height-rule: exactly; line-height: 20px; color: #fff; background: #0e0149;'>[DISCLAIMER]</td></tr></tbody></table></div></center><style>html, body{ margin: 0 auto !important; padding: 0 !important; height: 100% !important; width: 100% !important;} *{ -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; box-sizing: border-box;} div[style*='margin: 16px 0']{ margin: 0 !important;} table, td{ mso-table-lspace: 0pt !important; mso-table-rspace: 0pt !important;} table{ border-spacing: 0 !important; border-collapse: collapse !important; table-layout: fixed !important; margin: 0 auto !important;} table table table{ table-layout: auto;} img{ -ms-interpolation-mode: bicubic;} *[x-apple-data-detectors], .x-gmail-data-detectors, .x-gmail-data-detectors *, .aBn{ border-bottom: 0 !important; cursor: default !important; color: inherit !important; text-decoration: none !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important;} .a6S{ display: none !important; opacity: 0.01 !important;} img.g-img+div{ display: none !important;} .button-link{ text-decoration: none !important;}</style>";

			$wps_wgm_template = array(
				'post_title'   => 'Custom Template',
				'post_content' => $wps_wgm_custom_template_html,
				'post_status'  => 'publish',
				'post_author'  => get_current_user_id(),
				'post_type'    => 'giftcard',
			);
			$parent_post_id   = wp_insert_post( $wps_wgm_template );
			set_post_thumbnail( $parent_post_id, $arr[0] );
		}
	}

	/**
	 * Added Christmas Template
	 *
	 * @since 1.0.0
	 * @name wps_wgm_insert_christmas_template
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_insert_christmas_template() {
		$wps_wgm_template = get_option( 'wps_wgm_merry_christmas_template', '' );
		if ( empty( $wps_wgm_template ) ) {
			update_option( 'wps_wgm_merry_christmas_template', true );
			$filename = array( WPS_WGC_URL . 'assets/images/merry_christmas.png' );
			if ( isset( $filename ) && is_array( $filename ) && ! empty( $filename ) ) {
				foreach ( $filename as $key => $value ) {
					$upload_file = wp_upload_bits( basename( $value ), null, $this->wps_common_fun->wps_wgm_get_file_content( $value ) );
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
						require_once ABSPATH . 'wp-admin/includes/image.php';

						// Generate the metadata for the attachment, and update the database record.
						$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

						wp_update_attachment_metadata( $attach_id, $attach_data );
						$arr[] = $attach_id;
					}
				}
			}

				$wps_wgm_merry_christmas_template = "<style>@page{ margin: 20px}</style><center><div style='max-width:600px;margin:auto'><div style='display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;'>(Optional) This text will appear in the inbox preview, but not the email body.</div><table class='header-container' role='presentation' border='0' style='width: 100%;' cellspacing='0' cellpadding='0'><tbody><tr><td style='width: 100%;background:#A10005;padding:0;'><img src='[HEADER]' alt='Header' style='max-width:100%;width:100%;'></td></tr></tbody></table><table class='logo-container' style='margin: auto;width: 100%;' role='presentation' border='0' cellspacing='0' cellpadding='0' align='center'><tbody><tr><td style='width:100%;text-align: center; background-color: #A10005; color: #ffffff; font-weight: bold; padding: 20px 0; font-size: 20px; font-family: sans-serif;'>[LOGO]</td></tr></tbody></table><table class='featured-image-container' style='margin: auto;width: 100%;' role='presentation' border='0' cellspacing='0' cellpadding='0' align='center'><tbody><tr><td style='text-align: center;padding: 30px 0;width:100%;background:#A10005;'><img src='[CHRISTMASTITLE]' style='margin:auto;max-width:400px;' alt='Center'></td></tr></tbody></table><table class='amount-container' style='margin: auto;width: 100%;' role='presentation' border='0' cellspacing='0' cellpadding='0' align='center'><tbody><tr><td style='padding:20px 0; background:#A10005;width:100%;'><table role='presentation' border='0' cellspacing='0' cellpadding='0' align='center' style='width:80%;margin: auto;'><tbody><tr><td style='font-size: 20px; font-weight: normal;color: #fff; text-align: center; width: 100%; max-width: 800px;padding: 30px 0;'>[MESSAGE]</td></tr><tr><td dir='ltr' style='font-size: 28px;text-align: center;font-weight: normal; font-family: sans-serif; padding: 5px 0; color: #fff;' valign='top'><strong>[AMOUNT]</strong></td></tr></tbody></table></td></tr></tbody></table><table class='coupon-code-container' style='margin: auto;width: 100%;' role='presentation' border='0' cellspacing='0' cellpadding='0' align='center'><tbody><tr><td style='padding:30px 0; background:#A10005;width:100%;'><table role='presentation' border='0' cellspacing='0' cellpadding='0' align='center' style='width:60%;margin: auto;'><tbody><tr><td dir='ltr' style='border:2px dashed #fff; padding:10px;width: 100%;' valign='top'><table role='presentation' border='0' cellspacing='0' cellpadding='0' align='center' style='width: 100%;'><tbody><tr><td dir='ltr' style='font-size: 18px;text-align: center;font-weight: normal; font-family: sans-serif; padding: 2px 0; color: #fff;' valign='top'>COUPON CODE </td></tr><tr><td dir='ltr' style='font-size: 24px;text-align: center;font-weight: normal; font-family: sans-serif; padding: 2px 0; color: #fff;' valign='top'>[COUPON] </td></tr><tr><td dir='ltr' style='font-size: 18px;text-align: center;font-weight: normal; font-family: sans-serif; padding: 2px 0; color: #fff;' valign='top'>ED: [EXPIRYDATE] </td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><table class='footer-container' role='presentation' border='0' style='width: 100%;' cellspacing='0' cellpadding='0'><tbody><tr><td style='width: 100%;background:#A10005;padding:0;'><img src='[FOOTER]' alt='Footer' style='max-width:100%;width:100%;'></td></tr></tbody></table><table class='from-to-container' style='margin: auto;width: 100%;' role='presentation' border='0' cellspacing='0' cellpadding='0' align='center'><tbody><tr><td class='wps-woo-email-left' style='padding:0 0 0 50px;background:#E3F3FD;'><table role='presentation' border='0' cellspacing='0' cellpadding='0' align='left' style='width: 100%;'><tbody><tr><td dir='ltr' style='text-align: left;padding: 10px 10px 5px;font-family: sans-serif; font-size: 18px; line-height: 24px; color: #A10005; word-wrap: break-word;' valign='top'><span style='display: inline-block; font-size: 18px; vertical-align: top;'><strong>From&nbsp;</strong></span></td></tr><tr><td dir='ltr' style='text-align: left;padding: 0 10px 0;font-family: sans-serif; font-size: 18px; line-height: 24px; color: #000; word-wrap: break-word;' valign='top'><span style='display: inline-block; font-size: 18px; vertical-align: top;'>[FROM]</span></td></tr></tbody></table></td><td class='wps-woo-email-right' style='padding:0 50px 0 0;background:#E3F3FD;'><table role='presentation' border='0' cellspacing='0' cellpadding='0' align='right' style='width: 100%;'><tbody><tr><td dir='ltr' style='text-align: right;padding: 10px 10px 5px;font-family: sans-serif; font-size: 18px; line-height: 24px; color: #A10005; word-wrap: break-word;' valign='top'><span style='display: inline-block; font-size: 18px; vertical-align: top;'><strong>To&nbsp;</strong></span></td></tr><tr><td dir='ltr' style='text-align: right;padding: 0 10px 0;font-family: sans-serif; font-size: 18px; line-height: 24px; color: #000; word-wrap: break-word;' valign='top'><span style='display: inline-block; font-size: 18px; vertical-align: top;'>[TO]</span></td></tr></tbody></table></td></tr></tbody></table><table class='disclaimer-container' role='presentation' border='0' style='width: 100%;' cellspacing='0' cellpadding='0'><tbody><tr><td style='text-align: center;padding: 20px; font-family: sans-serif; font-size: 16px; mso-height-rule: exactly; line-height: 20px; color: #000; background: #E3F3FD;'>[DISCLAIMER]</td></tr></tbody></table></div></center><style>html, body{ margin: 0 auto !important; padding: 0 !important; height: 100% !important; width: 100% !important} *{ -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; box-sizing: border-box} div[style*='margin: 16px 0']{ margin: 0 !important} table, td{ mso-table-lspace: 0pt !important; mso-table-rspace: 0pt !important} table{ border-spacing: 0 !important; border-collapse: collapse !important; table-layout: fixed !important; margin: 0 auto !important} table table table{ table-layout: auto} img{ -ms-interpolation-mode: bicubic} *[x-apple-data-detectors], .x-gmail-data-detectors, .x-gmail-data-detectors *, .aBn{ border-bottom: 0 !important; cursor: default !important; color: inherit !important; text-decoration: none !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important} .a6S{ display: none !important; opacity: 0.01 !important} img.g-img+div{ display: none !important} .button-link{ text-decoration: none !important}</style>";

				$header_image          = WPS_WGC_URL . 'assets/images/header1.png';
				$christmas_title_image = WPS_WGC_URL . 'assets/images/christmas-title.png';
				$footer_image          = WPS_WGC_URL . 'assets/images/footer1.png';

				// Replced with images.
				$wps_wgm_merry_christmas_template = str_replace( '[HEADER]', $header_image, $wps_wgm_merry_christmas_template );
				$wps_wgm_merry_christmas_template = str_replace( '[CHRISTMASTITLE]', $christmas_title_image, $wps_wgm_merry_christmas_template );
				$wps_wgm_merry_christmas_template = str_replace( '[FOOTER]', $footer_image, $wps_wgm_merry_christmas_template );

			$gifttemplate_new = array(
				'post_title'   => 'Merry Christmas Template',
				'post_content' => $wps_wgm_merry_christmas_template,
				'post_status'  => 'publish',
				'post_author'  => get_current_user_id(),
				'post_type'    => 'giftcard',
			);
			$parent_post_id   = wp_insert_post( $gifttemplate_new );
			set_post_thumbnail( $parent_post_id, $arr[0] );
		}
	}

	/**
	 * Add Preview button link in giftcard post
	 *
	 * @name wps_wgm_preview_gift_template
	 * @param array  $actions actions.
	 * @param object $post post.
	 * @return $actions.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @since 1.0.0
	 */
	public function wps_wgm_preview_gift_template( $actions, $post ) {
		$secure_nonce      = wp_create_nonce( 'wps-gc-auth-nonce' );

		if ( 'giftcard' == $post->post_type ) {
			$actions['wps_wgm_quick_view'] = '<a href="' . admin_url( 'edit.php?post_type=giftcardpost&post_id=' . $post->ID . '&wps_wgm_template=giftcard&wps_nonce=' . $secure_nonce . '&TB_iframe=true&width=600&height=500' ) . '" rel="permalink" class="thickbox">' . __( 'Preview', 'woo-gift-cards-lite' ) . '</a>';
		}

		$actions = apply_filters( 'wps_wgm_export_pdf_gift_template', $actions, $post );
		return $actions;
	}

	/**
	 * Preview of email template
	 *
	 * @name wps_wgm_preview_email_template
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @since 1.0.0
	 */
	public function wps_wgm_preview_email_template() {

		if ( current_user_can( 'edit_others_posts' ) ) {

			if ( isset( $_GET['wps_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['wps_nonce'] ) ), 'wps-gc-auth-nonce' ) ) {
				if ( isset( $_GET['wps_wgm_template'] ) ) {

					if ( isset( $_GET['wps_wgm_template'] ) == 'giftcard' ) {
						$post_id                  = isset( $_GET['post_id'] ) ? sanitize_text_field( wp_unslash( $_GET['post_id'] ) ) : '';

						$wps_post_type = get_post_type( $post_id );
						if ( 'giftcard' == $wps_post_type ) {
							$todaydate                = date_i18n( 'Y-m-d' );
							$wps_wgm_general_settings = get_option( 'wps_wgm_general_settings', false );
								$local_expiry_day = get_post_meta( $post_id, 'wps_wgm_local_setting_giftcard_expiry', true );
							if ( empty( $local_expiry_day ) || 0 == $local_expiry_day ) {
								$expiry_date = $this->wps_common_fun->wps_wgm_get_template_data( $wps_wgm_general_settings, 'wps_wgm_general_setting_giftcard_expiry' );
							} else {
								$expiry_date = $local_expiry_day;
							}

							$expirydate_format             = $this->wps_common_fun->wps_wgm_check_expiry_date( $expiry_date );
							$wps_wgm_coupon_length_display = $this->wps_common_fun->wps_wgm_get_template_data( $wps_wgm_general_settings, 'wps_wgm_general_setting_giftcard_coupon_length' );

							if ( '' == $wps_wgm_coupon_length_display ) {
								$wps_wgm_coupon_length_display = 5;
							}
							$password = '';
							for ( $i = 0;$i < $wps_wgm_coupon_length_display;$i++ ) {
								$password .= 'x';
							}
							$giftcard_prefix = $this->wps_common_fun->wps_wgm_get_template_data( $wps_wgm_general_settings, 'wps_wgm_general_setting_giftcard_prefix' );
							$coupon          = $giftcard_prefix . $password;
							$templateid      = $post_id;

							$args['from']       = esc_html__( 'from@example.com', 'woo-gift-cards-lite' );
							$args['to']         = esc_html__( 'to@example.com', 'woo-gift-cards-lite' );
							$args['message']    = esc_html__( 'Your gift message will appear here which you send to your receiver. ', 'woo-gift-cards-lite' );
							$args['coupon']     = apply_filters( 'wps_wgm_static_coupon_img', $coupon );
							$args['expirydate'] = $expirydate_format;
							$args['amount']     = wc_price( 100 );
							$args['templateid'] = $templateid;
							$style              = '<style>
					table, th, tr, td {
						border: medium none;
					}
					table, th, tr, td {
						border: 0px !important;
					}
						#wps_gw_email {
					width: 630px !important;
				}
				</style>';
							$message            = $this->wps_common_fun->wps_wgm_create_gift_template( $args );
							$finalhtml          = $style . $message;

							if ( wps_uwgc_pro_active() ) {
								do_action( 'preview_email_template_for_pro', $finalhtml );
							} else {
								$allowed_tags = $this->wps_common_fun->wps_allowed_html_tags();
								// @codingStandardsIgnoreStart.
								echo wp_kses( $finalhtml, $allowed_tags );
								wp_die();
								// @codingStandardsIgnoreEnd.
							}
						}
					}
				}
			}
		}
	}

	/**
	 * This is used to add row meta on plugin activation.
	 *
	 * @since 1.0.0
	 * @name wps_custom_plugin_row_meta
	 * @author WP Swings <webmaster@wpswings.com>
	 * @param mixed $links Contains links.
	 * @param mixed $file Contains main file.
	 * @link https://www.wpswings.com/
	 */
	public function wps_custom_plugin_row_meta( $links, $file ) {
		if ( strpos( $file, 'woo-gift-cards-lite/woocommerce_gift_cards_lite.php' ) !== false ) {
			$new_links = array(
				'demo'    => '<a href="https://demo.wpswings.com/gift-cards-for-woocommerce-pro/?utm_source=wpswings-giftcards-demo&utm_medium=giftcards-org-backend&utm_campaign=demo" target="_blank"><img src="' . esc_html( WPS_WGC_URL ) . 'assets/images/Demo.svg" class="wps-info-img" alt="Demo image" style="margin-right: 5px;vertical-align: middle;max-width: 15px;">' . __( 'Demo', 'woo-gift-cards-lite' ) . '</a>',
				'doc'     => '<a href="https://docs.wpswings.com/gift-cards-for-woocommerce/?utm_source=wpswings-giftcards-doc&utm_medium=giftcards-org-backend&utm_campaign=documentation" target="_blank"><img src="' . esc_html( WPS_WGC_URL ) . 'assets/images/Documentation.svg" class="wps-info-img" alt="documentation image" style="margin-right: 5px;vertical-align: middle;max-width: 15px;">' . __( 'Documentation', 'woo-gift-cards-lite' ) . '</a>',
				'Video'     => '<a href="https://www.youtube.com/watch?v=g6JLA3ewph8" target="_blank"><img src="' . esc_html( WPS_WGC_URL ) . 'assets/images/YouTube32px.svg" class="wps-info-img" alt="Vedio image" style="margin-right: 5px;vertical-align: middle;max-width: 15px;">' . __( 'Video', 'woo-gift-cards-lite' ) . '</a>',
				'support' => '<a href="https://wpswings.com/submit-query/?utm_source=wpswings-giftcards-support&utm_medium=giftcards-org-backend&utm_campaign=support" target="_blank"><img src="' . esc_html( WPS_WGC_URL ) . 'assets/images/Support.svg" class="wps-info-img" alt="support image" style="margin-right: 5px;vertical-align: middle;max-width: 15px;">' . __( 'Support', 'woo-gift-cards-lite' ) . '</a>',
				'services' => '<a href="https://wpswings.com/woocommerce-services/?utm_source=wpswings-giftcards-services&utm_medium=giftcards-org-backend&utm_campaign=woocommerce-services" target="_blank"><img src="' . esc_html( WPS_WGC_URL ) . 'assets/images/Services.svg" class="wps-info-img" alt="services image" style="margin-right: 5px;vertical-align: middle;max-width: 15px;">' . __( 'Services', 'woo-gift-cards-lite' ) . '</a>',
			);

			$links = array_merge( $links, $new_links );
		}
		return $links;
	}

	/**
	 * This function is used to get all the templates in giftcard lite plugin.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_get_all_lite_templates
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_get_all_lite_templates() {
		$wps_lite_templates = array(
			'Love You Mom',
			'Gift for You',
			'Custom Template',
			'Merry Christmas Template',
		);
		return $wps_lite_templates;
	}


	/**
	 * Set Cron for plugin notification.
	 *
	 * @since    2.0.0
	 * @name wps_wgm_set_cron_for_plugin_notification
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_set_cron_for_plugin_notification() {

			$offset = get_option( 'gmt_offset' );
			$time   = time() + $offset * 60 * 60;
		if ( ! wp_next_scheduled( 'wps_wgm_check_for_notification_update' ) ) {

			wp_schedule_event( $time, 'daily', 'wps_wgm_check_for_notification_update' );

		}

	}

	/**
	 * This function is used to save notification message with notification id.
	 *
	 * @since    2.0.0
	 * @name wps_wgm_save_notice_message
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_save_notice_message() {
		$wps_notification_data = $this->wps_get_update_notification_data();
		if ( is_array( $wps_notification_data ) && ! empty( $wps_notification_data ) ) {
			$notification_id      = array_key_exists( 'notification_id', $wps_notification_data[0] ) ? $wps_notification_data[0]['notification_id'] : '';
			$notification_message = array_key_exists( 'notification_message', $wps_notification_data[0] ) ? $wps_notification_data[0]['notification_message'] : '';
			$banner_id      = array_key_exists( 'notification_id', $wps_notification_data[0] ) ? $wps_notification_data[0]['wps_banner_id'] : '';
			$banner_image = array_key_exists( 'notification_message', $wps_notification_data[0] ) ? $wps_notification_data[0]['wps_banner_image'] : '';
			$banner_url = array_key_exists( 'notification_message', $wps_notification_data[0] ) ? $wps_notification_data[0]['wps_banner_url'] : '';
			$banner_type = array_key_exists( 'notification_message', $wps_notification_data[0] ) ? $wps_notification_data[0]['wps_banner_type'] : '';

			update_option( 'wps_wgm_notify_new_msg_id', $notification_id );
			update_option( 'wps_wgm_notify_new_message', $notification_message );
			update_option( 'wps_wgm_notify_new_banner_id', $banner_id );
			update_option( 'wps_wgm_notify_new_banner_image', $banner_image );
			update_option( 'wps_wgm_notify_new_banner_url', $banner_url );
			update_option( 'wps_banner_type', $banner_type );
			if ( 'regular' == $banner_type ) {
				 update_option( 'wps_wgm_notify_hide_baneer_notification', '' );
			}
		}
	}

	/**
	 * This function is used to get notification data from server.
	 *
	 * @since    2.0.0
	 * @name wps_get_update_notification_data
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_get_update_notification_data() {
		$wps_notification_data = array();
		$url                   = 'https://demo.wpswings.com/client-notification/woo-gift-cards-lite/wps-client-notify.php';
		$attr                  = array(
			'action'         => 'wps_notification_fetch',
			'plugin_version' => WPS_WGC_VERSION,
		);
		$query                 = esc_url_raw( add_query_arg( $attr, $url ) );
		$response              = wp_remote_get(
			$query,
			array(
				'timeout'   => 20,
				'sslverify' => false,
			)
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo '<p><strong>Something went wrong: ' . esc_html( stripslashes( $error_message ) ) . '</strong></p>';
		} else {
			$wps_notification_data = json_decode( wp_remote_retrieve_body( $response ), true );
		}
		return $wps_notification_data;
	}

	/**
	 * This function is used to display notoification bar at admin.
	 *
	 * @since    2.0.0
	 * @name wps_wgm_display_notification_bar
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_display_notification_bar() {

		$secure_nonce      = wp_create_nonce( 'wps-gc-auth-nonce' );
		$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-gc-auth-nonce' );
		if ( ! $id_nonce_verified ) {
				wp_die( esc_html__( 'Nonce Not verified', 'woo-gift-cards-lite' ) );
		}
		$screen = get_current_screen();
		if ( isset( $screen->id ) ) {
			$pagescreen = $screen->id;
		}
		if ( ( isset( $_GET['page'] ) && 'wps-wgc-setting-lite' === $_GET['page'] ) || ( isset( $_GET['post_type'] ) && 'product' === $_GET['post_type'] ) || ( isset( $_GET['post_type'] ) && 'giftcard' === $_GET['post_type'] ) || ( isset( $pagescreen ) && 'plugins' === $pagescreen ) ) {
			$notification_id = get_option( 'wps_wgm_notify_new_msg_id', false );
			if ( isset( $notification_id ) && '' !== $notification_id ) {
				$hidden_id            = get_option( 'wps_wgm_notify_hide_notification', false );
				$notification_message = get_option( 'wps_wgm_notify_new_message', '' );
				if ( isset( $hidden_id ) && $hidden_id < $notification_id ) {
					if ( '' !== $notification_message ) {
						?>
						<div class="notice is-dismissible notice-info" id="dismiss_notice">
							<div class="notice-container">
								<div class="notice-image">
									<img src="<?php echo esc_url( WPS_WGC_URL . 'assets/images/wpswings_logo.png' ); ?>" alt="MakeWebBetter">
								</div> 
								<div class="notice-content">
									<?php echo wp_kses_post( $notification_message ); ?>
								</div>				
							</div>
							<button type="button" class="notice-dismiss dismiss_notice"><span class="screen-reader-text">Dismiss this notice.</span></button>
						</div>
						<?php
					}
				}
			}
		}
	}

	/**
	 * This function is used to dismiss admin notices.
	 *
	 * @since    2.0.0
	 * @name wps_wgm_dismiss_notice
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_dismiss_notice() {
		if ( isset( $_REQUEST['wps_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wps_nonce'] ) ), 'wps-wgm-verify-notice-nonce' ) ) {
			$notification_id = get_option( 'wps_wgm_notify_new_msg_id', false );

			if ( isset( $notification_id ) && '' !== $notification_id ) {
				update_option( 'wps_wgm_notify_hide_notification', $notification_id );

			}

			wp_send_json_success();
		}
	}
	/**
	 * This function is used to dismiss admin notices.
	 *
	 * @since    2.0.0
	 * @name wps_wgm_dismiss_notice
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_dismiss_notice_banner() {
		if ( isset( $_REQUEST['wps_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wps_nonce'] ) ), 'wps-wgm-verify-notice-nonce' ) ) {

			$banner_id = get_option( 'wps_wgm_notify_new_banner_id', false );

			if ( isset( $banner_id ) && '' != $banner_id ) {
				update_option( 'wps_wgm_notify_hide_baneer_notification', $banner_id );
			}

			wp_send_json_success();
		}
	}
	/**
	 * The function displays a button to enable plugin after plugin activation.
	 *
	 * @since    2.0.0
	 * @name wps_wgm_setting_notice_on_activation
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_setting_notice_on_activation() {
		/* Check transient, if available display notice */
		if ( get_transient( 'wps-wgm-giftcard-setting-notice' ) ) {
			?>
			<div class="updated notice is-dismissible" class="wps-wgm-is-dismissible">
			<p class="wps_wgm_plugin_active_para"><strong><?php esc_html_e( 'Welcome to Ultimate Gift Cards For WooCommerce', 'woo-gift-cards-lite' ); ?></strong><?php esc_html_e( '– Create and sell multiple gift cards with ease.', 'woo-gift-cards-lite' ); ?>
			</p>
			<?php
			$general_settings = get_option( 'wps_wgm_general_settings', array() );
			require_once WPS_WGC_DIRPATH . 'includes/class-woocommerce-gift-cards-common-function.php';
			$wps_obj                        = new Woocommerce_Gift_Cards_Common_Function();
			$wps_wgm_general_setting_enable = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_enable' );
			if ( 'on' !== $wps_wgm_general_setting_enable ) {
				?>
				<p class="wps_show_setting_on_activation">
					<a class="wps_wgm_plugin_activation_msg" href="<?php echo esc_url( admin_url( 'edit.php?post_type=giftcard&page=wps-wgc-setting-lite&tab=general_setting' ) ); ?>"><?php echo esc_html__( 'Enable Gift Cards', 'woo-gift-cards-lite' ); ?></a>
				</p>
				<?php
			}
			?>

			</div>
			<?php
			/* Delete transient, only display this notice once. */
			delete_transient( 'wps-wgm-giftcard-setting-notice' );
		}
	}

	/**
	 * Get all valid screens to add scripts and templates.
	 *
	 * @param array $valid_screens valid screen.
	 * @since    2.5.0
	 * @name add_wps_frontend_screens
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function add_wps_frontend_screens( $valid_screens = array() ) {

		if ( is_array( $valid_screens ) ) {

			// Push your screen here.
			array_push( $valid_screens, 'giftcard_page_wps-wgc-setting-lite' );
		}
		return $valid_screens;  }

	/**
	 * Get all valid slugs to add deactivate popup.
	 *
	 * @param array $valid_screens valid screen.
	 * @since    2.5.0
	 * @name add_wps_deactivation_screens
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function add_wps_deactivation_screens( $valid_screens = array() ) {

		if ( is_array( $valid_screens ) ) {

			// Push your screen here.
			array_push( $valid_screens, 'woo-gift-cards-lite' );
		}

		return $valid_screens;
	}

	/**
	 * Remove Quick Edit option from giftcard post type.
	 *
	 * @param array  $actions actions.
	 * @param object $post post.
	 * @return array $actions.
	 */
	public function wps_wgm_remove_row_actions( $actions, $post ) {
		global $current_screen;
		if ( 'giftcard' !== $current_screen->post_type ) {
			return $actions;
		}
		unset( $actions['inline hide-if-no-js'] );
		return $actions;
	}

	/**
	 * Function is used to add a sub menu for import template section.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_import_template().
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_import_template_org() {
		if ( ! wps_uwgc_pro_active() ) {
			add_submenu_page( 'edit.php?post_type=giftcard', __( 'Import Templates', 'woo-gift-cards-lite' ), __( 'Import Templates <span style="color:#00FF00;">Pro</span>', 'woo-gift-cards-lite' ), 'manage_options', 'giftcard-import-giftcard-templates', array( $this, 'wps_wgm_import_giftcard_template_org' ) );
		}
	}
	/**
	 * Function is used to import giftcard template.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_import_giftcard_template().
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_import_giftcard_template_org() {
		$url  = WPS_GIFT_TEMPLATE_URL . 'ultimate-woocommerce-gift-card/wps-get-gc-template.php';
		$attr = array(
			'action' => 'wps_fetch_gc_template',
		);
		$query = esc_url_raw( add_query_arg( $attr, $url ) );
		$wps_response = wp_remote_get(
			$query,
			array(
				'timeout' => 20,
				'sslverify' => false,
			)
		);

		if ( is_wp_error( $wps_response ) ) {
			$error_message = $wps_response->get_error_message();
			echo '<p><strong> Something went wrong: ' . esc_html( stripslashes( $error_message ) ) . '</strong></p>';
		} else {
			$wps_response = wp_remote_retrieve_body( $wps_response );
			$template_json_data = json_decode( $wps_response, true );
		}
		?>
		<div id="wps_wgm_setting_wrapper">
			<div class="wps-gc__popup-for-pro-wrap">
					<div class="wps-gc__popup-for-pro-shadow">
						
					</div>
					<div class="wps-gc__popup-for-pro">
						<span class="wps-gc__popup-for-pro-close">+</span>
						<h2 class="wps-gc__popup-for-pro-title">Unlock This Premium Template with a Pro Upgrade!  </h2>
						<p class="wps-gc__popup-for-pro-content">Congratulations on discovering our premium  Templates! This stunning template is reserved for our Pro members, offering you a world of creative possibilities. Upgrade today to unlock it and access a wealth of exclusive features.</p>
							<div class="wps-gc__popup-for-pro-link-wrap">
								<a target="_blank" href="https://wpswings.com/product/gift-cards-for-woocommerce-pro/?utm_source=wpswings-giftcards-pro&utm_medium=giftcards-org-backend&utm_campaign=go-pro" class="wps-gc__popup-for-pro-link">Go pro now</a>
							</div>
					</div>
			</div>
		</div>
		<div id="wps_wgm_loader" style="display: none;">
			<img src="<?php echo esc_url( WPS_GIFT_TEMPLATE_URL ); ?>assets/images/loading.gif">
		</div>
		<div class="wps_notice_temp" style="display:none;"> 
			<span id="wps_import_notice"></span>
			<i class="fas fa-times cancel_notice"></i>
		</div>
		<h1 id="wps-gc-import-template-title"><?php esc_html_e( 'Import Gift Card Templates', 'woo-gift-cards-lite' ); ?></h1>
		<?php
		if ( isset( $template_json_data ) && is_array( $template_json_data ) && ! empty( $template_json_data ) ) {
			?>
		<div class="wps_uwgc_filter_wrap">
			<h2><?php esc_html_e( 'Filter Gift Card Templates', 'woo-gift-cards-lite' ); ?></h2>
			<?php
			$check_if_all_template_imported = get_option( 'wps_uwgc_all_templates_imported', false );
			?>
			<a href="#" name="import_all_gift_card" class="wps_import_all_giftcard_templates button"><?php esc_html_e( 'Import All Gift Card Templates At Once', 'woo-gift-cards-lite' ); ?></a>
				
		</div>
		<div class="wps_uwgc_wrapper">
			<div id="filters" class="button-group wps_template_filter"> 
				 <button class="button wps_gc_events is-checked" data-filter="*">show all</button>
			<?php
			foreach ( $template_json_data as $rs ) {
				?>
			  <button class="button wps_gc_events" data-filter=".<?php echo esc_attr( stripslashes( $rs['occassion_id'] ) ); ?>"><?php echo esc_html( stripslashes( $rs['occassion_name'] ) ); ?></button>
				<?php
			}
		}
		?>
			</div>
			<?php
			if ( isset( $template_json_data ) && is_array( $template_json_data ) && ! empty( $template_json_data ) ) {
				?>
			<div id="filters_on_mobile" class="wps_template_filter"> 
			<select class="select-group wps_select_template_filter">
				<option class="wps_gc_events is-checked" value="*">show all</option>
				<?php
				foreach ( $template_json_data as $rs ) {
					?>
				<option class="wps_gc_events" value =".<?php echo esc_attr( stripslashes( $rs['occassion_id'] ) ); ?>"><?php echo esc_html( stripslashes( $rs['occassion_name'] ) ); ?></option>
					<?php
				}
				?>
			</select>
			</div>
				<?php
			}
			?>
			<div class="grid wps_template_display">
				<?php
				if ( isset( $template_json_data ) && is_array( $template_json_data ) && ! empty( $template_json_data ) ) {
					foreach ( $template_json_data as $rs ) {
						if ( array_key_exists( 'templates', $rs ) ) {
							foreach ( $rs['templates'] as $temp_data ) {
								?>
								<div class="element-item template_block <?php echo esc_attr( stripslashes( $rs['occassion_id'] ) ); ?>" data-category="template">
									<h3 class="name"><?php echo esc_html( stripslashes( $temp_data['template_name'] ) ); ?></h3>
									<div class="event_template">
										<img src="
										<?php
										 echo esc_url( WPS_GIFT_TEMPLATE_URL . 'ultimate-woocommerce-gift-card/giftcard-templates/' . $temp_data['template_image'] );
										?>
										  ">
									</div>
									<div class="wps_event_template_preview">
										<div class="wps_preview_links">
											<a href="">
												<i class="fas fa-eye wps_preview_template"></i>
											</a>
																						<i class="fas fa-download wps_download_template" data-id="<?php echo esc_attr( stripslashes( $temp_data['template_id'] ) ); ?>"></i>
											<div class="wps_template_import_note">
												<p class="wps_note"><?php esc_html_e( 'Import this template.', 'woo-gift-cards-lite' ); ?></p>
											</div>
											
																						
										</div>
									</div>
									<div class="wps-popup-wrapper">
										  <div class="wps-popup">
											  <div class="wps-popup-img">
												<span><i class="far fa-times-circle"></i></span>
												<img src="
												<?php
												echo esc_url( WPS_GIFT_TEMPLATE_URL . 'ultimate-woocommerce-gift-card/giftcard-templates/' . $temp_data['template_image'] );
												?>
												  ">
											  </div>
										  </div>
									</div>
								</div>
								<?php
							}
						}
					}
				}
				?>
			</div>
		 </div>
		<?php
	}

	/**
	 * Hide specific products.
	 * 
	 * @param  array $query query variable.
	 */
	public function wps_wgm_hide_specific_product_from_backend($query) {
		// Check if we are in admin and on the products page.
		if (is_admin() && $query->is_main_query() && $query->get('post_type') === 'product') {
		
			$excluded_product_id  = get_option( 'contributor_product_id' );
			$excluded_product_id1 = get_option( 'wps_gccoupon_rechargeable_product_id' );
			$extension_product_id = get_option( 'gc_expiry_extension_product_id' );
			$product_settings     = get_option( 'wps_wgm_product_settings', true );
			$wps_wgm_product_setting_expiry_extension = $this->wps_common_fun->wps_wgm_get_template_data( $product_settings, 'wps_wgm_product_setting_expiry_extension' );
			
			$excluded_products = array( $excluded_product_id, $excluded_product_id1 );

			if ( 'on' != $wps_wgm_product_setting_expiry_extension || ! wps_uwgc_pro_active() ) {
				array_push( $excluded_products, $extension_product_id );
			}
			// Exclude the specific product from the query.
			$query->set('post__not_in', $excluded_products );
		}
	}

	// PAR compatibility.

	/**
	 * This function is used create setting for coupon redeem in PAR.
	 *
	 * @param  array $other_settings other_settings.
	 * @return array
	 */
	public function wps_wgm_par_compatibility_settings( $other_settings ) {

		$add_settings   = array(
			array(
				'title'    => __( 'Convert Coupon into Points', 'woo-gift-cards-lite' ),
				'id'       => 'wps_wgm_enable_coupon_conversion_settings',
				'type'     => 'checkbox',
				'class'    => 'input-text',
				'desc_tip' => __( 'By enabling this setting, users have the ability to redeem their coupons and convert them into points.', 'woo-gift-cards-lite' ),
				'desc'     => __( 'Enable this to convert coupons amount into points.', 'woo-gift-cards-lite' ),
			),
			array(
				'title'       => __( 'Enter Coupon redeem conversion rate', 'woo-gift-cards-lite' ),
				'type'        => 'number_text',
				'number_text' => array(
					array(
						'id'    => 'wps_wgm_enter_price_rate',
						'type'  => 'number',
						'custom_attribute' => array(
							'min' => '"0"',
						),
						'class' => 'input-text',
						'curr'  => get_woocommerce_currency_symbol(),
					),
					array(
						'id'    => 'wps_wgm_enter_points_rate',
						'type'  => 'number',
						'custom_attribute' => array(
							'min' => '"0"',
						),
						'class' => 'input-text',
						'desc'  => __( ' Points ', 'woo-gift-cards-lite' ),
					),
				),
			),
		);
		$other_settings = array_merge( $other_settings, $add_settings );
		return $other_settings;
	}

	/**
	 * This function is used to create coupon redeem log on admin end.
	 *
	 * @param  array $point_log point_log.
	 * @return void
	 */
	public function wps_wgm_admin_end_points_log( $point_log ) {

		if ( array_key_exists( 'gift_coupon_redeem_details', $point_log ) ) {
			?>
			<div class="wps_wpr_slide_toggle">
				<p class="wps_wpr_view_log_notice wps_wpr_common_slider" ><?php esc_html_e( 'Gift Coupon Redeem Points', 'woo-gift-cards-lite' ); ?>
					<a class ="wps_wpr_open_toggle"  href="javascript:;"></a>
				</p>
				<table class = "form-table mwp_wpr_settings wps_wpr_points_view wps_wpr_common_table">
						<thead>
							<tr valign="top">
								<th scope="row" class="wps_wpr_head_titledesc">
									<span class="wps_wpr_nobr"><?php echo esc_html__( 'Date & Time', 'woo-gift-cards-lite' ); ?></span>
								</th>
								<th scope="row" class="wps_wpr_head_titledesc">
									<span class="wps_wpr_nobr"><?php echo esc_html__( 'Point Status', 'woo-gift-cards-lite' ); ?></span>
								</th>
								<th scope="row" class="wps_wpr_head_titledesc">
									<span class="wps_wpr_nobr"><?php echo esc_html__( 'Coupon Name.', 'woo-gift-cards-lite' ); ?></span>
								</th>
							</tr>
						</thead>
						<?php
						foreach ( $point_log['gift_coupon_redeem_details'] as $key => $value ) {
							?>
							<tr valign="top">
								<td class="forminp forminp-text"><?php echo esc_html( $value['date'] ); ?></td>
								<td class="forminp forminp-text"><?php echo '+' . esc_html( $value['gift_coupon_redeem_details'] ); ?> </td>
								<td class="forminp forminp-text"><?php echo esc_html( $value['coupon_name'] ); ?> </td>
							</tr>
							<?php
						}
						?>
				</table>
			</div>
			<?php
		}
	}

	/**
	 * Function is used to add admin toolbar for giftcard reporting.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_admin_toolbar()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_admin_toolbar() {
		global $wp_admin_bar;
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$args = array(
			'id'     => 'gift-cards',
			'title'  => __( 'GC Reports', 'woo-gift-cards-lite' ),
			'href'   => admin_url( 'admin.php?page=wc-reports&tab=giftcard_report'),
		);

		$wp_admin_bar->add_menu( $args );
	}

	/**
	 * Function is used to add Gift Card Report Section.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_report().
	 * @param array $reports Array of Reports.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_report( $reports ) {
		$reports['giftcard_report'] = array(
			'title'   => __( 'Gift Cards', 'woo-gift-cards-lite' ),
			'reports' => array(
				'giftcard_report' => array(
					'title'       => __( 'Gift Cards', 'woo-gift-cards-lite' ),
					'description' => '',
					'hide_title'  => true,
					'callback'    => array( __CLASS__, 'wps_wgm_giftcard_report' ),
				),
			),
		);
		return $reports;
	}

	/**
	 * Function is used to include report template.
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_giftcard_report()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public static function wps_wgm_giftcard_report() {	
		include_once WPS_WGC_DIRPATH . '/admin/partials/class-wps-wgm-giftcard-report-list.php';
	}

	/**
	 * Function to show giftcard details on ajax call.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_gift_card_details()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_gift_card_details() {
		check_ajax_referer( 'wps-uwgc-giftcard-report-nonce', 'wps_uwgc_nonce' );
		$_POST['wps_uwgc_report_details'] = 'wps_uwgc_report_details';
		$_POST['width'] = '650';
		$_POST['height'] = '480';
		$_POST['TB_iframe'] = true;
		$query = http_build_query( $_POST );
		$ajax_url = home_url( "?$query" );
		echo wp_kses_post( $ajax_url );
		wp_die();
	}

	/**
	 * Function is used to preview report deatils.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_preview_report_details()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_preview_report_details() {
		$secure_nonce      = wp_create_nonce( 'wps-gc-report-nonce' );
		$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-gc-report-nonce' );
		if ( ! $id_nonce_verified ) {
			wp_die( esc_html__( 'Nonce Not verified', 'woo-gift-cards-lite' ) );
		}
		if ( isset( $_GET['wps_uwgc_report_details'] ) && 'wps_uwgc_report_details' == $_GET['wps_uwgc_report_details'] ) {
			$order_id = isset( $_GET['order_id'] ) ? sanitize_text_field( wp_unslash( $_GET['order_id'] ) ) : '';
			$coupon_id = isset( $_GET['coupon_id'] ) ? sanitize_text_field( wp_unslash( $_GET['coupon_id'] ) ) : '';

			if ( '' !== $order_id && '' !== $coupon_id ) {
				$order_date = '';
				$remaining_amt = '';
				$to = '';
				$from = '';
				$msg = '';
				$gift_date = '';
				$productname = '';
				$pro_permalink = '';
				$giftcard_amount = get_post_meta( $coupon_id, 'wps_wgm_coupon_amount', true );
				$remaining_amt = get_post_meta( $coupon_id, 'coupon_amount', true );
				$order = wc_get_order( $order_id );

				global $wpdb;

				$offline_giftcard = get_option( 'wps_wgm_offline_giftcard', false );

				if ( isset( $offline_giftcard ) && ! empty( $offline_giftcard ) ) {
					$cache_key = 'wps_wgm_offline_giftcard_' . intval( $order_id );
					$giftresults = wp_cache_get( $cache_key, 'wps_wgm' );

					if ( false === $giftresults ) {
						$giftresults = $wpdb->get_results(
							$wpdb->prepare(
								"SELECT * FROM {$wpdb->prefix}offline_giftcard WHERE `id` = %d",
								intval( $order_id )
							),
							ARRAY_A
						);

						wp_cache_set( $cache_key, $giftresults, 'wps_wgm', HOUR_IN_SECONDS );
					}
				}
				
				if ( isset( $giftresults[0] ) ) {
					$giftresult = $giftresults[0];
					$order_date = $giftresult['date'];
					$product_id = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_product_id', true );
					if ( '' !== $product_id ) {
						$product = wc_get_product( $product_id );
						if ( isset( $product ) && ! empty( $product ) ) {
							$pro_permalink = $product->get_permalink();
							$productname = get_the_title( $product_id );
						}
					}
					$to = $giftresult['to'];
					$from = $giftresult['from'];
					$msg = $giftresult['message'];
					$gift_date = $giftresult['schedule'];
				} else {
					$order_date = $order->get_date_created()->date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
					$product_id = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_product_id', true );
					if ( '' !== $product_id ) {
						$product = wc_get_product( $product_id );
						if ( isset( $product ) && ! empty( $product ) ) {
							$pro_permalink = $product->get_permalink();
							$productname = get_the_title( $product_id );
						}
					}
					foreach ( $order->get_items() as $item ) {
						$item_meta_data = $item->get_meta_data();
						foreach ( $item_meta_data as $key => $value ) {
							if ( isset( $value->key ) && 'To' == $value->key && ! empty( $value->value ) ) {
								$to = $value->value;
							}
							if ( isset( $value->key ) && 'From' == $value->key && ! empty( $value->value ) ) {
								$from = $value->value;
							}
							if ( isset( $value->key ) && 'Message' == $value->key && ! empty( $value->value ) ) {
								$msg = $value->value;
							}
							if ( isset( $value->key ) && 'Send Date' == $value->key && ! empty( $value->value ) ) {
								$gift_date = $value->value;
							}
							if ( isset( $value->key ) && 'Variable Price Description' == $value->key && ! empty( $value->value ) ) {
								$variable_price_desc = $value->value;
							}
						}
					}

					$to = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_mail_to', true );
					///////////////////////////////////////////////////////////////////////////////////////////////////
						
					$meta_key = 'suborder#'.$order_id;
					
					$args = array(
						'post_type'      => 'shop_order',
						'post_status'    => 'any',
						'posts_per_page' => -1,
						'meta_key'       => $meta_key, // Replace with your custom field key.
					);
					$suborders = wc_get_orders( $args );
	
					/////////////////////////////////////////////////////////////////////////////////////////////////////
				}
				?>
				
					<div class="wps_uwgc_report_preview">
						<h3 style="text-align:;"><?php esc_html_e( 'Gift Card Details', 'woo-gift-cards-lite' ); ?></h3>

						<table>
							<tr>
								<td><b><?php esc_html_e( 'Purchased Date :', 'woo-gift-cards-lite' ); ?></b></td>
								<td><?php echo esc_html( $order_date ); ?></td>
							</tr>
							<tr>
								<?php
								if ( isset( $giftcard_amount ) && ! empty( $giftcard_amount ) ) {
									?>
								<td><b><?php esc_html_e( 'Gift Card Amount :', 'woo-gift-cards-lite' ); ?></b></td>
								<td><?php echo wp_kses_post( wc_price( $giftcard_amount ) ); ?></td>
								<?php } ?>
							</tr>
							
							<tr>
								<td><b><?php esc_html_e( 'Remaining Amount :', 'woo-gift-cards-lite' ); ?></b></td>
								<td><?php echo wp_kses_post( wc_price( $remaining_amt ) ); ?></td>
							</tr>
							<tr>
								<td><b><?php esc_html_e( ' To :', 'woo-gift-cards-lite' ); ?></b></td>
								<td><?php echo esc_html( $to ); ?></td>
							</tr>
							<tr>
								<td><b><?php esc_html_e( 'From :', 'woo-gift-cards-lite' ); ?></b></td>
								<td><?php echo esc_html( $from ); ?></td>
							</tr>
							<tr>
								<td><b><?php esc_html_e( 'Message :', 'woo-gift-cards-lite' ); ?></b></td>
								<td><?php echo esc_html( $msg ); ?></td>
							</tr>
							<tr>
								<td><b><?php esc_html_e( 'Scheduled Date :', 'woo-gift-cards-lite' ); ?></b></td>
								<td><?php echo esc_html( ( '' !== $gift_date ) ? $gift_date : $order_date ); ?></td>
							</tr>
							<?php if ( isset( $variable_price_desc ) ) {
								?>
								<tr>
									<td><b><?php esc_html_e( 'Variable Price Description :', 'woo-gift-cards-lite' ); ?></b></td>
									<td><?php echo esc_html( $variable_price_desc ); ?></td>
								</tr>
								<?php
							}
							?>
							<tr>
								<td><b><?php esc_html_e( 'Product :', 'woo-gift-cards-lite' ); ?></b></td>
								<td><a target="_blank" href="<?php echo esc_attr( $pro_permalink ); ?>"><?php echo esc_html( $productname ); ?></a></td>
							</tr>
						</table>
						<?php
						if (isset( $suborders ) && is_array($suborders) && !empty($suborders)){
							?>
								<table  class="wps_uwgc_suborer">
								<h3 style="text-align:center;"><?php esc_html_e( 'Suborder Transactions', 'woo-gift-cards-lite' ); ?></h3>
									<tr style="text-align:center;">
										<th><?php esc_html_e( 'Contributor Email :', 'woo-gift-cards-lite' ); ?></th>
										<th><?php esc_html_e( 'Contribution Amount :', 'woo-gift-cards-lite' ); ?></th>
									</tr>
								<?php
									foreach ($suborders as $orde) {
										$order_sub_id = $orde->get_id();
										$sub_order = wc_get_order( $order_sub_id);
									
										$billing_email  = $sub_order->get_billing_email();
										$product_amount = $sub_order->get_subtotal();
										?>
										<tr style="text-align:center;">
											<td><?php echo esc_attr( $billing_email ); ?></td>
											<td><?php echo wp_kses_post( wc_price( $product_amount) ); ?></td>
										</tr>
										<?php		
									}
								}
							?>
						<table class="wps_uwgc_transaction">
							<h3 style="text-align:;"><?php esc_html_e( 'Gift Card Transactions', 'woo-gift-cards-lite' ); ?></h3>
							<?php
							$wps_gw_used_coupon_details = get_post_meta( $coupon_id, 'wps_uwgc_used_order_id', true );
							if ( isset( $wps_gw_used_coupon_details ) && is_array( $wps_gw_used_coupon_details ) && ! empty( $wps_gw_used_coupon_details ) ) {
								?>
							<tr>
								<th><?php esc_html_e( 'Order Id', 'woo-gift-cards-lite' ); ?></th>
								<th><?php esc_html_e( 'Used Amount', 'woo-gift-cards-lite' ); ?></th>
							</tr>
								<?php

								foreach ( $wps_gw_used_coupon_details as $key => $value ) {
									?>
								<tr>
									<td>
										<a target ="_blank" href="
										<?php
											echo esc_url( admin_url( 'post.php?post=' . absint( $value['order_id'] ) . '&action=edit' ) );
										?>
										">#<?php echo esc_html( $value['order_id'] ); ?></a>
									</td>
									<td> <?php echo wp_kses_post( wc_price( $value['used_amount'] ) ); ?></td>
								</tr>
									<?php
								}
							} else {
								?>
								<tr>
									<td><?php esc_html_e( 'Gift Cards Not Used ', 'woo-gift-cards-lite' ); ?></td>
								</tr>
								<?php
							}
							?>
						</table>
					</div>

					<style type="text/css">
						.wps_uwgc_report_preview {
						font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
						}
						.wps_uwgc_report_preview table {
						width: 100%;
						border-collapse: collapse;
						border: 1px solid #efefef;
						}
						.wps_uwgc_report_preview h3 {
						text-align: center;
						font-size: 24px;
						margin: 0 0 20px;
						}
						.wps_uwgc_report_preview .wps_uwgc_transaction {
						text-align: center;
						border: 1px solid #efefef;
						}
						.wps_uwgc_report_preview .wps_uwgc_transaction td, .wps_uwgc_report_preview .wps_uwgc_transaction th {
						border-bottom: 1px solid #efefef;
						padding: 10px;
						}
						.wps_uwgc_report_preview table ~ h3 {
						margin-top: 25px;
						}
						.wps_uwgc_report_preview .wps_uwgc_transaction {
						text-align: center;
						}
						.wps_uwgc_report_preview .wps_uwgc_transaction th {
						background-color: #efefef;
						}
						.wps_uwgc_report_preview table td {
							padding: 10px;
							border-bottom: 1px solid #efefef;
						}
					</style>
				<?php
				$message = ob_get_clean();
				$wps_admin_obj = new Woocommerce_Gift_Cards_Common_Function();
				$allowed_tags = $wps_admin_obj->wps_allowed_html_tags();
				echo wp_kses( $message, $allowed_tags );
				wp_die();
			}
		}
	}

	/**
	 * Function is used to display show coupons datils with order id.
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_coupon_reporting_with_order_id().
	 * @param int   $coupon_id Coupon id.
	 * @param array $item Item array.
	 * @param mixed $total_discount Total Discount.
	 * @param mixed $remaining_amount Remaining Coupon Amount.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_coupon_reporting_with_order_id( $coupon_id, $item, $total_discount, $remaining_amount ) {
		$if_gc_coupon = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_unique', true );
		if ( 'online' === $if_gc_coupon ) {
			$wps_uwgc_order = get_post_meta( $coupon_id, 'wps_uwgc_used_order_id', true );
			if ( is_array( $wps_uwgc_order ) && ! empty( $wps_uwgc_order ) ) {
				$wps_uwgc_used_order_id = $item->get_order_id();
				$wps_uwgc_order[] = array(
					'order_id' => $wps_uwgc_used_order_id,
					'used_amount' => $total_discount,
				);
			} else {
				$wps_uwgc_order = array();
				$wps_uwgc_used_order_id = $item->get_order_id();
				$wps_uwgc_order[] = array(
					'order_id' => $wps_uwgc_used_order_id,
					'used_amount' => $total_discount,
				);
			}
			update_post_meta( $coupon_id, 'wps_uwgc_used_order_id', $wps_uwgc_order );
			update_post_meta( $coupon_id, 'coupon_amount', $remaining_amount );
		}
	}

	/**
	 * Function is used to generate consumer key and secret.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_generate_gifting_api_key_and_secret()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_generate_gifting_api_key_and_secret() {
		$api_keys = array();
		for ( $i = 0; $i < 2; $i++ ) {
			$random     = wp_rand();
			$api_keys[] = md5( $random );
		}
		$wps_wgm_gifting_api_keys['consumer_key']    = $api_keys[0];
		$wps_wgm_gifting_api_keys['consumer_secret'] = $api_keys[1];

		$url = 'https://gifting.wpswings.com/api/generate/update';

		$offline_giftcard_redeem_details  = get_option( 'giftcard_offline_redeem_link' );
		$client_license_code             = get_option( 'wps_gw_lcns_key', '' );
		$userid                          = isset( $offline_giftcard_redeem_details['user_id'] ) ? $offline_giftcard_redeem_details['user_id'] : '';
		$client_domain                   = home_url();
		$request_type                    = 'generate_token';

		$curl_data = array(
			'user_id'         => $userid,
			'domain'          => $client_domain,
			'license'         => $client_license_code,
			'consumer_key'    => $wps_wgm_gifting_api_keys['consumer_key'],
			'consumer_secret' => $wps_wgm_gifting_api_keys['consumer_secret'],
			'request_type'    => $request_type,
		);

		$response = wp_remote_post(
			$url,
			array(
				'timeout' => 50,
				'user-agent' => '',
				'sslverify' => false,
				'body' => $curl_data,
			)
		);

		if ( is_wp_error( $response ) ) {
			$logger = wc_get_logger();
			$logger->error( 'Request failed: ' . $response->get_error_message(), array( 'source' => 'Ultimate Gift Cards For WooCommerce' ) );
			return;
		}

		$response_body = wp_remote_retrieve_body( $response );

		if ( ! empty( $response_body ) ) {
			$response_data = json_decode( $response_body );
			if ( isset( $response_data->status ) ) {
				if ( 'success' === $response_data->status ) {
					update_option( 'wps_wgm_gifting_api_keys', $wps_wgm_gifting_api_keys );
				} else {
					$logger = wc_get_logger();
					$logger->error( 'API error: : Unknown error' , array( 'source' => 'Ultimate Gift Cards For WooCommerce' ) );
				}
			} else {
				$logger = wc_get_logger();
				$logger->error( 'API error: :Invalid response: Missing status field' , array( 'source' => 'Ultimate Gift Cards For WooCommerce' ) );
			}
		} else {
			$logger = wc_get_logger();
			$logger->error( 'API error: : Empty response from the API' , array( 'source' => 'Ultimate Gift Cards For WooCommerce' ) );
		}
	}

	/**
	 * Callback function to reset the gifting request count
	 */
	public function wps_reset_gifting_request() {
		update_option( 'wps_wgm_gifting_request', 0 );
	}

	/**
	 * This function is used to list shortcodes in Gutenburg.
	 *
	 * @return void
	 */
	public function wps_wgm_list_shortcode_in_gutenburg_block() {
		wp_register_script( 'google-embeds-org-block-giftcard', plugins_url( 'js/wps_wgm_gift_card_pro_admin.js', __FILE__ ), array( 'wp-blocks', 'wp-editor', 'wp-element', 'wp-components' ), $this->version, false );
		register_block_type( 'wpswings/googles-embed-org-giftcard', array( 'editor_script' => 'google-embeds-org-block-giftcard') );
	}

}
?>
