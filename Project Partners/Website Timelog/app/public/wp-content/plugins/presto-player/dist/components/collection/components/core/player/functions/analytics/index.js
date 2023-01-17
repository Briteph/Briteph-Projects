import gaTracking from './google';
import wpTracking from './wp';

export default function (player) {
  if (!window?.prestoPlayer?.isPremium) {
    return;
  }
  gaTracking();
  wpTracking(player);
}
