<?php

namespace PrestoPlayer\Pro\Models\Bunny;

use PrestoPlayer\Pro\Models\Bunny\Support\ZoneModel;

class StorageZone extends ZoneModel
{
    /**
     * Option name
     *
     * @var string
     */
    protected $option_name = 'bunny_storage_zones';

    /**
     * Fillable field schema
     *
     * @var array
     */
    protected $fillable = [
        // public
        'public_id' => [
            'type' => 'integer'
        ],
        'public_name' => [
            'type' => 'string'
        ],

        // private
        'private_id' => [
            'type' => 'integer'
        ],
        'private_name' => [
            'type' => 'string'
        ],
    ];
}
