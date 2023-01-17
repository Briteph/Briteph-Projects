<?php

namespace PrestoPlayer\Pro\Controllers;

use PrestoPlayer\Models\Setting;
use PrestoPlayer\Pro\Libraries\BunnyApiRequest;

class BunnyPullZoneController
{
    public function getClient()
    {
        return new BunnyApiRequest(Setting::get('bunny_keys', 'api_key'));
    }

    /**
     * Fetch zone from the Bunny.net API
     *
     * @param integer $id
     * @return Object Response object.
     */
    public function fetch($id)
    {
        $pullzone = $this->getClient()->get("pullzone/$id");

        if (is_wp_error($pullzone)) {
            return $pullzone;
        }

        return $pullzone;
    }

    /**
     * Purge pullzone cache
     *
     * @param integer $id
     * @return \WP_Error|true
     */
    public function purge($id)
    {
        $pullzone = $this->getClient()->post("pullzone/$id/purgeCache");

        if (is_wp_error($pullzone)) {
            return $pullzone;
        }

        return true;
    }

    public function getDefaultHostname($pullzone)
    {
        if (empty($pullzone->Hostnames)) {
            return new \WP_Error('missing_hostnames', 'There are no hostnames for this pullzone');
        }

        $default = '';
        foreach ($pullzone->Hostnames as $hostname) {
            if (!empty($hostname->IsSystemHostname) && !empty($hostname->Value)) {
                $default = $hostname->Value;
            }
        }

        return $default;
    }

    /**
     * Fetch default zone Hostname (url) from the Bunny.net API
     *
     * @param integer $id
     * @return Object Response object.
     */
    public function fetchDefaultHostname($id)
    {
        $pullzone = $this->fetch($id);

        return $this->getDefaultHostname($pullzone);
    }
}
