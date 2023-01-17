<?php

namespace PrestoPlayer\Pro\Services\Bunny\Storage;

/**
 * An exception thrown by BunnyCDNStorage caused by authentication failure
 */
class BunnyStorageAuthenticationException extends BunnyStorageException
{
    public function __construct($storageZoneName, $accessKey, $code = 0, \Exception $previous = null)
    {
        parent::__construct("Authentication failed for storage zone '{$storageZoneName}' with access key '{$accessKey}'.", $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": {$this->message}\n";
    }
}
