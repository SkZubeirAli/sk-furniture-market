<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
function mdpc_register_layout_controls(  \Elementor\Widget_Base $widget  ) : void {
    $widget->start_controls_section( 'section_layout', [
        'label' => esc_html__( 'Layout', 'monkeydesign-product-carousel' ),
        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
    ] );
    $skin_options = [
        'classic' => esc_html__( 'Classic', 'monkeydesign-product-carousel' ),
        'minimal' => esc_html__( 'Minimal', 'monkeydesign-product-carousel' ),
    ];
    $widget->add_control( 'skin', [
        'label'   => esc_html__( 'Skin', 'monkeydesign-product-carousel' ),
        'type'    => \Elementor\Controls_Manager::SELECT,
        'default' => 'classic',
        'options' => $skin_options,
    ] );
    $widget->add_responsive_control( 'columns', [
        'label'          => esc_html__( 'Columns', 'monkeydesign-product-carousel' ),
        'type'           => \Elementor\Controls_Manager::SLIDER,
        'default'        => [
            'size' => 3,
        ],
        'tablet_default' => [
            'size' => 2,
        ],
        'mobile_default' => [
            'size' => 1,
        ],
        'range'          => [
            'px' => [
                'min'  => 1,
                'max'  => 6,
                'step' => 1,
            ],
        ],
    ] );
    $widget->add_control( 'space_between', [
        'label'   => esc_html__( 'Gap between slides (px)', 'monkeydesign-product-carousel' ),
        'type'    => \Elementor\Controls_Manager::SLIDER,
        'default' => [
            'size' => 20,
        ],
        'range'   => [
            'px' => [
                'min'  => 0,
                'max'  => 60,
                'step' => 2,
            ],
        ],
    ] );
    $widget->add_control( 'center_mode', [
        'label'        => esc_html__( 'Center mode', 'monkeydesign-product-carousel' ),
        'description'  => esc_html__( 'Active slide is centered and adjacent slides are partially visible.', 'monkeydesign-product-carousel' ),
        'type'         => \Elementor\Controls_Manager::SWITCHER,
        'default'      => '',
        'return_value' => 'yes',
    ] );
    $widget->end_controls_section();
}
