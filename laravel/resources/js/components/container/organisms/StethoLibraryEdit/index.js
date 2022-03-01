import React from "react";
import { connect } from "react-redux";

// libs
import { Row, Col, Modal } from "react-bootstrap";
import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";
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
import { getLibraryUser } from "../../../../redux/modules/actions/LibraryUserAction";

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
  return[
    { id: 0, value: t("orig_collection") },
    { id: 1, value: t("processing_sound") },
    { id: 2, value: t("artificial_sound") },
  ];
};

const initial_state = {
  sound_source: "",
  is_video: 0,
  title: null,
  title_en: null,
  selected_sound_type: 1,
  ausculation_site: null,
  ausculation_site_en: null,
  selected_conversion_type: 0,
  normal_abnormal: null,
  disease: null,
  disease_en: null,
  source_desc: null,
  source_desc_en: null,
  description: "",
  description_en: "",
  image_list: [],
  image_list_en: [],
  status: null,
  group_attribute: null,
  supervisor: "0",
  supervisor_comment: null,
  selected_exam_group: [],
  sound_img: [],
  sound_img_desc: [],
  user_list: [],
  errors: {},

  // draging: {},
  // row_data: "",
  // row_key: null,

  remove_sound_img_id: [],
  media_hash: "",
};

class StethoLibraryEdit extends React.Component {
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
      sound_source,
      source_desc_en,
      source_desc,
      group_attribute,
      selected_conversion_type,
      ausculation_site,
      ausculation_site_en,
      selected_exam_group,
      disease,
      disease_en,
      is_video,
      status,
      normal_abnormal,
      image_list,
      image_list_en,
      selected_sound_type,
      user_list,
      supervisor,
      exam_groups,
      media_hash,
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
        className="organism-edit-stetho-library-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label>{t("edit_stetho_library")}</Label>
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
              <MediaComponent
                file={sound_source}
                type="sound"
                hash={media_hash}
              />
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
              <Label className="labelstyle stetho-library-modal-item">
                {t("video")}
              </Label>
              <Span className="radio-select-margin mb-2">
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
              <Label className="labelstyle stetho-library-modal-item">
                {t("ausculation_sound_type")}
              </Label>
            </Col>
            <Col xs={9}>
              <Select
                className="select-style"
                items={sound_type}
                value={selected_sound_type}
                onChange={(event) =>
                  handleSelect(event, this, "selected_sound_type")
                }
              ></Select>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col>
              <InputWithLabel
                validateError={errorCollection.includes("ausculation_site")}
                label={t("ausculation_site_jp")+t("required_sign")}
                typeName="text"
                value={ausculation_site || ""}
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
                value={ausculation_site_en || ""}
                name="ausculation_site_en"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["ausculation_site_en"]}</Span>}
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={3}>
              <Label className="labelstyle stetho-library-modal-item">
                {t("conversion_type")}
              </Label>
            </Col>
            <Col xs={9}>
              <Select
                className="select-style"
                items={getConversionType(this)}
                value={selected_conversion_type}
                onChange={(event) =>
                  handleSelect(event, this, "selected_conversion_type")
                }
              ></Select>
            </Col>
          </Row>
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
                className=" mr-2"
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
                value={disease || ""}
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
                value={disease_en || ""}
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
                value={source_desc || ""}
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
                value={source_desc_en || ""}
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
                label={t("descriptions_en")}
                name="description_en"
                value={description_en || ""}
                typeName="textarea"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["description_en"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col xs={3}>
              <Label className="labelstyle stetho-library-modal-item">
                {t("explanatory_image_ja")}
              </Label>
            </Col>

            <DragDropContext
              onDragEnd={(result) => handleonDragEnd(result, this, "")}
            >
              <Droppable droppableId={`explain-image-ja`}>
                {(provided) => (
                  <div
                    className="imageRow col-9"
                    {...provided.droppableProps}
                    ref={provided.innerRef}
                  >
                    {image_list &&
                      image_list.map((value, index) => {
                        let path = this.state.image_list[index].path;
                        let ext = path ? path.substr(path.length - 3) : "";
                        return (
                          <Draggable
                            key={value.key}
                            draggableId={value.key.toString()}
                            index={index}
                          >
                            {(provided) => (
                              <Row
                                className="ml-1 mt-4 mb-4 draggable file-select-drag"
                                {...provided.draggableProps}
                                ref={provided.innerRef}
                              >
                                <Input
                                  typeName="file"
                                  className="mb-2 file-input"
                                  name={`expalin_img${index}`}
                                  accept=".jpg,.png,image/jpeg,image/png"
                                  onChange={(event) =>
                                    setSoundImage(this, event, index)
                                  }
                                />
                                {value.sound_img ? (
                                  typeof value.sound_img === "object" ? (
                                    <Image
                                      isPossibleError={false}
                                      className="sound_img"
                                      url={URL.createObjectURL(value.sound_img)}
                                    />
                                  ) : (
                                    <Image
                                      isPossibleError={true}
                                      className="sound_img"
                                      url={`${value.sound_img}`}
                                    />
                                  )
                                ) : null}
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
                                  onClick={() => removeSoundImage(this, index)}
                                >
                                  {t("delete")}
                                </Button>
                                {value.sound_img ? (
                                  ext == "mp4" ? (
                                    <MediaComponent
                                      file={
                                        this.state.image_list[index].sound_img
                                      }
                                      type="video"
                                    />
                                  ) : (
                                    ""
                                  )
                                ) : value.path ? (
                                  <Image
                                    className="sound_img"
                                    // url={URL.createObjectURL(
                                    //   this.state.sound_img[index]
                                    // )}
                                    url={
                                      value.path
                                    }
                                  />
                                ) : (
                                  ""
                                )}
                                <Div
                                  {...provided.dragHandleProps}
                                  className="add_image_dnd_img"
                                >
                                  <Image url={DragNdropIcon} />
                                </Div>
                              </Row>
                            )}
                          </Draggable>
                        );
                      })}
                    {provided.placeholder}
                    <Button
                      mode="success"
                      onClick={() => addSoundImage(this)}
                      className="btn-block text-center organism-edit-modal-button ml-2"
                    >
                      {t("add_image")}
                    </Button>
                  </div>
                )}
              </Droppable>
            </DragDropContext>
          </Row>

          <Row className="mb-3">
            <Col xs={3}>
              <Label className="labelstyle stetho-library-modal-item">
                {t("explanatory_image_en")}
              </Label>
            </Col>

            <DragDropContext
              onDragEnd={(result) => handleonDragEnd(result, this, "en")}
            >
              <Droppable droppableId={`explain-image-en`}>
                {(provided) => (
                  <div
                    {...provided.droppableProps}
                    ref={provided.innerRef}
                    className="imageRowEn col-9"
                  >
                    {image_list_en &&
                      image_list_en.map((value, index) => {
                        let path = this.state.image_list_en[index].path;
                        let ext = path ? path.substr(path.length - 3) : "";
                        return (
                          <Draggable
                            key={value.key}
                            draggableId={value.key.toString()}
                            index={index}
                          >
                            {(provided) => (
                              <Row
                                className="ml-1 mt-4 mb-4 draggable"
                                {...provided.draggableProps}
                                ref={provided.innerRef}
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
                                {value.sound_img ? (
                                  typeof value.sound_img === "object" ? (
                                    <Image
                                      isPossibleError={false}
                                      className="sound_img"
                                      url={URL.createObjectURL(value.sound_img)}
                                    />
                                  ) : (
                                    <Image
                                      isPossibleError={true}
                                      className="sound_img"
                                      url={`${value.sound_img}`}
                                    />
                                  )
                                ) : null}
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
                                {value.sound_img ? (
                                  ext == "mp4" ? (
                                    <MediaComponent
                                      file={
                                        this.state.image_list_en[index]
                                          .sound_img
                                      }
                                      type="video"
                                    />
                                  ) : (
                                    ""
                                  )
                                ) : value.path ? (
                                  <Image
                                    className="sound_img"
                                    url={
                                      value.path
                                    }
                                  />
                                ) : (
                                  ""
                                )}
                                <Div
                                  {...provided.dragHandleProps}
                                  className="add_image_dnd_img"
                                >
                                  <Image url={DragNdropIcon} />
                                </Div>
                              </Row>
                            )}
                          </Draggable>
                        );
                      })}
                    {provided.placeholder}
                    <Button
                      mode="success"
                      onClick={() => addSoundImageEn(this)}
                      className="btn-block text-center organism-edit-modal-button ml-2"
                    >
                      {t("add_image")}
                    </Button>
                  </div>
                )}
              </Droppable>
            </DragDropContext>
          </Row>

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
                className=" mr-2"
                title={t("private")}
                name="status"
                defaultChecked={status == 0 || status == 1 ? "checked" : ""}
                value={1}
                onClick={(event) => handleChangeForm(event, this)}
              />
            </Span>
          </Div>

          {/* <Div className="mb-3 grid-item">
            <Label className="labelstyle stetho-library-modal-item">
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
                className="ml-2"
                name="group_attribute"
                value={1}
                defaultChecked={group_attribute == 1 ? "checked" : ""}
                onClick={(event) => handleChangeForm(event, this)}
              />
              <Col className="organism-stetho-group-attribute-item">
                {selected_exam_group && selected_exam_group.length > 0 && (
                  <ExamGroupItem
                    selected_exam_group={selected_exam_group}
                    onClick={(index) =>
                      handleRemoveExamGroup(index, this, "selected_exam_group")
                    }
                  />
                )}
              </Col>
            </Span>
          </Div> */}
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
                onClick={() => cancelAdd(this)}
                className="btn-block text-center organism-edit-modal-button"
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
 * fetch exam groups
 * @param {*} context
 */
const handleFetchExamGroup = async (context) => {
  const { userToken } = context.props;
  await context.props.getExamGroup(userToken);
  await context.props.getLibraryUser(userToken);
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
    media_hash: Date.now(),
  });
};

/**
 * set form values
 * @param {*} context
 */
const handleSetFormValues = async (context) => {
  const { editItem } = context.props;
  editItem &&
    context.setState({
      sound_source: editItem.sound_source || "",
      is_video: editItem.is_video,
      title: editItem.title,
      title_en: editItem.title_en,
      selected_sound_type: editItem.selected_sound_type,
      ausculation_site: editItem.ausculation_site || null,
      ausculation_site_en: editItem.ausculation_site_en || null,
      selected_conversion_type: editItem.selected_conversion_type,
      normal_abnormal: editItem.normal_abnormal === "normal" ? 1 : 0,
      disease: editItem.disease,
      disease_en: editItem.disease_en,
      source_desc: editItem.source_desc,
      source_desc_en: editItem.source_desc_en,
      description: editItem.description || null,
      description_en: editItem.description_en || null,
      image_list:
        editItem && editItem.image_list.length > 0
          ? editItem.image_list.map((image, index) => ({
              key: Date.now() + "_" + index,
              id: image.id,
              sound_img: image.image_path,
              sound_img_desc: image.title,
              disp_order: image.disp_order,
            }))
          : [],
      image_list_en:
        editItem && editItem.image_list_en.length > 0
          ? editItem.image_list_en.map((image, index) => ({
              key: Date.now() + "_" + index,
              id: image.id,
              sound_img: image.image_path,
              sound_img_desc: image.title,
              disp_order: image.disp_order,
            }))
          : [],
      status: editItem.status,
      group_attribute:
        editItem.selected_exam_group && editItem.selected_exam_group.length > 0
          ? 0
          : 1,

      supervisor: editItem.supervisor || null,
      supervisor_comment: editItem.supervisor_comment || "",
      id: editItem.ID,
      selected_exam_group: editItem.selected_exam_group,
    });
};

/**
 * handle adding sound file
 * @param {*} context
 * @param {event} event
 */
const handleSoundFile = (event, context) => {
  context.setState({ [event.target.name]: event.target.files[0] });
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
 * select exam groups
 * @param {*} selectedList
 * @param {*} selectedItem
 * @param {*} context
 * @param {*} type
 */
const handleSelectExamGroup = (selectedList, selectedItem, context, type) => {
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
 * remove item
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
 * handle form input change
 * @param {*} value
 * @param {*} context
 */
const handleChangeForm = (event, context) => {
  context.setState({ [event.target.name]: event.target.value });
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
const setSoundImageDesc = (context, e, index) => {
  const { image_list } = context.state;
  image_list[index]["sound_img_desc"] = e.target.value;
  context.setState({ image_list: image_list });
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
const addSoundImage = (context) => {
  const { image_list } = context.state;
  context.setState({
    image_list: image_list.concat({
      key: Date.now() + "_" + image_list.length,
      sound_img: null,
      sound_img_desc: "",
      disp_order: 0,
    }),
  });
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
      disp_order: 0,
    }),
  });
};

/**
 * find and set dragged itemname
 *
 * @param {*} event
 * @param {*} rowID
 * @param {*} context
 */

// const dragStartingEn = (event, rowID, context) => {
//   context.setState({ draging: event.target });
//   const rowData = context.state.image_list_en.find((e) => e.key == rowID);
//   context.setState({ row_data: rowData });
// };

// /**
//  *
//  * @param {*} event
//  * @param {*} context
//  * @param {*} row_data
//  */
// const dragingOverEn = (event, context, row_data) => {
//   let draggingTableData = context.state.draging;
//   let target = event.target.closest(".draggable");
//   if (draggingTableData.id != target.id) {
//     target.closest(".imageRowEn").insertBefore(draggingTableData, target);
//   }
//   context.setState({ row_key: row_data });
//   event.preventDefault();
// };

// /**
//  * end of drag
//  * @param {*} context
//  */

// const endDragEn = (context) => {
//   let setNewImgListEn = [];
//   let newImageRow = [];
//   context.state.image_list_en.map((data) => {
//     if (context.state.row_key == data.key) {
//       newImageRow.push(context.state.row_data, data);
//     } else {
//       if (context.state.row_data.id != data.id) newImageRow.push(data);
//     }
//   });
//   console.log("newImageRow", newImageRow);
//   if (setNewImgListEn.length != 0) {
//     context.setState({
//       ...context.state,
//       setNewImgListEn: newImageRow,
//     });
//   }
// };

/**
 * handle removing sound image description item
 * @param {*} context
 * @param {position} pos
 */
const removeSoundImage = (context, pos) => {
  const { image_list, remove_sound_img_id } = context.state;
  if (image_list.length > 0) {
    context.setState({
      image_list: image_list.filter((item, index) => index !== pos),
      remove_sound_img_id: [...remove_sound_img_id, image_list[pos].id],
    });
  }
};

/**
 * handle removing sound image description item
 * @param {*} context
 * @param {position} pos
 */
const removeSoundImageEn = (context, pos) => {
  const { image_list_en, remove_sound_img_id } = context.state;
  if (image_list_en.length > 0) {
    context.setState({
      image_list_en: image_list_en.filter((item, index) => index !== pos),
      remove_sound_img_id: [...remove_sound_img_id, image_list_en[pos].id],
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
  context.props.onHideEditModal();
};

/**
 * handle drag end of images
 * @param {*} context
 * @param {result} result
 */
const handleonDragEnd = (result, context, lang) => {
  const explain_image_type = lang == "en" ? `image_list_${lang}` : `image_list`;
  const { [explain_image_type]: explain_img } = context.state;
  const items = Array.from(explain_img);
  const [reorderedItem] = items.splice(result.source.index, 1);
  items.splice(result.destination.index, 0, reorderedItem);
  context.setState({
    [explain_image_type]: items,
  });
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
 * update stetho data
 * @param {*} context
 */
const submitData = (context) => {
  const stetho_data = {
    ...context.state,
  };
  const isValidated = handleValidate(context);
  if (isValidated) {
    context.props.updateStethoData(stetho_data, context.props.editItem.ID);
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
    currentUniversity: state.auth.userInfo.user.university_id,
    examGroup: state.examGroup,
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    userList: state.LibraryUserList,
  };
};

//===================================================
// export
//===================================================
export default connect(mapStateToProps, { getExamGroup, getLibraryUser })(
  withTranslation("translation")(StethoLibraryEdit)
);
