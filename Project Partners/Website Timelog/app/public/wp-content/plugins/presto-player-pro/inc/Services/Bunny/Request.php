<?php

namespace PrestoPlayer\Pro\Services\Bunny;

use PrestoPlayer\Pro\Models\Bunny\Account;

/**
 * A simple request wrapper for the bunny api
 */
abstract class Request
{
    protected $api_url = "https://bunnycdn.com/api";

    /**
     * Get API key from account
     * @return string
     */
    public function getApiKey()
    {
        return (new Account)->get('api_key');
    }

    /**
     * Make a request
     *
     * @param string $endpoint
     * @param array $args
     * @return array|\WP_Error
     */
    public function request($endpoint, $args = [])
    {
        $key = $this->getApiKey();

        if (!$key) {
            return new \WP_Error('missing_api_key', 'Please enter an API key.');
        }

        $args = wp_parse_args($args, [
            'timeout' => 25, // 25 second time out to prevent slow connection errors
            'headers' => [
                'AccessKey' => $this->getApiKey(),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
            'method' => 'GET'
        ]);

        if (!empty($args['body'])) {
            $args['body'] = json_encode($args['body']);
        }

        $response = wp_remote_request(esc_url_raw($this->api_url . '/' . $endpoint), $args);
        if (is_wp_error($response)) {
            return $response;
        }

        // okay if no response
        if (empty($response['body'])) {
            return true;
        }

        // decode json
        $decoded_result = json_decode($response['body'], true);

        /* If invalid JSON, return original result body. */
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new \WP_Error('invalid_apikey', 'The API key you entered was invalid.');
        }

        if (!empty($decoded_result['HttpCode'])) {
            if (!in_array($decoded_result['HttpCode'], ['200', '201'])) {
                return new \WP_Error('invalid_key', 'The Access Key you entered was invalid.');
            }
        }

        // handle bunny errors
        if (!empty($decoded_result['ErrorKey']) && !empty($decoded_result['Message'])) {
            return new \WP_Error($decoded_result['ErrorKey'], $decoded_result['Message']);
        }

        return $decoded_result;
    }

    /**
     * Convenience function
     *
     * @param string $endpoint
     * @param array $args
     * @return array|\WP_Error
     */
    public function get($endpoint, $args = [])
    {
        $args['method'] = 'GET';
        return $this->request($endpoint, $args);
    }

    /**
     * Convenience function
     *
     * @param string $endpoint
     * @param array $args
     * @return array|\WP_Error
     */
    public function post($endpoint, $args = [])
    {
        $args['method'] = 'POST';
        return $this->request($endpoint, $args);
    }

    /**
     * Convenience function
     *
     * @param string $endpoint
     * @param array $args
     * @return array|\WP_Error
     */
    public function delete($endpoint, $args = [])
    {
        $args['method'] = 'DELETE';
        return $this->request($endpoint, $args);
    }
}
