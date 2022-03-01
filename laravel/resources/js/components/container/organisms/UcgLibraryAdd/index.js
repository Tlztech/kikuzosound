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

// redux
import { getExamGroup } from "../../../../redux/modules/actions/ExamGroupAction";

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
  status: 1,
  user_list: [],
  supervisor: "0",
  errors: {},
  exam_groups: [],
  selected_exam_group: [],
  group_attribute: "1",
  supervisor_comment: "",
};

class UcgLibraryAdd extends React.Component {
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
      group_attribute,
      video_file,
      status,
      exam_groups,
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
        className="organism-add-ucg-library-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label>{t("add_ucg_library")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => cancelAdd(this)}
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
                name="title_en"
                typeName="text"
                onChange={(event) => handleChangeForm(event, this)}
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
                  defaultChecked="checked"
                  value={1}
                  onClick={(event) => handleChangeForm(event, this)}
                />

                <InputRadio
                  title={t("abnormal")}
                  name="normal_abnormal"
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
                onChange={(event) => handleChangeForm(event, this)}
              />
              {/* {<Span className="error">{errors["ucg_explanation_en"]}</Span>} */}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <Label className="ucg-add-modal-item">{t("status")}</Label>
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
              <Label className="ucg-add-modal-item"> {t("group_attr")}</Label>
            </Col>
            <Col xs={9}>
              <Span className="ml-2">
                <InputRadio
                  className="mr-2"
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
                  className="ml-3"
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
    }
    // () =>
    //   context.setState({
    //     supervisor: data.user.id,
    //   })
  );
};
/**
 * select / update item
 * @param {*} e
 * @param {*} context
 * @param {*} type
 */
const handleSelect = async (e, context, type) => {
  await context.setState({ [type]: e.target.value });
};

/**
 * choose file
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
 * select
 * @param {*} selectedList
 * @param {*} context
 * @param {*} type
 */
const onSelect = (selectedList, selectedItem, context, type) => {
  context.setState({
    [type]: Array.from(
      new Set([...context.state[type], selectedList.params.data])
    ),
  });
};

/**
 * delete item
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
 * submit ucg data
 * @param {*} context
 */
const submitData = (context) => {
  const ucg_data = {
    ...context.state,
  };
  const isValidated = handleValidate(context);
  if (isValidated) {
    context.props.addUcgData(ucg_data);
    context.setState({
      ...initial_state,
    });
    context.props.onHideAddModal();
  }
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
  withTranslation("translation")(UcgLibraryAdd)
);
