import React from "react";
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

// libs
import { Modal } from "react-bootstrap";

// components
import Div from "../../../presentational/atoms/Div";
import Label from "../../../presentational/atoms/Label";
import SoundOptionPlayer from "../../organisms/SoundOptionPlayer";
import MultiplePreChoice from "../../organisms/MultiplePreChoice";
import MultipleDetails from "../../organisms/MultipleDetails";
import QuizStartScreen from "../../organisms/QuizStartScreen";
import QuizResult from "../../organisms/ResultWithExplanationButton";
import QuizExplanation from "../QuizExplanation";

// redux
import { getSingleQuizPack } from "../../../../redux/modules/actions/QuizPackAction";

//i18n
import { withTranslation } from "react-i18next";
import i18next from "i18next";

//css
import "./style.css";

//===================================================
// Component
//===================================================
class ChoiceSelection extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      currentQuizIndex: 0,
      timeLimit: 0,
      totalQuestions: [],
      totalCorrectAnswers: 0,
      isMultipleQuizCompleted: false,
      submittedMultipleAnswers: null,
      selectedLibrary: null,
      isOpenFromDetail: false,
      multipleQuizState: null,
      isTimerCompleted: false,
      isStart: true,
      isResultScreenVisible: false,
      isQuizChoiceVisible: false,
      explanationIndex: 0,
    };
  }

  componentDidMount() {
    handleFetchQuizPack(this);
  }

  componentWillUnmount() {
    if (this.quizTimer) {
      clearInterval(this.quizTimer);
    }
  }

  render() {
    const { singleQuizPack, t, onHideQuizPreviewModal, isVisible } = this.props;
    const {
      currentQuizIndex,
      timeLimit,
      isMultipleQuizCompleted,
      submittedMultipleAnswers,
      selectedLibrary,
      isOpenFromDetail,
      multipleQuizState,
      isTimerCompleted,
      isStart,
      isResultScreenVisible,
      isQuizChoiceVisible,
      totalQuestions,
      totalCorrectAnswers,
      explanationIndex,
    } = this.state;
    let totalQuizzes = 0;
    let selectedQuiz = null;
    if (singleQuizPack) {
      totalQuizzes = singleQuizPack.quizzes ? singleQuizPack.quizzes.length : 0;
      selectedQuiz =
        singleQuizPack.quizzes && singleQuizPack.quizzes[currentQuizIndex][0];
    }
    return (
      <Modal
        className="template-quizPreview-wrapper"
        show={isVisible}
        onHide={() => onHideQuizPreviewModal()}
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
        size="lg"
      >
        {singleQuizPack ? (
          <Modal.Body>
            {isStart ? (
              <QuizStartScreen
                quizTitle={singleQuizPack.title}
                onStartClicked={() => handleStartQuiz(this)}
                onCloseClicked={() => onHideQuizPreviewModal()}
              />
            ) : isQuizChoiceVisible ? (
              <Div className="template-choiceSelection-wrapper">
                {selectedQuiz.mode === "simple" ? (
                  <SoundOptionPlayer
                    quizTitle={singleQuizPack.title}
                    quiz_number={currentQuizIndex + 1 + "/" + totalQuizzes}
                    quizItem={selectedQuiz}
                    timeLimit={timeLimit}
                    onButtonClicked={(item) =>
                      handleCheckSimpleAnswer(this, item)
                    }
                    onFinishClick={() => handleFinishClicked(this)}
                  />
                ) : !isMultipleQuizCompleted ? (
                  <MultiplePreChoice
                    t={t}
                    selectedLibrary={selectedLibrary}
                    quizTitle={singleQuizPack.title}
                    quiz_number={currentQuizIndex + 1 + "/" + totalQuizzes}
                    quizItem={selectedQuiz}
                    timeLimit={timeLimit}
                    handleAllMultipleAnswersSubmitted={(
                      submittedAnswers,
                      multipleQuizState
                    ) =>
                      handleMultipleDetails(
                        this,
                        submittedAnswers,
                        multipleQuizState
                      )
                    }
                    isOpenFromDetail={isOpenFromDetail}
                    handleSetMultipleQuizFalse={() =>
                      handleSetMultipleQuizFalse(this)
                    }
                    multipleQuizState={multipleQuizState}
                    isTimerCompleted={isTimerCompleted}
                  />
                ) : (
                  <MultipleDetails
                    t={t}
                    quizTitle={singleQuizPack.title}
                    quiz_number={currentQuizIndex + 1 + "/" + totalQuizzes}
                    quizItem={selectedQuiz}
                    timeLimit={timeLimit}
                    submittedMultipleAnswers={submittedMultipleAnswers}
                    onOpenMultipleChoice={(libType) =>
                      handleOpenMultipleQuiz(this, libType)
                    }
                    handleFinishMultipleQuiz={(item) =>
                      handleCheckMultipleAnswer(this, item)
                    }
                  />
                )}
              </Div>
            ) : isResultScreenVisible ? (
              <Div className="template-QuizResults-wrapper">
                <Label className="organism-quizTitle">
                  {singleQuizPack.title}
                </Label>
                <QuizResult
                  totalQuestions={totalQuestions}
                  answer={`${totalCorrectAnswers}/${totalQuestions.length}`}
                  onExplanationClicked={(index) =>
                    handleExplanationClicked(this, index)
                  }
                  onFinishClicked={() => onHideQuizPreviewModal()}
                />
              </Div>
            ) : (
              <QuizExplanation
                quizPack={singleQuizPack}
                quizItem={singleQuizPack.quizzes[explanationIndex][0]}
                quizNumber={explanationIndex + 1}
                selectedAnswer={totalQuestions[explanationIndex]}
                handleGoBack={() => handleGoToResults(this)}
              />
            )}
          </Modal.Body>
        ) : (
          <Modal.Body>
            <QuizStartScreen quizTitle={""} />
          </Modal.Body>
        )}
      </Modal>
    );
  }
}

//===================================================
// Functions
//===================================================
/**
 * Fetch quizpacks
 * @param {*} context
 */
const handleFetchQuizPack = async (context) => {
  const { userToken, quizPackId } = context.props;
  await context.props.getSingleQuizPack(
    userToken,
    quizPackId,
    i18next.language
  );
};

/**
 * Start Quiz
 * @param {*} context
 */
const handleStartQuiz = (context) => {
  const { singleQuizPack } = context.props;
  const timeLimit = singleQuizPack.quizzes[0][0].limit_seconds;
  context.setState(
    {
      isStart: false,
      isResultScreenVisible: false,
      isQuizChoiceVisible: true,
      timeLimit: timeLimit,
    },
    () => handleRunTimer(context)
  );
};

/**
 * Check simple quiz answer
 * then go to next quiz
 * @param {*} context
 * @param {*} item
 */
const handleCheckSimpleAnswer = (context, item) => {
  if (context.quizTimer) {
    clearInterval(context.quizTimer);
  }
  const {
    totalCorrectAnswers,
    currentQuizIndex,
    totalQuestions,
  } = context.state;
  if (item.is_correct) {
    context.setState(
      {
        isMultipleQuizCompleted: false,
        totalCorrectAnswers: totalCorrectAnswers + 1,
        totalQuestions: [
          ...totalQuestions,
          { id: currentQuizIndex + 1, isCorrect: true, item: item },
        ],
      },
      () => handleOpenNextQuiz(context)
    );
  } else {
    context.setState(
      {
        isMultipleQuizCompleted: false,
        totalQuestions: [
          ...totalQuestions,
          { id: currentQuizIndex + 1, isCorrect: false, item: item },
        ],
      },
      () => handleOpenNextQuiz(context)
    );
  }
};

/**
 * increase quiz index
 * @param {*} context
 */
const handleOpenNextQuiz = (context) => {
  const { currentQuizIndex, totalQuestions } = context.state;
  const { singleQuizPack } = context.props;
  if (currentQuizIndex < singleQuizPack.quizzes.length - 1) {
    context.setState(
      {
        currentQuizIndex: currentQuizIndex + 1,
        timeLimit:
          singleQuizPack.quizzes[currentQuizIndex + 1][0].limit_seconds,
      },
      () => handleRunTimer(context)
    );
  } else {
    // open results
    handleQuizCompleted(context);
  }
};

/**
 * Complete quizzes
 * @param {*} context
 */
const handleQuizCompleted = (context) => {
  context.setState({ isResultScreenVisible: true, isQuizChoiceVisible: false });
};

/**
 * Finish quiz
 * @param {*} context
 */
const handleFinishClicked = (context) => {
  const { currentQuizIndex, totalQuestions } = context.state;
  context.setState(
    {
      totalQuestions: [
        ...totalQuestions,
        {
          id: currentQuizIndex + 1,
          isCorrect: false,
          item: { is_correct: false },
        },
      ],
    },
    () => handleQuizCompleted(context)
  );
};

/**
 * Set timer for quiz
 * @param {*} context
 */
const handleRunTimer = (context) => {
  const { timeLimit } = context.state;
  if (timeLimit > 0) {
    context.quizTimer = setInterval(() => {
      const { timeLimit } = context.state;
      if (timeLimit > 1) {
        context.setState({ timeLimit: timeLimit - 1 });
      } else {
        clearInterval(context.quizTimer);
        context.setState({ isTimerCompleted: true });
        handleCheckSimpleAnswer(context, { is_correct: false });
      }
    }, 1000);
  }
};

/**
 * Open multiple quiz details
 * @param {*} context
 * @param {*} submittedAnswers
 * @param {*} multipleQuizState
 */
const handleMultipleDetails = (
  context,
  submittedAnswers,
  multipleQuizState
) => {
  context.setState({
    submittedMultipleAnswers: submittedAnswers,
    isMultipleQuizCompleted: true,
    multipleQuizState: multipleQuizState,
  });
};

/**
 * Check multiple quiz answers
 * @param {*} context
 * @param {*} item
 */
const handleCheckMultipleAnswer = (context, item) => {
  const { submittedMultipleAnswers } = context.state;
  context.setState({ isMultipleQuizCompleted: false });
  handleCheckSimpleAnswer(
    context,
    item
      ? { ...item, submittedMultipleAnswers }
      : { is_correct: false, submittedMultipleAnswers }
  );
};

/**
 * Open Multiple Quiz
 * @param {*} context
 * @param {*} libType
 */
const handleOpenMultipleQuiz = (context, libType) => {
  context.setState({
    isMultipleQuizCompleted: false,
    selectedLibrary: libType,
    isOpenFromDetail: true,
  });
};

/**
 * Set is open from detail false
 * @param {*} context
 */
const handleSetMultipleQuizFalse = (context) => {
  context.setState({ isOpenFromDetail: false, isTimerCompleted: false });
};

// Results Screen
/**
 * Open answer explanation
 * @param {*} context
 * @param {*} index
 */
const handleExplanationClicked = (context, index) => {
  context.setState({
    explanationIndex: index,
    isStart: false,
    isQuizChoiceVisible: false,
    isResultScreenVisible: false,
  });
};

// Explanation
/**
 * Go back to results screen
 * @param {*} context
 */
const handleGoToResults = (context) => {
  context.setState({
    explanationIndex: 0,
    isStart: false,
    isQuizChoiceVisible: false,
    isResultScreenVisible: true,
  });
};

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================
const mapStateToProps = (state) => {
  return {
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    singleQuizPack: state.quizPackList.singleQuizPack,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, {
  getSingleQuizPack,
})(withTranslation("translation")(withRouter(ChoiceSelection)));
