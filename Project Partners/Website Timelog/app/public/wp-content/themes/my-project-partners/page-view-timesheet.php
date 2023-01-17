<?php
/**
 * Template Name: View Timesheet
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
		// get the Scoro contact ID of the currently logged in WP user
		$contact_id = scoro_get_current_user_contact_id();

		// check if theres a valid task query string
		if ( $_GET['task'] && is_numeric( intval($_GET['task']) )) {

			// if the task query string is valid and sanitize it
			$task_id = sanitize_key( intval($_GET['task']) );
			
			// get specific task from scoro
			$task = scoro_get_task($task_id);

			// check if user has access to view this task
			if ( (pp_is_user_role('pp_timesheet_approver')) && $contact_id ) {
				// if user has access to view the task, list all the details
				$ratings = scoro_get_ratings_of_task($task_id, $contact_id);
				//var_dump($ratings);
				$accessible_timesheets = array_column($ratings, 'c_task');

				// calendar view
				get_template_part('/template-parts/views/single-timesheet/calendar', null, array(
					'task' => $task
				));
			}
 			else if(pp_is_user_role('pp_partner') && scoro_get_custom_field($task, 'c_partner') === scoro_get_current_user_partner_id()) {
				get_template_part('/template-parts/views/single-timesheet/calendar-partner', null, array(
					'task' => $task
				));
			}
			else {
				// if the user does not have access to view this task, bail
				?>
				<div class="no-access">
					<h1 class="h4">It seems like you are not the Project Manager for this task.</h1>
				</div>
			<?php
			}
		}
		else {
			// if there was no valid task query string passed, guide user to the timesheets list page
			echo '<h1 class="h4">Are you looking for your timesheet list?</h1>';
			echo '<a href="/timesheets/" class="button btn">Timesheets</a>';
		}
		?>


		</div>

	</main><!-- #main -->


<?php
get_footer();
