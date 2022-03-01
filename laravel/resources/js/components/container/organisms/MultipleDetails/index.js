import React from "react";

// Components
import Div from "../../../presentational/atoms/Div";
import Button from "../../../presentational/atoms/Button";
import Label from "../../../presentational/atoms/Label";
import DropdownLabel from "../../../presentational/molecules/DropdownWithLabel";
import InputWithSubmit from "../../../presentational/molecules/InputWithSubmit";

// css
import "./style.css";

// i18next
import i18next from "i18next";

//===================================================
// Component
//===================================================
class MultipleDetails extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      selectedAnswer: null,
    };
  }

  render() {
    const {
      quizTitle,
      quiz_number,
      quizItem,
      timeLimit,
      submittedMultipleAnswers,
      onOpenMultipleChoice,
      t,
    } = this.props;
    const { selectedAnswer, writtenAnswer } = this.state;
    const uniqueLibraryItems = quizItem.stetho_sounds && [
      ...new Set(quizItem.stetho_sounds.map((item) => item.lib_type)),
    ];
    const selectedLanguage = i18next.language;
    const quizChoices =
      quizItem.quiz_choices &&
      quizItem.quiz_choices.filter((item) => item.lib_type === null);
    let finalQuizChoices = [{ id: "", value: "what_is_your_diagonis" }];
    quizChoices &&
      quizChoices.forEach((element) => {
        finalQuizChoices = [
          ...finalQuizChoices,
          { value: element.title, value_en: element.title_en, ...element },
        ];
      });
    return (
      <Div className="organisms-multiple-details-container">
        <Label className="multipleDetails-quizTitle">{quizTitle}</Label>
        <Div className="organisms-multipleDetails-wrapper">
          <Label className="organism-quizNumber">{quiz_number}</Label>
          <Div className="multipleDetails-options-container">
            {timeLimit > 0 && (
              <Div className="organism-sound-timer">
                <Label className="organism-timer-text">{timeLimit}</Label>
              </Div>
            )}
            <Label className="organisms-quiz-question">
              {selectedLanguage === "ja"
                ? quizItem.question
                : quizItem.question_en}
            </Label>
            <Div className="multipleDetails-options">
              {uniqueLibraryItems &&
                uniqueLibraryItems.map((item, index) => {
                  const selectedLibraryAnswer =
                    submittedMultipleAnswers &&
                    submittedMultipleAnswers.find(
                      (answer) => answer.libItem === getLibraryType(item)
                    );
                  const libraryAnswer =
                    selectedLibraryAnswer &&
                    quizItem.quiz_choices.find(
                      (item) => item.id == selectedLibraryAnswer.answer
                    );
                  return (
                    <Div key={index} className="options-button-label">
                      <Button
                        key={index}
                        mode="quiz-options"
                        onClick={() => onOpenMultipleChoice(item)}
                      >
                        {t(getLibraryType(item))}
                      </Button>
                      <Label className="options-label">
                        {quizItem.is_optional == 1
                          ? libraryAnswer
                            ? i18next.language == "ja"
                              ? libraryAnswer.title
                              : libraryAnswer.title_en
                            : ""
                          : selectedLibraryAnswer
                          ? selectedLibraryAnswer.answer
                          : ""}
                      </Label>
                    </Div>
                  );
                })}
            </Div>
          </Div>
          {quizItem.is_optional == 1 ? (
            <Div className="organisms-multipleDetails-dropdown">
              <DropdownLabel
                label={t("diagnosis")}
                dropdown_items={finalQuizChoices}
                value={parseInt(selectedAnswer) || finalQuizChoices[0].id}
                onChange={(event) => handleDropdownItemChange(event, this)}
                onSubmitClicked={() => handleSubmitClicked(this, true)}
              />
            </Div>
          ) : (
            <InputWithSubmit
              name={"writtenAnswer"}
              value={writtenAnswer || ""}
              onChange={(e) => handleTextChange(e, this)}
              onClick={() => handleSubmitClicked(this, false)}
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

/**
 * Get library item name
 * @param {*} libType
 */
const getLibraryType = (libType) => {
  switch (libType) {
    case 1:
      return "ausculaide";
    case 2:
      return "palpation";
    case 3:
      return "ecg";
    case 4:
      return "inspection";
    case 5:
      return "xray";
    case 6:
      return "ucg";
    case 0:
      return "stetho";
    default:
      return "";
  }
};

/**
 * Get selected dropdown item
 * @param {*} event
 * @param {*} context
 */
const handleDropdownItemChange = (event, context) => {
  context.setState({ selectedAnswer: event.target.value });
};

/**
 * Submit multiple quiz answer
 * @param {*} context
 * @param {*} isOptional
 */
const handleSubmitClicked = (context, isOptional) => {
  const { selectedAnswer, writtenAnswer } = context.state;
  const { quizItem } = context.props;
  // if (!selectedAnswer) {
  //   return;
  // }
  const selectedAnswerItem =
    quizItem.quiz_choices &&
    quizItem.quiz_choices.find((item) => item.id == selectedAnswer);
  context.props.handleFinishMultipleQuiz(
    isOptional
      ? selectedAnswerItem
        ? selectedAnswerItem
        : null
      : { title: writtenAnswer, title_en: writtenAnswer }
  );
};

/**
 * Get written answer
 * @param {*} e
 * @param {*} context
 */
const handleTextChange = (e, context) => {
  context.setState({ writtenAnswer: e.target.value });
};
//===================================================
// actions
//===================================================

//===================================================
// redux
//===================================================

//===================================================
// export
//===================================================
export default MultipleDetails;
