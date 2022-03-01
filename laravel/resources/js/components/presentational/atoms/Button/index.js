import React from "react";
import { Button as ButtonBB } from "react-bootstrap";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class Button extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      variant: "",
      class_name: "",
      mode_class: "",
    };
  }

  componentDidMount() {
    switchMode(this);
  }

  render() {
    const { variant, class_name, mode_class } = this.state;
    const { class_style, type } = this.props;
    return (
      <ButtonBB
        type={type}
        variant={variant}
        onClick={this.props.onClick}
        disabled={this.props.disabled}
        className={
          "shadow-none atoms-button" +
          " " +
          class_name +
          " " +
          mode_class +
          " " +
          class_style
        }
      >
        {this.props.children}
      </ButtonBB>
    );
  }
}

//===================================================
// Functions
//===================================================

/**
 * switch button modes
 */
const switchMode = (context) => {
  const { className, mode } = context.props;
  context.setState({ class_name: className || "" });
  switch (mode) {
    case "active":
      context.setState({
        mode_class: "atoms-Button-active",
        variant: "primary",
      });
      break;
    case "edit":
      context.setState({
        mode_class: "atoms-Button-edit",
        variant: "success",
      });
      break;
    case "addTo":
      context.setState({
        mode_class: "atoms-Button-addTo",
        variant: "danger",
      });
      break;
    case "success":
      context.setState({
        mode_class: "atoms-Button-success",
        variant: "success",
      });
      break;
    case "preview":
      context.setState({
        mode_class: "atoms-Button-preview",
        variant: "preview",
      });
      break;
    case "submit":
      context.setState({
        mode_class: "atoms-Button-submit",
        variant: "submit",
      });
      break;
    case "danger":
      context.setState({
        mode_class: "atoms-Button-danger",
        variant: "danger",
      });
      break;
    case "cancel":
      context.setState({
        mode_class: "atoms-Button-cancel",
        variant: "cancel",
      });
      break;
    case "finish":
      context.setState({
        mode_class: "atoms-Button-finish",
        variant: "submit",
      });
      break;
    case "quiz-options":
      context.setState({
        mode_class: "atoms-Button-quiz-options",
        variant: "submit",
      });
      break;
    case "choice":
      context.setState({
        mode_class: "atoms-choice-button",
        variant: "submit",
      });
      break;
    case "quiz-options-selected":
      context.setState({
        mode_class: "atoms-Button-quiz-options-selected",
        variant: "submit",
      });
      break;
    case "library":
      context.setState({
        mode_class: "atoms-Button-library",
        variant: "submit",
      });
    case "correct":
      context.setState({
        mode_class: "atoms-Div-correct",
      });
      break;
    default:
      break;
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
export default Button;
