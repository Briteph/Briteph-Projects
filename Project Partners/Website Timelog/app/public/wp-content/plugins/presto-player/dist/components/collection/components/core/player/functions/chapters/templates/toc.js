export default `
<div class="presto-player-toc__wrapper">
    <div class="presto-player-toc__cover" data-player-toc-cover></div>
    <div class="presto-player-toc">
        <div class="presto-player-toc__title">${window?.prestoPlayer?.i18n?.chapters || 'Chapters'}</div>
        <div class="presto-player-toc__list" data-player-toc></div>
    </div>
</div>`;
