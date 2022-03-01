import React from "react";

// components
import Div from "../../presentational/atoms/Div";
import Menu from "../../container/templates/Menu";
import InspectionLibrary from "../../container/templates/InspectionLibrary";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class InspectionLIbrary extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { data } = this.props;
    return (
      <Div className="page-InspectionLIbraryManagement-wrapper">
        <Menu
          children={<InspectionLibrary data={data} />}
          pageTitle={"inspection_library"}
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
export default InspectionLIbrary;
