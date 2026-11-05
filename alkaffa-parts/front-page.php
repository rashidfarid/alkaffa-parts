<?php
/**
 * Homepage: hero with part search, shop by model, categories, featured parts.
 *
 * @package AlKaffaParts
 */

get_header();

$akp_hero_image = akp_option( 'hero_image' );
$akp_hero_video = akp_hero_video_url();
$akp_gen4       = get_term_by( 'slug', 'pajero-gen-4', 'pajero_model' );
$akp_hero_car   = $akp_gen4 ? akp_model_image_url( $akp_gen4 ) : akp_asset_url( 'models/pajero-gen-4.webp' );
$akp_wa         = akp_whatsapp_url( __( 'Hello Al Kaffa Parts, I need a part for my Pajero. My VIN is: ', 'alkaffa-parts' ) );
?>

<section class="hero<?php echo $akp_hero_video ? ' has-video' : ''; ?>"<?php echo $akp_hero_image ? ' style="background-image:url(' . esc_url( $akp_hero_image ) . ')"' : ''; ?>>
	<?php if ( $akp_hero_video ) : ?>
		<video class="hero__video" autoplay muted loop playsinline preload="auto"<?php echo $akp_hero_image ? ' poster="' . esc_url( $akp_hero_image ) . '"' : ''; ?> aria-hidden="true">
			<source src="<?php echo esc_url( $akp_hero_video ); ?>" type="<?php echo esc_attr( get_post_mime_type( (int) akp_option( 'hero_video' ) ) ); ?>">
		</video>
	<?php endif; ?>
	<div class="hero__desert" aria-hidden="true"><span class="hero__mtn"></span><span class="hero__dune-far"></span><span class="hero__dune-near"></span></div>
	<div class="hero__road" aria-hidden="true">
		<svg class="hero__roadsvg" viewBox="0 0 1000 120" preserveAspectRatio="none" focusable="false">
			<defs><linearGradient id="akp-asphalt" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#4f5866"/><stop offset="1" stop-color="#20262f"/></linearGradient></defs>
			<polygon points="0,46 1000,0 1000,120 0,120" fill="url(#akp-asphalt)"/>
			<path class="hero__kerb" d="M0 44 L1000 -2" stroke="#dfe5ee" stroke-width="9" stroke-dasharray="60 3" vector-effect="non-scaling-stroke"/>
			<path d="M0 50 L1000 4" stroke="#f2c230" stroke-width="3" vector-effect="non-scaling-stroke"/>
			<path class="hero__lane" d="M0 92 L1000 44" stroke="#f4f6f9" stroke-width="6" stroke-dasharray="70 70" vector-effect="non-scaling-stroke"/>
			<path d="M0 116 L1000 94" stroke="#ffffff" stroke-opacity=".75" stroke-width="3" vector-effect="non-scaling-stroke"/>
		</svg>
	</div>
	<?php if ( $akp_hero_car ) : ?>
		<div class="hero__stage" aria-hidden="true">
			<div class="hero__car" style="--car:url('<?php echo esc_url( $akp_hero_car ); ?>')">
				<img src="<?php echo esc_url( $akp_hero_car ); ?>" alt="" width="1200" height="598" fetchpriority="high">
				<span class="hero__wheel hero__wheel--front"><img src="<?php echo esc_url( akp_asset_url( 'models/rim-front.webp' ) ); ?>" alt=""></span>
				<svg class="hero__carrier" viewBox="0 0 1200 598" focusable="false">
					<defs>
						<linearGradient id="akp-tube" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#8a939e"/><stop offset=".35" stop-color="#2b3139"/><stop offset=".7" stop-color="#0d1014"/><stop offset="1" stop-color="#3a414a"/></linearGradient>
						<linearGradient id="akp-tubev" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#7d8792"/><stop offset=".45" stop-color="#22272e"/><stop offset="1" stop-color="#0b0d10"/></linearGradient>
						<linearGradient id="akp-tent" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ffb070"/><stop offset=".22" stop-color="#f27a2c"/><stop offset=".7" stop-color="#c94f12"/><stop offset="1" stop-color="#7a2c06"/></linearGradient>
						<radialGradient id="akp-tentcap" cx=".4" cy=".4" r=".7"><stop offset="0" stop-color="#ff9d55"/><stop offset="1" stop-color="#b54810"/></radialGradient>
						<linearGradient id="akp-box" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ffffff"/><stop offset="1" stop-color="#c9d2dd"/></linearGradient>
						<linearGradient id="akp-lid" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#5aa0ff"/><stop offset="1" stop-color="#1650a8"/></linearGradient>
						<linearGradient id="akp-can" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#5fb36f"/><stop offset=".35" stop-color="#2f7d40"/><stop offset="1" stop-color="#173f20"/></linearGradient>
						<linearGradient id="akp-bag" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#6d8fc6"/><stop offset=".3" stop-color="#34558c"/><stop offset=".75" stop-color="#1f3760"/><stop offset="1" stop-color="#101d36"/></linearGradient>
						<linearGradient id="akp-strap" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#3b3f45"/><stop offset=".5" stop-color="#1c1f23"/><stop offset="1" stop-color="#2c3036"/></linearGradient>
						<filter id="akp-fabric" x="0" y="0" width="100%" height="100%"><feTurbulence type="fractalNoise" baseFrequency="1.1 .35" numOctaves="2" seed="4"/><feColorMatrix values="0 0 0 0 0  0 0 0 0 0  0 0 0 0 0  0 0 0 .22 0"/><feComposite in2="SourceGraphic" operator="in"/><feBlend in2="SourceGraphic" mode="multiply"/></filter>
						<filter id="akp-soft" x="-10%" y="-50%" width="120%" height="200%"><feGaussianBlur stdDeviation="3.5"/></filter>
					</defs>
					<g transform="translate(742 8) rotate(5.9) scale(.93 1)">
						<rect x="4" y="3" width="350" height="9" rx="4" fill="#000" opacity=".5" filter="url(#akp-soft)"/>
						<g><path d="M44 9 l3 -11 h10 l3 11 z" fill="url(#akp-tubev)"/><path d="M174 9 l3 -11 h10 l3 11 z" fill="url(#akp-tubev)"/><path d="M298 9 l3 -11 h10 l3 11 z" fill="url(#akp-tubev)"/></g>
						<g opacity=".85" transform="translate(-20 -11)"><rect x="16" y="-30" width="346" height="4" rx="2.0" fill="url(#akp-tube)"/><rect x="8" y="-5" width="346" height="4" rx="2.0" fill="url(#akp-tube)"/><rect x="42.5" y="-28" width="3" height="25" rx="1.5" fill="url(#akp-tubev)"/><rect x="86.5" y="-28" width="3" height="25" rx="1.5" fill="url(#akp-tubev)"/><rect x="130.5" y="-28" width="3" height="25" rx="1.5" fill="url(#akp-tubev)"/><rect x="174.5" y="-28" width="3" height="25" rx="1.5" fill="url(#akp-tubev)"/><rect x="218.5" y="-28" width="3" height="25" rx="1.5" fill="url(#akp-tubev)"/><rect x="262.5" y="-28" width="3" height="25" rx="1.5" fill="url(#akp-tubev)"/><rect x="306.5" y="-28" width="3" height="25" rx="1.5" fill="url(#akp-tubev)"/></g>
						<path d="M8 -3 L354 -3 L334 -14 L-12 -14 Z" fill="#15191e"/>
						<path d="M30 -3 l-20 -11 M52 -3 l-20 -11 M74 -3 l-20 -11 M96 -3 l-20 -11 M118 -3 l-20 -11 M140 -3 l-20 -11 M162 -3 l-20 -11 M184 -3 l-20 -11 M206 -3 l-20 -11 M228 -3 l-20 -11 M250 -3 l-20 -11 M272 -3 l-20 -11 M294 -3 l-20 -11 M316 -3 l-20 -11 M338 -3 l-20 -11" stroke="#3a414a" stroke-width="2"/>
						<g class="hero__cargo">
							<g><rect x="24" y="-60" width="126" height="56" rx="10" fill="url(#akp-tent)"/><rect x="24" y="-60" width="126" height="56" rx="10" fill="#e8651a" filter="url(#akp-fabric)" opacity=".9" style="mix-blend-mode:multiply"/>
							<path d="M30 -54 q60 -6 116 0" stroke="#ffd0a8" stroke-width="2.5" fill="none" opacity=".7" stroke-linecap="round"/>
							<ellipse cx="25" cy="-32" rx="13" ry="28" fill="url(#akp-tentcap)"/><path d="M25 -32 m0 -4 a4 6 0 1 1 -4 7 a8 14 0 1 1 9 -15 a11 20 0 1 1 -12 24" fill="none" stroke="#7a2c06" stroke-width="1.6" opacity=".75"/></g>
							<g><path d="M160 -56 L244 -56 L228.0 -64.8 L144.0 -64.8 Z" fill="url(#akp-lid)"/>
							<rect x="160" y="-56" width="84" height="52" rx="4" fill="url(#akp-box)" stroke="#9aa6b4" stroke-width="1.5"/>
							<rect x="159" y="-57" width="86" height="12" rx="3" fill="url(#akp-lid)"/><rect x="161" y="-55" width="82" height="2" fill="#a9cbff" opacity=".8"/>
							<rect x="190" y="-32" width="24" height="6" rx="3" fill="#8a96a5"/><path d="M164 -42 h76" stroke="#ffffff" stroke-width="1.5" opacity=".8"/></g>
							<g><path d="M254 -58 L286 -58 L276.0 -63.5 L244.0 -63.5 Z" fill="#245e31"/>
							<path d="M254 -4 v-50 l6 -7 h21 l5 5 v52 z" fill="url(#akp-can)"/>
							<rect x="262" y="-67" width="9" height="8" rx="2" fill="#1b4523"/><rect x="273" y="-66" width="10" height="5" rx="2" fill="#1b4523"/>
							<path d="M259 -46 l23 34 M282 -46 l-23 34" stroke="#7fd08f" stroke-width="2" opacity=".55"/><path d="M260 -45 l23 34 M283 -45 l-23 34" stroke="#0f2e16" stroke-width="2" opacity=".5"/>
							<rect x="256" y="-52" width="3" height="46" fill="#a8e2b4" opacity=".35"/></g>
							<g><rect x="302" y="-44" width="50" height="40" rx="12" fill="url(#akp-bag)"/><rect x="302" y="-44" width="50" height="40" rx="12" fill="#2b4a7a" filter="url(#akp-fabric)" opacity=".8" style="mix-blend-mode:multiply"/>
							<ellipse cx="303" cy="-24" rx="10" ry="20" fill="#2f5089"/><path d="M303 -24 m0 -3 a3 4 0 1 1 -3 5 a6 10 0 1 1 7 -11 a8 15 0 1 1 -9 18" fill="none" stroke="#14264a" stroke-width="1.4"/>
							<rect x="318" y="-45" width="5" height="42" fill="#e8651a"/><rect x="336" y="-45" width="5" height="42" fill="#e8651a"/></g>
							<g><path d="M58 -59 h7 v56 h-7 z M118 -59 h7 v56 h-7 z" fill="url(#akp-strap)"/>
							<rect x="56" y="-34" width="11" height="8" rx="1.5" fill="#c9d0d8" stroke="#6b737d"/><rect x="116" y="-34" width="11" height="8" rx="1.5" fill="#c9d0d8" stroke="#6b737d"/>
							<path d="M196 -44 h7 v40 h-7 z" fill="url(#akp-strap)"/><rect x="194" y="-22" width="11" height="8" rx="1.5" fill="#c9d0d8" stroke="#6b737d"/></g>
						</g>
						<path d="M8 -30 q-14 2 -16 14 q-1 9 8 13" fill="none" stroke="url(#akp-tube)" stroke-width="5" stroke-linecap="round"/>
						<rect x="8" y="-31" width="348" height="5" rx="2.5" fill="url(#akp-tube)"/><rect x="8" y="-6" width="346" height="5" rx="2.5" fill="url(#akp-tube)"/>
						<rect x="42.3" y="-29" width="3.4" height="25" rx="1.5" fill="url(#akp-tubev)"/><rect x="86.3" y="-29" width="3.4" height="25" rx="1.5" fill="url(#akp-tubev)"/><rect x="130.3" y="-29" width="3.4" height="25" rx="1.5" fill="url(#akp-tubev)"/><rect x="174.3" y="-29" width="3.4" height="25" rx="1.5" fill="url(#akp-tubev)"/><rect x="218.3" y="-29" width="3.4" height="25" rx="1.5" fill="url(#akp-tubev)"/><rect x="262.3" y="-29" width="3.4" height="25" rx="1.5" fill="url(#akp-tubev)"/><rect x="306.3" y="-29" width="3.4" height="25" rx="1.5" fill="url(#akp-tubev)"/><rect x="350.3" y="-29" width="3.4" height="25" rx="1.5" fill="url(#akp-tubev)"/>
						<path d="M356 -31 q6 0 6 6 v19" fill="none" stroke="#1a1e24" stroke-width="5" stroke-linecap="round"/>
						<path d="M10 -30 h344" stroke="#d6dde6" stroke-width="1" opacity=".6"/>
					</g>
				</svg>
				<span class="hero__wheel hero__wheel--far"><img src="<?php echo esc_url( akp_asset_url( 'models/rim-far.webp' ) ); ?>" alt=""></span>
				<span class="hero__wheel hero__wheel--rear"><img src="<?php echo esc_url( akp_asset_url( 'models/rim-rear.webp' ) ); ?>" alt=""></span>
			</div>
			<span class="hero__dust"></span><span class="hero__dust"></span><span class="hero__dust"></span>
		</div>
	<?php endif; ?>
	<div class="wrap">
		<div class="hero__content">
			<p class="hero__eyebrow anim-up"><?php esc_html_e( 'Pajero parts specialists', 'alkaffa-parts' ); ?></p>
			<h1 class="anim-up" style="--d:.1s"><?php echo esc_html( akp_option( 'hero_title' ) ); ?></h1>
			<ul class="hero__trust anim-up" style="--d:.4s">
				<li><?php esc_html_e( 'Price before you pay', 'alkaffa-parts' ); ?></li>
				<li><?php esc_html_e( 'Fitment checked by VIN', 'alkaffa-parts' ); ?></li>
				<li><?php esc_html_e( 'Cash on delivery', 'alkaffa-parts' ); ?></li>
			</ul>
			<div class="hero__search anim-up" style="--d:.5s"><?php get_template_part( 'template-parts/search-form' ); ?></div>
		</div>
	</div>
</section>

<section class="usps">
	<div class="ticker">
		<?php for ( $akp_i = 0; $akp_i < 2; $akp_i++ ) : ?>
			<ul<?php echo $akp_i ? ' aria-hidden="true"' : ''; ?>>
				<li><?php esc_html_e( 'Genuine & OEM quality', 'alkaffa-parts' ); ?></li>
				<li><?php esc_html_e( 'Pajero 3rd & 4th Gen · 2000 – 2019', 'alkaffa-parts' ); ?></li>
				<li><?php esc_html_e( 'Fitment checked by VIN', 'alkaffa-parts' ); ?></li>
				<li><?php esc_html_e( 'Fast delivery', 'alkaffa-parts' ); ?></li>
				<li><?php esc_html_e( 'Cash on delivery', 'alkaffa-parts' ); ?></li>
			</ul>
		<?php endfor; ?>
	</div>
</section>

<?php if ( class_exists( 'WooCommerce' ) ) : ?>
	<?php $akp_new = do_shortcode( '[products limit="24" columns="3" tag="new-arrival" orderby="date" order="DESC"]' ); ?>
	<?php if ( false !== strpos( $akp_new, '<li' ) ) : ?>
<section class="section new-arrivals">
	<div class="wrap">
		<div class="section__head" data-reveal>
			<div>
				<p class="eyebrow"><?php esc_html_e( 'Just in', 'alkaffa-parts' ); ?></p>
				<h2><?php esc_html_e( 'New arrivals', 'alkaffa-parts' ); ?></h2>
			</div>
			<a href="<?php echo esc_url( akp_shop_url() ); ?>"><?php esc_html_e( 'All parts', 'alkaffa-parts' ); ?> &rarr;</a>
		</div>
		<div data-reveal><?php echo $akp_new; // phpcs:ignore WordPress.Security.EscapeOutput -- WooCommerce output. ?></div>
	</div>
</section>
	<?php endif; ?>
<?php endif; ?>

<?php if ( $akp_wa ) : ?>
<section class="section wa-order">
	<div class="wrap wa-order__grid">
		<div data-reveal>
			<p class="eyebrow"><?php esc_html_e( 'The fastest way to order', 'alkaffa-parts' ); ?></p>
			<h2><?php esc_html_e( 'Order on WhatsApp in 3 easy steps', 'alkaffa-parts' ); ?></h2>
			<ol class="wa-steps">
				<li><strong><?php esc_html_e( 'Send a photo or your VIN', 'alkaffa-parts' ); ?></strong><span><?php esc_html_e( 'Snap the old part, or share your chassis number and Pajero year.', 'alkaffa-parts' ); ?></span></li>
				<li><strong><?php esc_html_e( 'Get the price & fitment check', 'alkaffa-parts' ); ?></strong><span><?php esc_html_e( 'We confirm the exact part for your Pajero and send you the price.', 'alkaffa-parts' ); ?></span></li>
				<li><strong><?php esc_html_e( 'Delivered to your door', 'alkaffa-parts' ); ?></strong><span><?php esc_html_e( 'Confirm on WhatsApp and pay cash on delivery.', 'alkaffa-parts' ); ?></span></li>
			</ol>
			<a class="btn btn--whatsapp btn--lg" href="<?php echo esc_url( $akp_wa ); ?>" target="_blank" rel="noopener"><?php echo akp_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static markup. ?><?php echo esc_html( sprintf( /* translators: %s: phone number */ __( 'Chat now · %s', 'alkaffa-parts' ), akp_option( 'phone' ) ) ); ?></a>
		</div>
		<div class="wa-phone" data-reveal style="--d:.15s" aria-hidden="true">
			<div class="wa-phone__head">
				<?php echo akp_logo_mark( 'wa-phone__logo' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static markup. ?>
				<div><strong><?php echo esc_html( get_bloginfo( 'name' ) ); ?></strong><span><?php esc_html_e( 'online', 'alkaffa-parts' ); ?></span></div>
			</div>
			<div class="wa-phone__chat">
				<p class="wa-msg wa-msg--out" style="--i:0"><?php esc_html_e( 'Hi, do you have tail lights for my Pajero 2012?', 'alkaffa-parts' ); ?><time>10:02</time></p>
				<p class="wa-msg wa-msg--out wa-msg--img" style="--i:1"><img src="<?php echo esc_url( akp_asset_url( 'parts/lighting.svg' ) ); ?>" alt="" width="160" height="160" loading="lazy"><time>10:02</time></p>
				<p class="wa-msg wa-msg--typing" style="--i:2"><i></i><i></i><i></i></p>
				<p class="wa-msg wa-msg--in" style="--i:3"><?php esc_html_e( 'Hello! Yes, in stock. The clear chrome tail light set fits your 2012 Pajero. Sending you photos and the price now.', 'alkaffa-parts' ); ?><time>10:03</time></p>
				<p class="wa-msg wa-msg--out" style="--i:4"><?php esc_html_e( 'Great, please deliver to Dubai', 'alkaffa-parts' ); ?><time>10:04</time></p>
				<p class="wa-msg wa-msg--in" style="--i:5"><?php esc_html_e( 'Done! Your order is on the way. Pay cash on delivery.', 'alkaffa-parts' ); ?><time>10:04</time></p>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( class_exists( 'WooCommerce' ) ) : ?>
<section class="section">
	<div class="wrap">
		<div class="section__head" data-reveal>
			<div>
				<p class="eyebrow"><?php esc_html_e( 'Popular', 'alkaffa-parts' ); ?></p>
				<h2><?php esc_html_e( 'Best-selling Pajero parts', 'alkaffa-parts' ); ?></h2>
			</div>
			<a href="<?php echo esc_url( akp_shop_url() ); ?>"><?php esc_html_e( 'View all', 'alkaffa-parts' ); ?> &rarr;</a>
		</div>
		<div data-reveal>
			<?php
			$akp_featured = do_shortcode( '[products limit="8" columns="4" visibility="featured"]' );
			echo false !== strpos( $akp_featured, '<li' ) ? $akp_featured : do_shortcode( '[products limit="8" columns="4" orderby="date"]' ); // phpcs:ignore WordPress.Security.EscapeOutput -- WooCommerce output.
			?>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="section seo-intro">
	<div class="wrap seo-intro__grid" data-reveal>
		<div>
			<p class="eyebrow"><?php esc_html_e( 'Pajero parts specialists', 'alkaffa-parts' ); ?></p>
			<h2><?php esc_html_e( 'Mitsubishi Pajero spare parts in the UAE', 'alkaffa-parts' ); ?></h2>
			<p><?php esc_html_e( 'Al Kaffa Parts supplies new, OEM-quality and genuine used parts for the Mitsubishi Pajero 3rd generation (2000 – 2006, V60 / V70) and 4th generation (2006 – 2019, V80 / V90): brake parts, filters, engine and suspension parts, headlamps, bumpers, doors, fenders and other body parts.', 'alkaffa-parts' ); ?></p>
			<p><?php esc_html_e( 'Not sure which part fits? Send us your VIN or a photo of the old part on WhatsApp. We check the fitment, send you the price, and deliver across the UAE, including Dubai, Sharjah, Abu Dhabi, Ajman and Al Ain. Pay cash on delivery.', 'alkaffa-parts' ); ?></p>
		</div>
		<ul class="seo-intro__links">
			<?php foreach ( akp_get_models() as $akp_model ) : ?>
				<li><a href="<?php echo esc_url( get_term_link( $akp_model ) ); ?>"><?php /* translators: %s: model name */ echo esc_html( sprintf( __( 'Mitsubishi %s parts', 'alkaffa-parts' ), $akp_model->name ) ); ?><small><?php echo esc_html( $akp_model->description ); ?></small></a></li>
			<?php endforeach; ?>
			<?php $akp_body = get_term_by( 'slug', 'body-parts', 'product_cat' ); ?>
			<?php if ( $akp_body ) : ?>
				<li><a href="<?php echo esc_url( get_term_link( $akp_body ) ); ?>"><?php esc_html_e( 'Pajero body parts, new & used', 'alkaffa-parts' ); ?><small><?php esc_html_e( 'Bumpers, bonnet, doors, fenders, mirrors', 'alkaffa-parts' ); ?></small></a></li>
			<?php endif; ?>
		</ul>
	</div>
</section>

<section class="stats">
	<div class="wrap">
		<div class="stats__item" data-reveal><strong data-count="20">0</strong><span><?php esc_html_e( 'Years of Pajero covered', 'alkaffa-parts' ); ?></span></div>
		<div class="stats__item" data-reveal style="--d:.08s"><strong data-count="2" data-suffix="">0</strong><span><?php esc_html_e( 'Generations supported', 'alkaffa-parts' ); ?></span></div>
		<div class="stats__item" data-reveal style="--d:.16s"><strong data-count="<?php echo esc_attr( max( 10, (int) wp_count_posts( 'product' )->publish ) ); ?>">0</strong><span><?php esc_html_e( 'Parts in stock', 'alkaffa-parts' ); ?></span></div>
		<div class="stats__item" data-reveal style="--d:.24s"><strong data-count="100" data-suffix="%">0</strong><span><?php esc_html_e( 'Fitment checked', 'alkaffa-parts' ); ?></span></div>
	</div>
</section>

<section class="cta-band">
	<div class="wrap" data-reveal>
		<div>
			<h2><?php esc_html_e( 'Can’t find your part?', 'alkaffa-parts' ); ?></h2>
			<p><?php esc_html_e( 'Send us your chassis (VIN) number and a photo — we will source the exact part for your Pajero.', 'alkaffa-parts' ); ?></p>
		</div>
		<?php if ( $akp_wa ) : ?>
			<a class="btn btn--whatsapp" href="<?php echo esc_url( $akp_wa ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Chat on WhatsApp', 'alkaffa-parts' ); ?></a>
		<?php else : ?>
			<?php $akp_contact = get_page_by_path( 'contact' ); ?>
			<a class="btn" href="<?php echo esc_url( $akp_contact ? get_permalink( $akp_contact ) : akp_shop_url() ); ?>"><?php esc_html_e( 'Contact us', 'alkaffa-parts' ); ?></a>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
