export default function ({ name = 'Untitled', order = 1, highlight = false }) {
  return `<div class="presto-player-toc__chapter ${highlight ? 'is-highlighted' : ''}" data-chapter-item>
  
  <span class="presto-player-toc__order">
    ${order}
  </span>
  <span class="presto-player-toc__name">
    ${name}
  </div>
</div>`;
}
