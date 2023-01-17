<?php

namespace PrestoPlayer\Pro\Controllers;

use PrestoPlayer\Pro\Models\Bunny\Collection;
use PrestoPlayer\Pro\Support\AbstractBunnyStreamController;

class BunnyCollectionsController extends AbstractBunnyStreamController
{
    protected $endpoint = 'collections';
    protected $model = Collection::class;

    public function create($args)
    {
        if (empty($args['name'])) {
            return new \WP_Error('missing_name', 'You must provide a name');
        }

        // first, create the video
        $item = $this->client->post("library/$this->library_id/{$this->endpoint}", [
            'body' => $args
        ]);

        if (is_wp_error($item)) {
            return $item;
        }

        return $item;
    }
}
