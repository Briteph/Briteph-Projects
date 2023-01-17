<?php

namespace PrestoPlayer\Pro\Models\Bunny\Support;

use PrestoPlayer\Models\Setting;

abstract class ZoneModel extends SettingModel
{
    /**
     * Option name
     *
     * @var string
     */
    protected $option_name = '';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * Store this installs unique id in this settings key
     *
     * @var string
     */
    protected $uid_key = 'presto_player_bunny_uid';

    /**
     * Gets or sets the UID
     *
     * @return string
     */
    public function getUID()
    {
        return get_option($this->uid_key, '');
    }

    /**
     * Set the UID
     *
     * @param integer $value
     * @return bool
     */
    public function setUID($value)
    {
        return update_option($this->uid_key, strval($value));
    }

    /**
     * Generate UID
     *
     * @return string
     */
    public function generateUID()
    {
        return substr(wp_hash(time()), 0, 7);
    }

    /**
     * Get or create UID
     *
     * @param boolean $regenerate
     * @return void
     */
    public function getOrCreateUID($regenerate = false)
    {
        $local_id = $this->getUID();

        // maybe generate unique id for this install
        if (empty($local_id) || $regenerate) {
            $this->setUID(substr(wp_hash(time()), 0, 7));
        }

        return $this->getUID();
    }

    /**
     * Private name
     *
     * @return string
     */
    public function getPrivateName()
    {
        return 'prestoprivate' . $this->getOrCreateUID();
    }

    /**
     * Public name
     *
     * @return string
     */
    public function getPublicName()
    {
        return 'prestopublic' . $this->getOrCreateUID();
    }

    /**
     * Finds our zones in a list
     *
     * @param array $list List of storage zone models
     * @return array
     */
    public function findZonesInList($list = [])
    {
        // find which have been created
        $created = [
            'public' => false,
            'private' => false
        ];

        foreach ($list as $item) {
            // must have a valid name
            if (empty($item['Name'])) {
                continue;
            }

            // public created
            if (strpos($item['Name'], $this->getPublicName()) !== false) {
                $created['public'] = $item;
            }

            // private created
            if (strpos($item['Name'], $this->getPrivateName()) !== false) {
                $created['private'] = $item;
            }
        }

        return $created;
    }
}
