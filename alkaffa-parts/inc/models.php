<?php
/**
 * Pajero model fitment: a "Pajero model" taxonomy on products, default terms and categories.
 *
 * @package AlKaffaParts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the pajero_model taxonomy so each part can be tagged with the generations it fits.
 */
function akp_register_model_taxonomy() {
	register_taxonomy(
		'pajero_model',
		array( 'product' ),
		array(
			'labels'            => array(
				'name'          => __( 'Pajero models', 'alkaffa-parts' ),
				'singular_name' => __( 'Pajero model', 'alkaffa-parts' ),
				'menu_name'     => __( 'Pajero models', 'alkaffa-parts' ),
				'all_items'     => __( 'All Pajero models', 'alkaffa-parts' ),
				'edit_item'     => __( 'Edit model', 'alkaffa-parts' ),
				'add_new_item'  => __( 'Add new model', 'alkaffa-parts' ),
				'parent_item'   => __( 'Parent model', 'alkaffa-parts' ),
			),
			'description'       => __( 'Which Pajero generations a part fits.', 'alkaffa-parts' ),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'pajero-model' ),
		)
	);
}
add_action( 'init', 'akp_register_model_taxonomy' );

/**
 * Pajero generations created on activation. Description holds years and chassis codes.
 *
 * @return array
 */
function akp_default_models() {
	return array(
		'pajero-gen-3' => array( __( 'Pajero 3rd Gen', 'alkaffa-parts' ), '2000 – 2006 · V60 / V70' ),
		'pajero-gen-4' => array( __( 'Pajero 4th Gen', 'alkaffa-parts' ), '2006 – 2019 · V80 / V90' ),
	);
}

/**
 * Part categories created on activation (needs WooCommerce).
 *
 * @return array
 */
function akp_default_categories() {
	return array(
		'engine-parts'            => __( 'Engine Parts', 'alkaffa-parts' ),
		'filters-service'         => __( 'Filters & Service', 'alkaffa-parts' ),
		'brakes'                  => __( 'Brakes', 'alkaffa-parts' ),
		'suspension-steering'     => __( 'Suspension & Steering', 'alkaffa-parts' ),
		'cooling-system'          => __( 'Cooling System', 'alkaffa-parts' ),
		'electrical-sensors'      => __( 'Electrical & Sensors', 'alkaffa-parts' ),
		'transmission-drivetrain' => __( 'Transmission & Drivetrain', 'alkaffa-parts' ),
		'body-parts'              => __( 'Body Parts', 'alkaffa-parts' ),
		'lighting'                => __( 'Lighting', 'alkaffa-parts' ),
		'interior-accessories'    => __( 'Interior & Accessories', 'alkaffa-parts' ),
		'wheels-tyres'            => __( 'Wheels & Tyres', 'alkaffa-parts' ),
	);
}

/**
 * Create Pajero models, part categories and a Contact page if they don't exist yet.
 */
function akp_install_defaults() {
	if ( taxonomy_exists( 'pajero_model' ) ) {
		foreach ( akp_default_models() as $slug => $model ) {
			if ( ! term_exists( $slug, 'pajero_model' ) ) {
				wp_insert_term(
					$model[0],
					'pajero_model',
					array(
						'slug'        => $slug,
						'description' => $model[1],
					)
				);
			}
		}
	}

	if ( taxonomy_exists( 'product_cat' ) ) {
		foreach ( akp_default_categories() as $slug => $name ) {
			if ( ! term_exists( $slug, 'product_cat' ) ) {
				wp_insert_term( $name, 'product_cat', array( 'slug' => $slug ) );
			}
		}
	}

	if ( ! get_page_by_path( 'contact' ) ) {
		wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_title'   => __( 'Contact Us', 'alkaffa-parts' ),
				'post_name'    => 'contact',
				'post_status'  => 'publish',
				'post_content' => '[akp_contact]',
			)
		);
	}
}

/**
 * Top-level Pajero models, oldest generation first.
 *
 * @return WP_Term[]
 */
function akp_get_models() {
	if ( ! taxonomy_exists( 'pajero_model' ) ) {
		return array();
	}
	$terms = get_terms(
		array(
			'taxonomy'   => 'pajero_model',
			'hide_empty' => false,
			'parent'     => 0,
			'orderby'    => 'slug',
		)
	);
	return is_wp_error( $terms ) ? array() : $terms;
}

/**
 * Top-level part categories, excluding WooCommerce's "Uncategorized".
 *
 * @return WP_Term[]
 */
function akp_get_part_categories() {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return array();
	}
	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'parent'     => 0,
			'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
		)
	);
	return is_wp_error( $terms ) ? array() : $terms;
}

/**
 * Photo for a Pajero model: the owner's image from the model's edit screen, else the bundled one.
 *
 * @param WP_Term $term Model term.
 * @return string Image URL, or empty string.
 */
function akp_model_image_url( $term ) {
	$custom = get_term_meta( $term->term_id, 'akp_image', true );
	if ( $custom ) {
		return $custom;
	}
	$file = '/assets/images/models/' . $term->slug . '.webp';
	return file_exists( get_template_directory() . $file ) ? get_template_directory_uri() . $file . '?ver=' . AKP_VERSION : '';
}

/**
 * "Photo URL" field on Products → Pajero models → Edit.
 *
 * @param WP_Term $term Model term.
 */
function akp_model_image_field( $term ) {
	?>
	<tr class="form-field">
		<th scope="row"><label for="akp_image"><?php esc_html_e( 'Photo URL', 'alkaffa-parts' ); ?></label></th>
		<td>
			<input type="url" name="akp_image" id="akp_image" value="<?php echo esc_attr( get_term_meta( $term->term_id, 'akp_image', true ) ); ?>">
			<p class="description"><?php esc_html_e( 'Upload a photo in Media → Add New, copy its File URL and paste it here. A PNG or WebP with a transparent background looks best. Leave empty to use the theme photo.', 'alkaffa-parts' ); ?></p>
		</td>
	</tr>
	<?php
}
add_action( 'pajero_model_edit_form_fields', 'akp_model_image_field' );

/**
 * Save the model photo URL.
 *
 * @param int $term_id Term ID.
 */
function akp_save_model_image( $term_id ) {
	if ( ! isset( $_POST['akp_image'] ) || ! current_user_can( 'manage_categories' ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- core verifies the term edit nonce.
		return;
	}
	$url = esc_url_raw( wp_unslash( $_POST['akp_image'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
	if ( $url ) {
		update_term_meta( $term_id, 'akp_image', $url );
	} else {
		delete_term_meta( $term_id, 'akp_image' );
	}
}
add_action( 'edited_pajero_model', 'akp_save_model_image' );
