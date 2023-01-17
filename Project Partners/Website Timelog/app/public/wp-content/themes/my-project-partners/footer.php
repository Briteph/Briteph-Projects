<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package My_Project_Partners
 */

?>
<input type="hidden" id="barba-page-title" value="<?php esc_html(get_the_title()); ?>">
<footer id="colophon" class="site-footer">
  <div class="sep align-right <?php echo get_the_title() === 'Send us your thoughts!' ? 'd-none' : ''; ?>">
    <a href="<?php echo get_permalink( get_page_by_title( 'Send us your thoughts!' ) ); ?>">Send us
      your thoughts</a>
  </div>
  <div class="site-info">
    <span class="sep grey align-right">2021 © Project Partners</span>
  </div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</div> <!-- #app-content -->

</body>

</html>