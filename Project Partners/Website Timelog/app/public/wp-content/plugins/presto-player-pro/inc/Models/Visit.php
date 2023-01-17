<?php

namespace PrestoPlayer\Pro\Models;

use PrestoPlayer\Models\Model;
use PrestoPlayer\Models\Video;
use PrestoPlayer\Support\Utility;

class Visit extends Model
{
    protected $table = 'presto_player_visits';

    /**
     * Model Schema
     *
     * @var array
     */
    public function schema()
    {
        return [
            'id' => [
                'type' => 'integer',
            ],
            'user_id' => [
                'type' => 'integer',
                'default' => get_current_user_id()
            ],
            'duration' => [
                'type' => 'integer',
            ],
            'video_id' => [
                'type' => 'integer',
            ],
            'ip_address' => [
                'type' => 'string',
                'sanitize_callback' => function ($ip) {
                    return filter_var($ip, FILTER_VALIDATE_IP);
                },
                'default' => Utility::getIPAddress()
            ],
            'created_at' => [
                'type' => 'string'
            ],
            'updated_at' => [
                'type' => 'string',
            ],
            'deleted_at' => [
                'type' => 'string'
            ]
        ];
    }

    /**
     * Queryable attributes
     *
     * @var array
     */
    protected $queryable = ['id', 'user_id', 'duration', 'video_id', 'ip_address'];

    /**
     * Top videos
     *
     * @param array $args
     * @return array
     */
    public function topVideos($args)
    {
        global $wpdb;

        $args = wp_parse_args(
            $args,
            [
                'duration' => false,
                'page' => 1,
                'per_page' => 10,
                'start' => date('Y-m-d H:i:s', strtotime("-1 week")),
                'end' => current_time('mysql')
            ]
        );

        $limit = (int) $args['per_page'];
        $offset = (int) ($args['per_page'] * ($args['page'] - 1));
        $pagination = "LIMIT $limit OFFSET $offset ";

        $filter = "AND 1 = 1 ";
        if (isset($args['user_id'])) {
            $filter .= " AND user_id = ".esc_sql($args['user_id']);
            $filter .= " AND duration > 0 ";
        }

        $args['start'] = date('Y-m-d 00:00:00', strtotime($args['start']));
        $args['end']  = date('Y-m-d 23:59:59', strtotime($args['end']));

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT video_id as id,
                    count(video_id) AS visit_count,
                    AVG(duration) AS average_duration
                FROM {$wpdb->prefix}{$this->table}
                WHERE (created_at  >= '%1s' and created_at <='%2s') $filter
                GROUP BY video_id
                ORDER BY visit_count DESC
                $pagination",
                $args['start'],
                $args['end']
            )
        );

        $total = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT count(*) GroupAmountTimes from
                    (
                        SELECT video_id as id,
                        COUNT(video_id) AS visit_count
                        FROM {$wpdb->prefix}{$this->table}
                        WHERE (created_at  >= '%1s' and created_at <='%2s') $filter
                        GROUP BY video_id
                        ORDER BY visit_count DESC
                    ) as SubQuery
                ",
                $args['start'],
                $args['end']
            )
        );

        $output = [];
        foreach ($results as $data) {
            $output[] = [
                'video' => (new Video)->get($data->id)->toObject(),
                'stats' =>  [
                    [
                        'id' => 'views_count',
                        'title' => __('Views', 'presto-player-pro'),
                        'data' => sprintf(__('%d views', 'presto-player-pro'), (int) $data->visit_count),
                    ],
                    [
                        'id' => 'average_duration',
                        'title' => __('Avg. View Time', 'presto-player-pro'),
                        'className' => 'presto-badge',
                        'data' => (int) $data->average_duration ? Utility::human_readable_duration(gmdate('H:i:s', $data->average_duration)) : __('0 seconds')
                    ],
                ]
            ];
        }

        return [
            'total' => (int) $total,
            'data' => $output
        ];
    }

    public function topUsers($args)
    {
        global $wpdb;

        $args = wp_parse_args(
            $args,
            [
                'page' => 1,
                'per_page' => 10,
                'start' => date('Y-m-d H:i:s', strtotime("-1 week")),
                'end' => current_time('mysql')
            ]
        );

        $limit = (int) $args['per_page'];
        $offset = (int) ($args['per_page'] * ($args['page'] - 1));
        $pagination = "LIMIT $limit OFFSET $offset ";
        $args['start'] = date('Y-m-d 00:00:00', strtotime($args['start']));
        $args['end']  = date('Y-m-d 23:59:59', strtotime($args['end']));

        $output = [];
        $total = 0;

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT user_id, count(user_id) as visit_count, AVG(duration) as average_duration FROM
                {$wpdb->prefix}{$this->table}
                WHERE (created_at between '%2s' and '%3s') AND duration > 0
                AND user_id IS NOT NULL
                GROUP BY user_id
                ORDER BY COUNT(user_id) desc
                $pagination",
                $args['start'],
                $args['end']
            )
        );

        if (count($results) != 0) {
            $output = $results;
            $total  = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT count(*) total from(
                            SELECT count(id) FROM
                            {$wpdb->prefix}{$this->table}
                            WHERE (created_at between '%1s' and '%2s') AND duration > 0
                            AND user_id IS NOT NULL
                            GROUP BY user_id
                        ) as SubQuery
                        ",
                    $args['start'],
                    $args['end']
                )
            );
        }

        return [
            'total' => (int) $total,
            'data' => $output
        ];
    }

    public function topBy($column_name, $args)
    {
        global $wpdb;

        $args = wp_parse_args(
            $args,
            [
                'limit' => 10,
                'duration' => false,
                'start' => date('Y-m-d H:i:s', strtotime("-1 week")),
                'end' => current_time('mysql')
            ]
        );

        $args['start'] = date('Y-m-d 00:00:00', strtotime($args['start']));
        $args['end']  = date('Y-m-d 23:59:59', strtotime($args['end']));

        if ($args['duration'] !== false) {
            return $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT %1s, count(*) as visit_count, AVG(duration) as average_duration FROM
                    {$wpdb->prefix}{$this->table}
                    WHERE (created_at between '%2s' and '%3s') AND duration > 0
                    GROUP BY %4s
                    ORDER BY COUNT(%5s) desc
                    LIMIT %6d",
                    $column_name,
                    $args['start'],
                    $args['end'],
                    $column_name,
                    $column_name,
                    $args['limit']
                )
            );
        }

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT %1s, count(*) as visit_count, AVG(duration) as average_duration FROM
                {$wpdb->prefix}{$this->table}
                WHERE (created_at between '%2s' and '%3s')
                GROUP BY %4s
                ORDER BY COUNT(%5s) desc
                LIMIT %6d",
                $column_name,
                $args['start'],
                $args['end'],
                $column_name,
                $column_name,
                $args['limit']
            )
        );
    }

    public function totalWatchTimeByDay($args)
    {
        global $wpdb;

        $args = wp_parse_args(
            $args,
            [
                'start' => date('Y-m-d H:i:s', strtotime("-1 week")),
                'end' => current_time('mysql')
            ]
        );

        $args['start'] = date('Y-m-d 00:00:00', strtotime($args['start']));
        $args['end']  = date('Y-m-d 23:59:59', strtotime($args['end']));

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT SUM(duration) as total, created_at as date_time
                FROM {$wpdb->prefix}{$this->table}
                WHERE (created_at >= '%1s' and created_at <= '%2s') AND duration > 0
                GROUP BY DAY(created_at)
                ORDER BY created_at",
                $args['start'],
                $args['end']
            )
        );

        $average = (float) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT AVG(duration) as average_watch_time
                FROM {$wpdb->prefix}{$this->table}
                WHERE (created_at >= '%1s' and created_at <= '%2s') AND duration > 0",
                $args['start'],
                $args['end']
            )
        );

        return [
            'average' => $average,
            'data' => $results
        ];
    }

    /**
     * Video views
     *
     * @return void
     */
    public function views($args)
    {
        global $wpdb;

        $args = wp_parse_args(
            $args,
            [
                'start' => date('Y-m-d H:i:s', strtotime("-1 week")),
                'end' => current_time('mysql')
            ]
        );

        if (!$args['video_id']) {
            return new \WP_Error('missing_parameter', "You must provide a video id.");
        }

        // make sure it's the beginning and end of the day.
        $args['start'] = date('Y-m-d 00:00:00', strtotime($args['start']));
        $args['end']  = date('Y-m-d 23:59:59', strtotime($args['end']));

        $result = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT COUNT(*) as total
                FROM {$wpdb->prefix}{$this->table}
                WHERE video_id = %d
                AND (created_at >= '%1s' and created_at <= '%2s') AND duration > 0",
                $args['video_id'],
                $args['start'],
                $args['end']
            )
        );

        return !empty($result[0]->total) ? (int) $result[0]->total : 0;
    }

    /**
     * Video average watch time
     *
     * @param array $args
     * @return float
     */
    public function averageWatchTime($args = [])
    {
        global $wpdb;

        $args = wp_parse_args(
            $args,
            [
                'limit' => 10,
                'duration' => false,
                'start' => date('Y-m-d H:i:s', strtotime("-1 week")),
                'end' => current_time('mysql')
            ]
        );

        $args['start'] = date('Y-m-d 00:00:00', strtotime($args['start']));
        $args['end']  = date('Y-m-d 23:59:59', strtotime($args['end']));

        return (float) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT AVG(duration) as average_watch_time
                FROM {$wpdb->prefix}{$this->table}
                WHERE {$wpdb->prefix}{$this->table}.video_id =  %1d
                AND ({$wpdb->prefix}{$this->table}.created_at between '%2s' and '%3s')",
                $args['video_id'],
                $args['start'],
                $args['end']
            )
        );
    }

    public function timeline($args = [])
    {
        global $wpdb;

        $args = wp_parse_args(
            $args,
            [
                'start' => date('Y-m-d H:i:s', strtotime("-1 week")),
                'end' => current_time('mysql')
            ]
        );

        if (!$args['video_id']) {
            return new \WP_Error('missing_parameter', "You must provide a video id.");
        }

        // make sure it's the beginning and end of the day.
        $args['start'] = date('Y-m-d 00:00:00', strtotime($args['start']));
        $args['end']  = date('Y-m-d 23:59:59', strtotime($args['end']));

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT COUNT(id) as total, duration as watch_time
                FROM {$wpdb->prefix}{$this->table}
                WHERE video_id = %d
                AND (created_at >= '%1s' and created_at <= '%2s')
                GROUP BY duration
                ORDER BY duration",
                $args['video_id'],
                $args['start'],
                $args['end']
            )
        );

        if (empty($results) || !is_array($results)) {
            return $results;
        }

        // get the total views on the video
        $total_views = 0;
        foreach ($results as $result) {
            $total_views = $total_views + (int) $result->total; // add to total
        }

        // all start at zero
        $output[] = (object) [
            'total' => $total_views,
            'watch_time' => 0
        ];

        foreach ($results as $result) {
            if ((int) $result->watch_time === 0) {
                continue;
            }
            $total_views = $total_views - $result->total;
            $output[] = (object) [
                'total' => $total_views,
                'watch_time' => (int) $result->watch_time
            ];
        }

        return $output;
    }

    public function totalViewsByDay($args)
    {
        global $wpdb;

        $args = wp_parse_args(
            $args,
            [
                'start' => date('Y-m-d H:i:s', strtotime("-1 week")),
                'end' => current_time('mysql')
            ]
        );


        // make sure it's the beginning and end of the day.
        $args['start'] = date('Y-m-d 00:00:00', strtotime($args['start']));
        $args['end']  = date('Y-m-d 23:59:59', strtotime($args['end']));

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT count(id) as total, created_at as date_time
                FROM {$wpdb->prefix}{$this->table}
                WHERE (created_at between '%1s' and '%2s') AND duration > 0
                GROUP BY DAY(created_at)
                ORDER BY created_at",
                $args['start'],
                $args['end']
            )
        );

        return !empty($results) ? $results : [];
    }

    /**
     * Total Video views by particular user
     *
     * @return void
     */
    public function totalVideoViewsByUser($args)
    {
        global $wpdb;

        $args = wp_parse_args(
            $args,
            [
                'start' => date('Y-m-d H:i:s', strtotime("-1 week")),
                'end' => current_time('mysql')
            ]
        );

        if (!$args['user_id']) {
            return new \WP_Error('missing_parameter', "You must provide a user id.");
        }

        // make sure it's the beginning and end of the day.
        $args['start'] = date('Y-m-d 00:00:00', strtotime($args['start']));
        $args['end']  = date('Y-m-d 23:59:59', strtotime($args['end']));

        return (float) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) as total
                FROM {$wpdb->prefix}{$this->table}
                WHERE user_id = %d
                AND (created_at >= '%1s' and created_at <= '%2s') AND duration > 0",
                $args['user_id'],
                $args['start'],
                $args['end']
            )
        );
    }

    /**
     * Average watch time by particular user
     *
     * @return void
     */
    public function videoAverageWatchTimeByUser($args)
    {
        global $wpdb;

        $args = wp_parse_args(
            $args,
            [
                'start' => date('Y-m-d H:i:s', strtotime("-1 week")),
                'end' => current_time('mysql')
            ]
        );

        if (!$args['user_id']) {
            return new \WP_Error('missing_parameter', "You must provide a user id.");
        }

        // make sure it's the beginning and end of the day.
        $args['start'] = date('Y-m-d 00:00:00', strtotime($args['start']));
        $args['end']  = date('Y-m-d 23:59:59', strtotime($args['end']));
        return (float) $wpdb->get_var(
            $wpdb->prepare(
                "
                    SELECT AVG(duration) AS average_duration
                    FROM {$wpdb->prefix}{$this->table}
                    WHERE user_id = %d
                    AND (created_at >= '%1s' and created_at <= '%2s') AND duration > 0
                    GROUP BY user_id
                ",
                $args['user_id'],
                $args['start'],
                $args['end']
            )
        );
    }


    /**
     * Total watch time by particular user
     *
     * @return void
     */
    public function videoTotalWatchTimeByUser($args)
    {
        global $wpdb;

        $args = wp_parse_args(
            $args,
            [
                'start' => date('Y-m-d H:i:s', strtotime("-1 week")),
                'end' => current_time('mysql')
            ]
        );

        if (!$args['user_id']) {
            return new \WP_Error('missing_parameter', "You must provide a user id.");
        }

        // make sure it's the beginning and end of the day.
        $args['start'] = date('Y-m-d 00:00:00', strtotime($args['start']));
        $args['end']  = date('Y-m-d 23:59:59', strtotime($args['end']));
        return (float) $wpdb->get_var(
            $wpdb->prepare(
                "
                    SELECT sum(duration) AS total_duration
                    FROM {$wpdb->prefix}{$this->table}
                    WHERE user_id = %d
                    AND (created_at >= '%1s' and created_at <= '%2s') AND duration > 0
                    GROUP BY user_id
                ",
                $args['user_id'],
                $args['start'],
                $args['end']
            )
        );
    }

    public function checkDate($date)
    {
        $mm         = substr($date, 5, 2);
        $jj         = substr($date, 8, 2);
        $aa         = substr($date, 0, 4);
        $valid_date = wp_checkdate($mm, $jj, $aa, $date);
        if (!$valid_date) {
            return new \WP_Error('invalid_date', __('Invalid date.', 'presto-player-pro'), 400);
        }
    }

    public function deleteOlderThan($day_string)
    {
        // fetch ids of older visits
        $ids = $this->fetch([
            'date_query' => [
                'before' => date('Y-m-d 00:00:00', strtotime("-" . sanitize_text_field($day_string))),
            ],
            'fields' => 'ids'
        ]);

        if (!empty($ids->data)) {
            $deleted = $this->bulkDelete($ids->data);
            if (!$deleted) {
                error_log('Presto Player: Could not delete visits ' . implode(',', array_map('absint', $ids->data)));
            }

            return $deleted;
        }

        return false;
    }
}
