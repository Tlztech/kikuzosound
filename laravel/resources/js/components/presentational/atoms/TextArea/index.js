import React from "react";

// libs
import { Form } from "react-bootstrap";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class TextArea extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      class_name: ""
    };
  }

  componentDidMount() {
    this.setState({ class_name: this.props.className || "" });
  }

  render() {
    return (
      <Form.Control
        as="textarea"
        rows="3"
        className={"atoms-textarea" + " " + this.state.class_name}
        placeholder={this.props.placeholder}
        style={{ minHeight: 35 }}
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
export default TextArea;