<?php

namespace PrestoPlayer\Pro\Controllers;

use PrestoPlayer\Models\Setting;
use PrestoPlayer\Pro\Libraries\BunnyApiRequest;

class BunnyVideoLibraryController
{
    public function getClient()
    {
        return new BunnyApiRequest(Setting::get('bunny_keys', 'api_key'));
    }

    /**
     * Create or update the video library
     *
     * @param string $type Either 'public' or 'private'
     * @return Object Response object
     */
    public function createOrUpdate($type)
    {
        if (!in_array($type, ['public', 'private'])) {
            return new \WP_Error('invalid_library_type', 'Library must be either public or private');
        }

        // get library id stored in database
        $library_id = Setting::get('bunny_stream_' . $type, 'video_library_id');

        // create the library if it doesn't yet exist
        if (!$library_id) {
            return $this->createAndUpdate($type);
        }

        // first check to see if it exists on bunny.net
        // since updating won't show 404 if it has been deleted
        $exists = $this->exists($library_id);
        if (is_wp_error($exists)) {
            return $exists;
        }

        // create it if it doesnt exist
        if (!$exists) {
            Setting::delete('bunny_stream_' . $type, 'video_library_id');
            return $this->createAndUpdate($type);
        }

        // otherwise update library
        return $this->update($type, $library_id);
    }

    /**
     * Create and update library
     *
     * @param string $type
     * @return Object|\WP_Error
     */
    public function createAndUpdate($type)
    {
        $library = $this->create($type);

        if (is_wp_error($library)) {
            return $library;
        }

        if (empty($library->Id)) {
            return new \WP_Error('missing_id', 'The library could not be created');
        }

        return $this->update($type, $library->Id);
    }

    /**
     * Does the library exist on the server
     *
     * @param integer $id
     * @return boolean|\WP_Error
     */
    public function exists($id)
    {
        $result = $this->getClient()->get("videolibrary/$id");

        if (is_wp_error($result)) {
            $data = $result->get_error_data();
            if (404 === $data['status']) {
                return false;
            }
            return $result;
        }

        return true;
    }

    /**
     * Create a video library
     *
     * @param string $type Either 'public' or 'private'
     * @return Object Response object
     */
    public function create($type)
    {
        $result = $this->getClient()->post('videolibrary', [
            'body' => [
                'Name' => get_bloginfo('name') . ' (Presto ' . ucfirst($type) . ')',
            ]
        ]);

        // bail on error
        if (is_wp_error($result)) {
            return $result;
        }

        return $result;
    }

    /**
     * Update a video library
     *
     * @param string $type Either 'public' or 'private'
     * @param integer $id Video library id to update
     * @return Object Response object
     */
    public function update($type, $id, $args = [])
    {
        if (!$id) {
            return new \WP_Error('invalid_library_id', 'You must set a library id to update.');
        }

        $args = wp_parse_args($args, [
            'AllowEarlyPlay' => false,
            'EnableTokenAuthentication' => $type === 'private' ? true : false,
            'EnableTokenIPVerification' => false,
            "KeepOriginalFiles" => false,
            // 'AllowedReferrers' => "",
            'EnableMP4Fallback' => false,
            'AllowDirectPlay' => false,
            // 'EnableDRM' => false, // bunny, fix your shit
            'BlockNoneReferrer' => true
        ]);

        $result = $this->getClient()->post("videolibrary/$id", [
            'body' => $args
        ]);

        if (is_wp_error($result)) {
            // deleted server-side, reset
            Setting::delete('bunny_stream_' . $type, 'video_library_id');
            return $this->createOrUpdate($type);
        }

        return $result;
    }
}
