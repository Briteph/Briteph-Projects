<?php

namespace PrestoPlayer\Pro\Support;

interface BunnyStreamInterface
{
    /**
     * Create model from object response
     *
     * @param Object $args
     * @param string $type private|public
     * 
     * @return \PrestoPlayer\Pro\Models;
     */
    public function setModel($args);

    /**
     * Create model from object response
     *
     * @param Object $args
     * @param string $type private|public
     * 
     * @return Array
     */
    public function setModels($videos = []);
}
