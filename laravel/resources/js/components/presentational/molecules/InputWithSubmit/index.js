import React from "react";

// components
import Div from "../../atoms/Div/index";
import Input from "../../atoms/Input/index";
import Button from "../../../presentational/atoms/Button";

// style
import "./style.css";

//===================================================
// Component
//===================================================
class InputWithSubmit extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { name, value, onChange, placeholder, onClick } = this.props;
    return (
      <Div className="molecules-input-submit">
        <Div className="input-with-button">
          <Input
            typeName="text"
            value={value}
            placeholder={placeholder}
            onChange={onChange && onChange}
            name={name}
          />
          <Button mode="submit" onClick={onClick && onClick}>
            Submit
          </Button>
        </Div>
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
export default InputWithSubmit;
