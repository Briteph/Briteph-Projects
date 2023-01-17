const { __ } = wp.i18n;
const { BaseControl, ExternalLink, PanelRow, Notice } = wp.components;
const { useState } = wp.element;
const { dispatch } = wp.data;
const { apiFetch } = wp;
const { compose } = wp.compose;

import { getSetting } from "@/admin/settings/util";

import Integration from "../../../components/Integration";
import TextControl from "../../../components/TextControl";

import withIntegration from "./withIntegration";

export default compose([withIntegration()])(
  ({ settings, success, setSuccess, error, setError, isBusy, makeRequest }) => {
    const api_key = getSetting("mailerlite", "api_key");
    const connected = getSetting("mailerlite", "connected");

    const setData = (data) => {
      dispatch("presto-player/settings").updateSetting(
        "api_key",
        data?.api_key || "",
        "mailerlite"
      );
      dispatch("presto-player/settings").updateSetting(
        "connected",
        data?.connected || false,
        "mailerlite"
      );
    };

    const onConnect = () => {
      makeRequest({
        path: "/presto-player/v1/mailerlite/connect",
        data: { api_key },
        message: __("Connected", "presto-player"),
        success: setData,
        error: setData,
      });
    };

    const onDisconnect = async () => {
      makeRequest({
        path: "/presto-player/v1/mailerlite/disconnect",
        message: __("Disconnected", "presto-player"),
        success: setData,
        error: setData,
      });
    };

    return (
      <Integration
        title={__("Mailerlite")}
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
              className="presto-player__setting--mailerlite-api_key"
              option={{
                id: "api_key",
                name: __("Your Mailerlite API key", "presto-player"),
                help: (
                  <p>
                    {__(
                      "You can create a new key on your MailerLite account page.",
                      "presto-player"
                    )}{" "}
                    <ExternalLink href="https://app.mailerlite.com/integrations/api/">
                      {__("Get My API Key", "presto-player")}
                    </ExternalLink>
                  </p>
                ),
                type: "password",
              }}
              value={settings?.api_key}
              optionName="mailerlite"
              required
            />
          </BaseControl>
        </PanelRow>
      </Integration>
    );
  }
);
