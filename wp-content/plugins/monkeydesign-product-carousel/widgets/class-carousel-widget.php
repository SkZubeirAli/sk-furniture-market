<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MDPC_Carousel_Widget extends \Elementor\Widget_Base {

	public function get_name(): string {
		return 'mdpc_carousel';
	}

	public function get_title(): string {
		return esc_html__( 'Product Carousel', 'monkeydesign-product-carousel' );
	}

	public function get_icon(): string {
		return 'eicon-carousel';
	}

	public function get_categories(): array {
		return [ 'monkeydesign' ];
	}

	public function get_keywords(): array {
		return [ 'carousel', 'slider', 'product', 'woocommerce' ];
	}

	public function get_style_depends(): array {
		return [ 'mdpc-carousel' ];
	}

	public function get_script_depends(): array {
		return [ 'mdpc-carousel', 'mdpc-editor' ];
	}

	protected function register_controls(): void {
		require_once MDPC_PLUGIN_DIR . 'widgets/controls/query-controls.php';
		require_once MDPC_PLUGIN_DIR . 'widgets/controls/layout-controls.php';
		require_once MDPC_PLUGIN_DIR . 'widgets/controls/card-controls.php';
		require_once MDPC_PLUGIN_DIR . 'widgets/controls/navigation-controls.php';

		mdpc_register_query_controls( $this );
		mdpc_register_layout_controls( $this );
		mdpc_register_card_controls( $this );
		mdpc_register_navigation_controls( $this );
	}

	protected function render(): void {
		$s = $this->get_settings_for_display();

		// --- Query ---
		$manual_ids = [];
		if ( ! empty( $s['manual_ids'] ) ) {
			$manual_ids = array_filter( array_map( 'intval', explode( ',', $s['manual_ids'] ) ) );
		}

		$products = ( new MDPC_Query_Engine() )->get_products( [
			'source'            => $s['source']            ?? 'latest',
			'categories'        => $s['categories']        ?? [],
			'tags'              => $s['tags']              ?? [],
			'manual_ids'        => $manual_ids,
			'limit'             => (int) ( $s['limit']     ?? 8 ),
			'orderby'           => $s['orderby']           ?? 'date',
			'order'             => $s['order']             ?? 'DESC',
			'hide_out_of_stock' => 'yes' === ( $s['hide_out_of_stock'] ?? '' ),
		] );

		if ( empty( $products ) ) {
			echo '<p class="pc-no-products">' . esc_html__( 'No products found.', 'monkeydesign-product-carousel' ) . '</p>';
			return;
		}

		// --- Config ---
		$show_arrows      = 'yes' === ( $s['show_arrows']    ?? 'yes' );
		$show_dots        = 'yes' === ( $s['show_dots']      ?? 'yes' );
		$ajax_cart        = mdpc_fs()->can_use_premium_code__premium_only() && 'yes' === ( $s['ajax_cart_enhanced'] ?? 'yes' );
		$show_thumbs    = mdpc_fs()->can_use_premium_code__premium_only() && 'yes' === ( $s['show_thumbs']    ?? '' );
		$show_progress  = mdpc_fs()->can_use_premium_code__premium_only() && 'yes' === ( $s['show_progress']  ?? '' );
		$show_fraction  = mdpc_fs()->can_use_premium_code__premium_only() && 'yes' === ( $s['show_fraction']  ?? '' );
		$show_scrollbar = mdpc_fs()->can_use_premium_code__premium_only() && 'yes' === ( $s['show_scrollbar'] ?? '' );
		$skin           = sanitize_html_class( $s['skin'] ?? 'classic' );

		$toast_text = trim( (string) ( $s['ajax_toast_text'] ?? '' ) );
		if ( '' === $toast_text ) {
			$toast_text = __( '✓ Added to cart', 'monkeydesign-product-carousel' );
		}

		$qv_button_text = trim( (string) ( $s['qv_button_text'] ?? '' ) );
		if ( '' === $qv_button_text ) {
			$qv_button_text = __( 'Quick view', 'monkeydesign-product-carousel' );
		}

		// Toast position: normalize against the current option set. Widgets
		// saved before the "widget vs. page-relative" redesign may still have
		// one of the old keys stored (bottom-center/-left/-right, top-center/
		// -left/-right) — remap those to their closest new equivalent instead
		// of silently falling through to an unstyled, unpositioned element.
		$toast_position_raw   = sanitize_key( $s['ajax_toast_position'] ?? 'widget' );
		$toast_position_legacy = [
			'bottom-center' => 'page-bottom-center',
			'bottom-left'   => 'page-bottom-left',
			'bottom-right'  => 'page-bottom-right',
			'top-center'    => 'page-top-center',
			'top-left'      => 'page-top-left',
			'top-right'     => 'page-top-right',
		];
		$toast_position_valid = [ 'widget', 'page-bottom-center', 'page-bottom-left', 'page-bottom-right', 'page-top-center', 'page-top-left', 'page-top-right' ];
		if ( isset( $toast_position_legacy[ $toast_position_raw ] ) ) {
			$toast_position = $toast_position_legacy[ $toast_position_raw ];
		} elseif ( in_array( $toast_position_raw, $toast_position_valid, true ) ) {
			$toast_position = $toast_position_raw;
		} else {
			$toast_position = 'widget';
		}

		$swiper_config = wp_json_encode( [
			'columns' => [
				'desktop' => (int) ( $s['columns']['size']        ?? 3 ),
				'tablet'  => (int) ( $s['columns_tablet']['size'] ?? 2 ),
				'mobile'  => (int) ( $s['columns_mobile']['size'] ?? 1 ),
			],
			'space_between'   => (int) ( $s['space_between']['size'] ?? 20 ),
			'arrows'          => $show_arrows,
			'dots'            => $show_dots,
			'thumbs'          => $show_thumbs,
			'thumbs_gap'      => $show_thumbs ? (int) ( $s['thumbs_gap']['size'] ?? 8 ) : 0,
			'autoplay'        => 'yes' === ( $s['autoplay'] ?? '' ),
			'delay'           => (int) ( $s['autoplay_delay'] ?? 3000 ),
			'pause_on_hover'  => 'yes' === ( $s['autoplay_pause_hover'] ?? 'yes' ),
			'progress'        => $show_progress,
			'fraction'        => $show_fraction,
			'scrollbar'       => $show_scrollbar,
			'sync_id'         => sanitize_key( $s['sync_id'] ?? '' ),
			'loop'            => 'yes' === ( $s['loop'] ?? '' ),
			'speed'           => (int) ( $s['speed'] ?? 400 ),
			'center_mode'     => 'yes' === ( $s['center_mode'] ?? '' ),
			'ajax_cart'       => $ajax_cart,
			'toast_text'      => $ajax_cart ? sanitize_text_field( $toast_text ) : '',
			'toast_position'  => $toast_position,
			'toast_offset_x'  => (int) ( $s['ajax_toast_offset_x'] ?? 20 ),
			'toast_offset_y'  => (int) ( $s['ajax_toast_offset_y'] ?? 20 ),
		] );

		// --- Card settings ---
		$card_settings = [
			'show_image'       => 'yes' === ( $s['show_image']       ?? 'yes' ),
			'show_title'       => 'yes' === ( $s['show_title']       ?? 'yes' ),
			'show_price'       => 'yes' === ( $s['show_price']       ?? 'yes' ),
			'show_rating'      => 'yes' === ( $s['show_rating']      ?? 'yes' ),
			'show_badge'       => 'yes' === ( $s['show_badge']       ?? 'yes' ),
			'show_add_to_cart' => 'yes' === ( $s['show_add_to_cart'] ?? 'yes' ),
			'show_excerpt'     => 'yes' === ( $s['show_excerpt']     ?? '' ),
			'show_stock'       => 'yes' === ( $s['show_stock']       ?? '' ),
			'show_wishlist'    => mdpc_fs()->can_use_premium_code__premium_only() && 'yes' === ( $s['show_wishlist']    ?? '' ),
			'show_quick_view'  => mdpc_fs()->can_use_premium_code__premium_only() && 'yes' === ( $s['show_quick_view'] ?? '' ),
			'show_countdown'   => mdpc_fs()->can_use_premium_code__premium_only() && 'yes' === ( $s['show_countdown']  ?? '' ),
			'image_size'       => $s['image_size'] ?? 'woocommerce_thumbnail',
			'qv_button_text'   => $qv_button_text,
		];

		$renderer = new MDPC_Card_Renderer();
		?>
		<div class="pc-carousel pc-carousel--skin-<?php echo esc_attr( $skin ); ?>"
		     data-swiper-config="<?php echo esc_attr( $swiper_config ); ?>">

			<div class="pc-carousel-wrapper">
				<div class="swiper">
					<div class="swiper-wrapper">
						<?php foreach ( $products as $index => $product ) : ?>
						<div class="swiper-slide">
							<?php $renderer->render( $product, array_merge( $card_settings, [ 'is_first' => 0 === $index ] ) ); ?>
						</div>
						<?php endforeach; ?>
					</div>
					<?php if ( $show_dots ) : ?>
					<div class="pc-carousel__dots"></div>
					<?php endif; ?>
				</div>

				<?php if ( $show_arrows ) : ?>
				<button class="pc-carousel__prev" aria-label="<?php esc_attr_e( 'Previous slide', 'monkeydesign-product-carousel' ); ?>">
					<?php if ( mdpc_fs()->can_use_premium_code__premium_only() && ! empty( $s['arrow_prev_icon']['value'] ) ) :
						\Elementor\Icons_Manager::render_icon( $s['arrow_prev_icon'], [ 'aria-hidden' => 'true' ] );
					else : ?>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"></polyline></svg>
					<?php endif; ?>
				</button>
				<button class="pc-carousel__next" aria-label="<?php esc_attr_e( 'Next slide', 'monkeydesign-product-carousel' ); ?>">
					<?php if ( mdpc_fs()->can_use_premium_code__premium_only() && ! empty( $s['arrow_next_icon']['value'] ) ) :
						\Elementor\Icons_Manager::render_icon( $s['arrow_next_icon'], [ 'aria-hidden' => 'true' ] );
					else : ?>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"></polyline></svg>
					<?php endif; ?>
				</button>
				<?php endif; ?>

			</div>

			<?php if ( $show_progress ) : ?>
			<div class="pc-carousel__progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
				<div class="pc-carousel__progress-bar"></div>
			</div>
			<?php endif; ?>

			<?php if ( $show_fraction ) : ?>
			<div class="pc-carousel__fraction" aria-live="polite">
				<span class="pc-carousel__fraction-current">1</span>
				<span class="pc-carousel__fraction-sep">/</span>
				<span class="pc-carousel__fraction-total">1</span>
			</div>
			<?php endif; ?>

			<?php if ( $show_scrollbar ) : ?>
			<div class="pc-carousel__scrollbar"></div>
			<?php endif; ?>

			<?php if ( $show_thumbs ) : ?>
			<div class="swiper pc-carousel__thumbs-swiper">
				<div class="swiper-wrapper">
					<?php foreach ( $products as $index => $product ) :
						$thumb_id  = $product->get_image_id();
						$thumb_url = $thumb_id
							? wp_get_attachment_image_url( $thumb_id, 'thumbnail' )
							: wc_placeholder_img_src( 'thumbnail' );
					?>
					<div class="swiper-slide"
					     aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'monkeydesign-product-carousel' ), $index + 1 ) ); ?>">
						<img src="<?php echo esc_url( $thumb_url ); ?>"
						     alt="<?php echo esc_attr( $product->get_name() ); ?>"
						     loading="lazy">
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>

		</div>

		<?php
	}
}
