const { __ } = wp.i18n;
const { BaseControl, ExternalLink, PanelRow, Notice } = wp.components;
const { dispatch } = wp.data;
const { compose } = wp.compose;

import { getSetting } from "@/admin/settings/util";

import Integration from "../../../components/Integration";
import TextControl from "../../../components/TextControl";

import withIntegration from "./withIntegration";

export default compose([withIntegration()])(
  ({ settings, success, setSuccess, error, setError, isBusy, makeRequest }) => {
    const api_key = getSetting("mailchimp", "api_key");
    const connected = getSetting("mailchimp", "connected");

    const setData = (data) => {
      dispatch("presto-player/settings").updateSetting(
        "api_key",
        data?.api_key || "",
        "mailchimp"
      );
      dispatch("presto-player/settings").updateSetting(
        "connected",
        data?.connected || false,
        "mailchimp"
      );
    };

    const onConnect = () => {
      makeRequest({
        path: "/presto-player/v1/mailchimp/connect",
        data: { api_key },
        message: __("Connected", "presto-player"),
        success: setData,
        error: setData,
      });
    };

    const onDisconnect = async () => {
      makeRequest({
        path: "/presto-player/v1/mailchimp/disconnect",
        message: __("Disconnected", "presto-player"),
        success: setData,
        error: setData,
      });
    };
    return (
      <Integration
        title={__("Mailchimp")}
        connected={connected}
        onDisconnect={onDisconnect}
        onConnect={onConnect}
        isBusy={isBusy}
      >
        {error && (
          <PanelRow>
            <Notice
              className="presto-notice"
              status="error"
              onRemove={() => setError("")}
            >
              {error}
            </Notice>
          </PanelRow>
        )}
        {success && (
          <PanelRow>
            <Notice
              className="presto-notice"
              status="success"
              onRemove={() => setSuccess("")}
            >
              {success}
            </Notice>
          </PanelRow>
        )}
        <PanelRow>
          <BaseControl>
            <TextControl
              className="presto-player__setting--mailchimp-api_key"
              option={{
                id: "api_key",
                name: __("Your Mailchimp API key", "presto-player"),
                help: (
                  <p>
                    {__(
                      "You can create a new key on your mailchimp account page.",
                      "presto-player"
                    )}{" "}
                    <ExternalLink href="https://us11.admin.mailchimp.com/account/api/">
                      {__("Get My API Key", "presto-player")}
                    </ExternalLink>
                  </p>
                ),
                type: "password",
              }}
              value={settings?.api_key}
              optionName="mailchimp"
              required
            />
          </BaseControl>
        </PanelRow>
      </Integration>
    );
  }
);
