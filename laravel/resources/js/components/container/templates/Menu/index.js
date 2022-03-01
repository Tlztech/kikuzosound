import React from "react";

import Sidebar from "../../../presentational/molecules/SideBar";
import TopNav from "../../../presentational/molecules/HeaderNavbar";
import Div from "../../../presentational/atoms/Div";
import Logout from "../../organisms/LogoutModal";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class Menu extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      isSideMenuVisible: sessionStorage.getItem("toggle-nav") || false,
      isLogoutModalVisible: false
    };
  }

  // componentDidMount() {
  //   checkMainDiv(this);
  //   //resize event listener
  //   window.addEventListener("resize", () => {
  //     toggleResponsiveSideNav(this);
  //   });
  // }

  // componentWillUnmount() {
  //   // fix Warning: Can't perform a React state update on an unmounted component
  //   this.setState = (state, callback) => {
  //     return;
  //   };
  // }

  componentDidMount() {
    this.setState({
      isSideMenuVisible: true
    });
    sessionStorage.setItem("toggle-nav", true);
  }

  render() {
    const { isLogoutModalVisible, isSideMenuVisible } = this.state;
    const { children, pageTitle } = this.props;
    return (
      <Div className="menus">
        <Sidebar isSideMenuVisible={isSideMenuVisible} />
        <Div className="main">
          <TopNav
            pageTitle={pageTitle}
            onSideNavToggle={() => toggleSideNav(this)}
            onLogoutToogle={() => toggleLogoutModal(this, true)}
            isSideMenuVisible={isSideMenuVisible}
          />
          <Div className={`content content-${isSideMenuVisible}`}>
            {children}
            <Logout
              isVisible={isLogoutModalVisible}
              toggleVisible={() => toggleLogoutModal(this, false)}
            />
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
 * toggle side drawer
 * @param {*} context
 */
const toggleSideNav = context => {
  context.setState({
    isSideMenuVisible: !context.state.isSideMenuVisible
  });
  sessionStorage.setItem("toggle-nav", !context.state.isSideMenuVisible);
};

/**
 * show logout modal
 * @param context c
 */
const toggleLogoutModal = (c, condition) => {
  c.setState({ isLogoutModalVisible: condition });
};

/**
 * resize listener
 * @param context c
 */
// const toggleResponsiveSideNav = (c) => {
//   let sidebar_items = document.getElementById("sidebar").querySelectorAll("a");
//   if (window.innerWidth < 1067) {
//     showHalfSideNav(c, sidebar_items);
//     c.setState({
//       enable_sidetoggle: false,
//     });
//   } else {
//     if (localStorage.getItem("is_user_toggled_full_div") == "true") {
//       showFullSideNav(c, sidebar_items);
//     }
//     c.setState({
//       enable_sidetoggle: true,
//     });
//   }
// };

/**
 * toggle side drawer
 * @param {*} context
 */
// const toggleSideNav = (context) => {
//   let sidebar_items = document.getElementById("sidebar").querySelectorAll("a");
//   if (c.state.mainDiv == "main-div-side-full") {
//     localStorage.setItem("is_user_toggled_full_div", "false");
//     showHalfSideNav(c, sidebar_items);
//   } else {
//     localStorage.setItem("is_user_toggled_full_div", "true");
//     c.state.enable_sidetoggle == true ? showFullSideNav(c, sidebar_items) : "";
//   }
// };

// /**
//  * showHalfSideNav
//  * @param context c
//  */
// const showHalfSideNav = (c, sidebar_items) => {
//   c.setState({
//     mainDiv: "main-div-side-half",
//     show_logo: true,
//   });
//   localStorage.setItem("maniDiv", "main-div-side-half");

//   sidebar_items.forEach((items) => {
//     items.children[1]
//       ? (items.children[1].style.display = "none")
//       : (items.children[0].children[1].style.display = "none");
//     items.style.justifyContent = "center";
//   });
// };

// /**
//  * showFullSideNav
//  * @param context c
//  */
// const showFullSideNav = (c, sidebar_items) => {
//   c.setState({
//     show_logo: false,
//     mainDiv: "main-div-side-full",
//   });
//   localStorage.setItem("maniDiv", "main-div-side-full");

//   sidebar_items.forEach((items) => {
//     items.children[1]
//       ? (items.children[1].style.display = "block")
//       : (items.children[0].children[1].style.display = "block");
//     items.style.justifyContent = "flex-start";
//   });
// };

// /**
//  * checkdiv
//  * @param context c
//  */
// const checkMainDiv = (c) => {
//   let maindiv = localStorage.getItem("maniDiv") || c.state.mainDiv;
//   let sidebar_items = document.getElementById("sidebar").querySelectorAll("a");
//   if (maindiv == "main-div-side-half") {
//     showHalfSideNav(c, sidebar_items);
//   }
// };
//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default Menu;
