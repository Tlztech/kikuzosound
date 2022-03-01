import React from "react";

// components
import Div from "../../presentational/atoms/Div";
import Menu from "../../container/templates/Menu";
import EcgLibraryTemplate from "../../container/templates/EcgLibrary";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class EcgLibrary extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { data } = this.props;
    return (
      <Div className="page-EcgLibraryManagement-wrapper">
        <Menu
          children={<EcgLibraryTemplate data={data} />}
          pageTitle={"ecg_library"}
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
export default EcgLibrary;
