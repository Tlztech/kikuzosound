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
import RadioButton from "../../../presentational/atoms/RadioButton";
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

// css
import "./style.css";
import "react-select2-wrapper/css/select2.css";

//i18n
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
const initial_state = {
  image_file: "",
  title: null,
  title_en: null,
  normal_abnormal: 1,
  ecg_explanation: "",
  ecg_explanation_en: "",
  status: 2,
  user_list: [],
  supervisor: "0",
  errors: {},
  exam_groups: [],
  selected_exam_group: [],
  group_attribute: "1",
};
class EcgLibraryAdd extends React.Component {
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
      errors,
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
        onHide={() => cancelAdd(this)}
        size="lg"
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
        className="organism-add-ecg-library-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label>{t("add_ecg_library")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => cancelAdd(this)}
          />
        </Modal.Header>
        <Modal.Body className="ecg-library-organism-modal-body">
          {errorValue.length > 0 && (
            <Div className="alert alert-danger">
              <P>{t("validate_error")}</P>
              <ul>
                {errorValue && errorValue.map((e, i) => <li key={i}>{e}</li>)}
              </ul>
            </Div>
          )}
          {/* <Div className="mb-3">
            <Label
              labelError={errorCollection.includes("image_file")}
              className="labelstyle ecg-library-modal-item"
            >
              {t("image_file")}
            </Label>
            <Span className="input-item">
              <DropzoneArea
                dropzoneText={t("dropzoneText_exam")}
                acceptedFiles={[".jpg,.png,image/jpeg,image/png"]}
                showAlerts={false}
                filesLimit={1}
                showPreviews={true}
                showPreviewsInDropzone={false}
                previewText=""
                onChange={(files) =>
                  this.setState({
                    image_file: files[0],
                  })
                }
              />
              {<Span className="error">{errors["image_file"]}</Span>}
            </Span>
          </Div> */}
          <Row className="mb-3">
            <Col>
              <InputWithLabel
                validateError={errorCollection.includes("image_file")}
                label={t("image_file")+t("required_sign")}
                name="image-file"
                accept={[".jpg,.png,image/jpeg,image/png"]}
                typeName="file"
                onChange={(e) =>
                  this.setState({
                    image_file: e.target.files[0],
                  })
                }
              />
              {<Span className="error">{errors["image_file"]}</Span>}
              {this.state.image_file && (
                <Image
                  url={URL.createObjectURL(this.state.image_file)}
                  mode="selected-input"
                />
              )}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputWithLabel
                validateError={errorCollection.includes("title")}
                label={t("title_jp")+t("required_sign")}
                name="title"
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
                label={t("title_en")+t("required_sign")}
                name="title_en"
                typeName="text"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["title_en"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <Div className="organism-radio-wrapper">
                <Label className="organism-radio-label">
                  {t("normal_abnormal")}
                </Label>
                <Span className="organism-radio-button">
                  <RadioButton
                    className="radioStyle"
                    type="radio"
                    name="normal_abnormal"
                    defaultChecked="checked"
                    value={1}
                    onClick={(event) => handleChangeForm(event, this)}
                  />
                  <Label className="radioStyle mr-2">{t("normal")} </Label>
                  <RadioButton
                    className="radioStyle ml-2"
                    type="radio"
                    name="normal_abnormal"
                    value={0}
                    onClick={(event) => handleChangeForm(event, this)}
                  />
                  <Label className="radioStyle mr-2"> {t("abnormal")} </Label>
                </Span>
              </Div>
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputDescription
                validateError={errorCollection.includes("ecg_explanation")}
                label={t("ecg_explaination_jp")}
                name="ecg_explanation"
                typeName="textarea"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["ecg_explanation"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputDescription
                validateError={errorCollection.includes("ecg_explanation_en")}
                label={t("ecg_explaination_en")}
                name="ecg_explanation_en"
                typeName="textarea"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["ecg_explanation_en"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-1">
            <Col>
              <Div className="organism-radio-wrapper">
                <Label className="organism-radio-label">{t("status")}</Label>
                <Span className="organism-radio-button">
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
                </Span>
              </Div>
            </Col>
          </Row>

          {/* <Row className="mb-1">
            <Col>
              <Label className="labelstyle ecg-add-modal-item">
                {t("group_attributes")}
              </Label>
            </Col>
            <Col xs={9} className="organism-ecg-radio-container">
              <InputRadio
                title={t("yes")}
                name="group_attribute"
                value={0}
                defaultChecked={group_attribute == 0 ? "checked" : ""}
                onClick={(event) => handleChangeForm(event, this)}
              />
              <Select2
                defaultValue={0}
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
              <Col className="organism-ecg-group-attribute-item">
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
                onClick={() => submitData(this)}
                className="btn-block text-center organism-add-modal-button"
              >
                {t("ok_btn")}
              </Button>
            </Col>
            <Col md={4} xs={4}>
              <Button
                mode="cancel"
                onClick={() => cancelAdd(this)}
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
 * handle select
 * @param {*} e
 * @param {*} context
 * @param {*} type
 */
const handleSelect = async (e, context, type) => {
  await context.setState({ [type]: e.target.value });
};

/**
 * fetch exam groups and user
 * @param {*} context
 */
const handleFetchData = (context) => {
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
 * cancel add
 * @param {*} context
 */
const cancelAdd = (context) => {
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
 * handle selected group attributes
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
 * validate input
 * @param {*} context
 */
const handleValidate = (context) => {
  const { title, title_en, image_file } = context.state;
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
 * submit ecg data
 * @param {*} context
 */
const submitData = (context) => {
  const ecg_data = {
    ...context.state,
  };
  const isValidated = handleValidate(context);
  if (isValidated) {
    context.props.addEcgData(ecg_data);
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
  };
};

//===================================================
// export
//===================================================
export default connect(mapStateToProps, { getExamGroup })(
  withTranslation("translation")(EcgLibraryAdd)
);
