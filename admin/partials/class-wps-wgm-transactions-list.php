<?php
/**
 * List table for Gift Cards > Transactions.
 *
 * @link       https://wpswings.com/
 * @since      3.3.0
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Lists gift card transactions with filters.
 */
class Wps_Wgm_Transactions_List extends WP_List_Table {

	/**
	 * Active filters.
	 *
	 * @var array
	 */
	private $filters;

	/**
	 * Constructor.
	 *
	 * @param array $filters Active filters.
	 */
	public function __construct( $filters = array() ) {
		$this->filters = $filters;
		parent::__construct(
			array(
				'singular' => 'transaction',
				'plural'   => 'transactions',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'created_at'     => __( 'Date', 'woo-gift-cards-lite' ),
			'coupon_code'    => __( 'Gift card', 'woo-gift-cards-lite' ),
			'type'           => __( 'Type', 'woo-gift-cards-lite' ),
			'amount_change'  => __( 'Amount', 'woo-gift-cards-lite' ),
			'balance_before' => __( 'Before', 'woo-gift-cards-lite' ),
			'balance_after'  => __( 'After', 'woo-gift-cards-lite' ),
			'order_id'       => __( 'Order', 'woo-gift-cards-lite' ),
			'user_id'        => __( 'By', 'woo-gift-cards-lite' ),
			'source'         => __( 'Source', 'woo-gift-cards-lite' ),
			'note'           => __( 'Note', 'woo-gift-cards-lite' ),
		);
	}

	/**
	 * Load the current page of rows.
	 *
	 * @return void
	 */
	public function prepare_items() {
		$per_page = 20;
		$page     = $this->get_pagenum();

		$this->_column_headers = array( $this->get_columns(), array(), array() );
		$this->items           = Wps_Wgm_Transactions::query(
			array_merge(
				$this->filters,
				array(
					'per_page' => $per_page,
					'page'     => $page,
				)
			)
		);

		$this->set_pagination_args(
			array(
				'total_items' => Wps_Wgm_Transactions::count( $this->filters ),
				'per_page'    => $per_page,
			)
		);
	}

	/**
	 * Message when nothing matches.
	 *
	 * @return void
	 */
	public function no_items() {
		esc_html_e( 'No gift card transactions found.', 'woo-gift-cards-lite' );
	}

	/**
	 * Filter controls above the table.
	 *
	 * @param string $which top or bottom.
	 * @return void
	 */
	protected function extra_tablenav( $which ) {
		if ( 'top' !== $which ) {
			return;
		}
		$f = $this->filters;
		?>
		<div class="alignleft actions">
			<input type="search" name="code" placeholder="<?php esc_attr_e( 'Gift card code', 'woo-gift-cards-lite' ); ?>" value="<?php echo esc_attr( isset( $f['code'] ) ? $f['code'] : '' ); ?>" />
			<select name="type">
				<option value=""><?php esc_html_e( 'All types', 'woo-gift-cards-lite' ); ?></option>
				<?php foreach ( Wps_Wgm_Transactions::get_types() as $key => $label ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( isset( $f['type'] ) ? $f['type'] : '', $key ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
			<select name="source">
				<option value=""><?php esc_html_e( 'All sources', 'woo-gift-cards-lite' ); ?></option>
				<?php foreach ( Wps_Wgm_Transactions::get_sources() as $key => $label ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( isset( $f['source'] ) ? $f['source'] : '', $key ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
			<label class="screen-reader-text" for="wps-wgm-tx-from"><?php esc_html_e( 'From', 'woo-gift-cards-lite' ); ?></label>
			<input type="date" id="wps-wgm-tx-from" name="date_from" value="<?php echo esc_attr( isset( $f['date_from'] ) ? $f['date_from'] : '' ); ?>" />
			<label class="screen-reader-text" for="wps-wgm-tx-to"><?php esc_html_e( 'To', 'woo-gift-cards-lite' ); ?></label>
			<input type="date" id="wps-wgm-tx-to" name="date_to" value="<?php echo esc_attr( isset( $f['date_to'] ) ? $f['date_to'] : '' ); ?>" />
			<?php if ( ! empty( $f['coupon_id'] ) ) : ?>
				<input type="hidden" name="coupon_id" value="<?php echo esc_attr( $f['coupon_id'] ); ?>" />
			<?php endif; ?>
			<?php submit_button( __( 'Filter', 'woo-gift-cards-lite' ), '', 'filter_action', false ); ?>
			<?php if ( ! empty( $f ) ) : ?>
				<a class="button-link" href="<?php echo esc_url( Wps_Wgm_Transactions_Admin::page_url() ); ?>"><?php esc_html_e( 'Reset', 'woo-gift-cards-lite' ); ?></a>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Cell output.
	 *
	 * @param object $item        Row.
	 * @param string $column_name Column.
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'created_at':
				return esc_html( Wps_Wgm_Transactions_Admin::format_date( $item ) );
			case 'coupon_code':
				$link = get_edit_post_link( $item->coupon_id );
				return $link ? '<a href="' . esc_url( $link ) . '"><code>' . esc_html( $item->coupon_code ) . '</code></a>' : '<code>' . esc_html( $item->coupon_code ) . '</code>';
			case 'type':
				return esc_html( Wps_Wgm_Transactions::label( $item->type ) );
			case 'amount_change':
				return wp_kses_post( Wps_Wgm_Transactions_Admin::format_change( $item ) );
			case 'balance_before':
			case 'balance_after':
				return wp_kses_post( wc_price( $item->$column_name, array( 'currency' => $item->currency ) ) );
			case 'order_id':
				return wp_kses_post( Wps_Wgm_Transactions_Admin::order_link( $item->order_id ) );
			case 'user_id':
				return esc_html( Wps_Wgm_Transactions_Admin::user_name( $item->user_id, $item->source ) );
			case 'source':
				return esc_html( Wps_Wgm_Transactions::label( $item->source, 'source' ) );
			case 'note':
				return esc_html( (string) $item->note );
		}
		return '';
	}
}
