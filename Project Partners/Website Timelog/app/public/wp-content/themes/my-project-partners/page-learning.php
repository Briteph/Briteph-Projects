<?php
/**
 * Template Name: Learning 
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_Project_Partners
 */

get_header();
?>

<main id="primary" class="site-main">
  <div class="page-container">

    <header class="entry-header mb-2half">
      <?php the_title( '<h1 class="entry-title h2">', '</h1>' ); ?>
      <p><?php the_field( 'page_description' ); ?></p>
    </header><!-- .entry-header -->

    <!-- COURSES SECTION START -->
    <section class="mb-2half">
      <header class="flex items-center space-between">
        <h3 class="text-purple mr-1">Our Courses</h3>
        <a class="category-link arrow text-purple"
          href="<?php echo get_post_type_archive_link('sfwd-courses') ?>">View all</a>
      </header>
      <div class="articles-grid">
        <!-- Loop through 3 Learndash courses and output in a grid -->
        <?php
        $args= array(
                'post_type' => 'sfwd-courses',
                'post_status' => 'publish',
                'order' => 'DESC',
                'orderby' => 'date',
                'posts_per_page'=>3,
                'mycourses' => false
              );
                
        $query = new WP_Query( $args );

        if( $query->have_posts() ) :
          while ( $query->have_posts() ) : 
            $query->the_post();

            // Template to display post in a card
            include get_theme_file_path( '/template-parts/post-card.php' );
            
          endwhile; // end of query
        else :
          echo "No courses found";
        endif;
        
        wp_reset_postdata();  // Restore original Post Data
        ?>

      </div>
    </section> <!-- end of courses section -->

    <!-- VIDEO SECTION START -->
    <section class="mb-2half">
      <header class="flex items-center space-between">
        <h3 class="text-purple mr-1">Our Videos</h3>
        <a class="category-link arrow text-purple"
          href="<?php echo get_post_type_archive_link('video') ?>">View all</a>
      </header>
      <div class="articles-grid">
        <!-- Loop through 3 videos and output in a grid -->
        <?php
        $args= array(
              'post_type' => 'video',
              'post_status' => 'publish',
              'posts_per_page'=>3,
              );
              
        $query = new WP_Query( $args );

        if( $query->have_posts()) :
          while ( $query->have_posts() ) : 
            $query->the_post();

            // Show each video post in a card
            include get_theme_file_path( '/template-parts/post-card.php' );

          endwhile; // end of query

        else :
          echo "No videos found";
        endif;

        // Restore original Post Data
        wp_reset_postdata();
        ?>
      </div>
    </section> <!-- end of video section -->

    <!-- VIDEO SECTION START -->
    <section class="mb-2half">
      <header class="flex items-center space-between">
        <h3 class="text-purple mr-1">Learning Resources</h3>
        <a class="category-link arrow text-purple"
          href="<?php echo get_post_type_archive_link('learning-resource') ?>">View all</a>
      </header>
      <div class="articles-grid">
        <!-- Loop through 3 learning resources and output in a grid -->
        <?php
        $args= array(
              'post_type' => 'learning-resource',
              'post_status' => 'publish',
              'posts_per_page'=>3,
              );
              
        $query = new WP_Query( $args );

        if( $query->have_posts()) :
          while ( $query->have_posts() ) : 
            $query->the_post();

            // Show each video post in a card
            include get_theme_file_path( '/template-parts/post-card.php' );

          endwhile; // end of query

        else :
          echo "No resources found";
        endif;

        // Restore original Post Data
        wp_reset_postdata();
        ?>
      </div>
    </section> <!-- end of learning resources section -->
  </div>
</main><!-- #main -->

<?php
get_footer();