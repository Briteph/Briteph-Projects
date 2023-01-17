<?php

namespace PrestoPlayer\Pro\Models\Bunny\Support;

use ArrayAccess;
use PrestoPlayer\Models\Setting;

abstract class SettingModel implements SettingInterface, ArrayAccess
{
    /**
     * Option name
     *
     * @var string
     */
    protected $option_name = '';

    /**
     * Get fillable fields
     *
     * @return array
     */
    public function getFillableSchema()
    {
        return $this->fillable;
    }

    /**
     * Offset exists
     *
     * @param mixed $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, (array)$this->get());
    }

    /**
     * Offset get
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get()[$offset];
    }

    /**
     * Offset set
     *
     * @param mixed $offset
     * @param mixed $value
     * @return boolean
     */
    public function offsetSet($offset, $value)
    {
        return $this->update($offset, $value);
    }

    /**
     * Offset set
     *
     * @param mixed $offset
     * @return boolean
     */
    public function offsetUnset($offset)
    {
        return $this->delete($offset);
    }

    /**
     * Get option from this setting
     *
     * @param string $name
     * @param string $default
     * @return array|mixed
     */
    public function get($name = '', $default = '')
    {
        if ($name) {
            return Setting::get($this->option_name, $name, $default);
        }
        return Setting::get($this->option_name);
    }


    /**
     * Update a setting
     *
     * @param string $name
     * @param string $value
     * @return void
     */
    public function update($name, $value = '')
    {
        // must be fillable
        if (empty($this->fillable[$name])) {
            wp_die("This setting property does not exist. (" . sanitize_text_field($name) . ")");
        }

        // sanitize before saving to make sure it won't come back invalid
        $sanitized = rest_sanitize_value_from_schema($value, $this->fillable[$name], $name);

        // update setting
        return Setting::update($this->option_name, $name, $sanitized);
    }

    /**
     * Delete a setting
     *
     * @param string $name
     * @return boolean
     */
    public function delete($name = '')
    {
        if ($name) {
            return Setting::delete($this->option_name, $name);
        } else {
            return Setting::deleteAll($this->option_name);
        }
    }
}
