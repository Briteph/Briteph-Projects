<?php

namespace PrestoPlayer\Pro;

class Plugin
{
    /**
     * Get the version from plugin data
     *
     * @return void
     */
    public static function version()
    {
        // Load version from plugin data.
        if (!\function_exists('get_plugin_data')) {
            require_once \ABSPATH . 'wp-admin/includes/plugin.php';
        }

        return \get_plugin_data(PRESTO_PLAYER_PRO_PLUGIN_FILE, false, false)['Version'];
    }
}
