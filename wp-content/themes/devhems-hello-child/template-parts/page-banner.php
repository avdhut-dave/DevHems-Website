<?php
/**
 * Reusable inner-page banner: dark gradient background, breadcrumb, H1 and
 * a one-line support paragraph. Used by every fallback PHP template.
 *
 * Usage: get_template_part( 'template-parts/page-banner', null, array(
 *     'title'   => 'Our Services',
 *     'support' => 'One line describing the page.',
 * ) );
 */

defined( 'ABSPATH' ) || exit;

$title   = $args['title'] ?? get_the_title();
$support = $args['support'] ?? '';
?>
<div class="devhems-page-banner">
	<div class="devhems-page-banner-inner">
		<?php echo do_shortcode( '[devhems_breadcrumbs]' ); ?>
		<h1><?php echo esc_html( $title ); ?></h1>
		<?php if ( $support ) : ?>
			<p><?php echo esc_html( $support ); ?></p>
		<?php endif; ?>
	</div>
</div>
