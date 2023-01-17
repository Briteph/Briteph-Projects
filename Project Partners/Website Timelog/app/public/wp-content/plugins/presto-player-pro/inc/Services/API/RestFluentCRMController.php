<?php

namespace PrestoPlayer\Pro\Services\API;

use FluentCrm\App\Models\Tag;
use FluentCrm\App\Models\Lists;
use PrestoPlayer\Pro\Support\RestEmailProviderController;

class RestFluentCRMController extends RestEmailProviderController
{
    protected $namespace = 'presto-player';
    protected $version = 'v1';
    protected $base = 'fluentcrm';

    public function register()
    {
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }

    public function registerRoutes()
    {
        register_rest_route("$this->namespace/$this->version/$this->base", '/connect', [
            'methods' => 'POST',
            'callback' => [$this, 'connect'],
            'permission_callback' => [$this, 'connect_permissions_check']
        ]);

        register_rest_route("$this->namespace/$this->version/$this->base", '/disconnect', [
            'methods' => 'POST',
            'callback' => [$this, 'disconnect'],
            'permission_callback' => [$this, 'connect_permissions_check'],
        ]);

        register_rest_route("$this->namespace/$this->version/$this->base", '/lists', [
            'methods' => 'GET',
            'callback' => [$this, 'lists'],
            'permission_callback' => [$this, 'permissions_check'],
        ]);

        register_rest_route("$this->namespace/$this->version/$this->base", '/tags', [
            'methods' => 'GET',
            'callback' => [$this, 'tags'],
            'permission_callback' => [$this, 'permissions_check'],
        ]);
    }

    /**
     * Only for users who can manage options
     *
     * @return void
     */
    public function permissions_check()
    {
        return current_user_can('manage_options');
    }

    /**
     * Download and install Fluent CRM
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function connect($request)
    {
        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        // if exists and not activated, activate it
        if (file_exists(WP_PLUGIN_DIR . '/fluent-crm/fluent-crm.php')) {
            $activated = activate_plugin('fluent-crm/fluent-crm.php');
            if (is_wp_error($activated)) {
                return $activated;
            }
            return rest_ensure_response([
                'connected' => is_plugin_active('fluent-crm/fluent-crm.php')
            ]);
        }

        // seems like the plugin doesn't exists. Download and activate it
        $upgrader = new \Plugin_Upgrader(new \WP_Ajax_Upgrader_Skin());
        $api      = plugins_api('plugin_information', array('slug' => 'fluent-crm', 'fields' => array('sections' => false)));
        $result   = $upgrader->install($api->download_link);

        if (is_wp_error($result)) {
            return $result;
        }

        $activated = activate_plugin('fluent-crm/fluent-crm.php');
        if (is_wp_error($activated)) {
            return $activated;
        }
        return rest_ensure_response([
            'connected' => is_plugin_active('fluent-crm/fluent-crm.php')
        ]);
    }

    public function disconnect()
    {
        $activated = deactivate_plugins('fluent-crm/fluent-crm.php');
        if (is_wp_error($activated)) {
            return $activated;
        }
        return rest_ensure_response([
            'connected' => is_plugin_active('fluent-crm/fluent-crm.php')
        ]);
    }

    /**
     * Connect to the mailchimp api and store settings
     *
     * @return WP_Error|WP_REST_Response
     */
    public function lists()
    {
        if (!$this->connected()) {
            return [];
        }
        return rest_ensure_response(Lists::all());
    }


    /**
     * Connect to the mailchimp api and store settings
     *
     * @return WP_Error|WP_REST_Response
     */
    public function tags()
    {
        if (!$this->connected()) {
            return [];
        }
        return rest_ensure_response(Tag::all());
    }
}
