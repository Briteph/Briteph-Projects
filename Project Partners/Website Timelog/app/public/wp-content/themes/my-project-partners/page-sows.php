<?php
/**
 * Template Name: SoW Library
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
		<?php echo '<h2 class="h4">Test</h2>'; ?>
			<?php
			if (pp_is_user_role('pp_partner')){
				
				// get all sows from scoro
				$all_sows = scoro_get_all_current_quotes();
				?>
				
				<div id="sows" class="card big">
					<div class="search-and-sort flex">
						<input id="sow-search" name="sow-search" class="search" placeholder="Search" type="text" />
						<button class="sort" data-sort="company">Sort by Company Name</button>
					</div>
					<table id="sow-table">
						<tr>
							<th>SoW ID</th>
							<th>Company Name</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Project Manager</th>
						</tr>
						<tbody class="list">
							<?php 
							if($all_sows){
							foreach($all_sows as $sow) { ?>
							<tr>
								<td class="sow"><?php echo esc_html(scoro_get_custom_field($sow, 'c_sow')); ?>
									<button class="icon-button" data-sow="<?php echo esc_attr(scoro_get_custom_field($sow, 'c_sow')); ?>" type="button" aria-label="Copy SoW ID to the clipboard">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M8 7V15C8 16.1046 8.89543 17 10 17H16M8 7V5C8 3.89543 8.89543 3 10 3H14.5858C14.851 3 15.1054 3.10536 15.2929 3.29289L19.7071 7.70711C19.8946 7.89464 20 8.149 20 8.41421V15C20 16.1046 19.1046 17 18 17H16M8 7H6C4.89543 7 4 7.89543 4 9V19C4 20.1046 4.89543 21 6 21H14C15.1046 21 16 20.1046 16 19V17" stroke="#6E55FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</button>
								</td>
								<td class="company"><?php echo esc_html($sow->company_name); ?></td>
								<td class="start"><?php echo esc_html(scoro_get_custom_field($sow, 'c_servicescommence')); ?></td>
								<td class="end"><?php echo esc_html(scoro_get_custom_field($sow, 'c_servicescomplete')); ?></td>
								<td class="contact"><?php echo esc_html($sow->person_name); ?></td>
							</tr>
							<?php } 
							}
							?>
						</tbody>
					</table>
				</div>

			<?php
			}
			else {
				echo '<h2 class="h4">We could not find this page or you do not have permission to access it.</h2>';
			}
			?>

		</section>

	</main><!-- #main -->


<?php
get_footer();
