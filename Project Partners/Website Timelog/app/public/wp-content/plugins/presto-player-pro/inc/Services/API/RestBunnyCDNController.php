<?php

namespace PrestoPlayer\Pro\Services\API;

use PrestoPlayer\Models\Setting;
use PrestoPlayer\Pro\Blocks\BunnyCDNBlock;
use PrestoPlayer\Pro\Support\Utility;
use PrestoPlayer\Pro\Services\Bunny\URL;
use PrestoPlayer\Pro\Models\Bunny\Account;
use PrestoPlayer\Pro\Models\Bunny\PullZone;
use PrestoPlayer\Pro\Models\Bunny\StorageZone;
use PrestoPlayer\Pro\Services\Bunny\BunnyService;
use PrestoPlayer\Pro\Services\Bunny\CDNRequest;
use PrestoPlayer\Pro\Services\Bunny\StorageRequest;
use PrestoPlayer\Pro\Services\Bunny\Storage\StorageAPI;

class RestBunnyCDNController
{
    protected $namespace = 'presto-player';
    protected $version = 'v1';
    protected $base = 'bunny';
    protected $cdn_api;
    protected $account;
    protected $storage_zone;
    protected $pull_zone;

    public function __construct()
    {
        $this->cdn_api = new CDNRequest();
        $this->account = new Account();
        $this->storage_zone = new StorageZone();
        $this->pull_zone = new PullZone();
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
        register_rest_route(
            "{$this->namespace}/{$this->version}", '/' . $this->base . '/api-key', [
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'save_key'],
                'permission_callback' => [$this, 'connect_permissions_check'],
                'args'                => [
                    'api_key' => [
                        'required' => true,
                    ]
                ],
            ],
            ]
        );

        /**
         * Create storage zones
         */
        register_rest_route(
            "{$this->namespace}/{$this->version}", '/' . $this->base . '/storage-zones', [
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'create_storage_zones'],
                'permission_callback' => [$this, 'connect_permissions_check'],
            ],
            ]
        );


        /**
         * Create pull zones
         */
        register_rest_route(
            "{$this->namespace}/{$this->version}", '/' . $this->base . '/pull-zones', [
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'create_pull_zones'],
                'permission_callback' => [$this, 'connect_permissions_check'],
            ],
            ]
        );

        /**
         * Upload videos to temp directory in chunks
         */
        register_rest_route(
            "{$this->namespace}/{$this->version}", '/' . $this->base . '/upload', [
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => [$this, 'upload_videos'],
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
            ]
        );

        /**
         * Read and store public videos
         */
        register_rest_route(
            "{$this->namespace}/{$this->version}", '/' . $this->base . '/public-videos', [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this, 'fetch_public_videos'],
                'permission_callback' => [$this, 'read_files_permissions_check'],
            ],
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'store_public_video'],
                'permission_callback' => [$this, 'upload_files_permissions_check'],
                'args'                => [
                    'name' => [
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_file_name',
                        'required' => true,
                    ],
                    'path' => [
                        'type' => 'string',
                        'required' => true
                    ],
                ],
            ],
            [
                'methods'             => \WP_REST_Server::DELETABLE,
                'callback'            => [$this, 'delete_public_video'],
                'permission_callback' => [$this, 'read_files_permissions_check'],
                'args'                => [
                    'name' => [
                        'type' => 'string',
                        'required' => true,
                    ],
                ],
            ],
            ]
        );

        /**
         * Read and upload private videos
         */
        register_rest_route(
            "{$this->namespace}/{$this->version}", '/' . $this->base . '/private-videos', [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this, 'fetch_private_videos'],
                'permission_callback' => [$this, 'read_files_permissions_check'],
            ],
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'store_private_video'],
                'permission_callback' => [$this, 'upload_files_permissions_check'],
                'args'                => [
                    'name' => [
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_file_name',
                        'required' => true,
                    ],
                    'path' => [
                        'type' => 'string',
                        'required' => true
                    ],
                ],
            ],
            [
                'methods'             => \WP_REST_Server::DELETABLE,
                'callback'            => [$this, 'delete_private_video'],
                'permission_callback' => [$this, 'read_files_permissions_check'],
                'args'                => [
                    'name' => [
                        'type' => 'string',
                        'required' => true,
                    ],
                ],
            ],
            ]
        );

        /** 
         * Sign urls
         */
        register_rest_route(
            "{$this->namespace}/{$this->version}", '/' . $this->base . '/sign', [
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'sign_url'],
                'permission_callback' => [$this, 'sign_permissions_check'],
                // must have a url and a video id to sign
                'args'                => [
                    'url' => [
                        'type' => 'string',
                        'validate_callback' => function ($url) {
                            return filter_var($url, FILTER_VALIDATE_URL);
                        },
                        'required' => true,
                    ]
                ],
            ],
            ]
        );
    }

    /**
     * Can the person read files from bunny
     *
     * @return bool
     */
    public function read_files_permissions_check(\WP_REST_Request $request)
    {
        return apply_filters('presto_player_bunny_read_permissions', current_user_can('upload_files'), $request);
    }

    /**
     * Can the person connect to bunny
     *
     * @return bool
     */
    public function connect_permissions_check(\WP_REST_Request $request)
    {
        return apply_filters('presto_player_bunny_connect_permissions', $this->read_files_permissions_check($request), $request);
    }

    /**
     * Can the person upload files to Bunny
     *
     * @return bool
     */
    public function upload_files_permissions_check(\WP_REST_Request $request)
    {
        return apply_filters('presto_player_bunny_upload_permissions', $this->read_files_permissions_check($request), $request);
    }

    /**
     * Can the person sign the url?
     * They must be logged in by default
     */
    public function sign_permissions_check(\WP_REST_Request $request)
    {
        return apply_filters('presto_player_bunny_sign_url_permissions', is_user_logged_in(), $request);
    }

    /**
     * Sign a private URL with a token
     * 
     * @param  \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function sign_url(\WP_REST_Request $request)
    {
        return rest_ensure_response(BunnyService::signURL($request['url']));
    }

    /**
     * Deletes stored data to create new connection
     */
    public function resetData()
    {
        $this->storage_zone->delete();
        $this->pull_zone->delete();
        $this->account->delete();
    }

    /**
     * Tests and saves the api key
     * 
     * @param  \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function save_key(\WP_REST_Request $request)
    {
        // reset data
        $this->resetData();

        // store api key.
        $this->account->update('api_key', $request['api_key']);

        // test api key by getting billing stuff
        $test = $this->cdn_api->get('billing');
        if (is_wp_error($test)) {
            $this->account->update('api_key', '');
            return $test;
        }

        return new \WP_REST_Response(['success' => (bool)$this->account->get('api_key')]);
    }

    /**
     * Creates two storage zones
     * One for private, one for public
     * 
     * @param  \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function create_storage_zones(\WP_REST_Request $request)
    {
        // get storagezone list
        $list = $this->cdn_api->get('storagezone');
        if (is_wp_error($list)) {
            return $list;
        }

        // find zones in list
        $created = $this->storage_zone->findZonesInList($list);
        $new = false;

        // maybe create private
        if (!$created['private']) {
            $new = $this->cdn_api->post(
                'storagezone', [
                'body' => [
                    'Name' => $this->storage_zone->getPrivateName(),
                    'Region' => Setting::get('bunny_cdn', 'storage_zone_region', 'NY'),
                    'ReplicationRegions' => Setting::get('bunny_cdn', 'storage_zone_replication_regions', ['DE'])
                ]
                ]
            );
            if (is_wp_error($new)) {
                return $new;
            }
        }

        // maybe create public
        if (!$created['public']) {
            $new = $this->cdn_api->post(
                'storagezone', [
                'body' => [
                    'Name' => $this->storage_zone->getPublicName(),
                    'Region' => Setting::get('bunny_cdn', 'storage_zone_region', 'NY'),
                    'ReplicationRegions' => Setting::get('bunny_cdn', 'storage_zone_replication_regions', ['DE'])
                ]
                ]
            );
            if (is_wp_error($new)) {
                return $new;
            }
        }

        // one has been newly created, refetch
        if ($new) {
            $list = $this->cdn_api->get('storagezone');
            $created = $this->storage_zone->findZonesInList($list);
        }

        // make sure we have ids
        if (empty($created['private']['Id'])) {
            return new \WP_Error('cannot_create', 'Could not create private storage zone.');
        }
        if (empty($created['public']['Id'])) {
            return new \WP_Error('cannot_create', 'Could not create public storage zone.');
        }

        // store public and private zones
        $this->storage_zone->update('private_id', $created['private']['Id']);
        $this->storage_zone->update('private_name', $created['private']['Name']);
        $this->storage_zone->update('public_id', $created['public']['Id']);
        $this->storage_zone->update('public_name', $created['public']['Name']);

        // update password keys
        $this->account->update('public_storage_zone_password', $created['public']['Password']);
        $this->account->update('private_storage_zone_password', $created['private']['Password']);

        // created
        return rest_ensure_response(true);
    }

    /**
     * Creates two storage zones
     * One for private, one for public
     * 
     * @param  \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function create_pull_zones(\WP_REST_Request $request)
    {
        // storage zone ids need to be created
        if (!$this->storage_zone['public_id'] || !$this->storage_zone['private_id']) {
            return new \WP_Error('storage_zones_not_created', 'The public storage zone has not been created.');
        }

        // get storagezone list
        $list = $this->cdn_api->get('pullzone');
        if (is_wp_error($list)) {
            return $list;
        }

        // find zones in list
        $created = $this->pull_zone->findZonesInList($list);
        $new = false;

        // maybe create public
        if (!$created['public']) {
            $new = $this->cdn_api->post(
                'pullzone', [
                'body' => [
                    'Name' => $this->pull_zone->getPublicName(),
                    'Type' => 1, // high volume,
                    'StorageZoneId' => $this->storage_zone['public_id'],
                    'OriginUrl' => get_site_url()
                ]
                ]
            );
            if (is_wp_error($new)) {
                return $new;
            }
        }

        // maybe create private
        if (!$created['private']) {
            $new = $this->cdn_api->post(
                'pullzone', [
                'body' => [
                    'Name' => $this->pull_zone->getPrivateName(),
                    'Type' => 1, // high volume,
                    'StorageZoneId' => $this->storage_zone['private_id'],
                    'OriginUrl' => get_site_url()
                ]
                ]
            );
            if (is_wp_error($new)) {
                return $new;
            }
        }

        // one has been newly created, refetch
        if ($new) {
            $list = $this->cdn_api->get('pullzone');
            $created = $this->pull_zone->findZonesInList($list);
        }

        // make sure we have ids
        if (empty($created['private']['Id'])) {
            return new \WP_Error('cannot_create', 'Could not create private pull zone.');
        }
        if (empty($created['public']['Id'])) {
            return new \WP_Error('cannot_create', 'Could not create public pull zone.');
        }

        // always update private pullzone for token auth
        $private = $created['private'];
        $private['ZoneSecurityEnabled'] = true;
        $private['ZoneSecurityIncludeHashRemoteIP'] = false;
        $private['EnableCacheSlice'] = true; // optimize for video
        $update = $this->cdn_api->post("pullzone/{$private['Id']}", ['body' => $private]);
        if (is_wp_error($update)) {
            return $update;
        }

        $public = $created['public'];
        $public['EnableCacheSlice'] = true; // optimize for video
        $update = $this->cdn_api->post("pullzone/{$public['Id']}", ['body' => $public]);
        if (is_wp_error($update)) {
            return $update;
        }

        // store private id and hostnames
        $this->pull_zone->update('private_id', $created['private']['Id']);
        $this->pull_zone->update('private_hostname', $created['private']['Hostnames'][0]['Value']);
        $this->pull_zone->update('private_security_key', $created['private']['ZoneSecurityKey']);

        // store public id and hostnames
        $this->pull_zone->update('public_id', $created['public']['Id']);
        $this->pull_zone->update('public_hostname', $created['public']['Hostnames'][0]['Value']);

        // check it's saved
        foreach (['public_id', 'public_hostname', 'private_id', 'private_hostname', 'private_security_key'] as $name) {
            if (!isset($this->pull_zone[$name])) {
                return new \WP_Error('cannot_update_zones', 'Pull zones could not be linked');
            }
        }

        // created
        return rest_ensure_response(true);
    }

    /**
     * Fetch videos from public storage zone
     * 
     * @param  \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function fetch_public_videos(\WP_REST_Request $request)
    {
        return $this->fetch_videos('public', $request);
    }

    /**
     * Fetch videos from private storage zone
     * 
     * @param  \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function fetch_private_videos(\WP_REST_Request $request)
    {
        return $this->fetch_videos('private', $request);
    }

    /**
     * Fetch videos from private storage zone
     * 
     * @param  $type    string Type of video (public or private)
     * @param  \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function fetch_videos($type, \WP_REST_Request $request)
    {
        $password = $this->account->get("{$type}_storage_zone_password");
        if (!$password) {
            return new \WP_Error('configuration_error', 'Error: Please reconnect to bunny.net.');
        }

        $bunnyCDNStorage = new StorageAPI($this->storage_zone["{$type}_name"], $password, 'ny');
        try {
            $items = $bunnyCDNStorage->getStorageObjects("/{$this->storage_zone["{$type}_name"]}/");
        } catch (\Exception $e) {
            return new \WP_Error('error', $e->getMessage());
        }

        if (!is_array($items)) {
            return new \WP_Error('error', 'Something went wrong.');
        }

        $pullzone_hostname = $this->pull_zone->get("{$type}_hostname");
        $files = [];

        foreach ($items as $item) {
            // only show files, not directories
            if ($item->IsDirectory) {
                continue;
            }

            // TODO: Check filetype permissions based on name
            $mimes = apply_filters(
                'presto_player_allowed_mime_types', [
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
                ]
            );


            // don't show non-videos
            if (!$item->IsDirectory) {
                $wp_filetype = wp_check_filetype($item->ObjectName, $mimes);
                if (empty($wp_filetype['type']) || !preg_match('/video\/*/', $wp_filetype['type'])) {
                    continue;
                }
            }

            $url = 'https://' . trailingslashit($pullzone_hostname) . $item->ObjectName;
            $previewUrl = ($type === 'private') ? BunnyService::signURL($url) : $url;

            $files[] = [
                'id' => $item->Guid,
                'title' => $item->ObjectName,
                'previewUrl' => esc_url_raw($previewUrl),
                'url' => esc_url_raw($url),
                'size' => $item->Length,
                'visibility' => sanitize_text_field($type),
                'updated_at' =>  $item->LastChanged,
                'created_at' => $item->DateCreated
            ];
        }

        return rest_ensure_response($files);
    }


    /**
     * Upload videos to public storage zone
     * 
     * @param  \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function upload_videos(\WP_REST_Request $request)
    {
        // need a password
        $public_pass = $this->account->get("public_storage_zone_password");
        $private_pass =  $this->account->get("private_storage_zone_password");
        if (!$public_pass || !$private_pass) {
            return new \WP_Error('configuration_error', 'Error: Please reconnect to bunny.net.');
        }

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

        $mimes = apply_filters(
            'presto_player_allowed_mime_types', [
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
            ]
        );

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
            /**
 * Read binary input stream and append it to temp file. 
*/
            $in = @fopen($file['tmp_name'], 'rb');

            if ($in) {
                while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                }
            } else {
                /**
 * Failed to open input stream. 
*/
                /**
 * Attempt to clean up unfinished output. 
*/
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
     * @param  \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function store_public_video(\WP_REST_Request $request)
    {
        return $this->store_video('public', $request);
    }

    /**
     * Upload videos to private storage zone
     * 
     * @param  \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function store_private_video(\WP_REST_Request $request)
    {
        return $this->store_video('private', $request);
    }

    /**
     * Upload videos to public storage zone
     * 
     * @param  string           $type    Type of video to store (public or private)
     * @param  \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function store_video($type, \WP_REST_Request $request)
    {
        // need a password
        $password = $this->account->get("{$type}_storage_zone_password");
        if (!$password) {
            return new \WP_Error('configuration_error', 'Error: Please reconnect to bunny.net.');
        }

        if (!file_exists($request['path'])) {
            return new \WP_Error('not_found', 'This image could not be found.');
        }

        // upload file
        try {
            $bunnyCDNStorage = new StorageAPI($this->storage_zone["{$type}_name"], $password, 'ny');
            $send = $bunnyCDNStorage->uploadFile($request['path'], trailingslashit($this->storage_zone["{$type}_name"]) . $request['name']);
            // remove temporary file on upload
            if ($send) {
                @unlink($request['path']);
            }
            $function = "fetch_{$type}_videos";
            return $this->$function($request);
        } catch (\Exception $e) {
            // remove temporary file on error
            @unlink($request['path']);
            return new \WP_Error('upload_error', $e->getMessage());
        }
    }

    /**
     * Delete video from private storage
     *
     * @param  \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function delete_public_video(\WP_REST_Request $request)
    {
        $this->delete_video('public', $request);
    }

    /**
     * Delete video from private storage
     *
     * @param  \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function delete_private_video(\WP_REST_Request $request)
    {
        $this->delete_video('private', $request);
    }

    /**
     * Delete video from storage
     * 
     * @param  string           $type    Type of video to store (public or private)
     * @param  \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function delete_video($type, \WP_REST_Request $request)
    {
        // need a password
        $password = $this->account->get("{$type}_storage_zone_password");
        if (!$password) {
            return new \WP_Error('configuration_error', 'Error: Please reconnect to bunny.net.');
        }

        // upload file
        try {
            $bunnyCDNStorage = new StorageAPI($this->storage_zone["{$type}_name"], $password, 'ny');
            $deleted = $bunnyCDNStorage->deleteObject(trailingslashit($this->storage_zone["{$type}_name"]) . $request['name']);
            return rest_ensure_response($deleted);
        } catch (\Exception $e) {
            return new \WP_Error('upload_error', $e->getMessage());
        }
    }
}