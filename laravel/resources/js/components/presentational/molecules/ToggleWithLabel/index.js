import React from "react";

// redux

// components
import Label from "../../atoms/Label/index";
import Textarea from "../../atoms/TextArea/index";
import ToggleBar from "../../atoms/ToggleBar/index";

// style
import "./style.css";

//===================================================
// Component
//===================================================
class ToggleWithLabel extends React.Component {
  constructor(props) {
    super(props);
  }
  render() {
    return (
      <>
        <Label>{this.props.label}</Label>
        <ToggleBar/>
      </>
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
export default ToggleWithLabel;