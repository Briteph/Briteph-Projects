<?php

namespace PrestoPlayer\Pro\Services\API;

use PrestoPlayer\Support\Utility;
use PrestoPlayer\Pro\Models\Visit;

class RestAnalyticsController
{
    protected $namespace = 'presto-player';
    protected $version = 'v1';
    protected $base = 'analytics';

    /**
     * Register controller
     *
     * @return void
     */
    public function register()
    {
        // rest routes
        add_action('rest_api_init', [$this, 'registerRoutes']);

        // use ajax routes for front since many security plugins block api requests...
        add_action('wp_ajax_presto_player_progress', [$this, 'ajaxProgress']);
        add_action('wp_ajax_nopriv_presto_player_progress', [$this, 'ajaxProgress']);
    }

    /**
     * Save progress
     *
     * @return void
     */
    public function ajaxProgress()
    {
        // verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'wp_rest')) {
            wp_send_json_error('Nonce invalid', 403);
        }

        // video id is required
        if (empty($_POST['video_id'])) {
            wp_send_json_error('You must provide a video_id', 400);
        }

        // get visitor identification
        $id = get_current_user_id();
        $ip = Utility::getIPAddress();

        // allow blocking IP or user id
        if (apply_filters('presto_player_analytics_block', false, $ip, $id)) {
            wp_send_json_success();
        }

        // update or create visit within the last day
        // by IP address or user id
        $visit = (new Visit)->updateOrCreate([
            'video_id' => (int) $_POST['video_id'], // this video id
            'user_id' => $id ? (int) $id : null, // maybe by user id
            'ip_address' => !$id ? sanitize_text_field($ip) : null, // maybe by ip address
            'date_query' => [
                'after' => date('Y-m-d 00:00:00', strtotime("-1 day")), // after start of today
                'before' => date('Y-m-d 23:59:59', strtotime('today')), // before today ends
                'field' => 'created_at' // when it was created
            ]
        ], [
            'duration' => (int) $_POST['duration'],
            'ip_address' => sanitize_text_field($ip) // always store ip address
        ]);

        if (!$visit->id) {
            wp_send_json_error('Could not create visit', 500);
        }

        if (is_wp_error($visit)) {
            wp_send_json_error($visit->get_error_message(), 500);
        }

        // send success
        wp_send_json_success();
    }

    /**
     * Register rest routes
     *
     * @return void
     */
    public function registerRoutes()
    {
        // get video timeline
        register_rest_route("$this->namespace/$this->version/$this->base", '/video/(?P<id>\d+)/timeline', [
            'methods' => 'GET',
            'callback' => [$this, 'videoTimeline'],
            'permission_callback' => [$this, 'permissions_check'],
            'args' => [
                'id' => [
                    'validate_callback' => function ($param) {
                        return is_numeric($param);
                    }
                ],
                'context' => [
                    'default' => 'view',
                ],
                'start' => [
                    'description' => __('Limit response to posts published after a given ISO8601 compliant date.'),
                    'type'        => 'string',
                    'format'      => 'date-time',
                ],
                'end' => [
                    'description' => __('Limit response to posts published before a given ISO8601 compliant date.'),
                    'type'        => 'string',
                    'format'      => 'date-time',
                ]
            ],
        ]);

        register_rest_route("$this->namespace/$this->version/$this->base", '/video/(?P<id>\d+)/views', [
            'methods' => 'GET',
            'callback' => [$this, 'videoViews'],
            'permission_callback' => [$this, 'permissions_check'],
            'args' => [
                'id' => [
                    'validate_callback' => function ($param) {
                        return is_numeric($param);
                    }
                ],
                'context' => [
                    'default' => 'view',
                ],
                'start' => [
                    'description' => __('Limit response to posts published after a given ISO8601 compliant date.'),
                    'type'        => 'string',
                    'format'      => 'date-time',
                ],
                'end' => [
                    'description' => __('Limit response to posts published before a given ISO8601 compliant date.'),
                    'type'        => 'string',
                    'format'      => 'date-time',
                ]
            ],
        ]);

        register_rest_route("$this->namespace/$this->version/$this->base", '/video/(?P<id>\d+)/average-watchtime', [
            'methods' => 'GET',
            'callback' => [$this, 'videoAverageWatchTime'],
            'permission_callback' => [$this, 'permissions_check'],
            'args' => [
                'id' => [
                    'validate_callback' => function ($param) {
                        return is_numeric($param);
                    }
                ],
                'context' => [
                    'default' => 'view',
                ],
                'start' => [
                    'description' => __('Limit response to posts published after a given ISO8601 compliant date.'),
                    'type'        => 'string',
                    'format'      => 'date-time',
                ],
                'end' => [
                    'description' => __('Limit response to posts published before a given ISO8601 compliant date.'),
                    'type'        => 'string',
                    'format'      => 'date-time',
                ]
            ],
        ]);

        // analytics
        register_rest_route("$this->namespace/$this->version/$this->base", '/top-users/', [
            'methods' => 'GET',
            'callback' => [$this, 'topUsers'],
            'permission_callback' => [$this, 'permissions_check'],
            'args' => $this->collectionParams()
        ]);

        register_rest_route("$this->namespace/$this->version/$this->base", '/top-videos/', [
            'methods' => 'GET',
            'callback' => [$this, 'topVideos'],
            'permission_callback' => [$this, 'permissions_check'],
            'args' => $this->collectionParams()
        ]);

        register_rest_route("$this->namespace/$this->version/$this->base", '/views/', [
            'methods' => 'GET',
            'callback' => [$this, 'totalViewsByDay'],
            'permission_callback' => [$this, 'permissions_check'],
            'args' => $this->collectionParams()
        ]);

        register_rest_route("$this->namespace/$this->version/$this->base", '/watch-time/', [
            'methods' => 'GET',
            'callback' => [$this, 'totalWatchTimeByDay'],
            'permission_callback' => [$this, 'permissions_check'],
            'args' => $this->collectionParams()
        ]);

        // User Analytics
        register_rest_route("$this->namespace/$this->version/$this->base", '/user/(?P<id>\d+)/total-views', [
            'methods' => 'GET',
            'callback' => [$this, 'totalVideoViewsByUser'],
            'permission_callback' => [$this, 'permissions_check'],
            'args' => [
                'id' => [
                    'validate_callback' => function ($param) {
                        return is_numeric($param);
                    }
                ],
                'context' => [
                    'default' => 'view',
                ],
                'start' => [
                    'description' => __('Limit response to posts published after a given ISO8601 compliant date.'),
                    'type'        => 'string',
                    'format'      => 'date-time',
                ],
                'end' => [
                    'description' => __('Limit response to posts published before a given ISO8601 compliant date.'),
                    'type'        => 'string',
                    'format'      => 'date-time',
                ]
            ],
        ]);

        register_rest_route("$this->namespace/$this->version/$this->base", '/user/(?P<id>\d+)/average-watchtime', [
            'methods' => 'GET',
            'callback' => [$this, 'videoAverageWatchTimeByUser'],
            'permission_callback' => [$this, 'permissions_check'],
            'args' => [
                'id' => [
                    'validate_callback' => function ($param) {
                        return is_numeric($param);
                    }
                ],
                'context' => [
                    'default' => 'view',
                ],
                'start' => [
                    'description' => __('Limit response to posts published after a given ISO8601 compliant date.'),
                    'type'        => 'string',
                    'format'      => 'date-time',
                ],
                'end' => [
                    'description' => __('Limit response to posts published before a given ISO8601 compliant date.'),
                    'type'        => 'string',
                    'format'      => 'date-time',
                ]
            ],
        ]);

        register_rest_route("$this->namespace/$this->version/$this->base", '/user/(?P<id>\d+)/total-watchtime', [
            'methods' => 'GET',
            'callback' => [$this, 'videoTotalWatchTimeByUser'],
            'permission_callback' => [$this, 'permissions_check'],
            'args' => [
                'id' => [
                    'validate_callback' => function ($param) {
                        return is_numeric($param);
                    }
                ],
                'context' => [
                    'default' => 'view',
                ],
                'start' => [
                    'description' => __('Limit response to posts published after a given ISO8601 compliant date.'),
                    'type'        => 'string',
                    'format'      => 'date-time',
                ],
                'end' => [
                    'description' => __('Limit response to posts published before a given ISO8601 compliant date.'),
                    'type'        => 'string',
                    'format'      => 'date-time',
                ]
            ],
        ]);

    }

    /**
     * Must be able to manage options to access analytics
     *
     * @return bool
     */
    public function permissions_check()
    {
        return current_user_can('manage_options');
    }

    /**
     * Nonce refresh
     *
     * @return string|int
     */
    public function createNonce()
    {
        return wp_create_nonce('wp_rest');
    }

    /**
     * Individual Video Timeline
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function videoTimeline(\WP_REST_Request $request)
    {
        $timeline = (new Visit())->timeline([
            'video_id' => $request['id'],
            'start' => $request['start'],
            'end' => $request['end'],
        ]);

        if (is_wp_error($timeline)) {
            return $timeline;
        }

        return rest_ensure_response($timeline);
    }

    public function videoViews(\WP_REST_Request $request)
    {
        $timeline = (new Visit())->views([
            'video_id' => $request['id'],
            'start' => $request['start'],
            'end' => $request['end'],
        ]);

        if (is_wp_error($timeline)) {
            return $timeline;
        }
        return rest_ensure_response($timeline);
    }

    public function videoAverageWatchTime(\WP_REST_Request $request)
    {
        $average = (new Visit())->averageWatchTime([
            'video_id' => $request['id'],
            'start' => $request['start'],
            'end' => $request['end'],
        ]);

        if (is_wp_error($average)) {
            return $average;
        }

        return rest_ensure_response($average);
    }

    /**
     * Total Video views by particular user
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function totalVideoViewsByUser(\WP_REST_Request $request)
    {
        $totalVideoViewsByUser = (new Visit())->totalVideoViewsByUser([
            'user_id' => $request['id'],
            'start' => $request['start'],
            'end' => $request['end'],
        ]);

        if (is_wp_error($totalVideoViewsByUser)) {
            return $totalVideoViewsByUser;
        }
        $data['view'] = $totalVideoViewsByUser;

        return rest_ensure_response($data);
    }

    /**
     * Average watch time by particular user
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function videoAverageWatchTimeByUser(\WP_REST_Request $request)
    {
        $videoAverageWatchTimeByUser = (new Visit())->videoAverageWatchTimeByUser([
            'user_id' => $request['id'],
            'start' => $request['start'],
            'end' => $request['end'],
        ]);

        if (is_wp_error($videoAverageWatchTimeByUser)) {
            return $videoAverageWatchTimeByUser;
        }
        $data['view'] = $videoAverageWatchTimeByUser;

        return rest_ensure_response($data);
    }

    /**
     * Total watch time by particular user
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function videoTotalWatchTimeByUser(\WP_REST_Request $request)
    {
        $videoTotalWatchTimeByUser = (new Visit())->videoTotalWatchTimeByUser([
            'user_id' => $request['id'],
            'start' => $request['start'],
            'end' => $request['end'],
        ]);

        if (is_wp_error($videoTotalWatchTimeByUser)) {
            return $videoTotalWatchTimeByUser;
        }
        $data['view'] = $videoTotalWatchTimeByUser;

        return rest_ensure_response($data);
    }



    /**
     * Total views on all videos by day
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function totalViewsByDay(\WP_REST_Request $request)
    {
        $views_by_day = (new Visit)->totalViewsByDay(array_filter([
            'start' => $request['start'],
            'end' => $request['end'],
        ]));

        $total = 0;

        if (!empty($views_by_day)) {
            foreach ($views_by_day as $key => $day) {
                $total = $total + (int)$day->total;
                $views_by_day[$key]->total = (int)$day->total;
            }
        }

        $response = rest_ensure_response($views_by_day);
        $response->header('X-WP-Total', (int) $total);

        return $response;
    }

    /**
     * Total watch time by day
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function totalWatchTimeByDay(\WP_REST_Request $request)
    {
        $time_by_day = (new Visit)->totalWatchTimeByDay(array_filter([
            'start' => $request['start'],
            'end' => $request['end'],
        ]));

        return rest_ensure_response($time_by_day);
    }

    /**
     * Total watch time by day
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function topUsers(\WP_REST_Request $request)
    {
        // set args
        $args = [
            'page' => $request['page'],
            'per_page' => $request['per_page'],
            'start' => $request['start'],
            'end' => $request['end'],
        ];

        $user_data = (new Visit)->topUsers(array_filter($args));
        if (is_wp_error($user_data)) {
            return $user_data;
        }

        $total = $user_data['total'];

        $controller = new \WP_REST_Users_Controller();
        $users = [];
        // attach user data
        foreach ($user_data['data'] as $data) {
            $user = get_user_by('ID', $data->user_id);

            if ($user) {
                // prepare user object for rest response
                $user = $controller->prepare_item_for_response($user, $request);
                $users[] = apply_filters('presto_top_user_stats', [
                    'user' => $user->data, // user object
                    'stats' => [ // user stats
                        [
                            'id' => 'views_count',
                            'title' => 'Views',
                            'data' => sprintf(__('%d views', 'presto-player-pro'), (int) $data->visit_count),
                        ],
                        [
                            'id' => 'average_duration',
                            'title' => __('Avg. View Time', 'presto-player-pro'),
                            'className' => 'presto-badge',
                            'data' => Utility::human_readable_duration(gmdate('H:i:s', $data->average_duration))
                        ]
                    ]
                ]);
            }
        }

        $max_pages  = ceil($total / (int) $request['per_page'] ?? 10);

        $response = rest_ensure_response($users);
        $response->header('X-WP-Total', (int) $total);
        $response->header('X-WP-TotalPages', (int) $max_pages);

        return $response;
    }

    /**
     * Total watch time by day
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function topVideos(\WP_REST_Request $request)
    {
        // set args
        $args = [
            'page' => $request['page'],
            'per_page' => $request['per_page'],
            'start' => $request['start'],
            'end' => $request['end'],
            'user_id' => $request['user_id'],
        ];


        $top = (new Visit)->topVideos(array_filter($args));

        if (is_wp_error($top)) {
            return $top;
        }

        $total = $top['total'];
        $max_pages  = ceil($total / (int) $request['per_page'] ?? 10);
        $video_data = $top['data'];

        $response = rest_ensure_response($video_data);
        $response->header('X-WP-Total', (int) $total);
        $response->header('X-WP-TotalPages', (int) $max_pages);

        return $response;
    }

    /**
     * Prepare the item for response back to the client
     * Ensures we're only passing specific fields and double-checks sanitization
     *
     * @param WP_REST_Request $request Request object
     * @return WP_Error|object $prepared_item
     */
    public function prepareItemForResponse($item, $request)
    {
        $schema = $this->visitSchema();
        $prepared = [];
        foreach ($item as $name => $value) {
            if (!empty($schema['properties'][$name])) {
                $prepared[$name] = rest_sanitize_value_from_schema($value, $schema['properties'][$name], $name);
            }
        }

        return $prepared;
    }

    /**
     * API Schema
     *
     * @return array
     */
    public function visitSchema()
    {
        return [
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            'title'                => 'video',
            'type'                 => 'object',
            'properties'           => [
                'id' => [
                    'description'  => esc_html__('Unique identifier for the object.', 'presto-player-pro'),
                    'type'         => 'integer',
                    'context'      => array('view', 'edit', 'embed'),
                    'readonly'     => true,
                ],
                'user_id' => [
                    'description'  => esc_html__('ID of user who made the visit.', 'presto-player-pro'),
                    'type'         => 'integer',
                ],
                'duration' => [
                    'description'  => esc_html__('Duration of the view.', 'presto-player-pro'),
                    'type' => 'integer',
                ],
                'video_id' => [
                    'description'  => esc_html__('ID of the video', 'presto-player-pro'),
                    'type' => 'integer',
                ],
                'ip_address' => [
                    'type' => 'string',
                    'readonly' => true
                ],
                'created_at' => [
                    'type' => 'string',
                    'readonly'     => true,
                ],
                'updated_at' => [
                    'type' => 'string',
                    'readonly'     => true,
                ],
                'deleted_at' => [
                    'type' => 'string',
                    'readonly'     => true,
                ]
            ],
        ];
    }

    public function collectionParams()
    {
        return [
            'start' => [
                'description' => __('Limit response to posts published after a given ISO8601 compliant date.', 'presto-player-pro'),
                'type'        => 'string',
                'format'      => 'date-time',
            ],
            'end' => [
                'description' => __('Limit response to posts published before a given ISO8601 compliant date.', 'presto-player-pro'),
                'type'        => 'string',
                'format'      => 'date-time',
            ],
            'page' => [
                'description' => __('Get a current page', 'presto-player-pro'),
                'type'        => 'integer',
            ],
            'per_page' => [
                'description' => __('Get a current page', 'presto-player-pro'),
                'type'        => 'integer',
            ]
        ];
    }
}
