import React from "react";
import "./style.css";

//===================================================
// Component
//===================================================
class HR extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      class_name: this.props.className ? this.props.className : "",
      mode_class: "",
    };
  }

  componentDidMount() {
    switch (this.props.mode) {
      case "dotted":
        this.setState({ mode_class: "atoms-Hr-dotted" });
        break;
      case "divider":
        this.setState({ mode_class: "atoms-Hr-divider" });
        break;
      default:
        break;
    }
  }

  render() {
    let class_name =
      "atoms-Hr" + " " + this.state.class_name + " " + this.state.mode_class;
    return <hr className={class_name}></hr>;
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
export default HR;
