import React from "react";
import { connect } from "react-redux";

// libs
import { Row, Col, Modal } from "react-bootstrap";
import { DropzoneArea } from "material-ui-dropzone";
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
import ExamGroupItem from "../../../presentational/molecules/ExamGroupItem";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

// redux
import { getExamGroup } from "../../../../redux/modules/actions/ExamGroupAction";
import { getLibraryUser } from "../../../../redux/modules/actions/LibraryUserAction";
import { updateXray } from "../../../../redux/modules/actions/XrayLibraryAction";

// css
import "./style.css";

// i18n
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================

const initial_state = {
  image_file: "",
  image_file_object: {},
  title: null,
  title_en: null,
  is_normal: 1,
  description: null,
  description_en: null,
  status: 1,
  user_list: [],
  user_id: 0,
  errors: {},
  exam_groups: [],
  selected_exam_group: [],
  normal_abnormal: 0,
  group_attribute: 1,
  supervisor_comment: "",
  imageHash: null
};

class XrayEdit extends React.Component {
  constructor(props) {
    super(props);
    this.state = initial_state;
  }

  componentDidMount() {
    handleFetchExamGroup(this);
    handleFetchData(this);
  }

  componentDidUpdate(prevProps) {
    if (this.props.isVisible && !prevProps.isVisible) {
      handleFetchData(this);
    }
  }

  render() {
    const { isVisible, t } = this.props;
    const {
      errors,
      title,
      title_en,
      is_normal,
      description,
      description_en,
      status,
      image_file_object,
      image_file,
      exam_groups,
      group_attribute,
      selected_exam_group
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
        className="organism-add-xray-library-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label>{t("edit_xray_library")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => cancelEdit(this)}
          />
        </Modal.Header>
        <Modal.Body className="organism-add-xray-library-container">
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
          <Row className="mb-1">
            {/* <Col xs={3}>
              <Label
                className="labelstyle"
                labelError={errorCollection.includes("image_file")}
              >
                {t("image_file")}
              </Label>
            </Col>
            <Col xs={9}>
              <DropzoneArea
                dropzoneText={t("dropzoneText")}
                acceptedFiles={[".jpg,.png,image/jpeg,image/png"]}
                showAlerts={false}
                filesLimit={1}
                showPreviews={true}
                dropzoneText={t("dropzone_area_text")}
                showPreviewsInDropzone={false}
                previewText=""
                onChange={(files) =>
                  this.setState({
                    image_file_object: files[0],
                  })
                }
              />
              {image_file_object == undefined && image_file && (
                <Image
                  url={`${process.env.UNIV_ADMIN_API_URL}${image_file}?${this.state.imageHash}`}
                  className="icon-img"
                />
              )}
              {<Span className="error">{errors["image_file"]}</Span>}
            </Col> */}
            <Col>
              <InputWithLabel
                validateError={errorCollection.includes("image_file")}
                label={t("image_file")+t("required_sign")}
                name="image-file"
                typeName="file"
                onChange={e =>
                  this.setState({
                    image_file: e.target.files[0]
                  })
                }
              />
              {<Span className="error">{errors["image_file"]}</Span>}
              {typeof this.state.image_file == "object" &&
              this.state.image_file ? (
                <Image
                  url={URL.createObjectURL(this.state.image_file)}
                  className="edit-thumbnail-image"
                />
              ) : (
                <Image
                  url={`${image_file}?${this.state.imageHash}`}
                  className="edit-thumbnail-image"
                />
              )}
            </Col>
          </Row>

          <Row className="mb-3 mt-3">
            <Col>
              <InputWithLabel
                className="labelstyle"
                validateError={errorCollection.includes("title")}
                label={t("title_jp")+t("required_sign")}
                typeName="text"
                name="title"
                value={title || ""}
                onChange={event => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["title"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputWithLabel
                className="labelstyle"
                validateError={errorCollection.includes("title_en")}
                label={t("title_en")+t("required_sign")}
                typeName="text"
                name="title_en"
                value={title_en || ""}
                onChange={event => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["title_en"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-1">
            <Col className="form-item">
              <Label className="labelstyle xray-edit-modal-item mr-2">
                {t("normal_abnormal")}
              </Label>
            </Col>
            <Col xs={9}>
              <Span className="itemsa ml-2">
                <InputRadio
                  title={t("normal")}
                  className="radioStyle "
                  name="is_normal"
                  value={1}
                  defaultChecked={is_normal == 1 ? "checked" : ""}
                  onClick={event => handleChangeForm(event, this)}
                />
                <InputRadio
                  title={t("abnormal")}
                  className="radioStyle ml-1 "
                  name="is_normal"
                  value={0}
                  defaultChecked={is_normal == 0 ? "checked" : ""}
                  onClick={event => handleChangeForm(event, this)}
                />
              </Span>
            </Col>
          </Row>

          <Row className=" mt-1 mb-1">
            <Col>
              <InputDescription
                className="labelstyle"
                // validateError={errorCollection.includes("description")}
                label={t("xray_explanation_jp")}
                name="description"
                typeName="textarea"
                value={description || ""}
                onChange={event => handleChangeForm(event, this)}
              />
              {/* {<Span className="error">{errors["description"]}</Span>} */}
            </Col>
          </Row>

          <Row className="mt-1 mb-1">
            <Col>
              <InputDescription
                className="labelstyle"
                // validateError={errorCollection.includes("description_en")}
                label={t("xray_explanation_en")}
                name="description_en"
                typeName="textarea"
                value={description_en || ""}
                onChange={event => handleChangeForm(event, this)}
              />
              {/* {<Span className="error">{errors["description_en"]}</Span>} */}
            </Col>
          </Row>

          <Row className="mb-1">
            <Col>
              <Label className="labelstyle xray-edit-modal-item ">
                {t("status")}
              </Label>
              <Span className=" radio-wrapper mr-2">
                <InputRadio
                  title={t("public")}
                  className="radioStyle ml-1 "
                  name="status"
                  defaultChecked={status == 2 || status == 3 ? "checked" : ""}
                  value={3}
                  onClick={event => handleChangeForm(event, this)}
                />
                <InputRadio
                  title={t("private")}
                  className="radioStyle ml-2"
                  name="status"
                  defaultChecked={status == 0 || status == 1 ? "checked" : ""}
                  value={1}
                  onClick={event => handleChangeForm(event, this)}
                />
              </Span>
            </Col>
          </Row>
          {/* <Row className="mb-1">
            <Col>
              <Label className="xray-edit-modal-item">{t("group_attr")}</Label>
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
                    handleSelectItem(
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
                onClick={() => handleXraySubmit(this)}
                className="btn-block text-center organism-add-modal-button"
              >
                {t("ok_btn")}
              </Button>
            </Col>
            <Col md={4} xs={4}>
              <Button
                mode="cancel"
                onClick={() => cancelEdit(this)}
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
 * get xray data
 * @param {*} context
 */
const handleFetchData = async context => {
  const { editItem } = context.props;
  if (editItem) {
    context.setState({
      title: editItem.title,
      title_en: editItem.title_en,
      description: editItem.item.description,
      description_en: editItem.item.description_en,
      is_normal: editItem.item.is_normal,
      group_attribute:
        editItem.exam_groups && editItem.exam_groups.length > 0 ? 0 : 1,
      normal_abnormal: editItem.is_normal,
      user_id: editItem.user_id,
      supervisor_comment: editItem.supervisor_comment || "",
      id: editItem.id,
      image_file: editItem.image_path,
      status: editItem.status,
      selected_exam_group:
        editItem.exam_groups && editItem.exam_groups.length > 0
          ? editItem.exam_groups.map(item => ({
              id: item.id,
              text: item.name
            }))
          : []
    });
  }
};

/**
 * fetch exam groups
 * @param {*} context
 */
const handleFetchExamGroup = async context => {
  await context.props.getLibraryUser(context.props.userToken);
  await context.props.getExamGroup(context.props.userToken);
  let exam_groups = [];
  let user_list = [];

  if (context.props.examGroup && !context.props.examGroup.isLoading) {
    exam_groups = [
      ...context.props.examGroup.examGroupList.map(exam_group => ({
        id: exam_group.id,
        text: exam_group.name
      }))
    ];
  }
  if (context.props.userList && !context.props.userList.isLoading) {
    user_list = [
      ...context.props.userList.user_list.map(list => ({
        id: list.id,
        value: list.name
      }))
    ];
  }

  context.setState({
    exam_groups,
    user_list,
    imageHash: Date.now()
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
 * select group attribute items
 * @param {*} selectedList
 * @param {*} selectedItem
 * @param {*} context
 * @param {*} type
 */
const handleSelectItem = (selectedList, selectedItem, context, type) => {
  const isItemSelected = context.state.selected_exam_group.find(
    item => item.id === parseInt(selectedList.params.data.id)
  );
  if (!isItemSelected) {
    context.setState({
      [type]: Array.from(
        new Set([
          ...context.state[type],
          {
            ...selectedList.params.data,
            name: selectedList.params.data.text,
            id: parseInt(selectedList.params.data.id)
          }
        ])
      )
    });
  }
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
 * handle form input change
 * @param {*} value
 * @param {*} context
 */
const handleChangeForm = (event, context) => {
  context.setState({ [event.target.name]: event.target.value });
};

/**
 *  cancel Xray
 * @param {*} context
 */
const cancelEdit = context => {
  context.props.onHideEditModal();
  context.setState({
    ...initial_state
  });
};

/**
 * validate input
 * @param {*} context
 */
const handleValidate = context => {
  const { image_file, image_file_object, title, title_en } = context.state;
  const { t } = context.props;
  let errors = {};
  let formIsValid = true;
  let is_image_available = image_file ? image_file : image_file_object;
  //image_file
  if (!is_image_available) {
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
 * edit xray value
 * @param {*} context
 */
const handleXraySubmit = context => {
  const { editItem } = context.props;
  const xray_data = {
    id: editItem.id,
    ...context.state
  };
  const isValidated = handleValidate(context);
  if (isValidated) {
    const { userToken, userInfo } = context.props;
    context.props.updateXray(
      { ...xray_data, user_id: userInfo.id },
      userToken,
      editItem.id
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
const mapStateToProps = state => {
  return {
    examGroup: state.examGroup,
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    userInfo: state.auth.userInfo && state.auth.userInfo.user,
    userList: state.LibraryUserList
  };
};

//===================================================
// export
//===================================================
export default connect(mapStateToProps, {
  getExamGroup,
  getLibraryUser,
  updateXray
})(withTranslation("translation")(XrayEdit));
