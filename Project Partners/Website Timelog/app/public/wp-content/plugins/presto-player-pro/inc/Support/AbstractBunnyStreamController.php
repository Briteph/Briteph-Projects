<?php

namespace PrestoPlayer\Pro\Support;

use PrestoPlayer\Models\Setting;
use PrestoPlayer\Pro\Libraries\BunnyVideoApiRequest;

abstract class AbstractBunnyStreamController implements BunnyStreamInterface
{
    protected $type = '';
    protected $library_id;
    protected $client;
    protected $endpoint = '';
    protected $model;
    protected $api_key;

    public function __construct($type)
    {
        $this->type = $type;
        $this->library_id = Setting::get('bunny_stream_' . $type, 'video_library_id');
        $this->api_key = Setting::get('bunny_stream_' . $type, 'video_library_api_key');
        $this->client = new BunnyVideoApiRequest($this->api_key);
    }

    /**
     * Fetch all items
     *
     * @param string $type private|public
     * @return Object|\WP_Error Request object
     */
    public function fetch($query = [])
    {
        $items = $this->client->get("library/$this->library_id/{$this->endpoint}", [
            'query' => $query
        ]);

        if (is_wp_error($items)) {
            return $items;
        }

        $items->items = $this->setModels($items->items);

        return $items;
    }

    /**
     * Get a video
     *
     * @param string $id
     * @return Object|\WP_Error Request object
     */
    public function get($id)
    {
        $item = $this->client->get("library/$this->library_id/{$this->endpoint}/{$id}");

        if (is_wp_error($item)) {
            return $item;
        }

        return $this->setModel($item);
    }

    /**
     * Create a video model
     *
     * @param array $args
     * @return Object|\WP_Error Request object
     */
    public function create($args)
    {
        // first, create the video
        $item = $this->client->post("library/$this->library_id/{$this->endpoint}", [
            'body' => $args
        ]);

        if (is_wp_error($item)) {
            return $item;
        }

        return $this->setModel($item);
    }

    /**
     * Update a video
     *
     * @param string $id
     * @param array $args
     *@return Object|\WP_Error Request object
     */
    public function update($id, $args = [])
    {
        $item = $this->client->post("library/$this->library_id/{$this->endpoint}/{$id}", [
            'body' => $args
        ]);

        if (is_wp_error($item)) {
            return $item;
        }

        return $this->setModel($item);
    }

    /**
     * Delete a video
     *
     * @param string $id
     * @return true|\WP_Error Request object
     */
    public function delete($id)
    {
        $item = $this->client->delete("library/$this->library_id/{$this->endpoint}/{$id}");

        if (is_wp_error($item)) {
            return $item;
        }

        return true;
    }

    /**
     * Create model from object response
     *
     * @param Object $args
     * @param string $type private|public
     * 
     * @return \PrestoPlayer\Pro\Model;
     */
    public function setModel($args)
    {
        return new $this->model((object) $args, $this->type);
    }

    /**
     * Create model from object response
     *
     * @param Object $args
     * @param string $type private|public
     * 
     * @return Array
     */
    public function setModels($items = [])
    {
        $models = [];
        foreach ($items as $item) {
            $models[] = $this->setModel($item);
        }
        return $models;
    }
}
