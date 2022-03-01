import React from "react";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class Div extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      class_name: "",
      mode_class: "",
    };
  }

  componentDidMount() {
    switchMode(this);
  }

  render() {
    let class_name =
      "atoms-Div" + " " + this.state.class_name + " " + this.state.mode_class;
    const { setInputRef, ...rest } = this.props;
    return (
      <div
        role={this.props.role}
        className={class_name}
        onClick={this.props.onClick ? this.props.onClick : null}
        ref={(input) => setInputRef && setInputRef(input)}
        {...rest}
      >
        {this.props.children}
      </div>
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
    case "grey":
      context.setState({
        mode_class: "atoms-Div-grey",
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
export default Div;
