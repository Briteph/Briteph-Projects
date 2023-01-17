<?php

namespace PrestoPlayer\Pro\Libraries;

use PrestoPlayer\Models\Setting;
use PrestoPlayer\Pro\Support\ApiRequest;

class ActiveCampaignRequest extends ApiRequest
{
    protected $base_url = '';
    protected $key = 'active-campaign';

    public function __construct($api_key)
    {
        parent::__construct($api_key);
        $this->base_url = trailingslashit(Setting::get('activecampaign', 'url')) . 'api/3/';
    }

    public function request($endpoint, $args = [])
    {
        // this is how we pass the api key
        $args['headers']['Api-Token'] = $this->api_key;
        return parent::request($endpoint, $args);
    }

    public static function getClient()
    {
        return new static(Setting::get('activecampaign', 'api_key'));
    }
}
