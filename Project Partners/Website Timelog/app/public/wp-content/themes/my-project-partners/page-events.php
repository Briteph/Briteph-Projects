<?php
/**
 * Template Name: Events
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
    <!-- UPCOMING EVENTS -->
    <section class="mb-4">
      <header class="flex items-center space-between">
        <h3 class="text-purple mr-1">Upcoming Events</h3>
        <a class="category-link arrow text-purple"
          href="<?php echo get_post_type_archive_link('event') . '?event-type=upcoming' ?>">View all</a>
      </header>

      <?php
        // Get 5 events that are greater than or equal to today's date (future events).
        $args = array(
          'post_type' => 'event',
          'post_status' => 'publish',
          'posts_per_page'=> 5,
          'meta_key' => 'start_date_and_time',
          'orderby' => 'meta_value',
          'order' => 'ASC',
          'meta_query' => array(
            array(
              'key' => 'start_date_and_time',
              'compare' => '>=',
              'type' => 'DATE',
              'value' => date('Y-m-d H:i:s')
            ),
          )
        );

        // The query
        $query = new WP_Query($args);

        if( $query->have_posts() ) :
          while( $query->have_posts() ) :
            $query->the_post();

            get_template_part( 'template-parts/post-event' );

          endwhile;
        endif;
      ?>

    </section> <!-- Upcoming events end -->

    <!-- PREVIOUS EVENTS -->
    <section>
      <header class="flex items-center space-between">
        <h3 class="text-purple mr-1">Previous Events</h3>
        <a class="category-link arrow text-purple"
          href="<?php echo get_post_type_archive_link('event') . '?event-type=previous' ?>">View all</a>
      </header>

      <?php
        // Get 5 events that are less than today's date (in the past)
        $args = array(
          'post_type' => 'event',
          'post_status' => 'publish',
          'posts_per_page'=> 5,
          'meta_key' => 'start_date_and_time',
          'orderby' => 'meta_value',
          'order' => 'DESC',
          'meta_query' => array(
            array(
              'key' => 'start_date_and_time',
              'compare' => '<',
              'type' => 'DATE',
              'value' => date('Y-m-d H:i:s')
            ),
          )
        );

        // The query
        $query = new WP_Query($args);

        if( $query->have_posts() ) :
          while( $query->have_posts() ) :
            $query->the_post();

            get_template_part( 'template-parts/post-event' );

          endwhile;
        endif;
      ?>
    </section> <!-- Previous events end -->

  </div>
</main><!-- #main -->


<?php
get_footer();