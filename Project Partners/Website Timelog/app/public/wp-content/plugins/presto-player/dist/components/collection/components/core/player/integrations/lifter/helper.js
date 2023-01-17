// Mark Complete Button
const prevent_default = event => {
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
}

// Setting and Getting Event meta data
const getEventMeta = player => {
  let eventMeta = {
    ts: player.currentTime,
    duration: player.duration,
    url: player.source,
    provider: player.provider,
    id: player.config.id,
  };
  return eventMeta;
};

// tracking event
const track_event = (event_id, meta) => {
  llms.tracking.addEvent('video.' + event_id, {
    meta: meta,
  });
};

// ajax call
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
      nonce: settings.nonce,
    },
    beforeSend: function () {
      LLMS.Spinner.start($el);
    },
    error: function (xhr, status, error) {
      LLMS.Spinner.stop($el);
      console.error(xhr.responseText + ' (' + error + ' ' + status + ')'); /* eslint-disable-line no-console */
      console.log(xhr); /* eslint-disable-line no-console */
    },
    success: function (res) {
      LLMS.Spinner.stop($el);
      if (res?.html) {
        $el.append(res.html);
        start_countdown($el);
      }
    },
  });
}

export { getEventMeta, track_event, toggle_mark_complete, do_ajax_completion };
