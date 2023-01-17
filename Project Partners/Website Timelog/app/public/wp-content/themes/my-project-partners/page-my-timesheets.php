<?php
/**
 * Template Name: My Timesheets
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

		<?php
		if(get_field('maintenance')){
			echo '<section><h2 class="h2">Maintenance</h2>';
			echo '<p>We are currently working hard to make Time Machine more user-friendly.<br>Please check back later - sorry for the inconvenience.</p></section>';
		}
		else { ?>
		<section class="grid my-timesheets mb-2">

			<?php

				if (pp_is_user_role('pp_partner')){
					get_template_part( '/template-parts/views/timesheets-partner' );
				}
				else {
					echo '<h2 class="h4">We could not find this page or you do not have permission to access it.</h2>';
				}
			?>

		</section>
		<?php } ?>

	</main><!-- #main -->


<?php
get_footer();
