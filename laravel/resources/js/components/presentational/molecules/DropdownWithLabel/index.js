import React from "react";

// Components
import Div from "../../../presentational/atoms/Div";
import Label from "../../../presentational/atoms/Label";
import Select from "../../../presentational/atoms/Select";
import Button from "../../../presentational/atoms/Button";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class DropdownLabel extends React.Component {
  render() {
    const {
      dropdown_items,
      label,
      onChange,
      onSubmitClicked,
      value,
    } = this.props;
    return (
      <Div className="molecules-DropdownLabel-wrapper">
        <Label>{label}</Label>
        <Select
          items={dropdown_items}
          onChange={onChange && onChange}
          value={value}
        />
        <Button mode="submit" onClick={onSubmitClicked && onSubmitClicked}>
          Submit
        </Button>
      </Div>
    );
  }
}

//===================================================
// functions
//===================================================

//===================================================
// actions
//===================================================

//===================================================
// redux
//===================================================

//===================================================
// export
//===================================================
export default DropdownLabel;
