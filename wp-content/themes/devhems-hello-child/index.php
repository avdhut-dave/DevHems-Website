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

if ( is_category() ) {
	$banner_title = single_cat_title( '', false );
} elseif ( is_tag() ) {
	$banner_title = single_tag_title( '', false );
} elseif ( is_search() ) {
	$banner_title = sprintf(
		/* translators: %s: search query */
		__( 'Search Results for: %s', 'devhems-child' ),
		get_search_query()
	);
} else {
	$banner_title = __( 'Growth Notes', 'devhems-child' );
}

get_template_part( 'template-parts/page-banner', null, array(
	'title'   => $banner_title,
	'support' => is_search() ? '' : __( 'SEO, paid media, social and web development insights from the DevHems Technology team.', 'devhems-child' ),
) );
?>

<main id="content" tabindex="-1">
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

</main>

<?php get_template_part( 'template-parts/bottom-cta-banner' ); ?>
<?php get_footer(); ?>
