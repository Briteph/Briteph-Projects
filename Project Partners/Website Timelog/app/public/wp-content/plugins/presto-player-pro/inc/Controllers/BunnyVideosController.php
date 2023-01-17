<?php

namespace PrestoPlayer\Pro\Controllers;

use PrestoPlayer\Models\Setting;
use PrestoPlayer\Pro\Models\Bunny\Video;
use PrestoPlayer\Pro\Support\AbstractBunnyStreamController;

class BunnyVideosController extends AbstractBunnyStreamController
{
    protected $endpoint = 'videos';
    protected $model = Video::class;

    /**
     * Upload a video to stream
     *
     * @return void
     */
    public function upload($path, $args = [])
    {
        if (!$path) {
            return new \WP_Error('file_missing', 'You must have a file to upload');
        }
        if (empty($args['guid'])) {
            return new \WP_Error('guid_missing', 'You must select a video guid.');
        }

        $curl = curl_init();
        $stream = fopen($path, 'r');

        $headers = [
            "AccessKey: {$this->api_key}",
            "Content-Type: application/octet-stream", // or whatever you want
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://video.bunnycdn.com/library/$this->library_id/videos/{$args['guid']}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_PUT => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_INFILE => $stream,
            CURLOPT_INFILESIZE => filesize($path),
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
        ]);

        $response = curl_exec($curl);
        $curlError = curl_errno($curl);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        fclose($stream);
        @unlink($path);

        if ($curlError) {
            return new \WP_Error("error_$curlError", "An unknown error has occured during the request.");
        }

        if ($responseCode == 404) {
            return new \WP_Error("not_found", "The url was not found.");
        } else if ($responseCode == 401) {
            return new \WP_Error("not_authenticated", "The API key was incorrect.");
        } else if ($responseCode < 200 || $responseCode > 299) {
            return new \WP_Error("error", "An unknown error has occured during the request. Status code: " . $responseCode);
        }


        return $response;

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, "https://video.bunnycdn.com/library/$this->library_id/videos/{$args['guid']}");
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($path));

        // $key = Setting::get('bunny_stream_' . $this->type, 'video_library_api_key');
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        //     "AccessKey: {$key}",
        // ));

        // try {
        //     $output = curl_exec($ch);
        //     $curlError = curl_errno($ch);
        //     $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //     curl_close($ch);
        // } catch (\Exception $e) {
        //     return new \WP_Error("error", "An unknown error has occured during the request.");
        // }

        // if ($curlError) {
        //     return new \WP_Error("error_$curlError", "An unknown error has occured during the request.");
        // }

        // if ($responseCode == 404) {
        //     return new \WP_Error("not_found", "The url was not found.");
        // } else if ($responseCode == 401) {
        //     return new \WP_Error("not_authenticated", "The API key was incorrect.");
        // } else if ($responseCode < 200 || $responseCode > 299) {
        //     return new \WP_Error("error", "An unknown error has occured during the request. Status code: " . $responseCode);
        // }

        // return $output;
    }

    public function create($args)
    {
        if (empty($args['title'])) {
            return new \WP_Error('missing_title', 'You must provide a title');
        }
        return parent::create($args);
    }
}
