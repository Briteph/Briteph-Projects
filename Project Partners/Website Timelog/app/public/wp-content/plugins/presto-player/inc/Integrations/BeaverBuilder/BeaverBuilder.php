<?php

namespace PrestoPlayer\Integrations\BeaverBuilder;

use PrestoPlayer\Models\ReusableVideo;
use PrestoPlayer\Integrations\BeaverBuilder\ReusableVideoModule\Module;


class BeaverBuilder
{
    public function register()
    {
        add_action('init', [$this, 'module']);
        add_action('wp_ajax_presto_fetch_videos', [$this, 'fetchVideos']);
    }

    /**
     * Register module
     *
     * @return void
     */
    public function module()
    {
        if (!class_exists('\FLBuilder')) {
            return;
        }

        define('PRESTO_PLAYER_BB_DIR', plugin_dir_path(__FILE__));
        define('PRESTO_PLAYER_BB_URL', plugins_url('/', __FILE__));


        \FLBuilder::register_module(Module::class, Module::getSettings());
    }

    /**
     * Fetch videos for dynamic
     *
     * @return void
     */
    public function fetchVideos()
    {
        // verify nonce
        wp_verify_nonce('presto_bb_nonce');

        // need to edit posts
        if (!current_user_can('edit_posts')) {
            wp_send_json_error();
        }

        $args = [];

        if(!empty($_POST['search'])){
          $args['s'] = sanitize_text_field($_POST['search']);
        }

        if(!empty($_POST['post_id'])){
          $args['post__in'][0] = sanitize_text_field($_POST['post_id']); // Convert single post_id into array.
        }

        $videos = (new ReusableVideo())->fetch($args);

        wp_send_json_success($videos);
    }
}
