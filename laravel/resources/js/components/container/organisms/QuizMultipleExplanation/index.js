import React from "react";

// Components
import Div from "../../../presentational/atoms/Div";
import P from "../../../presentational/atoms/P";
import Label from "../../../presentational/atoms/Label";
import MediaComponent from "../../../presentational/molecules/Media";
import FinalAnswerText from "../../../presentational/molecules/FinalAnswerText";
import ImageSlider from "../../../presentational/molecules/ImageSlider";
import QuizOptionTextBox from "../../../presentational/molecules/QuizOptionTextBox";

// css
import "./style.css";

// i18next
import { withTranslation } from "react-i18next";
import i18next from "i18next";

//===================================================
// Component
//===================================================
class QuizMultipleExplanation extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      selectedImageIndex: 0,
    };
  }

  render() {
    const { libType, quizItem, selectedAnswer, t } = this.props;
    const { selectedImageIndex } = this.state;
    const libraryItemsList =
      quizItem.stetho_sounds &&
      quizItem.stetho_sounds.filter((item) => item.lib_type == libType);
    const quizChoices =
      quizItem.quiz_choices &&
      quizItem.quiz_choices.filter(
        (item) => item.lib_type == libType && item.is_fill_in == null
      );
    const rightAnswer =
      quizItem.quiz_choices &&
      quizItem.quiz_choices.find(
        (item) => item.lib_type == libType && item.is_correct == 1
      );
    let userAnswer = null;
    if (selectedAnswer.item) {
      if (
        quizItem.mode == "multiple" &&
        selectedAnswer.item.submittedMultipleAnswers
      ) {
        const obtainedAnswer = selectedAnswer.item.submittedMultipleAnswers.find(
          (el) => el.libItem == getLibraryType(libType)
        );
        if (quizItem.is_optional == 1) {
          userAnswer =
            quizItem.quiz_choices &&
            quizItem.quiz_choices.find(
              (item) => item.id == obtainedAnswer.answer
            );
        } else {
          userAnswer = obtainedAnswer;
        }
      } else {
        userAnswer = selectedAnswer.item;
      }
    }
    return (
      <Div className="organism-quizMultipleExplanation-wrapper">
        {quizItem.mode === "multiple" && (
          <Label className="organism-multipleExplain-title">
            {t(getLibraryType(libType))}
          </Label>
        )}
        <Div className="organism-quizMultipleExplanation-container">
          {quizItem.mode === "simple" && (
            <Label>
              {i18next.language === "ja"
                ? quizItem.question
                : quizItem.question_en}
            </Label>
          )}
          <Div className="organism-quizOption-container">
            {libraryItemsList && libType == 1 && (
              <ImageSlider
                url={`${libraryItemsList[selectedImageIndex].body_image}`}
                totalImages={libraryItemsList.length}
                selectedIndex={selectedImageIndex}
                onNextIconClicked={() => handleNextIconClicked(this)}
                onPreviousIconClicked={() => handlePreviousIconClicked(this)}
              />
            )}
            {libraryItemsList &&
              libType == 0 &&
              libraryItemsList.map((libItem, index) => (
                <Div key={index} className="organism-sound-video-container">
                  <MediaComponent file={libItem.sound_path} type="sound" />
                </Div>
              ))}
            {libraryItemsList &&
              libType == 2 &&
              libraryItemsList.map((libItem, index) => (
                <Div key={index} className="organism-sound-video-container">
                  <MediaComponent file={libItem.sound_path} type="sound" />
                  <MediaComponent
                    file={libItem.video_path}
                    type="video"
                    width={300}
                    height={150}
                  />
                </Div>
              ))}
            {libraryItemsList && libType == 3 && (
              <ImageSlider
                url={`${libraryItemsList[selectedImageIndex].body_image}`}
                totalImages={libraryItemsList.length}
                selectedIndex={selectedImageIndex}
                onNextIconClicked={() => handleNextIconClicked(this)}
                onPreviousIconClicked={() => handlePreviousIconClicked(this)}
              />
            )}
            {libraryItemsList &&
              libType == 4 &&
              libraryItemsList.map((libItem, index) => (
                <Div key={index} className="organism-sound-video-container">
                  <MediaComponent file={libItem.sound_path} type="sound" />
                  <MediaComponent
                    file={libItem.video_path}
                    type="video"
                    width={300}
                    height={150}
                  />
                </Div>
              ))}
            {libraryItemsList && libType == 5 && (
              <ImageSlider
                url={`${libraryItemsList[selectedImageIndex].body_image}`}
                totalImages={libraryItemsList.length}
                selectedIndex={selectedImageIndex}
                onNextIconClicked={() => handleNextIconClicked(this)}
                onPreviousIconClicked={() => handlePreviousIconClicked(this)}
              />
            )}
            {libraryItemsList &&
              libType == 6 &&
              libraryItemsList.map((libItem, index) => (
                <Div key={index} className="organism-sound-video-container">
                  <MediaComponent
                    file={libItem.video_path}
                    type="video"
                    width={300}
                    height={150}
                  />
                </Div>
              ))}
          </Div>
          {quizItem.is_optional == 1 && (
            <QuizOptionTextBox soundOptions={quizChoices} />
          )}
        </Div>
        {quizItem.is_optional == 1 ? (
          <FinalAnswerText
            correctAnswer={
              rightAnswer
                ? i18next.language === "ja"
                  ? rightAnswer.title
                  : rightAnswer.title_en
                : ""
            }
            myAnswer={
              userAnswer
                ? i18next.language === "ja"
                  ? userAnswer.title
                  : userAnswer.title_en
                : ""
            }
          />
        ) : (
          <FinalAnswerText
            correctAnswer={rightAnswer ? rightAnswer.title : ""}
            myAnswer={
              userAnswer
                ? quizItem.mode === "multiple"
                  ? userAnswer.answer
                  : userAnswer.title
                : ""
            }
          />
        )}
        <P>{libType.sub_description}</P>
      </Div>
    );
  }
}

//===================================================
// functions
//===================================================
/**
 * Open next image
 * @param {*} context
 */
const handleNextIconClicked = (context) => {
  const { libType, quizItem } = context.props;
  const { selectedImageIndex } = context.state;
  const libraryItemsList =
    quizItem.stetho_sounds &&
    quizItem.stetho_sounds.filter((item) => item.lib_type == libType);
  if (selectedImageIndex < libraryItemsList.length - 1) {
    context.setState({ selectedImageIndex: selectedImageIndex + 1 });
  }
};

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
 * Open previous image
 * @param {*} context
 */
const handlePreviousIconClicked = (context) => {
  const { selectedImageIndex } = context.state;
  if (selectedImageIndex > 0) {
    context.setState({ selectedImageIndex: selectedImageIndex - 1 });
  }
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
export default withTranslation("translation")(QuizMultipleExplanation);
