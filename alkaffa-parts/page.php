<?php
/**
 * Single pages and posts (Contact, About, Cart, Checkout…).
 *
 * @package AlKaffaParts
 */

get_header();
?>
<main class="site-main">
	<div class="wrap">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class(); ?>>
				<header class="page-header" data-reveal>
					<h1><?php the_title(); ?></h1>
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</main>
<?php
get_footer();
