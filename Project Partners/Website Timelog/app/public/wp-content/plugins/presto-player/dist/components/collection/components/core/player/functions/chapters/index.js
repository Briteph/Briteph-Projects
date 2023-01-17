import button from './templates/button';
import toc from './templates/toc';
import tocItem from './templates/toc-item';
import marker from './templates/timeline-marker';

export function addChapterControl(e) {
  let chapters,
    player,
    $wrapper,
    $playerTimeline,
    $tocContainer,
    $tocListContainer,
    $button,
    $tocCover,
    $timeline,
    $allMarkers,
    currentTime = 0,
    $allChapters,
    $controls,
    added,
    $items = {},
    $markers = {},
    checker = null,
    addedChapters = {},
    wait = 0;

  if (typeof jQuery === 'undefined') {
    return;
  }

  player = e.detail.plyr;
  chapters = formatChapters(player?.config?.chapters || []);

  // handle if preload is set to none
  if (player.provider === 'html5') {
    if (!player.duration) {
      player.on('loadedmetadata', addChapterControl);
      return;
    }
  }

  if (!chapters || !Object.keys(chapters).length) return;

  $wrapper = jQuery(player.elements.container).closest('.presto-player__wrapper').find('.plyr');
  $controls = jQuery(player.elements.controls);

  let $appendButton = $controls.find('.plyr__time');
  $appendButton = $appendButton.length ? $appendButton : $controls.find('.plyr__progress__container');

  $button = $wrapper.find('[data-plyr="chapters"]');
  if (!$button.length) {
    $button = jQuery(button).insertAfter($appendButton);
  }

  $tocContainer = $wrapper.find('.presto-player-toc__wrapper');

  if (!$tocContainer.length) {
    $tocContainer = jQuery(toc).appendTo($wrapper);
  }

  $tocCover = $tocContainer.find('[data-player-toc-cover]');
  $tocListContainer = $tocContainer.find('[data-player-toc]');
  $playerTimeline = $wrapper.find('.plyr__progress__container');
  let $timelineWrapper = jQuery('<div class="presto-player__chapters"></div>').appendTo($playerTimeline);
  $timeline = jQuery('<div class="presto-player__chapter-markers"></div>').appendTo($timelineWrapper);

  // init
  addControl(player);
  events();

  /**
   * Format chapters data
   */
  function formatChapters(chapters = []) {
    if (!chapters.length) {
      return chapters;
    }
    let formatted = {};
    chapters.forEach(chapter => {
      let pieces = chapter.time.split(':');
      let seconds;
      if (pieces.length > 1) {
        seconds = parseInt(pieces[0]) * 60;
      }
      // for not to get outside of player range
      let minTime = parseInt(pieces[1]) + parseInt(seconds);
      formatted[minTime] = chapter;
    });
    return formatted;
  }

  /**
   * Adds chapters and timeline markers
   */
  function addControl() {
    addChapters();
    addChapterTimelineMarkers();
  }

  /**
   * Add Chapters
   */
  function addChapters() {
    let order = 0;
    if ($tocListContainer.find('.presto-player-toc__chapter').length) {
      return;
    }
    Object.keys(chapters).forEach(timestamp => {
      order++;
      const chapter = chapters[timestamp];
      const $item = jQuery(tocItem({ name: chapter.title, order })).appendTo($tocListContainer);
      $item.data('presto-player-timestamp', timestamp);
      $items[timestamp] = $item;
      $item.on('click', () => {
        player.currentTime = parseFloat(timestamp);
        player.play();
        toggleToc(false);
      });
    });
    $allChapters = $tocContainer.find('[data-chapter-item]');
  }

  /**
   * When mouse leaves toc container
   */
  function onTocMouseOut() {
    if (player.playing) {
      let timeout = setTimeout(() => {
        toggleToc(false);
        $tocListContainer.off('mouseleave', onTocMouseOut);
      }, 2000);
      $tocListContainer.on('mouseenter', () => {
        clearTimeout(timeout);
      });
    }
  }

  /**
   * Handles all events
   */
  function events() {
    // toggle open/closed
    $button.off('click');
    $button.on('click', toggleToc);

    // current chapter
    currentChapter();
    player.off('timeupdate');
    player.on('timeupdate', currentChapter);

    // timeline markers
    checker = window.setInterval(maybeAddMarkers, 100);

    // close cover
    $tocCover.off('click');
    $tocCover.on('click', () => toggleToc(false));
    $tocListContainer.off('mouseleave');
    $tocListContainer.on('mouseleave', onTocMouseOut);
  }

  function maybeAddMarkers() {
    wait++;
    if (player.duration) {
      addChapterTimelineMarkers();
      window.clearInterval(checker);
    }
    if (wait > 50) {
      window.clearInterval(checker);
    }
  }

  /**
   * Handles highlighting the current chapter in the TOC
   */
  function currentChapter() {
    currentTime = player.currentTime;

    // find only ones before current time
    let passed = Object.keys(chapters).filter(item => {
      return item <= currentTime;
    });

    // convert to int
    let passedNumbers = passed.map(item => parseInt(item, 10));

    // find largest passed
    let currentChapterIndex = Math.max(...passedNumbers);

    // handle classes
    $allChapters && $allChapters.removeClass('is-highlighted');
    jQuery($items[currentChapterIndex || 0]).addClass('is-highlighted');
  }

  /**
   * Adds markers to the timeline on load
   */
  function addChapterTimelineMarkers() {
    if (!player.duration) {
      return;
    }
    Object.keys(chapters).forEach(timestamp => {
      // need a timestamp
      if (!parseInt(timestamp)) {
        return;
      }
      // make sure it's not already added
      if ($timeline.find(`[data-timestamp=${timestamp}]`).length) {
        return;
      }
      const chapter = chapters[timestamp];
      let position = (parseInt(timestamp) / player.duration) * 100;
      let $marker = jQuery(marker({ position, name: `${chapter.title}` })).prependTo($timeline);
      $marker.attr('data-timestamp', timestamp);
      $marker.data('timestamp', timestamp);
      $markers[timestamp] = $marker;
      $marker.on('click', e => {
        player.currentTime = parseInt(timestamp);
        player.play();
      });
    });

    $allMarkers = $tocContainer.find('[data-timeline-marker]');
  }

  /**
   * Toggles the TOC
   */
  function toggleToc(show) {
    if (typeof show !== undefined) {
      $tocContainer.toggleClass('is-showing', show);
    } else {
      $tocContainer.toggleClass('is-showing');
    }
  }
}
