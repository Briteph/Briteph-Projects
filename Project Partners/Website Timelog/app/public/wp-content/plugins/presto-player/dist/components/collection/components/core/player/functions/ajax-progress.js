const namespace = 'presto-player.progress';
let visit_time = Date.now();
let nonce;
/**
 * Sends an updated ajax progress event for plugins to hook into
 */
export default player => {
  var _a, _b;
  // automations are disabled
  if (!player.automations) {
    return;
  }
  // set nonce when fetched
  if (!((_b = (_a = window === null || window === void 0 ? void 0 : window.wp) === null || _a === void 0 ? void 0 : _a.hooks) === null || _b === void 0 ? void 0 : _b.hasAction('presto.nonceRefreshed', namespace))) {
    window.wp.hooks.addAction('presto.nonceRefreshed', namespace, newNonce => {
      nonce = newNonce;
    });
  }
  // on time update, maybe send time update
  window === null || window === void 0 ? void 0 : window.wp.hooks.addAction('presto.playerTimeUpdate', 'presto-player', sendTimeUpdate);
  // be sure to mark complete on end
  window === null || window === void 0 ? void 0 : window.wp.hooks.addAction('presto.playerEnded', 'presto-player', plyr => sendTimeUpdate(plyr, 100));
  let watched = {
    0: false,
    10: false,
    20: false,
    30: false,
    40: false,
    50: false,
    60: false,
    70: false,
    80: false,
    90: false,
    100: false,
  };
  function sendTimeUpdate(player, percent = null) {
    var _a;
    // need to send nonce
    if (!nonce) {
      return;
    }
    // bail if progress is not turned on
    if (!((_a = player === null || player === void 0 ? void 0 : player.config) === null || _a === void 0 ? void 0 : _a.ajaxProgress)) {
      return;
    }
    if (!percent) {
      percent = (parseFloat(player.currentTime) / parseFloat(player.duration)) * 100;
    }
    player.watched = player.watched || {};
    Object.keys(watched).forEach(m => {
      var _a, _b, _c, _d, _e, _f, _g, _h, _j;
      const marker = parseInt(m);
      if (!player.watched[marker] && percent >= marker) {
        player.watched[marker] = true;
        let formData = new FormData();
        formData.append('action', 'presto_player_progress_percent');
        formData.append('id', (_a = player === null || player === void 0 ? void 0 : player.config) === null || _a === void 0 ? void 0 : _a.id);
        formData.append('percent', marker.toString());
        formData.append('visit_time', visit_time.toString());
        formData.append('nonce', nonce);
        if ((_b = window === null || window === void 0 ? void 0 : window.prestoPlayer) === null || _b === void 0 ? void 0 : _b.debug) {
          console.log(`${marker} percent watched.`);
        }
        if (!((_c = window === null || window === void 0 ? void 0 : window.prestoPlayer) === null || _c === void 0 ? void 0 : _c.debug_navigator)) {
          let result = navigator.sendBeacon((_d = window === null || window === void 0 ? void 0 : window.prestoPlayer) === null || _d === void 0 ? void 0 : _d.ajaxurl, formData);
          if ((_e = window === null || window === void 0 ? void 0 : window.prestoPlayer) === null || _e === void 0 ? void 0 : _e.debug) {
            if (result) {
              console.log('Successfully queued progress:', {
                id: (_f = player === null || player === void 0 ? void 0 : player.config) === null || _f === void 0 ? void 0 : _f.id,
                percent: marker,
                visit_time,
                nonce,
              });
            }
            else {
              console.log('Failed to queue progress', {
                id: (_g = player === null || player === void 0 ? void 0 : player.config) === null || _g === void 0 ? void 0 : _g.id,
                percent: marker,
                visit_time,
                nonce,
              });
            }
          }
        }
        else {
          window.jQuery.ajax({
            type: 'POST',
            url: (_h = window === null || window === void 0 ? void 0 : window.prestoPlayer) === null || _h === void 0 ? void 0 : _h.ajaxurl,
            dataType: 'json',
            cache: false,
            data: {
              action: 'presto_player_progress_percent',
              id: (_j = player === null || player === void 0 ? void 0 : player.config) === null || _j === void 0 ? void 0 : _j.id,
              visit_time,
              percent: marker,
              nonce,
            },
          });
        }
      }
    });
  }
};
