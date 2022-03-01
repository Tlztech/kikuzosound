import React from "react";

// components
import Div from "../../atoms/Div/index";

// style
import "./style.css";

// i18next
import i18next from "i18next";

//===================================================
// Component
//===================================================
class QuizOptionTextBox extends React.Component {
  constructor(props) {
    super(props);
  }
  render() {
    const { soundOptions } = this.props;
    return (
      <Div className="molecules-quizOptionTextBox-wrapper">
        {soundOptions.map((item, index) => (
          <Div
            key={index}
            className={
              item.is_correct
                ? "molecules-text-wrapper molecules-quiz-options-selected"
                : "molecules-text-wrapper molecules-quiz-options"
            }
          >
            {i18next.language === "ja"
              ? item.title || "-"
              : item.title_en || "-"}
          </Div>
        ))}
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
export default QuizOptionTextBox;
