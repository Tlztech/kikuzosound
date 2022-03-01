import React from "react";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class H3 extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      class_name: "",
      mode_class: "",
    };
  }

  componentDidMount() {
    this.setState({
      class_name: this.props.className ? this.props.className : "",
    });
    switch (this.props.mode) {
      case "primary":
        this.setState({ mode_class: "atoms-H3-primary" });
        break;
      case "white":
        this.setState({ mode_class: "atoms-H3-white" });
        break;
      default:
        break;
    }
  }

  render() {
    return (
      <h3
        className={
          "atoms-h3" + " " + this.state.class_name + " " + this.state.mode_class
        }
      >
        {this.props.children}
      </h3>
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
export default H3;
