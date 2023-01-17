<?php
/**
 * Template Name: Articles
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_Project_Partners
 */

get_header();

?>

<main id="primary" class="site-main">
  <div class="page-container mb-3">

    <header class="entry-header mb-3">
      <?php the_title( '<h1 class="entry-title h2">', '</h1>' ); ?>
      <p><?php the_field( 'page_description' ); ?></p>
    </header><!-- .entry-header -->

	      <!-- PROJECT PONDERINGS SECTION START -->
    <section class="mb-4">
      <?php $category = 'Project Ponderings'; ?>

      <header class="flex items-center space-between">
        <h3 class="text-purple mr-1"><?php echo $category; ?></h3>
        <a class="category-link arrow text-purple"
          href="<?php echo get_category_link( get_category_by_slug( $category ) ) ?>">View all</a>
      </header>

      <div class="articles-grid">
        <?php
          // Query 3 posts & display in grid of cards
          $args = array(
            'post_type' => 'post',
            'category_name' => $category,
            'post_status' => 'publish',
            'posts_per_page'=>3,
            'orderby' => 'date',
            'order'   => 'DESC',
          );

          // The query
          $query = new WP_Query($args);

          if( $query->have_posts() ) :
            while( $query->have_posts() ) :
              $query->the_post();

               // Show the post in a card
              include get_theme_file_path( '/template-parts/post-card.php' );
            endwhile; // end of query
        ?>
      </div>
        <?php
        else : 
          echo "no $category found";
        endif; 

        // Restore original Post Data
        wp_reset_postdata();
        ?>
    </section> <!-- Project Ponderings end --> 
	  
    <!-- TUESDAY TITBITS SECTION START -->
    <section class="mb-4">
      <?php $category = 'Tuesday Titbits'; ?>

      <header class="flex items-center space-between">
        <h3 class="text-purple mr-1"><?php echo $category; ?></h3>
        <a class="category-link arrow text-purple"
          href="<?php echo get_category_link( get_category_by_slug( $category ) ) ?>">View all</a>
      </header>

      <div class="articles-grid">
        <?php
          // Query 3 posts & display in grid of cards
          $args = array(
            'post_type' => 'post',
            'category_name' => $category,
            'post_status' => 'publish',
            'posts_per_page'=>3,
            'orderby' => 'date',
            'order'   => 'DESC',
          );

          // The query
          $query = new WP_Query($args);

          if( $query->have_posts() ) :
            while( $query->have_posts() ) :
              $query->the_post();

               // Show the post in a card
              include get_theme_file_path( '/template-parts/post-card.php' );
            endwhile; // end of query
        ?>
      </div>
        <?php
        else : 
          echo "no $category found";
        endif; 

        // Restore original Post Data
        wp_reset_postdata();
        ?>
    </section> <!-- Tuesday Titbits end --> 

    <!-- SUNDAY SUCCESS SECTION START -->
    <section class="mb-4">
      <?php $category = 'Sunday Success'; ?>

      <header class="flex items-center space-between">
        <h3 class="text-purple mr-1"><?php echo $category; ?></h3>
        <a class="category-link arrow text-purple"
          href="<?php echo get_category_link( get_category_by_slug( $category ) ) ?>">View all</a>
      </header>

      <div class="articles-grid">
        <?php
          // Query 3 posts & display in grid of cards
          $args = array(
            'post_type' => 'post',
            'category_name' => $category,
            'post_status' => 'publish',
            'posts_per_page'=>3,
            'orderby' => 'date',
            'order'   => 'DESC',
          );

          // The query
          $query = new WP_Query($args);

          if( $query->have_posts() ) :
            while( $query->have_posts() ) :
              $query->the_post();

               // Show the post in a card
              include get_theme_file_path( '/template-parts/post-card.php' );
            endwhile; // end of query
        ?>
      </div>
        <?php
        else : 
          echo "no $category found";
        endif; 

        // Restore original Post Data
        wp_reset_postdata();
        ?>
    </section> <!-- Sunday success end -->

	  
  </div>
</main><!-- #main -->

<?php
get_footer();