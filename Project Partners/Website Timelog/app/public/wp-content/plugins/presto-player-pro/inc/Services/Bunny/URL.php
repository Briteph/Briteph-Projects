<?php

namespace PrestoPlayer\Pro\Services\Bunny;

use PrestoPlayer\Attachment;
use PrestoPlayer\Models\CurrentUser;
use PrestoPlayer\Pro\Models\Bunny\PullZone;

class URL
{
    protected $security_key = '';

    public function __construct($security_key)
    {
        $this->security_key = $security_key;
    }

    public function sign($url)
    {
        // pullzone model
        return self::signURL(
            $url,
            $this->security_key,
            apply_filters('presto_player_bunny_token_expires', 2 * HOUR_IN_SECONDS), // default to 2 hours
            null,
            true,
            '/'
        );
    }

    /**
     * Sign the url
     *
     * @param string $url
     * @param string $securityKey
     * @param integer $expiration_time
     * @param string $user_ip
     * @param boolean $is_directory_token
     * @param string $path_allowed
     * @param string $countries_allowed
     * @param string $countries_blocked
     * @param string $referers_allowed
     * 
     * @return string
     */
    public static function signURL($url, $securityKey, $expiration_time = 3600, $user_ip = NULL, $is_directory_token = false, $path_allowed = NULL, $countries_allowed = NULL, $countries_blocked = NULL, $referers_allowed = NULL)
    {
        if (!$user_ip) {
            $user_ip = CurrentUser::getIP();
        }

        if (!is_null($countries_allowed)) {
            $url .= (parse_url($url, PHP_URL_QUERY) == "") ? "?" : "&";
            $url .= "token_countries={$countries_allowed}";
        }
        if (!is_null($countries_blocked)) {
            $url .= (parse_url($url, PHP_URL_QUERY) == "") ? "?" : "&";
            $url .= "token_countries_blocked={$countries_blocked}";
        }
        if (!is_null($referers_allowed)) {
            $url .= (parse_url($url, PHP_URL_QUERY) == "") ? "?" : "&";
            $url .= "token_referer={$referers_allowed}";
        }

        $url_scheme = parse_url($url, PHP_URL_SCHEME);
        $url_host = parse_url($url, PHP_URL_HOST);
        $url_path = parse_url($url, PHP_URL_PATH);
        $url_query = parse_url($url, PHP_URL_QUERY);

        $parameters = array();
        parse_str($url_query, $parameters);

        // Check if the path is specified and ovewrite the default
        $signature_path = $url_path;

        if (!is_null($path_allowed)) {
            $signature_path = $path_allowed;
            $parameters["token_path"] = $signature_path;
        }

        // Expiration time
        $expires = time() + $expiration_time;

        // Construct the parameter data
        ksort($parameters); // Sort alphabetically, very important
        $parameter_data = "";
        $parameter_data_url = "";
        if (sizeof($parameters) > 0) {
            foreach ($parameters as $key => $value) {
                if (strlen($parameter_data) > 0)
                    $parameter_data .= "&";

                $parameter_data_url .= "&";

                $parameter_data .= "{$key}=" . $value;
                $parameter_data_url .= "{$key}=" . urlencode($value); // URL encode everything but slashes for the URL data
            }
        }

        // Generate the toke
        $hashableBase = $securityKey . $signature_path . $expires;

        // If using IP validation
        // if (!is_null($user_ip)) {
        //     $hashableBase .= $user_ip;
        // }

        $hashableBase .= $parameter_data;

        // Generate the token
        $token = hash('sha256', $hashableBase, true);
        $token = base64_encode($token);
        $token = strtr($token, '+/', '-_');
        $token = str_replace('=', '', $token);

        if ($is_directory_token) {
            return "https://{$url_host}/bcdn_token={$token}&expires={$expires}{$parameter_data_url}{$url_path}";
        } else {
            return "https://{$url_host}{$url_path}?token={$token}{$parameter_data_url}&expires={$expires}";
        }
    }
}
