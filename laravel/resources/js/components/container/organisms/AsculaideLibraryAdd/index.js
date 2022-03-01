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
import Input from "../../../presentational/atoms/Input";
import InputRadio from "../../../presentational/molecules/RadioWithLabel";
import Label from "../../../presentational/atoms/Label";
import InputWithLabel from "../../../presentational/molecules/InputWithLabel";
import InputDescription from "../../../presentational/molecules/InputDescription"
import Span from "../../../presentational/atoms/Span";
import Select from "../../../presentational/atoms/Select";
import ExamGroupItem from "../../../presentational/molecules/ExamGroupItem";
import Image from "../../../presentational/atoms/Image";

//redux
import { getLibraryUser } from "../../../../redux/modules/actions/LibraryUserAction";
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
let num_of_alveolar = [];

const content_group = [
  { id: 1, value: "lung_sound" },
  { id: 2, value: "heart_sound" },
];
const langs = ["ja", "en"];
const shinon_sounds = ["h1", "h2", "h3", "h4"];
const initial_state = {
  title: null,
  title_en: null,
  description: "",
  description_en: "",
  normal_abnormal: 1,
  image_description_ja: "",
  image_description_en: "",
  sort: null,
  supervisor: "0",
  supervisor_comment: "",
  status: 1,
  exam_groups: [],
  selected_exam_group: [],
  user_list: [],
  selected_content_group: "1",
  explanatory_image_ja: [],
  explanatory_image_en: [],
  body_image: null,
  body_image_back: null,
  coordinate: "",
  errors: {},
  group_attribute: 1,
  a_x: null,
  a_y: null,
  a_r: null,
  p_x: null,
  p_y: null,
  p_r: null,
  t_x: null,
  t_y: null,
  t_r: null,
  m_x: null,
  m_y: null,
  m_r: null,
  a_sound_file: null,
  pa_sound_file: null,
  p_sound_file: null,
  pp_sound_file: null,
  t_sound_file: null,
  pt_sound_file: null,
  m_sound_file: null,
  pm_sound_file: null,
  tr1_sound_file: null,
  tr2_sound_file: null,
  br1_sound_file: null,
  br2_sound_file: null,
  br3_sound_file: null,
  br4_sound_file: null,
  num_of_alveolar: [],
};
class AculaideLIbraryAdd extends React.Component {
  constructor(props) {
    super(props);
    this.state = initial_state;
  }

  componentDidMount() {
    handleFetchData(this);
    handleAlevolarSound(this);
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
      exam_groups,
      selected_exam_group,
      status,
      normal_abnormal,
      group_attribute,
      explanatory_image_ja,
      explanatory_image_en,
      selected_content_group,
    } = this.state;
    const errorCollection = errors && Object.keys(errors);
    const errorValue = errors && Object.values(errors);
    return (
      <Modal
        show={isVisible}
        onHide={() => cancelAdd(this)}
        size="lg"
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
        className="organism-add-ausculaide-library-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label>{t("add_ausculaide_library")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => cancelAdd(this)}
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
          <Row className="mb-3 textarea">
            <Col>
              <InputDescription
                validateError={errorCollection.includes("description")}
                label={t("description_jp")}
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
                label={t("description_en")}
                name="description_en"
                typeName="textarea"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["description_en"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
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
          {langs.map((lang_item, index) => (
            <React.Fragment key={index}>
              <Row className="mb-3">
                <Col>
                  <Label className="organism-labelTitle">
                    {t(`explanatory_image_${lang_item}`)}
                  </Label>
                  <Button
                    mode="success"
                    onClick={() => addExplainImage(this, lang_item)}
                  >
                    {t("add_image")}
                  </Button>
                </Col>
              </Row>

              <DragDropContext
                onDragEnd={(result) => handleonDragEnd(result, this, lang_item)}
              >
                <Droppable droppableId={`explain-image-${lang_item}`}>
                  {(provided) => (
                    <div {...provided.droppableProps} ref={provided.innerRef}>
                      {eval(`explanatory_image_${lang_item}`).length != 0 &&
                        eval(`explanatory_image_${lang_item}`).map(
                          (item, index) => (
                            <Draggable
                              key={item.id}
                              draggableId={item.id.toString()}
                              index={index}
                            >
                              {(provided) => (
                                <Row
                                  className="mb-3"
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
                                            lang_item,
                                            index,
                                            files[0]
                                          )
                                        }
                                      />
                                      <Button
                                        mode="danger"
                                        onClick={() =>
                                          removeExplainImg(
                                            this,
                                            index,
                                            lang_item
                                          )
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
                          )
                        )}
                      {provided.placeholder}
                    </div>
                  )}
                </Droppable>
              </DragDropContext>
            </React.Fragment>
          ))}
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

              {this.state.body_image && (
                <Image
                  url={URL.createObjectURL(this.state.body_image)}
                  mode="selected-input"
                />
              )}

              {/* <Span className="input-item">
                <DropzoneArea
                  dropzoneText={t("dropzoneText")}
                  acceptedFiles={[".jpg,.png,image/jpeg,image/png"]}
                  showAlerts={false}
                  filesLimit={1}
                  showPreviews={true}
                  showPreviewsInDropzone={false}
                  previewText=""
                  onChange={(files) =>
                    this.setState({
                      body_image: files[0],
                    })
                  }
                />
              </Span> */}
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
              {this.state.body_image_back && (
                <Image
                  url={URL.createObjectURL(this.state.body_image_back)}
                  mode="selected-input"
                />
              )}

              {/* <Span className="input-item">
                <DropzoneArea
                  dropzoneText={t("dropzoneText")}
                  acceptedFiles={[".jpg,.png,image/jpeg,image/png"]}
                  showAlerts={false}
                  filesLimit={1}
                  showPreviews={true}
                  showPreviewsInDropzone={false}
                  previewText=""
                  onChange={(files) =>
                    this.setState({
                      body_image_back: files[0],
                    })
                  }
                />
              </Span> */}
            </Col>
          </Row>
          {langs.map((lang, index) => (
            <React.Fragment key={index}>
              <Row className="mb-3">
                <Col>
                  <InputDescription
                    label={t(`image_description_${lang}`)}
                    typeName="textarea"
                    name={`image_description_${lang}`}
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                </Col>
              </Row>
            </React.Fragment>
          ))}
          <Row className="mb-3">
            <Col>
              <Label className="labelstyle">{t("configuration")}</Label>
            </Col>
            <Col xs={9} className="flx configuration-item">
              <Row className="ml-2 mt-2 input-sound">
                <Label>A</Label>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="a_x"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="a_y"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="a_r"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
              </Row>

              <Div className="ml-4">
                <Row className="flex-choose">
                  <Label className="mt-2">{t("Heart")}</Label>
                  <Input
                    typeName="file"
                    name="a_sound_file"
                    accept="audio/*"
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

              <Row className="ml-2 mt-2 input-sound">
                <Label>P</Label>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="p_x"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="p_y"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="p_r"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
              </Row>

              <Div className="ml-4">
                <Row className="flex-choose">
                  <Label className="mt-2">{t("Heart")}</Label>
                  <Input
                    typeName="file"
                    name="p_sound_file"
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
                    typeName="file"
                    name="pp_sound_file"
                    accept="audio/*"
                    onChange={(event) => handleSoundFile(event, this)}
                  />
                </Row>
                <Row>
                  <MediaComponent file={pp_sound_file} type="sound" />
                </Row>
              </Div>

              <Row className="ml-2 mt-2 input-sound">
                <Label>T</Label>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="t_x"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="t_y"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="t_r"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
              </Row>

              <Div className="ml-4">
                <Row className="flex-choose">
                  <Label className="mt-2">{t("Heart")}</Label>

                  <Input
                    typeName="file"
                    name="t_sound_file"
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
                    typeName="file"
                    name="pt_sound_file"
                    accept="audio/*"
                    onChange={(event) => handleSoundFile(event, this)}
                  />
                </Row>
                <Row>
                  <MediaComponent file={pt_sound_file} type="sound" />
                </Row>
              </Div>

              <Row className="ml-2  mt-2 input-sound">
                <Label>M</Label>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="m_x"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="m_y"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="m_r"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
              </Row>

              <Div className="ml-4">
                <Row className="flex-choose">
                  <Label className="mt-2">{t("Heart")}</Label>

                  <Input
                    typeName="file"
                    name="m_sound_file"
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
                    typeName="file"
                    name="pm_sound_file"
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
                        onChange={(event) => handleChangeForm(event, this)}
                      />
                      (px)
                    </Span>
                    <Span>
                      <Input
                        typeName="number"
                        className="px-no"
                        name={`${shinon}_y`}
                        onChange={(event) => handleChangeForm(event, this)}
                      />
                      (px)
                    </Span>
                    <Span>
                      <Input
                        typeName="number"
                        className="px-no"
                        name={`${shinon}_r`}
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
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="tr1_y"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="tr1_r"
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
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="tr2_y"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="tr2_r"
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
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br1_y"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br1_r"
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
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br2_y"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br2_r"
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
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br3_y"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br3_r"
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
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br4_y"
                    onChange={(event) => handleChangeForm(event, this)}
                  />
                  (px)
                </Span>
                <Span>
                  <Input
                    typeName="number"
                    className="px-no"
                    name="br4_r"
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
                        onChange={(event) => handleChangeForm(event, this)}
                      />
                      (px)
                    </Span>
                    <Span>
                      <Input
                        typeName="number"
                        className="px-no"
                        name={`ve${item}_y`}
                        onChange={(event) => handleChangeForm(event, this)}
                      />
                      (px)
                    </Span>
                    <Span>
                      <Input
                        typeName="number"
                        className="px-no"
                        name={`ve${item}_r`}
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
                items={content_group}
                value={selected_content_group}
                onChange={(event) =>
                  handleSelect(event, this, "selected_content_group")
                }
              ></Select>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col>
              <InputWithLabel
                className="organism-input-style"
                label={t("sort")}
                name="sort"
                typeName="number"
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
                typeName="text"
                onChange={(event) => handleChangeForm(event, this)}
              />
            </Col>
          </Row>
          <Row className="mb-2">
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
 * fetch exam groups and user
 * @param {*} context
 */
const handleFetchData = async (context) => {
  await context.props.getLibraryUser(context.props.userToken);
  await context.props.getExamGroup(context.props.userToken);
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
    if (context.props.userList.user_list) {
      user_list = [
        ...context.props.userList.user_list.map((list) => ({
          id: list.id,
          value: list.name,
        })),
      ];
    } else user_list[0] = { id: "", value: "" };
  }
  context.setState(
    {
      exam_groups,
      user_list,
    }
    // ,
    // () =>
    //   context.setState({
    //     supervisor: user_list[0].id,
    //   })
  );
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
const handleSoundFile = (event, context) => {
  context.setState({ [event.target.name]: event.target.files[0] });
};

/**
 * cancel add data
 * @param {*} context
 */
const cancelAdd = (context) => {
  context.setState({
    ...initial_state,
  });
  context.props.onHideAddModal();
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
  const { title, title_en, description, description_en} = context.state;
  const { t } = context.props;
  let errors = {};
  let formIsValid = true;
  //title
  if (!title || title.trim.length < 0) {
    formIsValid = false;
    errors["title"] = t("validate_title_jp");
  }
  //description
  // if (!description || description.trim.length < 0) {
  //   formIsValid = false;
  //   errors["description"] = t("validate_description_jp");
  // }
  //title_en
  if (!title_en || title_en.trim.length < 0) {
    formIsValid = false;
    errors["title_en"] = t("validate_title_en");
  }

  context.setState({ errors: errors });
  return formIsValid;
};

const handleAlevolarSound = (context) => {
  for (let i = 1; i < 13; i++) {
    num_of_alveolar = num_of_alveolar.concat(i);
    context.setState({
      [`ve${i}_sound_file`]: null,
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
      image: null,
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
  counter[index].image = file;
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
  const { [counter_type]: counter } = context.state;
  context.setState({
    [counter_type]: counter.filter((item, index) => index !== pos),
  });
};

/**
 * handle drag end of images
 * @param {*} context
 * @param {result} result
 */
const handleonDragEnd = (result, context, lang_item) => {
  const explain_image_type = `explanatory_image_${lang_item}`;
  const { [explain_image_type]: explain_img } = context.state;
  const items = Array.from(explain_img);
  const [reorderedItem] = items.splice(result.source.index, 1);
  items.splice(result.destination.index, 0, reorderedItem);
  context.setState({
    [explain_image_type]: items,
  });
};

/**
 * submit ausculaide Data
 * @param {*} context
 */
const submitData = (context) => {
  const ausculaide_data = {
    ...context.state,
  };
  const isValidated = handleValidate(context);
  if (isValidated) {
    context.props.addAusculaideData(ausculaide_data);
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
export default connect(mapStateToProps, { getLibraryUser, getExamGroup })(
  withTranslation("translation")(AculaideLIbraryAdd)
);
