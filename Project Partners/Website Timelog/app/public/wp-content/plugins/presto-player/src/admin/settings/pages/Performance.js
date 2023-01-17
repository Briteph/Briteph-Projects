/** @jsx jsx */
import { css, jsx } from "@emotion/core";

const { __ } = wp.i18n;
const { Notice, ExternalLink } = wp.components;
import { getSettings } from "@/admin/settings/util";

import ToggleControl from "../components/ToggleControl";

import Group from "../components/Group";
import Page from "../components/Page";

export default () => {
  const settings = getSettings();

  return (
    <Page
      title={__("Performance", "presto-player")}
      description={__("Player performance preferences.", "presto-player")}
    >
      <Group
        title={__("Performance", "presto-player")}
        description={__(
          "Performance options for player loading.",
          "presto-player"
        )}
      >
        <div>
          <ToggleControl
            className={"presto-player__setting--module-enabled"}
            option={{
              id: "module_enabled",
              name: __("Dynamically Load JavaScript", "presto-player"),
              help: __(
                "Dynamically load javascript modules on the page which can significantly reduce javascript size and increase performance.",
                "presto-player"
              ),
            }}
            value={settings?.presto_player_performance?.module_enabled}
            optionName="performance"
          />
          {!!settings?.presto_player_performance?.module_enabled && (
            <Notice
              css={css`
                background: #f3f4f5 !important;
              `}
              className="presto-notice"
              status="info"
              isDismissible={false}
            >
              <div>
                <strong>{__("Please Note", "presto-player")}</strong>
              </div>
              <div>
                {__(
                  "You may need to exclude the player script from combining, as well as enable CORS requests for some CDNs.",
                  "presto-player"
                )}{" "}
                <ExternalLink href="https://prestoplayer.com/docs/performance-preferences-explained#global-player-performance-setting">
                  {__("Learn More", "presto-player")}
                </ExternalLink>
              </div>
            </Notice>
          )}
        </div>
        <ToggleControl
          className={"presto-player__setting--module-enabled"}
          option={{
            id: "automations",
            name: __(
              "Enable Ajax Requests for Progress Integrations",
              "presto-player"
            ),
            help: __(
              "Keep this on unless you do not plan on using automation, LMS or membership integrations.",
              "presto-player"
            ),
          }}
          value={
            settings?.presto_player_performance?.automations === undefined
              ? true
              : settings?.presto_player_performance?.automations
          }
          optionName="performance"
        />
      </Group>
    </Page>
  );
};
