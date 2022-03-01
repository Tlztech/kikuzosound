import React from "react";

// Components
import Div from "../../atoms/Div";
import Label from "../../atoms/Label";
import P from "../../atoms/P";

//css
import "./style.css";

//===================================================
// Component
//===================================================
class HomeList extends React.Component {
  render() {
    const { title, description, isLastItem } = this.props;
    return (
      <Div
        className={
          !isLastItem
            ? "molecules-homeList-wrapper"
            : "molecules-homeList-wrapper molecules-homeList-noBorder"
        }
      >
        <Label>{title}</Label>
        <P>{description}</P>
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
export default HomeList;
