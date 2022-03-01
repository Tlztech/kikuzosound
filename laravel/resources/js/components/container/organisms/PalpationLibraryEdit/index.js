import React from "react";
import { connect } from "react-redux";

// libs
import { Row, Col, Modal } from "react-bootstrap";
import Select2 from "react-select2-wrapper";

// Components
import Div from "../../../presentational/atoms/Div";
import P from "../../../presentational/atoms/P";
import Button from "../../../presentational/atoms/Button";
import Image from "../../../presentational/atoms/Image";
import Label from "../../../presentational/atoms/Label";
import InputWithLabel from "../../../presentational/molecules/InputWithLabel";
import InputDescription from "../../../presentational/molecules/InputDescription"
import Span from "../../../presentational/atoms/Span";
import MediaComponent from "../../../presentational/molecules/Media";
import InputRadio from "../../../presentational/molecules/RadioWithLabel";
import ExamGroupItem from "../../../presentational/molecules/ExamGroupItem";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

// redux
import { getExamGroup } from "../../../../redux/modules/actions/ExamGroupAction";
import { getLibraryUser } from "../../../../redux/modules/actions/LibraryUserAction";

// css
import "./style.css";
import "react-select2-wrapper/css/select2.css";

//i18
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================

const initial_state = {
  sound_file: "",
  video_file: null,
  is_video: null,
  title: null,
  title_en: null,
  normal_abnormal: 1,
  description: null,
  description_en: null,
  user_id: "0",
  user_list: [],
  supervisor_comment: "",
  status: null,
  exam_groups: [],
  group_attribute: 1,
  selected_exam_group: [],
  errors: {},
  hash: Date.now(),
};

class PalpationLibraryEdit extends React.Component {
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
      sound_file,
      video_file,
      title,
      title_en,
      normal_abnormal,
      description,
      description_en,
      is_video,
      status,
      exam_groups,
      group_attribute,
      selected_exam_group,
      errors,
      hash,
    } = this.state;
    const errorCollection = Object.keys(errors);
    const errorValue = Object.values(errors);
    return (
      <Modal
        show={isVisible}
        onHide={() => cancelEdit(this)}
        size="lg"
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
        className="organism-edit-palpation-library-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label>{t("palpation_lib:edit_palpation_lib")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => cancelEdit(this)}
          />
        </Modal.Header>
        <Modal.Body className="palpation-library-organism-modal-body">
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
                label={t("palpation_lib:sound_file")}
                name="sound_file"
                typeName="file"
                accept="audio/*"
                onChange={(event) => handleFile(event, this)}
              />
              {sound_file && (
                <MediaComponent file={sound_file} type="sound" hash={hash} />
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
            <Div className="mb-3">
              <Label className="labelstyle palpation-library-modal-item">
                {t("video")}
              </Label>
              <Span className="radio-select-margin">
                <InputRadio
                  title={t("release")}
                  className="radioStyle"
                  name="is_video"
                  defaultChecked={is_video == 1 ? "checked" : ""}
                  value={1}
                  onClick={(event) => handleChangeForm(event, this)}
                />
                <InputRadio
                  title={t("private")}
                  className="radioStyle"
                  name="is_video"
                  defaultChecked={is_video == 0 ? "checked" : ""}
                  value={0}
                  onClick={(event) => handleChangeForm(event, this)}
                />
              </Span>
            </Div>
          ) : null}
          <Row className="mb-3">
            <Col>
              <InputWithLabel
                label={t("palpation_lib:video_file")}
                name="video_file"
                typeName="file"
                accept="video/*"
                onChange={(event) => handleFile(event, this)}
              />
              <MediaComponent
                height="240"
                width="320"
                file={video_file}
                type="video"
                hash={hash}
              />
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputWithLabel
                validateError={errorCollection.includes("title")}
                label={t("translation:title_jp")+t("required_sign")}
                name="title"
                value={title || ""}
                typeName="text"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["title"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputWithLabel
                validateError={errorCollection.includes("title_en")}
                label={t("translation:title_en")+t("required_sign")}
                name="title_en"
                value={title_en || ""}
                typeName="text"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["title_en"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputDescription
                validateError={errorCollection.includes("description")}
                label={t("translation:description_jp")}
                name="description"
                value={description || ""}
                typeName="textarea"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["description"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputDescription
                validateError={errorCollection.includes("description_en")}
                label={t("translation:description_en")}
                name="description_en"
                value={description_en || ""}
                typeName="textarea"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["description_en"]}</Span>}
            </Col>
          </Row>

          <Div className="mb-3 grid-item">
            <Label className="labelstyle palpation-library-modal-item">
              {t("translation:normal_abnormal")}
            </Label>
            <Span className="radio-select-margin">
              <InputRadio
                title={t("normal")}
                className="mr-2"
                name="normal_abnormal"
                defaultChecked={normal_abnormal == 1 ? "checked" : ""}
                value={1}
                onClick={(event) => handleChangeForm(event, this)}
              />
              <InputRadio
                className="mr-2"
                title={t("abnormal")}
                name="normal_abnormal"
                defaultChecked={normal_abnormal == 0 ? "checked" : ""}
                value={0}
                onClick={(event) => handleChangeForm(event, this)}
              />
            </Span>
          </Div>

          {/* <Row className="mb-1 teast">
            <Col>
              <Label className="labelstyle palpation-library-modal-item">
                {t("supervisor")}
              </Label>
            </Col>
            <Col xs={9}>
              <Select
                className="select-style"
                items={user_list}
                value={user_id}
                onChange={(event) => handleSelect(event, this, "user_id")}
              ></Select>
            </Col>
          </Row> */}

          {/* <Row className="mb-1">
            <Col>
              <InputWithLabel
                label={t("supervisor_comment")}
                typeName="textarea"
                name="supervisor_comment"
                value={supervisor_comment || ""}
                onChange={(event) => handleChangeForm(event, this)}
              />
            </Col>
          </Row> */}

          <Div className="mb-3 grid-item">
            <Label className="labelstyle palpation-library-modal-item">
              {t("status")}
            </Label>
            <Span className="radio-select-margin">
              <InputRadio
                title={t("public")}
                className="radioStyle mr-1"
                name="status"
                defaultChecked={status == 2 || status == 3 ? "checked" : ""}
                value={3}
                onClick={(event) => handleChangeForm(event, this)}
              />
              <InputRadio
                title={t("private")}
                className="radioStyle ml-1"
                name="status"
                defaultChecked={status == 0 || status == 1 ? "checked" : ""}
                value={1}
                onClick={(event) => handleChangeForm(event, this)}
              />

              {/* <InputRadio
                title={t("now_open")}
                className="radioStyle ml-1 "
                name="status"
                defaultChecked={status == 2 ? "checked" : ""}
                value={2}
                onClick={(event) => handleChangeForm(event, this)}
              />
              <InputRadio
                title={t("now_open_new")}
                className="radioStyle ml-1 "
                name="status"
                defaultChecked={status == 3 ? "checked" : ""}
                value={3}
                onClick={(event) => handleChangeForm(event, this)}
              /> */}
            </Span>
          </Div>

          {/* <Row className="mb-1">
            <Col>
              <Label className="labelstyle palpation-library-modal-item">
                {t("group_attr")}
              </Label>
            </Col>
            <Col xs={9} className="organism-palpation-radio-container">
              <Span>
                <InputRadio
                  title={t("yes")}
                  name="group_attribute"
                  defaultChecked={group_attribute == 0 ? "checked" : ""}
                  value={0}
                  onClick={(event) => handleChangeForm(event, this)}
                />
                <Select2
                  defaultValue={0}
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
                  title={t("none")}
                  className="ml-2"
                  name="group_attribute"
                  defaultChecked={group_attribute == 1 ? "checked" : ""}
                  value={1}
                  onClick={(event) => handleChangeForm(event, this)}
                />
              </Span>
              <Col className="organism-palpation-group-attribute-item">
                {selected_exam_group && (
                  <ExamGroupItem
                    selected_exam_group={selected_exam_group}
                    onClick={(index) =>
                      removeItem(index, this, "selected_exam_group")
                    }
                  />
                )}
              </Col>
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
    hash: Date.now(),
  });
};

/**
 * set form values
 * @param {*} context
 */
const handleSetFormValues = async (context) => {
  const { editItem } = context.props;
  console.log("PALP",editItem)
  context.setState({
    id: editItem.ID,
    is_video: editItem.is_video,
    description: editItem.description || "",
    description_en: editItem.description_en || "",
    group_attribute: editItem.group_attribute,
    normal_abnormal: editItem.normal_abnormal === "normal" ? 1 : 0,
    selected_exam_group: editItem.selected_exam_group,
    sound_file: editItem.sound_file,
    status: editItem.status,
    supervisor_comment: editItem.supervisor_comment || "",
    title: editItem.title || null,
    title_en: editItem.title_en || null,
    user_id: editItem.user_id,
    video_file: editItem.video_file,
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
 * handle form input change
 * @param {*} value
 * @param {*} context
 */
const handleChangeForm = (event, context) => {
  context.setState({ [event.target.name]: event.target.value });
};

/**
 * handle file
 * @param {*} event
 * @param {*} context
 */
const handleFile = (event, context) => {
  context.setState({
    [event.target.name]: event.target.files[0],
    hash: Date.now(),
  });
};

/**
 * on select
 * @param {*} selectedList
 * @param {*} selectedItem
 * @param {*} context
 * @param {*} type
 */
const onSelect = (selectedList, selectedItem, context, type) => {
  const groupAttributes = Array.from(
    new Set([...context.state[type], selectedList.params.data])
  );
  const filterAttributes = [
    ...new Map(groupAttributes.map((item) => [item["text"], item])).values(),
  ];
  context.setState({
    [type]: filterAttributes,
  });
};

/**
 * cancel edit
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
  const { title, title_en, sound_file } = context.state;
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
 * submit Palpation data
 */
const submitData = (context) => {
  const palpation_data = {
    ...context.state,
  };
  const isValidated = handleValidate(context);
  if (isValidated) {
    context.props.editPalpationData(palpation_data, context.props.editItem.ID);
    context.setState({
      ...initial_state,
    });
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
    libraryUserList: state.LibraryUserList,
  };
};

//===================================================
// export
//===================================================
export default connect(mapStateToProps, { getExamGroup, getLibraryUser })(
  withTranslation("translation")(PalpationLibraryEdit)
);
