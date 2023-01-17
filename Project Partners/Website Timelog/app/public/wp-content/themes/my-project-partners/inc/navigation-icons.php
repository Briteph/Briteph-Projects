<?php
/**
 * Custom navigation icons
 *
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package My_Project_Partners
 */

add_filter('wp_nav_menu_objects', 'pp_add_nav_menu_icons', 10, 2);

function pp_add_nav_menu_icons( $items, $args ){
    
    foreach( $items as $item ){

        $icon = get_field('icon', $item);

        $svg = file_get_contents(get_attached_file($icon));

        if($icon) $item->title = '<div class="menu-icon">' . $svg . '</div><div class="menu-title">' . esc_html($item->title) . '</div>';
        
    }

    return $items;
}