<?php
/**
 * Template part for displaying a single post or Learndash course in a card - for archive pages and displaying multiple posts/courses.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */
?>

<article id="post-<?php the_ID(); ?>" class="article-card rounded shadow">
  <?php echo get_the_post_thumbnail(); ?>
  <div class="card-content">
    <h4 class="title"><?php echo get_the_title(); ?></h4>
    <p class="excerpt">
      <?php echo wp_trim_words(get_the_excerpt(), 30); ?>
    </p>
  </div>
  <?php 
	$linkToPDF = "";
	$category_name = get_the_category()[0]->cat_name;
	if ( 'learning-resource' === get_post_type() ||
			('Tuesday Titbits' === $category_name) || 
		  ('Sunday Success' === $category_name)
		) :
	// Check if the post content contains a PDF. If so, it returns link to pdf. If not, returns "".
	$linkToPDF = pp_get_link_to_pdf(get_the_content());
	endif;
	?>
  <a class="btn rounded" href="<?php echo !empty($linkToPDF) ? $linkToPDF : get_permalink(); ?>"
    <?php echo !empty($linkToPDF) ? 'target="_blank"' : ''; ?>>
    <?php
		// Anchor tag's text depends on post/category type.
		if ( 'video' === get_post_type() ) : 
			echo 'Watch Now';
		elseif ( 'post' === get_post_type() ) :
			echo 'Read';
		elseif ( 'sfwd-courses' === get_post_type() ) :
			echo 'Go to Course';
		else :
			echo 'View';
		endif;
		?>
  </a>
  <?php echo get_post_type() === 'sfwd-courses' ? learndash_course_progress( array( 'course_id' => get_the_ID() ) ) : ''; ?>
</article>