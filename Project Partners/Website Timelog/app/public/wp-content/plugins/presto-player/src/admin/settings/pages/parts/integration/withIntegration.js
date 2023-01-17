/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { useState } = wp.element;
const { dispatch } = wp.data;
const { apiFetch } = wp;
const { createHigherOrderComponent } = wp.compose;

/**
 * Higher order component factory
 *
 * @return {Function} The higher order component.
 */
export default () =>
  createHigherOrderComponent(
    (WrappedComponent) => (props) => {
      const [error, setError] = useState("");
      const [success, setSuccess] = useState("");
      const [isBusy, setIsBusy] = useState(false);

      const makeRequest = async ({
        path,
        data = {},
        message = __("Success", "presto-player"),
        success,
        error,
      }) => {
        setError("");
        setSuccess("");
        setIsBusy(true);
        dispatch("presto-player/settings").setSaving(true);

        try {
          let response = await apiFetch({
            path,
            method: "post",
            data,
          });
          success && success(response);
          setSuccess(message);
        } catch (e) {
          if (e?.message) {
            setError(e.message);
            error && error(e);
          }
        } finally {
          setIsBusy(false);
          dispatch("presto-player/settings").setSaving(false);
        }
      };

      return (
        <WrappedComponent
          success={success}
          setSuccess={setSuccess}
          setError={setError}
          error={error}
          isBusy={isBusy}
          makeRequest={makeRequest}
          {...props}
        />
      );
    },
    "withIntegration"
  );
