import React from "react";

// bootstrap
import { Form } from "react-bootstrap";

//===================================================
// Component
//===================================================
class ToggleBar extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      checked: true,
    };
  }

  render() {
    const { title } = this.props;
    return (
      <>
        <Form.Check
          type="switch"
          id="custom-switch"
          label=""
          checked={this.state.checked}
          onChange={(event) => handleChange(event, this)}
          name="checked"
        />
      </>
    );
  }
}

//===================================================
// Functions
//===================================================
/**
 * Change state
 * @param {*} event
 * @param {*} context
 */
const handleChange = (event, context) => {
  context.setState({ [event.target.name]: event.target.checked });
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
export default ToggleBar;
