import React from "react";

// libs
import { connect } from "react-redux";
import { Row, Col, Modal } from "react-bootstrap";
import Select2 from "react-select2-wrapper";

// Components
import Div from "../../../presentational/atoms/Div";
import P from "../../../presentational/atoms/P";
import Button from "../../../presentational/atoms/Button";
import MediaComponent from "../../../presentational/molecules/Media";
import Image from "../../../presentational/atoms/Image";
import Label from "../../../presentational/atoms/Label";
import InputWithLabel from "../../../presentational/molecules/InputWithLabel";
import InputDescription from "../../../presentational/molecules/InputDescription"
import Span from "../../../presentational/atoms/Span";
import InputRadio from "../../../presentational/molecules/RadioWithLabel";
import ExamGroupItem from "../../../presentational/molecules/ExamGroupItem";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

//redux
import { getExamGroup } from "../../../../redux/modules/actions/ExamGroupAction";
import { getLibraryUser } from "../../../../redux/modules/actions/LibraryUserAction";

// css
import "./style.css";

//i18n
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
const initial_state = {
  video_file: null,
  title: null,
  title_en: null,
  normal_abnormal: 1,
  ucg_explanation: "",
  ucg_explanation_en: "",
  status: null,
  user_list: [],
  user_id: "0",
  errors: {},
  exam_groups: [],
  selected_exam_group: [],
  group_attribute: "1",
  supervisor_comment: "",
};

class UcgLibraryEdit extends React.Component {
  constructor(props) {
    super(props);
    this.state = initial_state;
  }

  componentDidMount() {
    handleFetchData(this);
    handleSetFormValues(this);
  }

  componentDidUpdate(prevProps) {
    if (this.props.isVisible && !prevProps.isVisible) {
      handleFetchData(this);
      handleSetFormValues(this);
    }
  }

  render() {
    const { isVisible, t } = this.props;
    const {
      errors,
      title,
      title_en,
      ucg_explanation,
      ucg_explanation_en,
      normal_abnormal,
      status,
      video_file,
      group_attribute,
      supervisor_comment,
      exam_groups,
      selected_exam_group,
    } = this.state;
    const errorCollection = Object.keys(errors);
    const errorValue = Object.values(errors);
    return (
      <Modal
        show={isVisible}
        onHide={() => handleCancelEdit(this)}
        size="lg"
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
        className="organism-edit-ucg-library-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label>{t("edit_ucg_library")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => handleCancelEdit(this)}
          />
        </Modal.Header>
        <Modal.Body className="ucg-library-organism-modal-body">
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
          <Row className="mb-3">
            <Col>
              <InputWithLabel
                label={t("video_file")}
                name="video_file"
                typeName="file"
                accept="video/*"
                onChange={(event) => handleFile(event, this)}
              />
              <MediaComponent file={video_file} type="video" />
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputWithLabel
                validateError={errorCollection.includes("title")}
                className="labelstyle"
                label={t("title_jp")+t("required_sign")}
                name="title"
                typeName="text"
                onChange={(event) => handleChangeForm(event, this)}
                value={title || ""}
              />
              {<Span className="error">{errors["title"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputWithLabel
                validateError={errorCollection.includes("title_en")}
                className="labelstyle"
                label={t("title_en")+t("required_sign")}
                name="title_en"
                typeName="text"
                onChange={(event) => handleChangeForm(event, this)}
                value={title_en || ""}
              />
              {<Span className="error">{errors["title_en"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <Label className="labelstyle">{t("normal_abnormal")}</Label>
            </Col>
            <Col xs={9}>
              <Span className="ml-2">
                <InputRadio
                  title={t("normal")}
                  name="normal_abnormal"
                  defaultChecked={normal_abnormal == 1 ? "checked" : ""}
                  value={1}
                  onClick={(event) => handleChangeForm(event, this)}
                />

                <InputRadio
                  title={t("abnormal")}
                  name="normal_abnormal"
                  defaultChecked={normal_abnormal == 0 ? "checked" : ""}
                  value={0}
                  onClick={(event) => handleChangeForm(event, this)}
                />
              </Span>
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputDescription
                // validateError={errorCollection.includes("ucg_explanation")}
                className="labelstyle"
                label={t("ucg_explanation_jp")}
                name="ucg_explanation"
                value={ucg_explanation || ""}
                typeName="textarea"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {/* {<Span className="error">{errors["ucg_explanation"]}</Span>} */}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputDescription
                // validateError={errorCollection.includes("ucg_explanation_en")}
                className="labelstyle"
                label={t("ucg_explanation_en")}
                name="ucg_explanation_en"
                typeName="textarea"
                value={ucg_explanation_en || ""}
                onChange={(event) => handleChangeForm(event, this)}
              />
              {/* {<Span className="error">{errors["ucg_explanation_en"]}</Span>} */}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <Label className="labelstyle ucg-library-modal-item">
                {t("status")}
              </Label>
            </Col>
            <Col xs={9}>
              <Span className="ml-2">
                <InputRadio
                  title={t("public")}
                  name="status"
                  defaultChecked={status == 2 || status == 3 ? "checked" : ""}
                  value={3}
                  onClick={(event) => handleChangeForm(event, this)}
                />
                <InputRadio
                  title={t("private")}
                  name="status"
                  defaultChecked={status == 0 || status == 1 ? "checked" : ""}
                  value={1}
                  onClick={(event) => handleChangeForm(event, this)}
                />
              </Span>
            </Col>
          </Row>

          {/* <Row className="mb-1">
            <Col>
              <Label className="ucg-edit-modal-item"> {t("group_attr")}</Label>
            </Col>
            <Col xs={9}>
              <Span>
                <InputRadio
                  className="ml-2 mr-2"
                  title={t("radio_yes")}
                  name="group_attribute"
                  defaultChecked={group_attribute == 0 ? "checked" : ""}
                  value={0}
                  onClick={(event) => handleChangeForm(event, this)}
                />

                <Select2
                  defaultValue={0} // or as string | array
                  data={exam_groups}
                  onSelect={(selectedList, selectedItem) =>
                    onSelect(
                      selectedList,
                      selectedItem,
                      this,
                      "selected_exam_group"
                    )
                  }
                />
                <InputRadio
                  className="ml-2"
                  title={t("radio_none")}
                  name="group_attribute"
                  defaultChecked={group_attribute == 1 ? "checked" : ""}
                  value={1}
                  onClick={(event) => handleChangeForm(event, this)}
                />
              </Span>
              <Row className="ml-1">
                <ExamGroupItem
                  selected_exam_group={selected_exam_group}
                  onClick={(index) =>
                    removeItem(index, this, "selected_exam_group")
                  }
                />
              </Row>
            </Col>
          </Row> */}
        </Modal.Body>
        <Modal.Footer className="organism-edit-modal-footer">
          <Row>
            <Col md={5} xs={5}>
              <Button
                mode="active"
                onClick={() => submitData(this)}
                className="btn-block text-center organism-edit-modal-button"
              >
                {t("ok_btn")}
              </Button>
            </Col>
            <Col md={4} xs={4}>
              <Button
                mode="cancel"
                onClick={() => handleCancelEdit(this)}
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
 * fetch quizpack
 * @param {*} context
 */
const handleFetchData = async (context) => {
  await context.props.getLibraryUser(context.props.userToken);
  await context.props.getExamGroup(context.props.userToken);
  let exam_groups = [];
  let user_list = [];
  if (context.props.examGroup && !context.props.examGroup.isLoading) {
    exam_groups = [
      ...context.props.examGroup.examGroupList.map((exam_group) => ({
        id: exam_group.id,
        text: exam_group.name,
      })),
    ];
  }
  if (
    context.props.libraryUserList &&
    !context.props.libraryUserList.isLoading
  ) {
    user_list = [
      ...context.props.libraryUserList.user_list.map((list) => ({
        id: list.id,
        value: list.name,
      })),
    ];
  }
  context.setState({
    exam_groups,
    user_list,
  });
};

/**
 * set form values
 * @param {*} context
 */
const handleSetFormValues = async (context) => {
  const { editItem } = context.props;
  context.setState({
    id: editItem.ID,
    group_attribute:
      editItem.selected_exam_group && editItem.selected_exam_group.length > 0
        ? 0
        : 1,
    normal_abnormal: editItem.normal_abnormal === "normal" ? 1 : 0,
    selected_exam_group: editItem.selected_exam_group || [],
    //"soundtype"
    status: getStatusValue(editItem.status),
    supervisor_comment: editItem.supervisor_comment,
    title: editItem.title || null,
    title_en: editItem.title_en || null,
    ucg_explanation: editItem.ucg_explanation || null,
    ucg_explanation_en: editItem.ucg_explanation_en || null,
    imageHash: Date.now(),
    video_file: editItem.video_file,
  });
};

/**
 * select image file
 * @param {*} event
 * @param {*} context
 */
const handleFile = (event, context) => {
  context.setState({ [event.target.name]: event.target.files[0] });
};

/**
 * close modal
 * @param {*} context
 */
const handleCancelEdit = (context) => {
  context.setState({
    ...initial_state,
  });
  context.props.onHideEditModal();
};

/**
 * get status value
 * @param {*} value
 */
const getStatusValue = (value) => {
  switch (value) {
    case "Supervised":
      return 1;
    case "Under supervision":
      return 0;
    case "Now open":
      return 2;
    case "Now open (New)":
      return 3;
  }
};

/**
 * select file
 * @param {*} e
 * @param {*} context
 * @param {*} type
 */
const handleSelect = async (e, context, type) => {
  await context.setState({ [type]: e.target.value });
};

/**
 * handle form input change
 * @param {*} value
 * @param {*} context
 */
const handleChangeForm = (event, context) => {
  context.setState({ [event.target.name]: event.target.value });
};

/**
 * select group attribute items
 * @param {*} selectedList
 * @param {*} selectedItem
 * @param {*} context
 * @param {*} type
 */
const onSelect = (selectedList, selectedItem, context, type) => {
  const isItemSelected = context.state.selected_exam_group.find(
    (item) => item.id === parseInt(selectedList.params.data.id)
  );
  if (!isItemSelected) {
    context.setState({
      [type]: Array.from(
        new Set([
          ...context.state[type],
          {
            ...selectedList.params.data,
            name: selectedList.params.data.text,
            id: parseInt(selectedList.params.data.id),
          },
        ])
      ),
    });
  }
};

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
    ucg_explanation,
    ucg_explanation_en,
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

  //ucg_explanation
  // if (!ucg_explanation || ucg_explanation.trim.length < 0) {
  //   formIsValid = false;
  //   errors["ucg_explanation"] = t("validate_description_jp");
  // }

  //ucg_explanation_en
  // if (!ucg_explanation_en || ucg_explanation_en.trim.length < 0) {
  //   formIsValid = false;
  //   errors["ucg_explanation_en"] = t("validate_description_en");
  // }

  context.setState({ errors: errors });
  return formIsValid;
};

/**
 * update data
 * @param {*} context
 */
const submitData = (context) => {
  const ucg_data = {
    ...context.state,
  };
  const isValidated = handleValidate(context);
  if (isValidated) {
    context.props.updateUcgData(ucg_data, context.props.editItem.ID);
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
    currentUniversity: state.auth.userInfo.user.university_id,
    examGroup: state.examGroup,
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    libraryUserList: state.LibraryUserList,
  };
};

//===================================================
// export
//===================================================
export default connect(mapStateToProps, { getExamGroup, getLibraryUser })(
  withTranslation("translation")(UcgLibraryEdit)
);
