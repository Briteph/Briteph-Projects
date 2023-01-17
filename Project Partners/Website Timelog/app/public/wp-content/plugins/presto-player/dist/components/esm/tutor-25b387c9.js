function tutor (player) {
  // autoload course content
  var autoload_content = function autoload_content() {
    var _tutorobject2, _tutorobject3;

    if (!_tutorobject.nonce_key || !((_tutorobject2 = _tutorobject) !== null && _tutorobject2 !== void 0 && _tutorobject2.ajaxurl)) {
      return;
    }

    var post_id = getVideoData().post_id;
    var data = {
      action: 'autoload_next_course_content',
      post_id: post_id
    };
    data[_tutorobject.nonce_key] = _tutorobject[_tutorobject.nonce_key];
    jQuery.post((_tutorobject3 = _tutorobject) === null || _tutorobject3 === void 0 ? void 0 : _tutorobject3.ajaxurl, data).done(function (response) {
      var _response$data;

      if (response !== null && response !== void 0 && response.success && response !== null && response !== void 0 && (_response$data = response.data) !== null && _response$data !== void 0 && _response$data.next_url) {
        location.href = response.data.next_url;
      }
    });
  }; // sync time with server


  var sync_time = function sync_time(instance, options) {
    var _tutorobject4;

    var post_id = getVideoData().post_id;
    var data = {
      action: 'sync_video_playback',
      currentTime: instance.currentTime,
      duration: instance.duration,
      post_id: post_id
    };
    data[_tutorobject.nonce_key] = _tutorobject[_tutorobject.nonce_key];
    var data_send = data;

    if (options) {
      data_send = Object.assign(data, options);
    }

    jQuery.post((_tutorobject4 = _tutorobject) === null || _tutorobject4 === void 0 ? void 0 : _tutorobject4.ajaxurl, data_send);
  }; // get video data


  var getVideoData = function getVideoData() {
    var video_track_data = jQuery('#tutor_video_tracking_information').val();
    return video_track_data ? JSON.parse(video_track_data) : {};
  }; // update time on server every 250ms


  var tempTimeNow = 0;
  var intervalSeconds = 30; //Send to tutor backend about video playing time in this interval

  player.on('timeupdate', function (event) {
    var instance = event.detail.plyr;
    var tempTimeNowInSec = tempTimeNow / 4; //timeupdate firing 250ms interval

    if (tempTimeNowInSec >= intervalSeconds) {
      sync_time(instance);
      tempTimeNow = 0;
    }

    tempTimeNow++;
  }); // on end, sync time and autoload content

  player.on('ended', function (event) {
    var video_data = getVideoData();
    var instance = event.detail.plyr;
    var data = {
      is_ended: true
    };
    sync_time(instance, data);

    if (video_data.autoload_next_course_content) {
      autoload_content();
    }
  });
}

export default tutor;
