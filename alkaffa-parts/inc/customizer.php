<?php
/**
 * Customizer settings: Appearance → Customize → Al Kaffa Parts.
 *
 * @package AlKaffaParts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register garage contact details and homepage text.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function akp_customize_register( $wp_customize ) {
	$defaults = akp_option_defaults();

	$wp_customize->add_section(
		'akp_settings',
		array(
			'title'    => __( 'Al Kaffa Parts', 'alkaffa-parts' ),
			'priority' => 30,
		)
	);

	$fields = array(
		'phone'      => array( __( 'Phone number', 'alkaffa-parts' ), 'text', 'sanitize_text_field' ),
		'whatsapp'   => array( __( 'WhatsApp number (with country code, e.g. +971501234567)', 'alkaffa-parts' ), 'text', 'sanitize_text_field' ),
		'email'      => array( __( 'Email', 'alkaffa-parts' ), 'email', 'sanitize_email' ),
		'address'    => array( __( 'Garage address', 'alkaffa-parts' ), 'textarea', 'sanitize_textarea_field' ),
		'hours'      => array( __( 'Opening hours', 'alkaffa-parts' ), 'text', 'sanitize_text_field' ),
		'hero_title' => array( __( 'Homepage headline', 'alkaffa-parts' ), 'text', 'sanitize_text_field' ),
		'hero_text'  => array( __( 'Homepage intro text', 'alkaffa-parts' ), 'textarea', 'sanitize_textarea_field' ),
	);

	foreach ( $fields as $key => $field ) {
		$wp_customize->add_setting(
			'akp_' . $key,
			array(
				'default'           => $defaults[ $key ],
				'sanitize_callback' => $field[2],
			)
		);
		$wp_customize->add_control(
			'akp_' . $key,
			array(
				'label'   => $field[0],
				'section' => 'akp_settings',
				'type'    => $field[1],
			)
		);
	}

	$wp_customize->add_setting(
		'akp_hero_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'akp_hero_image',
			array(
				'label'       => __( 'Homepage background photo', 'alkaffa-parts' ),
				'description' => __( 'A wide photo of your garage or a Pajero works best (1920×900).', 'alkaffa-parts' ),
				'section'     => 'akp_settings',
			)
		)
	);

	$wp_customize->add_setting(
		'akp_hero_video',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'akp_hero_video',
			array(
				'label'       => __( 'Homepage background video', 'alkaffa-parts' ),
				'description' => __( 'Short MP4 loop (10–20 seconds, under 10 MB, 1920×1080). Plays silently behind the headline instead of the 3D Pajero. The background photo above is shown while it loads.', 'alkaffa-parts' ),
				'section'     => 'akp_settings',
				'mime_type'   => 'video',
			)
		)
	);
}
add_action( 'customize_register', 'akp_customize_register' );
