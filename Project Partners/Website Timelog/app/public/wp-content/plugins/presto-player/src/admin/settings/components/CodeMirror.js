/** @jsx jsx */
import { css, jsx } from "@emotion/core";
const { ColorPicker, BaseControl } = wp.components;
const { dispatch } = wp.data;
const { useEffect, useRef } = wp.element;
import classNames from "classnames";

export default ({ option, value, optionName, className, disabled }) => {
  let codeMirror;

  const handleChange = (instance) => {
    if (disabled) {
      return;
    }
    instance.save();
    dispatch("presto-player/settings").updateSetting(
      option.id,
      textRef.current.value,
      optionName
    );
  };

  const textRef = useRef();
  useEffect(() => {
    if (!wp?.CodeMirror) {
      return;
    }
    codeMirror = wp.CodeMirror.fromTextArea(textRef.current, {
      type: "text/css",
      lineNumbers: true,
    });

    codeMirror.on("change", handleChange);
  }, []);

  return (
    <div className={classNames(className, "presto-settings__setting")}>
      <BaseControl
        css={css`
          .CodeMirror {
            height: 200px;
            border: 1px solid #e3e3e3;
            border-radius: 4px;
          }
        `}
        label={option?.name}
        help={option?.help}
      >
        <textarea onChange={handleChange} ref={textRef} rows="5" disabled>
          {value}
        </textarea>
      </BaseControl>
    </div>
  );
};
