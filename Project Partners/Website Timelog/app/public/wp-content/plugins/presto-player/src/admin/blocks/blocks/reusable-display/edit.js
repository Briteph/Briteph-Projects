// In your application's entrypoint
const { __ } = wp.i18n;
const { InnerBlocks, useBlockProps } = wp.blockEditor;
const { BlockControls, InspectorControls } = wp.editor;
const {
  Disabled,
  Toolbar,
  Button,
  PanelBody,
  Panel,
  Placeholder,
  Spinner,
} = wp.components;
const { useSelect, dispatch } = wp.data;
const { useState, useEffect } = wp.element;
const { parse } = wp.blockSerializationDefaultParser;

export default ({ attributes, isSelected, clientId }) => {
  const { id } = attributes;
  const blockProps = useBlockProps();
  const [hasResolved, setHasResolved] = useState(false);
  const [block, setBlock] = useState([]);
  const [link, setLink] = useState("");

  const video = useSelect((select) => {
    if (!id) {
      return;
    }
    return select("presto-player/player").getReusableVideo(id);
  });

  useEffect(() => {
    wp.data.dispatch("core/block-editor").selectBlock(clientId);
  }, [hasResolved]);

  // lock inner blocks
  const innerBlocks = useSelect((select) => {
    return select("core/editor").getBlocksByClientId(clientId)[0].innerBlocks;
  });
  if (innerBlocks && video?.id) {
    innerBlocks.forEach(function (block) {
      dispatch("core/editor").updateBlockAttributes(block.clientId, {
        selectionOverrideClientId: clientId,
      });
    });
  }

  useEffect(() => {
    if (video.id) {
      setHasResolved(true);
      // set inner block
      const blocks = parse(video?.content?.raw);
      setBlock([
        blocks?.[0]?.innerBlocks[0].blockName,
        blocks?.[0]?.innerBlocks[0].attrs,
      ]);

      setLink(`post.php?post=${video.id}&action=edit`);
    }
  }, [video]);

  if (!hasResolved) {
    return (
      <div {...blockProps}>
        <Placeholder>
          <Spinner />
        </Placeholder>
      </div>
    );
  }

  if (!block) {
    return;
  }

  return (
    <>
      <InspectorControls>
        <PanelBody>
          <p>
            This is a reusable video that you can edit once and place anywhere.
          </p>
          <Button isSecondary href={"edit.php?post_type=pp_video_block"}>
            {__("Manage Media Hub", "presto-player")}
          </Button>
        </PanelBody>
      </InspectorControls>
      <div className={"block-library-block__reusable-block-container"}>
        <div {...blockProps}>
          {isSelected && (
            <div className="reusable-block-edit-panel">
              <b className="reusable-block-edit-panel__info">
                {video?.title?.raw}
              </b>
              <Button
                isSecondary
                href={link}
                className="reusable-block-edit-panel__button"
              >
                {__("Edit Reusable Video", "presto-player")}
              </Button>
            </div>
          )}
          <Disabled>
            <InnerBlocks
              template={[block]}
              templateLock={"all"}
              renderAppender={false}
            />
          </Disabled>
        </div>
      </div>
    </>
  );
};
