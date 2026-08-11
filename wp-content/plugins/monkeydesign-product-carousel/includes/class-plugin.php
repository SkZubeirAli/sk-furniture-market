<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class MDPC_Plugin {
    private static ?MDPC_Plugin $instance = null;

    public static function instance() : self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init_hooks();
    }

    private function init_hooks() : void {
        require_once MDPC_PLUGIN_DIR . 'includes/class-query-engine.php';
        require_once MDPC_PLUGIN_DIR . 'includes/class-card-renderer.php';
        require_once MDPC_PLUGIN_DIR . 'includes/class-assets.php';
        require_once MDPC_PLUGIN_DIR . 'includes/class-ajax.php';
        new MDPC_Assets();
        add_action( 'elementor/elements/categories_registered', [$this, 'register_category'] );
        add_action( 'elementor/widgets/register', [$this, 'register_widgets'] );
        $this->init_admin();
    }

    public function register_category( \Elementor\Elements_Manager $manager ) : void {
        $manager->add_category( 'monkeydesign', [
            'title' => '🐒 MonkeyDesign',
            'icon'  => 'fa fa-plug',
        ] );
    }

    private function init_admin() : void {
        if ( !is_admin() ) {
            return;
        }
        require_once MDPC_PLUGIN_DIR . 'admin/class-admin.php';
        new MDPC_Admin();
    }

    public function register_widgets( \Elementor\Widgets_Manager $manager ) : void {
        require_once MDPC_PLUGIN_DIR . 'widgets/class-carousel-widget.php';
        $manager->register( new MDPC_Carousel_Widget() );
    }

}
