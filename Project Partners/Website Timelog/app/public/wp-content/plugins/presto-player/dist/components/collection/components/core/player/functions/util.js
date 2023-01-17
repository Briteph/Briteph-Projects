export function getYoutubeId(url) {
  var _a;
  const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
  const match = (url || '').match(regExp);
  return match && ((_a = match === null || match === void 0 ? void 0 : match[2]) === null || _a === void 0 ? void 0 : _a.length) === 11 ? match[2] : null;
}
export function getVimeoId(url) {
  const regExp = /^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/;
  const parseUrl = regExp.exec(url || '');
  return (parseUrl === null || parseUrl === void 0 ? void 0 : parseUrl[5]) || '';
}
export function determineVideoUrlType(url) {
  const youtube_id = getVimeoId(url);
  if (youtube_id) {
    return {
      video_id: youtube_id,
      type: 'vimeo',
    };
  }
  const vimeo_id = getYoutubeId(url);
  if (vimeo_id) {
    return {
      video_id: vimeo_id,
      type: 'youtube',
    };
  }
  return {
    $video_id: 0,
    $type: 'none',
  };
}
// is the source hls?
export function isHLS(url) {
  return typeof url === 'string' && url.includes('.m3u8');
}
export function isNotEmptyObject(item) {
  return typeof item === 'object' && Object.keys(item).length;
}
export function isNotEmptyArray(item) {
  return Array.isArray(item) && item.length > 0;
}
export function timePassed({ current, duration, showAfter }) {
  if (current === showAfter) {
    return true;
  }
  let percent = (current / duration) * 100;
  if (99.9 < percent) {
    percent = 100;
  }
  return percent >= showAfter;
}
export function getParents(elem) {
  var parents = [];
  while (elem.parentNode && elem.parentNode.nodeName.toLowerCase() != 'body') {
    elem = elem.parentNode;
    parents.push(elem);
  }
  return parents;
}
