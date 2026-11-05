<?php
/**
 * Appearance → Al Kaffa Setup: one-click categories, models and demo parts.
 *
 * @package AlKaffaParts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the admin page.
 */
function akp_setup_menu() {
	add_theme_page(
		__( 'Al Kaffa Setup', 'alkaffa-parts' ),
		__( 'Al Kaffa Setup', 'alkaffa-parts' ),
		'manage_options',
		'akp-setup',
		'akp_render_setup_page'
	);
}
add_action( 'admin_menu', 'akp_setup_menu' );

/**
 * Sample Pajero parts. Prices are in the store currency.
 *
 * @return array
 */
function akp_demo_products() {
	return array(
		array( 'Front Brake Pad Set', 'AKP-1001', 145, 'brakes', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Low-dust ceramic front brake pads. Complete axle set with wear sensors.', true ),
		array( 'Front Brake Disc Rotor (pair)', 'AKP-1002', 420, 'brakes', array( 'pajero-gen-4' ), 'Ventilated front brake discs, sold as a pair. Replace together with pads.', false ),
		array( 'Rear Brake Shoe Set', 'AKP-1003', 120, 'brakes', array( 'pajero-gen-3' ), 'Rear drum brake shoes for models with rear drums.', false ),
		array( 'Oil Filter – V6 Petrol (6G72 / 6G74 / 6G75)', 'AKP-2001', 25, 'filters-service', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Spin-on oil filter for 3.0, 3.5 and 3.8 V6 petrol engines.', true ),
		array( 'Air Filter Element – 3.8 V6', 'AKP-2002', 45, 'filters-service', array( 'pajero-gen-4' ), 'High-flow paper air filter for the 6G75 3.8 V6.', false ),
		array( 'Fuel Filter – 3.2 DI-D (4M41)', 'AKP-2003', 60, 'filters-service', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Diesel fuel filter with water separator for the 4M41 engine.', false ),
		array( 'Timing Belt Kit – 6G74 / 6G75', 'AKP-3001', 380, 'engine-parts', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Timing belt with tensioner and idler pulleys. Recommended every 100,000 km.', true ),
		array( 'Iridium Spark Plug Set (6 pcs)', 'AKP-3002', 150, 'engine-parts', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Long-life iridium spark plugs for V6 petrol engines.', false ),
		array( 'Water Pump – 6G72 3.0 V6', 'AKP-4001', 260, 'cooling-system', array( 'pajero-gen-3' ), 'Water pump with gasket for the 3.0 V6 petrol engine.', false ),
		array( 'Radiator Assembly – Automatic', 'AKP-4002', 650, 'cooling-system', array( 'pajero-gen-4' ), 'Aluminium core radiator for automatic transmission models.', true ),
		array( 'Front Lower Control Arm – Left', 'AKP-5001', 340, 'suspension-steering', array( 'pajero-gen-4' ), 'Front lower arm with ball joint and bushes pre-fitted.', false ),
		array( 'Front Shock Absorber', 'AKP-5002', 290, 'suspension-steering', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Gas-charged front shock absorber for a firm, controlled ride on and off road.', true ),
		array( 'Front Stabilizer Link', 'AKP-5003', 55, 'suspension-steering', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Anti-roll bar link. Cures knocking over bumps.', false ),
		array( 'Alternator 12V 110A', 'AKP-6001', 950, 'electrical-sensors', array( 'pajero-gen-4' ), 'Remanufactured alternator, tested to OEM output.', false ),
		array( 'Oxygen Sensor – Upstream', 'AKP-6002', 210, 'electrical-sensors', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Heated front O2 sensor for V6 petrol engines.', false ),
		array( 'CV Joint – Front Outer', 'AKP-7001', 320, 'transmission-drivetrain', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Outer CV joint kit with boot, clamps and grease.', false ),
		array( 'Headlamp Assembly – Right (2015+)', 'AKP-8001', 1200, 'lighting', array( 'pajero-gen-4' ), 'Complete right-hand headlamp for the 2015–2021 facelift.', true ),
		array( 'Front Bumper (2015 – 2021)', 'AKP-9001', 1100, 'body-parts', array( 'pajero-gen-4' ), 'Primed front bumper cover, ready for paint.', false ),
		array( 'Rubber Floor Mat Set', 'AKP-9501', 180, 'interior-accessories', array( 'pajero-gen-4' ), 'Heavy-duty rubber mats, front and rear. Easy to wash out after desert trips.', false ),
		array( 'Rear Brake Pad Set', 'AKP-1004', 125, 'brakes', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Rear disc brake pads for models with rear discs. Includes anti-squeal shims.', false ),
		array( 'Front Brake Caliper – Right', 'AKP-1005', 540, 'brakes', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Remanufactured front caliper with new seals and piston, ready to fit.', false ),
		array( 'Brake Master Cylinder', 'AKP-1007', 390, 'brakes', array( 'pajero-gen-3' ), 'Tandem master cylinder with reservoir seals.', false ),
		array( 'Air Filter Element – 3.2 DI-D', 'AKP-2004', 55, 'filters-service', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Air filter for the 4M41 3.2 diesel. Change every 20,000 km in dusty conditions.', true ),
		array( 'Cabin AC Filter', 'AKP-2005', 40, 'filters-service', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Activated-carbon pollen filter. Stops dust and odours entering the cabin.', false ),
		array( 'Oil Filter – Diesel (4D56 / 4M40 / 4M41)', 'AKP-2006', 30, 'filters-service', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Spin-on oil filter for 2.5, 2.8 and 3.2 diesel engines.', false ),
		array( 'Service Kit – 3.8 V6 (oil, air, cabin filters)', 'AKP-2007', 130, 'filters-service', array( 'pajero-gen-4' ), 'Everything for a minor service on the 6G75: oil, air and cabin filters plus sump washer.', true ),
		array( 'Valve Cover Gasket Set – 6G75', 'AKP-3003', 140, 'engine-parts', array( 'pajero-gen-4' ), 'Left and right rocker cover gaskets with spark plug tube seals. Cures oil leaks.', false ),
		array( 'Engine Mount – Front', 'AKP-3004', 210, 'engine-parts', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Rubber-metal front engine mount. Reduces vibration at idle.', false ),
		array( 'Glow Plug Set (4 pcs) – 3.2 DI-D', 'AKP-3005', 240, 'engine-parts', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Fast-heating glow plugs for easy cold starts on the 4M41 diesel.', false ),
		array( 'Turbocharger – 4M41 3.2 DI-D', 'AKP-3007', 2850, 'engine-parts', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Complete replacement turbocharger with gasket kit.', true ),
		array( 'Fuel Injector – 4M41', 'AKP-3008', 690, 'engine-parts', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Tested common-rail injector, coded and ready to fit.', false ),
		array( 'Ignition Coil – 6G74 / 6G75', 'AKP-3009', 230, 'engine-parts', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Direct ignition coil pack. Fixes misfires and rough idle.', false ),
		array( 'Thermostat with Housing', 'AKP-4003', 145, 'cooling-system', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Thermostat and housing with seal. Opens at 82 °C.', false ),
		array( 'Radiator Cooling Fan Clutch', 'AKP-4004', 380, 'cooling-system', array( 'pajero-gen-3' ), 'Viscous fan clutch for proper cooling in summer heat.', false ),
		array( 'Upper Radiator Hose', 'AKP-4005', 65, 'cooling-system', array( 'pajero-gen-4' ), 'Reinforced rubber upper hose for the 3.8 V6.', false ),
		array( 'AC Condenser', 'AKP-4006', 720, 'cooling-system', array( 'pajero-gen-4' ), 'Aluminium AC condenser with receiver-drier. Ice-cold air for UAE summers.', true ),
		array( 'Water Pump – 4M41 3.2 DI-D', 'AKP-4007', 310, 'cooling-system', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Water pump with gasket for the 3.2 diesel engine.', false ),
		array( 'Rear Shock Absorber', 'AKP-5004', 270, 'suspension-steering', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Gas-charged rear shock absorber for heavy loads and rough tracks.', false ),
		array( 'Front Upper Ball Joint', 'AKP-5005', 120, 'suspension-steering', array( 'pajero-gen-3' ), 'Upper ball joint with boot and castle nut.', false ),
		array( 'Tie Rod End – Outer', 'AKP-5006', 85, 'suspension-steering', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Outer tie rod end. Restores precise steering.', false ),
		array( 'Power Steering Pump', 'AKP-5007', 860, 'suspension-steering', array( 'pajero-gen-3' ), 'Remanufactured power steering pump with pulley.', false ),
		array( 'Front Wheel Hub Bearing', 'AKP-5009', 230, 'suspension-steering', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Sealed front hub bearing. Stops humming at speed.', true ),
		array( 'Starter Motor 12V', 'AKP-6003', 780, 'electrical-sensors', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Remanufactured starter motor for V6 petrol engines.', false ),
		array( 'Crankshaft Position Sensor', 'AKP-6004', 160, 'electrical-sensors', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Crank sensor for V6 petrol. Fixes cutting-out and no-start problems.', false ),
		array( 'Mass Air Flow Sensor', 'AKP-6005', 420, 'electrical-sensors', array( 'pajero-gen-4' ), 'MAF sensor for the 3.8 V6. Restores smooth power and fuel economy.', false ),
		array( 'ABS Wheel Speed Sensor – Front', 'AKP-6006', 145, 'electrical-sensors', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Front ABS sensor. Clears the ABS warning light.', false ),
		array( 'Battery 12V 90Ah', 'AKP-6007', 450, 'electrical-sensors', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Heat-resistant maintenance-free battery built for Gulf summers.', true ),
		array( 'Transfer Case Oil Seal Kit', 'AKP-7003', 90, 'transmission-drivetrain', array( 'pajero-gen-3' ), 'Input and output seals for the Super Select transfer case.', false ),
		array( 'Propeller Shaft Universal Joint', 'AKP-7004', 110, 'transmission-drivetrain', array( 'pajero-gen-3' ), 'Greaseable U-joint for front or rear propeller shaft.', false ),
		array( 'Automatic Transmission Filter Kit', 'AKP-7005', 160, 'transmission-drivetrain', array( 'pajero-gen-4' ), 'ATF filter and pan gasket for the 5-speed automatic.', false ),
		array( 'Front Differential Mount Bush', 'AKP-7006', 75, 'transmission-drivetrain', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Rubber diff mount. Stops clunks when switching to 4WD.', false ),
		array( 'Tail Lamp – Left', 'AKP-8002', 420, 'lighting', array( 'pajero-gen-4' ), 'Rear combination lamp, left side.', false ),
		array( 'Fog Lamp Set (pair)', 'AKP-8003', 350, 'lighting', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Front fog lamps with bulbs, left and right.', false ),
		array( 'Side Mirror – Right, Electric', 'AKP-9002', 520, 'body-parts', array( 'pajero-gen-4' ), 'Electric folding door mirror with indicator, primed.', false ),
		array( 'Front Grille (2015 – 2021)', 'AKP-9003', 650, 'body-parts', array( 'pajero-gen-4' ), 'Chrome front grille for the facelift Pajero.', true ),
		array( 'Spare Wheel Cover', 'AKP-9004', 160, 'body-parts', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Hard-shell rear spare wheel cover — the classic Pajero look.', false ),
		array( 'Door Handle – Front Outer', 'AKP-9005', 95, 'body-parts', array( 'pajero-gen-3' ), 'Exterior front door handle, paintable.', false ),
		array( 'Seat Cover Set – Leather Look', 'AKP-9502', 650, 'interior-accessories', array( 'pajero-gen-4' ), 'Tailored 7-seat covers in hard-wearing faux leather.', false ),
		array( 'Roof Rack Cross Bars', 'AKP-9503', 420, 'interior-accessories', array( 'pajero-gen-3', 'pajero-gen-4' ), 'Lockable aluminium cross bars for factory roof rails. 75 kg load.', false ),
	);
}

/**
 * Create the demo products. Skips any SKU that already exists.
 *
 * @return int Number of products created.
 */
function akp_install_demo_products() {
	if ( ! class_exists( 'WC_Product_Simple' ) ) {
		return 0;
	}
	akp_install_defaults();

	$created = 0;
	foreach ( akp_demo_products() as $row ) {
		list( $name, $sku, $price, $cat, $models, $desc, $featured ) = $row;
		if ( wc_get_product_id_by_sku( $sku ) ) {
			continue;
		}
		try {
			$product = new WC_Product_Simple();
			$product->set_name( $name );
			$product->set_sku( $sku );
			$product->set_regular_price( (string) $price );
			$product->set_short_description( $desc );
			$product->set_description( $desc . "\n\n" . __( 'Check the "Fits" list to confirm compatibility, or send us your VIN on WhatsApp.', 'alkaffa-parts' ) );
			$product->set_manage_stock( true );
			$product->set_stock_quantity( wp_rand( 2, 20 ) );
			$product->set_featured( $featured );
			$product->set_status( 'publish' );
			$term = get_term_by( 'slug', $cat, 'product_cat' );
			if ( $term ) {
				$product->set_category_ids( array( $term->term_id ) );
			}
			$id = $product->save();
			wp_set_object_terms( $id, $models, 'pajero_model' );
			++$created;
		} catch ( WC_Data_Exception $e ) {
			continue;
		}
	}
	return $created;
}

/**
 * Handle the setup form.
 */
function akp_handle_setup_action() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to do this.', 'alkaffa-parts' ) );
	}
	check_admin_referer( 'akp_setup' );

	$task = isset( $_POST['akp_task'] ) ? sanitize_key( $_POST['akp_task'] ) : '';
	$args = array( 'page' => 'akp-setup' );
	if ( 'demo' === $task ) {
		$args['created'] = akp_install_demo_products();
	} else {
		akp_install_defaults();
		$args['defaults'] = 1;
	}
	wp_safe_redirect( add_query_arg( $args, admin_url( 'themes.php' ) ) );
	exit;
}
add_action( 'admin_post_akp_setup', 'akp_handle_setup_action' );

/**
 * Render Appearance → Al Kaffa Setup.
 */
function akp_render_setup_page() {
	$wc_active = class_exists( 'WooCommerce' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Al Kaffa Parts – Store setup', 'alkaffa-parts' ); ?></h1>

		<?php if ( isset( $_GET['created'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification ?>
			<div class="notice notice-success"><p>
				<?php
				/* translators: %d: number of products */
				printf( esc_html__( '%d demo parts created.', 'alkaffa-parts' ), (int) $_GET['created'] ); // phpcs:ignore WordPress.Security.NonceVerification
				?>
			</p></div>
		<?php elseif ( isset( $_GET['defaults'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Pajero models, part categories and Contact page are ready.', 'alkaffa-parts' ); ?></p></div>
		<?php endif; ?>

		<?php if ( ! $wc_active ) : ?>
			<div class="notice notice-warning"><p>
				<?php esc_html_e( 'WooCommerce is not active. Install it from Plugins → Add New → search "WooCommerce", then come back here.', 'alkaffa-parts' ); ?>
			</p></div>
		<?php endif; ?>

		<ol style="font-size:14px;line-height:1.8">
			<li><?php esc_html_e( 'Set your phone, WhatsApp, address and homepage photo in Appearance → Customize → Al Kaffa Parts.', 'alkaffa-parts' ); ?></li>
			<li><?php esc_html_e( 'Set your currency, shipping zones and payment methods (e.g. Cash on Delivery) in WooCommerce → Settings.', 'alkaffa-parts' ); ?></li>
			<li><?php esc_html_e( 'Add parts in Products → Add New. Tick the Pajero models each part fits and fill in the OEM part number.', 'alkaffa-parts' ); ?></li>
		</ol>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="akp_setup">
			<?php wp_nonce_field( 'akp_setup' ); ?>
			<p>
				<button class="button" name="akp_task" value="defaults"><?php esc_html_e( 'Create Pajero models & part categories', 'alkaffa-parts' ); ?></button>
				<button class="button button-primary" name="akp_task" value="demo" <?php disabled( ! $wc_active ); ?>><?php printf( esc_html__( 'Add %d Pajero parts', 'alkaffa-parts' ), count( akp_demo_products() ) ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment ?></button>
			</p>
			<p class="description"><?php esc_html_e( 'Demo parts are examples to preview the shop. Edit or delete them before going live.', 'alkaffa-parts' ); ?></p>
		</form>
	</div>
	<?php
}
