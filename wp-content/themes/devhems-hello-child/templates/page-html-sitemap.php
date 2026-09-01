<?php
/**
 * Template Name: HTML Sitemap
 *
 * Selectable from the Page Attributes > Template dropdown when editing any
 * WordPress Page. Lists every public page, service, case study, career and
 * recent blog post automatically — nothing here needs manual upkeep as
 * content is added or removed.
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>

<main id="content" tabindex="-1">
<?php while ( have_posts() ) : the_post(); ?>

	<?php echo do_shortcode( '[devhems_breadcrumbs]' ); ?>

	<h1><?php the_title(); ?></h1>

	<section>
		<h2><?php esc_html_e( 'Pages', 'devhems-child' ); ?></h2>
		<?php
		wp_list_pages( array(
			'title_li'  => '',
			'exclude'   => get_the_ID(),
			'sort_column' => 'menu_order,post_title',
		) );
		?>
	</section>

	<section>
		<h2><?php esc_html_e( 'Services', 'devhems-child' ); ?></h2>
		<ul>
			<?php
			$services = get_posts( array( 'post_type' => 'service', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );
			foreach ( $services as $service ) :
				?>
				<li><a href="<?php echo esc_url( get_permalink( $service ) ); ?>"><?php echo esc_html( get_the_title( $service ) ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</section>

	<section>
		<h2><?php esc_html_e( 'Case Studies', 'devhems-child' ); ?></h2>
		<ul>
			<?php
			$case_studies = get_posts( array( 'post_type' => 'case_study', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );
			foreach ( $case_studies as $case_study ) :
				?>
				<li><a href="<?php echo esc_url( get_permalink( $case_study ) ); ?>"><?php echo esc_html( get_the_title( $case_study ) ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</section>

	<section>
		<h2><?php esc_html_e( 'Careers', 'devhems-child' ); ?></h2>
		<ul>
			<?php
			$careers = get_posts( array( 'post_type' => 'career', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );
			foreach ( $careers as $career ) :
				?>
				<li><a href="<?php echo esc_url( get_permalink( $career ) ); ?>"><?php echo esc_html( get_the_title( $career ) ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</section>

	<section>
		<h2><?php esc_html_e( 'Blog', 'devhems-child' ); ?></h2>
		<ul>
			<?php
			$posts = get_posts( array( 'post_type' => 'post', 'posts_per_page' => 50 ) );
			foreach ( $posts as $blog_post ) :
				?>
				<li><a href="<?php echo esc_url( get_permalink( $blog_post ) ); ?>"><?php echo esc_html( get_the_title( $blog_post ) ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</section>

<?php endwhile; ?>
</main>

<?php get_footer(); ?>
