const { __ } = wp.i18n;
import { getSetting } from "@/admin/settings/util";
import TextControl from "../../components/TextControl";

export default () => {
  const public_id = getSetting("bunny_stream_public", "video_library_id");
  const public_api_key = getSetting(
    "bunny_stream_public",
    "video_library_api_key"
  );
  const public_pullzone_url = getSetting(
    "bunny_stream_public",
    "pull_zone_url"
  );
  const private_id = getSetting("bunny_stream_private", "video_library_id");
  const private_api_key = getSetting(
    "bunny_stream_private",
    "video_library_api_key"
  );
  const private_pullzone_url = getSetting(
    "bunny_stream_private",
    "pull_zone_url"
  );
  const token_auth_key = getSetting("bunny_stream_private", "token_auth_key");

  return (
    <div>
      <TextControl
        className="presto-player__setting--stream_public_id"
        option={{
          id: "video_library_id",
          name: __("Public Video Library ID", "presto-player"),
          help: __("The ID of the video library to use.", "presto-player"),
        }}
        value={public_id}
        optionName="bunny_stream_public"
      />

      <TextControl
        className="presto-player__setting--stream_public_video_library_api_key"
        option={{
          id: "video_library_api_key",
          name: __("Public Token Authentication Key", "presto-player"),
          help: __(
            "The API key for the above video library for read/write access.",
            "presto-player"
          ),
        }}
        type="password"
        value={public_api_key}
        optionName="bunny_stream_public"
      />

      <TextControl
        className="presto-player__setting--stream_public_pull_zone_url"
        option={{
          id: "pull_zone_url",
          name: __("Public CDN Hostname", "presto-player"),
          help: __(
            "The public cdn hostename for the video library.",
            "presto-player"
          ),
        }}
        value={public_pullzone_url}
        optionName="bunny_stream_public"
      />

      <TextControl
        className="presto-player__setting--stream_private_id"
        option={{
          id: "video_library_id",
          name: __("Private Video Library ID", "presto-player"),
          help: __("The ID of the video library to use.", "presto-player"),
        }}
        value={private_id}
        optionName="bunny_stream_private"
      />

      <TextControl
        className="presto-player__setting--stream_private_api_key"
        option={{
          id: "video_library_api_key",
          name: __("Private Video Read/Write API Key", "presto-player"),
          help: __(
            "The API key for the above video library for read/write access.",
            "presto-player"
          ),
        }}
        type="password"
        value={private_api_key}
        optionName="bunny_stream_private"
      />

      <TextControl
        className="presto-player__setting--stream_private_pull_zone_url"
        option={{
          id: "pull_zone_url",
          name: __("Private CDN Hostname", "presto-player"),
          help: __(
            "The private cdn hostename for the video library.",
            "presto-player"
          ),
        }}
        value={private_pullzone_url}
        optionName="bunny_stream_private"
      />

      <TextControl
        className="presto-player__setting--stream_private_token_auth_key"
        option={{
          id: "token_auth_key",
          name: __("Private Video Token Authentication Key", "presto-player"),
          help: __(
            "The token authentication key used to sign private urls",
            "presto-player"
          ),
        }}
        type="password"
        value={token_auth_key}
        optionName="bunny_stream_private"
      />
    </div>
  );
};
