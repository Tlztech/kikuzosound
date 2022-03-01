import React from "react";

// components
import Div from "../../presentational/atoms/Div";
import Menu from "../../container/templates/Menu";
import Home from "../../container/templates/Home";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class HomePage extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    return (
      <Div className="page-home-wrapper">
        <Menu children={<Home />} pageTitle={"home"} />
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
export default HomePage;
