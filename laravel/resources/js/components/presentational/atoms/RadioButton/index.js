import React from "react";

//libs

//css
import "./style.css";

//===================================================
// Component
//===================================================
class RadioButton extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { id, name, value, className, defaultChecked, onClick } = this.props;
    return (
      <>
        <input
          type="radio"
          id={id}
          name={name}
          value={value}
          ref={this.props.Ref}
          className={className}
          checked={defaultChecked}
          onChange = {onClick}
        />
        &nbsp;
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
export default RadioButton;
