<?php

namespace PrestoPlayer\Pro\Services\API;

use PrestoPlayer\Models\Setting;
use PrestoPlayer\Pro\Libraries\ActiveCampaignRequest;
use PrestoPlayer\Pro\Support\RestEmailProviderController;

class RestActiveCampaignController extends RestEmailProviderController
{
    protected $namespace = 'presto-player';
    protected $version = 'v1';
    protected $base = 'activecampaign';
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
                'url' => [
                    'description' => __('Your API url'),
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


        register_rest_route("$this->namespace/$this->version/$this->base", '/tags', [
            'methods' => 'GET',
            'callback' => [$this, 'tags'],
            'permission_callback' => [$this, 'permissions_check'],
        ]);
    }

    public function disconnect()
    {
        Setting::deleteAll('activecampaign');
        return rest_ensure_response(Setting::getGroup('activecampaign'));
    }

    /**
     * Connect to the mailchimp api and store settings
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function connect($request)
    {
        Setting::update('activecampaign', 'url', $request['url']);

        $client = new ActiveCampaignRequest($request['api_key']);
        $response = $client->get('lists');

        if (is_wp_error($response)) {
            Setting::update('activecampaign', 'url', '');
            Setting::update('activecampaign', 'api_key', '');
            return new \WP_Error('error', sanitize_text_field($response->get_error_message()));
        }

        // save settings
        Setting::update('activecampaign', 'api_key', $request['api_key']);
        Setting::update('activecampaign', 'connected', true);

        return rest_ensure_response(Setting::getGroup('activecampaign'));
    }

    /**
     * Connect to the mailchimp api and store settings
     *
     * @return WP_Error|WP_REST_Response
     */
    public function lists()
    {
        $result = ActiveCampaignRequest::getClient()->get('lists', ['query' => ['limit' => 100] ]);

        if (is_wp_error($result)) {
            return new \WP_Error('could_not_connect', 'Could not connect. Please double-check your api key and try again.');
        }

        return rest_ensure_response(!empty($result->lists) ? $result->lists : []);
    }


    /**
     * Connect to the mailchimp api and store settings
     *
     * @return WP_Error|WP_REST_Response
     */
    public function tags()
    {
        $result = ActiveCampaignRequest::getClient()->get('tags', ['query' => ['limit' => 100] ]);

        if (is_wp_error($result)) {
            return new \WP_Error('could_not_connect', 'Could not connect. Please double-check your api key and try again.');
        }

        return rest_ensure_response(!empty($result->tags) ? $result->tags : []);
    }
}
