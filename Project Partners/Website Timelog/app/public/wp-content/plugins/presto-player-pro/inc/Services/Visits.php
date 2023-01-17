<?php

namespace PrestoPlayer\Pro\Services;

use PrestoPlayer\Models\Setting;
use PrestoPlayer\Pro\Models\Visit;

class Visits
{
    public function register()
    {
        add_action('init', [$this, 'scheduleCron']);
        add_action('edit_user_profile', [$this,'addProfileAnalyticsLink']);
        add_action('show_user_profile', [$this,'addProfileAnalyticsLink']);
    }

    /**
     * Schedule cron and register deactivation
     *
     * @return void
     */
    public function scheduleCron()
    {
        // run our method on hook
        add_action('presto_daily_cron', [$this, 'deleteOldVisits']);

        // deactivate cron on plugin deactivation
        register_deactivation_hook(__FILE__, [$this, 'deactivateCron']);

        // schedule our event
        if (!wp_next_scheduled('presto_daily_cron')) {
            wp_schedule_event(time(), 'daily', 'presto_daily_cron');
        }
    }

    /**
     * Clear cron
     *
     * @return int|false
     */
    public function deactivateCron()
    {
        return wp_clear_scheduled_hook('presto_daily_cron');
    }

    public function isVisitPurgeEnabled()
    {
        if (!Setting::get('analytics')) {
            return true;
        }
        return (bool) Setting::get('analytics', 'purge_data');
    }

    /**
     * Deletes visits 
     *
     * @return void
     */
    public function deleteOldVisits($older_than = '90 days')
    {
        if (!$this->isVisitPurgeEnabled()) {
            return;
        }

        $enabled = Setting::get('analytics', 'purge_data', true);

        if (!$enabled) {
            return false;
        }

        // allow filtering of this time
        $older_than = apply_filters('presto_player_analytics_time_period', $older_than);

        // fetch ids of older visits
        $visit = new Visit();
        $ids = $visit->fetch(
            [
            'date_query' => [
                'before' => date('Y-m-d 00:00:00', strtotime("-" . sanitize_text_field($older_than))),
            ],
            'fields' => 'ids'
            ]
        );

        // if we have visits, delete them
        if (!empty($ids->data)) {
            $deleted = $visit->bulkDelete($ids->data);
            if (!$deleted) {
                error_log('Presto Player: Could not delete visits ' . implode(',', array_map('absint', $ids->data)));
            }

            return $deleted;
        }
    }

    /**
     * Add an analytics link to the user profile
     *
     * @param \WP_User $user The user object from the screen.
     * 
     * @return void
     */
    public function addProfileAnalyticsLink($user)
    {
        ?>
        <h3 class="heading">
            <?php esc_html_e('Presto Player', 'presto-player-pro'); ?>
        </h3>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e('User View Analytics', 'presto-player-pro') ?></th>
                <td>
                    <a href="<?php echo esc_url(get_admin_url(null, 'edit.php?post_type=pp_video_block&page=presto-analytics#/user/' . (int) $user->ID)); ?>">
                        <button type="button" class="button button-secondary">
                            <?php esc_html_e('View Analytics', 'presto-player-pro'); ?>
                        </button>
                    </a>
                </td>
            </tr>
        </table>
        <?php
    }
}
