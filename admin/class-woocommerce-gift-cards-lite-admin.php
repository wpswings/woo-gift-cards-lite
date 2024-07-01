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
				wp_enqueue_script( 'pro_tag_remove_class', plugin_dir_url( __FILE__ ) . '/js/wps_wgm_gift_card_pro_admin.js', array( 'jquery' ), time(), true );
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
								<td>[PURCHASEDATE]</td>
								<td><?php esc_html_e( 'Replace with Gift Card Purchase Date.', 'woo-gift-cards-lite' ); ?></td>
							</tr>
							<tr>
								<td>[VARIABLEDESCRIPTION]</td>
								<td><?php esc_html_e( 'Replaced with Variable Product Description.', 'woo-gift-cards-lite' ); ?></td>
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
					$upload_file = wp_upload_bits( basename( $value ), null, $this->wps_wgm_get_file_content( $value ) );
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
			$wps_wgm_new_mom_template = '<div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">(Optional) This text will appear in the inbox preview, but not the email body.</div><table class="email-container table-wrap" style="margin: auto;" role="presentation" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#efefef;"><tbody><tr><td dir="ltr" style="padding: 10px;" align="center" bgcolor="#efefef" width="100%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="logo-content-wrap"><tbody><tr><td class="stack-column-center logo-wrap" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td dir="ltr" style="padding: 0px 25px; padding-left: 0;" valign="top"></td></tr></tbody></table></td><td class="stack-column-center content-wrap" style="" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; line-height: 20px; color: #ffffff; text-align: right !important; padding: 0px 10px;" valign="top"><span class="wps_receiver" style="color: #535151; font-size: 14px; line-height: 18px; display:block;">From- [FROM]</span><span style="color: #535151; font-size: 14px; line-height: 18px; display:block;">TO- [TO]</span></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><table class="email-container table-wrap" style="margin: auto;" role="presentation" border="0" width="600" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td dir="ltr" style="padding-top: 15px;" align="center" valign="top" bgcolor="#00897B" width="100%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="img-content-wrap"><tbody><tr><td class="stack-column-center" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td dir="ltr" style="padding: 0px 25px; padding-left: 0;" valign="top"><span class="img-wrap">[FEATUREDIMAGE]</span></td></tr></tbody></table></td><td class="stack-column-center" style="vertical-align: top;" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #ffffff; padding: 0px 30px; text-align: left; " valign="top"><p style="color: rgb(255, 255, 255); font-size: 46px; line-height: 60px; margin-top: 15px; margin-bottom: 15px;">I LOVE YOU MOM</p></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td class="wps_coupon_div" dir="ltr" align="center" valign="top" bgcolor="#fff" width="100%" style="position: relative;"><span class="back_bubble_img">[BACK]</span><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="stack-column-center" style="vertical-align: top;" width="50%"><table class="wps_mid_table" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center" style="position:relative; z-index:999;"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; line-height: 20px; color: #ffffff; padding: 0px 30px; text-align: left; background-color: #efefef;" valign="top"><p class="wps_message" style="text-align: center; line-height: 25px;white-space: pre-line; font-size: 16px; padding: 20px;">[MESSAGE]</p></td></tr></tbody></table></td></tr><tr><td class="wps_coupon_code" style="padding: 15px 10px; font-size: 26px; text-transform: uppercase; text-align: center; font-weight: bold; color: rgb(39, 39, 39); font-family: sans-serif;"><p style="letter-spacing: 1px; padding: 10px 10px; margin: 0px; text-transform: uppercase; text-align: center; color: #00897b; font-weight: bold; font-size: 13px;">coupon code</p>[COUPON]<p style="letter-spacing: 1px; padding: 15px 10px; margin: 0px; text-transform: uppercase; text-align: center; color: #00897b; font-weight: bold; font-size: 13px;">ED:[EXPIRYDATE]</p></td></tr></tbody></table></td></tr><tr><td dir="ltr" style="padding-top: 12px; padding-bottom: 12px; background-color: #efefef;" align="center" valign="top" bgcolor="#fff" width="100%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="stack-column-center" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td dir="ltr" style="padding: 0px 25px; padding-right: 0;" valign="top"><p style="font-family: sans-serif; font-size: 25px; font-weight: bold; margin: 0px; padding: 5px; color: #272727; text-align: right;">[AMOUNT]</p></td></tr></tbody></table></td><td class="stack-column-center" style="vertical-align: top;" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #ffffff; padding: 0px 30px; text-align: left; margin-top: 15px;" class="center-on-narrow arrow-img" valign="top">[ARROWIMAGE]</td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><table role="presentation" border="0" cellspacing="0" cellpadding="0" style="position:relative; z-index:999; background: rgb(0, 137, 123) none repeat scroll 0% 0%; color: rgb(255, 255, 255);" width="600" class="table-wrap footer-wrap"><tbody><tr><td class="wps_disclaimer" style="padding: 10px; text-align: center; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly;"><p style="font-weight: bold; padding-top: 15px; padding-bottom: 15px; font-size: 16px;">[DISCLAIMER]</p></td></tr></tbody></table><style>.wps_mid_table {position: relative;z-index: 999;}.back_bubble_img img {width: 100%;}.img-wrap img {width: 100%;}.wps_coupon_div {position: relative;}.wps_coupon_code {position: relative; z-index: 99;}.wps_message {color: rgb(21, 21, 21);}.wps_disclaimer {background: rgb(0, 137, 123) none repeat scroll 0% 0%;color: rgb(255, 255, 255);}.wps_receiver { display: block;}.img-wrap > img{width:100%;}.back_bubble_img{bottom: 0;content: "";left: 0;margin: 0 auto;position: absolute;right: 0;}.back_bubble_img >img{width:100%;}@media screen and (max-width: 600px){.email-container{width: 100% !important;margin: auto !important;}/* What it does: Forces elements to resize to the full width of their container. Useful for resizing images beyond their max-width. */.fluid{max-width: 90% !important;height: auto !important;margin-left: auto !important;margin-right: auto !important;}/* What it does: Forces table cells into full-width rows. */<br/>.stack-column,.stack-column-center{display: block !important;width: 100% !important;max-width: 100% !important;direction: ltr !important;}/* And center justify these ones. */.stack-column-center{text-align: center !important;}/* What it does: Generic utility class for centering. Useful for images, buttons, and nested tables. */.center-on-narrow{text-align: center !important;display: block !important;margin-left: auto !important;margin-right: auto !important;float: none !important;}table.center-on-narrow{display: inline-block !important;}.footer-wrap{width:100%;}}@media screen and (max-width: 500px){.img-content-wrap .stack-column-center{display: block;width: 100%;}.table-wrap{width:100%;}.logo-content-wrap .content-wrap{width:70%;}.logo-content-wrap .logo-wrap{width:30%;}.center-on-narrow.arrow-img{padding: 0 !important;}}html,body{margin: 0 auto !important;padding: 0 !important;height: 100% !important;width: 100% !important;}/* What it does: Stops email clients resizing small text. */*{-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;}/* What is does: Centers email on Android 4.4 */div[style*="margin: 16px 0"]{margin:0 !important;}/* What it does: Stops Outlook from adding extra spacing to tables. */table,td{mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;}/* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */table{border-spacing: 0 !important;border-collapse: collapse !important;table-layout: fixed !important;margin: 0 auto !important;}table table table{table-layout: auto;}/* What it does: Uses a better rendering method when resizing images in IE. */img{-ms-interpolation-mode:bicubic;}/* What it does: A work-around for iOS meddling in triggered links. */.mobile-link--footer a,a[x-apple-data-detectors]{color:inherit !important;text-decoration: underline !important;}/* What it does: Prevents underlining the button text in Windows 10 */.button-link{text-decoration: none !important;}.button-td,.button-a{transition: all 100ms ease-in;}.button-td:hover,.button-a:hover{background: #555555 !important;border-color: #555555 !important;}</style>';

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
					$upload_file = wp_upload_bits( basename( $value ), null, $this->wps_wgm_get_file_content( $value ) );
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

			$wps_wgm_gift_temp_for_you = '<style>/* What it does: Remove spaces around the email design added by some email clients. */ /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */ html, body{margin: 0 auto !important; padding: 0 !important; height: 100% !important; width: 100% !important;}body *{box-sizing: border-box;}/* What it does: Stops email clients resizing small text. */ *{-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;}/* What is does: Centers email on Android 4.4 */ div[style*="margin: 16px 0"]{margin:0 !important;}/* What it does: Stops Outlook from adding extra spacing to tables. */ table, td{mso-table-lspace: 0pt !important; mso-table-rspace: 0pt !important;}/* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */ table{border-spacing: 0 !important; border-collapse: collapse !important; table-layout: fixed !important; margin: 0 auto !important;}table table table{table-layout: auto;}/* What it does: Uses a better rendering method when resizing images in IE. */ img{-ms-interpolation-mode:bicubic; width: 100%;}/* What it does: A work-around for iOS meddling in triggered links. */ .mobile-link--footer a, a[x-apple-data-detectors]{color:inherit !important; text-decoration: underline !important;}/* What it does: Prevents underlining the button text in Windows 10 */ .button-link{text-decoration: none !important;}</style><style>/* What it does: Hover styles for buttons */ .button-td, .button-a{transition: all 100ms ease-in;}.button-td:hover, .button-a:hover{background: #555555 !important; border-color: #555555 !important;}/* Media Queries */ @media screen and (max-width: 599px){.email-container{width: 100% !important; margin: auto !important;}/* What it does: Forces elements to resize to the full width of their container. Useful for resizing images beyond their max-width. */ .fluid{max-width: 100% !important; height: auto !important; margin-left: auto !important; margin-right: auto !important;}/* What it does: Forces table cells into full-width rows. */ .stack-column, .stack-column-center{display: block !important; width: 100% !important; max-width: 100% !important; direction: ltr !important;}/* And center justify these ones. */ .stack-column-center{text-align: center !important;}/* What it does: Generic utility class for centering. Useful for images, buttons, and nested tables. */ .center-on-narrow{text-align: center !important; display: block !important; margin-left: auto !important; margin-right: auto !important; float: none !important;}table.center-on-narrow{display: inline-block !important;}}</style><center style="width: 100%; background: #222222;"></center><div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">(Optional) This text will appear in the inbox preview, but not the email body.</div><table class="email-container" style="margin: auto;" role="presentation" border="0" width="585" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td align="center" bgcolor="#ffffff">[FEATUREDIMAGE]</td></tr><tr><td dir="ltr" align="center" valign="top" bgcolor="#ffffff" width="100%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td style="line-height: 0; overflow: hidden; height: 30px;"></td></tr><tr><td class="stack-column-center" style="padding: 20px 0px; vertical-align: top; border-right: 1px solid #dddddd !important;" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #fff; padding: 0 20px 20px;" valign="top"><p style="margin: 10px 0 30px 0; text-align: left; font-weight: bold; font-size: 28px;"><span style="color: #333333; margin: 20px 0;">[AMOUNT]</span></p></td></tr><tr><td dir="ltr" style="padding: 30px 20px 0 20px;" valign="top"><p style="color: #333333; font-family: sans-serif; margin: 0px; font-size: 16px;"><span style="font-weight: bold; display: inline-block; text-align: left; font-size: 14px; width: 130px;">COUPON CODE:</span><span style="font-weight: bold; text-transform: uppercase; display: inline-block; text-align: left; font-size: 14px;">[COUPON]</span></p><p style="color: #333333; font-family: sans-serif; margin-bottom: 30px; font-size: 16px;"><span style="font-weight: bold; display: inline-block; text-align: left; font-size: 14px; width: 130px;">EXPIRY DATE:</span><span style="font-weight: bold; text-transform: uppercase; display: inline-block; text-align: left; font-size: 14px;">[EXPIRYDATE]</span></p></td></tr></tbody></table></td><td class="stack-column-center" style="padding: 20px 0px;" valign="top" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #fff; padding: 0px 30px 0 20px; min-height: 170px; height: auto;" valign="top"><p style="color: #333333; font-size: 15px;margin-bottom: 30px">[MESSAGE]</p></td></tr><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; padding: 0 0 0 20px; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #333333;" valign="top"><p style="margin-bottom: 0px; font-size: 16px; margin-top: 20px"><span style="font-weight: bold; display: inline-block; width: 20%; font-size: 15px;">From-</span><span style="display: inline-block; width: 75%; text-align: left; font-size: 14px;">[FROM]</span></p></td></tr><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; padding: 0 0 0 20px; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #333333;" valign="top"><p style="margin-top: 0px; font-size: 16px; line-height: 25px;"><span style="font-weight: bold; display: inline-block; width: 20%; font-size: 15px;">To-</span><span style="display: inline-block; width: 75%; text-align: left; font-size: 14px;">[TO]</span></p></td></tr></tbody></table></td></tr><tr><td style="line-height: 0; overflow: hidden; height: 30px;"></td></tr></tbody></table></td></tr><tr><td bgcolor="#ffffff"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="text-align: center; padding: 10px; border-top: 1px solid #dddddd !important; font-family: sans-serif; font-size: 16px; mso-height-rule: exactly; line-height: 20px; color: #333333;">[DISCLAIMER]</td></tr></tbody></table></td></tr></tbody></table>';

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
					$upload_file = wp_upload_bits( basename( $value ), null, $this->wps_wgm_get_file_content( $value ) );
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

			$wps_wgm_custom_template_html = '<table class="email-container" style="margin: auto;" border="0" width="600" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td style="text-align: center; background: #0e0149;"><p style="color: #0e0149; font-size: 25px; font-family: sans-serif; margin: 20px; text-align: left;"><strong>[LOGO]</strong></p></td></tr></tbody></table><table class="email-container" style="margin: auto;" border="0" width="600" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td style="padding-bottom: 0px;" bgcolor="#f6f6f6"></td></tr><tr><td style="padding: 19px 30px; text-align: center; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; color: #555555;" bgcolor="#d6ccfd"></td></tr><tr style="background-color: #0e0149;"><td style="color: #fff; font-size: 20px; letter-spacing: 0px; margin: 0; text-transform: uppercase; background-color: #0e0149; padding: 20px 10px; line-height: 0;"><p style="border: 2px dashed #ffffff; color: #fff; font-size: 20px; letter-spacing: 0px; padding: 30px 10px; line-height: 30px; margin: 0; text-transform: uppercase; background-color: #0e0149; text-align: center;">Coupon Code<span style="display: block; font-size: 25px;">[COUPON]</span><span style="display: block;">Ed:[EXPIRYDATE]</span></p></td></tr><tr><td dir="ltr" style="padding-bottom: 34px;" align="center" valign="top" bgcolor="#d7ceff" width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="stack-column-center" style="vertical-align: top;" width="50%"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td dir="ltr" style="padding: 15px 25px 0;" valign="top">[DEFAULTEVENT]</td></tr></tbody></table></td><td class="stack-column-center" style="vertical-align: top;" width="50%"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #ffffff; padding: 15px; text-align: left;" valign="top"><p style="font-size: 15px; line-height: 24px; text-align: justify; color: #535151; min-height: 150px; white-space: pre-line;">[MESSAGE]</p></td></tr><tr><td class="mail-content" style="word-wrap: break-word; font-family: sans-serif; padding: 6px 15px;"><span style="color: #535151; font-size: 15px; float: left; vertical-align: top; display-inline: block;width: 60px;">From- </span> <span style="color: #535151; font-size: 14px; vertical-align: top; display: inline-block; float: left;">[FROM]</span></td></tr><tr><td style="word-wrap: break-word; font-family: sans-serif; padding: 6px 15px;"><span style="color: #535151; font-size: 15px; float: left;width: 60px; display: inline-block; vertical-align: top;">To- </span> <span style="color: #535151; font-size: 14px; float: left; vertical-align: top;">[TO]</span></td></tr><tr><td style="padding: 5px 15px; word-wrap: break-word;"><span style="color: #0e0149; font-size: 23.96px; vertical-align: top;"><strong>[AMOUNT]/-</strong> </span></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td bgcolor="#0e0149"><table border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="padding: 20px 30px; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #ffffff;"><p style="font-weight: bold; text-align: center;"></p></td></tr></tbody></table></td></tr></tbody></table>';

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
					$upload_file = wp_upload_bits( basename( $value ), null, $this->wps_wgm_get_file_content( $value ) );
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

			$wps_wgm_merry_christmas_template = '<center style="width: 100%; text-align: left;"> <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;"> Christmas-gift-card </div><table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="margin: auto;" class="email-container"> <tr> <td aria-hidden="true" height="5" style="font-size: 0; line-height: 0;"> &nbsp; </td></tr></table> <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="margin: auto;" class="email-container"> <tr> <td bgcolor="#A10005" align="center"> [HEADER] </td></tr><!--===================================logo-section====================================--><tr> <td dir="ltr" style="padding-bottom: 10px; padding-top:0px;" width="100%" valign="top" align="center" bgcolor="#A10005"> <table role="presentation" width="100%" align="center" cellspacing="0" cellpadding="0" border="0"> <tbody> <tr> <td class="stack-column-center" width="100%"> <table role="presentation" width="100%" align="center" cellspacing="0" cellpadding="0" border="0"> <tbody> <tr> <td bgcolor="#A10005" align="center">[LOGO] </td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr><tr> <td bgcolor="#A10005" align="center"> [CHRISTMASTITLE] </td></tr><tr> <td bgcolor="#A10005" align="center" style="padding: 10px 20px 10px; text-align: center;"> <p style="text-align:center;margin: 0; font-family: sans-serif; font-size:18px; line-height: 125%; color: #fff; font-weight:normal;">[MESSAGE]</p> </td></tr><tr> <td bgcolor="#A10005" align="center" style="padding: 0px 20px 0px; text-align: center;"> <p style="margin: 0; font-family: sans-serif; font-size:26px; line-height: 125%; color: #fff; font-weight:600;display: inline-block;padding:8px 20px;">[AMOUNT]</p> </td></tr><!--=====================================================coupon-code and wishes section======================================================--><tr> <td dir="ltr" width="100%" valign="top" align="center" bgcolor="#a10005"> <table role="presentation" width="100%" align="center" cellspacing="0" cellpadding="0" border="0"> <tbody> <tr> <td class="stack-column-center" width="100%" style="background-color:#a10005; text-align: center;padding-bottom:10px;"> <table class="wps-gc-coupon" role="presentation" width="40%" align="center" cellspacing="0" cellpadding="0" border="0"> <tbody> <tr> <td dir="ltr" valign="top" align="center" style="padding:10px 0;"> <div style="border:2px dashed #fff;"> <p style="letter-spacing: 1px; margin: 15px 0px 10px; text-transform: uppercase;font-family: sans-serif; font-weight:600; font-size: 12px; color:#fff;">coupon code </p><span class="wps_coupon_code" style="padding: 10px 10px; text-transform: uppercase; font-size:18px;font-family: sans-serif;font-weight:600;color: rgb(255, 255, 255); font-family: sans-serif;"> [COUPON] </span> <p style="letter-spacing: 1px; text-transform: uppercase;font-family: sans-serif; font-weight: bold; font-size: 12px; margin: 10px 0px 15px; color:#fff;">(Ed:[EXPIRYDATE]) </p></div></td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr><tr> <td bgcolor="#A10005" align="center"> [FOOTER] </td></tr><tr> <td dir="ltr" style="padding-bottom: 10px; padding-top:10px;" width="100%" valign="top" align="center" bgcolor="#E3F3FD"> <table role="presentation" width="100%" align="center" cellspacing="0" cellpadding="0" border="0"> <tbody> <tr> <td class="wps-woo-email-left" width="50%"> <table role="presentation" width="100%" align="left" cellspacing="0" cellpadding="0" border="0"> <tbody> <tr> <td class="wps-gc-to" dir="ltr" style="text-align:left;padding-left:10px;" valign="top"> <p style="font-weight:bold;color: #A10005; font-size: 16px; font-family: sans-serif; margin: 0px;">To:</p><p style="text-decoration:none;padding-top:5px;padding-bottom:5px;font-weight:bold;color: #000; font-size: 13px; font-family: sans-serif; margin: 0px;">[TO]</p></td></tr></tbody> </table> </td><td class="wps-woo-email-right" style="vertical-align: top;" width="50%"> <table role="presentation" width="100%" align="right" cellspacing="0" cellpadding="0" border="0"> <tbody> <tr> <td class="wps-gc-from" dir="ltr" style="text-align:right;padding-right:10px;" valign="top"> <p style="font-weight:bold;color: #A10005; font-size: 16px; font-family: sans-serif; margin: 0px;">From:</p><p style="text-decoration:none;padding-top:5px;padding-bottom:5px;font-weight:bold;color: #000; font-size: 13px; font-family: sans-serif; margin: 0px;">[FROM]</p></td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr></table></center><style>html, body{margin: 0 auto !important; padding: 0 !important; height: 100% !important; width: 100% !important;}*{-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; box-sizing:border-box;}div[style*="margin: 16px 0"]{margin: 0 !important;}table, td{mso-table-lspace: 0pt !important; mso-table-rspace: 0pt !important;}table{border-spacing: 0 !important; border-collapse: collapse !important; table-layout: fixed !important; margin: 0 auto !important;}table table table{table-layout: auto;}img{-ms-interpolation-mode:bicubic;}*[x-apple-data-detectors], .x-gmail-data-detectors, .x-gmail-data-detectors *, .aBn{border-bottom: 0 !important; cursor: default !important; color: inherit !important; text-decoration: none !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important;}.a6S{display: none !important; opacity: 0.01 !important;}img.g-img + div{display: none !important;}.button-link{text-decoration: none !important;}@media only screen and (min-device-width: 501px) and (max-device-width: 599px){.wps-woo-email-left{width:49.5% !important; display: inline-block !important; text-align: left !important;}.wps-woo-email-right{width:49.5% !important; display: inline-block !important; text-align:right !important;}.wps-gc-from{text-align: right !important; padding-left:10px; padding-top:5px;}}@media screen and (max-width: 500px){.wps-woo-email-left{width:100% !important; display:block !important; text-align: left !important;}.wps-woo-email-right{width:100% !important; display:block !important; text-align:left !important;}.wps-gc-from{text-align: left !important; padding-left:10px; padding-top:5px;}.wps-gc-from p{font-size:14px !important;}.wps-gc-to{text-align: left !important; padding-left:10px; padding-top:5px;}.wps-gc-to p{font-size:14px !important;}}@media only screen and (min-device-width: 375px) and (max-device-width: 413px){.email-container{min-width: 375px !important;}}@media screen and (max-width: 480px){u ~ div .email-container{min-width: 100vw; width: 100% !important;}.wps-gc-coupon{width:80% !important;}}</style><style>.wps_coupon_code {color: rgb(255, 255, 255);}.button-td, .button-a{transition: all 100ms ease-in;}.button-td:hover, .button-a:hover{background: #555555 !important; border-color: #555555 !important;}@media screen and (max-width: 600px){.email-container{width: 100% !important; margin: auto !important;}.fluid{max-width: 100% !important; height: auto !important; margin-left: auto !important; margin-right: auto !important;}.stack-column, .stack-column-center{display: block !important; width: 100% !important; max-width: 100% !important; direction: ltr !important;}.stack-column-center{text-align: center !important;}.center-on-narrow{text-align: center !important; display: block !important; margin-left: auto !important; margin-right: auto !important; float: none !important;}table.center-on-narrow{display: inline-block !important;}}</style>';
				$header_image                 = WPS_WGC_URL . 'assets/images/header1.png';
				$header_image                 = "<img src='$header_image' width='600' height='' alt='alt_text' border='0' align='center' style='width: 100%; max-width: 600px; height: auto; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; margin: auto;' class='g-img'/>";

				$christmas_title_image = WPS_WGC_URL . 'assets/images/christmas-title.png';
				$christmas_title_image = "<img src='$christmas_title_image' width='250' height='' alt='alt_text' border='0' align='center' style='padding:0 10px;width: 100%; max-width: 500px; height: auto; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; margin: auto;' class='g-img'>";

				$footer_image = WPS_WGC_URL . 'assets/images/footer1.png';
				$footer_image = "<img src='$footer_image' width='600' height='' alt='alt_text' border='0' align='center' style='width: 100%; max-width: 600px; height: auto; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; margin: auto;' class='g-img'>";
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
								die();
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
				'doc'     => '<a href="https://docs.wpswings.com/woo-gift-cards-lite/?utm_source=wpswings-giftcards-doc&utm_medium=giftcards-org-backend&utm_campaign=documentation" target="_blank"><img src="' . esc_html( WPS_WGC_URL ) . 'assets/images/Documentation.svg" class="wps-info-img" alt="documentation image" style="margin-right: 5px;vertical-align: middle;max-width: 15px;">' . __( 'Documentation', 'woo-gift-cards-lite' ) . '</a>',
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
	 * Get the File Content
	 *
	 * @param string $wps_file_path file path.
	 * @return string $response['body'].
	 */
	public function wps_wgm_get_file_content( $wps_file_path ) {

		$response = wp_remote_get(
			$wps_file_path,
			array(
				'timeout'    => 20,
				'sslverify'  => false,
				'user-agent' => 'Ultimate Woocommerce Gift Cards/' . $this->version,
			)
		);

		return $response['body'];
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
		
			$excluded_product_id =  get_option( 'contributor_product_id' );
			$excluded_product_id1 = get_option( 'wps_gccoupon_rechargeable_product_id' );

			// Exclude the specific product from the query.
			$query->set('post__not_in', array($excluded_product_id,$excluded_product_id1));
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

}
?>
