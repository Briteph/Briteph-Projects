<?php

namespace PrestoPlayer\Pro\Services\API;

use PrestoPlayer\Models\Setting;
use PrestoPlayer\Pro\Support\RestEmailProviderController;
use PrestoPlayer\Pro\Libraries\MailChimpRequest;

class RestMailchimpController extends RestEmailProviderController
{
    protected $namespace = 'presto-player';
    protected $version = 'v1';
    protected $base = 'mailchimp';
    protected $client;

    public function register()
    {
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }

    public function getClient()
    {
        return new MailChimpRequest(Setting::get('mailchimp', 'api_key'));
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

        register_rest_route("$this->namespace/$this->version/$this->base", '/lists', [
            'methods' => 'GET',
            'callback' => [$this, 'lists'],
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
        try {
            $client = new MailChimpRequest($request['api_key']);
            $result = $client->get('ping');
        } catch (\Exception $e) {
            $result = new \WP_Error('error', $e->getMessage());
        }

        if (is_wp_error($result)) {
            return $result;
        }

        // save settings
        Setting::update('mailchimp', 'api_key', $request['api_key']);
        Setting::update('mailchimp', 'connected', true);

        return rest_ensure_response(Setting::getGroup('mailchimp'));
    }

    public function disconnect()
    {
        Setting::deleteAll('mailchimp');
        return rest_ensure_response(Setting::getGroup('mailchimp'));
    }

    /**
     * Connect to the mailchimp api and store settings
     *
     * @return WP_Error|WP_REST_Response
     */
    public function lists()
    {
        $result = MailChimpRequest::getClient()->get('lists', [
            'query' => [
                'count' => 1000
            ]
        ]);

        if (is_wp_error($result)) {
            return new \WP_Error('could_not_connect', 'Could not connect. Please double-check your api key and try again.');
        }

        return rest_ensure_response(!empty($result->lists) ? $result->lists : []);
    }
}
