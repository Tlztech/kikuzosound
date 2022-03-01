import React from "react";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class H4 extends React.Component {
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
    return (
      <h4
        className={
          "atoms-h4" + " " + this.state.class_name + " " + this.state.mode_class
        }
      >
        {this.props.children}
      </h4>
    );
  }
}

//===================================================
// Functions
//===================================================
/**
 * set h4 mode
 * @param {*} context 
 */
const setMode = (context) => {
  context.setState({
    class_name: context.props.className ? context.props.className : "",
  });
  switch (context.props.mode) {
    case "primary":
      context.setState({ mode_class: "atoms-H4-primary" });
      break;
    case "white":
      context.setState({ mode_class: "atoms-H4-white" });
      break;
    default:
      break;
  }
}
//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default H4;