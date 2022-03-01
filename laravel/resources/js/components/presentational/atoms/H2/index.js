import React from "react";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class H2 extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      class_name: "",
      mode_class: "",
    };
  }

  componentDidMount() {
    setMode(this);
  }

  render() {
    const { class_name, mode_class } = this.state;
    return (
      <h2 className={"atoms-h2" + " " + class_name + " " + mode_class}>
        {this.props.children}
      </h2>
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
const setMode = (context) => {
  const { className, mode } = context.props;
  context.setState({
    class_name: className ? className : "",
  });
  switch (mode) {
    case "primary":
      context.setState({ mode_class: "atoms-H2-primary" });
      break;
    case "white":
      context.setState({ mode_class: "atoms-H2-white" });
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
export default H2;
