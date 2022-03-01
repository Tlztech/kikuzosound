import React from "react";

// components
import Div from "../../presentational/atoms/Div";
import Menu from "../../container/templates/Menu";
import StethoSounds from "../../container/templates/StethoSounds";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class StethoSOunds extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { data } = this.props;
    return (
      <Div className="page-StethoLibraryManagement-wrapper">
        <Menu
          children={<StethoSounds data={data} />}
          pageTitle={"stetho_sounds"}
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
export default StethoSOunds;
