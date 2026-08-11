<?php
/**
 * Settings page template.
 *
 * @var bool $is_pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="pc-settings-page">

	<?php settings_errors( 'mdpc_settings' ); ?>

	<!-- Header -->
	<div class="pc-settings-header">
		<div class="pc-settings-header__brand">
			<div class="pc-settings-header__icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<rect x="2" y="7" width="20" height="14" rx="2"></rect>
					<path d="M16 2H8l-2 5h12l-2-5z"></path>
					<circle cx="8" cy="14" r="1" fill="currentColor" stroke="none"></circle>
					<circle cx="12" cy="14" r="1" fill="currentColor" stroke="none"></circle>
					<circle cx="16" cy="14" r="1" fill="currentColor" stroke="none"></circle>
				</svg>
			</div>
			<div>
				<div class="pc-settings-header__title"><?php esc_html_e( 'Product Carousel', 'monkeydesign-product-carousel' ); ?></div>
				<div class="pc-settings-header__sub"><?php esc_html_e( 'by MonkeyDesign', 'monkeydesign-product-carousel' ); ?></div>
			</div>
		</div>
		<div class="pc-settings-header__meta">
			<span class="pc-badge <?php echo esc_attr( $is_pro ? 'pc-badge--pro' : 'pc-badge--free' ); ?>">
				<?php echo $is_pro ? esc_html__( 'PRO', 'monkeydesign-product-carousel' ) : esc_html__( 'FREE', 'monkeydesign-product-carousel' ); ?>
			</span>
			<span class="pc-settings-version">v<?php echo esc_html( MDPC_VERSION ); ?></span>
		</div>
	</div>

	<!-- Body -->
	<div class="pc-settings-body">

		<!-- Main column -->
		<div class="pc-settings-main">

			<?php do_action( 'mdpc_settings_before_cards' ); ?>

			<!-- System Status -->
			<div class="pc-card">
				<div class="pc-card__header">
					<h3 class="pc-card__title"><?php esc_html_e( 'System Status', 'monkeydesign-product-carousel' ); ?></h3>
				</div>
				<div class="pc-card__body">
					<div class="pc-status-list">

						<?php
						// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- template-scoped locals
						$wc_ok  = class_exists( 'WooCommerce' );
						$el_ok  = class_exists( '\Elementor\Plugin' );
						$wc_ver = $wc_ok ? WC()->version : null;
						$el_ver = $el_ok ? ELEMENTOR_VERSION : null;
						// phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
						?>

						<div class="pc-status-indicator">
							<span class="pc-status-dot <?php echo esc_attr( $wc_ok ? 'pc-status-dot--ok' : 'pc-status-dot--err' ); ?>"></span>
							<div>
								<p class="pc-status-label">WooCommerce</p>
								<p class="pc-status-desc">
									<?php if ( $wc_ok ) : ?>
										<?php /* translators: %s: WooCommerce version number */ ?>
										<?php printf( esc_html__( 'Active — WooCommerce %s', 'monkeydesign-product-carousel' ), esc_html( $wc_ver ) ); ?>
									<?php else : ?>
										<?php esc_html_e( 'Not active. WooCommerce is required.', 'monkeydesign-product-carousel' ); ?>
									<?php endif; ?>
								</p>
							</div>
						</div>

						<div class="pc-status-indicator">
							<span class="pc-status-dot <?php echo esc_attr( $el_ok ? 'pc-status-dot--ok' : 'pc-status-dot--err' ); ?>"></span>
							<div>
								<p class="pc-status-label">Elementor</p>
								<p class="pc-status-desc">
									<?php if ( $el_ok ) : ?>
										<?php /* translators: %s: Elementor version number */ ?>
										<?php printf( esc_html__( 'Active — Elementor %s', 'monkeydesign-product-carousel' ), esc_html( $el_ver ) ); ?>
									<?php else : ?>
										<?php esc_html_e( 'Not active. Elementor is required.', 'monkeydesign-product-carousel' ); ?>
									<?php endif; ?>
								</p>
							</div>
						</div>

						<div class="pc-status-indicator">
							<span class="pc-status-dot pc-status-dot--ok"></span>
							<div>
								<p class="pc-status-label">PHP</p>
								<p class="pc-status-desc"><?php echo esc_html( PHP_VERSION ); ?></p>
							</div>
						</div>

					</div>
				</div>
			</div>

			<?php do_action( 'mdpc_settings_after_cards' ); ?>

		</div><!-- .pc-settings-main -->

		<!-- Sidebar -->
		<div class="pc-sidebar">

			<?php if ( ! $is_pro ) : ?>
			<div class="pc-card pc-pro-card">
				<div class="pc-card__header">
					<h3 class="pc-card__title">&#10024; <?php esc_html_e( 'Upgrade to PRO', 'monkeydesign-product-carousel' ); ?></h3>
				</div>
				<div class="pc-card__body">
					<ul class="pc-pro-feature-list">
						<li><?php esc_html_e( 'Quick view modal', 'monkeydesign-product-carousel' ); ?></li>
						<li><?php esc_html_e( 'AJAX add to cart with feedback', 'monkeydesign-product-carousel' ); ?></li>
						<li><?php esc_html_e( 'Wishlist button', 'monkeydesign-product-carousel' ); ?></li>
						<li><?php esc_html_e( 'Sale countdown timer', 'monkeydesign-product-carousel' ); ?></li>
						<li><?php esc_html_e( 'Overlay skin', 'monkeydesign-product-carousel' ); ?></li>
						<li><?php esc_html_e( 'Thumbnail navigation & slider sync', 'monkeydesign-product-carousel' ); ?></li>
						<li><?php esc_html_e( 'Recently viewed products', 'monkeydesign-product-carousel' ); ?></li>
						<li><?php esc_html_e( 'Preset export / import', 'monkeydesign-product-carousel' ); ?></li>
					</ul>
					<a href="https://monkeydesign.hu/en/products/elementor-addons/monkeydesign-product-carousel" class="pc-btn pc-btn--upgrade" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'Get PRO', 'monkeydesign-product-carousel' ); ?> &rarr;
					</a>
				</div>
			</div>
			<?php endif; ?>

			<div class="pc-card">
				<div class="pc-card__body">
					<ul class="pc-info-list">
						<li>
							<span class="pc-info-label"><?php esc_html_e( 'Version', 'monkeydesign-product-carousel' ); ?></span>
							<span class="pc-info-value"><?php echo esc_html( MDPC_VERSION ); ?></span>
						</li>
						<li>
							<span class="pc-info-label"><?php esc_html_e( 'License', 'monkeydesign-product-carousel' ); ?></span>
							<span class="pc-info-value"><?php echo $is_pro ? esc_html__( 'PRO', 'monkeydesign-product-carousel' ) : esc_html__( 'Free', 'monkeydesign-product-carousel' ); ?></span>
						</li>
						<li>
							<span class="pc-info-label"><?php esc_html_e( 'Author', 'monkeydesign-product-carousel' ); ?></span>
							<span class="pc-info-value"><a href="https://monkeydesign.hu/en/" target="_blank" rel="noopener noreferrer">MonkeyDesign</a></span>
						</li>
					</ul>
				</div>
			</div>

			<?php if ( 'en_US' !== get_locale() ) : ?>
			<div class="pc-card pc-translation-notice">
				<div class="pc-card__body">
					<p class="pc-translation-notice__text">
						<?php esc_html_e( 'Found a translation issue?', 'monkeydesign-product-carousel' ); ?>
					</p>
					<a href="https://wordpress.org/support/plugin/monkeydesign-product-carousel/" class="pc-translation-notice__link" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'Let us know', 'monkeydesign-product-carousel' ); ?> &rarr;
					</a>
				</div>
			</div>
			<?php endif; ?>

		</div><!-- .pc-sidebar -->

	</div><!-- .pc-settings-body -->

</div><!-- .pc-settings-page -->
