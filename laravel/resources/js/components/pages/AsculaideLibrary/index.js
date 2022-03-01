import React from "react";

// components
import Div from "../../presentational/atoms/Div";
import Menu from "../../container/templates/Menu";
import AsculaideLibrary from "../../container/templates/AsculaideLibrary";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class AsculaideLIbrary extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { data } = this.props;
    return (
      <Div className="page-AsculaideLIbraryManagement-wrapper">
        <Menu
          children={<AsculaideLibrary data={data} />}
          pageTitle={"ausculaide_library"}
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
export default AsculaideLIbrary;
