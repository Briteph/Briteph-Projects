<?php

namespace PrestoPlayer\Pro\Support;

use PrestoPlayer\Models\Setting;
use PrestoPlayer\Pro\Contracts\EmailProviderInterface;

abstract class EmailProvider implements EmailProviderInterface
{
    // holds email provider key
    protected $key = '';

    public function register()
    {
        if (Setting::get($this->key, 'connected')) {
            add_action('presto_player/pro/forms/save', [$this, 'maybeHandleSubmit'], 10, 2);
        }
    }

    /**
     * Determine whether to handle the request
     *
     * @param array $data
     * @param \PrestoPlayer\Models\Preset $preset
     * @return void
     */
    public function maybeHandleSubmit($data, $preset)
    {
        // bail if not our provider
        if (empty($preset->email_collection['provider']) || $this->key !== $preset->email_collection['provider']) {
            return;
        }

        $this->handle($data, $preset);

        return $this;
    }

    /**
     * Handle the request
     *
     * @return void
     */
    public function handle($data, $preset)
    {
    }
}
