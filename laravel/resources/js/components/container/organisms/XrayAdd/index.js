import React from "react";

// libs
import { connect } from "react-redux";
import { Row, Col, Modal } from "react-bootstrap";
import { DropzoneArea } from "material-ui-dropzone";
import Select2 from "react-select2-wrapper";

// Components
import Div from "../../../presentational/atoms/Div";
import P from "../../../presentational/atoms/P";
import Button from "../../../presentational/atoms/Button";
import InputRadio from "../../../presentational/molecules/RadioWithLabel";
import Image from "../../../presentational/atoms/Image";
import Label from "../../../presentational/atoms/Label";
import InputWithLabel from "../../../presentational/molecules/InputWithLabel";
import InputDescription from "../../../presentational/molecules/InputDescription"
import Span from "../../../presentational/atoms/Span";
import ExamGroupItem from "../../../presentational/molecules/ExamGroupItem";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

// redux
import { getExamGroup } from "../../../../redux/modules/actions/ExamGroupAction";
import { addXray } from "../../../../redux/modules/actions/XrayLibraryAction";
import { getLibraryUser } from "../../../../redux/modules/actions/LibraryUserAction";

// i18n
import { withTranslation } from "react-i18next";

// css
import "./style.css";

//===================================================
// Component
//===================================================
const initial_state = {
  image_file: null,
  title: null,
  title_en: null,
  normal_abnormal: 1,
  xray_explanation: null,
  xray_explanation_en: null,
  status: 1,
  user_list: [],
  supervisor: 0,
  errors: {},
  exam_groups: [],
  selected_exam_group: [],
  group_attribute: 1,
  supervisor_comment: "",
};

class XrayLibraryAdd extends React.Component {
  constructor(props) {
    super(props);
    this.state = initial_state;
  }

  componentDidMount() {
    handleFetchExamGroup(this);
  }

  componentDidUpdate(prevProps) {
    if (this.props.isVisible && !prevProps.isVisible) {
      handleFetchExamGroup(this);
    }
  }

  render() {
    const { isVisible, t } = this.props;
    const {
      errors,
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
        onHide={() => handleCancelAdd(this)}
        size="lg"
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
        className="organism-add-xray-library-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label>{t("add_xray_library")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => handleCancelAdd(this)}
          />
        </Modal.Header>
        <Modal.Body className="xray-library-organism-modal-body">
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
          {/* <Row className="mb-1">
            <Col>
              <Label
                className="labelstyle"
                labelError={errorCollection.includes("image_file")}
              >
                {t("image_file")}
              </Label>
            </Col>
            <Col xs={9}>
              <Span className="input-item ">
                <DropzoneArea
                  dropzoneText={t("dropzoneText")}
                  acceptedFiles={[".jpg,.png,image/jpeg,image/png"]}
                  showAlerts={false}
                  filesLimit={1}
                  showPreviews={true}
                  showPreviewsInDropzone={false}
                  dropzoneText={t("dropzone_area_text")}
                  previewText=""
                  onChange={(files) =>
                    this.setState({
                      image_file: files[0],
                    })
                  }
                />
                {<Span className="error">{errors["image_file"]}</Span>}
              </Span>
            </Col>
          </Row> */}
        
        <Row className="mb-1 mt-2">
            <Col>
              <InputWithLabel
                className="labelstyle"
                validateError={errorCollection.includes("image_file")}
                label={t("image_file")+t("required_sign")}
                name="image_file"
                accept={[".jpg,.png,image/jpeg,image/png"]}
                typeName="file"
                onChange={e => 
                this.setState({
                  image_file: e.target.files[0]
                })}
              />
              {<Span className="error">{errors["image_file"]}</Span>}
              {this.state.image_file && (
                <Image
                  url={URL.createObjectURL(this.state.image_file)}
                  mode="selected-input" />
              )}
            </Col>
          </Row>
        
         

          <Row className="mb-1 mt-2">
            <Col>
              <InputWithLabel
                className="labelstyle"
                validateError={errorCollection.includes("title")}
                label={t("title_jp")+t("required_sign")}
                name="title"
                typeName="text"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["title"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-2">
            <Col>
              <InputWithLabel
                className="labelstyle"
                validateError={errorCollection.includes("title_en")}
                label={t("title_en")+t("required_sign")}
                name="title_en"
                typeName="text"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["title_en"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-4">
            <Col className="form-item">
              <Label className="xray-add-modal-item labelstyle mr-2">
                {t("normal_abnormal")}
              </Label>
            </Col>
            <Col xs={9}>
              <Span className="ml-1">
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

          <Row className="mb-1">
            <Col>
              <InputDescription
                className="labelstyle"
                // validateError={errorCollection.includes("xray_explanation")}
                label={t("xray_explanation_jp")}
                name="xray_explanation"
                typeName="textarea"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {/* {<Span className="error">{errors["xray_explanation"]}</Span>} */}
            </Col>
          </Row>

          <Row className="mb-1">
            <Col>
              <InputDescription
                className="labelstyle"
                // validateError={errorCollection.includes("xray_explanation_en")}
                label={t("xray_explanation_en")}
                name="xray_explanation_en"
                typeName="textarea"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {/* {<Span className="error">{errors["xray_explanation_en"]}</Span>} */}
            </Col>
          </Row>

          {/* <Row className="mb-1 teast">
            <Col>
              <Label className="labelstyle xray-add-modal-item">
                {t("supervisor")}
              </Label>
            </Col>
            <Col xs={9}>
              <Select
                className="select-style ml-2 "
                items={user_list}
                value={
                  supervisor ||
                  (user_list && user_list.length > 0 && user_list[0].id)
                }
                onChange={(event) => handleSelect(event, this, "supervisor")}
              ></Select>
            </Col>
          </Row> */}

          <Row className=" mt-2 mb-1">
            <Col>
              <Label className="xray-add-modal-item labelstyle">
                {t("status")}
              </Label>
            </Col>
            <Col xs={9}>
              <Span className="ml-1">
                <InputRadio
                  title={t("public")}
                  className="radioStyle"
                  name="status"
                  defaultChecked={status == 2 || status == 3 ? "checked" : ""}
                  value={3}
                  onClick={(event) => handleChangeForm(event, this)}
                />
                <InputRadio
                  title={t("private")}
                  className="radioStyle ml-2"
                  name="status"
                  defaultChecked={status == 0 || status == 1 ? "checked" : ""}
                  value={1}
                  onClick={(event) => handleChangeForm(event, this)}
                />

                {/* <InputRadio
                  title={t("open")}
                  className="radioStyle ml-1 "
                  name="status"
                  defaultChecked={status == 2 ? "checked" : ""}
                  value={2}
                  onClick={(event) => handleChangeForm(event, this)}
                />
                <InputRadio
                  title={t("new")}
                  className="radioStyle ml-1 "
                  name="status"
                  defaultChecked={status == 3 ? "checked" : ""}
                  value={3}
                  onClick={(event) => handleChangeForm(event, this)}
                /> */}
              </Span>
            </Col>
          </Row>

          {/* <Row className="mb-1">
            <Col>
              <Label className="xray-add-modal-item">{t("group_attr")}</Label>
            </Col>
            <Col xs={9}>
              <Span className="ml-1">
                <InputRadio
                  title={t("radio_yes")}
                  name="group_attribute"
                  value={0}
                  defaultChecked={group_attribute == 0 ? "checked" : ""}
                  onClick={(event) => handleChangeForm(event, this)}
                />
                <Select2
                  defaultValue={0} // or as string | array
                  data={exam_groups}
                  onSelect={(selectedList, selectedItem) =>
                    handleSelectItem(
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
              </Span>
              <Row className="ml-1">
                <ExamGroupItem
                  selected_exam_group={selected_exam_group}
                  onClick={(index) =>
                    handleRemoveItem(index, this, "selected_exam_group")
                  }
                />
              </Row>
            </Col>
          </Row> */}
        </Modal.Body>
        <Modal.Footer className="organism-add-modal-footer">
          <Row>
            <Col md={5} xs={5}>
              <Button
                mode="active"
                onClick={() => handleSubmitData(this)}
                className="btn-block text-center organism-add-modal-button"
              >
                {t("ok_btn")}
              </Button>
            </Col>
            <Col md={4} xs={4}>
              <Button
                mode="cancel"
                onClick={() => handleCancelAdd(this)}
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
 * fetch exam groups
 * @param {*} context
 */
const handleFetchExamGroup = async (context) => {
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
 * select radio item
 * @param {*} e
 * @param {*} context
 * @param {*} type
 */
const handleSelect = async (e, context, type) => {
  await context.setState({ [type]: e.target.value });
};

/**
 * cancel add and close modal
 * @param {*} context
 */
const handleCancelAdd = (context) => {
  context.setState({
    ...initial_state,
  });
  context.props.onHideAddModal();
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
 *  select item from dropdown list
 * @param {*} selectedList
 * @param {*} selectedItem
 * @param {*} context
 * @param {*} type
 */
const handleSelectItem = (selectedList, selectedItem, context, type) => {
  context.setState({
    [type]: Array.from(
      new Set([...context.state[type], selectedList.params.data])
    ),
  });
};

/**
 * remove selected item
 * @param {*} index
 * @param {*} context
 * @param {*} type
 */
const handleRemoveItem = async (index, context, type) => {
  let remove_type = context.state[type];
  remove_type.splice(index, 1);
  context.setState({ [remove_type]: remove_type });
};

/**
 * validate input
 * @param {*} context
 */
const handleValidate = (context) => {
  const { image_file, title, title_en } = context.state;
  const { t } = context.props;
  let errors = {};
  let formIsValid = true;
  //image_file
  if (!image_file) {
    formIsValid = false;
    errors["image_file"] = t("validate_image_path");
  }
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
  context.setState({ errors: errors });
  return formIsValid;
};

/**
 * save xray data
 * @param {*} context
 */
const handleSubmitData = async (context) => {
  const { supervisor, user_list } = context.state;
  const xray_data = {
    ...context.state,
    supervisor:
      supervisor || (user_list && user_list.length > 0 && user_list[0].id),
  };
  const isValidated = handleValidate(context);
  if (isValidated) {
    const { userToken, userInfo } = context.props;
    context.props.addXray({ ...xray_data, user_id: userInfo.id }, userToken);
    context.setState({
      ...initial_state,
    });
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
    currentUniversity: state.auth.userInfo.user.university_id,
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
  getLibraryUser,
  getExamGroup,
  addXray,
})(withTranslation("translation")(XrayLibraryAdd));
