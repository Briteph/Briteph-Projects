// In your application's entrypoint
const { __ } = wp.i18n;
const { InnerBlocks, useBlockProps } = wp.blockEditor;
const { useSelect, dispatch } = wp.data;
const { Button, Placeholder } = wp.components;
const { getPlugins, unregisterPlugin } = wp.plugins;
const { useEffect } = wp.element;

/**
 * Block Name
 */
export const name = "presto-player/reusable-edit";

/**
 * Block Options
 */
export const options = {
  title: "Reusable Block Container",

  category: "presto",

  supports: {
    inserter: false,
    reusable: false,
    html: false,
  },

  icon: (
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      strokeWidth="2"
      strokeLinecap="round"
      strokeLinejoin="round"
      className="presto-block-icon"
    >
      <circle cx="12" cy="12" r="10"></circle>
      <line x1="2" y1="12" x2="22" y2="12"></line>
      <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
    </svg>
  ),

  edit: (props) => {
    const blockProps = useBlockProps();
    const { clientId, isSelected } = props;
    const innerBlocks = useSelect(
      (select) => select("core/block-editor").getBlock(clientId).innerBlocks
    );

    useEffect(() => {
      if (isSelected) {
        wp.data
          .dispatch("core/edit-post")
          .openGeneralSidebar("edit-post/block");
      }
    }, [isSelected]);

    // make sure we don't check template validity because is's buggy as fuck
    dispatch("core/block-editor").setTemplateValidity(true);

    // don't add reusable videos to a reusable video
    useEffect(() => {
      unregisterPlugin("presto-player");
    }, []);

    const insertBlockType = (type) => {
      const block = wp.blocks.createBlock(`presto-player/${type}`);
      return dispatch("core/block-editor").insertBlock(block, 0, clientId);
    };

    const appenderToUse = () => {
      if (innerBlocks.length === 0) {
        return <InnerBlocks.ButtonBlockAppender />;
      } else {
        return false;
      }
    };

    if (!innerBlocks?.length) {
      return (
        <div>
          <div {...blockProps}>
            <Placeholder
              icon={
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  strokeWidth="2"
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  className="presto-block-icon"
                >
                  <polygon points="23 7 16 12 23 17 23 7"></polygon>
                  <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                </svg>
              }
              instructions={__(
                "Choose a video type to get started.",
                "presto-player"
              )}
              label={__("Choose a Video Type", "presto-player")}
            >
              <Button
                isPrimary
                onClick={() => {
                  insertBlockType("self-hosted");
                }}
              >
                {__("Video", "presto-player")}
              </Button>
              <Button
                isPrimary
                onClick={() => {
                  insertBlockType("youtube");
                }}
              >
                {__("Youtube", "presto-player")}
              </Button>
              <Button
                isPrimary
                onClick={() => {
                  insertBlockType("vimeo");
                }}
              >
                {__("Vimeo", "presto-player")}
              </Button>
              {prestoPlayer?.isPremium ? (
                <Button
                  isPrimary
                  onClick={() => {
                    insertBlockType("bunny");
                  }}
                >
                  {__("Bunny.net", "presto-player")}
                </Button>
              ) : (
                ""
              )}
              <Button
                isPrimary
                onClick={() => {
                  insertBlockType("audio");
                }}
              >
                {__("Audio", "presto-player")}
              </Button>
            </Placeholder>
            <InnerBlocks templateLock={false} renderAppender={() => false} />
          </div>
        </div>
      );
    }

    return (
      <div>
        <div {...blockProps}>
          <InnerBlocks
            templateLock={false}
            renderAppender={() => appenderToUse()}
          />
        </div>
      </div>
    );
  },

  save: () => {
    const blockProps = useBlockProps.save();

    return (
      <div {...blockProps}>
        <InnerBlocks.Content />
      </div>
    );
  },
};
