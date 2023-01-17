import { isHLS } from './util';
export default ({ src, el, preload, currentTime }) => {
  // already loaded
  if (el.getAttribute('hls_loaded')) {
    return;
  }
  // preload is metadata or none
  if (!['metadata', 'none'].includes(preload)) {
    return;
  }
  // no hls
  if (!isHLS(src)) {
    return;
  }
  // if time is greater than 0
  if (currentTime > 0) {
    el.style.height = null;
    el.style.paddingBottom = null;
    el.setAttribute('hls_loaded', '1');
    return;
  }
  // style default
  el.style.height = '0px';
  el.style.paddingBottom = '56.25%';
};
