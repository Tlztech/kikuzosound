import React from "react";

// Components
import Div from "../../atoms/Div";
import P from "../../atoms/P";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class DateWithLabel extends React.Component {
  render() {
    const { title, value } = this.props;
    return (
      <Div className="molecules-dateWithLabel-wrapper">
        <P>{title}</P>
        <Div className="molecules-TitleBoxIcon-wrapper">
          <P className="molecules-rangeValue">{value}</P>
        </Div>
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
export default DateWithLabel;
