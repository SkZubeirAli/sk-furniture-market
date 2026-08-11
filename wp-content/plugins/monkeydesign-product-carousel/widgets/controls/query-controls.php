<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mdpc_register_query_controls( \Elementor\Widget_Base $widget ): void {

	$widget->start_controls_section( 'section_query', [
		'label' => esc_html__( 'Query', 'monkeydesign-product-carousel' ),
		'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
	] );

	$widget->add_control( 'source', [
		'label'   => esc_html__( 'Product source', 'monkeydesign-product-carousel' ),
		'type'    => \Elementor\Controls_Manager::SELECT,
		'default' => 'latest',
		'options' => [
			'latest'       => esc_html__( 'Latest products', 'monkeydesign-product-carousel' ),
			'featured'     => esc_html__( 'Featured products', 'monkeydesign-product-carousel' ),
			'on_sale'      => esc_html__( 'On sale', 'monkeydesign-product-carousel' ),
			'best_sellers' => esc_html__( 'Best sellers', 'monkeydesign-product-carousel' ),
			'category'     => esc_html__( 'By category', 'monkeydesign-product-carousel' ),
			'tag'          => esc_html__( 'By tag', 'monkeydesign-product-carousel' ),
			'manual'       => esc_html__( 'Manual selection', 'monkeydesign-product-carousel' ),
			'related'      => esc_html__( 'Related products', 'monkeydesign-product-carousel' ),
		],
	] );

	// Categories (shown only when source = category)
	$widget->add_control( 'categories', [
		'label'       => esc_html__( 'Categories', 'monkeydesign-product-carousel' ),
		'type'        => \Elementor\Controls_Manager::SELECT2,
		'multiple'    => true,
		'options'     => mdpc_get_taxonomy_options( 'product_cat' ),
		'condition'   => [ 'source' => 'category' ],
		'label_block' => true,
	] );

	// Tags (shown only when source = tag)
	$widget->add_control( 'tags', [
		'label'       => esc_html__( 'Tags', 'monkeydesign-product-carousel' ),
		'type'        => \Elementor\Controls_Manager::SELECT2,
		'multiple'    => true,
		'options'     => mdpc_get_taxonomy_options( 'product_tag' ),
		'condition'   => [ 'source' => 'tag' ],
		'label_block' => true,
	] );

	// Manual IDs (shown only when source = manual)
	$widget->add_control( 'manual_ids', [
		'label'       => esc_html__( 'Product IDs', 'monkeydesign-product-carousel' ),
		'description' => esc_html__( 'Comma-separated product IDs, e.g. 12, 45, 78', 'monkeydesign-product-carousel' ),
		'type'        => \Elementor\Controls_Manager::TEXT,
		'condition'   => [ 'source' => 'manual' ],
		'label_block' => true,
	] );

	$widget->add_control( 'limit', [
		'label'   => esc_html__( 'Number of products', 'monkeydesign-product-carousel' ),
		'type'    => \Elementor\Controls_Manager::NUMBER,
		'default' => 8,
		'min'     => 1,
		'max'     => 40,
	] );

	$widget->add_control( 'orderby', [
		'label'   => esc_html__( 'Order by', 'monkeydesign-product-carousel' ),
		'type'    => \Elementor\Controls_Manager::SELECT,
		'default' => 'date',
		'options' => [
			'date'        => esc_html__( 'Date', 'monkeydesign-product-carousel' ),
			'title'       => esc_html__( 'Title', 'monkeydesign-product-carousel' ),
			'price'       => esc_html__( 'Price', 'monkeydesign-product-carousel' ),
			'rand'        => esc_html__( 'Random', 'monkeydesign-product-carousel' ),
			'rating'      => esc_html__( 'Rating', 'monkeydesign-product-carousel' ),
			'menu_order'  => esc_html__( 'Menu order', 'monkeydesign-product-carousel' ),
		],
	] );

	$widget->add_control( 'order', [
		'label'   => esc_html__( 'Order', 'monkeydesign-product-carousel' ),
		'type'    => \Elementor\Controls_Manager::SELECT,
		'default' => 'DESC',
		'options' => [
			'DESC' => esc_html__( 'Descending', 'monkeydesign-product-carousel' ),
			'ASC'  => esc_html__( 'Ascending', 'monkeydesign-product-carousel' ),
		],
	] );

	$widget->add_control( 'hide_out_of_stock', [
		'label'        => esc_html__( 'Hide out of stock', 'monkeydesign-product-carousel' ),
		'type'         => \Elementor\Controls_Manager::SWITCHER,
		'default'      => '',
		'return_value' => 'yes',
	] );

	$widget->end_controls_section();
}

function mdpc_get_taxonomy_options( string $taxonomy ): array {
	$terms = get_terms( [ 'taxonomy' => $taxonomy, 'hide_empty' => false ] );

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return [];
	}

	$options = [];
	foreach ( $terms as $term ) {
		$options[ $term->term_id ] = $term->name;
	}

	return $options;
}
