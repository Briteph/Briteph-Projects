<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package My_Project_Partners
 */

get_header();
?>

<main id="primary" class="site-main">

  <?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );

			$cat_name = get_the_category()[0]->cat_name;

			////// Unomment to show next & prev links on posts that aren't videos or events. ///////
			// if( ('Events' !== $cat_name) && ('Videos' !== $cat_name) ) :
			// 	the_post_navigation(
			// 		array(
			// 			'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'my-project-partners' ) . '</span> <span class="nav-title">%title</span>',
			// 			'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'my-project-partners' ) . '</span> <span class="nav-title">%title</span>',
			// 		)
			// 	);
			// endif;

			$back_link = 'post' === get_post_type() ? 
					get_category_link( get_the_category()[0]->cat_ID ) :
					get_post_type_archive_link( get_post_type() );
			?>

  		<a href="<?php echo $back_link; ?>">← Back</a>

      <?php
			// If comments are open or we have at least one comment, AND it's not a video or event post, load up the comment template.
			if ( (comments_open() || get_comments_number()) && 
					 'Videos' !== $cat_name &&
						'Events' !== $cat_name
				) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

</main><!-- #main -->

<?php
get_footer();