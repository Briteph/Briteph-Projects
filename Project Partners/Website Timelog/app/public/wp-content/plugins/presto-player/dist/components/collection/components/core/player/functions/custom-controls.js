let player, $wrapper;

import { addChapterControl } from './chapters';

export default function (pl) {
  if (typeof jQuery === 'undefined') {
    return;
  }

  player = pl;
  $wrapper = jQuery(player.elements.container).closest('.presto-player__wrapper');

  // chapter control
  player.on('ready', addChapterControl);

  // control ui
  player.on('controlshidden', addParentClass);
  player.on('controlsshown', removeParentClass);
}

export function addParentClass(e) {
  if (typeof jQuery === 'undefined') {
    return;
  }
  jQuery(e?.detail?.plyr?.elements?.container).closest('.presto-player__wrapper').addClass('presto-player--hide-controls');
}

export function removeParentClass(e) {
  if (typeof jQuery === 'undefined') {
    return;
  }
  jQuery(e?.detail?.plyr?.elements?.container).closest('.presto-player__wrapper').removeClass('presto-player--hide-controls');
}
