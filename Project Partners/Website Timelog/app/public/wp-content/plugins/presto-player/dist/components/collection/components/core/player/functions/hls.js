import PrestoPlayer from './presto-player';

const loadHLS = ({ config, selector, src, preload }) => {
  return new Promise(async (resolve, reject) => {
    if (!window.Hls) {
      while (!window.hasOwnProperty('Hls')) await new Promise(resolve => setTimeout(resolve, 50));
    }

    if (window.Hls.isSupported()) {
      // should we autoload start
      let autoStartLoad = !['metadata', 'none'].includes(preload);

      // always autoload in admin for preview
      if (wp?.blocks) {
        autoStartLoad = true;
      }

      // maybe don't auto start load depending on performance parameter
      var hls = new window.Hls({
        autoStartLoad,
      });
      hls.loadSource(src);

      // update menu container to dynamically display quality
      hls.on(window.Hls.Events.LEVEL_SWITCHED, (event, data) => {
        var span = selector.closest('.presto-player__wrapper').querySelector(".plyr__menu__container [data-plyr='quality'][value='0'] span");
        if (hls.autoLevelEnabled) {
          span.innerHTML = `AUTO (${hls.levels[data.level].height}p)`;
        } else {
          span.innerHTML = `AUTO`;
        }
      });

      // From the m3u8 playlist, hls parses the manifest and returns
      // all available video qualities. This is important, in this approach,
      // we will have one source on the Plyr player.
      hls.on(window.Hls.Events.MANIFEST_PARSED, function (event, data) {
        // Transform available levels into an array of integers (height values).
        var availableQualities = hls.levels.map(function (l) {
          return l.height;
        });
        availableQualities.unshift(0); //prepend 0 to quality array;

        // do start level
        const startLevel = availableQualities.findIndex(qty => qty === parseInt(prestoPlayer?.hls_start_level));
        hls.startLevel = startLevel ? startLevel - 1 : 2;

        // Add new qualities to option
        config.quality = {
          default: 0,
          options: availableQualities,
          // this ensures Plyr to use Hls to update quality level
          forced: true,
          onChange: function (newQuality) {
            if (newQuality === 0) {
              prestoHLS.currentLevel = -1; //Enable AUTO quality if option.value = 0
            } else {
              prestoHLS.levels.forEach(function (level, levelIndex) {
                if (level.height === newQuality) {
                  console.log('Found quality match with ' + newQuality);
                  prestoHLS.currentLevel = levelIndex;
                }
              });
            }
          },
        };

        // attach media and make our HLS options available globally
        hls.attachMedia(selector);
        window.prestoHLS = hls;

        // Initialize player
        const player = new PrestoPlayer(selector, { ...config });
        player.hls = hls;

        // make sure we start loading on the first play
        const firstPlay = () => {
          hls.startLoad(-1);
          player.off('waiting', firstPlay);
        };
        player.on('waiting', firstPlay);

        // Handle changing captions
        player.on('languagechange', () => {
          // Caption support is still flaky. See: https://github.com/sampotts/plyr/issues/994
          setTimeout(() => (hls.subtitleTrack = player.currentTrack), 50);
        });

        return resolve(player);
      });

      return;
    } else if (selector.canPlayType('application/vnd.apple.mpegurl')) {
      return resolve(new PrestoPlayer(selector, { ...config }));
    }

    return resolve(new PrestoPlayer(selector, { ...config }));
  });
};

export default loadHLS;
