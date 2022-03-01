import React from "react";

// libs
import { connect } from "react-redux";
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
import InputRadio from "../../../presentational/molecules/RadioWithLabel";
import MediaComponent from "../../../presentational/molecules/Media";
import ExamGroupItem from "../../../presentational/molecules/ExamGroupItem";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

// redux
import { getExamGroup } from "../../../../redux/modules/actions/ExamGroupAction";

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
  is_video: 1,
  title: null,
  title_en: null,
  normal_abnormal: 1,
  description: "",
  description_en: "",
  supervisor: "0",
  user_list: [],
  supervisor_comment: "",
  status: 1,
  exam_groups: [],
  group_attribute: 1,
  selected_exam_group: [],
  errors: {},
};

class PalpationLibraryAdd extends React.Component {
  constructor(props) {
    super(props);
    this.state = initial_state;
  }
  async componentDidMount() {
    await this.props.getExamGroup(this.props.userToken);
  }

  componentDidUpdate(prevProps) {
    if (this.props.isVisible && !prevProps.isVisible) {
      handleFetchData(this);
    }
  }

  render() {
    const { isVisible, t } = this.props;
    const {
      supervisor,
      user_list,
      exam_groups,
      selected_exam_group,
      is_video,
      errors,
      sound_file,
      normal_abnormal,
      video_file,
      group_attribute,
      status,
    } = this.state;
    const errorCollection = Object.keys(errors);
    const errorValue = Object.values(errors);
    return (
      <Modal
        show={isVisible}
        onHide={() => handleCloseModal(this)}
        size="lg"
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
        className="organism-add-palpation-library-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label>{t("palpation_lib:add_palpation_lib")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => handleCloseModal(this)}
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
              <MediaComponent file={sound_file} type="sound" />
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
              />
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputWithLabel
                validateError={errorCollection.includes("title")}
                label={t("translation:title_jp")+t("required_sign")}
                typeName="text"
                name="title"
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
                typeName="text"
                name="title_en"
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
                typeName="textarea"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["description_en"]}</Span>}
            </Col>
          </Row>

          <Div className="mb-3">
            <Label className="labelstyle palpation-library-modal-item">
              {t("translation:normal_abnormal")}
            </Label>
            <Span className="radio-select-margin">
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
          </Div>

          <Div className="mb-3">
            <Label className="labelstyle palpation-library-modal-item ">
              {t("status")}
            </Label>
            <Span className="radio-select-margin">
              <InputRadio
                title={t("public")}
                name="status"
                defaultChecked={status == 2 || status == 3 ? "checked" : ""}
                value={3}
                onClick={(event) => handleChangeForm(event, this)}
              />
              <InputRadio
                title={t("private")}
                className="radioStyle"
                name="status"
                defaultChecked={status == 0 || status == 1 ? "checked" : ""}
                value={1}
                onClick={(event) => handleChangeForm(event, this)}
              />
            </Span>
          </Div>

          {/* <Row className="mb-1">
            <Col>
              <Label className="labelstyle palpation-add-modal-item">
                {t("group_attributes")}
              </Label>
            </Col>
            <Col xs={9} className="organism-palpation-radio-container">
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
                name="group_attribute"
                value={1}
                defaultChecked={group_attribute == 1 ? "checked" : ""}
                onClick={(event) => handleChangeForm(event, this)}
              />
              <Col className="organism-palpation-group-attribute-item">
                <ExamGroupItem
                  selected_exam_group={selected_exam_group}
                  onClick={(index) =>
                    handleRemoveExamGroup(index, this, "selected_exam_group")
                  }
                />
              </Col>
            </Col>
          </Row> */}
        </Modal.Body>
        <Modal.Footer className="organism-add-modal-footer">
          <Row>
            <Col md={5} xs={5}>
              <Button
                mode="active"
                onClick={() => handleSubmitPalpationData(this)}
                className="btn-block text-center organism-add-modal-button"
              >
                {t("ok_btn")}
              </Button>
            </Col>
            <Col md={4} xs={4}>
              <Button
                mode="cancel"
                onClick={() => handleCloseModal(this)}
                className="btn-block text-center organism-add-modal-button"
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
 * fetch exam groups and users
 * @param {*} context
 */
const handleFetchData = async (context) => {
  let exam_groups = [];
  let user_list = [];
  if (!context.props.examGroup.isLoading) {
    exam_groups = [
      ...context.props.examGroup.examGroupList.map((exam_group) => ({
        id: exam_group.id,
        text: exam_group.name,
      })),
    ];
  }
  if (!context.props.userList.isLoading) {
    user_list = [
      ...context.props.userList.user_list.map((list) => ({
        id: list.id,
        value: list.name,
      })),
    ];
  }
  context.setState(
    {
      exam_groups,
      user_list,
    },
    () =>
      context.setState({
        supervisor: user_list[0].id,
      })
  );
};
/**
 * select item from list
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
 * get upload file url
 * @param {*} event
 * @param {*} context
 */
const handleFile = (event, context) => {
  context.setState({ [event.target.name]: event.target.files[0] });
};

/**
 * select exam group item
 * @param {*} selectedList
 * @param {*} selectedItem
 * @param {*} context
 * @param {*} type
 */
const handleSelectExamGroup = (selectedList, selectedItem, context, type) => {
  context.setState({
    [type]: Array.from(
      new Set([...context.state[type], selectedList.params.data])
    ),
  });
};

/**
 * close add modal
 * @param {*} context
 */
const handleCloseModal = (context) => {
  context.setState({
    ...initial_state,
  });
  context.props.onHideAddModal();
};

/**
 * remove exam group item
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
 * Submit Palpation data
 * @param {*} context
 */
const handleSubmitPalpationData = (context) => {
  const quiz_data = {
    ...context.state,
  };
  const isValidated = handleValidate(context);
  if (isValidated) {
    context.props.addPalpationData(quiz_data);
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
    currentUniversity:
      state.auth.userInfo && state.auth.userInfo.user.university_id,
    examGroup: state.examGroup,
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
  };
};

//===================================================
// export
//===================================================
export default connect(mapStateToProps, { getExamGroup })(
  withTranslation("translation")(PalpationLibraryAdd)
);
