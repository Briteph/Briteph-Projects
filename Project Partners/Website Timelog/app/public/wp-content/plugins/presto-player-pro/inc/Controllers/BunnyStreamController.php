<?php

namespace PrestoPlayer\Pro\Controllers;

use PrestoPlayer\Models\Setting;

class BunnyStreamController
{
    public $pullzone_controller;
    public $library_controller;

    /**
     * Set up controllers
     */
    public function __construct()
    {
        $this->library_controller = new BunnyVideoLibraryController();
        $this->pullzone_controller = new BunnyPullZoneController();
    }

    /**
     * Set up all necessary data for the bunny streams
     *
     * @param string $type public or private
     * @return void
     */
    public function setup($type)
    {
        // create or update library
        $library = $this->library_controller->createOrUpdate($type);
        if (is_wp_error($library)) {
            return $library;
        }

        // store library
        $stored = $this->storeLibrary($library, $type);
        if (is_wp_error($stored)) {
            return $stored;
        }

        // fetch pullzone
        $pullzone = $this->pullzone_controller->fetch($library->PullZoneId);
        if (is_wp_error($pullzone)) {
            return $pullzone;
        }

        // store security key
        Setting::set('bunny_stream_' . $type, 'token_auth_key', $pullzone->ZoneSecurityKey);

        // default hostname
        $hostname = $this->pullzone_controller->getDefaultHostname($pullzone);
        if (is_wp_error($hostname)) {
            return $hostname;
        }

        $stored = $this->storeHostname($hostname, $type);
        if (is_wp_error($stored)) {
            return $stored;
        }

        if (!$stored) {
            return new \WP_Error('error', 'Could not store hostname');
        }

        // purge cache?
        $this->pullzone_controller->purge($pullzone->Id);


        return true;
    }

    /**
     * Store result from rest api in settings
     *
     * @param string $type pubic or private
     * @param Object $result Response object
     * @return \WP_Error|true
     */
    protected function storeLibrary($library, $type)
    {
        // validate id
        if (empty($library->Id) || !is_int($library->Id)) {
            return new \WP_Error('invalid_id', 'The video library has an invalid id');
        }
        // validate api key
        if (empty($library->ApiKey)) {
            return new \WP_Error('missing_api_key', 'The video library is missing an api key.');
        }
        // validate pullzone id
        if (empty($library->PullZoneId) || !is_int($library->PullZoneId)) {
            return new \WP_Error('invalid_pull_zone_id', 'The video library has a missing or invalid pull zone id.');
        }

        // store data
        Setting::set('bunny_stream_' . $type, 'video_library_id', $library->Id);
        Setting::set('bunny_stream_' . $type, 'video_library_api_key', $library->ApiKey);
        Setting::set('bunny_stream_' . $type, 'pull_zone_id', $library->PullZoneId);

        return true;
    }

    /**
     * Store hostname url in settings
     *
     * @param string $url hostname url
     * @param string $type pubic or private
     * @return \WP_Error|true
     */
    public function storeHostname($url, $type)
    {
        // validate input
        if (empty($url)) {
            return new \WP_Error('missing_url', 'You must provide a hostname url to save.');
        }

        Setting::set('bunny_stream_' . $type, 'pull_zone_url', $url);

        return true;
    }
}
