import React from "react";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class Label extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      mode_class: "",
    };
  }

  componentDidMount() {
    switchMode(this.props.mode, this);
  }

  render() {
    return (
      <label
        onClick={this.props.onClick}
        className={
          "atoms-label" +
          " " +
          this.props.className +
          " " +
          validateError(this.props.labelError) +
          " " +
          this.state.mode_class
        }
      >
        {this.props.children}
      </label>
    );
  }
}

//===================================================
// Functions
//===================================================
/**
 *
 * @param {*} mode
 * @param {*} context
 */
const switchMode = (mode, context) => {
  switch (mode) {
    case "form":
      context.setState({ mode_class: "atoms-Label-form" });
      break;
    case "error":
      context.setState({ mode_class: "atoms-Label-error" });
      break;
    case "success":
      context.setState({ mode_class: "atoms-Label-success" });
      break;
    case "left":
      context.setState({ mode_class: "atoms-Label-textAlign-left" });
      break;
    case "center":
      context.setState({ mode_class: "atoms-Label-textAlign-center" });
      break;
    default:
      context.setState({ mode_class: mode });
      break;
  }
};

/**
 * get validation error true/false
 * @param {*} mode
 * @param {*} context
 */
const validateError = (value) => {
  if (value) {
    return "atoms-Label-validate-error";
  } else {
    return null;
  }
};

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default Label;
