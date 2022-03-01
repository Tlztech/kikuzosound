import React from "react";

// Components
import Div from "../../../presentational/atoms/Div";
import Button from "../../../presentational/atoms/Button";
import Label from "../../../presentational/atoms/Label";
import FinalAnswerText from "../../../presentational/molecules/FinalAnswerText";
import QuizMultipleExplanationOrganism from "../../organisms/QuizMultipleExplanation";
import Image from "../../../presentational/atoms/Image";

// css
import "./style.css";

// i18next
import { withTranslation } from "react-i18next";
import i18next from "i18next";

//===================================================
// Component
//===================================================
class QuizExplanation extends React.Component {
  render() {
    const {
      quizPack,
      quizItem,
      quizNumber,
      selectedAnswer,
      handleGoBack,
      t,
    } = this.props;
    if (!quizItem) {
      return null;
    }
    const uniqueLibraryItems = quizItem.stetho_sounds && [
      ...new Set(quizItem.stetho_sounds.map((item) => item.lib_type)),
    ];
    const finalAnswer =
      quizItem.quiz_choices &&
      quizItem.quiz_choices.find(
        (item) => item.lib_type == null && item.is_correct == 1
      );
    return (
      <Div className="template-quizMultipleExplanation-wrapper">
        <Label className="template-quizTitle">{quizPack.title}</Label>
        <Div className="template-soundOption-wrapper">
          <Div className="template-button-title-wrapper">
            <Button mode="cancel" onClick={() => handleGoBack()}>
              {t("back_btn")}
            </Button>
            <Label>
              {i18next.language === "ja"
                ? `${quizNumber}${t("question")}`
                : `${t("question")}${quizNumber}`}
            </Label>
          </Div>
          {quizItem.mode === "simple" && quizItem.image_path && (
            <Div className="template-explain-image-wrapper">
              <Image
                url={`${quizItem.image_path}`}
              />
            </Div>
          )}
          {uniqueLibraryItems &&
            uniqueLibraryItems.map((item, index) => (
              <QuizMultipleExplanationOrganism
                key={index}
                libType={item}
                quizItem={quizItem}
                selectedAnswer={selectedAnswer}
              />
            ))}
          {quizItem.mode === "multiple" && (
            <FinalAnswerText
              isFinalAnswer={true}
              correctAnswer={
                finalAnswer
                  ? i18next.language === "ja"
                    ? finalAnswer.title
                    : finalAnswer.title_en
                  : ""
              }
              myAnswer={
                selectedAnswer.item
                  ? i18next.language === "ja"
                    ? selectedAnswer.item.title
                    : selectedAnswer.item.title_en
                  : ""
              }
            />
          )}
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
export default withTranslation("translation")(QuizExplanation);
