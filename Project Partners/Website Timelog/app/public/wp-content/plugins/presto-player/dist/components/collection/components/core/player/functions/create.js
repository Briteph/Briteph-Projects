import { isIOS } from '../../../../util';
import PrestoPlayer from './presto-player';
import { isHLS } from './util';

export default ({ config, selector, src, preload }) => {
  // do we have an HLS video.
  if (src && isHLS(src)) {
    // can we natively play it?
    if (selector && selector.canPlayType('application/vnd.apple.mpegurl') && isIOS()) {
      return createPlayer({ config, selector });
    }

    // use a polyfill only if needed.
    return import('./hls').then(module => {
      const hls = module.default;
      return hls({ config, selector, src, preload });
    });
  }

  // create the player.
  return createPlayer({ config, selector });
};

export const createPlayer = ({ config, selector }) => {
  return new Promise(resolve => {
    const player = new PrestoPlayer(selector, { ...config });
    return resolve(player);
  });
};
