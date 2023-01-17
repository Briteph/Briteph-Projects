export default function ({ title = '', description = '' }) {
  return `<div class="presto-player-error" data-timeline-marker>
  <div>
    ${title ? `<div class="presto-player-error__title">${title}</div>` : ``}
      ${description ? `<div class="presto-player-error__description">${description}</div>` : ``}
    </div>
</div>`;
}
