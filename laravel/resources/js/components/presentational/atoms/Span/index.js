import React from "react";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class Span extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      class_name: "",
      mode_class: ""
    };
  }

  componentDidMount() {
    this.setState({
      class_name: this.props.className ? this.props.className : ""
    });
    switch (this.props.mode) {
      case "primary":
        this.setState({ mode_class: "atoms-Span-primary" });
        break;
      default:
        break;
    }
  }

  render() {
    return (
      <span
        className={
          "atoms-span" +
          " " +
          this.state.class_name +
          " " +
          this.state.mode_class
        }
      >
        {this.props.children}
      </span>
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
export default Span;
