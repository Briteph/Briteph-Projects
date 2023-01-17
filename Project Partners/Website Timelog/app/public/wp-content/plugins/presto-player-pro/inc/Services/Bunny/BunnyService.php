<?php

namespace PrestoPlayer\Pro\Services\Bunny;

use PrestoPlayer\Models\Setting;
use PrestoPlayer\Models\CurrentUser;
use PrestoPlayer\Pro\Support\Utility;
use PrestoPlayer\Pro\Models\Bunny\PullZone;
use PrestoPlayer\Pro\Models\Bunny\StorageZone;

class BunnyService
{
    public function register()
    {
        add_filter('presto_player_admin_block_script_options', [$this, 'jsVars']);
        add_filter('presto_player_admin_block_script_options', [$this, 'jsAdminVars']);
        add_filter('presto-settings-js-options', [$this, 'jsVars']);
        add_filter('presto-settings-block-js-options', [$this, 'jsVars']);
        add_filter('presto_player_rest_prepared_response_item', [$this, 'maybeSignURL']);

        // update user's videos meta when created or updated 
        add_action('presto_player_videos_created', [$this, 'updateUserVideos']);
        add_action('presto_player_videos_updated', [$this, 'updateUserVideos']);
    }

    /**
     * Adds a list of bunny.net ids to user meta
     * This is useful for limiting bunny.net results when fetched through the API
     *
     * @param \PrestoPlayer\Models\Video $video
     * @return void
     */
    public function updateUserVideos($video)
    {
        // get fresh instance of video
        $video->fresh();
        $video = $video->toObject();

        // // must be logged in
        if (!$user_id = get_current_user_id()) {
            return;
        }

        // must be bunny
        if (!isset($video->type) || 'bunny' !== $video->type) {
            return;
        }

        // must have external id
        if (empty($video->external_id)) {
            return;
        }

        $video_ids = (array) get_user_meta($user_id, 'presto_bunny_video_ids', true);
        $video_ids[] = $video->external_id;

        update_user_meta($user_id, 'presto_bunny_video_ids', array_filter(array_unique($video_ids)));
    }

    /**
     * Is bunny properly set up?
     */
    public function isSetup()
    {
        $setup = [
            'storage' => false,
            'stream' => false,
        ];

        $pull_zones = new PullZone();
        $storage_zones = new StorageZone();

        if (
            !empty($pull_zones['private_id']) &&
            !empty($pull_zones['private_hostname']) &&
            !empty($pull_zones['public_id']) &&
            !empty($pull_zones['public_hostname']) &&
            !empty($storage_zones['private_id']) &&
            !empty($storage_zones['public_id'])
        ) {
            $setup['storage'] = true;
        }

        $stream_public = Setting::get('bunny_stream_public', 'pull_zone_url');
        $stream_private = Setting::get('bunny_stream_private', 'pull_zone_url');
        if (!empty($stream_private) && !empty($stream_public)) {
            $setup['stream'] = true;
        }

        return $setup;
    }

    /**
     * Add is setup to script vars
     *
     * @param array $options
     * @return array
     */
    public function jsVars($options)
    {
        $options['isSetup']['bunny'] = $this->isSetup();
        $options['hls_start_level'] = Setting::get('bunny_stream', 'hls_start_level', 480);

        return $options;
    }

    public function jsAdminVars($options)
    {
        $options['bunny']['disable_legacy_storage'] = Setting::get('bunny_stream', 'disable_legacy_storage', false);
        return $options;
    }

    /**
     * Maybe send a signed url with a rest api response
     *
     * @param array $prepared
     * @return array
     */
    public function maybeSignURL($prepared)
    {
        // maybe sign url if it's private
        if ('bunny' === $prepared['type'] && strpos($prepared['src'], Setting::get('bunny_stream_private', 'pull_zone_url'))) {
            $prepared['src'] = self::signURL($prepared['src']);
        }
        return $prepared;
    }

    /**
     * Get security key based on stream or storage
     *
     * @param string $src
     * @return string
     */
    public static function getSecurityKey($src)
    {
        return Utility::isStream($src) ?
            Setting::get('bunny_stream_private', 'token_auth_key') : (new PullZone())->get('private_security_key');
    }

    /**
     * Sign a private url
     *
     * @param string $url
     * @return string
     */
    public static function signURL($url, $key = '')
    {
        if (!$key) {
            $key = self::getSecurityKey($url);
        }
        return (new URL($key))->sign($url);
    }
}
