import React from "react";
import { connect } from "react-redux";

// libs
import { Row, Col, Modal, Form } from "react-bootstrap";
import Select2 from "react-select2-wrapper";
import { DropzoneArea } from "material-ui-dropzone";

// Components
import Div from "../../../presentational/atoms/Div/index";
import P from "../../../presentational/atoms/P/index";
import Button from "../../../presentational/atoms/Button";
import Image from "../../../presentational/atoms/Image";
import Label from "../../../presentational/atoms/Label";
import Input from "../../../presentational/atoms/Input/index";
import InputRadio from "../../../presentational/molecules/RadioWithLabel";
import Span from "../../../presentational/atoms/Span";
import Select from "../../../presentational/atoms/Select";
import ExamGroupItem from "../../../presentational/molecules/ExamGroupItem";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

// css
import "./style.css";

//i18n
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
const initial_state = {
  title: null,
  title_en: null,
  description: null,
  description_en: null,
  color: null,
  icon_path: null,
  new_icon: {},
  imageHash: null,
  question_format: 0,
  number_of_questions: 0,
  lang: "en",
  release: 0,
  errors: {},

  //quizzes
  selected_quiz: "",
  added_quiz: [],

  //grooup attribute section
  group_attribute: 0, //0 is true
  selected_exam_group: [],
};
class QuizPackEdit extends React.Component {
  constructor(props) {
    super(props);
    this.state = initial_state;
  }

  async componentDidMount() {
    await handleSetFormValues(this);
    setSelectedQuiz(this);
  }

  async componentDidUpdate(prevProps) {
    if (this.props.isVisible && !prevProps.isVisible) {
      await handleSetFormValues(this);
      setSelectedQuiz(this);
    }
  }

  render() {
    const { isVisible, t ,userInfo} = this.props;
    const {
      title,
      title_en,
      description,
      description_en,
      errors,
      lang,
      color,
      release,
      group_attribute,
      new_icon,
      icon_path,
    } = this.state;
    const errorCollection = Object.keys(errors);
    const errorValue = Object.values(errors);
    let quiz_list_data = getQuizList(this);
    return (
      <Modal
        show={isVisible}
        onHide={() => cancelEdit(this)}
        size="lg"
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
        className="organism-edit-manage-modal-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label>{t("edit_quizpack")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => cancelEdit(this)}
          />
        </Modal.Header>

        <Modal.Body className="quizpack-organism-edit-modal-container">
          {errorValue.length > 0 && (
            <Div className="alert alert-danger">
              <P>{t("validate_error")}</P>
              <ul>
                {errorValue.map((e, i) => (
                  <li key={i}>{e}</li>
                ))}
              </ul>
            </Div>
          )}
          <Div className="textInput-wrapper">
            <Col sm={12} md={12}>
              {/* form */}
              <Form onSubmit={submitData}>
                <Col sm={12} md={12}></Col>
                <Col sm={12} md={12}></Col>

                <Div className="form-item">
                  <Label
                    className="labelstyle"
                    labelError={errorCollection.includes("title")}
                  >
                    {t("title_jp")+t("required_sign")}
                  </Label>
                  <Input
                    inputError={errorCollection.includes("title")}
                    className="inputStyle"
                    placeholder="Lung Sound Pack(JP)"
                    name="title"
                    value={title || ""}
                    type="text"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  {<Span className="error">{errors["title"]}</Span>}
                </Div>

                <Div className="form-item">
                  <Label
                    className="labelstyle"
                    labelError={errorCollection.includes("title_en")}
                  >
                    {t("title_en")+t("required_sign")}
                  </Label>
                  <Input
                    inputError={errorCollection.includes("title_en")}
                    className="inputStyle"
                    placeholder="Lung Sound Pack(EN)"
                    name="title_en"
                    value={title_en || ""}
                    type="text"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  {<Span className="error">{errors["title_en"]}</Span>}
                </Div>

                <Div className="form-item">
                  <Label className="labelstyle"> {t("title_color")} </Label>
                  <Input
                    className="inputStyle background"
                    placeholder="333333"
                    value={this.state.color}
                    name="color"
                    value={color || ""}
                    type="color"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                </Div>
                <Div className="form-item">
                  <Label
                    className="labelstyle"
                    labelError={errorCollection.includes("description")}
                  >
                    {t("description_jp")+t("required_sign")}
                  </Label>
                  <Input
                    inputError={errorCollection.includes("description")}
                    className="inputStyle"
                    placeholder={t("description_jp_placeholder")}
                    name="description"
                    value={description || ""}
                    type="text"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  {<Span className="error">{errors["description"]}</Span>}
                </Div>

                <Div className="form-item">
                  <Label
                    className="labelstyle"
                    labelError={errorCollection.includes("description_en")}
                  >
                    {t("description_en")+t("required_sign")}
                  </Label>
                  <Input
                    inputError={errorCollection.includes("description_en")}
                    className="inputStyle"
                    placeholder={t("description_en_placeholder")}
                    name="description_en"
                    value={description_en || ""}
                    type="text"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  {<Span className="error">{errors["description"]}</Span>}
                </Div>
                <Div className="form-item">
                  <Label
                    labelError={errorCollection.includes("icon")}
                    className="labelstyle"
                  >
                    {t("icon")+t("required_sign")}
                  </Label>
                  <DropzoneArea
                    acceptedFiles={["image/*"]}
                    showAlerts={false}
                    filesLimit={1}
                    showPreviews={true}
                    showPreviewsInDropzone={false}
                    previewText=""
                    onChange={(files) =>
                      this.setState({
                        new_icon: files[0],
                      })
                    }
                    onDelete={() =>
                      this.setState({
                        icon_path: "",
                      })
                    }
                  />
                  {new_icon == undefined && icon_path && (
                    <Image
                      url={`${icon_path}?${this.state.imageHash}`}
                      className="icon-img"
                    />
                  )}

                  {<Span className="error">{errors["icon"]}</Span>}
                </Div>

                <Div className="form-item">
                  <Label className="labelstyle"> {t("radio_public")} </Label>
                  <Span>
                    <InputRadio
                      title={t("radio_public")}
                      className="radioStyle"
                      name="release"
                      value={1}
                      defaultChecked={release == 1 ? "checked" : ""}
                      onClick={(event) => handleChangeForm(event, this)}
                    />
                    <InputRadio
                      title={t("radio_private")}
                      className="radioStyle"
                      name="release"
                      value={0}
                      defaultChecked={release == 0 ? "checked" : ""}
                      onClick={(event) => handleChangeForm(event, this)}
                    />
                  </Span>
                </Div>

                <Div className="form-item">
                  <Label className="labelstyle"> {t("qstn_format")}</Label>
                  <select
                    className="inputStyle "
                    name="question_format"
                    onChange={(event) => handleChangeForm(event, this)}
                    value={this.state.question_format}
                  >
                    <option value={0}>{t("option_1")}</option>
                    <option value={1}>{t("option_2")}</option>
                  </select>
                </Div>
                <Div className="form-item">
                  <Label
                    className="labelstyle"
                    labelError={errorCollection.includes("quiz_select")}
                  >
                    {t("qstn_number")}
                  </Label>
                  {/* <Input
                    inputError={errorCollection.includes("max_quiz_count")}
                    readOnly={true}
                    className="inputStyle"
                    name="max_quiz_count"
                    type="number"
                    value={
                      this.state.selected_quiz &&
                      this.state.selected_quiz.length
                    }
                    onChange={(event) => handleChangeForm(event, this)}
                  /> */}
                  {<Span className="error">{errors["max_quiz_count"]}</Span>}
                </Div>

                <Div className="form-item">
                  <Label className="labelstyle"> {t("lange")} </Label>
                  <Span>
                    <InputRadio
                      title="En"
                      className="radioStyle"
                      name="lang"
                      value="EN"
                      defaultChecked={
                        lang && lang.toLowerCase() == "en" ? "checked" : ""
                      }
                      onClick={(event) => handleChangeForm(event, this)}
                    />
                    <InputRadio
                      title="Ja"
                      className="radioStyle"
                      name="lang"
                      value="JP"
                      defaultChecked={
                        lang && lang.toLowerCase() == "jp" ? "checked" : ""
                      }
                      onClick={(event) => handleChangeForm(event, this)}
                    />
                  </Span>
                </Div>

                <Div className="form-item">
                  <Label
                    className="labelstyle"
                    labelError={errorCollection.includes("quiz_select")}
                  >
                    {t("quizzes")+t("required_sign")}
                  </Label>
                  <Span>
                    <Select
                      className="select-style organism-QuizPackAdd-quiz_pack_dropdown"
                      items={quiz_list_data ? quiz_list_data : []}
                      value={this.state.selected_quiz}
                      onChange={(event) => selectQuiz(event, this)}
                    ></Select>

                    <Button mode="success" onClick={(e) => addQuiz(e, this)}>
                      Add Quiz
                    </Button>
                  </Span>

                  <Div></Div>
                  <Div className="quiz-list">
                    {this.state.added_quiz &&
                      this.state.added_quiz.length != 0 &&
                      this.state.added_quiz.map((item, index) => {
                        var quizItem = this.props.quiz_list.find(
                          (quiz_item) => quiz_item.id == item
                        )
                        return (
                          <Span className="quiz-item" key={index}>
                            <Label>
                              {this.props.quiz_list &&
                                this.props.quiz_list.length != 0 &&
                                quizItem !== undefined &&
                                  quizItem.value
                              }
                            </Label>
                            <Button
                              mode="danger"
                              onClick={(e) => deleteQuiz(index, this)}
                            >
                              Delete
                            </Button>
                          </Span>
                        );
                      })}
                  </Div>
                  <Span className="error">{errors["quiz_select"]}</Span>
                </Div>

                <Div className={"form-item "+(userInfo.role=="201" ? "teacher-group_attr_wrapper" : "group_attr_wrapper")}>
                  <Label className="labelstyle"> {t("group_attr")} </Label>
                  <Span className="mr-2">
                    {(userInfo.role!="201") &&
                      <InputRadio
                        title={t("radio_yes")}
                        className="radio-style ml-2 "
                        name="group_attribute"
                        value={0}
                        defaultChecked={group_attribute == 0 ? "checked" : ""}
                        onClick={(event) => handleChangeForm(event, this)}
                      />
                    }

                    <Select2
                      multiple
                      value={getdExamGroupDefaultValue(this)} // or as string | array
                      data={getdExamGroupData(this)}
                      onSelect={(selectedList, selectedItem) =>
                        onSelect(
                          selectedList,
                          selectedItem,
                          this,
                          "selected_exam_group"
                        )
                      }
                    />
                    {(userInfo.role!="201") &&
                      <InputRadio
                        title={t("radio_none")}
                        className="radio-style ml-2"
                        name="group_attribute"
                        value={1}
                        defaultChecked={group_attribute == 1 ? "checked" : ""}
                        onClick={(event) => handleChangeForm(event, this)}
                      />
                    }
                  </Span>
                </Div>
              </Form>
            </Col>
          </Div>
        </Modal.Body>

        <Modal.Footer className="organism-edit-modal-footer">
          <Row>
            <Col md={5} xs={5}>
              <Button
                mode="success"
                onClick={() => submitData(this)}
                className="btn-block text-center organism-edit-modal-button"
              >
                {t("ok_btn")}
              </Button>
            </Col>
            <Col md={4} xs={4}>
              <Button
                mode="cancel"
                onClick={() => cancelEdit(this)}
                className="btn-block text-center organism-edit-modal-button"
              >
                {t("cancel_btn")}
              </Button>
            </Col>
          </Row>
        </Modal.Footer>
      </Modal>
    );
  }
}

//===================================================
// functions
//===================================================
/**
 * get exam group default value
 * @param {*} context
 */
 const getdExamGroupDefaultValue = (context) => {
  const {userInfo,exam_groups} = context.props;
  let result = context.state.selected_exam_group.map(a => a.id)
  return result;
}
/**
 * get exam group data
 * @param {*} context
 */
 const getdExamGroupData = (context) => {
  const {userInfo,exam_groups} = context.props;
  if(userInfo && userInfo.role=="201"){
    let result = exam_groups.filter(group => group.id == parseInt(userInfo.university_id));
    return result;
  }else{
    return exam_groups;
  }
}
/**
 * fetch quizpack
 * @param {*} context
 */
const setSelectedQuiz = async (context) => {
  let quiz_list_data = context.props.quiz_list.filter(function(item) {
    return !context.state.added_quiz.includes(item.id);
  });
  context.setState({
    selected_quiz: quiz_list_data.length != 0 ? quiz_list_data[0].id : "",
  });

};

/**
 * set form values
 * @param {*} context
 */
const handleSetFormValues = async (context) => {
  
  const { editItem } = context.props;
  await context.setState({
    title: editItem.title || null,
    title_en: editItem.title_en || null,
    description: editItem.description || null,
    description_en: editItem.description_en || null,
    color: editItem.color,
    icon_path: editItem.icon_path,
    imageHash: Date.now(),
    question_format: editItem.question_format,
    number_of_questions: editItem.number_of_questions,
    lang: editItem.lang,
    release: editItem.release === "private" ? 0 : 1,
    selected_exam_group: editItem.selected_exam_group
      ? editItem.selected_exam_group
      : [],
    added_quiz: editItem.selected_quiz 
      ? editItem.selected_quiz.map((item) => item.id)
      : [],
    group_attribute:
      editItem.selected_exam_group && editItem.selected_exam_group.length > 0
        ? 0
        : 1,
    id: editItem.ID,
  });
};

/**
 * handle change on input
 * @param {*} context
 * @param {*} value
 */
const handleChangeForm = (event, context) => {
  context.setState({ [event.target.name]: event.target.value });
};

/**
 * on select
 * @param {*} selectedList
 * @param {*} selectedItem
 * @param {*} context
 * @param {*} type
 */
const onSelect = (selectedList, selectedItem, context, type) => {
  let list = {
    id: selectedList.params.data.id,
    text: selectedList.params.data.text,
  };
  context.setState({
    [type]: Array.from(new Set([...context.state[type], list])),
  });
};

/**
 *  cancel edit user
 * @param {*} context
 */
const cancelEdit = (context) => {
  context.setState({
    ...initial_state,
  });
  context.props.onHideEditModal();
};

/**
 * remove item
 * @param {*} index
 * @param {*} context
 * @param {*} type
 */
const removeItem = async (index, context, type) => {
  let remove_type = context.state[type];
  remove_type.splice(index, 1);
  context.setState({ [remove_type]: remove_type });
};

/**
 * validate input
 * @param {*} context
 */
const handleValidate = (context) => {
  const {
    title,
    title_en,
    description,
    description_en,
    added_quiz,
    new_icon,
    icon_path,
  } = context.state;
  const { t } = context.props;
  let errors = {};
  let formIsValid = true;
  //title
  if (!title || title.trim.length < 0) {
    formIsValid = false;
    errors["title"] = t("validate_title_jp");
  }
  //title_en
  if (!title_en || title_en.trim.length < 0) {
    formIsValid = false;
    errors["title_en"] = t("validate_title_en");
  }
  //description
  if (!description || description.trim.length < 0) {
    formIsValid = false;
    errors["description"] = t("validate_description_jp");
  }
  //description_en
  if (!description_en || description_en.trim.length < 0) {
    formIsValid = false;
    errors["description_en"] = t("validate_description_en");
  }
  //icon
  if (!icon_path && !new_icon) {
    formIsValid = false;
    errors["icon"] = t("validate_icon");
  }
  //max quiz count
  if (added_quiz < 1) {
    formIsValid = false;
    errors["max_quiz_count"] = t("validate_max_quiz");
    errors["quiz_select"] = t("validate_select_quiz");
  }
  context.setState({ errors: errors });
  return formIsValid;
};

/**
 * submitData
 * @param {*} context
 */
const submitData = (context) => {
  const { onHideEditModal, updateQuizPack } = context.props;
  const quizpack_data = {
    ...context.state,
  };
  const isValidated = handleValidate(context);
  if (isValidated) {
    updateQuizPack(quizpack_data, context.props.editItem.ID);
    context.setState({
      ...initial_state,
    });
    onHideEditModal();
  }
};
/**
 * get filtered quiz list
 * @param {*} context
 */
 const getQuizList = (context) => {
  return context.props.quiz_list.filter(function(item) {
    return !context.state.added_quiz.includes(item.id);
  });
}

/**
 * handle select quiz
 * @param {*} e
 * @param {*} context
 * @param {*} type
 */
const selectQuiz = async (e, context) => {
  await context.setState({
    selected_quiz: e.target.value,
  });
};

/**
 * handle adding quiz
 * @param {*} e
 * @param {*} context
 * @param {*} type
 */
const addQuiz = async (e, context) => {
  await context.setState({
    added_quiz: [...context.state.added_quiz, parseInt(context.state.selected_quiz)],
  });
  setSelectedQuiz(context);
};

/**
 * handle deleting quiz
 * @param {*} e
 * @param {*} context
 * @param {*} type
 */
const deleteQuiz = async (pos, context) => {
  const { added_quiz } = context.state;
  if (added_quiz.length > 0) {
    context.setState({
      added_quiz: added_quiz.filter((item, index) => index !== pos),
    });
    setSelectedQuiz(context);
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
    currentUniversity:
      state.auth.userInfo && state.auth.userInfo.user.university_id,
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    userInfo: state.auth.userInfo && state.auth.userInfo.user,
  };
};

//===================================================
// export
//===================================================
export default connect(mapStateToProps)(
  withTranslation("translation")(QuizPackEdit)
);
