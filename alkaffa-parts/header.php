<?php
/**
 * Site header.
 *
 * @package AlKaffaParts
 */

$akp_phone = akp_option( 'phone' );
$akp_wa    = akp_whatsapp_url( __( 'Hello Al Kaffa Parts, I need a part for my Pajero.', 'alkaffa-parts' ) );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script>document.documentElement.classList.add('js');</script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'alkaffa-parts' ); ?></a>

<div class="topbar">
	<div class="wrap">
		<span class="topbar__hours"><?php echo esc_html( akp_option( 'hours' ) ); ?></span>
		<span class="topbar__links">
			<?php if ( $akp_phone ) : ?>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $akp_phone ) ); ?>"><?php echo esc_html( $akp_phone ); ?></a>
			<?php endif; ?>
			<?php if ( $akp_wa ) : ?>
				<a href="<?php echo esc_url( $akp_wa ); ?>" target="_blank" rel="noopener">WhatsApp</a>
			<?php endif; ?>
			<span><?php esc_html_e( 'Genuine & OEM Pajero parts', 'alkaffa-parts' ); ?></span>
		</span>
	</div>
</div>

<header class="site-header">
	<div class="wrap">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php if ( has_custom_logo() ) : ?>
				<?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array( 'class' => 'custom-logo' ) ); ?>
			<?php else : ?>
				<?php
				echo '<img class="brand__car" src="' . esc_url( akp_asset_url( 'models/pajero-gen-4.webp' ) ) . '" alt="" width="1200" height="598">';
				$akp_name_parts = explode( ' ', get_bloginfo( 'name' ) );
				$akp_name_last  = count( $akp_name_parts ) > 1 ? array_pop( $akp_name_parts ) : '';
				?>
				<span class="brand__text">
					<span class="brand__name"><?php echo esc_html( implode( ' ', $akp_name_parts ) ); ?><?php echo $akp_name_last ? ' <span>' . esc_html( $akp_name_last ) . '</span>' : ''; ?></span>
					<span class="brand__tag"><?php esc_html_e( 'Pajero specialists · UAE', 'alkaffa-parts' ); ?></span>
				</span>
			<?php endif; ?>
		</a>

		<nav class="main-nav" id="main-nav" aria-label="<?php esc_attr_e( 'Main menu', 'alkaffa-parts' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'fallback_cb'    => 'akp_menu_fallback',
					'depth'          => 1,
				)
			);
			?>
		</nav>

		<div class="header-actions">
			<?php if ( $akp_wa ) : ?>
				<a class="header-wa" href="<?php echo esc_url( $akp_wa ); ?>" target="_blank" rel="noopener">
					<?php echo akp_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static markup. ?>
					<span class="header-wa__text"><small><?php esc_html_e( 'Order on WhatsApp', 'alkaffa-parts' ); ?></small><?php echo esc_html( akp_option( 'phone' ) ); ?></span>
				</a>
			<?php endif; ?>
			<button class="icon-btn search-toggle" type="button" aria-controls="header-search" aria-expanded="false" aria-label="<?php esc_attr_e( 'Search parts', 'alkaffa-parts' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
			</button>
			<button class="icon-btn menu-toggle" type="button" aria-controls="main-nav" aria-expanded="false" aria-label="<?php esc_attr_e( 'Menu', 'alkaffa-parts' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
			</button>
		</div>
	</div>
	<div class="header-search" id="header-search" hidden>
		<div class="wrap"><?php get_template_part( 'template-parts/search-form' ); ?></div>
	</div>
</header>

<div id="content" class="site-content">
