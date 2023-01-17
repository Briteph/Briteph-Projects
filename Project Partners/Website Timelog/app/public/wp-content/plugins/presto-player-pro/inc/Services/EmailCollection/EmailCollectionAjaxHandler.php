<?php

namespace PrestoPlayer\Pro\Services\EmailCollection;

use PrestoPlayer\Models\Preset;

class EmailCollectionAjaxHandler
{
    /**
     * Register actions
     *
     * @return void
     */
    public function register()
    {
        add_action('wp_ajax_presto_player_email_submit', [$this, 'handle']);
        add_action('wp_ajax_nopriv_presto_player_email_submit', [$this, 'handle']);
    }

    /**
     * Handle form submission
     *
     * @return void
     */
    public function handle()
    {
        // verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'wp_rest')) {
            return wp_send_json_error('Nonce invalid', 403);
        }

        // validate request
        $this->validate();

        // silently fail for these
        if (empty($_POST['video_id'])) {
            return wp_send_json_error('This video does not have an id.');
        }
        if (empty($_POST['preset_id'])) {
            return wp_send_json_error('You must send a preset.');
        }

        // prepare data
        $data = $this->prepareData($_POST);

        // get or save submission
        $saved = $this->getOrSave($data);

        // get preset
        $preset = new Preset($data['preset_id']);

        /**
         * Use this hook to collect form submission data
         * 
         * $save array Data to save
         * $post WP_Post Submission Post
         * $created boolean whether the submission was created (true) or not (false)
         */
        do_action('presto_player/pro/forms/save', $data, $preset, $saved['post'], $saved['created']);

        // send success
        wp_send_json_success($saved);
    }

    /**
     * Prepare and sanitize data
     *
     * @param array $data
     * @return array
     */
    public function prepareData($data)
    {
        return [
            'email' => sanitize_email($data['email']),
            'firstname' => !empty($data['firstname']) ? sanitize_text_field($data['firstname']) : '',
            'lastname' => !empty($data['lastname']) ? sanitize_text_field($data['lastname']) : '',
            'video_id' => !empty($data['video_id']) ? (int) $data['video_id'] : null,
            'preset_id' => !empty($data['preset_id']) ? (int) $data['preset_id'] : null
        ];
    }

    /**
     * Checks for duplicate submission before adding one
     * @return array [$post, $created]
     */
    public function getOrSave($save)
    {
        // check for duplicates
        $saved = get_posts([
            'post_type' => 'pp_email_submission',
            'meta_query' => [
                [
                    'key' => 'email',
                    'value' => sanitize_email($save['email']),
                    'compare' => '='
                ],
            ],
        ]);

        // already captured
        if (!empty($saved[0])) {
            return ['post' => $saved[0], 'created' => false];
        }

        // add submission
        $saved = wp_insert_post([
            'post_type' => 'pp_email_submission',
            'post_status' => 'publish',
            'meta_input' => $save
        ]);

        $saved = get_post($saved);

        return ['post' => $saved, 'created' => true];
    }

    /**
     * Validate submission
     *
     * @return void
     */
    public function validate()
    {
        $errors = [];

        // email is not valid
        if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = __("Please enter a valid email address", 'presto-player');
        }

        // let extendions add additional validation errors
        $errors = apply_filters('presto_player/pro/forms/validation', $errors);

        // we have validation errors
        if (!empty($errors)) {
            wp_send_json_error($errors, 400);
        }

        return true;
    }
}
