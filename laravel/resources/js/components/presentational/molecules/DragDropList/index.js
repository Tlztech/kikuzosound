import React from "react";

// Components
import Div from "../../atoms/Div";
import Label from "../../atoms/Label";
import Button from "../../atoms/Button";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class DragDropList extends React.Component {
  render() {
    const { title, onClick } = this.props;
    return (
      <Div className="molecules-dragDrop-list-container">
        <Label>{title}</Label>
        {/* <Button onClick={onClick && onClick} mode="preview">
          Preview
        </Button> */}
      </Div>
    );
  }
}

//===================================================
// functions
//===================================================

//===================================================
// actions
//===================================================

//===================================================
// redux
//===================================================

//===================================================
// export
//===================================================
export default DragDropList;
