<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
function mdpc_register_navigation_controls(  \Elementor\Widget_Base $widget  ) : void {
    // Navigation
    $widget->start_controls_section( 'section_navigation', [
        'label' => esc_html__( 'Navigation', 'monkeydesign-product-carousel' ),
        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
    ] );
    $widget->add_control( 'show_arrows', [
        'label'        => esc_html__( 'Arrows', 'monkeydesign-product-carousel' ),
        'type'         => \Elementor\Controls_Manager::SWITCHER,
        'default'      => 'yes',
        'return_value' => 'yes',
    ] );
    $widget->add_control( 'show_dots', [
        'label'        => esc_html__( 'Dots', 'monkeydesign-product-carousel' ),
        'type'         => \Elementor\Controls_Manager::SWITCHER,
        'default'      => 'yes',
        'return_value' => 'yes',
    ] );
    $widget->end_controls_section();
    // Autoplay
    $widget->start_controls_section( 'section_autoplay', [
        'label' => esc_html__( 'Autoplay', 'monkeydesign-product-carousel' ),
        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
    ] );
    $widget->add_control( 'autoplay', [
        'label'        => esc_html__( 'Autoplay', 'monkeydesign-product-carousel' ),
        'type'         => \Elementor\Controls_Manager::SWITCHER,
        'default'      => '',
        'return_value' => 'yes',
    ] );
    $widget->add_control( 'autoplay_delay', [
        'label'     => esc_html__( 'Delay (ms)', 'monkeydesign-product-carousel' ),
        'type'      => \Elementor\Controls_Manager::NUMBER,
        'default'   => 3000,
        'min'       => 500,
        'max'       => 10000,
        'step'      => 100,
        'condition' => [
            'autoplay' => 'yes',
        ],
    ] );
    $widget->add_control( 'autoplay_pause_hover', [
        'label'        => esc_html__( 'Pause on hover', 'monkeydesign-product-carousel' ),
        'type'         => \Elementor\Controls_Manager::SWITCHER,
        'default'      => 'yes',
        'return_value' => 'yes',
        'condition'    => [
            'autoplay' => 'yes',
        ],
    ] );
    $widget->add_control( 'loop', [
        'label'        => esc_html__( 'Infinite loop', 'monkeydesign-product-carousel' ),
        'type'         => \Elementor\Controls_Manager::SWITCHER,
        'default'      => '',
        'return_value' => 'yes',
    ] );
    $widget->add_control( 'speed', [
        'label'   => esc_html__( 'Transition speed (ms)', 'monkeydesign-product-carousel' ),
        'type'    => \Elementor\Controls_Manager::NUMBER,
        'default' => 400,
        'min'     => 100,
        'max'     => 2000,
        'step'    => 50,
    ] );
    $widget->end_controls_section();
}
