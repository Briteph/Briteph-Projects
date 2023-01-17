<?php

namespace PrestoPlayer\Pro\Services\EmailCollection;

class EmailCollection
{
    protected $post_type = 'pp_email_submission';
    public $meta_box;
    public $submissions;

    public function register()
    {
        // required presto version
        if (version_compare(\PrestoPlayer\Plugin::version(), '0.9', '<')) {
            return;
        }

        // subclasses
        $this->meta_box = (new EmailCollectionMetaBox($this->post_type))->register();
        $this->submissions = (new EmailCollectionAjaxHandler())->register();

        // Add post type and menu item
        add_action('init', [$this, 'registerPostType'], 9999);
        add_action('admin_menu', [$this, 'menuItem']);
    }


    public function registerPostType()
    {
        register_post_type(
            $this->post_type,
            array(
                'labels'                => array(
                    'name'                     => _x('Email Submissions', 'post type general name', 'presto-player-pro'),
                    'singular_name'            => _x('Submission', 'post type singular name', 'presto-player-pro'),
                    'menu_name'                => _x('Submissions', 'admin menu', 'presto-player-pro'),
                    'name_admin_bar'           => _x('Email Submission', 'add new on admin bar', 'presto-player-pro'),
                    'add_new'                  => _x('Add New', 'Email Submission', 'presto-player-pro'),
                    'add_new_item'             => __('Add New Submission', 'presto-player-pro'),
                    'new_item'                 => __('New Submission', 'presto-player-pro'),
                    'edit_item'                => __('Edit Submission', 'presto-player-pro'),
                    'view_item'                => __('View Submission', 'presto-player-pro'),
                    'all_items'                => __('Email Submissions', 'presto-player-pro'),
                    'search_items'             => __('Search Submissions', 'presto-player-pro'),
                    'not_found'                => __('No Submissions found.', 'presto-player-pro'),
                    'not_found_in_trash'       => __('No Submissions found in Trash.', 'presto-player-pro'),
                    'filter_items_list'        => __('Filter Submissions list', 'presto-player-pro'),
                    'items_list_navigation'    => __('Submissions list navigation', 'presto-player-pro'),
                    'items_list'               => __('Submissions list', 'presto-player-pro'),
                    'item_published'           => __('Submission published.', 'presto-player-pro'),
                    'item_published_privately' => __('Submission published privately.', 'presto-player-pro'),
                    'item_reverted_to_draft'   => __('Submission reverted to draft.', 'presto-player-pro'),
                    'item_scheduled'           => __('Submission scheduled.', 'presto-player-pro'),
                    'item_updated'             => __('Submission updated.', 'presto-player-pro'),
                ),
                'public'                => false,
                'show_ui'               => true,
                'show_in_menu'          => false,
                'rewrite'               => false,
                'show_in_rest'          => true,
                'rest_base'             => 'presto-email-submission',
                'map_meta_cap'          => true,
                'supports'              => false
            )
        );
    }

    public function menuItem()
    {
        add_submenu_page(
            'edit.php?post_type=pp_video_block',
            __('Email Submissions', 'presto-player-pro'),
            __('Emails', 'presto-player-pro'),
            'manage_options',
            'edit.php?post_type=pp_email_submission',
            false,
            3
        );
    }
}
