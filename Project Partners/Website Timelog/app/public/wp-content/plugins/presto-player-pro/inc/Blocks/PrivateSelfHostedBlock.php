<?php

namespace PrestoPlayer\Pro\Blocks;

use PrestoPlayer\Attachment;
use PrestoPlayer\Models\CurrentUser;
use PrestoPlayer\Blocks\SelfHostedBlock;

class PrivateSelfHostedBlock extends SelfHostedBlock
{
    protected $name = 'self-hosted-private';

    /**
     * Bail if user cannot access video
     *
     * @return void
     */
    public function middleware($attributes, $content)
    {
        if (!CurrentUser::canAccessVideo(!empty($attributes['id']) ? $attributes['id'] : 0)) {
            return false;
        }
        return true;
    }

    /**
     * Data to send to editor scripts
     *
     * @return void
     */
    public function editorScriptData()
    {
        return [
            'src' => sprintf(site_url('video-src/%s/'), wp_create_nonce('presto-player-user-token')),
        ];
    }

    /**
     * Add src to video
     *
     * @param array $attributes
     * @return void
     */
    public function sanitizeAttributes($attributes, $default_config)
    {
        return [
            'src'   => !empty($attributes['id']) ? Attachment::getSrc($attributes['id'], true) : '',
        ];
    }
}
