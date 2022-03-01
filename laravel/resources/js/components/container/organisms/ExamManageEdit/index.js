import React from "react";
import { connect } from "react-redux";

// libs
import { Col, Modal, Form } from "react-bootstrap";
import { DropzoneArea } from "material-ui-dropzone";

// Components
import Div from "../../../presentational/atoms/Div";
import P from "../../../presentational/atoms/P";
import Image from "../../../presentational/atoms/Image";
import InputWithLabel from "../../../presentational/molecules/InputWithLabel";
import { isEmail } from "../../../../common/Validation";
import Label from "../../../presentational/atoms/Label";
import Input from "../../../presentational/atoms/Input";
import Span from "../../../presentational/atoms/Span";
import DragDrop from "../../../presentational/molecules/DragDrop";
import InputRadio from "../../../presentational/molecules/RadioWithLabel";
import { getCurrentDate } from "../../../../common/Date";
import Button from "../../../presentational/atoms/Button";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

//redux
import { getExamGroup } from "../../../../redux/modules/actions/ExamGroupAction";

// css
import "./style.css";

//i18n
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================

class ExamEditModal extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      errors: {},
      Step_of_Exam: [],
      examEditItem: [],
      exams: [],
      filtered_exams: [],

      //quiz form data
      title_jp: "",
      title_en: "",
      // title_color: "",
      description: "",
      description_en: "",
      icon_path: "",
      max_quiz_count: "",
      destination_mail: "",
      exam_release: null,
      lang: "",
      quiz_order_type: "",
      examId: null,
      quizPackId: null,
      type_exam: null,
      type_quizzes: null,
      is_public: true,
      searchKey: "",

      //new img
      bg_img: {},
      imageHash: null,
    };
  }
  async componentDidMount() {
    await this.props.getExamGroup(this.props.userToken);
  }

  UNSAFE_componentWillReceiveProps(nextProps) {
    const state_array = Object.keys(this.state);
    // setting value to state to old value of item from props
    if (nextProps.isVisible && nextProps.examEditItem) {
      state_array.forEach((state) =>
        this.setState({
          [state]:
            state == "Step_of_Exam"
              ? nextProps.examEditItem[state].map((item) => item.id)
              : state == "errors"
              ? []
              : nextProps.examEditItem[state],
        })
      );
    }
    this.setState({
      imageHash: Date.now(),
    });
    setQuizzesState(nextProps, this);
  }

  render() {
    const { isVisible, onHideEditModal, examEditItem, t } = this.props;
    const {
      title_jp,
      title_en,
      description,
      description_en,
      bg_img,
      max_quiz_count,
      quiz_order_type,
      type_exam,
      type_quizzes,
      destination_mail,
      exam_release,
      errors,
      filtered_exams,
      imageHash,
      searchKey,
    } = this.state;
    const errorCollection = Object.keys(errors);
    const errorTexts = Object.values(errors);
    return (
      <Modal
        show={isVisible}
        onHide={onHideEditModal}
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
        className="organism-edit-modal-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label className="organism-header-text">{t("edit")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={onHideEditModal}
          />
        </Modal.Header>
        <Modal.Body className="organism-modal-body">
          {errorTexts.length > 0 && (
            <Div className="alert alert-danger">
              <P>{t("validate_error")}</P>
              <ul>
                {errorTexts.map((e, i) => (
                  <li key={i}>{e}</li>
                ))}
              </ul>
            </Div>
          )}
          <Div className="molecules-Drag-wrapper input-box">
            <Col md={5} className="organism-edit-modal-container-col">
              <InputWithLabel
                label={t("search")}
                typename="text"
                placeholder={t("enterText")}
                mode="left"
                onChange={(event) => handleSearch(event, this)}
              />
            </Col>
          </Div>
          <DragDrop
            tasks={filtered_exams}
            exam_steps={(data) => updateExamSteps(this, data)}
            searchKey={searchKey}
          />
          <Span className="center-error">{errors["step_exams_error"]}</Span>
          <Div className="textInput-wrapper">
            <Col sm={12} md={12}>
              <Form onSubmit={(e) => EditExamManage(e, this)}>
                <Col sm={12} md={12}></Col>
                <Col sm={12} md={12}></Col>
                <Div className="form-item">
                  <Label className="labelstyle">{t("title_jp")+t("required_sign")}</Label>
                  <Input
                    className="inputStyle"
                    placeholder={t("title_jp_exam_placeholder")}
                    name="title_jp"
                    value={title_jp}
                    type="text"
                    validateError={errorCollection.includes("title")+t("required_sign")}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  <Span className="error">{errors["title_jp_error"]}</Span>
                </Div>
                <Div className="form-item">
                  <Label className="labelstyle"> {t("title_en")+t("required_sign")} </Label>
                  <Input
                    className="inputStyle"
                    placeholder={t("title_en_exam_placeholder")}
                    name="title_en"
                    value={title_en}
                    type="text"
                    validateError={errorCollection.includes("title_en_error")}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  <Span className="error">{errors["title_en_error"]}</Span>
                </Div>
                <Div className="form-item">
                  <Label className="labelstyle">{t("descriptions_jp")+t("required_sign")}</Label>
                  <Input
                    className="inputStyle"
                    placeholder={t("description_jp_exam_placeholder")}
                    name="description"
                    value={description}
                    type="text"
                    validateError={errorCollection.includes(
                      "description_error"
                    )}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  <Span className="error">{errors["description_error"]}</Span>
                </Div>
                <Div className="form-item">
                  <Label className="labelstyle">{t("descriptions_en")+t("required_sign")}</Label>
                  <Input
                    className="inputStyle"
                    placeholder={t("description_en_exam_placeholder")}
                    name="description_en"
                    value={description_en}
                    type="text"
                    validateError={errorCollection.includes(
                      "description_en_error"
                    )}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  <Span className="error">
                    {errors["description_en_error"]}
                  </Span>
                </Div>
                <Div className="form-item">
                  <Label className="labelstyle"> {t("icon")+t("required_sign")} </Label>
                  <Span className="icon-container">
                    <DropzoneArea
                      dropzoneText={t("dropzoneText_exam")}
                      acceptedFiles={["image/*"]}
                      showAlerts={false}
                      showPreviews={true}
                      showPreviewsInDropzone={false}
                      previewText=""
                      onChange={(files) =>
                        this.setState({
                          bg_img: files[0],
                        })
                      }
                      filesLimit={1}
                    />
                    {bg_img == undefined &&
                      examEditItem &&
                      examEditItem.icon_path && (
                        <Image
                          url={`${examEditItem.icon_path}?${imageHash}`}
                          className="icon-img"
                        />
                      )}
                  </Span>
                </Div>
                {type_exam && (
                  <Div className="form-item">
                    <Label className="labelstyle">
                      {t("destination_email")+t("required_sign")}
                    </Label>
                    <Input
                      className="inputStyle "
                      name="destination_mail"
                      validateError={errorCollection.includes("email_error")}
                      onChange={(event) => handleChangeForm(event, this)}
                      value={destination_mail}
                    />
                    <Span className="error">{errors["email_error"]}</Span>
                  </Div>
                )}
                <Div className="form-item">
                  <Label className="labelstyle">{t("status")}</Label>
                  <Span className="radioStyle">
                    <InputRadio
                      title={t("radio_public")}
                      type="radio"
                      name="exam_release"
                      defaultChecked={exam_release == 1 ? "checked" : ""}
                      value={1}
                      onClick={(event) => handleChangeForm(event, this)}
                    />
                    <InputRadio
                      title={t("radio_private")}
                      type="radio"
                      name="exam_release"
                      defaultChecked={exam_release == 0 ? "checked" : ""}
                      value={0}
                      onClick={(event) => handleChangeForm(event, this)}
                    />
                  </Span>
                </Div>
                <Div className="form-item">
                  <Label className="labelstyle">{t("qstn_format")}</Label>
                  <select
                    className="inputStyle bg-white text-muted"
                    name="quiz_order_type"
                    value={quiz_order_type}
                    onChange={(event) => handleChangeForm(event, this)}
                  >
                    <option value={0}>{t("fixed")}</option>
                    <option value={1}>{t("random")}</option>
                  </select>
                  <Span className="select-arrow"></Span>
                </Div>
                <Div className="form-item">
                  <Label className="labelstyle">{t("qstn_number")}</Label>
                  <Input
                    readOnly={true}
                    className="inputStyle bg-white"
                    name="max_quiz_count"
                    value={max_quiz_count}
                    type="number"
                    validateError={errorCollection.includes("step_exams_error")}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  <Span className="error">{errors["max_quiz_count"]}</Span>
                </Div>
                <Div className="edit-button">
                  <Button
                    type="submit"
                    mode="active"
                    className="btn-block text-center organism-edit-modal-button"
                  >
                    {t("ok_btn")}
                  </Button>
                  <Button
                    onClick={() => this.props.onHideEditModal()}
                    mode="cancel"
                    className="btn-block text-center organism-edit-modal-button organism-cancel-modal-button"
                  >
                    {t("cancel_btn")}
                  </Button>
                </Div>
              </Form>
            </Col>
          </Div>
        </Modal.Body>
      </Modal>
    );
  }
}

//===================================================
// functions
//===================================================
/**
 * send props from dragdrop to add examsteps after drop
 * @param {*} context
 * @param {*} data
 */
const updateExamSteps = (context, value) => {
  let steps = [];
  value.forEach((element) => {
    steps = [...steps, element.id];
  });

  context.setState({
    // Step_of_Exam: value,
    Step_of_Exam: steps,
    max_quiz_count: steps.length,
  });
};

/**
 * handle inputwithlabel change
 * @param {*} value
 * @param {*} context
 */
const handleSearch = (event, context) => {
  const { exams } = context.state;
  let filterd_exams = exams.filter((quiz) =>
    quiz.taskName.includes(event.target.value)
  );
  // exclude selected quizzes
  let excludeToFilter = exams.filter((item) => {
    return context.state.Step_of_Exam.includes(item.id);
  });
  //get unique values
  filterd_exams = excludeToFilter
    .concat(filterd_exams)
    .filter((value, index, self) => {
      return self.indexOf(value) === index;
    });
  context.setState({
    filtered_exams: filterd_exams,
    searchKey: event.target.value,
  });
};

/**
 * handle form input change
 * @param {*} value
 * @param {*} context
 */
const handleChangeForm = (event, context) => {
  context.setState({ [event.target.name]: event.target.value });
};

const handleChangeCheckForm = (event, context) => {
  context.setState({ [event.target.name]: event.target.checked });
};

/**
 * handle setting quizzes state
 * @param {*} context
 * @param {nextProps} nextProps
 */
const setQuizzesState = (nextProps, context) => {
  const { examEditItem } = nextProps;
  const { quizList, isLoading } = nextProps.quizzes;
  //getting mapped quiz list
  let exams = [];
  if (!isLoading) {
    exams = quizList
      ? quizList.map((list) => {
          return {
            id: list.id,
            taskName: list.title,
            taskName_EN: list.title_en,
            type:
              examEditItem.length == 0
                ? "examLists"
                : examEditItem.Step_of_Exam.some((item) => item.id == list.id)
                ? "examSteps"
                : "examLists",
          };
        })
      : [];
  }
  const filter_exam = [];
  exams.map((exam, index) => {
    if (examEditItem.Step_of_Exam) {
      var items = examEditItem.Step_of_Exam.find((item) => item.id == exam.id);
      if (!items) filter_exam.push(exam);
    }
  });
  if (examEditItem && examEditItem.Step_of_Exam) {
    var sortedObjs = examEditItem.Step_of_Exam.sort(function (a, b) {
      return a.disp_order - b.disp_order;
    });
    sortedObjs.map((item) => {
      var sortedexam = exams.find((i) => i.id == item.id);
      filter_exam.push(sortedexam);
    });
  }
  context.setState({
    exams: exams,
    filtered_exams: filter_exam,
  });
};

/**
 * validate & update exam data
 * @param {*} context
 */
const validateForm = (context) => {
  const {
    Step_of_Exam,
    title_jp,
    title_en,
    description,
    description_en,
    type_exam,
    type_quizzes,
    destination_mail,
    bg_img,
  } = context.state;
  const { t } = context.props;
  const { isValid, message } = isEmail(destination_mail);
  let errors = {};
  let formIsValid = true;

  //quizzes
  if (Step_of_Exam.length < 1) {
    formIsValid = false;
    errors["step_exams_error"] = t("quizzes_error");
    // errors["max_quiz_count"] = t("max_number_quiz_validation");
  }
  //title
  if (!title_jp || title_jp.trim.length < 0) {
    formIsValid = false;
    errors["title_jp_error"] = t("validate_title_jp");
  }
  //title_en
  if (!title_en || title_en.trim.length < 0) {
    formIsValid = false;
    errors["title_en_error"] = t("validate_title_en");
  }
  //description
  if (!description || description.trim.length < 0) {
    formIsValid = false;
    errors["description_error"] = t("validate_description_jp");
  }
  //description_en
  if (!description_en || description_en.trim.length < 0) {
    formIsValid = false;
    errors["description_en_error"] = t("validate_description_en");
  }
  //email
  if (type_exam) {
    if (!isValid) {
      formIsValid = false;
      errors["email_error"] = getEmailError(message, context);
    }
  }
  //question
  if (Step_of_Exam.length < 1) {
    formIsValid = false;
    errors["max_quiz_count"] = t("max_number_quiz_validation");
  }
  context.setState({ errors: errors });
  return formIsValid;
};

/**
 * Return destination email error
 * @param {*} message
 * @param {*} context
 */
const getEmailError = (message, context) => {
  const { t } = context.props;
  switch (message) {
    case "empty_email":
      return t("empty_destination_email");
    case "invalid_email":
      return t("invalid_email");
    default:
      break;
  }
};

/**
 * Edit Exam Manage Button
 * @param {*} event
 * @param {*} context
 */
const EditExamManage = (event, context) => {
  event.preventDefault();
  event.stopPropagation();
  const {
    Step_of_Exam,
    title_jp,
    title_en,
    // title_color,
    description,
    description_en,
    bg_img,
    lang,
    quiz_order_type,
    max_quiz_count,
    examId,
    quizPackId,
    type_exam,
    type_quizzes,
    destination_mail,
    exam_release,
  } = context.state;
  const updateExam = {
    Step_of_Exam: Step_of_Exam,
    No: context.props.examEditItem.No,
    Created: context.props.examEditItem.Created,
    Updated: getCurrentDate(),
    examId: context.props.examEditItem.examId,
    quizPackId: context.props.examEditItem.quizPackId,
    examId: examId,
    type_exam: type_exam,
    type_quizzes: type_quizzes,
    destination_email: destination_mail,
    is_public: type_quizzes,
    exam_release: parseInt(exam_release),
  };
  const updateQuiz = {
    title_jp: title_jp,
    title_en: title_en,
    // title_color: title_color,
    description: description,
    description_en: description_en,
    bg_img: bg_img,
    quiz_order_type: quiz_order_type,
    lang: lang,
    max_quiz_count: max_quiz_count,
    quizPackId: quizPackId,
  };
  //make same as add
  const isValidated = validateForm(context);
  if (isValidated) {
    context.props.editExamData(updateExam, updateQuiz);
    context.props.onHideEditModal();
  }
};
//===================================================
// actions
//===================================================

//===================================================
// redux
//===================================================
const mapStateToProps = (state) => {
  return {
    examGroup: state.examGroup,
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    quizzes: state.quizzes,
  };
};

//===================================================
// export
//===================================================
export default connect(mapStateToProps, {
  getExamGroup,
})(withTranslation("translation")(ExamEditModal));
