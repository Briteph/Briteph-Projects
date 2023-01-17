export default async player => {
  if (typeof jQuery === 'undefined') {
    return;
  }

  if ('ResizeObserver' in window === false) {
    // Loads polyfill asynchronously, only if required.
    const module = await import('@juggle/resize-observer');
    window.ResizeObserver = module.ResizeObserver;
  }

  var ro = new ResizeObserver(entries => {
    for (let entry of entries) {
      const cr = entry.contentRect;

      jQuery(entry.target)
        .find('.plyr__menu__container')
        .css({ maxHeight: `${Math.max(cr.height - 48, 200)}px` });
    }
  });

  if (!player?.elements?.container?.getRootNode()?.firstChild) {
    return;
  }
  ro.observe(player?.elements?.container?.getRootNode()?.firstChild);

  if (!player) {
    return;
  }

  // this resets style on play for some reason
  player.on('playing', () => {
    const cr = player?.elements?.container?.getRootNode().firstChild.getBoundingClientRect();
    jQuery(player?.elements?.container?.getRootNode())
      .find('.plyr__menu__container')
      .css({ maxHeight: `${Math.max(cr.height - 48, 200)}px` });
  });
};
