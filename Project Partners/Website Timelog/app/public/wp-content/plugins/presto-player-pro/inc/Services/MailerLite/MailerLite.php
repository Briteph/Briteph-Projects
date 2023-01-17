<?php

namespace PrestoPlayer\Pro\Services\MailerLite;

use PrestoPlayer\Pro\Libraries\MailerLiteRequest;
use PrestoPlayer\Pro\Support\EmailProvider;

class MailerLite extends EmailProvider
{
    protected $key = 'mailerlite';

    // handle if mailchimp is active for preset
    public function handle($data, $preset)
    {
        // get list and email
        $email = $data['email'];
        $list = $preset->email_collection['provider_list'];

        // get client
        $client = MailerLiteRequest::getClient();

        // add subscriber 
        $client->post('subscribers', [
            'body' => [
                'email' => $email
            ]
        ]);

        // optionally add subscriber to group
        if ($list) {
            $client->post("groups/$list/subscribers", [
                'body' => [
                    'email' => $email
                ]
            ]);
        }
    }
}
