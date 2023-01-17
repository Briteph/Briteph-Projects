<?php

namespace PrestoPlayer\Pro\Models\Bunny;

class Collection
{
    public $type;
    public $videoLibraryId;
    public $guid;
    public $name;
    public $videoCount;
    public $totalSize = 0;
    public $previewVideoIds;


    public function __construct($args, $type)
    {
        if (!$type) {
            throw new \Exception('You must provide a type for the collection (public or private)');
            return;
        }

        $args = (object) $args;

        $has = get_object_vars($this);
        foreach ($has as $name => $_) {
            $this->$name = isset($args->$name) ? $args->$name : NULL;
        }
        $this->id = $args->guid;
        $this->type = $type;
    }
}
