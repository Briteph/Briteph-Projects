const { __ } = wp.i18n;
const { BaseControl, PanelRow, Notice } = wp.components;
const { dispatch } = wp.data;
const { compose } = wp.compose;

import { getSetting } from "@/admin/settings/util";

import Integration from "../../../components/Integration";
import TextControl from "../../../components/TextControl";
import withIntegration from "./withIntegration";

export default compose([withIntegration()])(
  ({ settings, success, setSuccess, error, setError, isBusy, makeRequest }) => {
    const api_key = getSetting("activecampaign", "api_key");
    const url = getSetting("activecampaign", "url");
    const connected = getSetting("activecampaign", "connected");

    const setData = (data) => {
      dispatch("presto-player/settings").updateSetting(
        "api_key",
        data?.api_key || "",
        "activecampaign"
      );
      dispatch("presto-player/settings").updateSetting(
        "url",
        data?.url || "",
        "activecampaign"
      );
      dispatch("presto-player/settings").updateSetting(
        "connected",
        data?.connected || false,
        "activecampaign"
      );
    };

    const onConnect = () => {
      makeRequest({
        path: "/presto-player/v1/activecampaign/connect",
        data: { api_key, url },
        message: __("Connected", "presto-player"),
        success: setData,
        error: setData,
      });
    };

    const onDisconnect = async () => {
      makeRequest({
        path: "/presto-player/v1/activecampaign/disconnect",
        message: __("Disconnected", "presto-player"),
        success: setData,
        error: setData,
      });
    };

    return (
      <Integration
        title={__("ActiveCampaign")}
        connected={connected}
        onConnect={onConnect}
        onDisconnect={onDisconnect}
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
              className="presto-player__setting--activecampaign-url"
              option={{
                id: "url",
                name: __("Your ActiveCampaign Url", "presto-player"),
                help: (
                  <p>
                    {__(
                      "You can find this on your Settings > Developer page.",
                      "presto-player"
                    )}{" "}
                  </p>
                ),
              }}
              value={settings?.url}
              optionName="activecampaign"
              type="url"
              required
            />
            <TextControl
              className="presto-player__setting--activecampaign-api_key"
              option={{
                id: "api_key",
                name: __("Your ActiveCampaign API key", "presto-player"),
                help: (
                  <p>
                    {__(
                      "You can find this on your Settings > Developer page.",
                      "presto-player"
                    )}{" "}
                  </p>
                ),
                type: "password",
              }}
              value={settings?.api_key}
              optionName="activecampaign"
              required
            />
          </BaseControl>
        </PanelRow>
      </Integration>
    );
  }
);
