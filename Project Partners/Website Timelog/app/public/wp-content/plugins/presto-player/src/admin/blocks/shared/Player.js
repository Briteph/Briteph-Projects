import { PrestoPlayer } from "@presto-player/react";
import { getProvider } from "../util";
const { useSelect } = wp.data;

export default ({
  src,
  classes,
  preset,
  branding,
  attributes,
  adminPreview,
  currentTime,
  preload = "metadata",
  overlays,
  type,
}) => {
  const { chapters, mutedOverlay, mutedPreview } = attributes;

  const youtube = useSelect((select) => {
    return select("presto-player/player")?.youtube();
  });

  const css = useSelect((select) => {
    return select("presto-player/player")?.playerCSS();
  });

  const convertHex = (hexCode, opacity = 1) => {
    var hex = hexCode.replace("#", "");

    if (hex.length === 3) {
      hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    }

    var r = parseInt(hex.substring(0, 2), 16),
      g = parseInt(hex.substring(2, 4), 16),
      b = parseInt(hex.substring(4, 6), 16);

    /* Backward compatibility for whole number based opacity values. */
    if (opacity > 1 && opacity <= 100) {
      opacity = opacity / 100;
    }

    return "rgba(" + r + "," + g + "," + b + "," + opacity + ")";
  };

  const mutedOverlayContent = () => {
    return (
      <div
        className="presto-player__overlay is-image"
        style={{
          position: "absolute",
          width: `${mutedOverlay?.width || 100}%`,
          left: `${(mutedOverlay?.focalPoint?.x || 0.5) * 100}%`,
          top: `${(mutedOverlay?.focalPoint?.y || 0.5) * 100}%`,
        }}
      >
        <img
          src={mutedOverlay?.src}
          style={{
            transform: "translateX(-50%) translateY(-50%)",
          }}
        />
      </div>
    );
  };

  return (
    <div
      className={"wp-block-video presto-block-video"}
      style={
        type === "audio"
          ? {
              "--presto-player-border-radius": `${preset?.border_radius}px`,
              ...(preset?.background_color
                ? {
                    "--plyr-audio-controls-background": preset.background_color,
                  }
                : { "--plyr-audio-controls-background": branding?.color }),
              ...(preset?.control_color
                ? {
                    "--plyr-audio-control-color": preset.control_color,
                    "--plyr-range-thumb-background": preset.control_color,
                    "--plyr-range-fill-background": preset.control_color,
                    "--plyr-audio-progress-buffered-background": convertHex(
                      preset.control_color || branding?.color || "#00b3ff",
                      0.5
                    ),
                  }
                : {
                    "--plyr-audio-control-color": "#ffffff",
                    "--plyr-range-thumb-background": "#ffffff",
                    "--plyr-range-fill-background": "#ffffff",
                  }),
              "--plyr-range-thumb-shadow": `0px`,
            }
          : {
              "--presto-player-border-radius": `${preset?.border_radius}px`,
              ...(preset?.caption_background
                ? { "--plyr-captions-background": preset.caption_background }
                : {}),
              ...(branding?.color
                ? { "--plyr-color-main": branding.color }
                : {}),
              "--presto-player-email-border-radius": `${
                preset?.email_collection?.border_radius || 0
              }px`,
              "--presto-player-logo-width": `${branding?.logo_width || 75}px`,
            }
      }
    >
      <PrestoPlayer
        src={src}
        css={css}
        classes={classes}
        currentTime={currentTime}
        overlays={overlays}
        isAdmin={true}
        preload={preload}
        preset={preset}
        bunny={{
          thumbnail: attributes?.previewThumbnail,
          preview: attributes?.preview,
        }}
        youtube={{
          channelId: youtube?.channel_id,
        }}
        tracks={
          !!preset?.captions && [
            {
              kind: "captions",
              label: "English",
              srclang: "en",
              src: "/path/to/captions.en.vtt",
              default: true,
            },
          ]
        }
        branding={branding}
        chapters={chapters}
        blockAttributes={attributes}
        poster={attributes.poster}
        provider={type === "audio" ? "audio" : getProvider(src)}
        mediaTitle={attributes.title}
      >
        <div slot="player-end">
          {mutedPreview?.enabled &&
            mutedOverlay?.enabled &&
            mutedOverlayContent()}
          {adminPreview}
        </div>
      </PrestoPlayer>
    </div>
  );
};
