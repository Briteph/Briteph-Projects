export default function (player) {
  if (player?.config?.poster) {
    player.poster = player.config.poster;
  }
}
