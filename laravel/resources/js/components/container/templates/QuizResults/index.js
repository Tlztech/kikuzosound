import React from "react";

// components
import Div from "../../../presentational/atoms/Div";
import Label from "../../../presentational/atoms/Label";
import QuizResult from "../../organisms/ResultWithExplanationButton";

//css
import "./style.css";

//===================================================
// Component
//===================================================
class QuizResultsTemplate extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { history } = this.props;
    if (!history) {
      return null;
    }
    const {
      quizPack,
      totalCorrectAnswers,
      totalQuestions,
    } = history.location.state;
    if (!quizPack) {
      return null;
    }
    return (
      <Div className="template-QuizResults-wrapper">
        <Label className="organism-quizTitle">{quizPack.title}</Label>
        <QuizResult
          totalQuestions={totalQuestions}
          answer={`${totalCorrectAnswers}/${totalQuestions.length} The right answer`}
          onExplanationClicked={(index) =>
            handleExplanationClicked(this, index)
          }
          onFinishClicked={() => handleFinishClicked(this)}
        />
      </Div>
    );
  }
}
//===================================================
// Functions
//===================================================
/**
 * Close window
 * @param {*} context
 */
const handleFinishClicked = (context) => {
  window.close();
};

/**
 * Open answer explanation
 * @param {*} context
 * @param {*} index
 */
const handleExplanationClicked = (context, index) => {
  const { history } = context.props;
  const { quizPack, totalQuestions } = history.location.state;
  history.push("quiz-explanation", {
    quizPack: quizPack,
    quizItem: quizPack.quizzes[index][0],
    quizNumber: index + 1,
    selectedAnswer: totalQuestions[index],
  });
};

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default QuizResultsTemplate;
