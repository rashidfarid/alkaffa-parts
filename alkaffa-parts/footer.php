<?php
/**
 * Site footer.
 *
 * @package AlKaffaParts
 */

$akp_wa = akp_whatsapp_url( __( 'Hello Al Kaffa Parts, I need a part for my Pajero.', 'alkaffa-parts' ) );
?>
</div><!-- #content -->

<footer class="site-footer">
	<div class="wrap footer-grid">
		<div data-reveal>
			<h3 class="footer-brand"><img class="footer-brand__car" src="<?php echo esc_url( akp_asset_url( 'models/pajero-gen-4.webp' ) ); ?>" alt="" width="1200" height="598" loading="lazy"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h3>
			<p><?php echo esc_html( get_bloginfo( 'description' ) ? get_bloginfo( 'description' ) : __( 'Spare parts specialists for every Mitsubishi Pajero generation.', 'alkaffa-parts' ) ); ?></p>
			<?php if ( akp_option( 'address' ) ) : ?>
				<p><?php echo nl2br( esc_html( akp_option( 'address' ) ) ); ?></p>
			<?php endif; ?>
		</div>
		<div data-reveal>
			<h3><?php esc_html_e( 'Shop by model', 'alkaffa-parts' ); ?></h3>
			<ul>
				<?php foreach ( akp_get_models() as $akp_model ) : ?>
					<li><a href="<?php echo esc_url( get_term_link( $akp_model ) ); ?>"><?php echo esc_html( $akp_model->name ); ?></a></li>
				<?php endforeach; ?>
				<li><a href="<?php echo esc_url( akp_shop_url() ); ?>"><?php esc_html_e( 'All parts', 'alkaffa-parts' ); ?></a></li>
			</ul>
		</div>
		<div data-reveal>
			<h3><?php esc_html_e( 'Contact', 'alkaffa-parts' ); ?></h3>
			<ul>
				<?php if ( akp_option( 'phone' ) ) : ?>
					<li><a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', akp_option( 'phone' ) ) ); ?>"><?php echo esc_html( akp_option( 'phone' ) ); ?></a></li>
				<?php endif; ?>
				<?php if ( $akp_wa ) : ?>
					<li><a href="<?php echo esc_url( $akp_wa ); ?>" target="_blank" rel="noopener">WhatsApp</a></li>
				<?php endif; ?>
				<?php if ( akp_option( 'email' ) ) : ?>
					<li><a href="mailto:<?php echo esc_attr( antispambot( akp_option( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( akp_option( 'email' ) ) ); ?></a></li>
				<?php endif; ?>
				<li><?php echo esc_html( akp_option( 'hours' ) ); ?></li>
			</ul>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => false,
					'fallback_cb'    => false,
					'depth'          => 1,
				)
			);
			?>
		</div>
	</div>
	<div class="footer-bottom">
		<div class="wrap">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>.
			<?php esc_html_e( 'Mitsubishi and Pajero are trademarks of their respective owner. We are an independent parts supplier.', 'alkaffa-parts' ); ?>
		</div>
	</div>
</footer>

<?php if ( $akp_wa ) : ?>
	<div class="wa-widget">
		<div class="wa-bubble" id="wa-bubble" role="dialog" aria-label="<?php esc_attr_e( 'Chat on WhatsApp', 'alkaffa-parts' ); ?>" hidden>
			<button class="wa-bubble__close" type="button" aria-label="<?php esc_attr_e( 'Close', 'alkaffa-parts' ); ?>">&times;</button>
			<div class="wa-bubble__head">
				<?php echo akp_logo_mark( 'wa-bubble__logo' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static markup. ?>
				<div><strong><?php echo esc_html( get_bloginfo( 'name' ) ); ?></strong><span><?php esc_html_e( 'Pajero parts team', 'alkaffa-parts' ); ?></span></div>
			</div>
			<p class="wa-bubble__msg"><?php esc_html_e( 'Hi! Need a Pajero part? Send us a photo of the part or your VIN and we will send you the price.', 'alkaffa-parts' ); ?></p>
			<a class="btn btn--whatsapp" href="<?php echo esc_url( $akp_wa ); ?>" target="_blank" rel="noopener"><?php echo akp_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static markup. ?><?php esc_html_e( 'Start chat', 'alkaffa-parts' ); ?></a>
		</div>
		<a class="wa-float" href="<?php echo esc_url( $akp_wa ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Chat on WhatsApp', 'alkaffa-parts' ); ?>">
			<?php echo akp_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static markup. ?>
			<span class="wa-float__badge" aria-hidden="true">1</span>
		</a>
	</div>

	<nav class="mobile-bar" aria-label="<?php esc_attr_e( 'Contact', 'alkaffa-parts' ); ?>">
		<?php if ( akp_option( 'phone' ) ) : ?>
			<a class="mobile-bar__call" href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', akp_option( 'phone' ) ) ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2Z"/></svg>
				<?php esc_html_e( 'Call', 'alkaffa-parts' ); ?>
			</a>
		<?php endif; ?>
		<a class="mobile-bar__wa" href="<?php echo esc_url( $akp_wa ); ?>" target="_blank" rel="noopener">
			<?php echo akp_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static markup. ?>
			<?php esc_html_e( 'Order on WhatsApp', 'alkaffa-parts' ); ?>
		</a>
	</nav>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
