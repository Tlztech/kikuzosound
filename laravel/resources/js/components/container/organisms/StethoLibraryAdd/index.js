import React from "react";

// libs
import { connect } from "react-redux";
import { Row, Col, Modal } from "react-bootstrap";
import { DragDropContext, Draggable, Droppable } from "react-beautiful-dnd";
import Select2 from "react-select2-wrapper";

// Components
import Div from "../../../presentational/atoms/Div";
import P from "../../../presentational/atoms/P";
import Button from "../../../presentational/atoms/Button";
import MediaComponent from "../../../presentational/molecules/Media";
import Image from "../../../presentational/atoms/Image";
import Label from "../../../presentational/atoms/Label";
import Input from "../../../presentational/atoms/Input";
import InputWithLabel from "../../../presentational/molecules/InputWithLabel";
import InputDescription from "../../../presentational/molecules/InputDescription"
import Span from "../../../presentational/atoms/Span";
import Select from "../../../presentational/atoms/Select";
import InputRadio from "../../../presentational/molecules/RadioWithLabel";
import ExamGroupItem from "../../../presentational/molecules/ExamGroupItem";

//redux
import { getExamGroup } from "../../../../redux/modules/actions/ExamGroupAction";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";
import { DragNdropIcon } from "../../../../assets";

// css
import "./style.css";
import "react-select2-wrapper/css/select2.css";

//i18n
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================

const sound_type = [
  { id: 1, value: "lung_sound" },
  { id: 2, value: "heart_sound" },
  { id: 3, value: "intestinal_sound" },
  { id: 9, value: "other" },
];

const getConversionType = (context) => {
  const { t } = context.props;
  return [
    { id: 0, value: t("orig_collection") },
    { id: 1, value: t("processing_sound") },
    { id: 2, value: t("artificial_sound") },
  ];
};

const initial_state = {
  image_list: [],
  image_list_en: [],
  sound_img: [],
  sound_img_desc: [],

  sound_source: "",
  is_video: 0,
  title: null,
  title_en: null,
  selected_sound_type: 1,
  ausculation_site: null,
  ausculation_site_en: null,
  selected_conversion_type: 0,
  normal_abnormal: 1,
  disease: null,
  disease_en: null,
  source_desc: "",
  source_desc_en: "",
  description: "",
  description_en: "",
  supervisor: "0",
  supervisor_comment: "",
  status: 1,
  exam_groups: [],
  selected_exam_group: [],
  user_list: [],
  errors: {},
  group_attribute: 1,

  draging: {},
  row_data: "",
  row_key: null,
};

class StethoLibraryAdd extends React.Component {
  constructor(props) {
    super(props);
    this.state = initial_state;
  }

  async componentDidMount() {
    await this.props.getExamGroup(this.props.userToken);
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
      sound_source,
      image_list,
      is_video,
      normal_abnormal,
      status,
      image_list_en,
      group_attribute,
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
        className="organism-add-stetho-library-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label>{t("add_stetho_library")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => cancelAdd(this)}
          />
        </Modal.Header>
        <Modal.Body className="stetho-library-organism-modal-body">
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
                validateError={errorCollection.includes("sound_source")}
                label={t("sound_source")}
                name="sound_source"
                typeName="file"
                accept="audio/*,video/*"
                onChange={(event) => handleSoundFile(event, this)}
              />
              <MediaComponent file={sound_source} type="sound" />
              {<Span className="error">{errors["sound_source"]}</Span>}
            </Col>
          </Row>
          {(
            typeof sound_source === "object"
              ? sound_source.type.includes("mp4")
              : sound_source
              ? sound_source.includes("mp4")
              : false
          ) ? (
            <Div className="mb-3 grid-item">
              <Label className="labelstyle stetho-library-modal-item ">
                {t("video")}
              </Label>
              <Span className="radio-select-margin mb-3">
                <InputRadio
                  title={t("release")}
                  name="is_video"
                  defaultChecked={is_video == 1 ? "checked" : ""}
                  value={1}
                  onClick={(event) => handleChangeForm(event, this)}
                />
                <InputRadio
                  title={t("private")}
                  name="is_video"
                  defaultChecked={is_video == 0 ? "checked" : ""}
                  value={0}
                  onClick={(event) => handleChangeForm(event, this)}
                />
              </Span>
            </Div>
          ) : (
            <></>
          )}
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

          <Div className="dropdown-style mb-3 grid-item">
            <Label className="labelstyle stetho-library-modal-item mr-2">
              {t("ausculation_sound_type")}
            </Label>
            <Select
              className="select-item"
              items={sound_type}
              value={this.state.selected_sound_type}
              onChange={(event) =>
                handleSelect(event, this, "selected_sound_type")
              }
            ></Select>
          </Div>

          <Row className="mb-3">
            <Col>
              <InputWithLabel
                validateError={errorCollection.includes("ausculation_site")}
                label={t("ausculation_site_jp")+t("required_sign")}
                typeName="text"
                name="ausculation_site"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["ausculation_site"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputWithLabel
                validateError={errorCollection.includes("ausculation_site_en")}
                label={t("ausculation_site_en")+t("required_sign")}
                typeName="text"
                name="ausculation_site_en"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["ausculation_site_en"]}</Span>}
            </Col>
          </Row>

          <Div className="grid-item mb-3">
            <Label className="labelstyle stetho-library-modal-item">
              {t("conversion_type")}
            </Label>
            <Div>
              <Select
                className="select-item"
                items={getConversionType(this)}
                value={this.state.selected_conversion_type}
                onChange={(event) =>
                  handleSelect(event, this, "selected_conversion_type")
                }
              ></Select>
            </Div>
          </Div>

          <Div className="mb-3 grid-item">
            <Label className="labelstyle stetho-library-modal-item ">
              {t("normal_abnormal")}
            </Label>
            <Span className="radio-select-margin">
              <InputRadio
                className="mr-2"
                title={t("normal")}
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

          <Row className="mb-3">
            <Col>
              <InputWithLabel
                validateError={errorCollection.includes("disease")}
                label={t("disease_jp")+t("required_sign")}
                typeName="text"
                name="disease"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["disease"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputWithLabel
                validateError={errorCollection.includes("disease_en")}
                label={t("disease_en")+t("required_sign")}
                typeName="text"
                name="disease_en"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["disease_en"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputWithLabel
                label={t("sound_source_description_jp")}
                typeName="text"
                name="source_desc"
                onChange={(event) => handleChangeForm(event, this)}
              />
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputWithLabel
                label={t("sound_source_description_en")}
                typeName="text"
                name="source_desc_en"
                onChange={(event) => handleChangeForm(event, this)}
              />
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputDescription
                validateError={errorCollection.includes("description")}
                label={t("descriptions_jp")}
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
                label={t("descriptions_en")}
                name="description_en"
                typeName="textarea"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["description_en"]}</Span>}
            </Col>
          </Row>

          <Div className="mb-3 grid-item">
            <Label className="labelstyle stetho-library-modal-item">
              {t("explanatory_image_ja")}
            </Label>
            <Div className="imageRow">
              <DragDropContext
                onDragEnd={(result) => endDrag(result, this, "")}
              >
                <Droppable droppableId={"explanatory_image_ja"}>
                  {(provided) => (
                    <Div
                      setInputRef={provided.innerRef}
                      {...provided.droppableProps}
                      className="file-select-drag"
                    >
                      {image_list.map((value, index) => {
                        return (
                          <Draggable
                            key={value.key}
                            draggableId={value.key}
                            index={index}
                          >
                            {(provided) => (
                              <Div
                                {...provided.draggableProps}
                                setInputRef={provided.innerRef}
                              >
                                <Row
                                  className="ml-1 mb-4 draggable"
                                  key={value.key}
                                  id={value.key}
                                >
                                  <Input
                                    typeName="file"
                                    className="mb-2"
                                    name={`expalin_img_${index}`}
                                    accept=".jpg,.png,image/jpeg,image/png"
                                    onChange={(event) =>
                                      setSoundImage(this, event, index)
                                    }
                                  />
                                  {value.sound_img && (
                                    <Image
                                      className="sound_img"
                                      url={URL.createObjectURL(value.sound_img)}
                                    />
                                  )}
                                  <Input
                                    typeName="input"
                                    className="mr-2"
                                    value={value.sound_img_desc || ""}
                                    onChange={(event) =>
                                      setSoundImageDesc(this, event, index)
                                    }
                                  />
                                  <Button
                                    mode="danger"
                                    onClick={() =>
                                      removeSoundImage(this, index)
                                    }
                                  >
                                    {t("delete")}
                                  </Button>
                                  <Div
                                    {...provided.dragHandleProps}
                                    className="add_image_dnd_img"
                                  >
                                    <Image url={DragNdropIcon} />
                                  </Div>
                                </Row>
                              </Div>
                            )}
                          </Draggable>
                        );
                      })}
                      {provided.placeholder}
                    </Div>
                  )}
                </Droppable>
              </DragDropContext>
              <Button
                mode="success"
                onClick={() => addSoundImage(this)}
                className="btn-block text-center organism-add-modal-button"
              >
                {t("add_image")}
              </Button>
            </Div>
          </Div>

          <Div className="mb-3 grid-item">
            <Label className="labelstyle stetho-library-modal-item">
              {t("explanatory_image_en")}
            </Label>
            <Div className="imageRowEn">
              <DragDropContext
                onDragEnd={(result) => endDrag(result, this, "en")}
              >
                <Droppable droppableId={"explanatory_image_en"}>
                  {(provided) => (
                    <Div
                      setInputRef={provided.innerRef}
                      {...provided.droppableProps}
                    >
                      {image_list_en.map((value, index) => {
                        return (
                          <Draggable
                            key={value.key}
                            draggableId={value.key}
                            index={index}
                          >
                            {(provided) => (
                              <Div
                                {...provided.draggableProps}
                                setInputRef={provided.innerRef}
                              >
                                <Row
                                  className="ml-1 mb-4 draggable"
                                  key={value.key}
                                  id={value.key}
                                >
                                  <Input
                                    typeName="file"
                                    className="mb-2"
                                    name={`expalin_img_en${index}`}
                                    accept=".jpg,.png,image/jpeg,image/png"
                                    onChange={(event) =>
                                      setSoundImageEn(this, event, index)
                                    }
                                  />
                                  {value.sound_img && (
                                    <Image
                                      className="sound_img"
                                      url={URL.createObjectURL(value.sound_img)}
                                    />
                                  )}
                                  <Input
                                    typeName="input"
                                    className="mr-2"
                                    // name={this.props.name}
                                    value={value.sound_img_desc || ""}
                                    onChange={(event) =>
                                      setSoundImageDescEn(this, event, index)
                                    }
                                  />
                                  <Button
                                    mode="danger"
                                    onClick={() =>
                                      removeSoundImageEn(this, index)
                                    }
                                  >
                                    {t("delete")}
                                  </Button>
                                  <Div
                                    {...provided.dragHandleProps}
                                    className="add_image_dnd_img"
                                  >
                                    <Image url={DragNdropIcon} />
                                  </Div>
                                </Row>
                              </Div>
                            )}
                          </Draggable>
                        );
                      })}
                      {provided.placeholder}
                    </Div>
                  )}
                </Droppable>
              </DragDropContext>
              <Button
                mode="success"
                onClick={() => addSoundImageEn(this)}
                className="btn-block text-center organism-add-modal-button"
              >
                {t("add_image")}
              </Button>
            </Div>
          </Div>

          <Div className="mb-3 grid-item">
            <Label className="labelstyle stetho-library-modal-item ">
              {t("status")}
            </Label>
            <Span className="radio-select-margin">
              <InputRadio
                className=" mr-2"
                title={t("public")}
                name="status"
                defaultChecked={status == 2 || status == 3 ? "checked" : ""}
                value={3}
                onClick={(event) => handleChangeForm(event, this)}
              />
              <InputRadio
                className="ml-2 mr-2"
                title={t("private")}
                name="status"
                defaultChecked={status == 0 || status == 1 ? "checked" : ""}
                value={1}
                onClick={(event) => handleChangeForm(event, this)}
              />
            </Span>
          </Div>

          {/* <Div className="mb-3 grid-item">
            <Label className="labelstyle stetho-library-modal-item ">
              {t("group_attributes")}
            </Label>
            <Span className="radio-select-margin">
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
                name="group_attribute"
                className="ml-2"
                value={1}
                defaultChecked={group_attribute == 1 ? "checked" : ""}
                onClick={(event) => handleChangeForm(event, this)}
              />
              <Col className="organism-stetho-group-attribute-item">
                <ExamGroupItem
                  selected_exam_group={selected_exam_group}
                  onClick={(index) =>
                    handleRemoveExamGroup(index, this, "selected_exam_group")
                  }
                />
              </Col>
            </Span>
          </Div> */}
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
    }
    // () =>
    //   context.setState({
    //     supervisor: data.user.id,
    //   })
  );
};
/**
 * handle selecting item from dropdown
 * @param {*} context
 * @param {event} event
 */
const handleSelect = async (e, context, type) => {
  await context.setState({ [type]: e.target.value });
};

/**
 * handle adding sound file
 * @param {*} context
 * @param {event} event
 */
const handleSoundFile = (event, context) => {
  console.log(event.target.files[0].name);
  context.setState({ [event.target.name]: event.target.files[0] });
};

/**
 * end of drag
 * @param {*} result
 * @param {*} context
 * @param {*} lang
 */
const endDrag = (result, context, lang) => {
  const { destination, source } = result;
  if (!destination) {
    return;
  }
  if (
    destination.droppableId === source.droppableId &&
    destination.index === source.index
  ) {
    return;
  }
  const imageType = lang === "en" ? "image_list_en" : "image_list";
  const libraryItems = context.state[imageType];
  const items = Array.from(libraryItems);
  const [reorderedItem] = items.splice(source.index, 1);
  items.splice(destination.index, 0, reorderedItem);
  context.setState({
    [imageType]: items,
  });
};

/**
 * handle setting sound image description image
 * @param {*} context
 * @param {e} event
 * @param {index} index
 */
const setSoundImage = (context, e, index) => {
  let image_file = e.target.files[0];
  const { image_list } = context.state;
  image_list[index]["sound_img"] = image_file;
  context.setState({ image_list: image_list });
};

/**
 * handle setting sound image description image
 * @param {*} context
 * @param {e} event
 * @param {index} index
 */
const setSoundImageDesc = (context, e, index) => {
  const { image_list } = context.state;
  image_list[index]["sound_img_desc"] = e.target.value;
  context.setState({ image_list: image_list });
};

/**
 * handle adding sound image description item
 * @param {*} context
 */
const addSoundImage = (context) => {
  const { image_list } = context.state;
  context.setState({
    image_list: image_list.concat({
      key: Date.now() + "_" + image_list.length,
      sound_img: null,
      sound_img_desc: "",
    }),
  });
};

/**
 * handle removing sound image description item
 * @param {*} context
 * @param {position} pos
 */
const removeSoundImage = (context, pos) => {
  const { image_list } = context.state;
  if (image_list.length > 0) {
    context.setState({
      image_list: image_list.filter((item, index) => index !== pos),
    });
  }
};

/**
 * handle setting sound image description image
 * @param {*} context
 * @param {e} event
 * @param {index} index
 */
const setSoundImageEn = (context, e, index) => {
  let image_file = e.target.files[0];
  const { image_list_en } = context.state;
  image_list_en[index]["sound_img"] = image_file;
  context.setState({ image_list_en: image_list_en });
};

/**
 * handle setting sound image description image
 * @param {*} context
 * @param {e} event
 * @param {index} index
 */
const setSoundImageDescEn = (context, e, index) => {
  const { image_list_en } = context.state;
  image_list_en[index]["sound_img_desc"] = e.target.value;
  context.setState({ image_list_en: image_list_en });
};

/**
 * handle adding sound image description item
 * @param {*} context
 */
const addSoundImageEn = (context) => {
  const { image_list_en } = context.state;
  context.setState({
    image_list_en: image_list_en.concat({
      key: Date.now() + "_" + image_list_en.length,
      sound_img: null,
      sound_img_desc: "",
    }),
  });
};

/**
 * handle removing sound image description item
 * @param {*} context
 * @param {position} pos
 */
const removeSoundImageEn = (context, pos) => {
  const { image_list_en } = context.state;
  if (image_list_en.length > 0) {
    context.setState({
      image_list_en: image_list_en.filter((item, index) => index !== pos),
    });
  }
};

/**
 * handle closing modal
 * @param {*} context
 */
const cancelAdd = (context) => {
  context.setState({
    ...initial_state,
    sound_img: [],
    sound_img_desc: [],
    erros: {},
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
  const {
    title,
    title_en,
    ausculation_site,
    ausculation_site_en,
    disease,
    disease_en,
    sound_source,
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
  //ausculation_site
  if (!ausculation_site || ausculation_site.trim.length < 0) {
    formIsValid = false;
    errors["ausculation_site"] = t("validate_ausculation_site");
  }
  //ausculation_site_en
  if (!ausculation_site_en || ausculation_site_en.trim.length < 0) {
    formIsValid = false;
    errors["ausculation_site_en"] = t("validate_ausculation_site_en");
  }
  //disease
  if (!disease || disease.trim.length < 0) {
    formIsValid = false;
    errors["disease"] = t("validate_disease");
  }
  //disease_en
  if (!disease_en || disease_en.trim.length < 0) {
    formIsValid = false;
    errors["disease_en"] = t("validate_disease_en");
  }
  //sound_format
  if (typeof sound_source === "object") {
    if (
      sound_source.name.includes("mp3") ||
      sound_source.name.includes("mp4")
    ) {
    } else {
      {
        formIsValid = false;
        errors["sound_source"] = t("validate_sound_format");
      }
    }
  }
  context.setState({ errors: errors });
  return formIsValid;
};

/**
 * submit stetho data
 * @param {*} context
 */
const submitData = (context) => {
  const stetho_data = {
    ...context.state,
  };
  const isValidated = handleValidate(context);
  if (isValidated) {
    context.props.addStethoData(stetho_data);
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
  withTranslation("translation")(StethoLibraryAdd)
);
