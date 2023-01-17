<?php

namespace PrestoPlayer\Pro\Services\FluentCRM;

use PrestoPlayer\Pro\Support\EmailProvider;

class FluentCRM extends EmailProvider
{
    protected $key = 'fluentcrm';

    // handle if mailchimp is active for preset
    public function handle($data, $preset)
    {
        // double check fluent is active
        if (!class_exists('\FluentCrm\App\Models\Subscriber')) {
            return;
        }

        // get list and email
        $email = $data['email'];
        $list = $preset->email_collection['provider_list'];
        $tag = $preset->email_collection['provider_tag'];

        $data = ['email' => $email];
        if ($list) {
            $data['lists'] = [$list];
        }
        if ($tag) {
            $data['tags'] = [$tag];
        }

        // update or create subscriver
        (new \FluentCrm\App\Models\Subscriber)->updateOrCreate($data);

        return $this;
    }
}
