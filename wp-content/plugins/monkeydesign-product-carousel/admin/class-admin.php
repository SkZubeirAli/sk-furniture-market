<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MDPC_Admin {

	private string $page_hook = '';

	public function __construct() {
		add_action( 'admin_menu',            [ $this, 'register_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
	}

	public function register_menu(): void {
		$this->page_hook = (string) add_menu_page(
			__( 'Product Carousel Settings', 'monkeydesign-product-carousel' ),
			__( 'Product Carousel', 'monkeydesign-product-carousel' ),
			'manage_options',
			'mdpc-settings',
			[ $this, 'render_settings_page' ],
			'dashicons-slides',
			58
		);
	}

	public function enqueue_assets( string $hook ): void {
		if ( '' === $this->page_hook || $this->page_hook !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'mdpc-settings',
			MDPC_PLUGIN_URL . 'assets/css/settings.css',
			[],
			MDPC_VERSION
		);
	}

	public function render_settings_page(): void {
		$is_pro = mdpc_is_pro();

		require_once MDPC_PLUGIN_DIR . 'admin/views/settings-page.php';
	}
}
