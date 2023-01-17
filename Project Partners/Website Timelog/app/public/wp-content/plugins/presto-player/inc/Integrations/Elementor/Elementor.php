<?php

namespace PrestoPlayer\Integrations\Elementor;

class Elementor
{
    public function register()
    {
        add_action('elementor/widgets/widgets_registered', [$this, 'widget']);
    }

    public function widget()
    {
        if (!class_exists('\Elementor\Plugin')) {
            return;
        }
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new ReusableVideoWidget());
    }
}
