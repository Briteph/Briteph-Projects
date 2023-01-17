import { resumeProgress } from './save-time';
export default ({ player, mutedPreview, captions, progress, savePosition, onPlay, }) => {
  // skip for iOS
  var ua = window.navigator.userAgent;
  var iOS = !!ua.match(/iPad/i) || !!ua.match(/iPhone/i);
  if (iOS) {
    console.log('ios device, do not do muted autoplay');
    return;
  }
  player.config.mutedPreview.enabled = mutedPreview;
  player.muted = mutedPreview;
  player.loop = mutedPreview;
  player.config.ajaxProgress = mutedPreview ? false : progress;
  player.config.save_player_position = mutedPreview ? false : savePosition;
  // caption change, catch error due to error in support check of plyr
  try {
    player.toggleCaptions(mutedPreview ? !!captions : false);
  }
  catch (e) { }
  setTimeout(() => {
    const resume = resumeProgress(player);
    if (!resume) {
      player.restart();
    }
    setTimeout(() => {
      player.muted = mutedPreview;
      onPlay();
    }, 50);
  }, 0);
  return player;
};
