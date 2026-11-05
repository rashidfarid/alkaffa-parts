<?php
/**
 * Small helpers used across templates.
 *
 * @package AlKaffaParts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Read a theme setting from the Customizer, with the theme default as fallback.
 *
 * @param string $key Setting key without the akp_ prefix.
 * @return string
 */
function akp_option( $key ) {
	$defaults = akp_option_defaults();
	return (string) get_theme_mod( 'akp_' . $key, isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
}

/**
 * Default values for Customizer settings.
 *
 * @return array
 */
function akp_option_defaults() {
	return array(
		'phone'      => '+971 50 124 9137',
		'whatsapp'   => '+971501249137',
		'email'      => 'alkaffahusedparts@gmail.com',
		'address'    => 'Al Sajaa, Sharjah, United Arab Emirates',
		'hours'      => __( 'Sat – Thu: 8:00 AM – 9:00 PM', 'alkaffa-parts' ),
		'hero_title' => __( 'Spare parts for your Mitsubishi Pajero', 'alkaffa-parts' ),
		'hero_text'  => __( 'Genuine, OEM-quality and used parts for the 3rd and 4th generation Pajero (2000 – 2019). Find the right part in seconds.', 'alkaffa-parts' ),
		'hero_image' => '',
		'hero_video' => '',
	);
}

/**
 * URL of a theme image with the theme version appended, so browsers and the CDN fetch it again after each update.
 *
 * @param string $path Path inside assets/images/.
 * @return string
 */
function akp_asset_url( $path ) {
	return get_template_directory_uri() . '/assets/images/' . $path . '?ver=' . AKP_VERSION;
}

/**
 * URL of the homepage background video chosen in the Customizer, or an empty string.
 *
 * @return string
 */
function akp_hero_video_url() {
	$id = (int) akp_option( 'hero_video' );
	if ( ! $id || 0 !== strpos( (string) get_post_mime_type( $id ), 'video/' ) ) {
		return '';
	}
	return (string) wp_get_attachment_url( $id );
}

/**
 * WhatsApp click-to-chat link, or an empty string if no number is set.
 *
 * @param string $text Pre-filled message.
 * @return string
 */
function akp_whatsapp_url( $text = '' ) {
	$number = preg_replace( '/\D/', '', akp_option( 'whatsapp' ) );
	if ( '' === $number ) {
		return '';
	}
	$url = 'https://wa.me/' . $number;
	if ( '' !== $text ) {
		$url .= '?text=' . rawurlencode( $text );
	}
	return $url;
}

/**
 * Shop page URL, falling back to the home page when WooCommerce is inactive.
 *
 * @return string
 */
function akp_shop_url() {
	return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
}

/**
 * Inline SVG of a boxy SUV used as decoration.
 *
 * @param string $class CSS class.
 * @return string
 */
function akp_suv_svg( $class = '' ) {
	return '<svg class="' . esc_attr( $class ) . '" viewBox="0 0 200 84" aria-hidden="true" focusable="false">'
		. '<path fill="currentColor" d="M8 62 L12 41 Q14 34 23 33 L50 31 L66 13 Q69 10 75 10 L150 10 Q157 10 159 16 L167 31 L185 34 Q193 36 193 45 L193 62 Z"/>'
		. '<path fill="var(--akp-glass, #9fb3c8)" d="M58 31 L71 16 L104 16 L104 31 Z M110 16 L149 16 L156 31 L110 31 Z"/>'
		. '<rect x="193" y="34" width="5" height="24" rx="2" fill="currentColor"/>'
		. '<circle cx="48" cy="64" r="15" fill="#111"/><circle cx="48" cy="64" r="6" fill="#bbb"/>'
		. '<circle cx="155" cy="64" r="15" fill="#111"/><circle cx="155" cy="64" r="6" fill="#bbb"/>'
		. '</svg>';
}

/**
 * Inline SVG of the Al Kaffa Parts logo mark: a hex-nut badge with a Pajero on a dune.
 *
 * @param string $class CSS class.
 * @return string
 */
function akp_logo_mark( $class = 'brand__mark' ) {
	return '<svg class="' . esc_attr( $class ) . '" viewBox="0 0 64 64" aria-hidden="true" focusable="false">'
		. '<defs><linearGradient id="akp-g" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ffa057"/><stop offset="1" stop-color="#e8590c"/></linearGradient><clipPath id="akp-c"><path d="M32 2.5 58 17.5v29L32 61.5 6 46.5v-29Z"/></clipPath></defs><g class="logo-nut"><path d="M32 2.5 58 17.5v29L32 61.5 6 46.5v-29Z" fill="url(#akp-g)"/><path d="M32 7.5 53.6 20v24L32 56.5 10.4 44V20Z" fill="none" stroke="#fff" stroke-opacity=".4" stroke-width="1.2" stroke-linejoin="round"/></g><g clip-path="url(#akp-c)"><circle class="logo-sun" cx="45" cy="15.5" r="3.6" fill="#fff" fill-opacity=".85"/><path class="logo-dune" d="M0 45.5 Q15 39 31 44 T64 41.5 V64 H0Z" fill="#0b1a2e"/><g class="logo-car"><rect x="9.6" y="30.6" width="3.4" height="8.6" rx="1.6" fill="#0b1a2e"/><path fill="#0b1a2e" d="M12.6 41V31.4Q12.6 29.4 14.6 29.2L16.4 29 18.6 22.2Q19.1 21 20.6 21H44.2Q45.8 21 46.3 22.4L48.6 28.8 50.4 29.4Q52 30 52 32V41Z"/><path fill="#0b1a2e" d="M20 19.6H44" stroke="#0b1a2e" stroke-width="1.3" stroke-linecap="round"/><path fill="#fff" d="M19.9 23.2H26.6V28.6H18.2Z M28.4 23.2H35.6V28.6H28.4Z M37.4 23.2H44L45.9 28.6H37.4Z"/><rect x="46.6" y="31.4" width="5.4" height="1.8" rx=".9" fill="#ffa057"/><rect x="12.6" y="35" width="39.4" height="1.2" fill="#ffa057" fill-opacity=".9"/><circle cx="21" cy="41.4" r="5" fill="#0b1a2e" stroke="#ffa057" stroke-width="1.5"/><circle cx="21" cy="41.4" r="1.7" fill="#fff"/><circle cx="43.2" cy="41.4" r="5" fill="#0b1a2e" stroke="#ffa057" stroke-width="1.5"/><circle cx="43.2" cy="41.4" r="1.7" fill="#fff"/></g></g><path d="M32 2.5 58 17.5v29L32 61.5 6 46.5v-29Z" fill="none" stroke="#e8590c" stroke-width="1.6" stroke-linejoin="round"/>'
		. '</svg>';
}

/**
 * Inline WhatsApp icon.
 *
 * @return string
 */
function akp_whatsapp_icon() {
	return '<svg class="wa-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.3-.4.8-1.4.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.1 5.1 0 0 0 1.1 2.7 11.7 11.7 0 0 0 4.5 4c1.7.7 2.3.8 3.2.6a2.7 2.7 0 0 0 1.8-1.2 2.2 2.2 0 0 0 .1-1.3c0-.1-.2-.2-.4-.3Z"/></svg>';
}

/**
 * WhatsApp order link for one product, pre-filled with name, part number, price and link.
 *
 * @param WC_Product $product Product.
 * @return string
 */
function akp_whatsapp_order_url( $product ) {
	$lines = array( __( 'Hello Al Kaffa Parts, I want to order (please send the price):', 'alkaffa-parts' ), '• ' . $product->get_name() );
	if ( $product->get_sku() ) {
		/* translators: %s: part number */
		$lines[] = sprintf( __( 'Part no.: %s', 'alkaffa-parts' ), $product->get_sku() );
	}
	$lines[] = get_permalink( $product->get_id() );
	$lines[] = __( 'My Pajero year / VIN: ', 'alkaffa-parts' );
	return akp_whatsapp_url( implode( "\n", $lines ) );
}

/**
 * Fallback menu used until the owner builds one under Appearance → Menus.
 */
function akp_menu_fallback() {
	echo '<ul class="menu">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'alkaffa-parts' ) . '</a></li>';
	echo '<li><a href="' . esc_url( akp_shop_url() ) . '">' . esc_html__( 'All Parts', 'alkaffa-parts' ) . '</a></li>';

	$contact = get_page_by_path( 'contact' );
	if ( $contact ) {
		echo '<li><a href="' . esc_url( get_permalink( $contact ) ) . '">' . esc_html__( 'Contact', 'alkaffa-parts' ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * [akp_contact] shortcode: contact card built from Customizer settings.
 *
 * @return string
 */
function akp_contact_shortcode() {
	$phone    = akp_option( 'phone' );
	$whatsapp = akp_whatsapp_url( __( 'Hello Al Kaffa Parts, I need a part for my Pajero.', 'alkaffa-parts' ) );
	$email    = akp_option( 'email' );
	$address  = akp_option( 'address' );
	$hours    = akp_option( 'hours' );

	ob_start();
	?>
	<div class="contact-card">
		<?php if ( $phone ) : ?>
			<p><strong><?php esc_html_e( 'Phone', 'alkaffa-parts' ); ?></strong><a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
		<?php endif; ?>
		<?php if ( $whatsapp ) : ?>
			<p><strong>WhatsApp</strong><a href="<?php echo esc_url( $whatsapp ); ?>" target="_blank" rel="noopener"><?php echo esc_html( akp_option( 'whatsapp' ) ); ?></a></p>
		<?php endif; ?>
		<?php if ( $email ) : ?>
			<p><strong><?php esc_html_e( 'Email', 'alkaffa-parts' ); ?></strong><a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a></p>
		<?php endif; ?>
		<?php if ( $address ) : ?>
			<p><strong><?php esc_html_e( 'Garage address', 'alkaffa-parts' ); ?></strong><a href="<?php echo esc_url( 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $address ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $address ); ?></a></p>
		<?php endif; ?>
		<?php if ( $hours ) : ?>
			<p><strong><?php esc_html_e( 'Opening hours', 'alkaffa-parts' ); ?></strong><span><?php echo esc_html( $hours ); ?></span></p>
		<?php endif; ?>
		<p class="contact-card__tip"><?php esc_html_e( 'Not sure which part fits? Send us your chassis (VIN) number and we will find the exact part for your Pajero.', 'alkaffa-parts' ); ?></p>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'akp_contact', 'akp_contact_shortcode' );
