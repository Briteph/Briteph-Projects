<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package My_Project_Partners
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="preload" href="/wp-content/themes/my-project-partners/fonts/Mont-Regular.woff2" as="font" type="font/woff2" crossorigin="anonymous">
	<link rel="preload" href="/wp-content/themes/my-project-partners/fonts/Mont-SemiBold.woff2" as="font" type="font/woff2" crossorigin="anonymous">

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-barba="wrapper">
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'my-project-partners' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="logomark mobile-only">
			<?php the_custom_logo(); ?>
		</div>

		<div id="page-title-wrapper" class="self-center desktop-only">
			<h1 id="page-title" class="h5 mb-0 mt-0 semibold"><?php the_title(); ?></h1>
		</div>

		<div id="search">
			<?php get_search_form(); ?>
		</div>

		<div id="profile-dropdown-wrapper" class="flex desktop-only">
			<div id="profile-dropdown" class="flex">
				
				<div class="avatar-wrap flex">
					<?php $user = wp_get_current_user(); ?>
					<?php echo get_avatar($user->id, 34); ?>
				</div>
				
				<span class="name-user self-center" id="name-user"><?php echo esc_html($user->first_name . ', ' . substr($user->last_name, 0, 1) . '.'); ?></span>
				
				<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" class="self-center">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M2.50411 4.83753C2.73191 4.60972 3.10126 4.60972 3.32906 4.83753L6.99992 8.50838L10.6708 4.83753C10.8986 4.60972 11.2679 4.60972 11.4957 4.83753C11.7235 5.06533 11.7235 5.43468 11.4957 5.66248L7.4124 9.74582C7.18459 9.97362 6.81525 9.97362 6.58744 9.74582L2.50411 5.66248C2.2763 5.43468 2.2763 5.06533 2.50411 4.83753Z" fill="#6F8699"/>
				</svg>

				<nav class="profile-dropdown-menu">
					<ul>
						<li>
							<a href="/account" class="flex dropdown-link">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M12 4C10.3431 4 9 5.34315 9 7C9 8.65685 10.3431 10 12 10C13.6569 10 15 8.65685 15 7C15 5.34315 13.6569 4 12 4ZM7 7C7 4.23858 9.23858 2 12 2C14.7614 2 17 4.23858 17 7C17 9.76142 14.7614 12 12 12C9.23858 12 7 9.76142 7 7Z" fill="#6F8699"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M4 19C4 16.2386 6.23858 14 9 14H15C17.7614 14 20 16.2386 20 19V21C20 21.5523 19.5523 22 19 22C18.4477 22 18 21.5523 18 21V19C18 17.3431 16.6569 16 15 16H9C7.34315 16 6 17.3431 6 19V21C6 21.5523 5.55228 22 5 22C4.44772 22 4 21.5523 4 21V19Z" fill="#6F8699"/>
								</svg>
								<span>Account</span>
							</a>
						</li>
						<li>
							<a href="<?php echo wp_logout_url('/login'); ?>" target="_blank" class="flex dropdown-link">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M8 5C6.89543 5 6 5.89543 6 7V17C6 18.1046 6.89543 19 8 19H13C13.5523 19 14 19.4477 14 20C14 20.5523 13.5523 21 13 21H8C5.79086 21 4 19.2091 4 17V7C4 4.79086 5.79086 3 8 3H13C13.5523 3 14 3.44772 14 4C14 4.55228 13.5523 5 13 5H8Z" fill="#6F8699"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M17.2929 16.7071C16.9024 16.3166 16.9024 15.6834 17.2929 15.2929L19.5858 13L11 13C10.4477 13 10 12.5523 10 12C10 11.4477 10.4477 11 11 11L19.5858 11L17.2929 8.70711C16.9024 8.31658 16.9024 7.68342 17.2929 7.29289C17.6834 6.90237 18.3166 6.90237 18.7071 7.29289L22.7071 11.2929C23.0976 11.6834 23.0976 12.3166 22.7071 12.7071L18.7071 16.7071C18.3166 17.0976 17.6834 17.0976 17.2929 16.7071Z" fill="#6F8699"/>
								</svg>
								<span>Log out</span>
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>

		<nav id="site-navigation" class="main-navigation mobile-only">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" type="button">
				<svg class="ham" viewBox="0 0 100 100" width="48">
					<path class="line top" d="m 70,33 h -40 c 0,0 -8.5,-0.149796 -8.5,8.5 0,8.649796 8.5,8.5 8.5,8.5 h 20 v -20" />
					<path class="line middle" d="m 70,50 h -40" />
					<path class="line bottom" d="m 30,67 h 40 c 0,0 8.5,0.149796 8.5,-8.5 0,-8.649796 -8.5,-8.5 -8.5,-8.5 h -20 v 20" />
				</svg>
			</button>
			<?php wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'mobile-menu',
				)
			); ?>
		</nav>
	</header><!-- #masthead -->
	
	<?php get_sidebar(); ?>

	<div id="app-content" class="wrap container" role="document" data-barba="container" data-barba-namespace="<?php echo pp_get_current_template() ?>">