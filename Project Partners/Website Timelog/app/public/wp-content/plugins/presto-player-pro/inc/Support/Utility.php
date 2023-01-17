<?php

namespace PrestoPlayer\Pro\Support;

class Utility
{
    /**
     * Is it an hls stream
     *
     * @param string $src
     * @return boolean
     */
    public static function isStream($src)
    {
        if (empty($src)) {
            return false;
        }

        $parts = pathinfo($src);

        if (!empty($parts['extension'])) {
            return 'm3u8' === $parts['extension'] || 'jpg' === $parts['extension'];
        }

        return false;
    }
}
