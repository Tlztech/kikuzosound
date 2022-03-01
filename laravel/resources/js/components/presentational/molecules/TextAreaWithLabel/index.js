import React from "react";

// redux

// components
import Label from "../../atoms/Label/index";
import Textarea from "../../atoms/TextArea/index";

// style
import "./style.css";

//===================================================
// Component
//===================================================
class TextareaWithLabel extends React.Component {
  constructor(props) {
    super(props);
  }
  render() {
    return (
      <>
        <Label>{this.props.label}</Label>
        <Textarea placeholder={this.props.placeholder} />
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
export default TextareaWithLabel;