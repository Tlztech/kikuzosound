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
import Span from "../../../presentational/atoms/Span";
import Input from "../../../presentational/atoms/Input";
import Label from "../../../presentational/atoms/Label";
import DragDrop from "../../../presentational/molecules/DragDrop";
import InputRadio from "../../../presentational/molecules/RadioWithLabel";
import { getCurrentDate } from "../../../../common/Date";
import { isEmail } from "../../../../common/Validation";
import Button from "../../../presentational/atoms/Button";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

//redux
import { getExamGroup } from "../../../../redux/modules/actions/ExamGroupAction";

// css
import "./style.css";

//i18n
import { withTranslation } from "react-i18next";
import i18next from "i18next";

//===================================================
// Component
//===================================================
const initial_state = {
  //exam data
  title: null,
  steps_of_exam: [],
  isChecked: true,
  //quiz form data
  title_jp: null,
  title_en: null,
  description: null,
  description_en: null,
  bg_img: {},
  question_format: 0, //quiz_order_type in edit
  max_quiz_count: "",
  destination_mail: "",
  exam_release: 1,
  lang: "",
  type_exam: true,
  type_quizzes: false,
  is_public: true,
  newexam: null,
  clearStep: false,
  errors: {},
  exams: [],
  filtered_exams: [],
  searched: false,
  searchKey: "",
};

class AddModal extends React.Component {
  constructor(props) {
    super(props);
    this.state = initial_state;
  }

  async componentDidMount() {
    await this.props.getExamGroup(this.props.userToken);
  }

  componentDidUpdate(prevProps) {
    const { exams, searched } = this.state;
    if (prevProps.exams != exams && !searched) {
      this.setState({
        exams: prevProps.exams,
        filtered_exams: prevProps.exams,
      });
    }
  }

  render() {
    const { isVisible, t } = this.props;
    const { newexam, clearStep, exams, filtered_exams, searchKey } = this.state;
    const {
      errors,
      destination_mail,
      type_exam,
      type_quizzes,
      question_format,
      max_quiz_count,
    } = this.state;
    const errorCollection = Object.keys(errors);
    const errorTexts = Object.values(errors);
    return (
      <Modal
        show={isVisible}
        onHide={() => {
          clearAddModalData(this, exams);
        }}
        aria-labelledby="contained-modal-title-vcenter"
        centered
        className="organism-ExamManageAdd-modal-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label>{t("add")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => {
              clearAddModalData(this, exams);
            }}
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
            <Col md={5} className="organism-add-modal-container-col">
              <InputWithLabel
                label={t("search")}
                typename="select"
                placeholder={t("enterText")}
                mode="left"
                onChange={(event) => handleSearch(event, this)}
              />
            </Col>
          </Div>

          <DragDrop
            tasks={clearStep ? newexam : filtered_exams}
            exam_steps={(data) => addExamSteps(this, data)}
            searchKey={searchKey}
          />
          <Span className="center-error">{errors["step_exams_error"]}</Span>

          <Div className="textInput-wrapper">
            <Col sm={12} md={12}>
              {/* form */}
              <Form onSubmit={(e) => AddExamManage(e, this)}>
                <Col sm={12} md={12}></Col>
                <Col sm={12} md={12}></Col>

                <Div className="form-item">
                  <Label className="labelstyle">{t("title_jp")+t("required_sign")}</Label>
                  <Input
                    className="inputStyle"
                    placeholder={t("title_jp_exam_placeholder")}
                    name="title_jp"
                    type="text"
                    validateError={errorCollection.includes("title")}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  <Span className="error">{errors["title_jp_error"]}</Span>
                </Div>

                <Div className="form-item">
                  <Label className="labelstyle">{t("title_en")+t("required_sign")}</Label>
                  <Input
                    className="inputStyle"
                    placeholder={t("title_en_exam_placeholder")}
                    name="title_en"
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
                  <Label className="labelstyle">{t("icon")+t("required_sign")}</Label>
                  <DropzoneArea
                    class="dropzone-test"
                    dropzoneText={t("dropzoneText_exam")}
                    acceptedFiles={["image/*"]}
                    showAlerts={false}
                    filesLimit={1}
                    showPreviews={true}
                    showPreviewsInDropzone={false}
                    previewText=""
                    onChange={(files) =>
                      this.setState({
                        bg_img: files[0],
                      })
                    }
                  />
                  <Span className="dropzone-error">
                    {errors["bg_img_error"]}
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
                      defaultChecked="checked"
                      value={1}
                      onClick={(event) => handleChangeForm(event, this)}
                    />
                    <InputRadio
                      title={t("radio_private")}
                      type="radio"
                      name="exam_release"
                      value={0}
                      onClick={(event) => handleChangeForm(event, this)}
                    />
                  </Span>
                </Div>

                <Div className="form-item">
                  <Label className="labelstyle">{t("qstn_format")}</Label>
                  <select
                    className="inputStyle "
                    name="question_format"
                    onChange={(event) => handleChangeForm(event, this)}
                    value={question_format}
                  >
                    <option value={0}>{t("fixed")}</option>
                    <option value={1}>{t("random")}</option>
                  </select>
                </Div>
                <Div className="form-item">
                  <Label className="labelstyle">{t("qstn_number")}</Label>
                  <Input
                    readOnly={true}
                    className="inputStyle"
                    name="max_quiz_count"
                    type="number"
                    validateError={errorCollection.includes("step_exams_error")}
                    value={max_quiz_count}
                    onChange={(event) => handleChangeForm(event, this)}
                  />

                  <Span className="error">{errors["max_quiz_count"]}</Span>
                </Div>
                <Div className="add-button">
                  <Button
                    mode="active"
                    className="btn-block text-center organism-add-modal-button"
                    type="submit"
                  >
                    {t("ok_btn")}
                  </Button>
                  <Button
                    mode="cancel"
                    onClick={() => clearAddModalData(this, exams)}
                    className="btn-block text-center organism-add-modal-button organism-cancel-modal-button"
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
const addExamSteps = (context, data) => {
  let steps = [];
  data.forEach((element) => {
    steps = [...steps, element.id];
  });
  context.setState({
    steps_of_exam: steps,
    max_quiz_count: steps.length,
  });
};

/**
 * on click checkbox
 * @param {*} context
 */
const oncheck = (event, context) => {
  context.setState({ isChecked: event.target.checked });
};

/**
 * clear step exam
 * @param {*} context
 * @param {*} value
 */
const clearAddModalData = (context, value) => {
  // on closing add modal reset exams to original state
  let newexam = value.map((item) => {
    return { ...item, type: "examLists" };
  });
  context.setState({ ...initial_state, newexam, clearStep: true });
  context.props.onHideAddModal();
};

/**
 * handle inputwithlabel change
 * @param {*} value
 * @param {*} context
 */
const handleSearch = (event, context) => {
  // context.props.onSearchFilter(event.target.value, context);
  const { exams } = context.state;
  const search_keyword = event.target.value.trim().toLowerCase();
  let filtered_exams = exams.filter(
    (el) =>
      (i18next.language === "en" &&
        el.taskName_EN.toLowerCase().includes(search_keyword)) ||
      (i18next.language === "ja" &&
        el.taskName.toLowerCase().includes(search_keyword))
  );
  !context.state.clearStep
    ? context.setState({
        searchKey: search_keyword,
        filtered_exams,
        searched: true,
      })
    : context.setState({
        searchKey: search_keyword,
        newexam: filtered_exams,
        searched: true,
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
 * validate & add exam data
 * @param {*} context
 */
const validateForm = (context) => {
  const {
    steps_of_exam,
    bg_img,
    title_jp,
    title_en,
    description,
    description_en,
    type_exam,
    destination_mail,
    type_quizzes,
  } = context.state;
  let errors = {};
  const { t } = context.props;
  let formIsValid = true;
  const { isValid, message } = isEmail(destination_mail);

  //quizzes
  if (steps_of_exam.length < 1) {
    formIsValid = false;
    errors["step_exams_error"] = t("quizzes_error");
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
  //icon
  if (!bg_img && bg_img == undefined) {
    formIsValid = false;
    errors["bg_img_error"] = t("icon_error");
  }
  //email
  if (type_exam) {
    if (!isValid) {
      formIsValid = false;
      errors["email_error"] = getEmailError(message, context);
    }
  }
  //question
  if (steps_of_exam.length < 1) {
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
 * Add Exam Manage Button
 * @param {*} event
 * @param {*} context
 */
const AddExamManage = (event, context) => {
  event.preventDefault();
  event.stopPropagation();
  const {
    title,
    steps_of_exam,
    isChecked,
    title_jp,
    title_en,
    title_color,
    description,
    description_en,
    bg_img,
    question_format,
    max_quiz_count,
    lang,
    type_exam,
    type_quizzes,
    is_public,
    exam_release,
    destination_mail,
  } = context.state;
  const newExam = {
    Title: title,
    Step_of_Exam: steps_of_exam,
    Enable_Disable: isChecked,
    Created: getCurrentDate(),
    Updated: getCurrentDate(),
    type_exam: type_exam,
    type_quizzes: type_quizzes,
    destination_email: destination_mail,
    is_public: type_quizzes,
    exam_release: parseInt(exam_release),
  };
  const newQuiz = {
    title_jp: title_jp,
    title_en: title_en,
    title_color: title_color,
    description: description,
    description_en: description_en,
    bg_img: bg_img,
    quiz_order_type: question_format,
    max_quiz_count: max_quiz_count,
    lang: lang,
  };
  const isValidated = validateForm(context);
  if (isValidated) {
    context.props.addExamData(newExam, newQuiz);
    context.props.onHideAddModal();
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
  };
};

//===================================================
// export
//===================================================
export default connect(mapStateToProps, {
  getExamGroup,
})(withTranslation("translation")(AddModal));
