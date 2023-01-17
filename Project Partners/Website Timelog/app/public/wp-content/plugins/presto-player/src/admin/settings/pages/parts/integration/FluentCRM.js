const { __ } = wp.i18n;
const { PanelRow, Notice } = wp.components;
const { dispatch } = wp.data;
const { compose } = wp.compose;

import { getSetting } from "@/admin/settings/util";

import Integration from "../../../components/Integration";
import withIntegration from "./withIntegration";

export default compose([withIntegration()])(
  ({ settings, success, setSuccess, error, setError, isBusy, makeRequest }) => {
    const connected = getSetting("fluentcrm", "connected");

    const setData = (data) => {
      dispatch("presto-player/settings").updateSetting(
        "connected",
        data?.connected || false,
        "fluentcrm"
      );
    };

    const onConnect = () => {
      makeRequest({
        path: "/presto-player/v1/fluentcrm/connect",
        message: __("Installed and connected", "presto-player"),
        success: setData,
        error: setData,
      });
    };

    const onDisconnect = async () => {
      makeRequest({
        path: "/presto-player/v1/fluentcrm/disconnect",
        message: __("Deactivated", "presto-player"),
        success: setData,
        error: setData,
      });
    };

    return (
      <Integration
        title={__("FluentCRM")}
        connected={connected}
        onConnect={onConnect}
        onDisconnect={onDisconnect}
        connectButtonText={__("Install FluentCRM Plugin", "presto-player")}
        disconnectButtonText={__(
          "Deactivate FluentCRM Plugin",
          "presto-player"
        )}
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
        {connected && (
          <PanelRow>
            <Notice
              className="presto-notice"
              status="success"
              isDismissible={false}
            >
              {__("Installed and connected!", "presto-player")}
            </Notice>
          </PanelRow>
        )}
      </Integration>
    );
  }
);
