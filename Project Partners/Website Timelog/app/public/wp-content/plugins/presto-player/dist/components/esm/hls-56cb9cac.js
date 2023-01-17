import { m as _asyncToGenerator, a as _objectSpread2 } from './index-a99dfdd1.js';
import { P as PrestoPlayer } from './presto-action-bar.presto-action-bar-controller.presto-action-bar-ui.presto-audio.presto-bunny.presto-business-skin.presto-cta-overlay.presto-cta-overlay-controller.presto-cta-overlay-ui.presto-dynamic-overlay-ui.presto-dynamic-overlays.presto-email-overlay.presto-email-overlay-controller.presto-email-overlay-ui.presto-modern-skin.presto-muted-overlay.presto-player.presto-player-button.presto-player-spinner.presto-stacked-skin.presto-timestamp.presto-video.presto-vimeo.presto-youtube.presto-youtube-subscribe-button-810a6b8f.js';

var loadHLS = function loadHLS(_ref) {
  var config = _ref.config,
      selector = _ref.selector,
      src = _ref.src,
      preload = _ref.preload;
  return new Promise( /*#__PURE__*/function () {
    var _ref2 = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(resolve, reject) {
      var _wp, autoStartLoad, hls;

      return regeneratorRuntime.wrap(function _callee$(_context) {
        while (1) {
          switch (_context.prev = _context.next) {
            case 0:
              if (window.Hls) {
                _context.next = 6;
                break;
              }

            case 1:
              if (window.hasOwnProperty('Hls')) {
                _context.next = 6;
                break;
              }

              _context.next = 4;
              return new Promise(function (resolve) {
                return setTimeout(resolve, 50);
              });

            case 4:
              _context.next = 1;
              break;

            case 6:
              if (!window.Hls.isSupported()) {
                _context.next = 16;
                break;
              }

              // should we autoload start
              autoStartLoad = !['metadata', 'none'].includes(preload); // always autoload in admin for preview

              if ((_wp = wp) !== null && _wp !== void 0 && _wp.blocks) {
                autoStartLoad = true;
              } // maybe don't auto start load depending on performance parameter


              hls = new window.Hls({
                autoStartLoad: autoStartLoad
              });
              hls.loadSource(src); // update menu container to dynamically display quality

              hls.on(window.Hls.Events.LEVEL_SWITCHED, function (event, data) {
                var span = selector.closest('.presto-player__wrapper').querySelector(".plyr__menu__container [data-plyr='quality'][value='0'] span");

                if (hls.autoLevelEnabled) {
                  span.innerHTML = "AUTO (".concat(hls.levels[data.level].height, "p)");
                } else {
                  span.innerHTML = "AUTO";
                }
              }); // From the m3u8 playlist, hls parses the manifest and returns
              // all available video qualities. This is important, in this approach,
              // we will have one source on the Plyr player.

              hls.on(window.Hls.Events.MANIFEST_PARSED, function (event, data) {
                // Transform available levels into an array of integers (height values).
                var availableQualities = hls.levels.map(function (l) {
                  return l.height;
                });
                availableQualities.unshift(0); //prepend 0 to quality array;
                // do start level

                var startLevel = availableQualities.findIndex(function (qty) {
                  var _prestoPlayer;

                  return qty === parseInt((_prestoPlayer = prestoPlayer) === null || _prestoPlayer === void 0 ? void 0 : _prestoPlayer.hls_start_level);
                });
                hls.startLevel = startLevel ? startLevel - 1 : 2; // Add new qualities to option

                config.quality = {
                  default: 0,
                  options: availableQualities,
                  // this ensures Plyr to use Hls to update quality level
                  forced: true,
                  onChange: function onChange(newQuality) {
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
                  }
                }; // attach media and make our HLS options available globally

                hls.attachMedia(selector);
                window.prestoHLS = hls; // Initialize player

                var player = new PrestoPlayer(selector, _objectSpread2({}, config));
                player.hls = hls; // make sure we start loading on the first play

                var firstPlay = function firstPlay() {
                  hls.startLoad(-1);
                  player.off('waiting', firstPlay);
                };

                player.on('waiting', firstPlay); // Handle changing captions

                player.on('languagechange', function () {
                  // Caption support is still flaky. See: https://github.com/sampotts/plyr/issues/994
                  setTimeout(function () {
                    return hls.subtitleTrack = player.currentTrack;
                  }, 50);
                });
                return resolve(player);
              });
              return _context.abrupt("return");

            case 16:
              if (!selector.canPlayType('application/vnd.apple.mpegurl')) {
                _context.next = 18;
                break;
              }

              return _context.abrupt("return", resolve(new PrestoPlayer(selector, _objectSpread2({}, config))));

            case 18:
              return _context.abrupt("return", resolve(new PrestoPlayer(selector, _objectSpread2({}, config))));

            case 19:
            case "end":
              return _context.stop();
          }
        }
      }, _callee);
    }));

    return function (_x, _x2) {
      return _ref2.apply(this, arguments);
    };
  }());
};

export default loadHLS;
