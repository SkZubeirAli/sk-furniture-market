<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class MDPC_Assets {
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [$this, 'register_frontend'] );
        add_action( 'elementor/editor/before_enqueue_scripts', [$this, 'register_frontend'] );
    }

    public function register_frontend() : void {
        wp_register_style(
            'mdpc-carousel',
            MDPC_PLUGIN_URL . 'assets/css/carousel.css',
            ['swiper'],
            MDPC_VERSION
        );
        wp_register_script(
            'mdpc-carousel',
            MDPC_PLUGIN_URL . 'assets/js/carousel.js',
            ['swiper'],
            MDPC_VERSION,
            true
        );
        wp_register_script(
            'mdpc-editor',
            MDPC_PLUGIN_URL . 'assets/js/editor.js',
            ['elementor-frontend', 'mdpc-carousel'],
            MDPC_VERSION,
            true
        );
    }

}
