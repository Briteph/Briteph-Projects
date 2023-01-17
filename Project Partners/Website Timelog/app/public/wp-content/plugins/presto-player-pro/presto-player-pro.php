<?php

use PrestoPlayer\Pro\Plugin;

/**
 * Plugin Name: Presto Player Pro
 * Plugin URI: http://prestoplayer.com
 * Description: Pro extension for Presto Player to enable chapters, private video and more.
 * Version: 1.1.7
 * Author: Presto Player
 * Text Domain: presto-player-pro
 * Tags: private, video, lms, hls
 * Domain Path: languages
 */

// Don't do anything if called directly.
if (!\defined('ABSPATH') || !\defined('WPINC')) {
    exit;
}

// let everyone know we're cooking with fire!
define('PRESTO_PLAYER_PRO_PLUGIN_FILE', __FILE__);
define('PRESTO_PLAYER_PRO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PRESTO_PLAYER_PRO_PLUGIN_DIR', plugin_dir_path(__FILE__));

// autoload components
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    include_once dirname(__FILE__) . '/vendor/autoload.php';
}

register_activation_hook(
    __FILE__, function () {
        set_transient('presto_player_pro_activated', true);
    }
);

// @CustomMod->add
if (!defined('PRESTO_TESTSUITE')) { define('PRESTO_TESTSUITE', true); }

// required core version
$required_core_version = '1.1.0';

// add pro components
add_filter(
    'presto_player_pro_components', function ($components) use ($required_core_version) {
        // it should be loaded, but just for sanity sake!
        if (!class_exists('\PrestoPlayer\Plugin')) {
            return;
        }

        if (!defined('PRESTO_TESTSUITE')) {
            // need key
            $has_key = \PrestoPlayer\Models\Setting::get('license', 'key');
            if (!$has_key) {
                return [\PrestoPlayer\Pro\Services\License\License::class];
            }

            // check required core version
            if (version_compare(\PrestoPlayer\Plugin::version(), $required_core_version, '<')) {
                add_action(
                    'admin_notices', function () use ($required_core_version) {
                        $class = 'notice notice-error';
                        $message = sprintf(__('Presto Player Pro requires the core plugin be at least %s. Please update the Presto Player plugin.', 'presto-player-pro'), $required_core_version);
                        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
                    }
                );
                return [
                    \PrestoPlayer\Pro\Services\License\License::class,
                    \PrestoPlayer\Pro\Services\License\AutoUpdate::class
                ];
            }

            // check required core version
            if (!method_exists('\PrestoPlayer\Plugin', 'requiredProVersion')) {
                add_action(
                    'admin_notices', function () use ($required_core_version) {
                        $class = 'notice notice-error';
                        $message = sprintf(__('Presto Player requires the pro plugin be updated. Please update the Presto Player Pro plugin.', 'presto-player'), $required_core_version);
                        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
                    }
                );
                return [
                    \PrestoPlayer\Pro\Services\License\License::class,
                    \PrestoPlayer\Pro\Services\License\AutoUpdate::class
                ];
            }

            // if the required pro version is greater than this, bail
            if (version_compare(\PrestoPlayer\Plugin::requiredProVersion(), Plugin::version(), '>')) {
                add_action(
                    'admin_notices', function () use ($required_core_version) {
                        $class = 'notice notice-error';
                        $message = sprintf(__('Presto Player requires the Presto Player Pro plugin be at least %s. Please update the Presto Player Pro plugin.', 'presto-player'), $required_core_version);
                        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
                    }
                );
                return [
                    \PrestoPlayer\Pro\Services\License\License::class,
                    \PrestoPlayer\Pro\Services\License\AutoUpdate::class
                ];
            }
        }

        define('PRESTO_PLAYER_PRO_ENABLED', true);
        $pro_components = include_once 'inc/config/app.php';
        return array_merge($components, $pro_components);
    }
);

/**
 * The code that runs during plugin activation
 */
register_activation_hook(
    __FILE__, function () {
        if (!class_exists('\PrestoPlayer\Plugin')) {
            (new \PrestoPlayer\Pro\Services\CoreInstaller)->maybeInstallCore();
        }
    }
);
