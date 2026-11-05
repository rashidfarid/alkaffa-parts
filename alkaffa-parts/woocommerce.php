<?php
/**
 * Wrapper for all WooCommerce pages: shop, categories, Pajero models and single parts.
 *
 * @package AlKaffaParts
 */

get_header();
?>
<main class="site-main site-main--shop">
	<div class="wrap">
		<?php woocommerce_breadcrumb(); ?>
		<?php if ( ! is_singular( 'product' ) ) : ?>
			<header class="shop-header" data-reveal>
				<h1><?php woocommerce_page_title(); ?></h1>
				<?php if ( is_tax( 'pajero_model' ) && term_description() ) : ?>
					<p class="shop-header__meta"><?php echo esc_html( wp_strip_all_tags( term_description() ) ); ?></p>
				<?php endif; ?>
			</header>
			<?php akp_model_filter_bar(); ?>
		<?php endif; ?>

		<?php woocommerce_content(); ?>
	</div>
</main>
<?php
get_footer();
