<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MDPC_Query_Engine {

	/**
	 * @param array{
	 *   source:            string,
	 *   categories:        int[],
	 *   tags:              int[],
	 *   manual_ids:        int[],
	 *   limit:             int,
	 *   orderby:           string,
	 *   order:             string,
	 *   hide_out_of_stock: bool,
	 * } $args
	 * @return WC_Product[]
	 */
	public function get_products( array $args ): array {
		$args = $this->parse_args( $args );

		$ids = match ( $args['source'] ) {
			'latest'       => $this->query_latest( $args ),
			'featured'     => $this->query_featured( $args ),
			'on_sale'      => $this->query_on_sale( $args ),
			'best_sellers' => $this->query_best_sellers( $args ),
			'category'     => $this->query_by_taxonomy( 'product_cat', $args ),
			'tag'          => $this->query_by_taxonomy( 'product_tag', $args ),
			'manual'       => $this->query_manual( $args ),
			'related'      => $this->query_related( $args ),
			default        => [],
		};

		return $this->ids_to_products( $ids );
	}

	// ------------------------------------------------------------------
	// Sources
	// ------------------------------------------------------------------

	private function query_latest( array $args ): array {
		return $this->run_query( [
			'orderby' => $args['orderby'] ?: 'date',
			'order'   => $args['order']   ?: 'DESC',
		], $args );
	}

	private function query_featured( array $args ): array {
		return $this->run_query( [
			'tax_query' => [ [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
			] ],
		], $args );
	}

	private function query_on_sale( array $args ): array {
		$sale_ids = wc_get_product_ids_on_sale();

		if ( empty( $sale_ids ) ) {
			return [];
		}

		return $this->run_query( [
			'post__in' => $sale_ids,
		], $args );
	}

	private function query_best_sellers( array $args ): array {
		$cache_key     = 'mdpc_best_sellers_' . md5( serialize( $args ) );
		$cache_seconds = (int) apply_filters( 'mdpc_best_sellers_cache_seconds', HOUR_IN_SECONDS );
		$cached        = get_transient( $cache_key );

		if ( false !== $cached ) {
			return $cached;
		}

		$ids = $this->run_query( [
			'meta_key' => 'total_sales', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'orderby'  => 'meta_value_num',
			'order'    => 'DESC',
		], $args );

		set_transient( $cache_key, $ids, $cache_seconds );

		return $ids;
	}

	private function query_by_taxonomy( string $taxonomy, array $args ): array {
		$terms = 'product_cat' === $taxonomy ? $args['categories'] : $args['tags'];

		if ( empty( $terms ) ) {
			return [];
		}

		return $this->run_query( [
			'tax_query' => [ [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				'taxonomy' => $taxonomy,
				'field'    => 'term_id',
				'terms'    => array_map( 'intval', $terms ),
			] ],
		], $args );
	}

	private function query_manual( array $args ): array {
		if ( empty( $args['manual_ids'] ) ) {
			return [];
		}

		$ids = array_map( 'intval', $args['manual_ids'] );

		return $this->run_query( [
			'post__in' => $ids,
			'orderby'  => 'post__in',
		], $args );
	}

	private function query_related( array $args ): array {
		$product_id = $args['related_to'] ?? 0;

		if ( ! $product_id ) {
			return [];
		}

		$related = wc_get_related_products( $product_id, $args['limit'] );

		if ( empty( $related ) ) {
			return [];
		}

		return $this->run_query( [
			'post__in' => $related,
			'orderby'  => 'post__in',
		], $args );
	}

	// ------------------------------------------------------------------
	// Core query builder
	// ------------------------------------------------------------------

	private function run_query( array $extra, array $args ): array {
		$query_args = array_merge( [
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $args['limit'],
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'orderby'        => $args['orderby'] ?: 'date',
			'order'          => $args['order']   ?: 'DESC',
		], $extra );

		if ( $args['hide_out_of_stock'] ) {
			$stock_query = [
				'relation' => 'OR',
				[ 'key' => '_stock_status', 'value' => 'instock' ],
				[ 'key' => '_stock_status', 'value' => 'onbackorder' ],
			];

			if ( isset( $query_args['meta_query'] ) ) {
				$query_args['meta_query'][] = $stock_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			} else {
				$query_args['meta_query'] = [ $stock_query ]; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			}
		}

		$query = new WP_Query( apply_filters( 'mdpc_query_args', $query_args, $args ) );

		return $query->posts;
	}

	// ------------------------------------------------------------------
	// Helpers
	// ------------------------------------------------------------------

	private function ids_to_products( array $ids ): array {
		$products = [];

		foreach ( $ids as $id ) {
			$product = wc_get_product( $id );

			if ( $product instanceof WC_Product ) {
				$products[] = $product;
			}
		}

		return $products;
	}

	private function parse_args( array $args ): array {
		return wp_parse_args( $args, [
			'source'            => 'latest',
			'categories'        => [],
			'tags'              => [],
			'manual_ids'        => [],
			'related_to'        => 0,
			'limit'             => 8,
			'orderby'           => 'date',
			'order'             => 'DESC',
			'hide_out_of_stock' => false,
		] );
	}
}
