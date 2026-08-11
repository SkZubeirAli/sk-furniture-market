<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MDPC_Ajax {

	public function __construct() {
		add_action( 'wp_ajax_mdpc_quick_view',        [ $this, 'quick_view' ] );
		add_action( 'wp_ajax_nopriv_mdpc_quick_view', [ $this, 'quick_view' ] );
	}

	public function quick_view(): void {
		check_ajax_referer( 'mdpc_quick_view', 'nonce' );

		$product_id = absint( $_POST['product_id'] ?? 0 );
		if ( ! $product_id ) {
			wp_send_json_error( [ 'message' => 'Invalid product.' ] );
		}

		$product = wc_get_product( $product_id );
		if ( ! $product || ! $product->is_visible() ) {
			wp_send_json_error( [ 'message' => 'Product not found.' ] );
		}

		$renderer    = new MDPC_Card_Renderer();
		$product_url = get_permalink( $product_id );

		ob_start();
		?>
		<div class="pc-qv__image-col">
			<?php echo $product->get_image( 'woocommerce_single', [ 'class' => 'pc-qv__image' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<div class="pc-qv__details-col">
			<h2 class="pc-qv__title">
				<a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
			</h2>

			<?php if ( wc_review_ratings_enabled() ) : ?>
			<div class="pc-qv__rating">
				<?php echo wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<?php endif; ?>

			<div class="pc-qv__price">
				<?php echo wp_kses_post( $product->get_price_html() ); ?>
			</div>

			<?php $excerpt = $product->get_short_description(); if ( $excerpt ) : ?>
			<div class="pc-qv__excerpt">
				<?php echo wp_kses_post( $excerpt ); ?>
			</div>
			<?php endif; ?>

			<div class="pc-qv__actions">
				<?php echo $renderer->get_add_to_cart_button( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<a href="<?php echo esc_url( $product_url ); ?>" class="pc-qv__full-link">
				<?php esc_html_e( 'View full details', 'monkeydesign-product-carousel' ); ?> &rarr;
			</a>
		</div>
		<?php
		$html = ob_get_clean();

		wp_send_json_success( [ 'html' => $html ] );
	}
}
