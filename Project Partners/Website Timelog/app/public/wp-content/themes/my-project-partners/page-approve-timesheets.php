<?php
/**
 * Template Name: Approve Timesheets
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_Project_Partners
 * 
 */

get_header();

?>

	<main id="primary" class="site-main">

		<header class="entry-header mobile-only">
			<?php the_title( '<h1 class="entry-title h3">', '</h1>' ); ?>
		</header><!-- .entry-header -->

		<div class="mb-2">

			<?php
			if (pp_is_user_role('pp_timesheet_approver')){
				get_template_part( '/template-parts/views/timesheets-timesheet-approver' );
			}
			else {
				echo '<h6 class="h6">We could not find this page or you do not have permission to access it.</h6>';
			}
			?>

		</div>

	</main><!-- #main -->


<?php
get_footer();
