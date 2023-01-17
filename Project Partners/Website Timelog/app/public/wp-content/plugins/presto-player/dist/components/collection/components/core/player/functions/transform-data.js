// takes our saved options and transforms them into a format that plyr.io will use
export default function ({ preset = {}, chapters, branding, src, analytics, automations, autoplay, blockAttributes = {}, provider = '', provider_video_id = '', youtube, type, isAdmin, i18n, storage = {
  enabled: !isAdmin,
  key: `presto-player-${preset.id}`,
}, }) {
  var _a, _b, _c, _d;
  const controlOptions = ['play-large', 'rewind', 'play', 'fast-forward', 'progress', 'current-time', 'mute', 'volume', 'captions', 'settings', 'pip', 'fullscreen'];
  const required = ['settings'];
  const disabled = [...(['youtube', 'vimeo'].includes(provider) ? ['pip'] : [])];
  const controls = controlOptions.filter(key => {
    return ((preset === null || preset === void 0 ? void 0 : preset[key]) || required.includes(key)) && !disabled.includes(key);
  });
  if ((_a = blockAttributes === null || blockAttributes === void 0 ? void 0 : blockAttributes.mutedPreview) === null || _a === void 0 ? void 0 : _a.enabled) {
    storage = {
      enabled: false,
    };
  }
  let playerSettings = Object.assign(Object.assign(Object.assign(Object.assign({ id: blockAttributes === null || blockAttributes === void 0 ? void 0 : blockAttributes.id, title: (blockAttributes === null || blockAttributes === void 0 ? void 0 : blockAttributes.title) || '', blockAttributes: Object.assign(Object.assign({}, blockAttributes), { type }), src, ajaxProgress: true, analytics,
    preset,
    automations, mutedPreview: {
      enabled: (_b = blockAttributes === null || blockAttributes === void 0 ? void 0 : blockAttributes.mutedPreview) === null || _b === void 0 ? void 0 : _b.enabled,
    }, loop: {
      active: (preset === null || preset === void 0 ? void 0 : preset.on_video_end) === 'loop' ? true : false,
    }, chapters,
    controls, settings: ['captions', 'quality', 'loop', ...((preset === null || preset === void 0 ? void 0 : preset.speed) ? ['speed'] : [])], hideControls: preset === null || preset === void 0 ? void 0 : preset.auto_hide, captions: { active: preset === null || preset === void 0 ? void 0 : preset.captions_enabled, language: 'auto', update: false }, logo: branding === null || branding === void 0 ? void 0 : branding.logo, logo_width: branding === null || branding === void 0 ? void 0 : branding.logo_width, hide_logo: preset === null || preset === void 0 ? void 0 : preset.hide_logo, lazy_load_youtube: preset === null || preset === void 0 ? void 0 : preset.lazy_load_youtube, save_player_position: !!(preset === null || preset === void 0 ? void 0 : preset.save_player_position), sticky_scroll: preset === null || preset === void 0 ? void 0 : preset.sticky_scroll, play_video_viewport: preset === null || preset === void 0 ? void 0 : preset.play_video_viewport, autoplay: autoplay && !((_c = window.wp) === null || _c === void 0 ? void 0 : _c.blocks) ? true : false }, (provider ? { provider } : {})), (provider_video_id ? { provider_video_id } : {})), ((blockAttributes === null || blockAttributes === void 0 ? void 0 : blockAttributes.poster) ? { poster: blockAttributes.poster } : {})), { invertTime: preset.hasOwnProperty('invert_time') ? preset === null || preset === void 0 ? void 0 : preset.invert_time : true, 
    // debug: true,
    storage, keyboard: { focused: false, global: false }, resetOnEnd: !!(preset === null || preset === void 0 ? void 0 : preset.reset_on_end), vimeo: {
      byline: false,
      portrait: false,
      title: false,
      speed: true,
      transparent: false,
      // Custom settings from Plyr
      customControls: true,
      // Whether the owner of the video has a Pro or Business account
      // (which allows us to properly hide controls without CSS hacks, etc)
      premium: false,
      playsinline: !!(blockAttributes === null || blockAttributes === void 0 ? void 0 : blockAttributes.playsInline),
      // Prevent Vimeo blocking site
    }, youtube: {
      rel: 0,
      showinfo: 0,
      iv_load_policy: 3,
      modestbranding: 1,
      // Custom settings from Plyr
      customControls: true,
      noCookie: !!(youtube === null || youtube === void 0 ? void 0 : youtube.noCookie),
      playsinline: !!(blockAttributes === null || blockAttributes === void 0 ? void 0 : blockAttributes.playsInline),
    }, tooltips: {
      controls: true,
      seek: true,
    }, 
    // Localisation
    i18n });
  if ((_d = window === null || window === void 0 ? void 0 : window.wp) === null || _d === void 0 ? void 0 : _d.hooks) {
    playerSettings = window.wp.hooks.applyFilters('presto.playerSettings', playerSettings);
  }
  return playerSettings;
}
