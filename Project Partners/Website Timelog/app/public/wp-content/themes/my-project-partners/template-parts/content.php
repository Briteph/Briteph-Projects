<?php
/**
 * Template for displaying posts and Learndash courses
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_Project_Partners
 */

?>

<?php 

if(is_singular()) {
  // Template for displaying one whole single post/course
  include get_theme_file_path( '/template-parts/post-single.php' );
} else {
  // Template for displaying a post/course in a card - for archive pages
  include get_theme_file_path( '/template-parts/post-card.php' );
}

?>

