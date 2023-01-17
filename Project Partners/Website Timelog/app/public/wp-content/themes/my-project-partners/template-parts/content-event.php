<?php
/**
 * Template part for displaying an event.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_Project_Partners
 */

?>

<?php
  if(is_singular()) :
    // Template for displaying all the content of a single event
    get_template_part( 'template-parts/post-single' );
  else :
    // Template for displaying an event in an archive
    get_template_part( 'template-parts/post-event' );
  endif;
?>