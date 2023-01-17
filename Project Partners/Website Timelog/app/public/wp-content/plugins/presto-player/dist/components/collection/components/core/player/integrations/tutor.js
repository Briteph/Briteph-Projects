export default function (player) {
  // autoload course content
  const autoload_content = () => {
    if (!_tutorobject.nonce_key || !_tutorobject?.ajaxurl) {
      return;
    }
    const post_id = getVideoData().post_id;
    let data = { action: 'autoload_next_course_content', post_id };
    data[_tutorobject.nonce_key] = _tutorobject[_tutorobject.nonce_key];

    jQuery.post(_tutorobject?.ajaxurl, data).done(function (response) {
      if (response?.success && response?.data?.next_url) {
        location.href = response.data.next_url;
      }
    });
  };

  // sync time with server
  const sync_time = (instance, options) => {
    const post_id = getVideoData().post_id;
    let data = { action: 'sync_video_playback', currentTime: instance.currentTime, duration: instance.duration, post_id };
    data[_tutorobject.nonce_key] = _tutorobject[_tutorobject.nonce_key];

    let data_send = data;
    if (options) {
      data_send = Object.assign(data, options);
    }
    jQuery.post(_tutorobject?.ajaxurl, data_send);
  };

  // get video data
  const getVideoData = () => {
    const video_track_data = jQuery('#tutor_video_tracking_information').val();
    return video_track_data ? JSON.parse(video_track_data) : {};
  };

  // update time on server every 250ms
  let tempTimeNow = 0;
  let intervalSeconds = 30; //Send to tutor backend about video playing time in this interval
  player.on('timeupdate', event => {
    const instance = event.detail.plyr;
    const tempTimeNowInSec = tempTimeNow / 4; //timeupdate firing 250ms interval
    if (tempTimeNowInSec >= intervalSeconds) {
      sync_time(instance);
      tempTimeNow = 0;
    }
    tempTimeNow++;
  });

  // on end, sync time and autoload content
  player.on('ended', event => {
    const video_data = getVideoData();
    const instance = event.detail.plyr;
    const data = { is_ended: true };
    sync_time(instance, data);
    if (video_data.autoload_next_course_content) {
      autoload_content();
    }
  });
}
