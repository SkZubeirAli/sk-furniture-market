<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( !function_exists( 'mdpc_fs' ) ) {
    function mdpc_fs() : ?Freemius {
        global $mdpc_fs;
        if ( !isset( $mdpc_fs ) ) {
            require_once MDPC_PLUGIN_DIR . 'vendor/freemius/start.php';
            $mdpc_fs = fs_dynamic_init( array(
                'id'               => '32205',
                'slug'             => 'monkeydesign-product-carousel',
                'premium_slug'     => 'monkeydesign-product-carousel-pro',
                'type'             => 'plugin',
                'public_key'       => 'pk_bfb883cabce4ddf9645852a07f1c2',
                'is_premium'       => false,
                'premium_suffix'   => 'PRO',
                'has_addons'       => false,
                'has_paid_plans'   => true,
                'is_org_compliant' => true,
                'menu'             => array(
                    'slug'    => 'mdpc-settings',
                    'account' => true,
                    'contact' => false,
                    'support' => false,
                    'pricing' => true,
                    'addons'  => false,
                ),
                'is_live'          => true,
            ) );
        }
        return $mdpc_fs;
    }

    mdpc_fs();
    do_action( 'mdpc_fs_loaded' );
}
/**
 * Returns true if the active install can use premium code.
 * For local testing: add_filter( 'mdpc_is_pro', '__return_true' );
 */
function mdpc_is_pro() : bool {
    if ( apply_filters( 'mdpc_is_pro', false ) ) {
        return true;
    }
    $fs = mdpc_fs();
    return $fs instanceof Freemius && $fs->can_use_premium_code();
}

/**
 * 2026-07-21 test result: a custom-named wrapper (mdpc_is_pro__premium_only())
 * is NOT recognized by Freemius' deployment code-stripping preprocessor — a
 * real test deployment confirmed the guarded block still shipped in the
 * generated FREE zip. The preprocessor only recognizes the SDK's own built-in
 * __premium_only methods (see vendor/freemius/includes/class-freemius-abstract.php),
 * called directly on the $fs instance — e.g. mdpc_fs()->can_use_premium_code__premium_only().
 * That is now the pattern used in widgets/controls/layout-controls.php as the
 * (second, currently being re-tested) isolated test case.
 */