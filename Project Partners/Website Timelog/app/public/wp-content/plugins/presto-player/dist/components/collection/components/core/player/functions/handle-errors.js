let player;
export default function (pl) {
  player = pl;
  player.on('error', (...args) => {
    console.error({ args });
  });
}
