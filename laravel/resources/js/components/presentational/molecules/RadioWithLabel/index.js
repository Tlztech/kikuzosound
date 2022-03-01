import React from "react";

// Components
import Label from "../../atoms/Label";
import RadioButton from "../../atoms/RadioButton";
import Span from "../../atoms/Span";

// styles
import "./style.css";

//===================================================
// Component
//===================================================
class RadioWithLabel extends React.Component {
  render() {
    const {
      title,
      id,
      name,
      value,
      defaultChecked,
      onClick,
      className = "",
    } = this.props;
    return (
      <Span className={`molecule-radio-label-container ${className}`}>
        <RadioButton
          id={id}
          name={name}
          value={value}
          Ref={this.props.Ref}
          defaultChecked={defaultChecked}
          onClick={onClick}
        />
        <Label>{title}</Label>
      </Span>
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
export default RadioWithLabel;
