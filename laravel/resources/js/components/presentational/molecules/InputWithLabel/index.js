import React from "react";

//components
import Div from "../../atoms/Div/index";
import Label from "../../atoms/Label/index";
import Input from "../../atoms/Input/index";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class InputWithLabel extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      mode_class: "",
    };
  }

  componentDidMount() {
    switchMode(this);
  }

  render() {
    const { mode_class } = this.state;
    const {
      setInputEmailRef,
      autocomplete,
      onKeyDown,
      min,
      validateError,
      className,
      accept,
    } = this.props;
    return (
      <Div
        className={
          "molecules-inputwithlabel-wrapper" + " " + mode_class + className
        }
      >
        <Label labelError={validateError} mode={this.props.label_mode}>
          {this.props.label}
        </Label>
        <Input
          inputError={validateError}
          setInputRef={(inpEmail) =>
            setInputEmailRef && setInputEmailRef(inpEmail)
          }
          mode={this.props.input_mode}
          typeName={this.props.typeName}
          value={this.props.value}
          placeholder={this.props.placeholder}
          onChange={this.props.onChange}
          name={this.props.name}
          autocomplete={autocomplete}
          onKeyDown={onKeyDown}
          min={min}
          accept={accept}
        />
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================

/**
 *
 * @param {*} context
 */
const switchMode = (context) => {
  const { mode } = context.props;

  switch (mode) {
    case "left":
      context.setState({ mode_class: "justify-left-inputwithlabel" });
      break;
    case "right":
      context.setState({ mode_class: "justify-right-inputwithlabel" });
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
export default InputWithLabel;
