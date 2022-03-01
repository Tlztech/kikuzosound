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
import { getSingleQuiz } from "../../../../redux/modules/actions/QuizAction";

//i18n
import { withTranslation } from "react-i18next";
import i18next from "i18next";

//css
import "./style.css";

//===================================================
// Component
//===================================================
class QuizPreview extends React.Component {
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
    handleFetchQuiz(this);
  }

  componentWillUnmount() {
    if (this.quizTimer) {
      clearInterval(this.quizTimer);
    }
  }

  render() {
    const { singleQuiz, t, onHideQuizPreviewModal, isVisible } = this.props;
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
    const singleQuizPack = JSON.parse(JSON.stringify(singleQuiz));
    if (singleQuizPack) {
      totalQuizzes = singleQuizPack.quiz ? singleQuizPack.quiz.length : 0;
      selectedQuiz =
        singleQuizPack.quiz && singleQuizPack.quiz[currentQuizIndex];
      selectedQuiz = {
        ...selectedQuiz,
        quiz_type: singleQuizPack.quiz_type,
        mode: singleQuizPack.mode,
      };
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
                quizTitle={
                  i18next.language == "ja"
                    ? selectedQuiz.title
                    : selectedQuiz.title_en
                }
                onStartClicked={() => handleStartQuiz(this)}
                onCloseClicked={() => onHideQuizPreviewModal()}
              />
            ) : isQuizChoiceVisible ? (
              <Div className="template-choiceSelection-wrapper">
                {selectedQuiz.mode === "simple" ? (
                  <SoundOptionPlayer
                    quizTitle={
                      i18next.language == "ja"
                        ? selectedQuiz.title
                        : selectedQuiz.title_en
                    }
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
                    quizTitle={
                      i18next.language == "ja"
                        ? selectedQuiz.title
                        : selectedQuiz.title_en
                    }
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
                    quizTitle={
                      i18next.language == "ja"
                        ? selectedQuiz.title
                        : selectedQuiz.title_en
                    }
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
                  {i18next.language == "ja"
                    ? selectedQuiz.title
                    : selectedQuiz.title_en}
                </Label>
                <QuizResult
                  totalQuestions={totalQuestions}
                  answer={`${totalCorrectAnswers}/${totalQuestions.length} The right answer`}
                  onExplanationClicked={(index) =>
                    handleExplanationClicked(this, index)
                  }
                  onFinishClicked={() => onHideQuizPreviewModal()}
                />
              </Div>
            ) : (
              <QuizExplanation
                quizPack={{
                  ...singleQuizPack,
                  title:
                    i18next.language == "ja"
                      ? selectedQuiz.title
                      : selectedQuiz.title_en,
                }}
                quizItem={{
                  ...singleQuizPack.quiz[explanationIndex],
                  quiz_type: singleQuizPack.quiz_type,
                  mode: singleQuizPack.mode,
                }}
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
const handleFetchQuiz = async (context) => {
  const { userToken, quizId } = context.props;
  await context.props.getSingleQuiz(userToken, quizId);
};

/**
 * Start Quiz
 * @param {*} context
 */
const handleStartQuiz = (context) => {
  const { singleQuiz } = context.props;
  const timeLimit = singleQuiz.quiz[0].limit_seconds;
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
  const { singleQuiz } = context.props;
  if (currentQuizIndex < singleQuiz.quiz.length - 1) {
    context.setState(
      {
        currentQuizIndex: currentQuizIndex + 1,
        timeLimit: singleQuiz.quiz[currentQuizIndex + 1].limit_seconds,
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
    singleQuiz: state.quizzes.singleQuiz,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, {
  getSingleQuiz,
})(withTranslation("translation")(withRouter(QuizPreview)));
