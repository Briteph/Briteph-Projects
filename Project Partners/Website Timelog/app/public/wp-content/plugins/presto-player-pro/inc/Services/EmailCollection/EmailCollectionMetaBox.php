<?php

namespace PrestoPlayer\Pro\Services\EmailCollection;

use PrestoPlayer\Models\Video;
use PrestoPlayer\Models\Preset;


class EmailCollectionMetaBox
{
    protected $post_type;
    protected $nonce_field = 'presto_player_email_submission_admin_nonce';

    public function __construct($post_type)
    {
        $this->post_type = $post_type;
    }

    /**
     * Register meta box actions
     */
    public function register()
    {
        add_action('add_meta_boxes', [$this, 'add']);
        add_action('save_post', [$this, 'save']);

        // post type ui
        add_filter("manage_{$this->post_type}_posts_columns", [$this, 'columns'], 1);
        add_action("manage_{$this->post_type}_posts_custom_column", [$this, 'content'], 10, 2);
    }

    /**
     * Add meta box to post type
     *
     * @param string $post_type
     * @return void
     */
    public function add($post_type)
    {
        if ($post_type !== $this->post_type) {
            return;
        }

        add_meta_box(
            'submission_details',
            __('Submission Details', 'presto-player-pro'),
            [$this, 'render'],
            $this->post_type,
            'advanced',
            'high'
        );
    }

    /**
     * Render meta box content
     */
    public function render()
    {
        global $post;

        // Add an nonce field so we can check for it later.
        wp_nonce_field($this->nonce_field, $this->nonce_field);

        // Use get_post_meta to retrieve an existing value from the database.
        $value = get_post_meta($post->ID, 'email', true);
?>
        <label for="presto_submission_email">
            <p><?php _e('Email', 'presto-player-pro'); ?></p>
        </label>
        <input type="email" id="presto_submission_email" name="presto_submission_email" value="<?php echo esc_attr($value); ?>" size="25" class="widefat" required />
<?php
    }

    public function save($post_id)
    {
        // Check if our nonce is set.
        if (empty($_POST[$this->nonce_field])) {
            return $post_id;
        }

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($_POST[$this->nonce_field], $this->nonce_field)) {
            return $post_id;
        }

        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // bail if user cannot edit this post
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        /* OK, it's safe for us to save the data now. */

        // Sanitize the user input.
        $mydata = sanitize_email($_POST['presto_submission_email']);

        // Update the meta field.
        update_post_meta($post_id, 'email', $mydata);
    }

    public function columns($defaults)
    {
        return array(
            'cb' => 'bulk',
            'email' => __('Email', 'presto-player'),
            'video' => __('Video', 'presto-player'),
            'preset' => __('Preset', 'presto-player'),
            'date' => $defaults['date']
        );
    }

    public function content($name, $post_id)
    {
        switch ($name) {
            case 'email':
                echo '<a href="' . get_edit_post_link($post_id) . '">';
                echo sanitize_email(get_post_meta($post_id, 'email', true));
                echo '</a>';
                break;
            case 'video':
                $video = new Video((int) get_post_meta($post_id, 'video_id', true));
                echo $video->title;
                break;
            case 'preset':
                $preset = new Preset((int) get_post_meta($post_id, 'preset_id', true));
                echo $preset->name;
                break;
        }
    }
}
