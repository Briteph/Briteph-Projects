<?php

namespace PrestoPlayer\Services;

use PrestoPlayer\Plugin;
use PrestoPlayer\Models\Block;
use PrestoPlayer\Models\Setting;
use PrestoPlayer\WPackio\Enqueue;


class Scripts
{
    public $enqueue;

    public function __construct()
    {
        $this->enqueue = new Enqueue(
            'prestoPlayer',
            'dist',
            Plugin::version(),
            'plugin',
            PRESTO_PLAYER_PLUGIN_FILE
        );
    }

    /**
     * Register scripts used throughout the plugin
     *
     * @return void
     */
    public function register()
    {
        add_action('enqueue_block_assets', [$this, 'registerPrestoComponents']);
        add_action('enqueue_block_editor_assets', [$this, 'registerPrestoComponents']);
        add_action('wp_enqueue_scripts', [$this, 'registerPrestoComponents']);
        add_action('admin_init', [$this, 'registerPrestoComponents']);
        add_filter('script_loader_tag', [$this, 'prestoComponentsTag'], 10, 3);

        // block assets
        add_action('enqueue_block_editor_assets', [$this, 'blockEditorAssets']);
        add_action('enqueue_block_assets', [$this, 'blockAssets']);

        add_action('admin_enqueue_scripts', [$this, 'learndashAdminScripts']);

        // elementor editor scripts
        add_action('elementor/frontend/before_enqueue_scripts', [$this, 'elementorPreviewScripts']);
        add_action('elementor/frontend/before_enqueue_scripts', [$this, 'blockAssets']);

        // admin pages
        add_action("admin_print_scripts-presto-player_page_presto_license", [$this, 'licenseScripts']);
        add_action("presto_player_pro_register_license_page", [$this, 'licenseScripts']);
    }

    public function learndashAdminScripts($hook_suffix)
    {
        global $post_type;

        // must be on learndash page
        if (!in_array($post_type, ['sfwd-lessons', 'sfwd-topic'])) {
            return;
        }

        // must be on new post page
        if (!in_array($hook_suffix, array('post.php', 'post-new.php'))) {
            return;
        }

        $this->enqueue->enqueue('integrations', 'learndash', ['js_dep' => ['jquery']]);
    }

    /**
     * Preload components to increase performance
     */
    public function preloadComponents()
    {
        /**
         * Get base file needed for components
         */
        $file_contents = file_get_contents(PRESTO_PLAYER_PLUGIN_DIR . "dist/components/web-components/web-components.esm.js");
        preg_match('/\.\\/p\-(.*)\.js/', $file_contents, $matches);

        // get entry file
        if (!empty($matches[0])) {
            $file = str_replace('./', '', $matches[0]);
            echo "<link rel='modulepreload' href='" . esc_url_raw(PRESTO_PLAYER_PLUGIN_URL . "dist/components/web-components/" . $file) . "' as='script' />\n"; // base
        }

        /**
         * Get entry files
         */
        $files = scandir(PRESTO_PLAYER_PLUGIN_DIR . "dist/components/web-components/");
        foreach ($files as $file) {
            if (strpos($file, '.entry.js') !== false) {
                echo "<link rel='modulepreload' href='" .  esc_url_raw(PRESTO_PLAYER_PLUGIN_URL . "dist/components/web-components/" . $file) . "' as='script' />\n"; // list
            }
        }

        /**
         * Web components loader
         */
        echo "<link rel='modulepreload' href='" .  esc_url_raw(PRESTO_PLAYER_PLUGIN_URL . "dist/components/web-components/web-components.esm.js") . "?ver=" . filemtime(PRESTO_PLAYER_PLUGIN_DIR . "dist/components/web-components/web-components.esm.js") . "' as='script' />";
    }

    /**
     * Add a type="module" to our components tag to lazy load them
     */
    public function prestoComponentsTag($tag, $handle, $source)
    {
        if ('presto-components' === $handle) {
            $tag = '<script src="' . $source . '" type="module" defer></script>';
        }
        if ('presto-components-static' === $handle) {
            $tag = '<script src="' . $source . '" defer></script>';
        }

        return $tag;
    }

    /**
     * Register our components
     */
    public function registerPrestoComponents()
    {
        $deps = [
            'jquery',
            'wp-hooks',
            'wp-i18n'
        ];

        wp_register_script(
            'presto-components',
            PRESTO_PLAYER_PLUGIN_URL . "dist/components/web-components/web-components.esm.js",
            $deps,
            filemtime(PRESTO_PLAYER_PLUGIN_DIR . "dist/components/web-components/web-components.esm.js"),
            true
        );

        wp_register_script(
            'presto-components-static',
            PRESTO_PLAYER_PLUGIN_URL . "src/player/player-static.js",
            $deps,
            filemtime(PRESTO_PLAYER_PLUGIN_DIR . "src/player/player-static.js"),
            true
        );

        wp_register_script(
            'hls.js',
            PRESTO_PLAYER_PLUGIN_URL . 'src/libraries/hls.min.js',
            [],
            '0.14.17',
            true
        );
    }

    /**
     * Elementor scripts (needed speifically on preview pages)
     */
    public function elementorPreviewScripts()
    {
        if (!isset($_GET['elementor-preview'])) {
            return;
        }

        $assets = $this->enqueue->enqueue('elementor', 'editor', ['js_dep' => ['jquery', 'hls.js']]);
        $entry_point = array_pop($assets['js']);
        wp_localize_script(
            $entry_point['handle'],
            'prestoEditorData',
            [
                'proVersion' => Plugin::proVersion(),
                'isPremium' => Plugin::isPro(),
                'root' => esc_url_raw(get_rest_url()),
                'nonce' => wp_create_nonce('wp_rest'),
                'wpVersionString' => 'wp/v2/',
                'siteURL' => esc_url_raw(untrailingslashit(get_site_url(get_current_blog_id()))),
            ]
        );
    }

    public function getAssets()
    {
        return $this->enqueue->getAssets(func_get_args());
    }

    /**
     * Block Editor Assets
     *
     * @return void
     */
    public function blockEditorAssets()
    {
        if (!is_admin()) {
            return;
        }

        $assets = $this->enqueue->enqueue('blocks', 'admin', [
            'js_dep' => ['wp-element', 'wp-components', 'wp-block-editor', 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-polyfill', 'wp-api', 'wp-hooks', 'presto-components', 'hls.js'],
        ]);

        $entry_point = array_pop($assets['js']);

        wp_localize_script(
            $entry_point['handle'],
            'prestoPlayer',
            apply_filters(
                'presto_player_admin_script_options',
                [
                    'plugin_url' => esc_url_raw(trailingslashit(PRESTO_PLAYER_PLUGIN_URL)),
                    'root' => esc_url_raw(get_rest_url()),
                    'nonce' => wp_create_nonce('wp_rest'),
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'isAdmin' => is_admin(),
                    'proVersion' => Plugin::proVersion(),
                    'isPremium' => Plugin::isPro(),
                    'isSetup' => [
                        'bunny' => false
                    ],
                    'wpVersionString' => 'wp/v2/',
                    'prestoVersionString' => 'presto-player/v1/',
                    'defaults' => [
                        'color' => Setting::getDefaultColor()
                    ],
                    'i18n' => Translation::geti18n()
                ]
            )
        );

        if (function_exists('wp_set_script_translations')) {
            wp_set_script_translations($entry_point['handle'], 'presto-player');
        }

        wp_localize_script(
            $entry_point['handle'],
            'prestoPlayerAdmin',
            apply_filters(
                'presto_player_admin_block_script_options',
                [
                    'root' => esc_url_raw(get_rest_url()),
                    'nonce' => wp_create_nonce('wp_rest'),
                    'logged_in' => is_user_logged_in(),
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'wp_max_upload_size' => wp_max_upload_size(),
                    'isAdmin' => is_admin(),
                    'proVersion' => Plugin::proVersion(),
                    'isPremium' => Plugin::isPro(),
                    'isSetup' => [
                        'bunny' => false
                    ],
                    'wpVersionString' => 'wp/v2/',
                    'prestoVersionString' => 'presto-player/v1/',
                    'defaults' => [
                        'color' => Setting::getDefaultColor()
                    ]
                ]
            )
        );
    }

    /**
     * Does the page have a player?
     */
    public function hasPlayer()
    {
        // global is the most reliable between page builders
        global $load_presto_js;
        if ($load_presto_js) {
            return true;
        }

        // must be a singular page
        if (!is_singular()) {
            return false;
        }

        $id = get_the_ID();

        // change to see if we have one of our blocks
        $types = Block::getBlockTypes();
        foreach ($types as $type) {
            if (has_block($type, $id)) {
                return true;
            }
        }

        // check for data-presto-config (player rendered)
        $wp_post = get_post($id);
        if ($wp_post instanceof \WP_Post) {
            $post = $wp_post->post_content;
        }
        $has_player = false !== strpos($post, 'data-presto-config');
        if ($has_player) {
            return true;
        }

        // check that we have a shortcode
        if (has_shortcode($post, 'presto_player')) {
            return true;
        }

        // enable on Elementor
        if (!empty($_GET['action']) && 'elementor' === $_GET['action']) {
            return true;
        }
        if (isset($_GET['elementor-preview'])) {
            return true;
        }

        // load for beaver builder
        if (isset($_GET['fl_builder'])) {
            return true;
        }

        // tutor LMS
        global $post;
        if (!empty($post->post_type) && $post->post_type) {
            if (defined('TUTOR_VERSION') && 'lesson' === $post->post_type) {
                return true;
            }
        }

        // load for Divi builder
        if (isset($_GET['et_fb'])) {
            return true;
        }

        // do we have the player
        return $has_player;
    }

    /**
     * Add global player styles inline.
     *
     * @return void
     */
    public function globalStyles()
    { ?>
        <style>
            <?php readfile(PRESTO_PLAYER_PLUGIN_DIR . 'src/player/global.css'); ?>
        </style>
<?php }

    public function loadJavascript()
    {
        $module_load = Setting::get('performance', 'module_enabled');

        // global styles
        if (!wp_doing_ajax() && !defined('REST_REQUEST') && !defined('PRESTO_TESTSUITE')) {
            $this->globalStyles();
        }

        // direct load
        if ($module_load) {
            $handle = 'presto-components';
            // preload components
            add_action('wp_head', [$this, 'preloadComponents']);
        } else {
            $handle = 'presto-components-static';
            wp_localize_script($handle, 'prestoComponents', [
                'url' => PRESTO_PLAYER_PLUGIN_URL . "dist/components/web-components/web-components.esm.js?ver=" . filemtime(PRESTO_PLAYER_PLUGIN_DIR . "dist/components/web-components/web-components.esm.js")
            ]);
        }

        wp_enqueue_script($handle);

        return $handle;
    }

    /**
     * Block frontend assets
     *
     * @return void
     */
    public function blockAssets()
    {
        // don't output if it doesn't have our block
        if (!apply_filters('presto_player_load_js', $this->hasPlayer())) {
            return;
        }

        $handle = $this->loadJavascript();

        // translations
        if (function_exists('wp_set_script_translations')) {
            wp_set_script_translations($handle, 'presto-player');
        }

        // fallback styles and script to load iframes
        add_action('wp_footer', function () {
            if (is_admin()) return;
            if (!apply_filters('presto_player/scripts/load_iframe_fallback', false)) return;
            $this->printFallbackScriptsAndStyles();
        });

        wp_localize_script(
            $handle,
            'prestoPlayer',
            apply_filters('presto-settings-block-js-options', [
                'plugin_url' => esc_url_raw(trailingslashit(PRESTO_PLAYER_PLUGIN_URL)),
                'logged_in' => is_user_logged_in(),
                'root' => esc_url_raw(get_rest_url()),
                'nonce' => wp_create_nonce('wp_rest'),
                'ajaxurl' => admin_url('admin-ajax.php'),
                'isAdmin' => is_admin(),
                'isSetup' => [
                    'bunny' => false
                ],
                'proVersion' => Plugin::proVersion(),
                'isPremium' => Plugin::isPro(),
                'wpVersionString' => 'wp/v2/',
                'prestoVersionString' => 'presto-player/v1/',
                'debug' => defined('SCRIPT_DEBUG') && SCRIPT_DEBUG,
                'debug_navigator' => defined('PRESTO_DEBUG_NAVIGATOR') && PRESTO_DEBUG_NAVIGATOR,
                'i18n' => Translation::geti18n(),
            ])
        );
    }

    public function licenseScripts($hook)
    {
        add_action("admin_print_scripts-{$hook}", function () {
            $this->enqueue->enqueue('license', 'admin', []);
        });
    }

    public function printFallbackScriptsAndStyles()
    {
        /*
        * This CSS is duplicated in 'packages/components/src/components/core/player/presto-player/presto-player.scss'
        */
        echo '<style>.presto-iframe-fallback-container{position:relative;padding-bottom:56.25%;padding-top:30px;height:0;overflow:hidden}.presto-iframe-fallback-container embed,.presto-iframe-fallback-container iframe,.presto-iframe-fallback-container object{position:absolute;top:0;left:0;width:100%;height:100%}</style>';
        echo '<script defer>
                window.addEventListener("load", function(event) {
                    setTimeout(function() {
                        var deferVideo = document.getElementsByClassName("presto-fallback-iframe");
                        if (!deferVideo.length) return;
                        Array.from(deferVideo).forEach(function(video) {
                            video && video.setAttribute("src", video.getAttribute("data-src"));
                        });
                    }, 2000);
                }, false);
            </script>';
    }
}
