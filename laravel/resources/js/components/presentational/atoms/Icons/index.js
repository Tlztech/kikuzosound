import React from "react";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class Icons extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      class_name: "",
      mode_class: "",
    };
  }

  componentDidMount() {
    this.setState({
      class_name: this.props.className
        ? "default-img" + " " + this.props.className
        : "default-img",
    });
  }

  render() {
    const { url, onClick, alt } = this.props;
    return (
      <img
        src={url}
        alt={alt}
        className={this.state.class_name}
        onClick={onClick && onClick}
      />
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
export default Icons;
