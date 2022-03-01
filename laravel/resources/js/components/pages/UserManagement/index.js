import React from "react";

// components
import Div from "../../presentational/atoms/Div";
import Menu from "../../container/templates/Menu";
import UserBread from "../../container/templates/UserBread";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class UserManagement extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { data } = this.props;
    return (
      <Div className="page-UserManagement-wrapper">
        <Menu
          children={<UserBread data={data} />}
          pageTitle={"user_management"}
        />
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
export default UserManagement;
