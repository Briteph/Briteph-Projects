<?php

namespace PrestoPlayer\Pro\Services;

/**
 * Install core plugin if not installed/activated
 */
class CoreInstaller
{

    private $slug = 'presto-player';
    public $plugin = 'presto-player/presto-player.php';

    /**
     * Maybe install the core plugin
     *
     * @return void
     */
    public function maybeInstallCore()
    {
        if (is_plugin_active($this->plugin)) {
            return;
        }
        if (!current_user_can('install_plugins')) {
            return;
        }
        $this->installPlugin();
    }

    /**
     * Install the plugin from the source
     */
    public function installPlugin()
    {
        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        // if exists and not activated, activate it
        if (file_exists(WP_PLUGIN_DIR . '/' . $this->plugin)) {
            return activate_plugin($this->plugin);
        }

        // seems like the plugin doesn't exists. Download and activate it
        $upgrader = new \Plugin_Upgrader(new \WP_Ajax_Upgrader_Skin());

        $api      = plugins_api('plugin_information', array('slug' => $this->slug, 'fields' => array('sections' => false)));
        $result   = $upgrader->install($api->download_link);

        if (is_wp_error($result)) {
            return $result;
        }

        return activate_plugin($this->plugin);
    }
}
