export default function ({ position = 0, name = '' }) {
  return `<div class="presto-player-progress__marker plyr__controls__item plyr__control" style="left: ${position}%" data-timeline-marker>
    <span class="label--not-pressed plyr__tooltip" role="tooltip">${name}</span>
  </div>`;
}
