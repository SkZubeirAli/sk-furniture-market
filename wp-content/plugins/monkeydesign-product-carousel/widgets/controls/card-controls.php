<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
function mdpc_register_card_controls(  \Elementor\Widget_Base $widget  ) : void {
    $widget->start_controls_section( 'section_card', [
        'label' => esc_html__( 'Card elements', 'monkeydesign-product-carousel' ),
        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
    ] );
    $toggles = [
        'show_image'       => [
            'label'   => esc_html__( 'Image', 'monkeydesign-product-carousel' ),
            'default' => 'yes',
        ],
        'show_badge'       => [
            'label'   => esc_html__( 'Badge (sale / featured)', 'monkeydesign-product-carousel' ),
            'default' => 'yes',
        ],
        'show_title'       => [
            'label'   => esc_html__( 'Title', 'monkeydesign-product-carousel' ),
            'default' => 'yes',
        ],
        'show_rating'      => [
            'label'   => esc_html__( 'Star rating', 'monkeydesign-product-carousel' ),
            'default' => 'yes',
        ],
        'show_price'       => [
            'label'   => esc_html__( 'Price', 'monkeydesign-product-carousel' ),
            'default' => 'yes',
        ],
        'show_excerpt'     => [
            'label'   => esc_html__( 'Short description', 'monkeydesign-product-carousel' ),
            'default' => '',
        ],
        'show_stock'       => [
            'label'   => esc_html__( 'Stock status', 'monkeydesign-product-carousel' ),
            'default' => '',
        ],
        'show_add_to_cart' => [
            'label'   => esc_html__( 'Add to cart button', 'monkeydesign-product-carousel' ),
            'default' => 'yes',
        ],
    ];
    foreach ( $toggles as $key => $args ) {
        $widget->add_control( $key, [
            'label'        => $args['label'],
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'default'      => $args['default'],
            'return_value' => 'yes',
        ] );
    }
    $widget->add_control( 'image_size', [
        'label'   => esc_html__( 'Image size', 'monkeydesign-product-carousel' ),
        'type'    => \Elementor\Controls_Manager::SELECT,
        'default' => 'woocommerce_thumbnail',
        'options' => [
            'woocommerce_thumbnail' => esc_html__( 'WooCommerce thumbnail', 'monkeydesign-product-carousel' ),
            'woocommerce_single'    => esc_html__( 'WooCommerce single', 'monkeydesign-product-carousel' ),
            'large'                 => esc_html__( 'Large', 'monkeydesign-product-carousel' ),
            'full'                  => esc_html__( 'Full', 'monkeydesign-product-carousel' ),
        ],
    ] );
    $widget->end_controls_section();
}
