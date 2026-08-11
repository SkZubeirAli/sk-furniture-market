<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MDPC_Card_Renderer {

	/**
	 * @param array{
	 *   show_image:       bool,
	 *   show_title:       bool,
	 *   show_price:       bool,
	 *   show_rating:      bool,
	 *   show_badge:       bool,
	 *   show_add_to_cart: bool,
	 *   show_excerpt:     bool,
	 *   show_stock:       bool,
	 *   image_size:       string,
	 *   is_first:         bool,
	 * } $settings
	 */
	public function render( WC_Product $product, array $settings = [] ): void {
		$settings = $this->parse_settings( $settings );

		$html = $this->build_card( $product, $settings );
		$html = apply_filters( 'mdpc_card_html', $html, $product, $settings );

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	private function build_card( WC_Product $product, array $settings ): string {
		ob_start();

		$theme_template = locate_template( 'product-carousel/card.php' );
		$template       = $theme_template ?: MDPC_PLUGIN_DIR . 'templates/card.php';
		$template       = apply_filters( 'mdpc_card_template', $template, $product, $settings );

		if ( file_exists( $template ) ) {
			include $template;
		}

		return ob_get_clean();
	}

	// ------------------------------------------------------------------
	// Template data helpers (called from card.php)
	// ------------------------------------------------------------------

	public function get_badge( WC_Product $product ): string {
		$badges = [];

		if ( ! $product->is_in_stock() ) {
			$badges[] = '<span class="pc-badge pc-badge--out-of-stock">'
				. esc_html__( 'Out of stock', 'monkeydesign-product-carousel' )
				. '</span>';
		}

		if ( $product->is_on_sale() ) {
			$badges[] = '<span class="pc-badge pc-badge--sale">'
				. esc_html__( 'Sale', 'monkeydesign-product-carousel' )
				. '</span>';
		}

		if ( $product->is_featured() ) {
			$badges[] = '<span class="pc-badge pc-badge--featured">'
				. esc_html__( 'Featured', 'monkeydesign-product-carousel' )
				. '</span>';
		}

		return implode( '', $badges );
	}

	public function get_add_to_cart_button( WC_Product $product ): string {
		if ( ! $product->is_in_stock() || $product->is_type( 'variable' ) || $product->is_type( 'grouped' ) ) {
			return sprintf(
				'<a href="%s" class="pc-card__btn pc-card__btn--view">%s</a>',
				esc_url( get_permalink( $product->get_id() ) ),
				esc_html__( 'View product', 'monkeydesign-product-carousel' )
			);
		}

		return sprintf(
			'<a href="%s" data-product_id="%d" data-product_sku="%s" class="pc-card__btn pc-card__btn--cart add_to_cart_button ajax_add_to_cart" rel="nofollow">%s</a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( $product->get_id() ),
			esc_attr( $product->get_sku() ),
			esc_html( $product->add_to_cart_text() )
		);
	}

	// ------------------------------------------------------------------
	// Helpers
	// ------------------------------------------------------------------

	private function parse_settings( array $settings ): array {
		return wp_parse_args( $settings, [
			'show_image'       => true,
			'show_title'       => true,
			'show_price'       => true,
			'show_rating'      => true,
			'show_badge'       => true,
			'show_add_to_cart' => true,
			'show_excerpt'     => false,
			'show_stock'       => false,
			'image_size'       => 'woocommerce_thumbnail',
			'is_first'         => false,
		] );
	}
}
