import React from "react";

// components
import Div from "../../presentational/atoms/Div";
import Menu from "../../container/templates/Menu";
import XrayLibrary from "../../container/templates/XrayLibrary";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class XrayLIbrary extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { data } = this.props;
    return (
      <Div className="page-XrayLIbraryManagement-wrapper">
        <Menu
          children={<XrayLibrary data={data} />}
          pageTitle={"xray_library"}
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
export default XrayLIbrary;
