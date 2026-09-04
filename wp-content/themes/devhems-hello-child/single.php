<?php
/**
 * Blog Detail Template (single 'post' view). Prefer building this visually
 * in Elementor Pro Theme Builder (condition: Single = Post) with dynamic
 * tags for title/content/featured image if a Pro license is available —
 * see elementor-templates/THEME-BUILDER-GUIDE.md.
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php
	get_template_part( 'template-parts/page-banner', null, array(
		'title'   => get_the_title(),
		'support' => wp_strip_all_tags( get_the_category_list( ', ' ) ),
	) );
	?>

<main id="content" tabindex="-1">

	<article <?php post_class( 'devhems-blog-article' ); ?>>

		<header class="devhems-blog-hero">
			<p class="devhems-blog-meta">
				<?php
				printf(
					/* translators: 1: author name, 2: publish date, 3: reading time */
					esc_html__( 'By %1$s · %2$s · %3$s', 'devhems-child' ),
					esc_html( get_the_author() ),
					esc_html( get_the_date() ),
					esc_html( devhems_reading_time() )
				);
				?>
			</p>
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'devhems-hero', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
			<?php endif; ?>
		</header>

		<div class="devhems-blog-body">
			<?php the_content(); ?>
		</div>

		<?php $tags = get_the_tags(); ?>
		<?php if ( $tags ) : ?>
			<div class="devhems-blog-tags">
				<?php foreach ( $tags as $tag ) : ?>
					<a class="devhems-filter-pill" href="<?php echo esc_url( get_tag_link( $tag ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="devhems-author-box">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 72 ); ?>
			<div>
				<p class="devhems-author-name"><?php the_author(); ?></p>
				<p class="devhems-author-bio"><?php the_author_meta( 'description' ); ?></p>
			</div>
		</div>

	</article>

	<?php
	$related = get_posts( array(
		'category__in'   => wp_get_post_categories( get_the_ID() ),
		'numberposts'    => 3,
		'post__not_in'   => array( get_the_ID() ),
	) );
	?>
	<?php if ( $related ) : ?>
	<section class="devhems-related-blogs">
		<h2><?php esc_html_e( 'You Might Also Like', 'devhems-child' ); ?></h2>
		<div class="devhems-card-grid">
			<?php foreach ( $related as $related_post ) : ?>
				<a class="devhems-blog-card" href="<?php echo esc_url( get_permalink( $related_post ) ); ?>">
					<?php echo get_the_post_thumbnail( $related_post, 'devhems-card', array( 'loading' => 'lazy' ) ); ?>
					<h3><?php echo esc_html( get_the_title( $related_post ) ); ?></h3>
				</a>
			<?php endforeach; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( comments_open() || get_comments_number() ) : ?>
		<section class="devhems-comments">
			<?php comments_template(); ?>
		</section>
	<?php endif; ?>

</main>

<?php get_template_part( 'template-parts/bottom-cta-banner' ); ?>
<?php endwhile; ?>

<?php get_footer(); ?>
