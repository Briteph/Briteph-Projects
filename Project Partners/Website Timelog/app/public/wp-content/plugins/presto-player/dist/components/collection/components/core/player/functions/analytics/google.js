export default function () {
  if (!window?.gtag) {
    return;
  }
  wp.hooks.addAction('presto.playerPlay', 'presto-player', videoStart);
  wp.hooks.addAction('presto.playerPause', 'presto-player', videoPause);
  wp.hooks.addAction('presto.playerReady', 'presto-player', videoLoad);
  wp.hooks.addAction('presto.playerTimeUpdate', 'presto-player', videoTime);
}

export function videoData(player) {
  const data = {
    video_current_time: parseInt(player?.currentTime || 0),
    video_provider: player?.provider || 'html5',
    ...(player?.duration ? { video_duration: parseInt(player?.duration) } : {}),
    ...(player?.source ? { video_url: player?.source } : {}),
    ...(player?.config?.title ? { video_title: player?.config?.title } : {}),
  };
  return data;
}

// send video start
export function videoStart(player) {
  if (!player?.config?.hasPlayed) {
    player.config.hasPlayed = true;
    window?.gtag('event', 'Play', videoData(player));
  }
}

export function videoPause(player) {
  window?.gtag('event', 'Pause', videoData(player));
}

export function videoLoad(player) {
  if (!player?.config?.hasLoaded) {
    player.config.hasLoaded = true;
    window?.gtag('event', 'Player Load', videoData(player));
  }
}

let watched = {
  25: false,
  50: false,
  75: false,
  100: false,
};

export function videoTime(player) {
  if (!player.currentTime) {
    return;
  }
  const percent = (parseFloat(player.currentTime) / parseFloat(player.duration)) * 100;

  Object.keys(watched).forEach(marker => {
    if (!watched[marker] && percent >= parseInt(marker)) {
      watched[marker] = true;
      window?.gtag('event', `${marker} Percent Played`, videoData(player));
    }
  });
}
