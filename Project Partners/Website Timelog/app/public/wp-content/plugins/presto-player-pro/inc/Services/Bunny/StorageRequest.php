<?php

namespace PrestoPlayer\Pro\Services\Bunny;

/**
 * A simple request wrapper for the bunny api
 */
class StorageRequest extends Request
{
    protected $api_url = "https://storage.bunnycdn.com";
    protected $key = '';

    // needs a key on creation
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Get API key from account
     * @return string
     */
    public function getApiKey()
    {
        return $this->key;
    }
}
