<?php

namespace PrestoPlayer\Pro\Services;

use PrestoPlayer\Models\Setting;
use PrestoPlayer\Services\Settings;


class GoogleAnalytics
{
    protected $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Maybe output script in footer
     *
     * @return void
     */
    public function register()
    {
        if (Setting::get('google_analytics', 'enable')) {
            add_action('wp_footer', [$this, 'script']);
        }
    }

    /**
     * Output script and config
     *
     * @return void
     */
    public function script()
    {
        // check if we're using existing tag
        if (Setting::get('google_analytics', 'use_existing_tag')) {
            return;
        }

        // must have a measurement id
        if (!Setting::get('google_analytics', 'measurement_id')) {
            return;
        }
?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_html(Setting::get('google_analytics', 'measurement_id')); ?>"></script>

        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', "<?php echo esc_html(Setting::get('google_analytics', 'measurement_id')); ?>");
        </script>
<?php }
}
