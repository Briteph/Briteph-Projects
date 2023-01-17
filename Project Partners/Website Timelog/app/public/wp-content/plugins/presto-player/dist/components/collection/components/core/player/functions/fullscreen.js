export default player => {
  var ua = window.navigator.userAgent;
  var iOS = !!ua.match(/iPad/i) || !!ua.match(/iPhone/i);
  if (!iOS) {
    return;
  }

  const handleFullScreen = (player, open) => {
    let elem = player?.elements?.container?.getRootNode()?.host;
    while ((elem != null ? elem.nodeType : void 0) === Node.ELEMENT_NODE && elem.tagName !== 'BODY' && elem.tagName !== 'HTML') {
      if (open) {
        elem.classList.add('presto-player-fullscreen-open');
      } else {
        elem.classList.remove('presto-player-fullscreen-open');
      }
      // set parent node
      elem = elem.parentNode;
    }
  };

  wp.hooks.addAction('presto.playerEnterFullScreen', 'presto-player', () => {
    if (!player?.fullscreen?.active) {
      return;
    }
    handleFullScreen(player, true);
  });

  wp.hooks.addAction('presto.playerExitFullScreen', 'presto-player', () => {
    if (player?.fullscreen?.active) {
      return;
    }
    handleFullScreen(player, false);
  });
};
