import React from "react";
import { NavLink, withRouter } from "react-router-dom";

// Components
import Div from "../../atoms/Div";
import Span from "../../atoms/Span";
import P from "../../atoms/P";

//i18n
import { withTranslation } from "react-i18next";

//icons
import ArrowDropDownIcon from "@material-ui/icons/ArrowDropDown";
import ArrowDropUpIcon from "@material-ui/icons/ArrowDropUp";

//css
import "./style.css";

const analyticsNavItems = ["analytics", "log-analytics"];
const libraryNavItems = [
  "ausculaide-library",
  "stetho-sounds",
  "palpation-library",
  "ecg-library",
  "inspection-library",
  "xray-library",
  "ucg-library",
];

// redux
import { connect } from "react-redux";

//===================================================
// Component
//===================================================
class SideBar extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      isAnalyticsOpen: false,
      isLibraryOpen: false,
    };
  }

  componentDidMount() {
    handleGetSelectedNav(this);
  }

  render() {
    const { t, isSideMenuVisible } = this.props;
    const { isAnalyticsOpen, isLibraryOpen } = this.state;
    const { user, group } = this.props.auth.userInfo;

    return (
      <Div className="molecules-sidebar" id="sidebar">
        <Div className={isSideMenuVisible ? "sidebar-show" : "sidebar-hide"}>
          <Div className="molecules-sidebar-header">
            <P className="molecules-appname">iPax Admin <i className="molecules-sidebar-beta">{t("beta")}</i></P>
          </Div>
          <Div className="molecules-sidebar-group">
            { group && (
              <P className="molecules-user">{ group.name }</P>
            )}
          </Div>
          <Div className="molecules-sidebar-user">
            <P className="molecules-user">{ user.name }</P>
          </Div>
          <Div>
            <NavLink exact to="/">
              <Span>{t("home")}</Span>
            </NavLink>
          </Div>
          <Div
            className="molecules-dropdown-wrapper"
          >
            <Span>{t("analytics")}</Span>
          </Div>
          <Div className="molecules-dropdown-items">
            <Div>
              <NavLink to="/analytics">
                {/* <Icon url={Chart2} /> */}
                <Span> {t("test_analysis")} </Span>
              </NavLink>
            </Div>
            <Div>
              <NavLink to="/log-analytics">
                {/* <Icon url={Analytics} /> */}
                <Span> {t("log_analytics")} </Span>
              </NavLink>
            </Div>
          </Div>
          <Div>
            <NavLink to="/exam">
              {/* <Icon url={ExamIcon} /> */}
              <Span> {t("exam_management")} </Span>
            </NavLink>
          </Div>
          <Div>
            <NavLink to="/quiz-packs">
              {/* <Icon url={ExamIcon} /> */}
              <Span> {t("quiz_packs_sidebar")} </Span>
            </NavLink>
          </Div>
          {/* <Div>
          <NavLink to="/quiz-packs">
            <Icon url={Quizpack} />
            <Span> {t("quizpacks")} </Span>
          </NavLink>
        </Div> */}
          <Div>
            <NavLink to="/quizzes">
              {/* <Icon url={Quizzes} /> */}
              <Span> {t("quizzes_sidebar")} </Span>
            </NavLink>
          </Div>
          <Div
            className="molecules-dropdown-wrapper"
            onClick={() => handleToggleLibrary(this)}
          >
            <Span>{t("library_management")}</Span>
            {isLibraryOpen ? (
              <ArrowDropUpIcon fontSize="small" />
            ) : (
              <ArrowDropDownIcon fontSize="small" />
            )}
          </Div>
          {isLibraryOpen && (
            <Div className="molecules-dropdown-items">
              <Div>
                <NavLink to="/inspection-library">
                  {/* <Icon url={Inspection} /> */}
                  <Span> {t("inspection_library")} </Span>
                </NavLink>
              </Div>
              <Div>
                <NavLink to="/palpation-library">
                  {/* <Icon url={PalpationIcon} /> */}
                  <Span> {t("palpation_library")} </Span>
                </NavLink>
              </Div>
              <Div>
                <NavLink to="/ausculaide-library">
                  {/* <Icon url={Ausculaide} /> */}
                  <Span> {t("ausculaide_library")} </Span>
                </NavLink>
              </Div>
              <Div>
                <NavLink to="/stetho-sounds">
                  {/* <Icon url={Stetho} /> */}
                  <Span> {t("stetho_sounds")} </Span>
                </NavLink>
              </Div>
              <Div>
                <NavLink to="/ecg-library">
                  {/* <Icon url={Ecg} /> */}
                  <Span> {t("ecg_library")} </Span>
                </NavLink>
              </Div>
              <Div>
                <NavLink to="/xray-library">
                  {/* <Icon url={Xray} /> */}
                  <Span> {t("xray_library")} </Span>
                </NavLink>
              </Div>
              <Div>
                <NavLink to="/ucg-library">
                  {/* <Icon url={UcgLibraryIcon} /> */}
                  <Span> {t("ucg_library")} </Span>
                </NavLink>
              </Div>
            </Div>
          )}
          {/* <Div onClick={this.props.showLogoutModal}>
          <A>
            <Icon url={LogoutWhite} />
            <Span> {t("logout")} </Span>
          </A>
        </Div> */}
          <Div>
            <NavLink to="/user">
              {/* <Icon url={UserManagement} /> */}
              <Span> {t("user_management")} </Span>
            </NavLink>
          </Div>
        </Div>
      </Div>
    );
  }
}
//===================================================
// Functions
//===================================================
/**
 * Toggle library dropdown
 * @param {*} context
 */
const handleToggleLibrary = (context) => {
  const { isLibraryOpen } = context.state;
  context.setState({ isLibraryOpen: !isLibraryOpen, isAnalyticsOpen: false });
};

/**
 * Toggle analytics dropdown
 * @param {*} context
 */
const handleToggleAnalytics = (context) => {
  const { isAnalyticsOpen } = context.state;
  context.setState({ isLibraryOpen: false, isAnalyticsOpen: !isAnalyticsOpen });
};

/**
 * Open dropdown if selected nav item is in dropdown
 * @param {*} context
 */
const handleGetSelectedNav = (context) => {
  const selectedPath = window.location.pathname;
  const pathName = selectedPath.split("/")[1];
  if (analyticsNavItems.includes(pathName)) {
    context.setState({ isAnalyticsOpen: true });
  }
  if (libraryNavItems.includes(pathName)) {
    context.setState({ isLibraryOpen: true });
  }
};

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================
const mapStateToProps = state => {
  return {
    auth: state.auth
  };
};
//===================================================
// Export
//===================================================
export default connect(
  mapStateToProps,
  null
)(withTranslation("translation")(withRouter(SideBar)));
