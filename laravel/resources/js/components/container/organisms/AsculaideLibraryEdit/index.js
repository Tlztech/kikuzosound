import React from "react";
import { connect } from "react-redux";

// libs
import { Row, Col, Modal } from "react-bootstrap";
import { DropzoneArea } from "material-ui-dropzone";
import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";
import Select2 from "react-select2-wrapper";

// Components
import Div from "../../../presentational/atoms/Div";
import P from "../../../presentational/atoms/P";
import Button from "../../../presentational/atoms/Button";
import MediaComponent from "../../../presentational/molecules/Media";
import Image from "../../../presentational/atoms/Image";
import Input from "../../../presentational/atoms/Input";
import InputRadio from "../../../presentational/molecules/RadioWithLabel";
import Label from "../../../presentational/atoms/Label";
import InputWithLabel from "../../../presentational/molecules/InputWithLabel";
import InputDescription from "../../../presentational/molecules/InputDescription"
import Span from "../../../presentational/atoms/Span";
import Select from "../../../presentational/atoms/Select";
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

let num_of_alveolar = [];

const content_group_list = [
  { id: 1, value: "lung_sound" },
  { id: 2, value: "heart_sound" },
];
const langs = ["ja", "en"];
const shinon_sounds = ["h1", "h2", "h3", "h4"];

const initial_state = {
  title: "",
  title_en: "",
  description: "",
  description_en: "",
  normal_abnormal: 0,
  image_description_ja: "",
  image_description_en: "",
  sort: "",
  user_id: 0,
  status: "",
  selected_exam_group: [],
  content_group: "1",
  explanatory_image_ja: [],
  explanatory_image_en: [],
  remove_explain_img_id: [],
  body_image: "",
  body_image_back: "",
  coordinate: "",
  supervisor_comment: "",
  group_attribute: 0,
  error: false,
  errors: {},
  user_list: [],
  exam_groups: [],
  //configuration
  configuration: "",
  a_x: "",
  a_y: "",
  a_r: "",
  p_x: "",
  p_y: "",
  p_r: "",
  t_x: "",
  t_y: "",
  t_r: "",
  m_x: "",
  m_y: "",
  m_r: "",
  a_sound_file: "",
  p_sound_file: "",
  t_sound_file: "",
  m_sound_file: "",
  body_image_back_object: {},
  body_image_object: {},
  imageHash: "",
};

class AsculaideLibraryEdit extends React.Component {
  constructor(props) {
    super(props);
    this.state = initial_state;
  }

  componentDidMount() {
    handleFetchExamGroup(this);
    handleSetFormValues(this);
    handleAlevolarSound(this);
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
      title,
      title_en,
      description,
      description_en,
      normal_abnormal,
      image_description_ja,
      image_description_en,
      sort,
      status,
      content_group,
      coordinate,
      a_x,
      a_y,
      a_r,
      p_x,
      p_y,
      p_r,
      t_x,
      t_y,
      t_r,
      m_x,
      m_y,
      m_r,

      tr1_x,
      tr1_y,
      tr1_r,
      tr2_x,
      tr2_y,
      tr2_r,

      br1_x,
      br1_y,
      br1_r,
      br2_x,
      br2_y,
      br2_r,
      br3_x,
      br3_y,
      br3_r,
      br4_x,
      br4_y,
      br4_r,

      ve1_x,
      ve1_y,
      ve1_r,
      ve2_x,
      ve2_y,
      ve2_r,
      ve3_x,
      ve3_y,
      ve3_r,
      ve4_x,
      ve4_y,
      ve4_r,

      ve5_x,
      ve5_y,
      ve5_r,
      ve6_x,
      ve6_y,
      ve6_r,
      ve7_x,
      ve7_y,
      ve7_r,
      ve8_x,
      ve8_y,
      ve8_r,

      ve9_x,
      ve9_y,
      ve9_r,
      ve10_x,
      ve10_y,
      ve10_r,
      ve11_x,
      ve11_y,
      ve11_r,
      ve12_x,
      ve12_y,
      ve12_r,

      a_sound_file,
      pa_sound_file,
      p_sound_file,
      pp_sound_file,
      t_sound_file,
      pt_sound_file,
      m_sound_file,
      pm_sound_file,

      tr1_sound_file,
      tr2_sound_file,

      br1_sound_file,
      br2_sound_file,
      br3_sound_file,
      br4_sound_file,

      ve1_sound_file,
      ve2_sound_file,
      ve3_sound_file,
      ve4_sound_file,
      ve5_sound_file,
      ve6_sound_file,
      ve7_sound_file,
      ve8_sound_file,
      ve9_sound_file,
      ve10_sound_file,
      ve11_sound_file,
      ve12_sound_file,

      explanatory_image_ja,
      explanatory_image_en,
      body_image,
      body_image_object,
      body_image_back,
      body_image_back_object,
      exam_groups,
      selected_exam_group,
      user_id,
      errors,
      group_attribute,
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
        className="organism-edit-ausculaide-library-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label>{t("edit_ausculaide_library")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => cancelEdit(this)}
          />
        </Modal.Header>
        <Modal.Body className="ausculaide-library-organism-modal-body">
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
            <Col>
              <InputDescription
                validateError={errorCollection.includes("description")}
                label={t("description_jp")}
                typeName="textarea"
                name="description"
                value={description || ""}
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["description"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputDescription
                validateError={errorCollection.includes("description_en")}
                label={t("description_en")}
                typeName="textarea"
                value={description_en || ""}
                name="description_en"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["description_en"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col className="form-item">
              <Label className="organism-labelTitle">
                {t("normal")}/{t("abnormal")}
              </Label>
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

          {langs.map((lang, index) => (
            <React.Fragment key={index}>
              <Row className="mb-3">
                <Col>
                  <Label className="organism-labelTitle">
                    {t(`explanatory_image_${lang}`)}
                  </Label>
                  <Button
                    mode="success"
                    onClick={() => addExplainImage(this, lang)}
                  >
                    {t("add_image")}
                  </Button>
                </Col>
              </Row>

              <DragDropContext
                onDragEnd={(result) => handleonDragEnd(result, this, lang)}
              >
                <Droppable droppableId={`explain-image-${lang}`}>
                  {(provided) => (
                    <div {...provided.droppableProps} ref={provided.innerRef}>
                      {eval(`explanatory_image_${lang}`).length != 0 &&
                        eval(`explanatory_image_${lang}`).map((item, index) => (
                          <Draggable
                            key={item.id}
                            draggableId={item.id.toString()}
                            index={index}
                          >
                            {(provided) => (
                              <Row
                                className="mb-2"
                                {...provided.draggableProps}
                                ref={provided.innerRef}
                              >
                                <Col>
                                  <Span className="input-item explain-img-row">
                                    <DropzoneArea
                                      dropzoneText={t("dropzoneText")}
                                      acceptedFiles={[
                                        ".jpg,.png,image/jpeg,image/png",
                                      ]}
                                      showAlerts={false}
                                      filesLimit={1}
                                      showPreviews={true}
                                      showPreviewsInDropzone={false}
                                      previewText=""
                                      onChange={(files) =>
                                        addImageExplainImage(
                                          this,
                                          lang,
                                          index,
                                          files[0]
                                        )
                                      }
                                    />
                                    {eval(`explanatory_image_${lang}`)[index]
                                      .image_path &&
                                      typeof eval(`explanatory_image_${lang}`)[
                                        index
                                      ].image_path == "string" && (
                                        <Image
                                          url={`${
                                            eval(`explanatory_image_${lang}`)[
                                              index
                                            ].image_path
                                          }?${this.state.imageHash}`}
                                          className="icon-img"
                                        />
                                      )}

                                    <Button
                                      mode="danger"
                                      onClick={() =>
                                        removeExplainImg(this, index, lang)
                                      }
                                    >
                                      {t("delete")}
                                    </Button>
                                    <Div {...provided.dragHandleProps}>
                                      <Image
                                        className="drag-icon"
                                        url={DragNdropIcon}
                                      />
                                    </Div>
                                  </Span>
                                </Col>
                              </Row>
                            )}
                          </Draggable>
                        ))}
                      {provided.placeholder}
                    </div>
                  )}
                </Droppable>
              </DragDropContext>
            </React.Fragment>
          ))}

          {/* <Row className="mb-2">
            <Col>
              <Label className="labelstyle ausculaide-library-modal-item">
                {t("explanatory_image_en")}
              </Label>
              <Button
                mode="success"
                onClick={() => addExplainImage(this, "en")}
              >
                {t("add_image")}
              </Button>
            </Col>
          </Row>

          {explanatory_image_en.length != 0 &&
            explanatory_image_en.map((item, index) => (
              <Row className="mb-2" key={index}>
                <Col>
                  <Span className="input-item explain-img-row">
                    <DropzoneArea
                      dropzoneText={t("dropzoneText")}
                      acceptedFiles={["image/*"]}
                      showAlerts={false}
                      filesLimit={1}
                      showPreviews={true}
                      showPreviewsInDropzone={false}
                      previewText=""
                      onChange={(files) =>
                        addImageExplainImage(this, "en", index, files[0])
                      }
                    />
                    {explanatory_image_en[index].image_path &&
                      typeof explanatory_image_en[index].image_path ==
                        "string" && (
                        <Image
                          url={`${process.env.UNIV_ADMIN_API_URL}${explanatory_image_en[index].image_path}?${this.state.imageHash}`}
                          className="icon-img"
                        />
                      )}
                    <Button
                      mode="danger"
                      onClick={() => removeExplainImg(this, index, "en")}
                    >
                      {t("delete")}
                    </Button>
                    <Image className="drag-icon" url={DragNdropIcon} />
                  </Span>
                </Col>
              </Row>
            ))} */}
          <Row className="mb-3">
            <Col>
              <Span className="select-body-image">
                <Label className="organism-labelTitle">
                  {t("body_portrait_front")}
                </Label>
                <Input
                  typeName="file"
                  name="body_image"
                  accept={[".jpg,.png,image/jpeg,image/png"]}
                  onChange={(e) =>
                    this.setState({
                      body_image: e.target.files[0],
                    })
                  }
                />
              </Span>

              {typeof this.state.body_image == "object" &&
              this.state.body_image ? (
                <Image
                  mode="selected-input"
                  url={URL.createObjectURL(this.state.body_image)}
                />
              ) : (
                <Image
                  url={`${body_image}?${this.state.imageHash}`}
                  mode="selected-input"
                />
              )}
            </Col>
          </Row>
          <Row className="mb-3">
            <Col>
              <Span className="select-body-image">
                <Label className="organism-labelTitle">
                  {t("body_portrait_back")}
                </Label>
                <Input
                  typeName="file"
                  name="body_image_back"
                  accept={[".jpg,.png,image/jpeg,image/png"]}
                  onChange={(e) =>
                    this.setState({
                      body_image_back: e.target.files[0],
                    })
                  }
                />
              </Span>

              {typeof this.state.body_image_back == "object" &&
              this.state.body_image_back ? (
                <Image
                  mode="selected-input"
                  url={URL.createObjectURL(this.state.body_image_back)}
                />
              ) : (
                <Image
                  url={`${body_image_back}?${this.state.imageHash}`}
                  mode="selected-input"
                />
              )}
            </Col>
          </Row>

          {/* <Div className="mb-3">
            <Label className="organism-labelTitle">
              {t("body_portrait_front")}
            </Label>
            <Span className="input-item">
              <DropzoneArea
                dropzoneText={t("dropzoneText")}
                acceptedFiles={[".jpg,.png,image/jpeg,image/png"]}
                showAlerts={false}
                filesLimit={1}
                showPreviews={true}
                showPreviewsInDropzone={false}
                previewText=""
                onChange={(files) =>
                  files &&
                  this.setState({
                    body_image_object: files[0],
                  })
                }
              />
              {body_image_object == undefined && body_image && (
                <Image
                  url={`${process.env.UNIV_ADMIN_API_URL}${body_image}?${this.state.imageHash}`}
                  className="icon-img"
                />
              )}
            </Span>
          </Div>
          <Div className="mb-3">
            <Label className="organism-labelTitle">
              {t("body_portrait_back")}
            </Label>
            <Span className="input-item">
              <DropzoneArea
                dropzoneText={t("dropzoneText")}
                acceptedFiles={[".jpg,.png,image/jpeg,image/png"]}
                showAlerts={false}
                filesLimit={1}
                showPreviews={true}
                showPreviewsInDropzone={false}
                previewText=""
                onChange={(files) =>
                  files &&
                  this.setState({
                    body_image_back_object: files[0],
                  })
                }
              />
              {body_image_back_object == undefined && body_image_back && (
                <Image
                  url={`${process.env.UNIV_ADMIN_API_URL}${body_image_back}?${this.state.imageHash}`}
                  className="icon-img"
                />
              )}
            </Span>
          </Div> */}

          {langs.map((lang_item, index) => (
            <React.Fragment key={index}>
              <Row className="mb-3">
                <Col>
                  <InputDescription
                    label={t(`image_description_${lang_item}`)}
                    typeName="textarea"
                    name={`image_description_${lang_item}`}
                    value={eval(`image_description_${lang_item}`)}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                </Col>
              </Row>
            </React.Fragment>
          ))}
          <Row className="mb-3">
            <Col>
              <Label className="labelstyle"> {t("configuration")} </Label>
            </Col>

            <Col xs={9} className="flx configuration-item">
              {/* row value */}
              <Row className="ml-2 mt-2 input-sound">
                <Label>A</Label>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="a_x"
                    value={a_x || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>

                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="a_y"
                    value={a_y || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="a_r"
                    value={a_r || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
              </Row>

              {/* a file */}
              <Div className="ml-4">
                <Row className="flex-choose">
                  <Label className="mt-2">{t("Heart")}</Label>

                  <Input
                    name="a_sound_file"
                    accept="audio/*"
                    typeName="file"
                    onChange={(event) => handleSoundFile(event, this)}
                  />
                </Row>

                <Row>
                  <MediaComponent file={a_sound_file} type="sound" />
                </Row>

                <Row className="flex-choose">
                  <Label className="mt-2">{t("Pulse")}</Label>

                  <Input
                    typeName="file"
                    name="pa_sound_file"
                    accept="audio/*"
                    onChange={(event) => handleSoundFile(event, this)}
                  />
                </Row>
                <Row>
                  <MediaComponent file={pa_sound_file} type="sound" />
                </Row>
              </Div>

              {/* p value */}
              <Row className="ml-2 mt-2 input-sound">
                <Label>P</Label>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="p_x"
                    value={p_x || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="p_y"
                    value={p_y || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="p_r"
                    value={p_r || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
              </Row>

              {/* p file */}
              <Div className="ml-4">
                <Row className="flex-choose">
                  <Label className="mt-2">{t("Heart")}</Label>

                  <Input
                    name="p_sound_file"
                    typeName="file"
                    accept="audio/*"
                    onChange={(event) => handleSoundFile(event, this)}
                  />
                </Row>
                <Row>
                  <MediaComponent file={p_sound_file} type="sound" />
                </Row>

                <Row className="flex-choose">
                  <Label className="mt-2">{t("Pulse")}</Label>

                  <Input
                    name="pp_sound_file"
                    typeName="file"
                    accept="audio/*"
                    onChange={(event) => handleSoundFile(event, this)}
                  />
                </Row>
                <Row>
                  <MediaComponent file={pp_sound_file} type="sound" />
                </Row>
              </Div>

              {/* t value */}
              <Row className="ml-2 mt-2 input-sound">
                <Label>T</Label>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="t_x"
                    value={t_x || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="t_y"
                    value={t_y || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="t_r"
                    value={t_r || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
              </Row>
              {/* t file */}
              <Div className="ml-4">
                <Row className="flex-choose">
                  <Label className="mt-2">{t("Heart")}</Label>

                  <Input
                    name="t_sound_file"
                    typeName="file"
                    accept="audio/*"
                    onChange={(event) => handleSoundFile(event, this)}
                  />
                </Row>
                <Row>
                  <MediaComponent file={t_sound_file} type="sound" />
                </Row>

                <Row className="flex-choose">
                  <Label className="mt-2">{t("Pulse")}</Label>

                  <Input
                    name="pt_sound_file"
                    typeName="file"
                    accept="audio/*"
                    onChange={(event) => handleSoundFile(event, this)}
                  />
                </Row>
                <Row>
                  <MediaComponent file={pt_sound_file} type="sound" />
                </Row>
              </Div>
              {/* m values */}
              <Row className="ml-2  mt-2 input-sound">
                <Label>M</Label>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="m_x"
                    value={m_x || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="m_y"
                    value={m_y || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="m_r"
                    value={m_r || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
              </Row>
              {/* m file */}
              <Div className="ml-4">
                <Row className="flex-choose">
                  <Label className="mt-2">{t("Heart")}</Label>

                  <Input
                    name="m_sound_file"
                    typeName="file"
                    accept="audio/*"
                    onChange={(event) => handleSoundFile(event, this)}
                  />
                </Row>
                <Row>
                  <MediaComponent file={m_sound_file} type="sound" />
                </Row>

                <Row className="flex-choose">
                  <Label className="mt-2">{t("Pulse")}</Label>

                  <Input
                    name="pm_sound_file"
                    typeName="file"
                    accept="audio/*"
                    onChange={(event) => handleSoundFile(event, this)}
                  />
                </Row>
                <Row>
                  <MediaComponent file={pm_sound_file} type="sound" />
                </Row>
              </Div>

              {/* Shinon sounds */}
              <Label>{t("Heart sounds")}</Label>

              {shinon_sounds.map((shinon, index) => (
                <React.Fragment key={index}>
                  <Row className="ml-2 mt-2 input-sound">
                    <Label></Label>
                    <Span>
                      <Input
                        typeName="number"
                        className="px-no"
                        name={`${shinon}_x`}
                        value={this.state[`${shinon}_x`] || ""}
                        onChange={(event) => handleChangeForm(event, this)}
                      />
                      (px)
                    </Span>
                    <Span>
                      <Input
                        typeName="number"
                        className="px-no"
                        name={`${shinon}_y`}
                        value={this.state[`${shinon}_y`] || ""}
                        onChange={(event) => handleChangeForm(event, this)}
                      />
                      (px)
                    </Span>
                    <Span>
                      <Input
                        typeName="number"
                        className="px-no"
                        name={`${shinon}_r`}
                        value={this.state[`${shinon}_r`] || ""}
                        onChange={(event) => handleChangeForm(event, this)}
                      />
                      (px)
                    </Span>
                  </Row>

                  <Row className="ml-2 sound-choose">
                    <Input
                      typeName="file"
                      name={`${shinon}_sound_file`}
                      accept="audio/*"
                      onChange={(event) => handleSoundFile(event, this)}
                    />
                    <MediaComponent
                      file={this.state[`${shinon}_sound_file`]}
                      type="sound"
                    />
                  </Row>
                </React.Fragment>
              ))}

              {/* Shinon sounds end */}

              {/* tracheal 1*/}
              <Label>{t("Tracheal breath sounds")}</Label>
              <Row className="ml-2 mt-2 input-sound">
                <Label></Label>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="tr1_x"
                    value={tr1_x || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="tr1_y"
                    value={tr1_y || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="tr1_r"
                    value={tr1_r || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
              </Row>

              <Row className="ml-2 sound-choose">
                <Input
                  typeName="file"
                  name="tr1_sound_file"
                  accept="audio/*"
                  onChange={(event) => handleSoundFile(event, this)}
                />
                <MediaComponent file={tr1_sound_file} type="sound" />
              </Row>

              {/* tracheal 2*/}
              <Row className="ml-2 mt-2 input-sound">
                <Label></Label>

                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="tr2_x"
                    value={tr2_x || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="tr2_y"
                    value={tr2_y || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="tr2_r"
                    value={tr2_r || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
              </Row>

              <Row className="ml-2 sound-choose">
                <Input
                  typeName="file"
                  name="tr2_sound_file"
                  accept="audio/*"
                  onChange={(event) => handleSoundFile(event, this)}
                />
                <MediaComponent file={tr2_sound_file} type="sound" />
              </Row>

              {/* Bronchail front start */}

              {/* Bronchial 1*/}
              <Label>{t("Bronchial breath sounds")}</Label>
              <Label className="frontback-text">({t("front")})</Label>

              <Row className="ml-2 mt-2 input-sound">
                <Label></Label>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br1_x"
                    value={br1_x || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br1_y"
                    value={br1_y || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br1_r"
                    value={br1_r || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
              </Row>

              <Row className="ml-2 sound-choose">
                <Input
                  typeName="file"
                  name="br1_sound_file"
                  accept="audio/*"
                  onChange={(event) => handleSoundFile(event, this)}
                />
                <MediaComponent file={br1_sound_file} type="sound" />
              </Row>

              {/* Bronchial 2*/}
              <Row className="ml-2 mt-2 input-sound">
                <Label></Label>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br2_x"
                    value={br2_x || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br2_y"
                    value={br2_y || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br2_r"
                    value={br2_r || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
              </Row>

              <Row className="ml-2 sound-choose">
                <Input
                  typeName="file"
                  name="br2_sound_file"
                  accept="audio/*"
                  onChange={(event) => handleSoundFile(event, this)}
                />
                <MediaComponent file={br2_sound_file} type="sound" />
              </Row>
              {/* Bronchial 2 end*/}

              {/* Bronchial front end*/}

              {/* Bronchial back start */}
              {/* Bronchial back 1 */}
              <Label className="frontback-text">({t("back")})</Label>

              <Row className="ml-2 mt-2 input-sound">
                <Label></Label>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br3_x"
                    value={br3_x || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br3_y"
                    value={br3_y || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br3_r"
                    value={br3_r || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
              </Row>

              <Row className="ml-2 sound-choose">
                <Input
                  typeName="file"
                  name="br3_sound_file"
                  accept="audio/*"
                  onChange={(event) => handleSoundFile(event, this)}
                />
                <MediaComponent file={br3_sound_file} type="sound" />
              </Row>

              {/* Bronchial back 2 */}
              <Row className="ml-2 mt-2 input-sound">
                <Label></Label>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br4_x"
                    value={br4_x || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br4_y"
                    value={br4_y || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br4_r"
                    value={br4_r || ""}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
              </Row>

              <Row className="ml-2 sound-choose">
                <Input
                  typeName="file"
                  name="br4_sound_file"
                  accept="audio/*"
                  onChange={(event) => handleSoundFile(event, this)}
                />
                <MediaComponent file={br4_sound_file} type="sound" />
              </Row>
              {/* Bronchial end */}

              {/* Alveolar breath sounds */}
              <Label>{t("Alveolar breath sounds")}</Label>
              {num_of_alveolar.map((item, index) => (
                <React.Fragment key={index}>
                  <Label className="frontback-text">
                    {(item == 1 && `(${t("front")})`) ||
                      (item == 7 && `(${t("back")})`)}
                  </Label>
                  <Row className="ml-2 mt-2 input-sound">
                    <Label></Label>
                    <Span>
                      <Input
                        typeName="number"
                        className="px-no"
                        name={`ve${item}_x`}
                        value={eval(`ve${item}_x`) || ""}
                        onChange={(event) => handleChangeForm(event, this)}
                      />
                      (px)
                    </Span>
                    <Span>
                      <Input
                        typeName="number"
                        className="px-no"
                        name={`ve${item}_y`}
                        value={eval(`ve${item}_y`) || ""}
                        onChange={(event) => handleChangeForm(event, this)}
                      />
                      (px)
                    </Span>
                    <Span>
                      <Input
                        typeName="number"
                        className="px-no"
                        name={`ve${item}_r`}
                        value={eval(`ve${item}_r`) || ""}
                        onChange={(event) => handleChangeForm(event, this)}
                      />
                      (px)
                    </Span>
                  </Row>
                  <Row className="ml-2 sound-choose">
                    <Input
                      typeName="file"
                      name={`ve${item}_sound_file`}
                      accept="audio/*"
                      onChange={(event) => handleSoundFile(event, this)}
                    />
                    <MediaComponent
                      file={this.state[`ve${item}_sound_file`]}
                      type="sound"
                    />
                  </Row>
                </React.Fragment>
              ))}
            </Col>
          </Row>
          <Row className="mb-3">
            <Col className="organism-labelwithselect">
              <Label className="organism-labelstyle">
                {t("content_group")}
              </Label>
              <Select
                className="organism-select-style"
                items={content_group_list}
                value={content_group}
                onChange={(event) => handleSelect(event, this, "content_group")}
              ></Select>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col>
              <InputWithLabel
                className="organism-input-style"
                label={t("sort")}
                typeName="number"
                name="sort"
                value={sort || ""}
                onChange={(event) => handleChangeForm(event, this)}
              />
            </Col>
          </Row>
          <Row className="mb-3">
            <Col>
              <InputWithLabel
                className="organism-input-style"
                label={t("coordinate")}
                name="coordinate"
                value={coordinate || ""}
                onChange={(event) => handleChangeForm(event, this)}
                typeName="text"
              />
            </Col>
          </Row>
          <Row className="mb-3">
            <Col>
              <Label className="organism-labelTitle">{t("status")}</Label>
              <InputRadio
                title={t("public")}
                name="status"
                value={3}
                defaultChecked={status == 2 || status == 3 ? "checked" : ""}
                onClick={(event) => handleChangeForm(event, this)}
              />
              <InputRadio
                title={t("private")}
                name="status"
                value={1}
                defaultChecked={status == 0 || status == 1 ? "checked" : ""}
                onClick={(event) => handleChangeForm(event, this)}
              />
            </Col>
          </Row>
          {/* <Row>
            <Col>
              <Label className="organism-labelTitle">
                {t("group_attributes")}
              </Label>
              <Span>
                <InputRadio
                  title={t("yes")}
                  name="group_attribute"
                  value={0}
                  defaultChecked={group_attribute == 0 ? "checked" : ""}
                  onClick={event => handleChangeForm(event, this)}
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
                  onClick={event => handleChangeForm(event, this)}
                />
                <Col className="organism-aus-group-attribute-item">
                  <ExamGroupItem
                    selected_exam_group={selected_exam_group}
                    onClick={index =>
                      handleRemoveExamGroup(index, this, "selected_exam_group")
                    }
                  />
                </Col>
              </Span>
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
  if (editItem) {
    context.setState({
      title: editItem.title || "",
      title_en: editItem.title_en || "",
      description: editItem.description || "",
      description_en: editItem.description_en || "",
      normal_abnormal: editItem.normal_abnormal === "normal" ? 1 : 0,
      image_description_ja: editItem.image_description,
      image_description_en: editItem.image_description_en,
      sort: editItem.sort || "",
      coordinate: editItem.coordinate,
      supervisor_comment: editItem.supervisor_comment || "",
      status: editItem.status,
      user_id: editItem.user_id,
      group_attribute: editItem.group_attribute,
      selected_exam_group: editItem.selected_exam_group,
      a_r: editItem.a_r || "",
      a_x: editItem.a_x || "",
      a_y: editItem.a_y || "",
      area: editItem.area,
      m_r: editItem.m_r || "",
      m_x: editItem.m_x || "",
      m_y: editItem.m_y || "",
      p_r: editItem.p_r || "",
      p_x: editItem.p_x || "",
      p_y: editItem.p_y || "",
      t_r: editItem.t_r || "",
      t_x: editItem.t_x || "",
      t_y: editItem.t_y || "",

      // shinon
      h1_x: editItem.h1_x || "",
      h1_y: editItem.h1_y || "",
      h1_r: editItem.h1_r || "",

      h2_x: editItem.h2_x || "",
      h2_y: editItem.h2_y || "",
      h2_r: editItem.h2_r || "",

      h3_x: editItem.h3_x || "",
      h3_y: editItem.h3_y || "",
      h3_r: editItem.h3_r || "",

      h4_x: editItem.h4_x || "",
      h4_y: editItem.h4_y || "",
      h4_r: editItem.h4_r || "",

      // tracheal
      tr1_x: editItem.tr1_x || "",
      tr1_y: editItem.tr1_y || "",
      tr1_r: editItem.tr1_r || "",

      tr2_x: editItem.tr2_x || "",
      tr2_y: editItem.tr2_y || "",
      tr2_r: editItem.tr2_r || "",

      // bronchial
      br1_x: editItem.br1_x || "",
      br1_y: editItem.br1_y || "",
      br1_r: editItem.br1_r || "",

      br2_x: editItem.br2_x || "",
      br2_y: editItem.br2_y || "",
      br2_r: editItem.br2_r || "",

      br3_x: editItem.br3_x || "",
      br3_y: editItem.br3_y || "",
      br3_r: editItem.br3_r || "",

      br4_x: editItem.br4_x || "",
      br4_y: editItem.br4_y || "",
      br4_r: editItem.br4_r || "",

      // alveolar
      ve1_x: editItem.ve1_x || "",
      ve1_y: editItem.ve1_y || "",
      ve1_r: editItem.ve1_r || "",

      ve2_x: editItem.ve2_x || "",
      ve2_y: editItem.ve2_y || "",
      ve2_r: editItem.ve2_r || "",
      ve3_x: editItem.ve3_x || "",
      ve3_y: editItem.ve3_y || "",
      ve3_r: editItem.ve3_r || "",
      ve4_x: editItem.ve4_x || "",
      ve4_y: editItem.ve4_y || "",
      ve4_r: editItem.ve4_r || "",
      ve5_x: editItem.ve5_x || "",
      ve5_y: editItem.ve5_y || "",
      ve5_r: editItem.ve5_r || "",
      ve6_x: editItem.ve6_x || "",
      ve6_y: editItem.ve6_y || "",
      ve6_r: editItem.ve6_r || "",
      ve7_x: editItem.ve7_x || "",
      ve7_y: editItem.ve7_y || "",
      ve7_r: editItem.ve7_r || "",
      ve8_x: editItem.ve8_x || "",
      ve8_y: editItem.ve8_y || "",
      ve8_r: editItem.ve8_r || "",
      ve9_x: editItem.ve9_x || "",
      ve9_y: editItem.ve9_y || "",
      ve9_r: editItem.ve9_r || "",
      ve10_x: editItem.ve10_x || "",
      ve10_y: editItem.ve10_y || "",
      ve10_r: editItem.ve10_r || "",
      ve11_x: editItem.ve11_x || "",
      ve11_y: editItem.ve11_y || "",
      ve11_r: editItem.ve11_r || "",
      ve12_x: editItem.ve12_x || "",
      ve12_y: editItem.ve12_y || "",
      ve12_r: editItem.ve12_r || "",

      a_sound_file: editItem.a_sound_file || null,
      pa_sound_file: editItem.pa_sound_file || null,
      m_sound_file: editItem.m_sound_file || null,
      pm_sound_file: editItem.pm_sound_file || null,
      p_sound_file: editItem.p_sound_file || null,
      pp_sound_file: editItem.pp_sound_file || null,
      t_sound_file: editItem.t_sound_file || null,
      pt_sound_file: editItem.pt_sound_file || null,

      // shinon
      h1_sound_file: editItem.h1_sound_file || null,
      h2_sound_file: editItem.h2_sound_file || null,
      h3_sound_file: editItem.h3_sound_file || null,
      h4_sound_file: editItem.h4_sound_file || null,

      // tracheal
      tr1_sound_file: editItem.tr1_sound_file || null,
      tr2_sound_file: editItem.tr2_sound_file || null,

      // bronchial
      br1_sound_file: editItem.br1_sound_file || null,
      br2_sound_file: editItem.br2_sound_file || null,
      br3_sound_file: editItem.br3_sound_file || null,
      br4_sound_file: editItem.br4_sound_file || null,

      // alveolar
      ve1_sound_file: editItem.ve1_sound_file || null,
      ve2_sound_file: editItem.ve2_sound_file || null,
      ve3_sound_file: editItem.ve3_sound_file || null,
      ve4_sound_file: editItem.ve4_sound_file || null,

      ve5_sound_file: editItem.ve5_sound_file || null,
      ve6_sound_file: editItem.ve6_sound_file || null,
      ve7_sound_file: editItem.ve7_sound_file || null,
      ve8_sound_file: editItem.ve8_sound_file || null,

      ve9_sound_file: editItem.ve9_sound_file || null,
      ve10_sound_file: editItem.ve10_sound_file || null,
      ve11_sound_file: editItem.ve11_sound_file || null,
      ve12_sound_file: editItem.ve12_sound_file || null,

      explanatory_image_ja: editItem.explanatory_image,
      explanatory_image_en: editItem.explanatory_image_en,
      content_group: getItemType(parseInt(editItem.content_group)),
      body_image: editItem.body_image,
      body_image_back: editItem.body_image_back,
      body_image: editItem.body_image || "",
      body_image_back: editItem.body_image_back || "",
    });
  }
};

/**
 * get Item Type
 * @param {*} item_no
 */
const getItemType = (item_no) => {
  switch (item_no) {
    case 1:
      return 1;
    case 2:
      return 2;
    case 3:
      return 3;
    default:
      return 9;
  }
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
    if (context.props.examGroup.examGroupList) {
      exam_groups = [
        ...context.props.examGroup.examGroupList.map((exam_group) => ({
          id: exam_group.id,
          text: exam_group.name,
        })),
      ];
    }
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
    imageHash: Date.now(),
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
 * sound file
 * @param {*} event
 * @param {*} context
 */
const handleSoundFile = (event, context) => {
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
 * validate input
 * @param {*} context
 */
const handleValidate = (context) => {
  const { title, title_en, description, description_en } = context.state;
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

  context.setState({ errors: errors });
  return formIsValid;
};

const handleAlevolarSound = (context) => {
  const { editItem } = context.props;
  for (let i = 1; i < 13; i++) {
    num_of_alveolar = num_of_alveolar.concat(i);
    context.setState({
      [`ve${i}_sound_file`]: editItem[`ve${i}_sound_file`],
    });
  }
};

/**
 * addExplainImage
 * @param {*} context
 * @param {lang} lang
 */
const addExplainImage = (context, lang) => {
  let counter_type;
  counter_type = lang == "ja" ? "explanatory_image_ja" : "explanatory_image_en";
  const { [counter_type]: counter } = context.state;
  context.setState({
    [counter_type]: counter.concat({
      key: Date.now(),
      id: counter.length,
      disp_order: counter.length,
      image_path: null,
    }),
  });
};

/**
 * add image to explain image
 * @param {*} context
 * @param {lang} lang
 */
const addImageExplainImage = (context, lang, index, file) => {
  let counter_type;
  counter_type = lang == "ja" ? "explanatory_image_ja" : "explanatory_image_en";
  const { [counter_type]: counter } = context.state;
  if (file) {
    counter[index] = {
      ...counter[index],
      key: Date.now(),
      image_path: file,
    };
  }
  context.setState({
    [counter_type]: counter,
  });
};

/**
 * remove image from explain image
 * @param {*} context
 * @param {lang} lang
 */
const removeExplainImg = (context, pos, lang) => {
  let counter_type;
  counter_type = lang == "ja" ? "explanatory_image_ja" : "explanatory_image_en";
  const { [counter_type]: counter, remove_explain_img_id } = context.state;
  context.setState({
    [counter_type]: counter.filter((item, index) => index !== pos),
    remove_explain_img_id: [...remove_explain_img_id, counter[pos].id],
  });
};

/**
 * handle drag end of images
 * @param {*} context
 * @param {result} result
 */
const handleonDragEnd = (result, context, lang) => {
  const explain_image_type = `explanatory_image_${lang}`;
  const { [explain_image_type]: explain_img } = context.state;
  const items = Array.from(explain_img);
  const [reorderedItem] = items.splice(result.source.index, 1);
  items.splice(result.destination.index, 0, reorderedItem);
  context.setState({
    [explain_image_type]: items,
  });
};

/**
 * update ausculaide item
 * @param {*} context
 */
const submitData = (context) => {
  const ausculaide_data = {
    ...context.state,
  };
  const isValidated = handleValidate(context);
  if (isValidated) {
    context.props.updateAusculaideData(
      ausculaide_data,
      context.props.editItem.ID
    );
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
export default connect(mapStateToProps, {
  getExamGroup,
  getLibraryUser,
})(withTranslation("translation")(AsculaideLibraryEdit));
