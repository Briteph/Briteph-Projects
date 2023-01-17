/** @jsx jsx */
const { __ } = wp.i18n;
const { dispatch } = wp.data;
const { RangeControl, Spinner } = wp.components;
const { useEffect, useState } = wp.element;

import { getSetting, getSettings } from "@/admin/settings/util";
import { css, jsx } from "@emotion/core";
import ColorPicker from "../components/ColorPicker";
import Disabled from "../components/Disabled";
import Group from "../components/Group";
import Media from "../components/Media";
import Page from "../components/Page";
import ComboboxControl from "../components/ComboboxControl";
import ToggleControl from "../components/ToggleControl";
import CodeMirror from "../components/CodeMirror";

export default () => {
  const settings = getSettings();
  const disabled = () => {
    if (prestoPlayer?.isPremium) {
      return false;
    }
    return {
      title: __("Pro Feature", "presto-player"),
      heading: __("Unlock Presto Player Pro", "presto-player"),
      message: __(
        "Get this feature and more with the Pro version of Presto Player!",
        "presto-player"
      ),
      link: "https://prestoplayer.com",
    };
  };

  const [presets, setPresets] = useState([]);
  const [audioPresets, setAudioPresets] = useState([]);

  useEffect(() => {
    async function getPresets() {
      const res = await wp.apiFetch({
        path: `/presto-player/v1/preset/`,
      });
      setPresets(
        res.map(function (item) {
          return {
            label: item.name,
            value: item.id,
          };
        })
      );
    }

    // get audio presets
    async function getAudioPresets() {
      const res = await wp.apiFetch({
        path: `/presto-player/v1/audio-preset/`,
      });
      setAudioPresets(
        res.map(function (item) {
          return {
            label: item.name,
            value: item.id,
          };
        })
      );
    }
    getPresets();
    getAudioPresets();
  }, []);

  return (
    <Page
      title={__("General", "presto-player")}
      description={__(
        "Branding, analytics and uninstall data.",
        "presto-player"
      )}
    >
      <Group
        title={__("Branding", "presto-player")}
        description={__("Global player branding options", "presto-player")}
      >
        <Disabled disabled={disabled()}>
          <Media
            className={"presto-player__setting--logo"}
            option={{
              name: (
                <>
                  {__("Logo", "presto-player")}{" "}
                  {disabled() && (
                    <span className="presto-options__pro-badge">
                      {__("Pro", "presto-player")}
                    </span>
                  )}
                </>
              ),
              id: "logo",
              default: "",
            }}
            maxWidth={getSetting("branding", "logo_width") || 150}
            value={getSetting("branding", "logo")}
            optionName="branding"
          />

          <div style={{ maxWidth: "500px" }}>
            <RangeControl
              className={"presto-player__setting--logo-width"}
              label={__("Logo Max Width", "presto-player")}
              value={getSetting("branding", "logo_width") || 150}
              onChange={(width) => {
                dispatch("presto-player/settings").updateSetting(
                  "logo_width",
                  width,
                  "branding"
                );
              }}
              min={1}
              max={400}
            />
          </div>
        </Disabled>
        <ColorPicker
          className={"presto-player__setting--brand-color"}
          option={{
            name: __("Brand Color", "presto-player"),
            id: "color",
            default: "",
          }}
          value={getSetting("branding", "color")}
          optionName="branding"
        />
      </Group>

      <Group
        title={__("Analytics", "presto-player")}
        disabled={disabled()}
        description={__(
          "Analytics settings for media plays, watch times and more.",
          "presto-player"
        )}
      >
        <div>
          <ToggleControl
            className={"presto-player__setting--analytics-enable"}
            option={{
              id: "enable",
              name: __("Enable", "presto-player"),
              help: __("Enable view analytics for your media", "presto-player"),
            }}
            value={settings?.presto_player_analytics?.enable}
            optionName="analytics"
          />
          {!!settings?.presto_player_analytics?.enable && (
            <ToggleControl
              className={"presto-player__setting--purge-data"}
              option={{
                id: "purge_data",
                name: __("Auto-Purge Data (recommended)", "presto-player"),
                help: __(
                  "Automatically purge data older than 90 days.",
                  "presto-player"
                ),
              }}
              value={
                settings?.presto_player_analytics?.purge_data !== undefined
                  ? settings?.presto_player_analytics?.purge_data
                  : true
              }
              optionName="analytics"
            />
          )}
        </div>
      </Group>

      <Group
        title={__("Presets", "presto-player")}
        disabled={disabled()}
        description={__("Media presets settings.", "presto-player")}
      >
        {!presets.length ? (
          <Spinner />
        ) : (
          <ComboboxControl
            option={{
              name: __("Select default preset for video.", "presto-player"),
              id: "default_player_preset",
            }}
            options={presets}
            value={settings?.presto_player_presets?.default_player_preset}
            optionName="presets"
          />
        )}
        {!audioPresets.length ? (
          <Spinner />
        ) : (
          <ComboboxControl
            option={{
              name: __("Select default preset for audio.", "presto-player"),
              id: "default_player_preset",
            }}
            options={audioPresets}
            value={settings?.presto_player_audio_presets?.default_player_preset}
            optionName="audio_presets"
          />
        )}
      </Group>

      <Group
        disabled={disabled()}
        title={__("Custom CSS", "presto-player")}
        description={__(
          "Quickly add custom css to the player web component.",
          "presto-player"
        )}
      >
        <CodeMirror
          disabled={!prestoPlayer?.isPremium}
          option={{ id: "player_css" }}
          value={settings?.presto_player_branding?.player_css}
          optionName="branding"
        />
      </Group>

      <Group
        title={__("Uninstall Options", "presto-player")}
        description={__(
          "Options to remove data on uninstall.",
          "presto-player"
        )}
      >
        <ToggleControl
          className="presto-player__setting--uninstall"
          option={{
            id: "uninstall_data",
            name: __("Remove data on uninstall", "presto-player"),
            help: __("This removes all data on uninstall.", "presto-player"),
            confirm: {
              title: __("Caution: Data Loss", "presto-player"),
              heading: __("Are you sure?", "presto-player"),
              message: __(
                "Are you sure you want to remove all the data from this plugin? This is irreversible!",
                "presto-player"
              ),
            },
          }}
          value={settings?.presto_player_uninstall?.uninstall_data}
          optionName="uninstall"
        />
      </Group>
    </Page>
  );
};
