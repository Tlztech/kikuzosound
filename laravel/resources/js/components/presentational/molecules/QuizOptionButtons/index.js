import React from "react";

// components
import Div from "../../atoms/Div/index";
import Button from "../../atoms/Button";

// style
import "./style.css";

// i18next
import i18next from "i18next";
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class QuizOptionButtons extends React.Component {
  constructor(props) {
    super(props);
  }
  render() {
    const {
      soundOptions,
      onClick,
      onFinishClick,
      isExplanation = false,
      t,
    } = this.props;
    return (
      <Div className="molecules-quizOptionButtons-wrapper">
        {soundOptions.map((item, index) => (
          <Button
            key={index}
            mode={
              isExplanation && item.is_correct
                ? "quiz-options-selected"
                : "quiz-options"
            }
            onClick={() => !isExplanation && onClick(item)}
          >
            {i18next.language === "ja"
              ? item.title || "-"
              : item.title_en || "-"}
          </Button>
        ))}
        {!isExplanation && (
          <Button
            mode={"quiz-options"}
            onClick={onFinishClick && onFinishClick}
          >
            {t("see_answers_and_finish")}
          </Button>
        )}
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
export default withTranslation("translation")(QuizOptionButtons);
