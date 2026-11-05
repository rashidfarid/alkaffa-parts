<?php
/**
 * Part search: product name, part number (SKU) or OEM number.
 *
 * @package AlKaffaParts
 */

?>
<form class="part-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="akp-search-<?php echo esc_attr( wp_unique_id() ); ?>"><?php esc_html_e( 'Search parts', 'alkaffa-parts' ); ?></label>
	<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Part name, part no. or OEM no.', 'alkaffa-parts' ); ?>">
	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<input type="hidden" name="post_type" value="product">
	<?php endif; ?>
	<button class="btn" type="submit"><?php esc_html_e( 'Search', 'alkaffa-parts' ); ?></button>
</form>
