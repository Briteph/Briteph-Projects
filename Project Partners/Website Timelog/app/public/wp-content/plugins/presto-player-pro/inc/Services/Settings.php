<?php

namespace PrestoPlayer\Pro\Services;

use PrestoPlayer\Pro\Models\Bunny\PullZone;
use PrestoPlayer\Pro\Models\Bunny\StorageZone;

class Settings
{
    public function register()
    {
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('rest_api_init', [$this, 'registerSettings']);

        add_filter("pre_option_presto_player_fluentcrm", [$this, 'filterFluent']);
    }

    // option will update if fluent crm is active
    public function filterFluent($value)
    {
        $value['connected'] = is_plugin_active('fluent-crm/fluent-crm.php');
        return $value;
    }

    public function registerSettings()
    {
        \register_setting(
            'presto_player',
            'presto_player_bunny_stream_public',
            [
                'type'              => 'object',
                'description'       => __('Bunny.net public video stream settings.', 'presto-player-pro'),
                'show_in_rest'      => [
                    'name' =>  'presto_player_bunny_stream_public',
                    'type'  => 'object',
                    'schema' => [
                        'properties' => [
                            'video_library_id' => [
                                'type' => 'integer'
                            ],
                            'video_library_api_key' => [
                                'type' => 'string'
                            ],
                            'pull_zone_id' => [
                                'type' => 'integer'
                            ],
                            'pull_zone_url' => [
                                'type' => 'string'
                            ],
                            'token_auth_key' => [
                                'type' => 'string'
                            ]
                        ]
                    ]
                ],
                'default' => []
            ]
        );

        \register_setting(
            'presto_player',
            'presto_player_bunny_stream',
            [
                'type'              => 'object',
                'description'       => __('Bunny.net stream general settings.', 'presto-player-pro'),
                'show_in_rest'      => [
                    'name' =>  'presto_player_bunny_stream',
                    'type'  => 'object',
                    'schema' => [
                        'properties' => [
                            'hls_start_level' => [
                                'type' => 'integer | null'
                            ],
                            'disable_legacy_storage' => [
                                'type' => 'boolean'
                            ],
                        ]
                    ]
                ],
                'default' => [
                    'hls_start_level' => 480,
                    'disable_legacy_storage' => false
                ]
            ]
        );

        \register_setting(
            'presto_player',
            'presto_player_bunny_stream_private',
            [
                'type'              => 'object',
                'description'       => __('Bunny.net private video stream settings.', 'presto-player-pro'),
                'show_in_rest'      => [
                    'name' =>  'presto_player_bunny_stream_private',
                    'type'  => 'object',
                    'schema' => [
                        'properties' => [
                            'video_library_id' => [
                                'type' => 'integer'
                            ],
                            'video_library_api_key' => [
                                'type' => 'string'
                            ],
                            'pull_zone_id' => [
                                'type' => 'integer'
                            ],
                            'pull_zone_url' => [
                                'type' => 'string'
                            ],
                            'token_auth_key' => [
                                'type' => 'string'
                            ]
                        ]
                    ]
                ],
                'default' => []
            ]
        );

        $pull_zone = new PullZone();
        \register_setting(
            'presto_player',
            'presto_player_bunny_pull_zones',
            [
                'type'              => 'object',
                'description'       => __('Bunny.net settings.', 'presto-player-pro'),
                'show_in_rest'      => [
                    'name' =>  'presto_player_bunny_pull_zones',
                    'type'  => 'object',
                    'schema' => [
                        'properties' => $pull_zone->getFillableSchema()
                    ]
                ],
                'default' => []
            ]
        );

        $storage_zone = new StorageZone();
        \register_setting(
            'presto_player',
            'presto_player_bunny_storage_zones',
            [
                'type'              => 'object',
                'description'       => __('Bunny.net settings.', 'presto-player-pro'),
                'show_in_rest'      => [
                    'name' =>  'presto_player_bunny_storage_zones',
                    'type'  => 'object',
                    'schema' => [
                        'properties' => $storage_zone->getFillableSchema()
                    ]
                ],
                'default' => []
            ]
        );

        // unique install id
        \register_setting(
            'presto_player',
            'presto_player_bunny_uid',
            [
                'type'              => 'string',
                'description'       => __('A generated unique install id for Bunny.net.', 'presto-player-pro'),
                'show_in_rest'      => true
            ]
        );

        // mailchimp
        \register_setting(
            'presto_player',
            'presto_player_mailchimp',
            [
                'type'              => 'object',
                'description'       => __('Mailchimp settings.', 'presto-player-pro'),
                'show_in_rest'      => [
                    'name' =>  'presto_player_mailchimp',
                    'type'  => 'object',
                    'schema' => [
                        'properties' => [
                            'api_key' => [
                                'type' => 'string',
                            ],
                            'connected' => [
                                'type' => 'boolean'
                            ]
                        ]
                    ]
                ],
                'default' => [
                    'api_key' => '',
                    'connected' => false
                ]
            ]
        );

        // mailerlite
        \register_setting(
            'presto_player',
            'presto_player_mailerlite',
            [
                'type'              => 'object',
                'description'       => __('Mailerlite settings.', 'presto-player-pro'),
                'show_in_rest'      => [
                    'name' =>  'presto_player_mailerlite',
                    'type'  => 'object',
                    'schema' => [
                        'properties' => [
                            'api_key' => [
                                'type' => 'string',
                            ],
                            'connected' => [
                                'type' => 'boolean'
                            ]
                        ]
                    ]
                ],
                'default' => [
                    'api_key' => '',
                    'connected' => false
                ]
            ]
        );

        // activecampaign
        \register_setting(
            'presto_player',
            'presto_player_activecampaign',
            [
                'type'              => 'object',
                'description'       => __('ActiveCampaign settings.', 'presto-player-pro'),
                'show_in_rest'      => [
                    'name' =>  'presto_player_activecampaign',
                    'type'  => 'object',
                    'schema' => [
                        'properties' => [
                            'api_key' => [
                                'type' => 'string',
                            ],
                            'url' => [
                                'type' => 'string'
                            ],
                            'connected' => [
                                'type' => 'boolean'
                            ]
                        ]
                    ]
                ],
                'default' => [
                    'api_key' => '',
                    'url' => '',
                    'connected' => false
                ]
            ]
        );

        //  fluent crm
        \register_setting(
            'presto_player',
            'presto_player_fluentcrm',
            [
                'type'              => 'object',
                'description'       => __('Is fluent crm connected', 'presto-player-pro'),
                'show_in_rest'      => [
                    'name' =>  'presto_player_fluentcrm',
                    'type'  => 'object',
                    'schema' => [
                        'properties' => [
                            'connected' => [
                                'type' => 'boolean',
                            ],
                        ]
                    ]
                ],
                'default' => [
                    'connected' => false
                ]
            ]
        );

        /**
         * License
         */
        \register_setting(
            'presto_player',
            'presto_player_license',
            [
                'type'              => 'object',
                'description'       => __('License settings.', 'presto-player-pro'),
                'show_in_rest'      => [
                    'name' =>  'presto_player_license',
                    'type'  => 'object',
                    'schema' => [
                        'properties' => [
                            'key' => [
                                'type' => 'string',
                            ],
                        ]
                    ]
                ],
                'default' => [
                    'enable' => false,
                    'purge_data' => true
                ]
            ]
        );
    }
}
