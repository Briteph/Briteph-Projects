<?php

namespace PrestoPlayer\Pro\Libraries;

use PrestoPlayer\Models\Setting;
use PrestoPlayer\Pro\Support\ApiRequest;

class MailChimpRequest extends ApiRequest
{
    protected $base_url = 'https://<dc>.api.mailchimp.com/3.0/';
    protected $key = 'mailchimp';

    public function __construct($api_key = '')
    {
        parent::__construct($api_key);

        // set base url depending on api key
        if ($api_key) {
            if (strpos($api_key, '-') === false) {
                throw new \Exception(__("This API key is not valid. Please double check it and try again.", 'presto-player'));
            }
            list(, $data_center) = explode('-', $api_key);
            $this->base_url = str_replace('<dc>', $data_center, $this->base_url);
        }
    }

    public function request($endpoint, $args = [])
    {
        // this is how we pass the api key
        $args['headers']['Accept'] = 'application/vnd.api+json';
        $args['headers']['Content-Type'] = 'application/vnd.api+json';
        $args['headers']['Authorization'] = 'apikey ' . $this->api_key;

        return parent::request($endpoint, $args);
    }

    public static function getClient()
    {
        return new static(Setting::get('mailchimp', 'api_key'));
    }
}
