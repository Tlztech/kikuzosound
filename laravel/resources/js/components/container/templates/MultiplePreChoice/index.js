import React from "react";

// Components
import Div from "../../../presentational/atoms/Div";
import MultiplePreChoice from "../../organisms/MultiplePreChoice";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class MultiplePreChoiceTemplate extends React.Component {
  render() {
    const { quizTitle, quiz_number, timeLimit, quizItem } = this.props;
    return (
      <Div className="templates-MultiplePreChoice-wrapper">
        <MultiplePreChoice
          quizTitle={quizTitle}
          quiz_number={quiz_number}
          timeLimit={timeLimit}
          quizItem={quizItem}
        />
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
export default MultiplePreChoiceTemplate;
