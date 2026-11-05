<?php
/**
 * Al Kaffa Parts theme functions.
 *
 * @package AlKaffaParts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AKP_VERSION', '2.8.0' );

require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/models.php';
require get_template_directory() . '/inc/woocommerce.php';
require get_template_directory() . '/inc/setup-page.php';
require get_template_directory() . '/inc/seo.php';

/**
 * Theme supports and menus.
 */
function akp_setup() {
	load_theme_textdomain( 'alkaffa-parts', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 260,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus(
		array(
			'primary' => __( 'Main menu', 'alkaffa-parts' ),
			'footer'  => __( 'Footer menu', 'alkaffa-parts' ),
		)
	);
}
add_action( 'after_setup_theme', 'akp_setup' );

/**
 * Styles and scripts.
 */
function akp_enqueue_assets() {
	wp_enqueue_style(
		'akp-fonts',
		'https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600&family=Barlow+Condensed:wght@600;700&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'akp-style', get_stylesheet_uri(), array(), AKP_VERSION );
	wp_enqueue_script( 'akp-main', get_template_directory_uri() . '/assets/js/main.js', array(), AKP_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'akp_enqueue_assets' );

/**
 * Create default Pajero models, part categories and the Contact page on activation.
 */
function akp_on_theme_switch() {
	akp_install_defaults();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'akp_on_theme_switch' );

/**
 * Use the logo mark as the browser tab icon until a Site Icon is set.
 */
function akp_default_favicon() {
	if ( ! has_site_icon() ) {
		echo '<link rel="icon" type="image/svg+xml" href="' . esc_url( akp_asset_url( 'logo-mark.svg' ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'akp_default_favicon', 5 );
