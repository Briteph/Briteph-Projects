/**
 * Maps player actions to wordpress actions
 */
export default player => {
  if (!window?.wp?.hooks) {
    return;
  }

  player.on('ready', () => {
    doAction('Ready', player);
  });
  player.on('play', () => {
    doAction('Play', player);
  });
  player.on('playing', () => {
    doAction('Playing', player);
  });
  player.on('pause', () => {
    doAction('Pause', player);
  });
  player.on('ended', () => {
    doAction('Ended', player);
  });
  player.on('seeked', () => {
    doAction('Seeked', player);
  });
  player.on('timeupdate', () => {
    doAction('TimeUpdate', player);
  });
  player.on('enterfullscreen', () => {
    doAction('EnterFullScreen', player);
  });
  player.on('exitfullscreen', () => {
    doAction('ExitFullScreen', player);
  });

  document.addEventListener('visibilitychange', () => {
    doAction(document.visibilityState === 'hidden' ? 'Hidden' : 'Visible', player);
  });

  // youtube events
  player.on('statechange', e => {
    switch (e?.detail?.code) {
      case 0:
        doAction('Ended', player);
        break;
      case 1:
        doAction('Play', player);
        break;
      case 2:
        doAction('Pause', player);
        break;
    }
  });
};

export function doAction(action, player) {
  window.wp.hooks.doAction(`presto.player${action}`, player);
}
