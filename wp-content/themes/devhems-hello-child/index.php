<?php
/**
 * Required theme fallback (every WordPress theme needs an index.php) and
 * the template used for the Blog Listing page: the posts index, category/
 * tag archives and search results. The static Home/About/Contact pages are
 * unaffected — Elementor renders those via page.php regardless of this file.
 *
 * Prefer building the blog listing visually in Elementor Pro Theme Builder
 * (condition: Archive = Blog / Posts) with a Loop Grid widget if a Pro
 * license is available — see elementor-templates/THEME-BUILDER-GUIDE.md.
 */

defined( 'ABSPATH' ) || exit;
get_header();

$categories = get_categories( array( 'hide_empty' => true ) );
?>

<main id="content" tabindex="-1">
	<?php echo do_shortcode( '[devhems_breadcrumbs]' ); ?>

	<header class="devhems-archive-header">
		<h1>
			<?php
			if ( is_category() ) {
				single_cat_title();
			} elseif ( is_tag() ) {
				single_tag_title();
			} elseif ( is_search() ) {
				printf( esc_html__( 'Search Results for: %s', 'devhems-child' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
			} else {
				esc_html_e( 'Blog', 'devhems-child' );
			}
			?>
		</h1>
		<?php if ( ! is_search() ) : ?>
			<p><?php esc_html_e( 'Insights on SEO, web development, AI automation and growth marketing from the DevHems Technology team.', 'devhems-child' ); ?></p>
		<?php endif; ?>
	</header>

	<?php if ( ! empty( $categories ) ) : ?>
	<nav class="devhems-service-filter" aria-label="<?php esc_attr_e( 'Filter posts by category', 'devhems-child' ); ?>">
		<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/' ) ); ?>" class="devhems-filter-pill<?php echo ( ! is_category() ) ? ' is-active' : ''; ?>">
			<?php esc_html_e( 'All Posts', 'devhems-child' ); ?>
		</a>
		<?php foreach ( $categories as $category ) : ?>
			<a href="<?php echo esc_url( get_category_link( $category ) ); ?>" class="devhems-filter-pill<?php echo is_category( $category->term_id ) ? ' is-active' : ''; ?>">
				<?php echo esc_html( $category->name ); ?>
			</a>
		<?php endforeach; ?>
	</nav>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<div class="devhems-card-grid">
			<?php while ( have_posts() ) : the_post(); ?>
				<a class="devhems-blog-card" href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( 'devhems-card', array( 'loading' => 'lazy' ) ); ?>
					<span class="devhems-blog-card-category"><?php echo esc_html( get_the_category_list( ', ' ) ); ?></span>
					<h2><?php the_title(); ?></h2>
					<p><?php echo esc_html( get_the_excerpt() ); ?></p>
					<span class="devhems-blog-card-meta"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( devhems_reading_time() ); ?></span>
				</a>
			<?php endwhile; ?>
		</div>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No posts found.', 'devhems-child' ); ?></p>
	<?php endif; ?>

	<section class="devhems-final-cta">
		<h2><?php esc_html_e( 'Want growth insights delivered to your inbox?', 'devhems-child' ); ?></h2>
		<?php echo do_shortcode( '[contact-form-7 id="FOOTER_ENQUIRY_FORM_ID" title="Footer Enquiry Form"]' ); ?>
	</section>
</main>

<?php get_footer(); ?>
