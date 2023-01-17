<?php
/**
 * The archive template for displaying posts from the Events category
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_Project_Partners
 */

get_header();
?>

<?php 
// Get event type from url query param.
$event_type = htmlspecialchars($_GET['event-type']);

// If event-type is not upcoming and isn't previous, or is empty -> set to 'upcoming' as default.
if(
    (
      $event_type !== 'upcoming' && 
      $event_type !== 'previous'
    ) || 
    empty($_GET['event-type'])
  ) :
    $event_type = 'upcoming';
endif;

// If event is upcoming, order by ascending date, and only pull in events that are greater than or equal to today's date.
$order = $event_type === 'upcoming' ? 'ASC' : 'DESC';
$compare = $event_type === 'upcoming' ? '>=' : '<';
?>

<main id="primary" class="site-main pb-4">
  <h1 class="mb-3 h2"><?php echo ucfirst($event_type) . ' Events'; ?></h1>

  <section class="mb-4">
    <?php
        $args = array(
          'post-type' => 'post',
          'category_name' => 'Events',
          'post_status' => 'publish',
          'posts_per_page'=> 15,
          'meta_key' => 'start_date_and_time',
          'orderby' => 'meta_value',
          'order' => $order,
          'meta_query' => array(
            array(
              'key' => 'start_date_and_time',
              'compare' => $compare,
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
      ?>


    <?php
        endwhile;
      endif;
      ?>
  </section> <!-- events section end -->
</main><!-- #main -->

<?php
get_footer();