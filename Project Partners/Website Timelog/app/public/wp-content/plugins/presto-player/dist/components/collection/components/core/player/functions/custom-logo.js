export default player => {
  player.on('ready', () => {
    if (!player?.config?.logo || player?.config?.logo_added) {
      return;
    }
    if (typeof jQuery === 'undefined') {
      return;
    }
    if (player.isAudio) return;

    jQuery(`<img src="${player?.config?.logo}" class="presto-player__logo is-bottom-right" part="logo">`).insertBefore(player?.elements?.controls);

    player.config.logo_added = true;
  });
};
