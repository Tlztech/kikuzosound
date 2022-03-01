import React from "react";

// // Components
import Div from "../../../presentational/atoms/Div/index";
import Label from "../../../presentational/atoms/Label/index";
import AnswerWithLabel from "../../../presentational/molecules/FinalAnswerText/index";

// styles
import "./style.css";

//===================================================
// Component
//===================================================
class AnswerWihExplain extends React.Component {
  render() {
    const { correctAnswer, myAnswer, label } = this.props;
    return (
      <Div>
        <AnswerWithLabel correctAnswer={correctAnswer} myAnswer={myAnswer} />
        <Label>{label}</Label>
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
export default AnswerWihExplain;
