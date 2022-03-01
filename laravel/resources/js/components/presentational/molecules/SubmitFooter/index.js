import React from "react";

// components
import Div from "../../atoms/Div/index";
import Label from "../../atoms/Label/index";
import Select from "../../../presentational/atoms/Select";
import Button from "../../../presentational/atoms/Button";

// style
import "./style.css";

//===================================================
// Component
//===================================================
class SubmitFooter extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { label, data } = this.props;
    return (
      <Div className="molecules-submit-footer">
        <Label>{label}</Label>
        <Select items={data} className="dropdown-box" />
        <Button mode="submit">Submit</Button>
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
export default SubmitFooter;
