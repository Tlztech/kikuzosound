import React from "react";

// libs
import { Row, Col, Modal } from "react-bootstrap";
import { connect } from "react-redux";
import Select2 from "react-select2-wrapper";

// Components
import Div from "../../../presentational/atoms/Div";
import P from "../../../presentational/atoms/P/index";
import Button from "../../../presentational/atoms/Button";
import Image from "../../../presentational/atoms/Image";
import Label from "../../../presentational/atoms/Label";
import InputWithLabel from "../../../presentational/molecules/InputWithLabel";
import InputDescription from "../../../presentational/molecules/InputDescription"
import Span from "../../../presentational/atoms/Span";
import MediaComponent from "../../../presentational/molecules/Media";
import InputRadio from "../../../presentational/molecules/RadioWithLabel";
import ExamGroupItem from "../../../presentational/molecules/ExamGroupItem";

// redux
import { getExamGroup } from "../../../../redux/modules/actions/ExamGroupAction";
import { getLibraryUser } from "../../../../redux/modules/actions/LibraryUserAction";
import { updateInspection } from "../../../../redux/modules/actions/InspectionLibraryAction";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

// css
import "./style.css";
import "react-select2-wrapper/css/select2.css";

//i18n
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================

const initial_state = {
  title: null,
  title_en: null,
  description: "",
  description_en: "",
  exam_groups: [],
  selected_exam_group: [],
  user_list: [],
  sound_file: "",
  is_video: null,
  video_file: null,
  user_id: 0,
  status: 1,
  group_attribute: 1,
  normal_abnormal: 0,
  errors: {},
  hash: "",
};

class InspectionLibraryEdit extends React.Component {
  constructor(props) {
    super(props);
    this.state = initial_state;
  }

  componentDidMount() {
    handleFetchExamGroup(this);
    handleSetFormValues(this);
  }

  componentDidUpdate(prevProps) {
    if (this.props.isVisible && !prevProps.isVisible) {
      handleFetchExamGroup(this);
      handleSetFormValues(this);
    }
  }

  render() {
    const { isVisible, t } = this.props;
    const {
      errors,
      title,
      title_en,
      description,
      description_en,
      video_file,
      sound_file,
      is_video,
      normal_abnormal,
      status,
      exam_groups,
      group_attribute,
      selected_exam_group,
    } = this.state;
    const errorCollection = Object.keys(errors);
    const errorValue = Object.values(errors);
    return (
      <Modal
        show={isVisible}
        onHide={() => handleCancelModal(this)}
        size="lg"
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
        className="organism-edit-inspection-library-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label>{t("edit_inspection_library")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => handleCancelModal(this)}
          />
        </Modal.Header>
        <Modal.Body className="inspection-library-organism-modal-body">
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
                validateError={errorCollection.includes("sound_file")}
                label={t("sound_file")}
                name="sound_file"
                typeName="file"
                accept="audio/*"
                onChange={(event) => handleUploadFile(event, this)}
              />
              {sound_file && (
                <MediaComponent
                  file={sound_file}
                  type="sound"
                  hash={this.state.hash}
                />
              )}
              {<Span className="error">{errors["sound_file"]}</Span>}
            </Col>
          </Row>
          {(
            typeof sound_file === "object"
              ? sound_file.type.includes("mp4")
              : sound_file
              ? sound_file.includes("mp4")
              : false
          ) ? (
            <Row className="mb-3">
              <Col className="form-item">
                <Label className="labelstyle inspection-add-modal-item mr-2">
                  {t("video")}
                </Label>
              </Col>
              <Col xs={9} className="organism-inspection-radio-container">
                <Span>
                  <InputRadio
                    title={t("release")}
                    className="ml-1"
                    name="is_video"
                    defaultChecked={is_video == 1 ? "checked" : ""}
                    value={1}
                    onClick={(event) => handleChangeForm(event, this)}
                  />
                  <InputRadio
                    title={t("private")}
                    className="ml-2"
                    name="is_video"
                    defaultChecked={is_video == 0 ? "checked" : ""}
                    value={0}
                    onClick={(event) => handleChangeForm(event, this)}
                  />
                </Span>
              </Col>
            </Row>
          ) : null}
          <Row className="mb-3">
            <Col>
              <InputWithLabel
                label={t("video_file")}
                name="video_file"
                typeName="file"
                accept="video/*"
                onChange={(event) => handleUploadFile(event, this)}
              />
              {video_file && (
                <MediaComponent
                  height="240"
                  width="320"
                  file={video_file}
                  hash={this.state.hash}
                  type="video"
                />
              )}
            </Col>
          </Row>
          <Row className="mb-3">
            <Col>
              <InputWithLabel
                validateError={errorCollection.includes("title")}
                label={t("title_jp")+t("required_sign")}
                typeName="text"
                name="title"
                value={title || ""}
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["title"]}</Span>}
            </Col>
          </Row>
          <Row className="mb-3">
            <Col>
              <InputWithLabel
                validateError={errorCollection.includes("title_en")}
                label={t("title_en")+t("required_sign")}
                typeName="text"
                name="title_en"
                value={title_en || ""}
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["title_en"]}</Span>}
            </Col>
          </Row>
          <Row className="mb-3">
            <Col className="form-item">
              <Label className="labelstyle inspection-add-modal-item mr-2">
                {t("normal_abnormal")}
              </Label>
            </Col>
            <Col xs={9} className="organism-inspection-radio-container">
              <InputRadio
                title={t("normal")}
                name="normal_abnormal"
                value={1}
                defaultChecked={normal_abnormal == 1 ? "checked" : ""}
                onClick={(event) => handleChangeForm(event, this)}
              />
              <InputRadio
                title={t("abnormal")}
                name="normal_abnormal"
                value={0}
                defaultChecked={normal_abnormal == 0 ? "checked" : ""}
                onClick={(event) => handleChangeForm(event, this)}
              />
            </Col>
          </Row>
          <Row className="mb-3">
            <Col>
              <InputDescription
                // validateError={errorCollection.includes("description")}
                label={t("descriptions_jp")}
                name="description"
                typeName="textarea"
                value={description || ""}
                onChange={(event) => handleChangeForm(event, this)}
              />
            </Col>
          </Row>
          {/* <Span className="error">{errors["description"]}</Span>{" "} */}
          <Row className="mb-3">
            <Col>
              <InputDescription
                // validateError={errorCollection.includes("description_en")}
                label={t("descriptions_en")}
                name="description_en"
                typeName="textarea"
                value={description_en || ""}
                onChange={(event) => handleChangeForm(event, this)}
              />
            </Col>
          </Row>
          {/* <Span className="error">{errors["description_en"]}</Span> */}
          {/* <Row className="mb-1 teast">
            <Col>
              <Label className="labelstyle xray-add-modal-item">
                {t("supervisor")}
              </Label>
            </Col>
            <Col xs={9}>
              <Select
                className="select-style "
                items={user_list || []}
                value={
                  user_id ||
                  (user_list && user_list.length > 0 && user_list[0].id)
                }
                onChange={(event) => handleSelect(event, this, "user_id")}
              />
            </Col>
          </Row> */}
          <Row className="organism-inspection-status-wrapper">
            <Col className="form-item">
              <Label className="labelstyle inspection-add-modal-item mr-2">
                {t("status")}
              </Label>
            </Col>
            <Col xs={9} className="organism-inspection-radio-container">
              <InputRadio
                title={t("public")}
                name="status"
                defaultChecked={status == 2 || status == 3 ? "checked" : ""}
                onClick={(event) => handleChangeForm(event, this)}
                value={3}
              />
              <InputRadio
                title={t("private")}
                className="ml-2"
                name="status"
                defaultChecked={status == 0 || status == 1 ? "checked" : ""}
                onClick={(event) => handleChangeForm(event, this)}
                value={1}
              />
            </Col>
          </Row>
          {/* <Row className="mb-1">
            <Col>
              <Label className="labelstyle inspection-add-modal-item ">
                {t("group_attributes")}
              </Label>
            </Col>
            <Col xs={9} className="organism-inspection-radio-container">
              <InputRadio
                title={t("yes")}
                name="group_attribute"
                value={0}
                defaultChecked={group_attribute == 0 ? "checked" : ""}
                onClick={(event) => handleChangeForm(event, this)}
              />
              <Select2
                defaultValue={0} // or as string | array
                data={exam_groups}
                onSelect={(selectedList, selectedItem) =>
                  handleSelectExamGroup(
                    selectedList,
                    selectedItem,
                    this,
                    "selected_exam_group"
                  )
                }
              />
              <InputRadio
                title={t("none")}
                className="ml-2"
                name="group_attribute"
                value={1}
                defaultChecked={group_attribute == 1 ? "checked" : ""}
                onClick={(event) => handleChangeForm(event, this)}
              />
              <Col className="organism-inspection-group-attribute-item">
                {selected_exam_group && selected_exam_group.length > 0 && (
                  <ExamGroupItem
                    selected_exam_group={selected_exam_group}
                    onClick={(index) =>
                      handleRemoveExamGroup(index, this, "selected_exam_group")
                    }
                  />
                )}
              </Col>
            </Col>
          </Row> */}
        </Modal.Body>
        <Modal.Footer className="organism-add-modal-footer">
          <Row>
            <Col md={5} xs={5}>
              <Button
                mode="active"
                onClick={() => handleUpdateInspectionData(this)}
                className="btn-block text-center organism-add-modal-button"
              >
                {t("ok_btn")}
              </Button>
            </Col>
            <Col md={4} xs={4}>
              <Button
                mode="cancel"
                onClick={() => handleCancelModal(this)}
                className="btn-block text-center organism-add-modal-button"
              >
                {t("cancel")}
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
 * set form values
 * @param {*} context
 */
const handleSetFormValues = async (context) => {
  
  const { editItem } = context.props;
  context.setState({
    title: editItem.title || null,
    title_en: editItem.title_en || null,
    description: editItem.description || null,
    description_en: editItem.description_en || null,
    selected_exam_group:
      (editItem.exam_groups &&
        editItem.exam_groups.map((item) => {
          return {
            id: item.id,
            text: item.name,
          };
        })) ||
      [],
    sound_file: editItem.item.sound_path,
    video_file: editItem.item.video_path,
    user_id: editItem.user_id,
    status: editItem.status,
    group_attribute:
      editItem.exam_groups && editItem.exam_groups.length > 0 ? 0 : 1,
    normal_abnormal: editItem.item.is_normal,
    supervisor_comment: editItem.supervisor_comment || "",
    id: editItem.id,
    is_video: editItem.is_video_show,
  });
};

/**
 * fetch exam groups
 * @param {*} context
 */
const handleFetchExamGroup = async (context) => {
  const { userToken } = context.props;
  await context.props.getLibraryUser(userToken);
  await context.props.getExamGroup(userToken);
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
  if (context.props.userList && !context.props.userList.isLoading) {
    user_list = [
      ...context.props.userList.user_list.map((list) => ({
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
 * handle select
 * @param {*} e
 * @param {*} context
 * @param {*} type
 */
const handleSelect = async (e, context, type) => {
  await context.setState({ [type]: e.target.value });
};

/**
 * handle selected group attributes
 * @param {*} selectedList
 * @param {*} selectedItem
 * @param {*} context
 * @param {*} type
 */
const handleSelectExamGroup = (selectedList, selectedItem, context, type) => {
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

/**
 * remove selected item
 * @param {*} index
 * @param {*} context
 * @param {*} type
 */
const handleRemoveExamGroup = async (index, context, type) => {
  let remove_type = context.state[type];
  remove_type.splice(index, 1);
  context.setState({ [remove_type]: remove_type });
};

/**
 * handle input changes
 * @param {*} event
 * @param {*} context
 */
const handleChangeForm = (event, context) => {
  context.setState({ [event.target.name]: event.target.value });
};

/**
 * get file url
 * @param {*} event
 * @param {*} context
 */
const handleUploadFile = (event, context) => {
  context.setState({
    [event.target.name]: event.target.files[0],
    hash: Date.now(),
  });
};

/**
 * handle on click cancel
 * @param {*} context
 */
const handleCancelModal = (context) => {
  context.setState({
    ...initial_state,
  });
  context.props.onHideEditModal();
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
    sound_file,
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
  // if (!description || description.trim.length < 0) {
  //   formIsValid = false;
  //   errors["description"] = t("validate_description_jp");
  // }

  //description_en
  // if (!description_en || description_en.trim.length < 0) {
  //   formIsValid = false;
  //   errors["description_en"] = t("validate_description_en");
  // }

  //sound_format
  if (typeof sound_file === "object") {
    if (sound_file.name.includes("mp3") || sound_file.name.includes("mp4")) {
    } else {
      {
        formIsValid = false;
        errors["sound_file"] = t("validate_sound_format");
      }
    }
  }
  context.setState({ errors: errors });
  return formIsValid;
};

/**
 * update inspection library
 * @param {*} context
 */
const handleUpdateInspectionData = (context) => {
  const quiz_data = {
    ...context.state,
  };
  const isValidated = handleValidate(context);
  if (isValidated) {
    const { userToken, userInfo } = context.props;
    context.props.updateInspection(
      { ...quiz_data, user_id: userInfo.id },
      userToken
    );
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
    currentUniversity:
      state.auth.userInfo && state.auth.userInfo.user.university_id,
    examGroup: state.examGroup,
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    userInfo: state.auth.userInfo && state.auth.userInfo.user,
    userList: state.LibraryUserList,
  };
};

//===================================================
// export
//===================================================
export default connect(mapStateToProps, {
  getExamGroup,
  getLibraryUser,
  updateInspection,
})(withTranslation("translation")(InspectionLibraryEdit));
