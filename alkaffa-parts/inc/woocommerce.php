<?php
/**
 * WooCommerce integration: OEM part numbers, fitment display, part-number search, CSV import.
 *
 * @package AlKaffaParts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

add_filter( 'loop_shop_per_page', fn() => 16 );
add_filter( 'loop_shop_columns', fn() => 4 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
add_filter( 'woocommerce_show_page_title', '__return_false' );

/**
 * "OEM part number" field under SKU in the product editor.
 */
function akp_oem_field() {
	woocommerce_wp_text_input(
		array(
			'id'          => '_akp_oem_number',
			'label'       => __( 'OEM part number', 'alkaffa-parts' ),
			'desc_tip'    => true,
			'description' => __( 'Mitsubishi original part number. Customers can search the shop by this number.', 'alkaffa-parts' ),
		)
	);
}
add_action( 'woocommerce_product_options_sku', 'akp_oem_field' );

/**
 * Save the OEM part number.
 *
 * @param WC_Product $product Product being saved.
 */
function akp_save_oem_field( $product ) {
	if ( isset( $_POST['_akp_oem_number'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- WooCommerce verifies the nonce.
		$product->update_meta_data( '_akp_oem_number', wc_clean( wp_unslash( $_POST['_akp_oem_number'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
	}
}
add_action( 'woocommerce_admin_process_product_object', 'akp_save_oem_field' );

/**
 * Names of the Pajero models a product fits, linked to their archive.
 *
 * @param int $product_id Product ID.
 * @return string HTML chips, or empty string.
 */
function akp_fitment_chips( $product_id ) {
	$terms = get_the_terms( $product_id, 'pajero_model' );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return '';
	}
	usort( $terms, fn( $a, $b ) => strcmp( $a->slug, $b->slug ) );
	$out = '';
	foreach ( $terms as $term ) {
		$out .= '<a class="chip" href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
	}
	return $out;
}

/**
 * Illustration for a product's part category, used until the owner uploads a real photo.
 *
 * @param int $product_id Product ID.
 * @return string Image URL.
 */
function akp_part_illustration_url( $product_id ) {
	$slug  = 'default';
	$terms = get_the_terms( $product_id, 'product_cat' );
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$ancestors = get_ancestors( $term->term_id, 'product_cat' );
			$top       = $ancestors ? get_term( (int) end( $ancestors ), 'product_cat' ) : $term;
			if ( $top && ! is_wp_error( $top ) && file_exists( get_template_directory() . '/assets/images/parts/' . $top->slug . '.svg' ) ) {
				$slug = $top->slug;
				break;
			}
		}
	}
	return akp_asset_url( 'parts/' . $slug . '.svg' );
}

/**
 * Shop grids, cart and widgets: show the part illustration instead of the grey placeholder.
 *
 * @param string     $html    Image HTML.
 * @param WC_Product $product Product.
 * @param string     $size    Image size.
 * @param array      $attr    Attributes.
 * @param bool       $placeholder Whether a placeholder is allowed.
 * @return string
 */
function akp_product_image_fallback( $html, $product, $size, $attr, $placeholder ) {
	if ( $product->get_image_id() || ! $placeholder ) {
		return $html;
	}
	$id = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
	return '<img src="' . esc_url( akp_part_illustration_url( $id ) ) . '" class="woocommerce-placeholder wp-post-image akp-illustration" alt="' . esc_attr( $product->get_name() ) . '" width="600" height="600" loading="lazy" decoding="async">';
}
add_filter( 'woocommerce_product_get_image', 'akp_product_image_fallback', 10, 5 );

/**
 * Single product page: illustration instead of the placeholder when there is no photo.
 *
 * @param string $html Gallery image HTML.
 * @param int    $attachment_id Attachment ID (0 when none).
 * @return string
 */
function akp_single_image_fallback( $html, $attachment_id ) {
	global $product;
	if ( $attachment_id || ! $product instanceof WC_Product ) {
		return $html;
	}
	return '<div class="woocommerce-product-gallery__image--placeholder"><img src="' . esc_url( akp_part_illustration_url( $product->get_id() ) ) . '" alt="' . esc_attr( $product->get_name() ) . '" class="wp-post-image akp-illustration" width="600" height="600"></div>';
}
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'akp_single_image_fallback', 10, 2 );

/**
 * Fitment, part numbers and a WhatsApp enquiry button on the single product page.
 */
function akp_single_product_fitment() {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$chips = akp_fitment_chips( $product->get_id() );
	$sku   = $product->get_sku();
	$oem   = $product->get_meta( '_akp_oem_number' );
	$wa    = akp_whatsapp_url(
		/* translators: 1: product name, 2: product URL */
		sprintf( __( 'Hello, does this part fit my Pajero? %1$s (%2$s) My VIN is: ', 'alkaffa-parts' ), $product->get_name(), get_permalink( $product->get_id() ) )
	);
	?>
	<div class="fitment-box">
		<?php if ( $chips ) : ?>
			<div class="fitment-box__row"><span class="fitment-box__label"><?php esc_html_e( 'Fits', 'alkaffa-parts' ); ?></span><span class="chips"><?php echo $chips; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in akp_fitment_chips(). ?></span></div>
		<?php endif; ?>
		<?php if ( $sku ) : ?>
			<div class="fitment-box__row"><span class="fitment-box__label"><?php esc_html_e( 'Part no.', 'alkaffa-parts' ); ?></span><code><?php echo esc_html( $sku ); ?></code></div>
		<?php endif; ?>
		<?php if ( $oem ) : ?>
			<div class="fitment-box__row"><span class="fitment-box__label"><?php esc_html_e( 'OEM no.', 'alkaffa-parts' ); ?></span><code><?php echo esc_html( $oem ); ?></code></div>
		<?php endif; ?>
		<p class="fitment-box__note"><?php esc_html_e( 'Unsure if it fits? Send us your VIN and we will confirm before you buy.', 'alkaffa-parts' ); ?></p>
		<?php if ( $wa ) : ?>
			<a class="btn btn--ghost" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Check fitment on WhatsApp', 'alkaffa-parts' ); ?></a>
		<?php endif; ?>
	</div>
	<?php
}
add_action( 'woocommerce_single_product_summary', 'akp_single_product_fitment', 25 );

/**
 * Big "Order on WhatsApp" button right under the price on the single product page.
 */
function akp_single_whatsapp_order() {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$wa = akp_whatsapp_order_url( $product );
	if ( ! $wa ) {
		return;
	}
	?>
	<div class="wa-order-box">
		<a class="btn btn--whatsapp btn--lg" href="<?php echo esc_attr( $wa ) /* esc_url() would strip the %0A line breaks; built from a fixed wa.me URL. */; ?>" target="_blank" rel="noopener"><?php echo akp_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static markup. ?><?php esc_html_e( 'Order on WhatsApp', 'alkaffa-parts' ); ?></a>
		<span><?php esc_html_e( 'Fastest way to order · We confirm price & fitment · Cash on delivery', 'alkaffa-parts' ); ?></span>
	</div>
	<?php
}
add_action( 'woocommerce_single_product_summary', 'akp_single_whatsapp_order', 15 );

/**
 * "Order on WhatsApp" button on each product card in shop grids.
 */
function akp_loop_whatsapp_order() {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$wa = akp_whatsapp_order_url( $product );
	if ( $wa ) {
		echo '<a class="button loop-wa" href="' . esc_attr( $wa ) . '" target="_blank" rel="noopener">' . akp_whatsapp_icon() . esc_html__( 'Order on WhatsApp', 'alkaffa-parts' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput -- static icon markup.
	}
}
add_action( 'woocommerce_after_shop_loop_item', 'akp_loop_whatsapp_order', 9 );

/**
 * "Send this order on WhatsApp" button for the cart. main.js fills in the cart contents on click.
 *
 * @return string
 */
function akp_cart_whatsapp_button() {
	$wa = akp_whatsapp_url( __( 'Hello Al Kaffa Parts, I want to order the parts in my cart.', 'alkaffa-parts' ) );
	if ( ! $wa ) {
		return '';
	}
	return '<div class="cart-wa">'
		. '<a class="btn btn--whatsapp btn--lg" href="' . esc_url( $wa ) . '" target="_blank" rel="noopener" data-wa-cart="' . esc_attr( preg_replace( '/\D/', '', akp_option( 'whatsapp' ) ) ) . '" data-cart-api="' . esc_url( rest_url( 'wc/store/v1/cart' ) ) . '">'
		. akp_whatsapp_icon() . esc_html__( 'Send this order on WhatsApp', 'alkaffa-parts' ) . '</a>'
		. '<p>' . esc_html__( 'No online payment needed. We confirm stock and delivery on WhatsApp, and you pay cash on delivery.', 'alkaffa-parts' ) . '</p>'
		. '</div>';
}

add_filter(
	'render_block_woocommerce/cart',
	function ( $html ) {
		return WC()->cart && ! WC()->cart->is_empty() ? $html . akp_cart_whatsapp_button() : $html;
	}
);
add_action(
	'woocommerce_proceed_to_checkout',
	function () {
		echo akp_cart_whatsapp_button(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in akp_cart_whatsapp_button().
	},
	30
);

/**
 * Short "Fits:" line under each product title in shop grids.
 */
function akp_loop_fitment() {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$terms = get_the_terms( $product->get_id(), 'pajero_model' );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return;
	}
	usort( $terms, fn( $a, $b ) => strcmp( $a->slug, $b->slug ) );
	$names = array_map( fn( $t ) => str_replace( 'Pajero ', '', $t->name ), $terms );
	echo '<p class="loop-fits">' . esc_html__( 'Fits:', 'alkaffa-parts' ) . ' ' . esc_html( implode( ', ', $names ) ) . '</p>';
}
add_action( 'woocommerce_after_shop_loop_item_title', 'akp_loop_fitment', 4 );

/**
 * Keep the header cart count in sync after AJAX add-to-cart.
 *
 * @param array $fragments Fragments keyed by selector.
 * @return array
 */
function akp_cart_fragment( $fragments ) {
	$fragments['span.cart-count'] = '<span class="cart-count">' . (int) WC()->cart->get_cart_contents_count() . '</span>';
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'akp_cart_fragment' );

/**
 * If a search term exactly matches a part number (SKU) or OEM number, go straight to that product.
 */
function akp_part_number_search() {
	if ( ! is_search() ) {
		return;
	}
	$term = trim( get_search_query( false ) );
	if ( '' === $term ) {
		return;
	}

	$id = wc_get_product_id_by_sku( $term );
	if ( ! $id ) {
		$ids = get_posts(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'fields'         => 'ids',
				'posts_per_page' => 2,
				'meta_key'       => '_akp_oem_number', // phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_value'     => $term, // phpcs:ignore WordPress.DB.SlowDBQuery
			)
		);
		$id = 1 === count( $ids ) ? $ids[0] : 0;
	}
	if ( ! $id ) {
		return;
	}

	$parent = wp_get_post_parent_id( $id );
	$target = $parent ? $parent : $id;
	if ( 'publish' === get_post_status( $target ) ) {
		wp_safe_redirect( get_permalink( $target ) );
		exit;
	}
}
add_action( 'template_redirect', 'akp_part_number_search' );

/**
 * Model filter chips shown above shop and category listings.
 */
function akp_model_filter_bar() {
	$models = akp_get_models();
	if ( ! $models ) {
		return;
	}
	$current = get_query_var( 'pajero_model' );
	echo '<nav class="model-filter" aria-label="' . esc_attr__( 'Filter by Pajero model', 'alkaffa-parts' ) . '">';
	echo '<a class="chip' . ( $current ? '' : ' is-active' ) . '" href="' . esc_url( remove_query_arg( 'pajero_model' ) ) . '">' . esc_html__( 'All models', 'alkaffa-parts' ) . '</a>';
	foreach ( $models as $model ) {
		$url = is_tax( 'pajero_model' ) ? get_term_link( $model ) : add_query_arg( 'pajero_model', $model->slug );
		echo '<a class="chip' . ( $current === $model->slug ? ' is-active' : '' ) . '" href="' . esc_url( $url ) . '">' . esc_html( $model->name ) . '</a>';
	}
	echo '</nav>';
}

/*
 * CSV import (Products → Import): support "Pajero models" and "OEM part number" columns.
 */
add_filter(
	'woocommerce_csv_product_import_mapping_options',
	function ( $options ) {
		$options['akp_pajero_models'] = __( 'Pajero models', 'alkaffa-parts' );
		$options['akp_oem_number']    = __( 'OEM part number', 'alkaffa-parts' );
		return $options;
	}
);

add_filter(
	'woocommerce_csv_product_import_mapping_default_columns',
	function ( $columns ) {
		$columns[ __( 'Pajero models', 'alkaffa-parts' ) ]   = 'akp_pajero_models';
		$columns[ __( 'OEM part number', 'alkaffa-parts' ) ] = 'akp_oem_number';
		return $columns;
	}
);

add_action(
	'woocommerce_product_import_inserted_product_object',
	function ( $product, $data ) {
		if ( ! empty( $data['akp_oem_number'] ) ) {
			$product->update_meta_data( '_akp_oem_number', wc_clean( $data['akp_oem_number'] ) );
			$product->save();
		}
		if ( ! empty( $data['akp_pajero_models'] ) ) {
			$models = array_filter( array_map( 'trim', explode( ',', $data['akp_pajero_models'] ) ) );
			wp_set_object_terms( $product->get_id(), $models, 'pajero_model' );
		}
	},
	10,
	2
);

/**
 * Orders go through WhatsApp only: no prices on the site and no cart. Each part shows
 * "Price on WhatsApp" and the customer gets the price in the chat.
 */
add_filter( 'woocommerce_is_purchasable', '__return_false' );
add_filter(
	'woocommerce_get_price_html',
	function () {
		return '<span class="price-ask">' . esc_html__( 'Price on WhatsApp', 'alkaffa-parts' ) . '</span>';
	}
);
add_filter( 'woocommerce_empty_price_html', fn() => '<span class="price-ask">' . esc_html__( 'Price on WhatsApp', 'alkaffa-parts' ) . '</span>' );
add_filter( 'woocommerce_product_add_to_cart_text', fn() => __( 'View details', 'alkaffa-parts' ) );
add_filter( 'woocommerce_product_single_add_to_cart_text', fn() => __( 'Order on WhatsApp', 'alkaffa-parts' ) );

/**
 * Keep prices out of Google results too: drop the offer (price) from product structured data.
 *
 * @param array $markup Product schema.
 * @return array
 */
function akp_structured_data_without_price( $markup ) {
	unset( $markup['offers'] );
	return $markup;
}
add_filter( 'woocommerce_structured_data_product', 'akp_structured_data_without_price' );
