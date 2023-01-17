<?php

namespace PrestoPlayer\Pro\Services\API;

use PrestoPlayer\Pro\Models\Bunny\Account;
use PrestoPlayer\Pro\Libraries\BunnyApiRequest;
use PrestoPlayer\Pro\Controllers\BunnyStreamController;
use PrestoPlayer\Pro\Controllers\BunnyVideosController;
use PrestoPlayer\Pro\Services\Bunny\Storage\StorageAPI;
use PrestoPlayer\Pro\Controllers\BunnyCollectionsController;

class RestBunnyStreamController
{
    protected $namespace = 'presto-player';
    protected $version = 'v1';
    protected $base = 'bunny/stream';
    protected $account;

    public function __construct()
    {
        $this->account = new Account();
    }

    /**
     * Register controller
     *
     * @return void
     */
    public function register()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Register presets routes
     *
     * @return void
     */
    public function register_routes()
    {
        /**
         * Save API Key
         */
        register_rest_route("{$this->namespace}/{$this->version}", '/' . $this->base . '/api-key', [
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'save_key'],
                'permission_callback' => [$this, 'connect_permissions_check'],
                'args'                => [
                    'api_key' => [
                        'type' => 'string',
                    ]
                ],
            ],
        ]);

        /**
         * Create storage zones
         */
        register_rest_route("{$this->namespace}/{$this->version}", '/' . $this->base . '/library', [
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'create_video_library'],
                'permission_callback' => [$this, 'connect_permissions_check'],
                'args'                => [
                    'type' => [
                        'type' => 'string',
                        'required' => true,
                        'validate_callback' => function ($value) {
                            return in_array($value, ['public', 'private']);
                        }
                    ]
                ],
            ],
        ]);

        /**
         * Upload videos to temp directory in chunks
         */
        register_rest_route("{$this->namespace}/{$this->version}", '/' . $this->base . '/upload', [
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'upload_file'],
                'permission_callback' => [$this, 'upload_files_permissions_check'],
                'args'                => [
                    'name' => [
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_file_name',
                        'required' => true,
                    ],
                    'chunk' => [
                        'type' => 'integer',
                        'required' => true
                    ],
                    'chunks' => [
                        'type' => 'integer',
                        'required' => true
                    ]
                ],
            ],
            [
                'methods'             => \WP_REST_Server::DELETABLE,
                'callback'            => [$this, 'delete_file'],
                'permission_callback' => [$this, 'upload_files_permissions_check'],
                'args'                => [
                    'name' => [
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_file_name',
                        'required' => true,
                    ]
                ],
            ],
        ]);

        /**
         * Store on Bunny.net
         */
        register_rest_route("{$this->namespace}/{$this->version}", '/' . $this->base . '/store', [
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => [$this, 'store_video'],
            'permission_callback' => [$this, 'upload_files_permissions_check'],
            'args'                => [
                'type' => [
                    'type' => 'string',
                    'required' => true,
                    'validate_callback' => function ($value) {
                        return in_array($value, ['public', 'private']);
                    }
                ],
                'guid' => [
                    'type' => 'string',
                    'required' => true,
                ],
                'path' => [
                    'type' => 'string',
                    'required' => true
                ],
            ],
        ]);

        /**
         * Read and store collections
         */
        register_rest_route("{$this->namespace}/{$this->version}", '/' . $this->base . '/collections', [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this, 'fetch_collections'],
                'permission_callback' => [$this, 'read_files_permissions_check'],
                'args'                => [
                    'type' => [
                        'type' => 'string',
                        'required' => true,
                        'validate_callback' => function ($value) {
                            return in_array($value, ['public', 'private']);
                        }
                    ],
                ]
            ],
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'create_collection'],
                'permission_callback' => [$this, 'upload_files_permissions_check'],
                'args'                => [
                    'type' => [
                        'type' => 'string',
                        'required' => true,
                        'validate_callback' => function ($value) {
                            return in_array($value, ['public', 'private']);
                        }
                    ],
                    'name' => [
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_text_field',
                        'required' => true,
                    ],
                ],
            ],
        ]);

        /**
         * Read, Update, Delete
         */
        register_rest_route("{$this->namespace}/{$this->version}", '/' . $this->base . '/collections/(?P<guid>[\S]+)', [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this, 'fetch_collection'],
                'permission_callback' => [$this, 'read_files_permissions_check'],
                'args'                => [
                    'type' => [
                        'type' => 'string',
                        'required' => true,
                        'validate_callback' => function ($value) {
                            return in_array($value, ['public', 'private']);
                        }
                    ],
                ]
            ],
            [
                'methods'             => \WP_REST_Server::EDITABLE,
                'callback'            => [$this, 'update_collection'],
                'permission_callback' => [$this, 'read_files_permissions_check'],
                'args'                => [
                    'type' => [
                        'type' => 'string',
                        'required' => true,
                        'validate_callback' => function ($value) {
                            return in_array($value, ['public', 'private']);
                        }
                    ],
                ]
            ],
            [
                'methods'             => \WP_REST_Server::DELETABLE,
                'callback'            => [$this, 'delete_collection'],
                'permission_callback' => [$this, 'read_files_permissions_check'],
                'args'                => [
                    'type' => [
                        'type' => 'string',
                        'required' => true,
                        'validate_callback' => function ($value) {
                            return in_array($value, ['public', 'private']);
                        }
                    ],
                ]
            ],
        ]);


        /**
         * Read and store public videos
         */
        register_rest_route("{$this->namespace}/{$this->version}", '/' . $this->base . '/videos', [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this, 'fetch_videos'],
                'permission_callback' => [$this, 'read_files_permissions_check'],
                'args'                => [
                    'type' => [
                        'type' => 'string',
                        'required' => true,
                        'validate_callback' => function ($value) {
                            return in_array($value, ['public', 'private']);
                        }
                    ],
                    'page' => [
                        'type' => 'integer',
                    ],
                    'search' => [
                        'type' => 'string'
                    ],
                    'items_per_page'  => [
                        'type' => 'integer',
                    ],
                    'collection' => [
                        'type' => 'string'
                    ],
                    'order_by' => [
                        'type' => 'string'
                    ]
                ]
            ],
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'create_video'],
                'permission_callback' => [$this, 'upload_files_permissions_check'],
                'args'                => [
                    'type' => [
                        'type' => 'string',
                        'required' => true,
                        'validate_callback' => function ($value) {
                            return in_array($value, ['public', 'private']);
                        }
                    ],
                    'name' => [
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_text_field',
                        'required' => true,
                    ],
                    'collection' => [
                        'type' => 'string',
                    ]
                ],
            ],
        ]);

        /**
         * Read, Update, Delete
         */
        register_rest_route("{$this->namespace}/{$this->version}", '/' . $this->base . '/videos/(?P<guid>[\S]+)', [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this, 'fetch_video'],
                'permission_callback' => [$this, 'read_files_permissions_check'],
                'args'                => [
                    'type' => [
                        'type' => 'string',
                        'required' => true,
                        'validate_callback' => function ($value) {
                            return in_array($value, ['public', 'private']);
                        }
                    ],
                ]
            ],
            [
                'methods'             => \WP_REST_Server::EDITABLE,
                'callback'            => [$this, 'update_video'],
                'permission_callback' => [$this, 'read_files_permissions_check'],
                'args'                => [
                    'type' => [
                        'type' => 'string',
                        'required' => true,
                        'validate_callback' => function ($value) {
                            return in_array($value, ['public', 'private']);
                        }
                    ],
                ]
            ],
            [
                'methods'             => \WP_REST_Server::DELETABLE,
                'callback'            => [$this, 'delete_video'],
                'permission_callback' => [$this, 'read_files_permissions_check'],
                'args'                => [
                    'type' => [
                        'type' => 'string',
                        'required' => true,
                        'validate_callback' => function ($value) {
                            return in_array($value, ['public', 'private']);
                        }
                    ],
                ]
            ],
        ]);
    }

    /**
     * Can the person read files from bunny
     * @return bool
     */
    public function read_files_permissions_check(\WP_REST_Request $request)
    {
        return apply_filters('presto_player_bunny_read_permissions', current_user_can('upload_files'), $request);
    }

    /**
     * Can the person connect to bunny
     * @return bool
     */
    public function connect_permissions_check(\WP_REST_Request $request)
    {
        return apply_filters('presto_player_bunny_connect_permissions', $this->read_files_permissions_check($request), $request);
    }

    /**
     * Can the person upload files to Bunny
     * @return bool
     */
    public function upload_files_permissions_check(\WP_REST_Request $request)
    {
        return apply_filters('presto_player_bunny_upload_permissions', $this->read_files_permissions_check($request), $request);
    }

    /**
     * Tests and saves the api key
     * 
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function save_key(\WP_REST_Request $request)
    {
        $api_key = $request['api_key'] ? $request['api_key'] : $this->account->get('api_key');

        $client = new BunnyApiRequest($api_key);
        $test = $client->get('billing');

        if (is_wp_error($test)) {
            return $test;
        }

        // save
        $this->account->update('api_key', $api_key);

        // response
        return new \WP_REST_Response(['success' => (bool)$this->account->get('api_key')]);
    }

    /**
     * Creates a video library
     * 
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function create_video_library(\WP_REST_Request $request)
    {
        $library = (new BunnyStreamController())->setup($request['type']);
        return rest_ensure_response($library);
    }

    /**
     * Fetch video collections
     *
     * @param \WP_REST_Request $request
     * @return void
     */
    public function fetch_collections(\WP_REST_Request $request)
    {
        $collections = (new BunnyCollectionsController($request['type']))->fetch(
            [
                'page' => (int) $request['page'],
                'itemsPerPage' => (int)$request['items_per_page'],
                'search' => sanitize_text_field($request['search']),
                'orderBy' => sanitize_text_field($request['order_by']),
            ]
        );

        return rest_ensure_response($collections);
    }

    /**
     * Create a collection
     *
     * @param \WP_REST_Request $request
     * @return void
     */
    public function create_collection(\WP_REST_Request $request)
    {
        $collection = (new BunnyCollectionsController($request['type']))->create(
            [
                'name' => $request['name'],
            ]
        );

        if (is_wp_error($collection)) {
            return $collection;
        }

        return rest_ensure_response($collection);
    }

    /**
     * Fetch videos from public storage zone
     * 
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function fetch_videos(\WP_REST_Request $request)
    {
        $videos = (new BunnyVideosController($request['type']))->fetch(
            [
                'page' => (int) $request['page'],
                'itemsPerPage' => (int)$request['items_per_page'],
                'search' => sanitize_text_field($request['search']),
                'collection' => sanitize_text_field($request['collection']),
                'orderBy' => sanitize_text_field($request['order_by']),
            ]
        );

        if (is_wp_error($videos)) {
            return $videos;
        }

        // prepare for response
        $items = [];
        foreach ($videos->items as $key => $item) {
            $item->size = $item->storageSize;
            $item->thumbnail = $item->webPURL;
            $item->updated_at = $item->dateUploaded;
            $item->created_at = $item->dateUploaded;
            $items[] = $item;
        }
        $videos->items = $items;

        return rest_ensure_response($videos);
    }

    /**
     * Fetch individual video
     */
    public function fetch_video(\WP_REST_Request $request)
    {
        $video = (new BunnyVideosController($request['type']))->get($request['guid']);
        return rest_ensure_response($video);
    }

    /**
     * Upload videos to public storage zone
     * 
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function upload_file(\WP_REST_Request $request)
    {
        $files   = $request->get_file_params();

        // must have a file
        if (empty($files['file'])) {
            return new \WP_Error('rest_upload_no_file', __('Please send a file.'), ['status' => 400]);
        }

        // get file info
        $file = $files['file'];
        $name = $request['name'];
        $chunk = $request['chunk'];
        $chunks = $request['chunks'];

        $mimes = apply_filters('presto_player_allowed_mime_types', [
            'asf|asx'                      => 'video/x-ms-asf',
            'wmv'                          => 'video/x-ms-wmv',
            'wmx'                          => 'video/x-ms-wmx',
            'wm'                           => 'video/x-ms-wm',
            'avi'                          => 'video/avi',
            'divx'                         => 'video/divx',
            'flv'                          => 'video/x-flv',
            'mov|qt'                       => 'video/quicktime',
            'mpeg|mpg|mpe'                 => 'video/mpeg',
            'mp4|m4v'                      => 'video/mp4',
            'ogv'                          => 'video/ogg',
            'webm'                         => 'video/webm',
            'mkv'                          => 'video/x-matroska',
            '3gp|3gpp'                     => 'video/3gpp',  // Can also be audio.
            '3g2|3gp2'                     => 'video/3gpp2', // Can also be audio.
        ]);

        // A correct MIME type will pass this test. Override $mimes or use the upload_mimes filter.
        $wp_filetype     = wp_check_filetype_and_ext(sanitize_file_name($file['tmp_name']), $name, $mimes);
        $ext             = empty($wp_filetype['ext']) ? '' : $wp_filetype['ext'];
        $type            = empty($wp_filetype['type']) ? '' : $wp_filetype['type'];
        $proper_filename = empty($wp_filetype['proper_filename']) ? '' : $wp_filetype['proper_filename'];

        // Check to see if wp_check_filetype_and_ext() determined the filename was incorrect.
        if ($proper_filename) {
            $name = $proper_filename;
        }

        if ((!$type || !$ext) && !current_user_can('unfiltered_upload')) {
            return new \WP_Error('invalid_type', __('Sorry, this file type is not permitted for security reasons.'), ['status' => 400]);
        }

        if (!$type) {
            $type = $file['type'];
        }

        // temp dir and file path
        $temp_dir = get_temp_dir();
        $file_path = trailingslashit($temp_dir) . $name;

        // create temporary part
        $out = @fopen("{$file_path}.part", $chunk == 0 ? 'wb' : 'ab');
        if ($out) {
            /** Read binary input stream and append it to temp file. */
            $in = @fopen($file['tmp_name'], 'rb');

            if ($in) {
                while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                }
            } else {
                /** Failed to open input stream. */
                /** Attempt to clean up unfinished output. */
                @fclose($out);
                @unlink("{$file_path}.part");
                return new \WP_Error('input_stream_failed', 'Failed to open input stream.');
            }

            @fclose($in);
            @fclose($out);

            @unlink($file['tmp_name']);
        }

        if (!$chunks || $chunk == $chunks) {
            // Strip the temp .part suffix off
            rename("{$file_path}.part", $file_path);
        }

        // send back file path for uploading
        return rest_ensure_response(['path' => esc_url_raw($file_path), 'name' => esc_html($name)]);
    }

    /**
     * Upload videos to public storage zone
     * 
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function delete_file(\WP_REST_Request $request)
    {
        $files   = $request->get_file_params();

        // must have a file
        if (empty($files['file'])) {
            return new \WP_Error('rest_upload_no_file', __('Please send a file.'), ['status' => 400]);
        }

        // get file info
        $file = $files['file'];
        $name = $request['name'];

        $mimes = apply_filters('presto_player_allowed_mime_types', [
            'asf|asx'                      => 'video/x-ms-asf',
            'wmv'                          => 'video/x-ms-wmv',
            'wmx'                          => 'video/x-ms-wmx',
            'wm'                           => 'video/x-ms-wm',
            'avi'                          => 'video/avi',
            'divx'                         => 'video/divx',
            'flv'                          => 'video/x-flv',
            'mov|qt'                       => 'video/quicktime',
            'mpeg|mpg|mpe'                 => 'video/mpeg',
            'mp4|m4v'                      => 'video/mp4',
            'ogv'                          => 'video/ogg',
            'webm'                         => 'video/webm',
            'mkv'                          => 'video/x-matroska',
            '3gp|3gpp'                     => 'video/3gpp',  // Can also be audio.
            '3g2|3gp2'                     => 'video/3gpp2', // Can also be audio.
        ]);

        // A correct MIME type will pass this test. Override $mimes or use the upload_mimes filter.
        $wp_filetype     = wp_check_filetype_and_ext(sanitize_file_name($file['tmp_name']), $name, $mimes);
        $ext             = empty($wp_filetype['ext']) ? '' : $wp_filetype['ext'];
        $type            = empty($wp_filetype['type']) ? '' : $wp_filetype['type'];
        $proper_filename = empty($wp_filetype['proper_filename']) ? '' : $wp_filetype['proper_filename'];

        // Check to see if wp_check_filetype_and_ext() determined the filename was incorrect.
        if ($proper_filename) {
            $name = $proper_filename;
        }

        if ((!$type || !$ext) && !current_user_can('unfiltered_upload')) {
            return new \WP_Error('invalid_type', __('Sorry, this file type is not permitted for security reasons.'), ['status' => 400]);
        }

        if (!$type) {
            $type = $file['type'];
        }

        // temp dir and file path
        $temp_dir = get_temp_dir();
        $file_path = trailingslashit($temp_dir) . $name;

        // remove temp file
        @unlink("{$file_path}.part");

        // send back file path for uploading
        return rest_ensure_response(true);
    }

    /**
     * Create a video to prepare for uploading
     * 
     * @param string $type Type of video to store (public or private)
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function create_video(\WP_REST_Request $request)
    {
        $video = (new BunnyVideosController($request['type']))->create(
            [
                'title' => $request['name'],
                'collectionId' => $request['collection']
            ]
        );

        return rest_ensure_response($video);
    }

    /**
     * Upload videos to storage zone
     * 
     * @param string $type Type of video to store (public or private)
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function store_video(\WP_REST_Request $request)
    {
        $uploaded = (new BunnyVideosController($request['type']))->upload(
            $request['path'],
            [
                'guid' => $request['guid']
            ]
        );

        // delete temp file, even on error
        @unlink($request['path']);

        return rest_ensure_response($uploaded);
    }

    /**
     * Delete video from storage
     * 
     * @param string $type Type of video to store (public or private)
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function delete_video(\WP_REST_Request $request)
    {
        $deleted = (new BunnyVideosController($request['type']))->delete($request['guid']);
        if (!$deleted) {
            return new \WP_Error('could_not_delete', 'Could not delete the video');
        }
        return true;
    }

    /**
     * Delete video from storage
     * 
     * @param string $type Type of video to store (public or private)
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function delete_collection(\WP_REST_Request $request)
    {
        $deleted = (new BunnyCollectionsController($request['type']))->delete($request['guid']);
        if (!$deleted) {
            return new \WP_Error('could_not_delete', 'Could not delete the video');
        }
        return true;
    }
}
