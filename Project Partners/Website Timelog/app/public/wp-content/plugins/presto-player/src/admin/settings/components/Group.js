const { __ } = wp.i18n;
const { Card, CardBody, CardFooter } = wp.components;
import SaveButton from "./SaveButton";
import Disabled from "./Disabled";

export default ({ title, description, children, disabled, hideSaveButton }) => {
  return (
    <Disabled disabled={disabled}>
      <Card size="large" className="presto-options__card">
        <CardBody className={`presto-options__card-body`}>
          <div className="presto-flow" style={{ "--presto-flow-space": "2em" }}>
            <div
              className="presto-flow"
              style={{ "--presto-flow-space": "1em" }}
            >
              {title && (
                <h2 style={{ marginBottom: 0 }}>
                  {title}{" "}
                  {!!disabled && (
                    <div className="presto-options__pro-badge">Pro</div>
                  )}
                </h2>
              )}
              {description && <p>{description}</p>}
            </div>
            <div>{children}</div>
          </div>
        </CardBody>
        {!hideSaveButton ? (
          <CardFooter isShady>
            <div>
              <SaveButton form="presto-settings-form" />
            </div>
          </CardFooter>
        ) : (
          <br />
        )}
      </Card>
    </Disabled>
  );
};
