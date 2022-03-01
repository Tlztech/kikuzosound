import React from "react";

// bootstrap
import { Modal } from "react-bootstrap";

// components
import Div from "../../../presentational/atoms/Div/index";
import Span from "../../../presentational/atoms/Span/index";
import P from "../../../presentational/atoms/P/index";
import Label from "../../../presentational/atoms/Label/index";
import Image from "../../../presentational/atoms/Image";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";
import CheckMark from "../../../../assets/checkmark.png";
import DefaultIcon from "../../../../assets/default_icon.jpg";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class ExamManagePreview extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      title: "",
      quiz_infos: [],
      current_quiz_question: "",
      current_quiz_choices: [],
    };
  }

  UNSAFE_componentWillReceiveProps(nextProps) {
    if (nextProps.examPreviewItem != this.props.examPreviewItem) {
      this.setState({
        quiz_infos: nextProps.examPreviewItem.Step_of_Exam,
      });
    }
  }

  render() {
    const { isVisible, onHidePreviewModal, examPreviewItem } = this.props;
    const {
      quiz_infos,
      title,
      current_quiz_choices,
      current_quiz_question,
    } = this.state;
    return (
      <Modal
        show={isVisible}
        onHide={onHidePreviewModal}
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
        className="organism-preview-modal-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label>Preview</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => {
              newData(this);
            }}
          />
        </Modal.Header>
        <Modal.Body className="organism-modal-body">
          <Span className="dynamic-title">{title}</Span>
          <Div className="preview-grid-content">
            <Image
              url={
                examPreviewItem.icon_path
                  ? `${examPreviewItem.icon_path}`
                  : DefaultIcon
              }
              className="organism-modal-examImage"
            />
            <P className="description">
              {examPreviewItem.lang == "ja"
                ? examPreviewItem.description
                : examPreviewItem.description_en}
            </P>
          </Div>

          <Div className="steps-of-exam">
            <ul>
              {quiz_infos.map((quiz) => {
                return (
                  <li
                    key={quiz.id}
                    onClick={(e) => setCurrentQuizInfo(this, e, quiz)}
                  >
                    {quiz.title.substring(2)}
                  </li>
                );
              })}
            </ul>
          </Div>

          <Div className="question">
            {current_quiz_question ? `Question : ${current_quiz_question}` : ""}
          </Div>
          <Div className="answer-list">
            {current_quiz_choices.length != 0 ? "Choices:" : ""}
            <Div className="choice-list">
              {current_quiz_choices && current_quiz_choices.map((choice, index) => {
                return (
                  <li key={index}>
                    {choice.title}
                    {choice.is_correct_answer == 1 ? (
                      <Image url={CheckMark} className="tick-mark" />
                    ) : (
                      ""
                    )}
                  </li>
                );
              })}
            </Div>
          </Div>
        </Modal.Body>
        <Modal.Footer className="organism-preview-modal-footer"></Modal.Footer>
      </Modal>
    );
  }
}

//===================================================
// Functions
//===================================================

const setCurrentQuizInfo = (context, event, quiz) => {
  context.setState({
    title: event.target.innerHTML,
    current_quiz_question:
      quiz.question != "" ? quiz.question : quiz.question_en,
    current_quiz_choices: quiz.quiz_choices.filter(
      (choice) => choice.title != ""
    ),
  });
};

const newData = (context) => {
  context.setState({
    title: "",
    current_quiz_question: "",
    current_quiz_choices: [],
  });
  context.props.onHidePreviewModal();
};

export default ExamManagePreview;
