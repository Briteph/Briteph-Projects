<?php
return [
    \PrestoPlayer\Pro\Services\Translation::class,
    \PrestoPlayer\Pro\Services\License\License::class,
    \PrestoPlayer\Pro\Services\License\AutoUpdate::class,
    \PrestoPlayer\Pro\Services\Settings::class,
    \PrestoPlayer\Pro\Services\Visits::class,
    \PrestoPlayer\Pro\Services\Bunny\BunnyService::class,

    \PrestoPlayer\Pro\Services\ActiveCampaign\ActiveCampaign::class,
    \PrestoPlayer\Pro\Services\FluentCRM\FluentCRM::class,
    \PrestoPlayer\Pro\Services\Mailchimp\Mailchimp::class,
    \PrestoPlayer\Pro\Services\MailerLite\MailerLite::class,
    \PrestoPlayer\Pro\Services\EmailCollection\EmailCollection::class,
    \PrestoPlayer\Pro\Services\EmailExport::class,

    \PrestoPlayer\Pro\Services\API\RestBunnyCDNController::class,
    \PrestoPlayer\Pro\Services\API\RestBunnyStreamController::class,
    \PrestoPlayer\Pro\Services\API\RestActiveCampaignController::class,
    \PrestoPlayer\Pro\Services\API\RestEmailCollectionController::class,
    \PrestoPlayer\Pro\Services\API\RestFluentCRMController::class,
    \PrestoPlayer\Pro\Services\API\RestMailchimpController::class,
    \PrestoPlayer\Pro\Services\API\RestMailerLiteController::class,

    \PrestoPlayer\Pro\Blocks\BunnyCDNBlock::class,
    \PrestoPlayer\Pro\Services\GoogleAnalytics::class,
    \PrestoPlayer\Pro\Services\API\RestAnalyticsController::class
];
