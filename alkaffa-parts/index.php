<?php
/**
 * Fallback template: blog, archives and search results.
 *
 * @package AlKaffaParts
 */

get_header();
?>
<main class="site-main">
	<div class="wrap">
		<header class="page-header" data-reveal>
			<h1>
				<?php
				if ( is_search() ) {
					/* translators: %s: search query */
					printf( esc_html__( 'Results for “%s”', 'alkaffa-parts' ), esc_html( get_search_query() ) );
				} elseif ( is_archive() ) {
					echo esc_html( wp_strip_all_tags( get_the_archive_title() ) );
				} elseif ( is_home() && ! is_front_page() ) {
					single_post_title();
				} else {
					esc_html_e( 'Latest news', 'alkaffa-parts' );
				}
				?>
			</h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="post-list">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'post-card' ); ?> data-reveal>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="post-card__meta"><?php echo esc_html( get_the_date() ); ?></p>
						<?php the_excerpt(); ?>
					</article>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<div class="empty-state">
				<h2><?php esc_html_e( 'Nothing found', 'alkaffa-parts' ); ?></h2>
				<p><?php esc_html_e( 'Try another search, or send us your VIN and we will find the part for you.', 'alkaffa-parts' ); ?></p>
				<?php get_template_part( 'template-parts/search-form' ); ?>
			</div>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
