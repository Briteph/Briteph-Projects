<?php

namespace PrestoPlayer\Pro\Services\API;

use PrestoPlayer\Models\Setting;
use PrestoPlayer\Pro\Models\Bunny\Account;
use PrestoPlayer\Pro\Libraries\BunnyCDNStorage;

class BunnyStorageRemoteAPI
{
    public function __construct(BunnyCDNStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Get api key
     *
     * @return void
     */
    public function getKey()
    {
        return (new Account)->api_key;
    }

    /**
     * Make a request
     *
     * @return mixed
     */
    public function request()
    {
        if (!$key = $this->getKey()) {
            throw new \Exception(__("Please add an API Key", 'presto-player-pro'));
        }

        return $this->cdn->Account($key);
    }
}
