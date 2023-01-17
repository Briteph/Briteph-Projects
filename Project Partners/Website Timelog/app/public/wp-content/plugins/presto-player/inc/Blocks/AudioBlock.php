<?php

namespace PrestoPlayer\Blocks;

use PrestoPlayer\Models\CurrentUser;
use PrestoPlayer\Support\Block;

class AudioBlock extends Block
{
  /**
   * Block name
   *
   * @var string
   */
  protected $name = 'audio';

  /**
   * Bail if user cannot access video
   *
   * @return void
   */
  public function middleware($attributes, $content)
  {
    // if private and user cannot access video, don't load
    if (!empty($attributes['visibility']) && 'private' === $attributes['visibility']) {
      if (empty($attributes['id'])) {
        return false;
      }
      if (!CurrentUser::canAccessVideo($attributes['id'])) {
        return false;
      }
    }

    return parent::middleware($attributes, $content);
  }

  /**
   * Add curtain styles.
   *
   * @return void
   */
  public function sanitizeAttributes($attributes, $default_config)
  {
    return [
      'styles' => $default_config['styles'] . ' --presto-curtain-size: 25%',
    ];
  }
}
