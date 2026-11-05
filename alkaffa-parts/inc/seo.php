<?php
/**
 * Search engine basics: page titles, meta descriptions, social sharing tags and business schema.
 * Steps aside automatically when a dedicated SEO plugin (Yoast, Rank Math, AIOSEO, SEOPress) is active.
 *
 * @package AlKaffaParts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether a dedicated SEO plugin is handling titles and meta tags.
 *
 * @return bool
 */
function akp_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

/**
 * "3rd & 4th Gen" style label for the Pajero models a product fits.
 *
 * @param int $product_id Product ID.
 * @return string
 */
function akp_fitment_label( $product_id ) {
	$terms = get_the_terms( $product_id, 'pajero_model' );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return '';
	}
	usort( $terms, fn( $a, $b ) => strcmp( $a->slug, $b->slug ) );
	$gens = array_map( fn( $t ) => trim( str_replace( array( 'Pajero', 'Gen' ), '', $t->name ) ), $terms );
	return implode( ' & ', $gens ) . ' Gen';
}

/**
 * Keyword-rich page titles.
 *
 * @param array $parts Title parts.
 * @return array
 */
function akp_document_title( $parts ) {
	if ( is_front_page() ) {
		$parts['title']   = __( 'Mitsubishi Pajero Spare Parts in UAE – New & Used', 'alkaffa-parts' );
		$parts['tagline'] = get_bloginfo( 'name' );
	} elseif ( function_exists( 'is_product' ) && is_product() ) {
		$fits = akp_fitment_label( get_queried_object_id() );
		/* translators: 1: product name, 2: generations, e.g. "3rd & 4th Gen" */
		$parts['title'] = $fits ? sprintf( __( '%1$s for Mitsubishi Pajero %2$s', 'alkaffa-parts' ), single_post_title( '', false ), $fits ) : sprintf( __( '%s for Mitsubishi Pajero', 'alkaffa-parts' ), single_post_title( '', false ) );
	} elseif ( is_tax( 'pajero_model' ) ) {
		$term = get_queried_object();
		/* translators: 1: model name, 2: years and chassis codes */
		$parts['title'] = sprintf( __( 'Mitsubishi %1$s Spare Parts UAE (%2$s)', 'alkaffa-parts' ), $term->name, $term->description );
	} elseif ( is_tax( 'product_cat' ) ) {
		/* translators: %s: category name */
		$parts['title'] = sprintf( __( 'Pajero %s – Mitsubishi Pajero Parts UAE', 'alkaffa-parts' ), single_term_title( '', false ) );
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$parts['title'] = __( 'Shop Mitsubishi Pajero Parts Online – UAE', 'alkaffa-parts' );
	}
	return $parts;
}

/**
 * Meta description for the current page, or an empty string.
 *
 * @return string
 */
function akp_meta_description() {
	$text = '';
	if ( is_front_page() ) {
		$text = __( 'Mitsubishi Pajero spare parts in UAE for the 3rd and 4th generation (2000 – 2019): new, OEM-quality and used body parts. Order on WhatsApp, cash on delivery.', 'alkaffa-parts' );
	} elseif ( function_exists( 'is_product' ) && is_product() ) {
		$product = wc_get_product( get_queried_object_id() );
		$text    = $product ? ( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ) : '';
	} elseif ( is_tax( 'pajero_model' ) ) {
		$term = get_queried_object();
		/* translators: 1: model name, 2: years and chassis codes */
		$text = sprintf( __( 'Spare parts for the Mitsubishi %1$s (%2$s) in UAE: brakes, engine, suspension, lights, body parts and more. Order on WhatsApp, cash on delivery.', 'alkaffa-parts' ), $term->name, $term->description );
	} elseif ( is_tax( 'product_cat' ) ) {
		/* translators: %s: category name */
		$text = sprintf( __( '%s for Mitsubishi Pajero 3rd and 4th generation in UAE. Fitment checked by VIN. Order on WhatsApp, cash on delivery.', 'alkaffa-parts' ), single_term_title( '', false ) );
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$text = __( 'Shop Mitsubishi Pajero spare parts online in UAE: brakes, filters, engine, suspension, lights and used body parts for the 3rd and 4th generation.', 'alkaffa-parts' );
	} elseif ( is_singular() ) {
		$text = get_the_excerpt();
	}
	$text = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $text ) ) );
	return mb_strlen( $text ) > 160 ? rtrim( mb_substr( $text, 0, 157 ) ) . '…' : $text;
}

/**
 * Meta description, Open Graph / Twitter tags and business schema.
 */
function akp_seo_head() {
	$desc  = akp_meta_description();
	$title = wp_get_document_title();
	$url   = is_front_page() ? home_url( '/' ) : ( is_singular() ? get_permalink() : ( is_tax() || is_category() ? get_term_link( get_queried_object() ) : '' ) );
	$image = akp_asset_url( 'models/pajero-gen-4.webp' );
	if ( is_singular() && has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( null, 'large' );
	}

	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:type" content="' . ( function_exists( 'is_product' ) && is_product() ? 'product' : 'website' ) . '">' . "\n";
	if ( $desc ) {
		echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
	if ( $url && ! is_wp_error( $url ) ) {
		echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	}
	echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";

	if ( is_front_page() ) {
		$schema = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'AutoPartsStore',
			'name'        => get_bloginfo( 'name' ),
			'description' => akp_meta_description(),
			'url'         => home_url( '/' ),
			'logo'        => akp_asset_url( 'logo-mark.svg' ),
			'image'       => $image,
			'telephone'   => preg_replace( '/[^\d+]/', '', akp_option( 'phone' ) ),
			'email'       => akp_option( 'email' ),
			'areaServed'  => array( '@type' => 'Country', 'name' => 'United Arab Emirates' ),
			'address'     => array_filter(
				array(
					'@type'           => 'PostalAddress',
					'streetAddress'   => akp_option( 'address' ) ? preg_replace( '/\s+/', ' ', akp_option( 'address' ) ) : null,
					'addressLocality' => 'Sharjah',
					'addressCountry'  => 'AE',
				)
			),
			'knowsAbout'  => array( 'Mitsubishi Pajero spare parts', 'Pajero used body parts', 'Mitsubishi Pajero 3rd generation', 'Mitsubishi Pajero 4th generation' ),
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	}
}

if ( ! akp_seo_plugin_active() ) {
	add_filter( 'document_title_parts', 'akp_document_title' );
	add_action( 'wp_head', 'akp_seo_head', 2 );
	// Jetpack (WordPress.com) prints its own Open Graph tags; ours carry the product photo and description.
	add_filter( 'jetpack_enable_open_graph', '__return_false' );
}
