<?php
/**
 * Template Name: My account
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_Project_Partners
 */

get_header();

?>

	<main id="primary" class="site-main">

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php my_project_partners_post_thumbnail(); ?>

			<div class="entry-content">
				<div class="card large" id="pp-account">
					<header class="entry-header">
						<h1 class="entry-title h4">Manage your account</h1>
					</header><!-- .entry-header -->
					<?php
					the_content();
					?>
				</div>
			</div><!-- .entry-content -->

			
		</article><!-- #post-<?php the_ID(); ?> -->

	</main><!-- #main -->


<?php
get_footer();
