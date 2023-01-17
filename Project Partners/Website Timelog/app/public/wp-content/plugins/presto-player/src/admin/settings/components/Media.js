const { __ } = wp.i18n;

const { Button, BaseControl } = wp.components;
const { MediaUpload } = wp.mediaUtils;
const { dispatch } = wp.data;
import classNames from "classnames";

export default ({ option, value, optionName, className, maxWidth }) => {
  const onSelect = (image) => {
    dispatch("presto-player/settings").updateSetting(
      option.id,
      image.url,
      optionName
    );
  };
  const onRemoveImage = (e) => {
    dispatch("presto-player/settings").updateSetting(option.id, "", optionName);
    e.preventDefault();
  };

  return (
    <div
      className={classNames(
        className,
        "presto-settings__setting is-media-control"
      )}
    >
      <BaseControl className="editor-video-poster-control">
        <BaseControl.VisualLabel>
          <p>{option?.name}</p>
        </BaseControl.VisualLabel>
        {value && (
          <BaseControl>
            <img
              style={{
                maxWidth,
                border: "1px solid #dcdcdc",
              }}
              src={value}
            />
          </BaseControl>
        )}
        <MediaUpload
          title={option?.help}
          onSelect={onSelect}
          allowedTypes={option?.allowed_types}
          render={({ open }) => (
            <Button
              isSecondary
              onClick={open}
              className={!value ? "button-select" : "button-replace"}
            >
              {!value
                ? __("Select", "presto-player")
                : __("Replace", "presto-player")}
            </Button>
          )}
        />
        <p id={`video-block__logo-image-description-${option?.id}`} hidden>
          {value
            ? sprintf(
                /* translators: %s: poster image URL. */
                __("The current logo image url is %s", "presto-player"),
                value
              )
            : __("There is no logo image currently selected", "presto-player")}
        </p>
        {!!value && (
          <Button onClick={onRemoveImage} isTertiary className="button-remove">
            {__("Remove", "presto-player")}
          </Button>
        )}
      </BaseControl>
      <br />
    </div>
  );
};
