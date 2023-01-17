import { getEventMeta, track_event, toggle_mark_complete, do_ajax_completion } from './helper';

export const onPauseVideo = e => {
  const player = e.detail.plyr;
  let eventMeta = getEventMeta(player);
  track_event('paused', eventMeta);
};

export const onPlayingVideo = e => {
  const player = e.detail.plyr;
  let eventMeta = getEventMeta(player);
  let event_id = eventMeta.ts < 1 ? 'started' : 'played';
  track_event(event_id, eventMeta);
};

export const onEndedVideo = (e, $el, settings) => {
  const player = e.detail.plyr;
  let eventMeta = getEventMeta(player);

  track_event('ended', eventMeta);
  do_ajax_completion($el, settings);
  toggle_mark_complete(true);
};

export default function (player) {
  /*
    Below code is coped from LifterLMS-Advanced-Videos/assets/js/llms.av.js
    We replaced $ with jQuery.
  */
  var settings = {};
  var tracking_settings = llms?.tracking?.getSettings() || null;

  if (tracking_settings?.av) {
    settings = tracking_settings.av;
  }

  /*
    LifterLMS-Advanced-Videos copied code end.
    Below code will glue the LifterAV with Presto player.
  */
  if (settings.require_video_completion && !settings.video_completed) {
    toggle_mark_complete(false);
  }

  let $el = jQuery('.presto-block-video');

  // on end, sync time and autoload content
  player.on('pause', onPauseVideo);
  player.on('playing', onPlayingVideo);
  player.on('ended', e => onEndedVideo(e, $el, settings));
}
