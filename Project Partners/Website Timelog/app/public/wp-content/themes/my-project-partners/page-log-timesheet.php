<?php
/**
 * Template Name: Log Timesheet
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_Project_Partners
 */

get_header();

?>

	<main id="primary" class="site-main">

		<header class="entry-header mobile-only">
			<?php the_title( '<h1 class="entry-title h3">', '</h1>' ); ?>
		</header><!-- .entry-header -->

		<section class="grid my-timesheets mb-2">

			<?php
			if (pp_is_user_role('pp_partner')){
				get_template_part( '/template-parts/views/calendar-partner' );
			}
			else {
				echo '<h2 class="h4">We could not find this page or you do not have permission to access it.</h2>';
			}
			?>

		</section>

	</main><!-- #main -->


<?php
get_footer();
