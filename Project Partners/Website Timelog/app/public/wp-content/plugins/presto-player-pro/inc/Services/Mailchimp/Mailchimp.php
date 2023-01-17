<?php

namespace PrestoPlayer\Pro\Services\Mailchimp;

use PrestoPlayer\Pro\Libraries\MailChimpRequest;
use PrestoPlayer\Pro\Support\EmailProvider;

class Mailchimp extends EmailProvider
{
    protected $key = 'mailchimp';

    // handle if mailchimp is active for preset
    public function handle($data, $preset)
    {
        $email = $data['email'];
        $list = $preset->email_collection['provider_list'];
        $tag = $preset->email_collection['provider_tag'];

        // generate url
        $url = "lists/$list/members/";
        $url = $url . md5(strtolower(sanitize_email($email)));
        $client = MailChimpRequest::getClient();

        // add or update list member
        $client->put($url, [
            'query' => [
                'skip_merge_validation' => true
            ],
            'body' => [
                'email_address' => sanitize_email($email),
                'status_if_new' => 'subscribed',
            ]
        ]);

        // update tags
        $client->post(trailingslashit($url) . 'tags', [
            'body' => [
                'tags' => [[
                    'name' => $tag,
                    'status' => 'active'
                ]]
            ]
        ]);
    }
}
