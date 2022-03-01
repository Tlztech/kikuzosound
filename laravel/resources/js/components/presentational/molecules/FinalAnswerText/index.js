import React from "react";

// Components
import Div from "../../atoms/Div";
import Label from "../../atoms/Label";

// css
import "./style.css";

// i18next
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class FinalAnswerText extends React.Component {
  render() {
    const { correctAnswer, myAnswer, isFinalAnswer, t } = this.props;
    return (
      <Div className="molecules-rightAnswer-container">
        <Div className="molecules-rightAnswer">
          <Label>
            {isFinalAnswer
              ? t("the_final_right_answer")
              : t("the_right_answer")}
            :&nbsp;
            <Label className="molecules-rightAnswer-description">
              {correctAnswer}
            </Label>
          </Label>
        </Div>
        <Div>
          <Label>
            {t("your_answer")}:&nbsp;
            <Label className="molecules-rightAnswer-description">
              {myAnswer}
            </Label>
          </Label>
        </Div>
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
export default withTranslation("translation")(FinalAnswerText);
