<?php

namespace PrestoPlayer\Pro\Support;

use PrestoPlayer\Models\Setting;

abstract class RestEmailProviderController
{
    protected $base = '';

    /**
     * Is fluent connected
     *
     * @return void
     */
    public function connected()
    {
        return (bool) Setting::get($this->base, 'connected');
    }

    /**
     * Connect permissions check
     *
     * @return boolean
     */
    public function connect_permissions_check()
    {
        return current_user_can('manage_options');
    }

    /**
     * Only for users who can manage options
     *
     * @return \WP_Error|boolean
     */
    public function permissions_check()
    {
        if (!$this->connected()) {
            return new \WP_Error('not_connected', __('Please connect this service to use this integration.'), ['status' => 501]);
        }

        return $this->connect_permissions_check();
    }
}
