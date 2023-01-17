const { ComboboxControl } = wp.components;
const { useState } = wp.element;
const { dispatch } = wp.data;
import classNames from "classnames";

export default ({ option, options, value, optionName, className }) => {
  const [filteredOptions, setFilteredOptions] = useState(options || []);
  return (
    <ComboboxControl
      className={classNames(
        className,
        "presto-settings__setting is-combo-control"
      )}
      label={option?.name}
      value={value}
      help={option?.help}
      options={filteredOptions}
      onFilterValueChange={(inputValue) =>
        setFilteredOptions(
          options.filter((option) =>
            option.label.toLowerCase().startsWith(inputValue.toLowerCase())
          )
        )
      }
      onChange={(value) =>
        dispatch("presto-player/settings").updateSetting(
          option.id,
          value,
          optionName
        )
      }
    />
  );
};
