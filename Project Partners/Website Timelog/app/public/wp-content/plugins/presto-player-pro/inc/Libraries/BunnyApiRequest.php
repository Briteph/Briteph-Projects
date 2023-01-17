<?php

namespace PrestoPlayer\Pro\Libraries;

use PrestoPlayer\Pro\Support\ApiRequest;

class BunnyApiRequest extends ApiRequest
{
    protected $base_url = 'https://api.bunny.net/';
    protected $key = 'bunny';

    public function request($endpoint, $args = [])
    {
        // this is how we pass the api key
        $args['headers']['AccessKey'] = $this->api_key;
        return parent::request($endpoint, $args);
    }
}
