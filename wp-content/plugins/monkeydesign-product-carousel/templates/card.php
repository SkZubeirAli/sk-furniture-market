<?php
/**
 * Product card template.
 *
 * @var WC_Product      $product
 * @var array           $settings
 * @var MDPC_Card_Renderer $this
 *
 * Override by copying to: your-theme/product-carousel/card.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- template-scoped locals, not globals
$product_id  = $product->get_id();
$product_url = get_permalink( $product_id );
$image_size  = $settings['image_size'];
$loading     = $settings['is_first'] ? 'eager' : 'lazy';
$fetchpriority = $settings['is_first'] ? ' fetchpriority="high"' : '';
?>
<div class="pc-card" data-product-id="<?php echo esc_attr( $product_id ); ?>">

	<?php if ( $settings['show_image'] ) : ?>
	<div class="pc-card__image-wrap">
		<a href="<?php echo esc_url( $product_url ); ?>" tabindex="-1" aria-hidden="true">
			<?php
			$image_html = $product->get_image(
				$image_size,
				[
					'loading'       => $loading,
					'fetchpriority' => $settings['is_first'] ? 'high' : false,
					'class'         => 'pc-card__image',
				]
			);
			echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</a>

		<?php if ( $settings['show_badge'] ) : ?>
			<?php $badge = $this->get_badge( $product ); ?>
			<?php if ( $badge ) : ?>
				<div class="pc-card__badges"><?php echo $badge; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( $settings['show_wishlist'] ) : ?>
		<button class="pc-wishlist-btn"
		        data-product-id="<?php echo esc_attr( $product_id ); ?>"
		        aria-label="<?php echo esc_attr( sprintf( __( 'Add to wishlist: %s', 'monkeydesign-product-carousel' ), $product->get_name() ) ); ?>"
		        aria-pressed="false">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
		</button>
		<?php endif; ?>

		<?php if ( $settings['show_quick_view'] ) : ?>
		<button class="pc-qv-trigger"
		        data-product-id="<?php echo esc_attr( $product_id ); ?>"
		        aria-label="<?php echo esc_attr( sprintf( __( 'Quick view: %s', 'monkeydesign-product-carousel' ), $product->get_name() ) ); ?>">
			<?php echo esc_html( $settings['qv_button_text'] ?? __( 'Quick view', 'monkeydesign-product-carousel' ) ); ?>
		</button>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<?php if ( $settings['show_title'] || $settings['show_rating'] || $settings['show_excerpt'] || $settings['show_price'] || $settings['show_countdown'] || $settings['show_stock'] || $settings['show_add_to_cart'] ) : ?>
	<div class="pc-card__body">

		<?php if ( $settings['show_title'] ) : ?>
		<h3 class="pc-card__title">
			<a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
		</h3>
		<?php endif; ?>

		<?php if ( $settings['show_rating'] && wc_review_ratings_enabled() ) : ?>
		<div class="pc-card__rating">
			<?php echo wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<?php endif; ?>

		<?php if ( $settings['show_excerpt'] ) : ?>
		<div class="pc-card__excerpt">
			<?php echo wp_kses_post( $product->get_short_description() ); ?>
		</div>
		<?php endif; ?>

		<?php if ( $settings['show_price'] ) : ?>
		<div class="pc-card__price">
			<?php echo wp_kses_post( $product->get_price_html() ); ?>
		</div>
		<?php endif; ?>

		<?php if ( $settings['show_countdown'] ) :
			$sale_to = $product->get_date_on_sale_to();
			if ( $sale_to && $product->is_on_sale() ) : ?>
		<div class="pc-countdown" data-expires="<?php echo esc_attr( $sale_to->getTimestamp() ); ?>">
			<span class="pc-countdown__label"><?php esc_html_e( 'Sale ends in', 'monkeydesign-product-carousel' ); ?></span>
			<div class="pc-countdown__timer">
				<span class="pc-countdown__part"><span class="pc-countdown__num" data-unit="d">--</span><span class="pc-countdown__unit"><?php esc_html_e( 'd', 'monkeydesign-product-carousel' ); ?></span></span>
				<span class="pc-countdown__part"><span class="pc-countdown__num" data-unit="h">--</span><span class="pc-countdown__unit"><?php esc_html_e( 'h', 'monkeydesign-product-carousel' ); ?></span></span>
				<span class="pc-countdown__part"><span class="pc-countdown__num" data-unit="m">--</span><span class="pc-countdown__unit"><?php esc_html_e( 'm', 'monkeydesign-product-carousel' ); ?></span></span>
				<span class="pc-countdown__part"><span class="pc-countdown__num" data-unit="s">--</span><span class="pc-countdown__unit"><?php esc_html_e( 's', 'monkeydesign-product-carousel' ); ?></span></span>
			</div>
		</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( $settings['show_stock'] ) : ?>
		<div class="pc-card__stock">
			<?php echo wp_kses_post( wc_get_stock_html( $product ) ); ?>
		</div>
		<?php endif; ?>

		<?php if ( $settings['show_add_to_cart'] ) : ?>
		<div class="pc-card__actions">
			<?php echo $this->get_add_to_cart_button( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<?php endif; ?>

	</div><!-- .pc-card__body -->
	<?php endif; ?>

</div><!-- .pc-card -->
