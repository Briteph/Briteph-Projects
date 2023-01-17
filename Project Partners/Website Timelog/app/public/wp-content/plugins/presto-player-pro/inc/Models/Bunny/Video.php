<?php

namespace PrestoPlayer\Pro\Models\Bunny;

use PrestoPlayer\Models\Setting;
use PrestoPlayer\Pro\Services\Bunny\URL;
use PrestoPlayer\Pro\Services\Bunny\BunnyService;

class Video
{
    public $type;
    public $videoLibraryId;
    public $guid;
    public $title;
    public $dateUploaded;
    public $views = 0;
    public $isPublic;
    public $length;
    public $status;
    public $framerate;
    public $width;
    public $height;
    public $availableResolutions = '';
    public $thumbnailCount;
    public $encodeProgress;
    public $storageSize;
    public $captions = [];
    public $hasMP4Fallback = false;
    public $collectionId;
    public $thumbnailFileName;
    public $playlistURL = '';
    public $webPURL = '';
    public $MP4URLs = [];
    public $pullzone_url;

    public $playlistURLSigned = "";
    public $webPURLSigned = "";
    public $thumbnailURLSigned = "";
    public $MP4URLsSigned = [];

    public function __construct($args, $type)
    {
        if (!$type) {
            throw new \Exception('You must provide a type for the video (public or private)');
            return;
        }

        $args = (object) $args;

        $has = get_object_vars($this);
        foreach ($has as $name => $_) {
            $this->$name = isset($args->$name) ? $args->$name : NULL;
        }

        $this->id = $args->guid;
        $this->type = $type;
        $this->pullzone_url = Setting::get('bunny_stream_' . $this->type, 'pull_zone_url');
        $this->availableResolutions = explode(',', $this->availableResolutions);

        $this->setURLS();
    }

    public function setURLS()
    {
        $this->playlistURL = "{$this->getRootURL()}/playlist.m3u8";
        $this->webPURL = "{$this->getRootURL()}/preview.webp";
        $this->thumbnailURL = "{$this->getRootURL()}/{$this->thumbnailFileName}";
        $this->MP4URLs = [];
        if (!empty($this->availableResolutions)) {
            foreach ($this->availableResolutions as $resolution) {
                $this->MP4URLs[] = "{$this->getRootURL()}/play_{$resolution}p.mp4";
            }
        }

        // sign urls
        $key = Setting::get('bunny_stream_private', 'token_auth_key');
        $this->playlistURLSigned = BunnyService::signURL($this->playlistURL, $key);
        $this->webPURLSigned = BunnyService::signURL($this->webPURL, $key);
        $this->thumbnailURLSigned = BunnyService::signURL($this->thumbnailURL, $key);
        $this->MP4URLsSigned = [];
        if (!empty($this->MP4URLs)) {
            foreach ($this->MP4URLs as $mp4_url) {
                $this->MP4URLsSigned[] = BunnyService::signURL($mp4_url, $key);
            }
        }
    }

    /**
     * Get the root url
     *
     * @return string
     */
    public function getRootURL()
    {
        return untrailingslashit("https://{$this->pullzone_url}/{$this->id}");
    }
}
