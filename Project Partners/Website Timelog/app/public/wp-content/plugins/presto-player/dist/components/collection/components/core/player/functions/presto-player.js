import Player from 'plyr';

import saveTime from './save-time';
import setControls from './custom-controls';
import analytics from './analytics';
import customLogo from './custom-logo';
import setPoster from './set-poster';
import actions from './actions';
import ajaxProgress from './ajax-progress';
import getNonce from './get-nonce';
import fullscreen from './fullscreen';
import menuSizing from './menu-sizing';

/**
 * Get things going
 *
 * @param {HTMLElement} element
 */
export default function (element, options = {}) {
  const setup = {
    ...options,
    ...(window?.prestoPlayer?.plugin_url
      ? {
          iconUrl: `${window?.prestoPlayer?.plugin_url}img/` + `${options?.preset?.skin ? options.preset.skin : 'default'}` + '.svg',
        }
      : {}),
    ...{
      chapters: options?.chapters || [],
      controls: options?.controls || [],
      settings: Object.keys(options?.settings || {}).length ? options.settings : {},
    },
  };

  let player = new Player(element, setup);

  // conditionally load learndash
  if (typeof learndash_video_data !== 'undefined') {
    import('../integrations/learndash').then(module => {
      const learnDash = module.default;
      learnDash(player);
    });
  }

  if (typeof _tutorobject !== 'undefined') {
    import('../integrations/tutor').then(module => {
      const tutor = module.default;
      tutor(player);
    });
  }

  if (typeof window.llms !== 'undefined') {
    if (prestoPlayer?.lifter?.isLesson === true) {
      import('../integrations/lifter/lifter').then(module => {
        const lifter = module.default;
        lifter(player);
      });
    }
  }

  actions(player);
  fullscreen(player);
  menuSizing(player);
  customLogo(player);
  setPoster(player);
  setControls(player);

  // if we're in the admin, don't do the rest of these.
  if (window?.prestoPlayer?.isAdmin) {
    return player;
  }

  // non-admin stuff
  getNonce(player);
  ajaxProgress(setup);
  saveTime(player);
  analytics(player);

  return player;
}
