import React from "react";

import Div from "../../atoms/Div/index";
import Button from "../../atoms/Button/index";
// css
import "./style.css";

//===================================================
// Component
//===================================================
class BreadButtons extends React.Component {
  constructor(props) {
    super(props);
  }

  componentDidMount() {}

  render() {
    const { onEditClicked, onDeleteClicked } = this.props;
    return (
      <Div className="molecules-BreadButtons-wrapper">
        <Button mode="active" onClick={onEditClicked && onEditClicked}>
          Edit
        </Button>
        <Button mode="danger" onClick={onDeleteClicked}>
          Delete
        </Button>
      </Div>
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
export default BreadButtons;
