const { __ } = wp.i18n;
const {
  Card,
  SnackbarList,
  Flex,
  FlexBlock,
  FlexItem,
  Spinner,
} = wp.components;
const { dispatch, useSelect } = wp.data;
const { useState, useEffect } = wp.element;

import { fetchSettings } from "@/admin/settings/settings";

import { Router, Link, Route } from "@/router";
import { routes } from "./routes";

import SaveButton from "./components/SaveButton";
import General from "./pages/General";
import Integrations from "./pages/Integrations";
import Performance from "./pages/Performance";

function App() {
  let [loading, setLoading] = useState(true);

  // scroll top on history change
  window.onhashchange = () => {
    window.scrollTo(0, 0);
  };

  useEffect(() => {
    fetchSettings().then(() => {
      setLoading(false);
    });
  }, []);

  const notices = useSelect((select) => {
    return select("presto-player/settings").notices();
  });

  const removeNotice = (id) => {
    dispatch("presto-player/settings").removeNotice(id);
  };

  const scrollToTop = () => {
    window.scrollTo(0, 0);
  };

  if (loading) {
    return (
      <div className="presto-settings__loading">
        <Spinner />
      </div>
    );
  }

  return (
    <div className="presto-settings">
      <Router routes={routes} defaultRoute={routes?.general?.path}>
        <Card className="presto-settings__navigation">
          <Flex>
            <FlexBlock>
              <div
                role="tablist"
                aria-orientation="horizontal"
                className="components-tab-panel__tabs"
              >
                <Link
                  to={routes?.general?.path}
                  type="button"
                  role="tab"
                  activeClassName="is-active"
                  className="components-button components-tab-panel__tabs-item presto-player__nav-general"
                >
                  {__("General", "presto-player")}
                </Link>
                <Link
                  to={routes?.integrations?.path}
                  type="button"
                  role="tab"
                  activeClassName="is-active"
                  className="components-button components-tab-panel__tabs-item presto-player__nav-integrations"
                >
                  {__("Integrations", "presto-player")}
                </Link>
                <Link
                  to={routes?.performance?.path}
                  type="button"
                  role="tab"
                  activeClassName="is-active"
                  className="components-button components-tab-panel__tabs-item presto-player__nav-performance"
                >
                  {__("Performance", "presto-player")}
                </Link>
              </div>
            </FlexBlock>
            <FlexItem>
              <SaveButton
                style={{ margin: "0 10px" }}
                form="presto-settings-form"
              />
            </FlexItem>
          </Flex>
        </Card>

        <Route path={routes?.general?.path} onRoute={scrollToTop}>
          <General />
        </Route>
        <Route path={routes?.integrations?.path} onRoute={scrollToTop}>
          <Integrations />
        </Route>
        <Route path={routes?.performance?.path} onRoute={scrollToTop}>
          <Performance />
        </Route>
      </Router>

      <SnackbarList
        className="presto-settings-page-notices"
        notices={notices}
        onRemove={removeNotice}
      />
    </div>
  );
}

export default App;
