/**
 * Find out if time is passed.
 * @returns boolean
 */
export function timePassed({ current, duration, showAfter = null }) {
  if (showAfter === 0) {
    return true;
  }
  if (showAfter === null || !duration) {
    return false;
  }
  if (current === showAfter) {
    return true;
  }
  let percent = (current / duration) * 100;
  if (99.9 < percent) {
    percent = 100;
  }
  return percent >= showAfter;
}
export function lightOrDark(color) {
  if (!color)
    return;
  // Variables for red, green, blue values
  let r, g, b, hsp;
  // Check the format of the color, HEX or RGB?
  if (color.match(/^rgb/)) {
    // If RGB --> store the red, green, blue values in separate variables
    const rgb = color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/);
    r = rgb[1];
    g = rgb[2];
    b = rgb[3];
  }
  else {
    // If hex --> Convert it to RGB: http://gist.github.com/983661
    const hex = +('0x' + color.slice(1).replace(color.length < 5 && /./g, '$&$&'));
    r = hex >> 16;
    g = (hex >> 8) & 255;
    b = hex & 255;
  }
  // HSP (Highly Sensitive Poo) equation from http://alienryderflex.com/hsp.html
  hsp = Math.sqrt(0.299 * (r * r) + 0.587 * (g * g) + 0.114 * (b * b));
  // Using the HSP value, determine whether the color is light or dark
  if (hsp > 127.5) {
    return 'light';
  }
  else {
    return 'dark';
  }
}
export function timeToSeconds(time) {
  let pieces = time.split(':');
  let seconds;
  if (pieces.length > 1) {
    seconds = parseInt(pieces[0]) * 60;
  }
  return parseInt(pieces[1]) + parseInt(seconds);
}
export function getMobileOperatingSystem() {
  var userAgent = navigator.userAgent || navigator.vendor;
  // Windows Phone must come first because its UA also contains "Android"
  if (/windows phone/i.test(userAgent)) {
    return 'Windows Phone';
  }
  if (/android/i.test(userAgent)) {
    return 'Android';
  }
  // iOS detection from: http://stackoverflow.com/a/9039885/177710
  if ((navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1) || /(iPad|iPhone|iPod)/gi.test(navigator.platform)) {
    return 'iOS';
  }
  return 'unknown';
}
export function isIOS() {
  return getMobileOperatingSystem() === 'iOS';
}
export function isMobile() {
  return getMobileOperatingSystem() !== 'unknown';
}
/**
 * Is iOS Youtube Fullscreen.
 */
export function isiOSYoutubeFullscreen(player) {
  var _a, _b;
  return isIOS() && player.provider === 'youtube' && !((_b = (_a = player === null || player === void 0 ? void 0 : player.config) === null || _a === void 0 ? void 0 : _a.blockAttributes) === null || _b === void 0 ? void 0 : _b.playsinline);
}
export function isWebView() {
  var standalone = window.navigator['standalone'], userAgent = window.navigator.userAgent.toLowerCase(), safari = /safari/.test(userAgent), ios = /iphone|ipod|ipad/.test(userAgent);
  if (ios) {
    if (!standalone && safari) {
      // Safari
      return false;
    }
    else if (!standalone && !safari) {
      // iOS webview
      return true;
    }
  }
  else {
    if (userAgent.includes('wv')) {
      // Android webview
      return true;
    }
    else {
      // Chrome
      return false;
    }
  }
}
export function isAndroidWebView() {
  if (getMobileOperatingSystem() === 'Android' && isWebView()) {
    return true;
  }
  else {
    return false;
  }
}
export function parseColor(color) {
  var m = color.match(/^rgb\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)$/i);
  if (m) {
    return [m[1], m[2], m[3], '1'];
  }
  m = color.match(/^rgba\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*((0.)?\d+)\s*\)$/i);
  if (m) {
    return [m[1], m[2], m[3], m[4]];
  }
}
export function exitFullScreen(player) {
  var _a, _b, _c, _d, _e;
  if (!isMobile())
    return;
  if (!((_b = (_a = player === null || player === void 0 ? void 0 : player.config) === null || _a === void 0 ? void 0 : _a.blockAttributes) === null || _b === void 0 ? void 0 : _b.playsInline)) {
    // html5
    typeof ((_c = player === null || player === void 0 ? void 0 : player.media) === null || _c === void 0 ? void 0 : _c.webkitExitFullScreen) === 'function' && (player === null || player === void 0 ? void 0 : player.media.webkitExitFullScreen());
    // vimeo
    typeof ((_d = player === null || player === void 0 ? void 0 : player.embed) === null || _d === void 0 ? void 0 : _d.exitFullscreen) === 'function' && ((_e = player === null || player === void 0 ? void 0 : player.embed) === null || _e === void 0 ? void 0 : _e.exitFullscreen());
    // youtube hack
    if (player.provider === 'youtube') {
      const lastTime = player.currentTime;
      player.currentTime = player.duration;
      player.once('playing', () => {
        player.currentTime = lastTime;
      });
    }
  }
}
