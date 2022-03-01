import React from "react";

// components
import Div from "../../presentational/atoms/Div";
import Menu from "../../container/templates/Menu";
import PalpationLibrary from "../../container/templates/PalpationLibrary";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class PalpationLibraryPage extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { data } = this.props;
    return (
      <Div className="page-PalpationLibrary-wrapper">
        <Menu
          children={<PalpationLibrary data={data} />}
          pageTitle={"palpation_library"}
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
export default PalpationLibraryPage;
