import React from "react";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class H1 extends React.Component {
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
        this.setState({ mode_class: "atoms-H1-primary" });
        break;
      case "white":
        this.setState({ mode_class: "atoms-H1-white" });
        break;
      default:
        break;
    }
  }

  render() {
    return (
      <h1
        className={
          "atoms-h1" + " " + this.state.class_name + " " + this.state.mode_class
        }
      >
        {this.props.children}
      </h1>
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
export default H1;
