<?php

namespace PrestoPlayer\Pro\Services\License;

use PrestoPlayer\Pro\Plugin;
use PrestoPlayer\Services\AdminNotice;
use PrestoPlayer\Pro\Models\LicensedProduct;

class License
{
    protected $page_id = '';
    protected $page_link = 'edit.php?post_type=pp_video_block&page=presto_license';

    public function register()
    {
        // @CustomMod-remove_actions
        // add_action('admin_notices', [$this, 'keyNotices']);
        // add_action('network_admin_notices', [$this, 'keyNotices']);
        // add_action('admin_menu', [$this, 'optionsPage']);
        // add_action('init', [$this, 'maybeActivateLicense']);
        // add_action('admin_init', [$this, 'maybeRedirect']);
    }

    /**
     * Maybe redirect to license page on activation
     *
     * @return void
     */
    public function maybeRedirect()
    {
        if (get_transient('presto_player_pro_activated')) {
            delete_transient('presto_player_pro_activated');
            if (!LicensedProduct::getKey()) {
                exit(wp_redirect(admin_url($this->page_link)));
            }
        }
    }

    /**
     * Maybe activate a license
     *
     * @return void
     */
    public function maybeActivateLicense()
    {
        // form has been submitted
        if (!isset($_POST['presto_player_licence_form_submit'])) {
            return;
        }
        // verify nonce
        if (!wp_verify_nonce($_POST['presto_player_license_nonce'], 'presto_player_licence')) {
            wp_die('Your session expired. Please login and retry.', 'Session Expired');
        }

        // activate
        if (isset($_POST['presto_player_licence_activate'])) {
            $key = $_POST['licence_key'] ?? '';
            $key = sanitize_key(trim($key));
            $response = LicensedProduct::activate($key);
            if (is_wp_error($response)) {
                AdminNotice::displayError($response->get_error_message());
            }
            if (is_string($response)) {
                AdminNotice::displaySuccess(sanitize_text_field($response));
            }
            return;
        }

        // deactivate
        if (isset($_POST['presto_player_licence_deactivate'])) {
            $response = LicensedProduct::deactivate();
            if (is_wp_error($response)) {
                AdminNotice::displayError($response->get_error_message());
            }
            if (is_string($response)) {
                AdminNotice::displaySuccess(sanitize_text_field($response));
            }
        }
    }

    /**
     * Activate the license
     *
     * @return true|\WP_Error
     */
    public function activateLicense($key = '')
    {
        $request_uri = add_query_arg([
            'woo_sl_action' => 'activate',
            'licence_key' => $key,
            'product_unique_id' => LicensedProduct::id(),
            'domain' => LicensedProduct::domain()
        ], LicensedProduct::apiUrl());

        $data = wp_remote_get($request_uri);

        // error
        if (is_wp_error($data) || $data['response']['code'] != 200) {
            return new \WP_Error('connection_error', 'There was a problem establishing a connection to the licensing server. Please try again in a few minutes.');
        }

        // check body
        $data_body = json_decode($data['body']);

        // maybe save key
        if (isset($data_body->status)) {
            if ($data_body->status == 'success' && ($data_body->status_code == 's100' || $data_body->status_code == 's101')) {
                return LicensedProduct::saveKey($key);
            } else {
                return new \WP_Error('activation_error', 'There was a problem activating the license. Please check your license expiration.');
            }
        }

        return new \WP_Error('connection_error', 'There was a problem establishing a connection to the licensing server. Please try again in a few minutes.');
    }

    /**
     * Show an admin notice if the license has not yet been activated
     *
     * @return void
     */
    public function keyNotices()
    {
        // check for key
        if (LicensedProduct::getKey()) {
            return;
        }

        // don't show on license page
        if ($this->page_id === get_current_screen()->id) {
            return;
        }

        // handle multisite
        $link = admin_url($this->page_link);
?>
        <div class="error fade">
            <p><?php _e("Presto Player Pro plugin is inactive, please enter your", 'presto-player-pro') ?>
                <a href="<?php echo esc_url($link) ?>">
                    <?php _e("Licence Key", 'presto-player-pro') ?>
                </a>
            </p>
        </div>
    <?php
    }

    /**
     * Add the license options page
     *
     * @return void
     */
    public function optionsPage()
    {
        $this->page_id = add_submenu_page('edit.php?post_type=pp_video_block', __('License', 'presto-player-pro'), __('License', 'presto-player-pro'), 'manage_options', 'presto_license', [$this, 'licensePageTemplate'], 10);
        do_action('presto_player_pro_register_license_page', $this->page_id);
    }

    /**
     * License page template
     *
     * @return void
     */
    public function licensePageTemplate()
    {
        if (LicensedProduct::getKey()) {
            return $this->licenseDeactivateForm();
        } else {
            return $this->licenseActivateForm();
        }
    }

    /**
     * License activation form
     *
     * @return void
     */
    public function licenseActivateForm()
    {
    ?>
        <div class="presto-player-dashboard__header">
            <img class="presto-player-dashboard__logo" src="<?php echo esc_url(PRESTO_PLAYER_PLUGIN_URL . '/img/logo.svg'); ?>" />
            <div class="presto-player-dashboard__version">v<?php echo esc_html(Plugin::version()); ?></div>
        </div>
        <div class="wrap">
            <?php AdminNotice::displayAdminNotice(); ?>
            <div class="start-container">
                <h3><?php _e("License Key", 'presto-player-pro') ?></h3>

                <form name="form" method="post">
                    <?php wp_nonce_field('presto_player_licence', 'presto_player_license_nonce'); ?>
                    <input type="hidden" name="presto_player_licence_form_submit" value="true" />
                    <input type="hidden" name="presto_player_licence_activate" value="true" />

                    <div class="section section-text ">
                        <div class="option">
                            <div class="controls">
                                <input type="text" value="" name="licence_key" class="text-input">
                            </div>
                            <div class="explain"><?php _e("Enter the Licence Key you received when purchased this product. If you lost the key, you can always retrieve it from", 'presto-player-pro') ?> <a href="https://my.prestomade.com/my-account/" target="_blank"><?php _e("My Account", 'presto-player-pro') ?></a></div>
                        </div>
                    </div>

                    <p class="submit">
                        <input type="submit" name="Submit" class="button button-primary button-large" value="<?php _e('Save', 'presto-player-pro') ?>">
                    </p>
                </form>
            </div>
        </div>
    <?php
    }

    /**
     * License deactivation form
     *
     * @return void
     */
    public function licenseDeactivateForm()
    {
    ?>
        <div class="presto-player-dashboard__header">
            <img class="presto-player-dashboard__logo" src="<?php echo esc_url(PRESTO_PLAYER_PLUGIN_URL . '/img/logo.svg'); ?>" />
            <div class="presto-player-dashboard__version">v<?php echo esc_html(Plugin::version()); ?></div>
        </div>
        <div class="wrap">
            <?php AdminNotice::displayAdminNotice(); ?>
            <div class="start-container">
                <h3><?php _e("License Key", 'presto-player-pro') ?></h3>

                <form name="form" method="post">
                    <?php wp_nonce_field('presto_player_licence', 'presto_player_license_nonce'); ?>
                    <input type="hidden" name="presto_player_licence_form_submit" value="true" />
                    <input type="hidden" name="presto_player_licence_deactivate" value="true" />

                    <div class="section section-text ">
                        <div class="option">
                            <div class="controls">
                                <p><b><?php echo substr(LicensedProduct::getKey(), 0, 20) ?>-**********-**********</b> &nbsp;&nbsp;&nbsp;<a class="button-secondary" title="Deactivate" href="javascript: void(0)" onclick="jQuery(this).closest('form').submit();"><?php _e('Deactivate', 'software-license') ?></a></p>
                            </div>
                            <div class="explain"><?php _e("Enter the Licence Key you received when purchased this product. If you lost the key, you can always retrieve it from", 'presto-player-pro') ?> <a href="https://my.prestomade.com/my-account/" target="_blank"><?php _e("My Account", 'presto-player-pro') ?></a></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
<?php
    }
}
