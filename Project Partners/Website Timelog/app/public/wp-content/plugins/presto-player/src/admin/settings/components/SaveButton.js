const { __ } = wp.i18n;
const { Button } = wp.components;
const { useSelect } = wp.data;
import { getSettings, saveSettings } from "@/admin/settings/settings";

export default ({ style, className }) => {
  const settings = getSettings();

  const ui = useSelect((select) => {
    return select("presto-player/settings").ui();
  });

  const save = (e) => {
    e.preventDefault();
    saveSettings(settings);
  };

  return (
    <Button
      isPrimary
      style={style}
      className={className}
      disabled={ui.saving}
      onClick={save}
    >
      {__("Save Changes", "presto-player")}
    </Button>
  );
};
