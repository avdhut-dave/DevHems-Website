<?php
/**
 * Custom Walker_Nav_Menu that renders a two-level WP menu as an accessible
 * mega menu: a dark "categories" panel on the left (top-level's direct
 * children with no grandchildren, or category items) and a white
 * sub-services panel on the right (grandchildren), matching the dark
 * info-panel / white service-panel layout requested for the header.
 *
 * Structure expected in Appearance > Menus:
 *   Services (top level)
 *     Digital Marketing (category — 2nd level)
 *       Search Engine Optimization (sub-service — 3rd level)
 *       Local SEO
 *       ...
 *     Website Services (category — 2nd level)
 *       UI/UX Design
 *       ...
 */

defined( 'ABSPATH' ) || exit;

class DevHems_Mega_Menu_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		// Depth 0 = the mega panel itself (rendered explicitly in start_el for
		// top-level items with children, see below), depth 1 = sub-service list.
		if ( 1 === $depth ) {
			$output .= '<ul class="devhems-subservice-list" role="group">';
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( 1 === $depth ) {
			$output .= '</ul>';
		}
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_children = in_array( 'menu-item-has-children', $classes, true );

		if ( 0 === $depth ) {
			$item_classes = array( 'devhems-menu-item' );
			if ( $has_children ) {
				$item_classes[] = 'has-mega-menu';
			}
			if ( in_array( 'current-menu-item', $classes, true ) || in_array( 'current-menu-parent', $classes, true ) ) {
				$item_classes[] = 'is-active';
			}

			$output .= '<li class="' . esc_attr( implode( ' ', $item_classes ) ) . '">';

			$link_attrs = array(
				'class' => 'devhems-menu-link',
			);
			if ( $has_children ) {
				$link_attrs['aria-haspopup'] = 'true';
				$link_attrs['aria-expanded'] = 'false';
			}
			if ( in_array( 'current-menu-item', $classes, true ) ) {
				$link_attrs['aria-current'] = 'page';
			}

			$output .= $this->render_link( $item, $link_attrs );

			if ( $has_children ) {
				$output .= '<div class="devhems-mega-panel" role="group" aria-label="' . esc_attr( $item->title ) . ' menu">';
				$output .= '<div class="devhems-mega-panel-inner">';
				$output .= '<div class="devhems-mega-info" aria-hidden="true">';
				$output .= '<p class="devhems-mega-info-eyebrow">' . esc_html__( 'Explore', 'devhems-child' ) . '</p>';
				$output .= '<p class="devhems-mega-info-title">' . esc_html( $item->title ) . '</p>';
				$output .= '<p class="devhems-mega-info-copy">' . esc_html__( 'Full-stack digital solutions built to grow your business.', 'devhems-child' ) . '</p>';
				$output .= '<a class="devhems-mega-info-cta" href="' . esc_url( get_post_type_archive_link( 'service' ) ?: home_url( '/services/' ) ) . '">' . esc_html__( 'View all services', 'devhems-child' ) . ' <span class="devhems-arrow" aria-hidden="true">&rarr;</span></a>';
				$output .= '</div>';
				$output .= '<div class="devhems-mega-categories" role="group">';
			}
			return;
		}

		if ( 1 === $depth ) {
			$output .= '<div class="devhems-mega-category">';
			$output .= '<p class="devhems-mega-category-title">' . esc_html( $item->title ) . '</p>';
			return;
		}

		if ( 2 === $depth ) {
			$output .= '<li class="devhems-subservice-item">';
			$output .= $this->render_link( $item, array( 'class' => 'devhems-subservice-link' ), true );
			$output .= '</li>';
		}
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_children = in_array( 'menu-item-has-children', $classes, true );

		if ( 0 === $depth ) {
			if ( $has_children ) {
				$output .= '</div>'; // .devhems-mega-categories
				$output .= '</div>'; // .devhems-mega-panel-inner
				$output .= '</div>'; // .devhems-mega-panel
			}
			$output .= '</li>';
			return;
		}

		if ( 1 === $depth ) {
			$output .= '</div>'; // .devhems-mega-category (sub-list closed by end_lvl before this fires)
		}
	}

	/**
	 * Render an <a> tag for a menu item, optionally with a trailing arrow
	 * icon (used for "important links" per the spec).
	 */
	private function render_link( $item, $extra_attrs = array(), $with_arrow = false ) {
		$attrs = array(
			'href' => esc_url( $item->url ),
		);
		$attrs = array_merge( $attrs, $extra_attrs );

		$attr_string = '';
		foreach ( $attrs as $key => $value ) {
			$attr_string .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}

		$arrow = $with_arrow ? ' <span class="devhems-arrow" aria-hidden="true">&rarr;</span>' : '';

		return '<a' . $attr_string . '>' . esc_html( $item->title ) . $arrow . '</a>';
	}
}
