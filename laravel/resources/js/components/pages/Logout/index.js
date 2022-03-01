import React from "react";

// Components
import Menu from "../../container/templates/Menu";
import LogoutModal from "../../container/organisms/LogoutModal";
import Div from "../../presentational/atoms/Div";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class Logout extends React.Component {
  render() {
    return (
      <Div className="page-logout-wrapper">
        <Menu>
          <LogoutModal history={this.props.history} />
        </Menu>
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default Logout;
