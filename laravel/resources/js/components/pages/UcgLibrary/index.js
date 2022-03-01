import React from "react";

// components
import Div from "../../presentational/atoms/Div";
import Menu from "../../container/templates/Menu";
import UcgLibrary from "../../container/templates/UcgLibrary";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class UcgLIbrary extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { data } = this.props;
    return (
      <Div className="page-QuickPacksManagement-wrapper">
        <Menu children={<UcgLibrary data={data} />} pageTitle={"ucg_library"} />
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
export default UcgLIbrary;
