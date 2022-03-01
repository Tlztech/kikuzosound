import React from "react";

// Components
import Div from "../../../presentational/atoms/Div";
import P from "../../../presentational/atoms/P";
import Button from "../../../presentational/atoms/Button";
import Image from "../../../presentational/atoms/Image";
import ExplanationButton from "../../../presentational/molecules/ExplanationButton";

// images
import { LineOrange } from "../../../../assets";

// css
import "./style.css";

// i18next
import { withTranslation } from "react-i18next";
import i18next from "i18next";

//===================================================
// Component
//===================================================
class ResultWithExplanationButton extends React.Component {
  render() {
    const {
      answer,
      onExplanationClicked,
      onFinishClicked,
      totalQuestions,
      t,
    } = this.props;
    return (
      <Div className="organism-ResultWithExplanationButton-container text-center">
        <P className="organism-score-text">{t("score")}</P>
        <Image className="organism-orange-line" url={LineOrange} />
        <P className="organism-right-answer">
          {answer}&nbsp;{t("the_right_answer")}
        </P>
        <Image className="organism-orange-line" url={LineOrange} />
        <Div className="organism-ExplanationButton">
          {totalQuestions &&
            totalQuestions.map((item, index) => (
              <ExplanationButton
                key={index}
                title={
                  i18next.language === "ja"
                    ? `${index + 1}${t("question")}`
                    : `${t("question")}${index + 1}`
                }
                name={t("explanation")}
                isCorrect={item.isCorrect}
                onClick={() => onExplanationClicked(index)}
              />
            ))}
        </Div>
        <P className="organism-explanation-text">
          {t("click_explanation_button")}
        </P>
        <Button
          className="organism-explanation-button"
          mode="finish"
          onClick={onFinishClicked}
        >
          {t("finish")}
        </Button>
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
export default withTranslation("translation")(ResultWithExplanationButton);
