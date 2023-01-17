<?php

namespace PrestoPlayer\Pro\Services\API;

use PrestoPlayer\Models\Setting;
use PrestoPlayer\Pro\Support\RestEmailProviderController;
use PrestoPlayer\Pro\Libraries\MailerLiteRequest;

class RestMailerLiteController extends RestEmailProviderController
{
    protected $namespace = 'presto-player';
    protected $version = 'v1';
    protected $base = 'mailerlite';
    protected $client;

    public function register()
    {
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }

    public function registerRoutes()
    {
        register_rest_route("$this->namespace/$this->version/$this->base", '/connect', [
            'methods' => 'POST',
            'callback' => [$this, 'connect'],
            'permission_callback' => [$this, 'connect_permissions_check'],
            'args' => [
                'api_key' => [
                    'description' => __('Your API key'),
                    'type'        => 'string',
                    'required'    => true
                ],
            ],
        ]);

        register_rest_route("$this->namespace/$this->version/$this->base", '/disconnect', [
            'methods' => 'POST',
            'callback' => [$this, 'disconnect'],
            'permission_callback' => [$this, 'connect_permissions_check'],
        ]);

        register_rest_route("$this->namespace/$this->version/$this->base", '/groups', [
            'methods' => 'GET',
            'callback' => [$this, 'groups'],
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
     * Connect to the mailchimp api and store settings
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function connect($request)
    {
        $client = new MailerLiteRequest($request['api_key']);
        $response = $client->get('stats');

        if (is_wp_error($response)) {
            return new \WP_Error('could_not_connect', 'Could not connect. Please double-check your api key and try again.');
        }

        // save settings
        Setting::update('mailerlite', 'api_key', $request['api_key']);
        Setting::update('mailerlite', 'connected', true);

        return rest_ensure_response(Setting::getGroup('mailerlite'));
    }

    public function disconnect()
    {
        Setting::deleteAll('mailerlite');
        return rest_ensure_response(Setting::getGroup('mailerlite'));
    }

    /**
     * Connect to the mailchimp api and store settings
     *
     * @return WP_Error|WP_REST_Response
     */
    public function groups()
    {
        $result = MailerLiteRequest::getClient()->get('groups', [
            'query' => [
                'limit' => 1000
            ]
        ]);

        if (is_wp_error($result)) {
            return new \WP_Error('could_not_connect', 'Could not connect. Please double-check your api key and try again.');
        }

        return rest_ensure_response(!empty($result) ? $result : []);
    }
}
