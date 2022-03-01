import React from "react";

import Div from "../../atoms/Div/index";
import Button from "../../atoms/Button/index";
// css
import "./style.css";

//===================================================
// Component
//===================================================
class AddEditButton extends React.Component {
  constructor(props) {
    super(props);
  }

  componentDidMount() {}

  render() {
    const { onEditClicked, onDeleteClicked } = this.props;
    return (
      <Div className="molecules-AddEditButton-wrapper">
        <Button mode="edit" onClick={onEditClicked && onEditClicked}>
          Edit
        </Button>
        <Button mode="addTo" onClick={onDeleteClicked}>
          Add To
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
export default AddEditButton;
