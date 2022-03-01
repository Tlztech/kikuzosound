import React from "react";

// Components
import Div from "../../../presentational/atoms/Div";
import Label from "../../../presentational/atoms/Label";
import QuizOptionButtons from "../../../presentational/molecules/QuizOptionButtons";
import SoundWithLabel from "../../../presentational/molecules/SoundWithLabel";
import InputWithSubmit from "../../../presentational/molecules/InputWithSubmit";

// css
import "./style.css";

// i18next
import i18next from "i18next";

//===================================================
// Component
//===================================================
class SoundOptionPlayer extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      writtenAnswer: "",
    };
  }

  componentDidUpdate(prevProps) {
    if (prevProps.quiz_number !== this.props.quiz_number) {
      this.setState({ writtenAnswer: "" });
    }
  }

  render() {
    const { quizTitle, quiz_number, quizItem, timeLimit } = this.props;
    const { writtenAnswer } = this.state;
    const selectedLanguage = i18next.language;
    if (!quizItem) {
      return null;
    }
    return (
      <Div className="organism-soundOptionPlayer-wrapper">
        <Label className="organism-quizTitle">{quizTitle}</Label>
        <Div className="organism-soundOption-wrapper">
          <Label className="organism-quizNumber">{quiz_number}</Label>
          <Div className="organism-soundOptionPlayer-container">
            <Label className="organism-quiz-question-text">
              <Div
                dangerouslySetInnerHTML={{
                  __html:
                    selectedLanguage === "ja"
                      ? quizItem.question
                      : quizItem.question_en,
                }}
              />
            </Label>
            {timeLimit > 0 && (
              <Div className="organism-sound-timer">
                <Label className="organism-timer-text">{timeLimit}</Label>
              </Div>
            )}
            <Div className="organism-soundOptionPlayer-options">
              {quizItem.stetho_sounds.map((item, index) => (
                <SoundWithLabel
                  key={index}
                  label={selectedLanguage === "ja" ? item.title : item.title_en}
                  file={item.sound_path}
                />
              ))}
            </Div>
          </Div>
          {quizItem.is_optional == 1 ? (
            <QuizOptionButtons
              soundOptions={quizItem.quiz_choices.filter(
                (item) => item.is_fill_in === null
              )}
              onClick={(item) => this.props.onButtonClicked(item)}
              onFinishClick={() => this.props.onFinishClick()}
            />
          ) : (
            <InputWithSubmit
              name="writtenAnswer"
              value={writtenAnswer}
              onChange={(e) => handleTextChange(e, this)}
              onClick={() =>
                this.props.onButtonClicked({
                  title: writtenAnswer,
                  title_en: writtenAnswer,
                })
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
export default SoundOptionPlayer;
