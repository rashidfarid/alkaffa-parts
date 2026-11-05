<?php
/**
 * Page not found.
 *
 * @package AlKaffaParts
 */

get_header();
?>
<main class="site-main">
	<div class="wrap empty-state">
		<?php echo akp_suv_svg( 'empty-state__suv' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static markup. ?>
		<h1><?php esc_html_e( 'This road leads nowhere', 'alkaffa-parts' ); ?></h1>
		<p><?php esc_html_e( 'The page you are looking for has moved or no longer exists. Search for your part instead:', 'alkaffa-parts' ); ?></p>
		<?php get_template_part( 'template-parts/search-form' ); ?>
	</div>
</main>
<?php
get_footer();
