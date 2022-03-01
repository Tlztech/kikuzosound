import React from "react";

//libs

//css
import "./style.css";

//===================================================
// Component
//===================================================
class P extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      class_name: "",
      mode_class: "",
    };
  }

  componentDidMount() {
    this.setState({ class_name: this.props.className || "" });
    switch (this.props.mode) {
      case "primary":
        this.setState({ mode_class: "atoms-P-primary" });
        break;
      case "grey":
        this.setState({ mode_class: "atoms-P-grey" });
        break;
      case "table":
        this.setState({ mode_class: "atoms-P-table" });
        break;
      default:
        break;
    }
  }

  render() {
    const { children, onClick } = this.props;
    return (
      <p
        className={
          "atoms-p" + " " + this.state.class_name + " " + this.state.mode_class
        }
        onClick={onClick && onClick}
      >
        {children}
      </p>
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
export default P;
