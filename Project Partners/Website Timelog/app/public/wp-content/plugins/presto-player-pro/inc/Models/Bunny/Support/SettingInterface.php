<?php

namespace PrestoPlayer\Pro\Models\Bunny\Support;

interface SettingInterface
{
    public function getFillableSchema();
    public function get($name);
    public function update($name, $value);
    public function delete($name);
}
