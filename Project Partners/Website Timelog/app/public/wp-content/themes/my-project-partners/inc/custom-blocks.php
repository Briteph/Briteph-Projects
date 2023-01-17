<?php

/**
 * Register Custom Gutenberg Blocks with Advanced Custom Fields PRO
 *
 */

add_action('acf/init', 'my_project_partners_acf_blocks_init');
function my_project_partners_acf_blocks_init()
{

    // Check function exists.
    if (function_exists('acf_register_block_type')) {
        // Register an Toggle block
        acf_register_block_type(array(
            'name'              => 'toggle',
            'title'             => __('Toggle'),
            'description'       => __('Create accordion/toggle blocks to answer frequently asked questions.'),
            'render_template'   => 'template-parts/blocks/toggle/toggle.php',
            'category'          => 'common',
            'icon'              => 'editor-help',
            'keywords'          => array('faq', 'frequently asked questions', 'toggle', 'accordion', 'card'),
            // 'enqueue_script' => get_stylesheet_directory_uri() . '/template-parts/blocks/toggle/toggle.js?v=1.0.0',
        ));
    }
}