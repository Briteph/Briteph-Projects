<?php

namespace PrestoPlayer\Pro\Contracts;

interface EmailProviderInterface
{
    /**
     * Handle the request
     *
     * @return void
     */
    public function handle($data, $preset);
}
