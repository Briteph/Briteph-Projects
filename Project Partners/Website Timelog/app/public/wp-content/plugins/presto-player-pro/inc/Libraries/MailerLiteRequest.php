<?php

namespace PrestoPlayer\Pro\Libraries;

use PrestoPlayer\Models\Setting;
use PrestoPlayer\Pro\Support\ApiRequest;

class MailerLiteRequest extends ApiRequest
{
    protected $base_url = 'https://api.mailerlite.com/api/v2/';
    protected $key = 'mailerlite';

    public function request($endpoint, $args = [])
    {
        // this is how we pass the api key
        $args['headers']['X-MailerLite-ApiKey'] = $this->api_key;
        return parent::request($endpoint, $args);
    }

    public static function getClient()
    {
        return new static(Setting::get('mailerlite', 'api_key'));
    }
}
