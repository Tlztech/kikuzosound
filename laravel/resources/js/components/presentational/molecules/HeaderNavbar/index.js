import React from "react";

// image
import { Hamburger, LogoutIcon, SettingIcon } from "../../../../assets";

// Components
import Div from "../../atoms/Div";
import Icons from "../../atoms/Icons";
import LanguageChangeButton from "../LanguageChangeButton";
import ChangePasswordModal from "../../../container/organisms/ChangePasswordModal";

//css
import "./style.css";

//i18
import { withTranslation } from "react-i18next";

//redux
import { connect } from "react-redux";
import { withRouter } from "react-router";

//===================================================
// Component
//===================================================
class HeaderNavbar extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      showOptions: false,
      is_visible: false
    };
  }

  componentDidMount() {
    document.addEventListener("mousedown", e => handleClickOutside(e, this));
  }

  componentWillUnmount() {
    document.removeEventListener("mousedown", e => handleClickOutside(e, this));
  }

  render() {
    const { t, pageTitle, isSideMenuVisible } = this.props;
    const { is_visible, showOptions } = this.state;
    const { email } = this.props.auth.user;

    const options = [
      // { id: 1, value: email },
      { id: 2, value: "change_password" }
    ];

    return (
      <Div
        className={`molecules-top-nav molecules-top-nav-${isSideMenuVisible}`}
      >
        <Icons
          className="molecules-icon-nav molecules-hamburger-icon"
          url={Hamburger}
          onClick={this.props.onSideNavToggle}
          alt={"sidemenu-icon"}
        />
        <Div className="molecules-navbar-text">{t(pageTitle)}</Div>
        <Div className="buttons-group">
          <LanguageChangeButton />
          <Div
            className="option-selector"
            onClick={() => {
              toggleOptionBox(this);
            }}
          >
            <Icons
              className="molecules-icon-nav"
              url={SettingIcon}
              alt={"settings-icon"}
            />
            {showOptions && (
              <Div
                className="dropdown-list"
                setInputRef={node => (this.wrapperRef = node)}
              >
                <ul>
                  {options.map((item, index) => (
                    <li
                      key={index}
                      name={item.value}
                      onClick={e => handleAction(e, this, item.value)}
                    >
                      {t(item.value)}
                    </li>
                  ))}
                </ul>
              </Div>
            )}
          </Div>
          <Icons
            className="molecules-icon-nav logoutIcon"
            url={LogoutIcon}
            onClick={this.props.onLogoutToogle}
            alt={"logout-icon"}
          />
        </Div>
        <ChangePasswordModal
          isVisible={is_visible}
          onHideChangePasswordModal={() => onHideChangePasswordModal(this)}
        />
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================
/**
 * close change password modal
 * @param {*} context
 */
const onHideChangePasswordModal = context => {
  context.setState({
    is_visible: false
  });
};

/**
 * toggle setting option dialog
 * @param {*} context
 */
const toggleOptionBox = context => {
  context.setState({
    showOptions: !context.state.showOptions
  });
};

/**
 * Process data according to selected option
 * @param {*} e
 * @param {*} context
 * @param {*} name
 * @returns
 */
const handleAction = (e, context, name) => {
  switch (name) {
    case "change_password":
      context.setState({
        is_visible: true
      });
      return;
    default:
      return;
  }
};

/**
 * handle click outside of setting dialog
 * @param {*} event
 * @param {*} context
 */
const handleClickOutside = (event, context) => {
  if (context.wrapperRef && !context.wrapperRef.contains(event.target)) {
    context.setState({
      showOptions: false
    });
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
    auth: state.auth.userInfo,
    state: state
  };
};

//===================================================
// Export
//===================================================
export default connect(
  mapStateToProps,
  null
)(withTranslation("translation")(withRouter(HeaderNavbar)));
