const { __ } = wp.i18n;
const { ToggleControl: Toggle, ExternalLink } = wp.components;
const { useState, Fragment } = wp.element;

const semverCompare = require("semver/functions/compare");

import { getSettings, getSetting } from "@/admin/settings/util";

import Group from "../components/Group";
import Page from "../components/Page";

import TextControl from "../components/TextControl";
import ToggleControl from "../components/ToggleControl";
import Mailchimp from "./parts/integration/Mailchimp";
import MailerLite from "./parts/integration/MailerLite";
import FluentCRM from "./parts/integration/FluentCRM";
import ActiveCampaign from "./parts/integration/ActiveCampaign";
import EmailExport from "./parts/EmailExport";
import CTA from "../components/CTA";
import BunnyStream from "./parts/BunnyStream";

export default () => {
  const settings = getSettings();
  const [editBunny, setEditBunny] = useState(false);

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

  const showEmailIntegration = () => {
    if (!prestoPlayer?.isPremium) {
      return true;
    }

    if (
      prestoPlayer?.proVersion &&
      semverCompare(prestoPlayer?.proVersion, "0.9.0") >= 0
    ) {
      return true;
    }

    return false;
  };

  const bunnySettings = () => {
    return (
      <>
        <Group
          title={__("Bunny.net Settings", "presto-player")}
          description={__("Modify bunny.net settings.", "presto-player")}
        >
          {settings?.presto_player_bunny_stream && (
            <Fragment>
              <TextControl
                option={{
                  id: "hls_start_level",
                  name: __("Initial Quality Level", "presto-player"),
                  help: __(
                    "You can set the default quality start level for all streams (i.e. 240, 360, 480, 720, 1080, etc). Set this lower to prevent initial buffering if your users have slow connections. Set this higher to start with a higher quality stream.",
                    "presto-player"
                  ),
                }}
                placeholder={"480"}
                type="number"
                value={settings?.presto_player_bunny_stream?.hls_start_level}
                optionName="bunny_stream"
              />
              {<br />}

              <ToggleControl
                className="presto-player__setting--use-existing-tag"
                option={{
                  id: "disable_legacy_storage",
                  name: __(
                    "Disable Classic Bunny.net Storage",
                    "presto-player"
                  ),
                  help: __(
                    "This will disable Bunny.net classic storage in your block UI.",
                    "presto-player"
                  ),
                }}
                value={
                  settings?.presto_player_bunny_stream?.disable_legacy_storage
                }
                optionName="bunny_stream"
              />

              {<br />}
            </Fragment>
          )}

          <Toggle
            label={__("Edit Bunny.net Settings", "presto-player")}
            help={__(
              "Edit Bunny.net connection settings. Only edit this if you know what you're doing.",
              "presto-player"
            )}
            checked={editBunny}
            onChange={setEditBunny}
          />
          {!!editBunny && (
            <>
              {settings?.presto_player_bunny_stream_public && (
                <Fragment>
                  <h2 style={{ marginTop: "40px" }}>
                    {__("Bunny.net Stream", "presto-player")}
                  </h2>
                  <BunnyStream />
                </Fragment>
              )}

              <h2 style={{ marginTop: "40px" }}>
                {__("Bunny.net Storage (Classic)", "presto-player")}
              </h2>
              <p style={{ fontSize: "12px", color: "#757575" }}>
                {__(
                  'Note: To Change your API key, please click "Reconnect" from a bunny block.',
                  "presto-player"
                )}
              </p>
              <TextControl
                className="presto-player__setting--pull_public_id"
                option={{
                  id: "public_id",
                  name: __("Public ID", "presto-player"),
                  help: __(
                    "The ID of the public pull zone to use.",
                    "presto-player"
                  ),
                }}
                value={settings?.presto_player_bunny_pull_zones?.public_id}
                optionName="bunny_pull_zones"
              />

              <TextControl
                className="presto-player__setting--pull_private_name"
                option={{
                  id: "public_hostname",
                  name: __("Public Host Name", "presto-player"),
                  help: __(
                    "The hostname to use for this pullzone.",
                    "presto-player"
                  ),
                }}
                value={
                  settings?.presto_player_bunny_pull_zones?.public_hostname
                }
                optionName="bunny_pull_zones"
              />

              <TextControl
                className="presto-player__setting--pull_private_id"
                option={{
                  id: "private_id",
                  name: __("Private ID", "presto-player"),
                  help: __(
                    "The ID of the private pull zone to use.",
                    "presto-player"
                  ),
                }}
                value={settings?.presto_player_bunny_pull_zones?.private_id}
                optionName="bunny_pull_zones"
              />

              <TextControl
                className="presto-player__setting--pull_private_name"
                option={{
                  id: "private_hostname",
                  name: __("Private Host Name", "presto-player"),
                  help: __(
                    "The hostname to use for this pullzone.",
                    "presto-player"
                  ),
                }}
                value={
                  settings?.presto_player_bunny_pull_zones?.private_hostname
                }
                optionName="bunny_pull_zones"
              />

              <TextControl
                className="presto-player__setting--pull_private_name"
                option={{
                  id: "private_security_key",
                  name: __(
                    "Private Url Token Authentication Key",
                    "presto-player"
                  ),
                  help: __(
                    "Update the security token used to sign private urls.",
                    "presto-player"
                  ),
                  type: "password",
                }}
                value={
                  settings?.presto_player_bunny_pull_zones?.private_security_key
                }
                optionName="bunny_pull_zones"
              />
            </>
          )}
        </Group>
      </>
    );
  };

  return (
    <Page
      title={__("Integrations", "presto-player")}
      description={__(
        "Third party integrations and connections.",
        "presto-player"
      )}
    >
      <Group
        title={__("Google Analytics", "presto-player")}
        description={__(
          "Analytics settings for media plays, watch times and more.",
          "presto-player"
        )}
        disabled={disabled()}
      >
        <ToggleControl
          className="presto-player__setting--google-analytics"
          option={{
            id: "enable",
            name: __("Enable", "presto-player"),
            help: __(
              "Send analytics events to your Google Analytics account.",
              "presto-player"
            ),
          }}
          value={getSetting("google_analytics", "enable")}
          optionName="google_analytics"
        />
        <ToggleControl
          className="presto-player__setting--use-existing-tag"
          option={{
            id: "use_existing_tag",
            name: __("Use existing on-page tag?", "presto-player"),
            help: __(
              "Should we use an existing google analytics (v4) tag? If not, we'll output one for you.",
              "presto-player"
            ),
          }}
          value={getSetting("google_analytics", "use_existing_tag")}
          optionName="google_analytics"
        />
        <TextControl
          className="presto-player__setting--measurement-id"
          option={{
            id: "measurement_id",
            name: __("Measurement ID", "presto-player"),
            help: __(
              "Enter a Google Analytics Measurement ID, which can be found on your analytics admin page.",
              "presto-player"
            ),
          }}
          value={getSetting("google_analytics", "measurement_id")}
          optionName="google_analytics"
        />
      </Group>

      <Group
        title={__("YouTube", "presto-player")}
        description={__("Settings for YouTube videos.", "presto-player")}
      >
        <ToggleControl
          className="presto-player__setting--youtube-nocookie"
          option={{
            id: "nocookie",
            name: __("Privacy-Enhanced Mode", "presto-player"),
            help: __(
              "Embed YouTube videos without using cookies that track viewing behaviour.",
              "presto-player"
            ),
          }}
          value={getSetting("youtube", "nocookie")}
          optionName="youtube"
        />
        <TextControl
          className="presto-player__setting--youtube-channel-id"
          option={{
            id: "channel_id",
            name: __("Channel ID", "presto-player"),
            help: (
              <div>
                {__(
                  "Enter the ID of your channel to enable Youtube Subscribe button functionality.",
                  "presto-player"
                )}{" "}
                <ExternalLink href="https://support.google.com/youtube/answer/3250431?hl=en">
                  {__("Find my channel id", "presto-player")}
                </ExternalLink>
              </div>
            ),
          }}
          value={getSetting("youtube", "channel_id")}
          optionName="youtube"
        />
      </Group>

      {showEmailIntegration() && (
        <Group
          hideSaveButton={true}
          title={__("Email Capture", "presto-player")}
          description={__(
            "Integrate Presto Player with an email provider for email capture.",
            "presto-player"
          )}
          disabled={disabled()}
        >
          <ActiveCampaign settings={settings?.presto_player_activecampaign} />
          <FluentCRM settings={settings?.presto_player_fluentcrm} />
          <Mailchimp settings={settings?.presto_player_mailchimp} />
          <MailerLite settings={settings?.presto_player_mailerlite} />
          <EmailExport />
        </Group>
      )}

      {!window?.prestoPlayer?.isSetup?.bunny ? (
        <Group>
          <CTA
            className="presto-player__setting--bunny-cta"
            option={{
              name: __("Bunny.net Video", "presto-player"),
              help: __(
                "To get started with Bunny.net, add a Bunny.net video to your page.",
                "presto-player"
              ),
              type: "cta",
              button: {
                text: "Learn More",
                link: "https://prestoplayer.com/secure-video-with-bunny-net",
                target: "_blank",
              },
            }}
          />
        </Group>
      ) : (
        bunnySettings()
      )}
    </Page>
  );
};
