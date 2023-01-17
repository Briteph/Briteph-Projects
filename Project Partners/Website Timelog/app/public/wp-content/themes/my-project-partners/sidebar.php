<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package My_Project_Partners
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="sidebar" class="widget-area desktop-only">
	<div class="logomark">
		<?php the_custom_logo(); ?>
		<a href="<?php echo site_url(); ?>" class="large-only large-logo block" rel="home" aria-current="page"><img src="<?php echo esc_url( str_replace( 'http://', 'https://', get_theme_mod( 'pp_wide_logo' ) ) ); ?>" class="custom-logo" alt="Project Partners"></a>
	</div>
	<nav id="sidebar-nav">	
		<?php wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
					)
				); ?>
	</nav>
</aside><!-- #sidebar -->
