<?php

namespace PrestoPlayer\Pro\Models\Bunny;

use PrestoPlayer\Pro\Models\Bunny\Support\ZoneModel;

class PullZone extends ZoneModel
{
    /**
     * Option name
     *
     * @var string
     */
    protected $option_name = 'bunny_pull_zones';

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
        'public_hostname' => [
            'type' => 'string'
        ],

        // private
        'private_id' => [
            'type' => 'integer'
        ],
        'private_name' => [
            'type' => 'string'
        ],
        'private_hostname' => [
            'type' => 'string'
        ],
        'private_security_key' => [
            'type' => 'string'
        ],
    ];
}
