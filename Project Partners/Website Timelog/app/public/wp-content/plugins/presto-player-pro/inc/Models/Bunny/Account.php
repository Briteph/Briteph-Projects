<?php

namespace PrestoPlayer\Pro\Models\Bunny;

use PrestoPlayer\Pro\Models\Bunny\Support\SettingModel;

class Account extends SettingModel
{
    /**
     * Option name
     *
     * @var string
     */
    protected $option_name = 'bunny_keys';

    /**
     * Fillable field schema
     *
     * @var array
     */
    protected $fillable = [
        'api_key' => [
            'type' => 'string'
        ],
        'public_storage_zone_password' => [
            'type' => 'string'
        ],
        'private_storage_zone_password' => [
            'type' => 'string'
        ]
    ];
}
