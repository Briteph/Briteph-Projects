<?php

namespace PrestoPlayer\Services;

use PrestoPlayer\Blocks\AudioBlock;
use PrestoPlayer\Models\Video;
use PrestoPlayer\Blocks\VimeoBlock;
use PrestoPlayer\Blocks\YouTubeBlock;
use PrestoPlayer\Blocks\SelfHostedBlock;
use PrestoPlayer\Services\ReusableVideos;
use PrestoPlayer\Pro\Blocks\BunnyCDNBlock;

class Shortcodes
{
  public function register()
  {
    add_shortcode('presto_player_chapter', '__return_false');
    add_shortcode('presto_player_overlay', '__return_false');
    add_shortcode('presto_player_track', '__return_false');
    add_shortcode('presto_player', [$this, 'playerShortcode'], 10, 2);
    add_shortcode('presto_timestamp', [$this, 'timestampShortcode'], 10, 2);
    add_shortcode('pptime', [$this, 'timestampShortcode'], 10, 2);
  }

  /**
   * Do the shortcode
   *
   * @param array $atts Array of shortcode attributes
   * @param string $content String of content.
   * @return void
   */
  public function playerShortcode($atts, $content)
  {
    if (!is_admin()) {
      // global is the most reliable between page builders
      global $load_presto_js;
      $load_presto_js = true;
      (new Scripts())->blockAssets(); // enqueue block assets
    }

    $atts = shortcode_atts(
      [
        'id' => '',
        'src' => '',
        'title' => '',
        'provider' => '',
        'class' => '',
        'custom_field' => '',
        'poster' => '',
        'preload' => 'auto',
        'preset' => 0,
        'autoplay' => false,
        'plays_inline' => false,
        'chapters' => [],
        'overlays' => [],
        'muted_autoplay_preview' => false,
        'muted_autoplay_caption_preview' => false,
      ],
      $atts
    );

    // could not find source but ID is present.
    if (!$atts['src'] && !$atts['custom_field'] && $atts['id']) {
      return ReusableVideos::getBlock($atts['id']);
    }

    $atts = $this->parseAttributes($atts, $content);
    return $this->renderBlock($atts);
  }

  /**
   * Timestamp shortcode
   *
   * @param array $atts Shortcode attributes.
   * @param string $content Content inside shortcode.
   * @return string
   */
  public function timestampShortcode($atts, $content)
  {
    $atts = shortcode_atts(
      [
        'time' => '',
      ],
      $atts
    );
    return '<presto-timestamp time="' . esc_attr($atts['time']) . '">' . $content . '</presto-timestamp>';
  }

  public function parseAttributes($atts, $content = '')
  {
    $atts = shortcode_atts(
      [
        'id' => '',
        'src' => '',
        'title' => '',
        'provider' => '',
        'class' => '',
        'custom_field' => '',
        'poster' => '',
        'preload' => 'auto',
        'preset' => 0,
        'autoplay' => false,
        'plays_inline' => false,
        'chapters' => [],
        'overlays' => [],
        'muted_autoplay_preview' => false,
        'muted_autoplay_caption_preview' => false,
      ],
      $atts
    );

    if (!$atts['id'] && !$atts['src'] && !$atts['custom_field']) {
      return [];
    }

    // custom field as a src
    if ($atts['custom_field']) {
      $atts['src'] = get_post_meta(get_the_ID(), $atts['custom_field'], true);
    }

    // get provider based on src, if not provided
    $atts['provider'] = !$atts['provider'] ? $this->getProvider($atts['src']) : 'self-hosted';

    $atts['id'] = $this->getOrCreateVideoId($atts);
    $atts['chapters'] = $this->getChapters($content);
    $atts['overlays'] = $this->getOverlays($content);
    $atts['tracks'] = $this->getTracks($content);
    $atts['playsInline'] = (bool) $atts['plays_inline'];
    $atts['mutedPreview'] = [
      'enabled' => (bool) $atts['muted_autoplay_preview'],
      'captions' => (bool) $atts['muted_autoplay_caption_preview'],
    ];
    $atts['className'] = sanitize_html_class($atts['class']);

    unset($atts['plays_inline']);
    unset($atts['muted_autoplay_preview']);
    unset($atts['muted_autoplay_caption_preview']);
    unset($atts['class']);

    return $atts;
  }

  /**
   * Renders the block with attributes.
   *
   * @param array $atts Block attributes.
   * @return void
   */
  public function renderBlock($atts)
  {
    switch ($atts['provider']) {
      case 'self-hosted':
        return (new SelfHostedBlock())->html($atts, '');

      case 'youtube':
        return (new YouTubeBlock())->html($atts, '');

      case 'vimeo':
        return (new VimeoBlock())->html($atts, '');

      case 'bunny':
        return (new BunnyCDNBlock())->html($atts, '');

      case 'audio':
        return (new AudioBlock())->html($atts, '');
    }
  }

  /**
   * Get or create video id for analytics
   *
   * @param array $atts
   * @return int
   */
  public function getOrCreateVideoId($atts)
  {
    $create = [
      'src' => $atts['src'],
      'type' => $atts['provider']
    ];
    if (!empty($atts['title'])) {
      $create['title'] = $atts['title'];
    }

    $video = new Video();
    $model = $video->getOrCreate(
      ['src' => $atts['src']],
      $create
    );

    $model = $model->toObject();
    return !empty($model->id) ? $model->id : 0;
  }

  /**
   * Get chapters from shortcodes
   *
   * @param string $content
   * @return array
   */
  public function getChapters($content)
  {
    $chapters = $this->getShortcodesAtts(
      'presto_player_chapter',
      $content,
      [
        'time' => '00:00',
        'title' => ''
      ]
    );
    foreach ((array) $chapters as $key => $chapter) {
      if (!strpos($chapter['time'], ':')) {
        $chapters[$key]['time'] = '00:' . $chapter['time'];
      }
    }

    return $chapters;
  }

  /**
   * Get overlays from shortcodes
   *
   * @param string $content
   * @return array
   */
  public function getOverlays($content)
  {

    $overlays = $this->getShortcodesAtts(
      'presto_player_overlay',
      $content,
      [
        'start_time' => '00:00',
        'end_time' => '',
        'text' => '',
        'link' => [],
        'position' => '',
      ]
    );
    foreach ((array) $overlays as $key => $overlay) {
      if (!strpos($overlay['start_time'], ':')) {
        $overlays[$key]['startTime'] = '00:' . $overlay['start_time'];
      } else {
        $overlays[$key]['startTime'] = $overlay['start_time'];
      }

      if (!strpos($overlay['end_time'], ':')) {
        $overlays[$key]['endTime'] = '00:' . $overlay['end_time'];
      } else {
        $overlays[$key]['endTime'] = $overlay['end_time'];
      }

      $overlays[$key]['link']['url'] = $overlay['link_url'];
      $overlays[$key]['link']['opensInNewTab'] = (bool) $overlay['link_new_tab'];

      unset($overlays[$key]['link_url']);
      unset($overlays[$key]['link_new_tab']);
      unset($overlays[$key]['start_time']);
      unset($overlays[$key]['end_time']);
    }
    return $overlays;
  }

  /**
   * Get tracks from shortcodes
   *
   * @param string $content
   * @return array
   */
  public function getTracks($content)
  {
    return $this->getShortcodesAtts(
      'presto_player_track',
      $content,
      [
        'label' => '',
        'src' => '',
        'srclang' => ''
      ]
    );
  }

  /**
   * Get specific shortcode atts from content
   *
   * @param string $name Name of shortcode
   * @param string $content Page content
   * @param array $defaults Defaults for each
   * @return array
   */
  public function getShortcodesAtts($name, $content, $defaults = [])
  {
    $items = [];

    // if shortcode exists
    if (
      preg_match_all('/' . get_shortcode_regex() . '/s', $content, $matches)
      && array_key_exists(2, $matches)
      && in_array($name, $matches[2])
    ) {
      foreach ((array) $matches[0] as $key => $value) {
        if (strpos($value, $name) !== false) {
          $items[] = wp_parse_args(
            shortcode_parse_atts($matches[3][$key]),
            $defaults
          );
        }
      }
    }

    return $items;
  }

  /**
   * Maybe switch provider if the url is overridden
   */
  protected function getProvider($src)
  {
    $provider = 'self-hosted';

    if (!empty($src)) {
      $filetype = wp_check_filetype($src);
      if (isset($filetype['type']) && false !== strpos($filetype['type'], 'audio')) {
        return 'audio';
      }

      $yt_rx = '/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/';
      $has_match_youtube = preg_match($yt_rx, $src, $yt_matches);

      if ($has_match_youtube) {
        return 'youtube';
      }

      $vm_rx = '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([‌​0-9]{6,11})[?]?.*/';
      $has_match_vimeo = preg_match($vm_rx, $src, $vm_matches);

      if ($has_match_vimeo) {
        return 'vimeo';
      }

      if (strpos($src, 'https://vz-') !== false && strpos($src, 'b-cdn.net') !== false) {
        return 'bunny';
      }
    }

    return $provider;
  }
}
