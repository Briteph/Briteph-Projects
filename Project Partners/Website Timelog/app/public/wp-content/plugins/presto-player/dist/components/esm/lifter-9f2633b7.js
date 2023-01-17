// Mark Complete Button
var prevent_default = function prevent_default(event) {
  event.preventDefault();
};

function toggle_mark_complete(enabled) {
  var $form = jQuery('.llms-complete-lesson-form'),
      $btn = jQuery('.llms-complete-lesson-form button[type="submit"]'),
      $links = jQuery('#llms_start_quiz, #llms-start-assignment');

  if (true === enabled) {
    $form.off('submit', prevent_default);
    $btn.removeAttr('disabled');
    $links.removeClass('llms-av-disabled').off('click', prevent_default);
  } else {
    $form.on('submit', prevent_default);
    $btn.attr('disabled', 'disabled');
    $links.addClass('llms-av-disabled').on('click', prevent_default);
  }
} // Setting and Getting Event meta data


var getEventMeta = function getEventMeta(player) {
  var eventMeta = {
    ts: player.currentTime,
    duration: player.duration,
    url: player.source,
    provider: player.provider,
    id: player.config.id
  };
  return eventMeta;
}; // tracking event


var track_event = function track_event(event_id, meta) {
  llms.tracking.addEvent('video.' + event_id, {
    meta: meta
  });
}; // ajax call


function start_countdown($el) {
  var $seconds = $el.find('.llms-av-pv--seconds'),
      time;
  setInterval(function () {
    time = $seconds.text() * 1 - 1;

    if (1 === time) {
      $seconds.closest('a')[0].click();
    }

    if (time >= 0) {
      $seconds.text(time);
    }
  }, 1000);
}

function do_ajax_completion($el, settings) {
  LLMS.Ajax.call({
    data: {
      action: 'llms_av_video_ended',
      id: settings.post_id,
      nonce: settings.nonce
    },
    beforeSend: function beforeSend() {
      LLMS.Spinner.start($el);
    },
    error: function error(xhr, status, _error) {
      LLMS.Spinner.stop($el);
      console.error(xhr.responseText + ' (' + _error + ' ' + status + ')');
      /* eslint-disable-line no-console */

      console.log(xhr);
      /* eslint-disable-line no-console */
    },
    success: function success(res) {
      LLMS.Spinner.stop($el);

      if (res !== null && res !== void 0 && res.html) {
        $el.append(res.html);
        start_countdown($el);
      }
    }
  });
}

var onPauseVideo = function onPauseVideo(e) {
  var player = e.detail.plyr;
  var eventMeta = getEventMeta(player);
  track_event('paused', eventMeta);
};
var onPlayingVideo = function onPlayingVideo(e) {
  var player = e.detail.plyr;
  var eventMeta = getEventMeta(player);
  var event_id = eventMeta.ts < 1 ? 'started' : 'played';
  track_event(event_id, eventMeta);
};
var onEndedVideo = function onEndedVideo(e, $el, settings) {
  var player = e.detail.plyr;
  var eventMeta = getEventMeta(player);
  track_event('ended', eventMeta);
  do_ajax_completion($el, settings);
  toggle_mark_complete(true);
};
function lifter (player) {
  var _llms, _llms$tracking;

  /*
    Below code is coped from LifterLMS-Advanced-Videos/assets/js/llms.av.js
    We replaced $ with jQuery.
  */
  var settings = {};
  var tracking_settings = ((_llms = llms) === null || _llms === void 0 ? void 0 : (_llms$tracking = _llms.tracking) === null || _llms$tracking === void 0 ? void 0 : _llms$tracking.getSettings()) || null;

  if (tracking_settings !== null && tracking_settings !== void 0 && tracking_settings.av) {
    settings = tracking_settings.av;
  }
  /*
    LifterLMS-Advanced-Videos copied code end.
    Below code will glue the LifterAV with Presto player.
  */


  if (settings.require_video_completion && !settings.video_completed) {
    toggle_mark_complete(false);
  }

  var $el = jQuery('.presto-block-video'); // on end, sync time and autoload content

  player.on('pause', onPauseVideo);
  player.on('playing', onPlayingVideo);
  player.on('ended', function (e) {
    return onEndedVideo(e, $el, settings);
  });
}

export default lifter;
export { onEndedVideo, onPauseVideo, onPlayingVideo };
