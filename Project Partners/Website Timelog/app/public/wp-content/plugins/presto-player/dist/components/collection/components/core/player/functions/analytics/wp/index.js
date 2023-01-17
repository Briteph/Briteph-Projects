let player;
const namespace = 'presto-player.analytics';
let nonce = '';
let currentTime = 0;
export default function (pl) {
  player = pl;

  if (!player.config.analytics) {
    return;
  }
  if (!window?.wp?.hooks) {
    return;
  }

  if (!wp.hooks.hasAction('presto.nonceRefreshed', namespace)) {
    wp.hooks.addAction('presto.nonceRefreshed', namespace, newNonce => {
      nonce = newNonce;
    });
  }

  if (!wp.hooks.hasAction('presto.playerPlay', namespace)) {
    wp.hooks.addAction('presto.playerPlay', namespace, sendVideoData);
  }
  if (!wp.hooks.hasAction('presto.playerEnded', namespace)) {
    wp.hooks.addAction('presto.playerEnded', namespace, sendVideoData);
  }
  if (!wp.hooks.hasAction('presto.playerHidden', namespace)) {
    wp.hooks.addAction('presto.playerHidden', namespace, sendVideoData);
  }
  if (!wp.hooks.hasAction('presto.playerPause', namespace)) {
    wp.hooks.addAction('presto.playerPause', namespace, sendVideoData);
  }
  if (!wp.hooks.hasAction('presto.playerSeeked', namespace)) {
    wp.hooks.addAction('presto.playerSeeked', namespace, sendVideoData);
  }
}

export function videoData(player) {
  if (player.currentTime > currentTime) {
    currentTime = player.currentTime;
  }
  if (player.currentTime >= player.duration) {
    currentTime = player.duration;
  }
  const data = {
    video_current_time: parseInt(currentTime),
    video_provider: player?.provider || 'html5',
    ...(player?.duration ? { video_duration: parseInt(player?.duration) } : {}),
    ...(player?.source ? { video_url: player?.source } : {}),
    ...(player?.config?.title ? { video_title: player?.config?.title } : {}),
  };
  return data;
}

// send video data
export function sendVideoData(player) {
  if (isNaN(player?.currentTime)) return; // bail if invalid data.
  sendData(videoData(player));
}

export function sendData(data) {
  if (!nonce) {
    return;
  }

  let formData = new FormData();

  formData.append('action', 'presto_player_progress');
  formData.append('duration', data?.video_current_time);
  formData.append('video_id', player?.config?.id);
  formData.append('nonce', nonce);

  if (!window?.prestoPlayer?.debug_navigator) {
    navigator.sendBeacon(window?.prestoPlayer?.ajaxurl, formData);
    return;
  }

  if (typeof jQuery === 'undefined') {
    return;
  }

  jQuery.ajax({
    type: 'POST',
    url: window?.prestoPlayer?.ajaxurl,
    dataType: 'json',
    cache: false,
    data: {
      action: 'presto_player_progress',
      duration: data?.video_current_time,
      video_id: player?.config?.id,
      nonce,
    },
  });
}
