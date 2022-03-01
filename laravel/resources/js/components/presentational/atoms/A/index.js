import React from "react";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class A extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      class_name: "",
      mode_class: ""
    };
  }

  componentDidMount() {
    switchMode(this);
  }

  render() {
    const { link, target } = this.props;
    const { class_name, mode_class } = this.state;
    return (
      <a
        href={link}
        target={target}
        className={"atoms-a" + " " + class_name + " " + mode_class}
      >
        {this.props.children}
      </a>
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
const switchMode = context => {
  const { className, mode } = context.props;
  context.setState({
    class_name: className || ""
  });
  switch (mode) {
    case "anchor":
      context.setState({ mode_class: "atoms-A-anchor" });
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
export default A;
