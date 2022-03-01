import React from "react";

// components
import Div from "../../presentational/atoms/Div";
import Menu from "../../container/templates/Menu";
import LogAnalytics from "../../container/templates/LogAnalytics";

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
          children={<LogAnalytics data={data} />}
          pageTitle={"log_analytics"}
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
