<?php
/**
 * My Account > My Gift Cards.
 *
 * This template can be overridden by copying it to yourtheme/woo-gift-cards-lite/myaccount/wps-wgm-my-gift-cards.php.
 *
 * @package woo-gift-cards-lite
 * @since   3.2.13
 *
 * @var array[] $cards     All cards, usable first; each has a 'type' of received or sent.
 * @var array[] $received  Cards sent to the current user, usable cards first.
 * @var array[] $purchased Cards the current user bought for someone else.
 * @var array   $summary   Totals for the received cards: balance, active, expiring.
 * @var array   $counts    Card counts per filter: all, received, sent, active, expired, used.
 * @var int     $per_page  Cards per page.
 * @var string  $accent    Hex color used for the card face.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wps_wgm_status_labels = array(
	'active'   => __( 'Active', 'woo-gift-cards-lite' ),
	'used'     => __( 'Redeemed', 'woo-gift-cards-lite' ),
	'expired'  => __( 'Expired', 'woo-gift-cards-lite' ),
	'disabled' => __( 'Disabled', 'woo-gift-cards-lite' ),
);

$wps_wgm_icon_gift   = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M20 12v9H4v-9M2 7h20v5H2zM12 21V7M12 7H7.5a2.5 2.5 0 1 1 0-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 1 0 0-5C13 2 12 7 12 7z"/></svg>';
$wps_wgm_icon_copy   = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>';
$wps_wgm_icon_mail   = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg>';
$wps_wgm_icon_search = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>';
$wps_wgm_icon_eye    = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>';
$wps_wgm_icon_close  = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M18 6 6 18M6 6l12 12"/></svg>';
$wps_wgm_icon_clock  = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>';
$wps_wgm_svg_tags    = array(
	'svg'    => array(
		'viewbox'     => true,
		'aria-hidden' => true,
		'focusable'   => true,
	),
	'path'   => array( 'd' => true ),
	'rect'   => array(
		'x'      => true,
		'y'      => true,
		'width'  => true,
		'height' => true,
		'rx'     => true,
	),
	'circle' => array(
		'cx' => true,
		'cy' => true,
		'r'  => true,
	),
);

$wps_wgm_filters = array(
	'all'      => __( 'All', 'woo-gift-cards-lite' ),
	'received' => __( 'Received', 'woo-gift-cards-lite' ),
	'sent'     => __( 'Sent', 'woo-gift-cards-lite' ),
	'active'   => __( 'Active', 'woo-gift-cards-lite' ),
	'expired'  => __( 'Expired', 'woo-gift-cards-lite' ),
	'used'     => __( 'Redeemed', 'woo-gift-cards-lite' ),
);
$wps_wgm_sorts   = array(
	'default'      => __( 'Recommended', 'woo-gift-cards-lite' ),
	'newest'       => __( 'Newest first', 'woo-gift-cards-lite' ),
	'oldest'       => __( 'Oldest first', 'woo-gift-cards-lite' ),
	'balance-desc' => __( 'Balance: high to low', 'woo-gift-cards-lite' ),
	'balance-asc'  => __( 'Balance: low to high', 'woo-gift-cards-lite' ),
	'expiry'       => __( 'Expiring soonest', 'woo-gift-cards-lite' ),
);
?>
<div class="wps-wgm-mgc" style="--wps-mgc-accent: <?php echo esc_attr( $accent ); ?>;" data-per-page="<?php echo esc_attr( $per_page ); ?>">

<?php if ( empty( $cards ) ) : ?>
	<div class="wps-wgm-mgc-empty">
		<span class="wps-wgm-mgc-empty-icon"><?php echo wp_kses( $wps_wgm_icon_gift, $wps_wgm_svg_tags ); ?></span>
		<h3><?php esc_html_e( 'No gift cards yet', 'woo-gift-cards-lite' ); ?></h3>
		<p><?php esc_html_e( 'Gift cards you buy for others, or receive from them, will show up here with their balance and expiry date.', 'woo-gift-cards-lite' ); ?></p>
		<a class="woocommerce-Button button wps-wgm-mgc-btn wps-wgm-mgc-btn-primary" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Browse gift cards', 'woo-gift-cards-lite' ); ?></a>
	</div>
<?php else : ?>
	<?php if ( ! empty( $received ) ) : ?>
		<div class="wps-wgm-mgc-summary">
			<div class="wps-wgm-mgc-stat wps-wgm-mgc-stat-main">
				<span class="wps-wgm-mgc-stat-label"><?php esc_html_e( 'Available to spend', 'woo-gift-cards-lite' ); ?></span>
				<span class="wps-wgm-mgc-stat-value"><?php echo wp_kses_post( wc_price( $summary['balance'] ) ); ?></span>
			</div>
			<div class="wps-wgm-mgc-stat">
				<span class="wps-wgm-mgc-stat-label"><?php esc_html_e( 'Active cards', 'woo-gift-cards-lite' ); ?></span>
				<span class="wps-wgm-mgc-stat-value"><?php echo esc_html( number_format_i18n( $summary['active'] ) ); ?></span>
			</div>
			<div class="wps-wgm-mgc-stat<?php echo $summary['expiring'] ? ' is-warning' : ''; ?>">
				<span class="wps-wgm-mgc-stat-label"><?php esc_html_e( 'Expiring soon', 'woo-gift-cards-lite' ); ?></span>
				<span class="wps-wgm-mgc-stat-value"><?php echo esc_html( number_format_i18n( $summary['expiring'] ) ); ?></span>
			</div>
		</div>
	<?php endif; ?>

	<?php // Search, filters and sorting need JavaScript; without it every card is listed. ?>
	<div class="wps-wgm-mgc-controls" hidden>
		<div class="wps-wgm-mgc-toolbar">
			<label class="wps-wgm-mgc-search">
				<?php echo wp_kses( $wps_wgm_icon_search, $wps_wgm_svg_tags ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Search gift cards', 'woo-gift-cards-lite' ); ?></span>
				<input type="search" class="wps-wgm-mgc-search-input" placeholder="<?php esc_attr_e( 'Search by code, name, email or order', 'woo-gift-cards-lite' ); ?>" autocomplete="off" />
			</label>
			<label class="wps-wgm-mgc-sort">
				<span class="wps-wgm-mgc-sort-label"><?php esc_html_e( 'Sort by', 'woo-gift-cards-lite' ); ?></span>
				<select class="wps-wgm-mgc-sort-select">
					<?php foreach ( $wps_wgm_sorts as $wps_wgm_sort_key => $wps_wgm_sort_label ) : ?>
						<option value="<?php echo esc_attr( $wps_wgm_sort_key ); ?>"><?php echo esc_html( $wps_wgm_sort_label ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</div>
		<div class="wps-wgm-mgc-filters" role="group" aria-label="<?php esc_attr_e( 'Filter gift cards', 'woo-gift-cards-lite' ); ?>">
			<?php foreach ( $wps_wgm_filters as $wps_wgm_filter_key => $wps_wgm_filter_label ) : ?>
				<button type="button" class="wps-wgm-mgc-chip" data-filter="<?php echo esc_attr( $wps_wgm_filter_key ); ?>" aria-pressed="<?php echo 'all' === $wps_wgm_filter_key ? 'true' : 'false'; ?>">
					<?php echo esc_html( $wps_wgm_filter_label ); ?>
					<span class="wps-wgm-mgc-chip-count"><?php echo esc_html( number_format_i18n( $counts[ $wps_wgm_filter_key ] ) ); ?></span>
				</button>
			<?php endforeach; ?>
		</div>
		<p class="wps-wgm-mgc-results" role="status" aria-live="polite"></p>
	</div>

	<ul class="wps-wgm-mgc-grid">
		<?php
		foreach ( $cards as $wps_wgm_index => $wps_wgm_card ) :
			$wps_wgm_code    = strtoupper( $wps_wgm_card['code'] );
			$wps_wgm_is_sent = 'sent' === $wps_wgm_card['type'];
			$wps_wgm_party   = $wps_wgm_is_sent ? $wps_wgm_card['mail_to'] : $wps_wgm_card['from'];
			$wps_wgm_percent = $wps_wgm_card['original'] > 0 ? max( 0, min( 100, round( $wps_wgm_card['balance'] / $wps_wgm_card['original'] * 100 ) ) ) : 0;
			$wps_wgm_uses    = count( $wps_wgm_card['history'] );
			$wps_wgm_search  = array( $wps_wgm_card['code'], $wps_wgm_card['from'], $wps_wgm_card['mail_to'] );
			if ( $wps_wgm_card['order'] ) {
				$wps_wgm_search[] = '#' . $wps_wgm_card['order']->get_order_number();
			}
			$wps_wgm_expiry = $wps_wgm_card['expires'] ? wc_format_datetime( $wps_wgm_card['expires'] ) : __( 'Never', 'woo-gift-cards-lite' );
			?>
			<li class="wps-wgm-mgc-card wps-wgm-mgc-status-<?php echo esc_attr( $wps_wgm_card['status'] ); ?>"
				data-index="<?php echo esc_attr( $wps_wgm_index ); ?>"
				data-id="<?php echo esc_attr( $wps_wgm_card['id'] ); ?>"
				data-type="<?php echo esc_attr( $wps_wgm_card['type'] ); ?>"
				data-status="<?php echo esc_attr( $wps_wgm_card['status'] ); ?>"
				data-balance="<?php echo esc_attr( $wps_wgm_card['balance'] ); ?>"
				data-expires="<?php echo esc_attr( $wps_wgm_card['expires'] ? $wps_wgm_card['expires']->getTimestamp() : '' ); ?>"
				data-search="<?php echo esc_attr( strtolower( implode( ' ', array_filter( $wps_wgm_search ) ) ) ); ?>">
				<div class="wps-wgm-mgc-face">
					<div class="wps-wgm-mgc-face-top">
						<span class="wps-wgm-mgc-brand"><?php echo wp_kses( $wps_wgm_icon_gift, $wps_wgm_svg_tags ); ?><?php echo $wps_wgm_is_sent ? esc_html__( 'Sent', 'woo-gift-cards-lite' ) : esc_html__( 'Received', 'woo-gift-cards-lite' ); ?></span>
						<span class="wps-wgm-mgc-badge"><?php echo esc_html( $wps_wgm_status_labels[ $wps_wgm_card['status'] ] ); ?></span>
					</div>
					<div class="wps-wgm-mgc-balance">
						<span class="wps-wgm-mgc-amount"><?php echo wp_kses_post( wc_price( $wps_wgm_card['balance'] ) ); ?></span>
						<span class="wps-wgm-mgc-of">
							<?php
							/* translators: %s: original gift card amount. */
							echo wp_kses_post( sprintf( __( 'left of %s', 'woo-gift-cards-lite' ), wc_price( $wps_wgm_card['original'] ) ) );
							?>
						</span>
					</div>
					<div class="wps-wgm-mgc-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo esc_attr( $wps_wgm_percent ); ?>" aria-label="<?php esc_attr_e( 'Balance remaining', 'woo-gift-cards-lite' ); ?>">
						<span style="width: <?php echo esc_attr( $wps_wgm_percent ); ?>%;"></span>
					</div>
					<div class="wps-wgm-mgc-code-row">
						<code class="wps-wgm-mgc-code"><?php echo esc_html( $wps_wgm_code ); ?></code>
						<button type="button" class="wps-wgm-mgc-copy" data-code="<?php echo esc_attr( $wps_wgm_code ); ?>">
							<?php echo wp_kses( $wps_wgm_icon_copy, $wps_wgm_svg_tags ); ?>
							<span class="wps-wgm-mgc-copy-label"><?php esc_html_e( 'Copy', 'woo-gift-cards-lite' ); ?></span>
							<span class="screen-reader-text"><?php esc_html_e( 'gift card code', 'woo-gift-cards-lite' ); ?></span>
						</button>
					</div>
				</div>

				<div class="wps-wgm-mgc-body">
					<dl class="wps-wgm-mgc-meta">
						<div>
							<dt><?php echo $wps_wgm_is_sent ? esc_html__( 'Sent to', 'woo-gift-cards-lite' ) : esc_html__( 'From', 'woo-gift-cards-lite' ); ?></dt>
							<dd><?php echo esc_html( $wps_wgm_party ? $wps_wgm_party : '—' ); ?></dd>
						</div>
						<div>
							<dt><?php esc_html_e( 'Expires', 'woo-gift-cards-lite' ); ?></dt>
							<dd><?php echo esc_html( $wps_wgm_expiry ); ?></dd>
						</div>
					</dl>

					<?php if ( $wps_wgm_card['expiring_soon'] ) : ?>
						<p class="wps-wgm-mgc-alert">
							<?php echo wp_kses( $wps_wgm_icon_clock, $wps_wgm_svg_tags ); ?>
							<?php
							echo esc_html(
								$wps_wgm_card['days_left'] <= 0
									? __( 'Expires today', 'woo-gift-cards-lite' )
									/* translators: %d: number of days until the gift card expires. */
									: sprintf( _n( 'Expires in %d day', 'Expires in %d days', $wps_wgm_card['days_left'], 'woo-gift-cards-lite' ), $wps_wgm_card['days_left'] )
							);
							?>
						</p>
					<?php endif; ?>

					<div class="wps-wgm-mgc-actions">
						<div class="wps-wgm-mgc-action-row">
							<button type="button" class="wps-wgm-mgc-btn wps-wgm-mgc-view" aria-haspopup="dialog">
								<?php echo wp_kses( $wps_wgm_icon_eye, $wps_wgm_svg_tags ); ?>
								<?php esc_html_e( 'View details', 'woo-gift-cards-lite' ); ?>
							</button>
							<?php if ( $wps_wgm_card['can_resend'] ) : ?>
								<button type="button" class="wps-wgm-mgc-btn wps-wgm-mgc-resend" data-coupon-id="<?php echo esc_attr( $wps_wgm_card['id'] ); ?>">
									<?php echo wp_kses( $wps_wgm_icon_mail, $wps_wgm_svg_tags ); ?>
									<span class="wps-wgm-mgc-resend-label"><?php esc_html_e( 'Resend email', 'woo-gift-cards-lite' ); ?></span>
								</button>
							<?php endif; ?>
						</div>
						<?php if ( $wps_wgm_card['can_resend'] ) : ?>
							<p class="wps-wgm-mgc-notice" role="status" aria-live="polite"></p>
						<?php endif; ?>
					</div>

					<p class="wps-wgm-mgc-usage">
						<?php
						echo esc_html(
							$wps_wgm_uses
								/* translators: %s: number of times the gift card was used. */
								? sprintf( _n( 'Used %s time', 'Used %s times', $wps_wgm_uses, 'woo-gift-cards-lite' ), number_format_i18n( $wps_wgm_uses ) )
								: __( 'Not used yet', 'woo-gift-cards-lite' )
						);
						?>
					</p>
				</div>

				<template class="wps-wgm-mgc-details">
					<div class="wps-wgm-mgc-detail-card wps-wgm-mgc-status-<?php echo esc_attr( $wps_wgm_card['status'] ); ?>">
						<div class="wps-wgm-mgc-code-row">
							<code class="wps-wgm-mgc-code"><?php echo esc_html( $wps_wgm_code ); ?></code>
							<button type="button" class="wps-wgm-mgc-copy" data-code="<?php echo esc_attr( $wps_wgm_code ); ?>">
								<?php echo wp_kses( $wps_wgm_icon_copy, $wps_wgm_svg_tags ); ?>
								<span class="wps-wgm-mgc-copy-label"><?php esc_html_e( 'Copy', 'woo-gift-cards-lite' ); ?></span>
								<span class="screen-reader-text"><?php esc_html_e( 'gift card code', 'woo-gift-cards-lite' ); ?></span>
							</button>
						</div>
						<span class="wps-wgm-mgc-badge"><?php echo esc_html( $wps_wgm_status_labels[ $wps_wgm_card['status'] ] ); ?></span>
					</div>

					<div class="wps-wgm-mgc-detail-stats">
						<div>
							<span><?php esc_html_e( 'Original balance', 'woo-gift-cards-lite' ); ?></span>
							<strong><?php echo wp_kses_post( wc_price( $wps_wgm_card['original'] ) ); ?></strong>
						</div>
						<div>
							<span><?php esc_html_e( 'Remaining balance', 'woo-gift-cards-lite' ); ?></span>
							<strong><?php echo wp_kses_post( wc_price( $wps_wgm_card['balance'] ) ); ?></strong>
						</div>
						<div>
							<span><?php esc_html_e( 'Expiry date', 'woo-gift-cards-lite' ); ?></span>
							<strong><?php echo esc_html( $wps_wgm_expiry ); ?></strong>
						</div>
					</div>

					<dl class="wps-wgm-mgc-meta">
						<div>
							<dt><?php echo $wps_wgm_is_sent ? esc_html__( 'Sent to', 'woo-gift-cards-lite' ) : esc_html__( 'From', 'woo-gift-cards-lite' ); ?></dt>
							<dd><?php echo esc_html( $wps_wgm_party ? $wps_wgm_party : '—' ); ?></dd>
						</div>
						<?php if ( $wps_wgm_card['issued'] ) : ?>
							<div>
								<dt><?php esc_html_e( 'Issued on', 'woo-gift-cards-lite' ); ?></dt>
								<dd><?php echo esc_html( wc_format_datetime( $wps_wgm_card['issued'] ) ); ?></dd>
							</div>
						<?php endif; ?>
						<div>
							<dt><?php esc_html_e( 'Amount used', 'woo-gift-cards-lite' ); ?></dt>
							<dd><?php echo wp_kses_post( wc_price( max( 0, $wps_wgm_card['original'] - $wps_wgm_card['balance'] ) ) ); ?></dd>
						</div>
						<?php if ( $wps_wgm_card['order'] ) : ?>
							<div>
								<dt><?php esc_html_e( 'Purchase order', 'woo-gift-cards-lite' ); ?></dt>
								<dd><a href="<?php echo esc_url( $wps_wgm_card['order']->get_view_order_url() ); ?>">#<?php echo esc_html( $wps_wgm_card['order']->get_order_number() ); ?></a></dd>
							</div>
						<?php endif; ?>
					</dl>

					<h4 class="wps-wgm-mgc-detail-heading"><?php esc_html_e( 'Usage history', 'woo-gift-cards-lite' ); ?></h4>
					<?php if ( $wps_wgm_uses ) : ?>
						<ol class="wps-wgm-mgc-timeline">
							<?php foreach ( $wps_wgm_card['history'] as $wps_wgm_use ) : ?>
								<li>
									<span class="wps-wgm-mgc-timeline-date">
										<?php echo esc_html( $wps_wgm_use['date'] ? wc_format_datetime( $wps_wgm_use['date'] ) : '' ); ?>
										<?php if ( $wps_wgm_use['order_url'] ) : ?>
											&middot; <a href="<?php echo esc_url( $wps_wgm_use['order_url'] ); ?>">#<?php echo esc_html( $wps_wgm_use['order_number'] ); ?></a>
										<?php endif; ?>
									</span>
									<span class="wps-wgm-mgc-timeline-amount">&minus;<?php echo wp_kses_post( wc_price( $wps_wgm_use['amount'], array( 'currency' => $wps_wgm_use['currency'] ) ) ); ?></span>
								</li>
							<?php endforeach; ?>
						</ol>
					<?php else : ?>
						<p class="wps-wgm-mgc-detail-empty"><?php esc_html_e( 'This gift card has not been used yet.', 'woo-gift-cards-lite' ); ?></p>
					<?php endif; ?>
				</template>
			</li>
		<?php endforeach; ?>
	</ul>

	<div class="wps-wgm-mgc-no-results" hidden>
		<span class="wps-wgm-mgc-empty-icon"><?php echo wp_kses( $wps_wgm_icon_search, $wps_wgm_svg_tags ); ?></span>
		<h3><?php esc_html_e( 'No matching gift cards', 'woo-gift-cards-lite' ); ?></h3>
		<p><?php esc_html_e( 'Try a different search term or filter.', 'woo-gift-cards-lite' ); ?></p>
		<button type="button" class="wps-wgm-mgc-btn wps-wgm-mgc-btn-primary wps-wgm-mgc-reset"><?php esc_html_e( 'Clear search and filters', 'woo-gift-cards-lite' ); ?></button>
	</div>

	<nav class="wps-wgm-mgc-pagination" aria-label="<?php esc_attr_e( 'Gift card pages', 'woo-gift-cards-lite' ); ?>" hidden></nav>

	<dialog class="wps-wgm-mgc-dialog" aria-labelledby="wps-wgm-mgc-dialog-title">
		<div class="wps-wgm-mgc-dialog-header">
			<h3 id="wps-wgm-mgc-dialog-title"><?php esc_html_e( 'Gift card details', 'woo-gift-cards-lite' ); ?></h3>
			<button type="button" class="wps-wgm-mgc-dialog-close">
				<?php echo wp_kses( $wps_wgm_icon_close, $wps_wgm_svg_tags ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Close', 'woo-gift-cards-lite' ); ?></span>
			</button>
		</div>
		<div class="wps-wgm-mgc-dialog-body"></div>
	</dialog>
<?php endif; ?>
</div>
