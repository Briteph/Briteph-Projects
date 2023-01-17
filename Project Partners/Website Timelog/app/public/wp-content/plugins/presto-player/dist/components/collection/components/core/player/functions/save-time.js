export default function (player) {
  // check option
  player.on('timeupdate', () => saveProgress(player));
  player.on('ready', () => resumeProgress(player));
  player.on('loadeddata', () => resumeProgress(player));
  player.on('ended', () => clearProgress(player));
}

export function getId(player) {
  return player?.config?.blockAttributes?.id;
}

export function saveProgress(player) {
  if (player?.config?.mutedPreview?.enabled) {
    return;
  }
  updateTime(player);
}

export function clearProgress(player) {
  localStorage.removeItem(`player-progress-${getId(player)}`);
}

export function resumeProgress(player, force = false) {
  if (!player?.config?.save_player_position || player?.config?.mutedPreview?.enabled) {
    return;
  }

  // get current saved time
  let currentTime = getTime(player);

  // maybe start loading HLS
  if (player.hls) {
    if (currentTime >= 0) {
      player.hls.startLoad(currentTime);
    }
  }

  // if we have duration
  // current time shouldn't be more than duration
  if (player.duration) {
    currentTime = Math.min(currentTime, player.duration);
  }

  if (!currentTime || currentTime < 1) {
    return 0;
  }

  player.currentTime = currentTime;
  if (player?.isVimeo) {
    setTimeout(() => {
      player.embed.setCurrentTime(currentTime || 0);
    }, 0);
  }

  return currentTime;
}

export function getTime(player) {
  return parseFloat(localStorage.getItem(`player-progress-${getId(player)}`));
}

export function updateTime(player) {
  if (player.currentTime === 0) {
    return;
  }
  if (!player?.config?.save_player_position) {
    return;
  }
  localStorage.setItem(`player-progress-${getId(player)}`, player.currentTime);
}
