<?php
/**
 * Mega menu support.
 *
 * Two ways to use this:
 *  1. Elementor Pro Theme Builder header + Elementor Pro's Nav Menu widget,
 *     styled with assets/css/mega-menu.css and driven by assets/js/mega-menu.js
 *     (recommended — matches "build the header in Elementor" requirement).
 *  2. The [devhems_mega_menu] shortcode below, which renders the "primary-menu"
 *     WP menu location through a custom Walker that outputs the two-panel
 *     (dark left / white right) markup with full ARIA wiring, for use if
 *     Elementor Pro is not licensed and the header is built as a template part.
 *
 * To configure the menu structure: WP Admin > Appearance > Menus > create a
 * menu assigned to "Primary Mega Menu", add the Service Category taxonomy
 * terms and Service posts as menu items, and nest sub-services under each
 * category as second-level items. The "Services" top-level item automatically
 * becomes a mega-menu trigger when it has children.
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/class-devhems-mega-menu-walker.php';

function devhems_mega_menu_shortcode( $atts ) {
	if ( ! has_nav_menu( 'primary-menu' ) ) {
		return '';
	}

	return wp_nav_menu(
		array(
			'theme_location' => 'primary-menu',
			'container'      => 'nav',
			'container_class' => 'devhems-primary-nav',
			'container_aria_label' => __( 'Primary navigation', 'devhems-child' ),
			'menu_class'     => 'devhems-menu',
			'walker'         => new DevHems_Mega_Menu_Walker(),
			'echo'           => false,
			'fallback_cb'    => false,
		)
	);
}
add_shortcode( 'devhems_mega_menu', 'devhems_mega_menu_shortcode' );
