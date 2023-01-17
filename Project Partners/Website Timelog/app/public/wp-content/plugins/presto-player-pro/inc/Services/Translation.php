<?php

namespace PrestoPlayer\Pro\Services;

class Translation
{
    public function register()
    {
        add_action('init', [$this, 'textdomain']);
    }

    public function textdomain()
    {
        load_plugin_textdomain('presto-player-pro', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
}
