let fetching = false;
let fetched = false;
function status(response) {
  if (response.status >= 200 && response.status < 300) {
    return Promise.resolve(response);
  }
  else {
    return Promise.reject(new Error(response.statusText));
  }
}
export default player => {
  var _a, _b;
  // we don't have need for nonce
  if (!player.config.analytics && !player.config.automations) {
    return;
  }
  (_b = (_a = window === null || window === void 0 ? void 0 : window.wp) === null || _a === void 0 ? void 0 : _a.hooks) === null || _b === void 0 ? void 0 : _b.addAction('presto.playerPlaying', 'presto-player', () => {
    window === null || window === void 0 ? void 0 : window.wp.hooks.doAction('presto.playerGetNonce');
  });
  // get nonce refresh
  window === null || window === void 0 ? void 0 : window.wp.hooks.addAction('presto.playerGetNonce', 'presto-player', () => {
    var _a;
    // bail if we are already getting it or got it
    if (fetching || fetched) {
      return;
    }
    // we're fetching
    fetched = true;
    // fetch it
    fetch(`${(_a = window === null || window === void 0 ? void 0 : window.prestoPlayer) === null || _a === void 0 ? void 0 : _a.ajaxurl}?action=presto_refresh_progress_nonce`)
      .then(status)
      .then(response => response.json())
      .then(({ data }) => {
      const nonce = data;
      window === null || window === void 0 ? void 0 : window.wp.hooks.doAction('presto.nonceRefreshed', nonce);
      // we got it
      fetching = true;
    })
      .catch(function (error) {
      console.log('Request failed', error);
    })
      .finally(() => {
      // we're done fetching
      fetched = false;
    });
  });
};
