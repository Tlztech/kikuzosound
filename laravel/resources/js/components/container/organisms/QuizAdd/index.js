import React from "react";
import { connect } from "react-redux";

// libs
import { Row, Col, Modal, Table } from "react-bootstrap";
import { DropzoneArea } from "material-ui-dropzone";
import { DragDropContext, Draggable, Droppable } from "react-beautiful-dnd";

// Components
import Div from "../../../presentational/atoms/Div";
import P from "../../../presentational/atoms/P";
import BR from "../../../presentational/atoms/Br";
import Button from "../../../presentational/atoms/Button";
import Image from "../../../presentational/atoms/Image";
import InputRadio from "../../../presentational/molecules/RadioWithLabel";
import Input from "../../../presentational/atoms/Input";
import Label from "../../../presentational/atoms/Label";
import InputWithLabel from "../../../presentational/molecules/InputWithLabel";
import Span from "../../../presentational/atoms/Span";
import Select from "../../../presentational/atoms/Select";
import RadioButton from "../../../presentational/atoms/RadioButton";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";
import { DragNdropIcon } from "../../../../assets";

// Redux
import { getExamGroup } from "../../../../redux/modules/actions/ExamGroupAction";
import { getLibraryUser } from "../../../../redux/modules/actions/LibraryUserAction";

// css
import "./style.css";

//i18n
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================

const case_gender_items = [
  { id: 0, value: "male" },
  { id: 1, value: "female" },
];

const lib_items = [
  "ausculaide_list",
  "stetho_list",
  "palpation_list",
  "ecg_list",
  "inspection_list",
  "xray_list",
  "ucg_list",
];

const initial_state = {
  counter_choice_ausculaide: [],
  counter_choice_stetho: [],
  counter_choice_xray: [],
  counter_choice_ecg: [],
  counter_choice_ucg: [],
  counter_choice_palpation: [],
  counter_choice_inspection: [],

  //registration
  //final answer
  counter_choice_registration: [],
  final_fill_in: { key: null, value: "" },

  counter_ausculaide: [],
  counter_stetho: [],
  counter_xray: [],
  counter_ecg: [],
  counter_ucg: [],
  counter_palpation: [],
  counter_inspection: [],

  title: null,
  title_en: null,
  question: null,
  question_en: null,
  image_path: null,
  case_gender: 0,
  current_case: "",
  current_case_en: "",
  case_age: null,
  limit_seconds: null,
  is_optional: 1,
  group_attribute: 1,
  errors: {},
  selected_exam_group: [],
  enable_selective: true,
  enable_article_style: false,

  selected_lib_list: {
    selected_ausculaide: "",
    selected_stetho: "",
    selected_xray: "",
    selected_ecg: "",
    selected_ucg: "",
    selected_palpation: "",
    selected_inspection: "",
  },

  //word fill in
  ausculaide_fill_in: { key: null, value: "" },
  stetho_fill_in: { key: null, value: "" },
  palpation_fill_in: { key: null, value: "" },
  ecg_fill_in: { key: null, value: "" },
  ucg_fill_in: { key: null, value: "" },
  inspection_fill_in: { key: null, value: "" },
  xray_fill_in: { key: null, value: "" },

  //answer of each
  ausculaide_description: "",
  stetho_description: "",
  xray_description: "",
  ecg_description: "",
  ucg_description: "",
  palpation_description: "",
  inspection_description: "",

  selected_ausculaide_list_dropdown: [],
  selected_stetho_list_dropdown: [],
  selected_ecg_list_dropdown: [],
  selected_ucg_list_dropdown: [],
  selected_palpation_list_dropdown: [],
  selected_inspection_list_dropdown: [],
  selected_xray_list_dropdown: [],
};

class QuizAdd extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      ...initial_state,
      ausculaide_list: [],

      stetho_list: [],

      xray_list: [],

      ecg_list: [],

      ucg_list: [],

      palpation_list: [],

      inspection_list: [],
      initial_ausculaide_list: [],
      initial_stetho_list: [],
      initial_xray_list: [],
      initial_ecg_list: [],
      initial_ucg_list: [],
      initial_palpation_list: [],
      initial_inspection_list: [],

      row_data: {},
      dragging: {},

      new_counter_ausculaide: [],
      new_counter_stetho: [],
      new_counter_xray: [],
      new_counter_ecg: [],
      new_counter_ucg: [],
      new_counter_palpation: [],
      new_counter_inspection: [],
    };
  }

  componentDidMount() {
    handleFetchExamGroup(this);
    handleLibraryData(this);
  }

  render() {
    const { isVisible, t } = this.props;
    const {
      errors,
      title,
      title_en,
      question,
      question_en,
      current_case,
      current_case_en,
      case_gender,
      counter,
      ausculaide_list,
      stetho_list,
      xray_list,
      ecg_list,
      ucg_list,
      palpation_list,
      inspection_list,
      is_optional,
      group_attribute,
      image_path,
      enable_selective,
      enable_article_style,
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
        className="organism-add-quiz-add-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label>{t("add_quiz")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => handleCancelAdd(this)}
          />
        </Modal.Header>
        <Modal.Body className="quiz-add-organism-modal-body">
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
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["title_en"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col xs={12}>
              <InputWithLabel
                validateError={errorCollection.includes("question")}
                label={t("question_jp")+t("required_sign")}
                typeName="text"
                name="question"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["question"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col>
              <InputWithLabel
                validateError={errorCollection.includes("question_en")}
                label={t("question_en")+t("required_sign")}
                typeName="question_en"
                name="question_en"
                onChange={(event) => handleChangeForm(event, this)}
              />
              {<Span className="error">{errors["question_en"]}</Span>}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col xs={3}>
              <Label className="labelstyle">{t("an_illustration")}</Label>
            </Col>
            <Col xs={9}>
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
                    image_path: files[0],
                  })
                }
              />
            </Col>
          </Row>

          <Row className="label-style mt-2 mb-3 ">
            <Col className="case-gender">
              <InputWithLabel
                onKeyDown={(event) => formatInput(event)}
                min="0"
                label={t("case")}
                typeName="number"
                placeholder={t("age")}
                name="case_age"
                onChange={(event) => handleChangeForm(event, this)}
              />
              <Select
                className="select-item"
                items={case_gender_items}
                value={case_gender}
                onChange={(event) => handleSelect(event, this, "case_gender")}
              ></Select>
            </Col>
          </Row>

          <Row className="mt-1 mb-1">
            <Col>
              <InputWithLabel
                className="desc-field"
                label={t("current_case_jp")}
                typeName="textarea"
                name="current_case"
                onChange={(event) => handleChangeForm(event, this)}
              />
            </Col>
          </Row>

          <Row className="mt-1 mb-1">
            <Col>
              <InputWithLabel
                className="desc-field"
                label={t("current_case_en")}
                typeName="textarea"
                name="current_case_en"
                onChange={(event) => handleChangeForm(event, this)}
              />
            </Col>
          </Row>

          <Row className="mb-1 mt-1">
            <Col>
              <InputWithLabel
                onKeyDown={(event) => formatInput(event)}
                min={0}
                label={t("time_limit")}
                typeName="number"
                placeholder={"30"}
                name="limit_seconds"
                onChange={(event) => handleChangeForm(event, this)}
              />
            </Col>
          </Row>
          <Row className="mb-1">
            <Col className="form-item"></Col>
            <Col xs={9}>
              <Label className="organism-timeLimit-description">
                {t("specify_unlimited_time")}
              </Label>
            </Col>
          </Row>
          <Row className="mb-1">
            <Col className="form-item">
              <Label className="labelstyle quiz-add-modal-item mr-2">
                {t("quiz_format")}
              </Label>
            </Col>
            <Col xs={9}>
              <Span className="itemsa">
                <InputRadio
                  title={t("choice_setting")}
                  type="radio"
                  name="is_optional"
                  defaultChecked={is_optional == 1 ? "checked" : ""}
                  value={1}
                  onClick={(event) => handleSelective(event, this, "selective")}
                />
                <InputRadio
                  title={t("word_setting")}
                  type="radio"
                  name="is_optional"
                  defaultChecked={is_optional == 0 ? "checked" : ""}
                  value={0}
                  onClick={(event) => handleSelective(event, this, "article")}
                />
              </Span>
            </Col>
          </Row>

          <Span className="select-mode">
            <Button
              class_style={enable_selective ? "active-tab" : "inactive-tab"}
            >
              {t("choice_setting")}
            </Button>
            <Button
              class_style={enable_article_style ? "active-tab" : "inactive-tab"}
            >
              {t("word_setting")}
            </Button>
          </Span>

          {/* library items loop */}
          {/* selective style */}
          {lib_items.map((item, index) => {
            return (
              <React.Fragment key={index}>
                <Div className="data-box">
                  <Modal.Dialog className="library-items">
                    <Modal.Header>
                      <Modal.Title className="modal-title-style font-weight-bold">
                        {t(item)} /{" "}
                        {enable_article_style
                          ? t("word_setting")
                          : t("choice_setting")}
                      </Modal.Title>
                    </Modal.Header>

                    <Modal.Body>
                      <Row className="organism-choice-setting-wrapper">
                        <Col>
                          <Label className="labelstyle quiz-add-modal-item quiz-list-item">
                            {t(item.split("_")[0]).toUpperCase()}
                          </Label>
                        </Col>
                        {this.state[`counter_${item.split("_")[0]}`].length !=
                          0 && (
                          <Col className="organism-choice-setting-list" xs={10}>
                            <Div className="mb-1 mt-1 table-added-library">
                              <Table>
                                <DragDropContext
                                  onDragEnd={(result) =>
                                    onDragEnd(
                                      result,
                                      `counter_${item.split("_")[0]}`,
                                      this
                                    )
                                  }
                                >
                                  <Droppable droppableId={item.split("_")[0]}>
                                    {(provided) => (
                                      <tbody
                                        className={
                                          "counter_" + item.split("_")[0] + item
                                        }
                                        ref={provided.innerRef}
                                        {...provided.droppableProps}
                                      >
                                        {this.state[
                                          `counter_${item.split("_")[0]}`
                                        ].map((value, singleIndex) => {
                                          return (
                                            <Draggable
                                              key={value.id}
                                              draggableId={`counter_${
                                                item.split("_")[0]
                                              }-${value.id}`}
                                              index={singleIndex}
                                            >
                                              {(provided) => (
                                                <tr
                                                  {...provided.draggableProps}
                                                  ref={provided.innerRef}
                                                  key={value.id}
                                                >
                                                  <td>
                                                    <Label>{value.label}</Label>
                                                  </td>
                                                  <td>
                                                    <Input
                                                      className="organism-choice-setting-input"
                                                      typeName="text"
                                                      placeholder="jp"
                                                      value={value.title}
                                                      onChange={(e) =>
                                                        handleAddingItemsDesc(
                                                          e,
                                                          this,
                                                          "title",
                                                          item,
                                                          singleIndex
                                                        )
                                                      }
                                                    />
                                                  </td>
                                                  <td>
                                                    <Input
                                                      className="organism-choice-setting-input"
                                                      typeName="text"
                                                      placeholder="en"
                                                      value={value.title_en}
                                                      onChange={(e) =>
                                                        handleAddingItemsDesc(
                                                          e,
                                                          this,
                                                          "title_en",
                                                          item,
                                                          singleIndex
                                                        )
                                                      }
                                                    />
                                                  </td>

                                                  <td>
                                                    <Div className="buttons">
                                                      <Input
                                                        readOnly={true}
                                                        typeName="text"
                                                        placeholder={t(
                                                          `explain_answer_${
                                                            item.split("_")[0]
                                                          }`
                                                        )}
                                                        onClick={() =>
                                                          setAnswerExplanation(
                                                            this,
                                                            item,
                                                            value
                                                          )
                                                        }
                                                      />

                                                      <Button
                                                        mode="danger"
                                                        onClick={() =>
                                                          removeLibItem(
                                                            this,
                                                            singleIndex,
                                                            item
                                                          )
                                                        }
                                                      >
                                                        {t("delete")}
                                                      </Button>
                                                      <Div
                                                        {...provided.dragHandleProps}
                                                      >
                                                        <Image
                                                          url={DragNdropIcon}
                                                        />
                                                      </Div>
                                                    </Div>
                                                  </td>
                                                </tr>
                                              )}
                                            </Draggable>
                                          );
                                        })}
                                        {provided.placeholder}
                                      </tbody>
                                    )}
                                  </Droppable>
                                </DragDropContext>
                              </Table>
                            </Div>
                          </Col>
                        )}

                        {this.state[`counter_${item.split("_")[0]}`].length ==
                          0 && (
                          <Col xs={12}>
                            <Row className="select-dropdown-add">
                              <Select
                                className="select-item"
                                items={eval(item)}
                                value={
                                  this.state.selected_lib_list[
                                    `selected_${item.split("_")[0]}`
                                  ]
                                }
                                onChange={(event) =>
                                  handleSelectLibItem(
                                    event,
                                    this,
                                    `selected_${item.split("_")[0]}`
                                  )
                                }
                              />
                              <Button
                                mode="success"
                                disabled={
                                  !eval(item) ||
                                  (eval(item) && eval(item).length < 1)
                                }
                                onClick={(event) =>
                                  handleAddingItems(event, this, item)
                                }
                                className="btn-block text-center organism-add-modal-button"
                              >
                                {t(`add_${item.split("_")[0]}`)}
                              </Button>
                            </Row>
                          </Col>
                        )}
                      </Row>

                      {this.state[`counter_${item.split("_")[0]}`].length !=
                        0 && (
                        <Row className="select-dropdown-add">
                          <Select
                            className="select-item"
                            items={eval(item)}
                            value={
                              this.state.selected_lib_list[
                                `selected_${item.split("_")[0]}`
                              ]
                            }
                            onChange={(event) =>
                              handleSelectLibItem(
                                event,
                                this,
                                `selected_${item.split("_")[0]}`
                              )
                            }
                          />
                          <Button
                            mode="success"
                            disabled={
                              !eval(item) ||
                              (eval(item) && eval(item).length < 1)
                            }
                            onClick={(event) =>
                              handleAddingItems(event, this, item)
                            }
                            className="btn-block text-center organism-add-modal-button"
                          >
                            {t(`add_${item.split("_")[0]}`)}
                          </Button>
                        </Row>
                      )}

                      <hr />

                      {this.state.enable_article_style && (
                        <Row>
                          <Col>
                            <Label className="labelstyle quiz-add-modal-item">
                              {t("word_registration")}
                            </Label>
                          </Col>
                          <Col className="fill-in-box">
                            <Div className="mb-1 mt-1">
                              <Input
                                type="text"
                                value={
                                  this.state[`${item.split("_")[0]}_fill_in`]
                                    .value
                                }
                                onChange={(e) =>
                                  handleWordRegistrationInput(e, this, item)
                                }
                              />
                            </Div>
                          </Col>
                        </Row>
                      )}

                      {this.state.enable_selective && (
                        <Row>
                          <Col>
                            <Label className="labelstyle quiz-add-modal-item">
                              {t("choices")}
                            </Label>
                          </Col>
                          <Col xs={10}>
                            <Div className="mb-1 mt-1">
                              <Table className="choice-table">
                                <thead>
                                  <tr>
                                    <th className="choice-correct-answer">
                                      {t("correct_answer")}
                                    </th>
                                    <th
                                      className="choict-table-choices"
                                      colSpan="4"
                                    >
                                      {t("choices")}
                                    </th>
                                  </tr>
                                </thead>
                                <DragDropContext
                                  onDragEnd={(result) =>
                                    onDragEnd(
                                      result,
                                      `counter_choice_${item.split("_")[0]}`,
                                      this
                                    )
                                  }
                                >
                                  <Droppable
                                    droppableId={"choice-" + item.split("_")[0]}
                                  >
                                    {(provided) => (
                                      <tbody
                                        className={
                                          "counter_" + item.split("_")[0]
                                        }
                                        ref={provided.innerRef}
                                        {...provided.droppableProps}
                                      >
                                        {this.state[
                                          `counter_choice_${item.split("_")[0]}`
                                        ].map((value, choiceIndex) => {
                                          return (
                                            <Draggable
                                              key={value.key}
                                              draggableId={value.key.toString()}
                                              index={choiceIndex}
                                            >
                                              {(provided) => (
                                                <tr
                                                  {...provided.draggableProps}
                                                  ref={provided.innerRef}
                                                  key={value.key}
                                                >
                                                  <td>
                                                    <RadioButton
                                                      defaultChecked={
                                                        value.checked === 1
                                                      }
                                                      name={`choice_radio_${item}`}
                                                      onClick={(e) =>
                                                        addChoiceData(
                                                          e,
                                                          this,
                                                          item,
                                                          choiceIndex,
                                                          "checked"
                                                        )
                                                      }
                                                    />
                                                  </td>
                                                  <td>
                                                    <Input
                                                      inputError={
                                                        !value.isTitleValid
                                                      }
                                                      typeName="text"
                                                      placeholder="jp"
                                                      value={value.title}
                                                      onChange={(e) =>
                                                        addChoiceData(
                                                          e,
                                                          this,
                                                          item,
                                                          choiceIndex,
                                                          "title"
                                                        )
                                                      }
                                                    />
                                                  </td>
                                                  <td>
                                                    <Input
                                                      inputError={
                                                        !value.isTitle_enValid
                                                      }
                                                      typeName="text"
                                                      placeholder="en"
                                                      value={value.title_en}
                                                      onChange={(e) =>
                                                        addChoiceData(
                                                          e,
                                                          this,
                                                          item,
                                                          choiceIndex,
                                                          "title_en"
                                                        )
                                                      }
                                                    />
                                                  </td>
                                                  <td>
                                                    <Button
                                                      mode="danger"
                                                      onClick={() =>
                                                        removeChoice(
                                                          this,
                                                          item,
                                                          choiceIndex
                                                        )
                                                      }
                                                    >
                                                      {t("delete")}
                                                    </Button>
                                                  </td>
                                                  <td
                                                    {...provided.dragHandleProps}
                                                    className="icon"
                                                  >
                                                    <Image
                                                      url={DragNdropIcon}
                                                    />
                                                  </td>
                                                </tr>
                                              )}
                                            </Draggable>
                                          );
                                        })}
                                        {provided.placeholder}
                                      </tbody>
                                    )}
                                  </Droppable>
                                </DragDropContext>
                              </Table>
                              <BR />
                              <Button
                                mode="success"
                                class_style="float-right"
                                onClick={() => addChoice(this, item)}
                              >
                                {t("add_choices")}
                              </Button>
                            </Div>
                            <BR />
                            <Span className="error-choices">
                              {errors[`choice_${item.split("_")[0]}`]}
                            </Span>
                          </Col>
                        </Row>
                      )}

                      <Modal.Dialog className="library-items">
                        <Modal.Header>
                          <Modal.Title className="modal-title-style font-weight-bold">
                            {t("answer_explanation")}
                          </Modal.Title>
                        </Modal.Header>

                        <Modal.Body>
                          <Div className="mb-1 mt-1 teast">
                            <Label className="labelstyle quiz-add-modal-item">
                              {t("answer_explanation")}
                            </Label>
                            <Select
                              className="select-item"
                              items={[{ id: "", value: "" }].concat(
                                this.state[`initial_${item}`]
                              )}
                              value={
                                this.state[`${item.split("_")[0]}_description`]
                              }
                              onChange={(event) =>
                                handleSelect(
                                  event,
                                  this,
                                  `${item.split("_")[0]}_description`
                                )
                              }
                            />
                          </Div>
                        </Modal.Body>
                      </Modal.Dialog>

                    </Modal.Body>
                  </Modal.Dialog>

                  
                </Div>
              </React.Fragment>
            );
          })}

          {/* Answer registration */}
          {this.state.enable_selective && (
            <Div className="data-box">
              <Modal.Dialog className="library-items">
                <Modal.Header>
                  <Modal.Title className="modal-title-style font-weight-bold">
                    {t("answer_registration")}
                  </Modal.Title>
                </Modal.Header>
                <Modal.Body>
                  <Row>
                    <Col>
                      <Label className="labelstyle quiz-add-modal-item">
                        {t("choices")}
                      </Label>
                    </Col>
                    <Col xs={10}>
                      <Div className="mb-1 mt-1">
                        <Table className="choice-table">
                          <thead>
                            <tr>
                              <th className="choice-correct-answer">
                                {t("correct_answer")}
                              </th>
                              <th className="choict-table-choices">
                                {t("choices")}
                              </th>
                            </tr>
                          </thead>
                          <DragDropContext
                            onDragEnd={(result) =>
                              onDragEnd(
                                result,
                                "counter_choice_registration",
                                this
                              )
                            }
                          >
                            <Droppable
                              droppableId={"counter_choice_registration"}
                            >
                              {(provided) => (
                                <tbody
                                  className="counter_choice_registration"
                                  ref={provided.innerRef}
                                  {...provided.droppableProps}
                                >
                                  {this.state.counter_choice_registration.map(
                                    (value, index) => {
                                      return (
                                        <Draggable
                                          key={value.key}
                                          draggableId={value.key.toString()}
                                          index={index}
                                        >
                                          {(provided) => (
                                            <tr
                                              {...provided.draggableProps}
                                              ref={provided.innerRef}
                                              key={value.key}
                                            >
                                              <td>
                                                <RadioButton
                                                  name={`choice_radio_registration`}
                                                  defaultChecked={
                                                    value.checked === 1
                                                  }
                                                  onClick={(e) =>
                                                    addChoiceDataRegistraion(
                                                      e,
                                                      this,
                                                      index,
                                                      "checked"
                                                    )
                                                  }
                                                />
                                              </td>
                                              <td>
                                                <Input
                                                  inputError={
                                                    !value.isTitleValid
                                                  }
                                                  typeName="text"
                                                  placeholder="jp"
                                                  value={value.title}
                                                  onChange={(e) =>
                                                    addChoiceDataRegistraion(
                                                      e,
                                                      this,
                                                      index,
                                                      "title"
                                                    )
                                                  }
                                                />
                                              </td>
                                              <td>
                                                <Input
                                                  inputError={
                                                    !value.isTitle_enValid
                                                  }
                                                  typeName="text"
                                                  placeholder="en"
                                                  value={value.title_en}
                                                  onChange={(e) =>
                                                    addChoiceDataRegistraion(
                                                      e,
                                                      this,
                                                      index,
                                                      "title_en"
                                                    )
                                                  }
                                                />
                                              </td>
                                              <td>
                                                <Button
                                                  mode="danger"
                                                  onClick={() =>
                                                    removeChoiceDataRegistration(
                                                      this,
                                                      index
                                                    )
                                                  }
                                                >
                                                  {t("delete")}
                                                </Button>
                                              </td>
                                              <td
                                                {...provided.dragHandleProps}
                                                className="icon"
                                              >
                                                <Image
                                                  mode="drag"
                                                  url={DragNdropIcon}
                                                />
                                              </td>
                                            </tr>
                                          )}
                                        </Draggable>
                                      );
                                    }
                                  )}
                                  {provided.placeholder}
                                </tbody>
                              )}
                            </Droppable>
                          </DragDropContext>
                        </Table>
                        <br />
                        <Button
                          mode="success"
                          class_style="float-right"
                          onClick={() => addChoiceRegistration(this)}
                        >
                          {t("add_choices")}
                        </Button>
                      </Div>
                      <BR />
                      <Span className="error-choices">
                        {errors[`choice_registration`]}
                      </Span>
                    </Col>
                  </Row>
                </Modal.Body>
              </Modal.Dialog>
            </Div>
          )}

          {this.state.enable_article_style && (
            <Div className="data-box">
              <Modal.Dialog className="library-items">
                <Modal.Header>
                  <Modal.Title className="modal-title-style font-weight-bold">
                    {t("answer_registration")}
                  </Modal.Title>
                </Modal.Header>
                <Modal.Body>
                  <Row>
                    <Col>
                      <Label
                        labelError={errorCollection.includes("final_fill_in")}
                        className="labelstyle quiz-add-modal-item"
                      >
                        {t("word_registration")}
                      </Label>
                    </Col>
                    <Col xs={9}>
                      <Div className="mb-1 mt-1 teast">
                        <Input
                          inputError={errorCollection.includes("final_fill_in")}
                          type="text"
                          value={this.state.final_fill_in.value}
                          onChange={(e) =>
                            handleWordRegistrationInput(e, this, "final")
                          }
                        />
                      </Div>
                    </Col>
                    <Span className="error">{errors["final_fill_in"]}</Span>
                  </Row>
                </Modal.Body>
              </Modal.Dialog>
            </Div>
          )}
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
 * Get sorted list after drag ends
 * @param {*} result
 * @param {*} libraryType
 * @param {*} context
 */
const onDragEnd = (result, libraryType, context) => {
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
  const libraryItems = context.state[libraryType];
  const items = Array.from(libraryItems);
  const [reorderedItem] = items.splice(source.index, 1);
  items.splice(destination.index, 0, reorderedItem);
  context.setState({
    [libraryType]: items,
  });
};

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

//fetch library data
const handleLibraryData = async (context) => {
  let {
    ausculaide_list,
    stetho_list,
    xray_list,
    ecg_list,
    ucg_list,
    palpation_list,
    inspection_list,
  } = context.props.libItems;
  context.setState(
    {
      initial_ausculaide_list: JSON.parse(JSON.stringify(ausculaide_list)),
      initial_stetho_list: JSON.parse(JSON.stringify(stetho_list)),
      initial_xray_list: JSON.parse(JSON.stringify(xray_list)),
      initial_ecg_list: JSON.parse(JSON.stringify(ecg_list)),
      initial_ucg_list: JSON.parse(JSON.stringify(ucg_list)),
      initial_palpation_list: JSON.parse(JSON.stringify(palpation_list)),
      initial_inspection_list: JSON.parse(JSON.stringify(inspection_list)),
      ausculaide_list: ausculaide_list,
      stetho_list: stetho_list,
      xray_list: xray_list,
      ecg_list: ecg_list,
      ucg_list: ucg_list,
      palpation_list: palpation_list,
      inspection_list: inspection_list,
    },

    () => {
      //set starting value as selected in dropdown box
      let selected_lib_list = context.state.selected_lib_list;

      selected_lib_list.selected_ausculaide =
        context.state.ausculaide_list.length > 0
          ? context.state.ausculaide_list[0].id
          : "";
      selected_lib_list.selected_stetho =
        context.state.stetho_list.length > 0
          ? context.state.stetho_list[0].id
          : "";
      selected_lib_list.selected_xray =
        context.state.xray_list.length > 0 ? context.state.xray_list[0].id : "";
      selected_lib_list.selected_ecg =
        context.state.ecg_list.length > 0 ? context.state.ecg_list[0].id : "";
      selected_lib_list.selected_ucg =
        context.state.ucg_list.length > 0 ? context.state.ucg_list[0].id : "";
      selected_lib_list.selected_palpation =
        context.state.palpation_list.length > 0
          ? context.state.palpation_list[0].id
          : "";
      selected_lib_list.selected_inspection =
        context.state.inspection_list.length > 0
          ? context.state.inspection_list[0].id
          : "";

      context.setState({
        selected_lib_list,
      });
    }
  );
};

/**
 * handle Select
 * @param {*} context
 * @param {event} e
 * @param {type} type
 */
const handleSelect = async (e, context, type) => {
  await context.setState({ [type]: e.target.value });
};

/**
 * handle Select lib item
 * @param {*} context
 * @param {event} e
 * @param {type} type
 */
const handleSelectLibItem = async (e, context, type) => {
  let { selected_lib_list } = context.state;
  selected_lib_list[type] = e.target.value;
  await context.setState(selected_lib_list);
};

/**
 * handle adding choice
 * @param {*} context
 * @param {type} type
 */
const addChoice = (context, type) => {
  let item_type = type.split("_")[0];
  let counter_type = `counter_choice_${item_type}`;
  const { [counter_type]: counter } = context.state;
  context.setState({
    [counter_type]: [
      ...counter,
      {
        key: Date.now() + "_" + counter.length,
        title_en: "",
        title: "",
        checked:
          context.state[counter_type].length == undefined ||
          context.state[counter_type].length == 0
            ? 1
            : 0,
        disp_order: 0,
        lib_key: getLibKey(item_type),
        isTitleValid: true,
        isTitle_enValid: true,
      },
    ],
  });
};

/**
 * handle adding choice data
 * @param {*} context
 * @param {event} e
 * @param {type} type
 * @param {position} pos
 *
 */
const addChoiceData = (e, context, type, index, lang_type) => {
  let item_type = type.split("_")[0];
  let counter_item = context.state[`counter_choice_${item_type}`];
  counter_item[index][lang_type] =
    lang_type == "checked" ? (e.target.checked ? 1 : 0) : e.target.value;
  lang_type == "checked" &&
    context.state[`counter_choice_${item_type}`]
      .filter((item, ind) => ind != index)
      .forEach((item) => (item.checked = 0));
  context.setState({ [`counter_choice_${item_type}`]: counter_item });
};

/**
 * Make first letter capital
 * @param {string} item
 */
function firstLetterCapital(string) {
  let capitalize =
    string == "ucg" || string == "ecg"
      ? string.toUpperCase()
      : string.charAt(0).toUpperCase() + string.slice(1);
  return capitalize;
}

/**
 * handle removing choice
 * @param {*} context
 * @param {position} pos
 */
const removeChoice = (context, type, pos) => {
  let item_type = type.split("_")[0];
  let counter_type = `counter_choice_${item_type}`;

  const selectedChoice = context.state[counter_type];
  if (selectedChoice.length > 0) {
    let obtainSelectedChoice = selectedChoice.filter(
      (i, index) => index !== pos
    );
    let isCheckedExist = obtainSelectedChoice.some((e) => e.checked == 1);
    if (!isCheckedExist && obtainSelectedChoice.length > 0) {
      obtainSelectedChoice[0].checked = 1;
      context.setState({
        [counter_type]: obtainSelectedChoice,
      });
    } else {
      context.setState({
        [counter_type]: obtainSelectedChoice,
      });
    }
  }
};

/**
 * handle adding word registration data
 * @param {*} context
 * @param {event} e
 * @param {type} item
 */
const handleWordRegistrationInput = (e, context, type) => {
  let item_type = type.split("_")[0];

  let added = context.state[`${item_type}_fill_in`]; //like ausculaide_fill_in
  added = e.target.value;

  context.setState({
    [`${item_type}_fill_in`]: { key: Date.now(), value: added },
  });
};

/**
 * handle adding Items library
 * @param {event} e
 * @param {*} context
 * @param {*} type
 */
const handleAddingItems = (e, context, type) => {
  let item_type = type.split("_")[0];
  let counter_type = `counter_${item_type}`;
  let list_arr = context.state[`${item_type}_list`];
  let selected_type = `selected_${item_type}`;
  const selectListItems = `${item_type}_list`;

  const { [counter_type]: counter } = context.state;
  let { [selected_type]: selected } = context.state.selected_lib_list;
  let selectedOption = list_arr.find((item) => item.id == selected);
  const initialSelectList = context.state[`initial_${selectListItems}`];
  const selectedDropdowns = [
    ...context.state[`selected_${selectListItems}_dropdown`],
    selectedOption.id,
  ];

  let remainingList = initialSelectList;
  for (let i = 0; i < selectedDropdowns.length; i++) {
    remainingList = remainingList.filter(
      (item) => item.id != selectedDropdowns[i]
    );
  }

  context.setState({
    [counter_type]: counter.concat({
      key: Date.now() + "_" + counter.length,
      id: selectedOption.id,
      title_en: "",
      title: "",
      label: selectedOption.value,
      disp_order: 0,
    }),
    [`selected_${selectListItems}_dropdown`]: selectedDropdowns,
    [selectListItems]: remainingList,
    selected_lib_list: {
      ...context.state.selected_lib_list,
      [`selected_${item_type}`]:
        remainingList.length > 0 ? remainingList[0].id : 0,
    },
  });

  // let { [type]: added } = context.state.added_lib_list;
  // added = added.concat(selectedOption);
  // let usedOptions = { ...context.state.added_lib_list, [type]: added };
  // context.setState({
  //   added_lib_list: usedOptions,
  // });
};

/**
 * handle adding Items description
 * @param {*} e
 * @param {*} context
 * @param {*} lang_type
 * @param {*} type
 * @param {*} index
 */
const handleAddingItemsDesc = (e, context, lang_type, type, index) => {
  // let { [type]: added } = context.state.added_lib_list; //like ausculaide_list
  // added[pos] = { ...added[pos], [lang_type]: e.target.value };
  // let new_obj = { ...context.state.added_lib_list, [type]: added };
  // context.setState({
  //   added_lib_list: new_obj,
  // });
  let item_type = type.split("_")[0];
  let counter_item = context.state[`counter_${item_type}`];
  counter_item[index][lang_type] = e.target.value;
  context.setState({ [`counter_${item_type}`]: counter_item });
};

/**
 * handle removing Items library
 * @param {*} context
 * @param {position} pos
 */
const removeLibItem = (context, pos, type) => {
  let item_type = type.split("_")[0];
  let counter_type = `counter_${item_type}`;

  const count = context.state[counter_type].filter(
    (el, index) => index !== pos
  );

  const removedItemId = context.state[counter_type].find(
    (el, index) => index == pos
  ).id;

  const selectListItems = `${item_type}_list`;
  const initialSelectList = context.state[`initial_${selectListItems}`];
  const selectedDropdowns = context.state[
    `selected_${selectListItems}_dropdown`
  ].filter((item) => item != removedItemId);

  let remainingList = initialSelectList;
  for (let i = 0; i < selectedDropdowns.length; i++) {
    remainingList = remainingList.filter(
      (item) => item.id != selectedDropdowns[i]
    );
  }

  context.setState({
    [counter_type]: count,
    [`selected_${selectListItems}_dropdown`]: selectedDropdowns,
    [selectListItems]: remainingList,
    selected_lib_list: {
      ...context.state.selected_lib_list,
      [`selected_${item_type}`]: remainingList[0].id,
    },
  });
};

/**
 * Prevent characters that are not numbers ("e", "E", ".", "+" & "-")
 * @param {*} event
 */
const formatInput = (e) => {
  let checkIfNum;
  if (e.key !== undefined) {
    checkIfNum =
      e.key === "e" ||
      e.key === "." ||
      e.key === "+" ||
      e.key === "-" ||
      e.key === "E";
  } else if (e.keyCode !== undefined) {
    checkIfNum =
      e.keyCode === 69 ||
      e.keyCode === 190 ||
      e.keyCode === 187 ||
      e.keyCode === 189;
  }
  return checkIfNum && e.preventDefault();
};

/**
 * handle adding choice answer registration
 * @param {*} context
 */
const addChoiceRegistration = (context) => {
  const { counter_choice_registration } = context.state;
  context.setState({
    counter_choice_registration: counter_choice_registration.concat({
      key: Date.now(),
      title_en: "",
      title: "",
      checked:
        counter_choice_registration.length == undefined ||
        counter_choice_registration.length == 0
          ? 1
          : 0,
      disp_order: 0,
      lib_key: "final_answer",
      isTitleValid: true,
      isTitle_enValid: true,
    }),
  });
};

/**
 * handle adding choice data answer registration
 * @param {event} e
 * @param {*} context
 * @param {position} pos
 * @param {type} input_type
 *
 */
const addChoiceDataRegistraion = (e, context, pos, input_type) => {
  let { counter_choice_registration } = context.state;
  // arr[pos] = {
  //   ...arr[pos],
  //   [input_type]:
  //     input_type == "checked" ? (e.target.checked ? 1 : 0) : e.target.value,
  // };

  // //remove checked from other items
  // input_type == "checked" &&
  //   context.state.choice_data_registration
  //     .filter((item, index) => index != pos)
  //     .forEach((item) => (item.checked = 0));
  counter_choice_registration[pos][input_type] =
    input_type == "checked" ? (e.target.checked ? 1 : 0) : e.target.value;

  // //remove checked from other items

  input_type == "checked" &&
    context.state.counter_choice_registration
      .filter((item, ind) => ind != pos)
      .forEach((item) => (item.checked = 0));

  context.setState({
    counter_choice_registration: counter_choice_registration,
  });
};

/**
 * handle removing choice data answer registration
 * @param {*} context
 * @param {position} pos
 */
const removeChoiceDataRegistration = (context, pos) => {
  const { counter_choice_registration } = context.state;
  if (counter_choice_registration.length > 0) {
    const choiceRegistration = context.state.counter_choice_registration.filter(
      (el, index) => index !== pos
    );
    let isCheckedExist = choiceRegistration.some((e) => e.checked == 1);
    if (!isCheckedExist && choiceRegistration.length > 0) {
      choiceRegistration[0].checked = 1;
      context.setState({
        counter_choice_registration: choiceRegistration,
      });
    } else {
      context.setState({
        counter_choice_registration: choiceRegistration,
      });
    }
  }
};

/**
 * handle selecting answer explanation for each lib items
 * @param {*} context
 */
const selectAnswerExplanation = (context) => {};

/**
 * handle form input change
 * @param {*} event
 * @param {*} context
 */
const handleChangeForm = (event, context) => {
  context.setState({ [event.target.name]: event.target.value });
};

/**
 * handle form selective changes
 * @param {*} event
 * @param {*} context
 */
const handleSelective = (event, context, selective_type) => {
  handleChangeForm(event, context);
  selective_type == "selective"
    ? context.setState({
        enable_selective: true,
        enable_article_style: false,
      })
    : context.setState({
        enable_selective: false,
        enable_article_style: true,
      });
};

/**
 *  select item from dropdown list
 * @param {*} selectedList
 * @param {*} selectedItem
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
 * remove selected item
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
 * close modal
 * @param {*} context
 */
const handleCancelAdd = (context) => {
  context.setState({
    ...initial_state,
    errors: {},
  });
  context.props.onHideAddModal();
};

/**
 * get lib key
 * @param {lib type} item_type
 */
const getLibKey = (item_type) => {
  switch (item_type) {
    case "ausculaide":
      return 1;
    case "stetho":
      return "0";
    case "palpation":
      return 2;
    case "ecg":
      return 3;
    case "inspection":
      return 4;
    case "xray":
      return 5;
    case "ucg":
      return 6;
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
    question,
    question_en,
    counter_ausculaide,
    counter_stetho,
    counter_xray,
    counter_ecg,
    counter_ucg,
    counter_palpation,
    counter_inspection,
    counter_choice_ausculaide,
    counter_choice_stetho,
    counter_choice_xray,
    counter_choice_ecg,
    counter_choice_ucg,
    counter_choice_palpation,
    counter_choice_inspection,
    final_fill_in,
    enable_selective,
    enable_article_style,
    counter_choice_registration,
  } = context.state;
  const { t } = context.props;
  let errors = {};
  let formIsValid = true;
  let multiple_library = 0;
  //title
  if (!title || title.trim().length < 0) {
    formIsValid = false;
    errors["title"] = t("validate_title_jp");
  }
  //title_en
  if (!title_en || title_en.trim().length < 0) {
    formIsValid = false;
    errors["title_en"] = t("validate_title_en");
  }
  //description
  if (!question || question.trim().length < 0) {
    formIsValid = false;
    errors["question"] = t("validate_question");
  }
  //description_en
  if (!question_en || question_en.trim().length < 0) {
    formIsValid = false;
    errors["question_en"] = t("validate_question_en");
  }
  if (
    counter_ausculaide.length == 0 &&
    counter_stetho.length == 0 &&
    counter_xray.length == 0 &&
    counter_ecg.length == 0 &&
    counter_ucg.length == 0 &&
    counter_palpation.length == 0 &&
    counter_inspection.length == 0
  ) {
    formIsValid = false;
    errors["quiz_library"] = t("validate_quiz_library");
  }
  //library choice
  if (enable_selective) {
    if (
      counter_ausculaide.length > 0 &&
      counter_choice_ausculaide.length == 0
    ) {
      formIsValid = false;
      errors["choice_ausculaide"] = t("validate_ausculaide_choice_library");
    }

    if (counter_stetho.length > 0 && counter_choice_stetho.length == 0) {
      formIsValid = false;
      errors["choice_stetho"] = t("validate_stetho_choice_library");
    }

    if (counter_xray.length > 0 && counter_choice_xray.length == 0) {
      formIsValid = false;
      errors["choice_xray"] = t("validate_xray_choice_library");
    }

    if (counter_ecg.length > 0 && counter_choice_ecg.length == 0) {
      formIsValid = false;
      errors["choice_ecg"] = t("validate_ecg_choice_library");
    }

    if (counter_ucg.length > 0 && counter_choice_ucg.length == 0) {
      formIsValid = false;
      errors["choice_ucg"] = t("validate_ucg_choice_library");
    }

    if (counter_palpation.length > 0 && counter_choice_palpation.length == 0) {
      formIsValid = false;
      errors["choice_palpation"] = t("validate_palpation_choice_library");
    }

    if (
      counter_inspection.length > 0 &&
      counter_choice_inspection.length == 0
    ) {
      formIsValid = false;
      errors["choice_inspection"] = t("validate_inspection_choice_library");
    }

    lib_items.forEach((item, i) => {
      const library_name = item.split("_")[0];
      const library_item = `counter_${library_name}`;
      const library_choice_item = `counter_choice_${library_name}`;
      if (context.state[library_item].length > 0) {
        multiple_library += 1;
      }
      if (context.state[library_choice_item].length > 0) {
        context.state[library_choice_item].forEach((e, i) => {
          const isTitleValid = e.title.trim().length > 0 ? true : false;
          const isTitle_enValid = e.title_en.trim().length > 0 ? true : false;
          context.state[library_choice_item][i].isTitleValid = isTitleValid;
          context.state[library_choice_item][
            i
          ].isTitle_enValid = isTitle_enValid;
          if (!isTitleValid || !isTitle_enValid) {
            formIsValid = false;
            errors[`choice_${library_name}`] = t(
              `validate_${library_name}_choice_library`
            );
          }
          context.setState({
            library_choice_item: context.state[library_choice_item],
          });
        });
      }
    });

    if (multiple_library >= 2) {
      if (counter_choice_registration.length > 0) {
        counter_choice_registration &&
          counter_choice_registration.forEach((e, i) => {
            const isTitleValid = e.title.trim().length > 0 ? true : false;
            const isTitle_enValid = e.title_en.trim().length > 0 ? true : false;
            counter_choice_registration[i].isTitleValid = isTitleValid;
            counter_choice_registration[i].isTitle_enValid = isTitle_enValid;
            if (!isTitleValid || !isTitle_enValid) {
              formIsValid = false;
              errors["choice_registration"] = t("validate_multiple_library");
            }
            context.setState({
              counter_choice_registration,
            });
          });
      } else {
        formIsValid = false;
        errors["choice_registration"] = t("validate_multiple_library");
      }
    }
  }

  if (
    enable_article_style &&
    final_fill_in &&
    final_fill_in.value.trim().length == 0
  ) {
    formIsValid = false;
    errors["final_fill_in"] = t("validate_final_fill_in");
  }

  context.setState({ errors: errors });
  return formIsValid;
};

/**
 * set new answer explanation
 */

const setAnswerExplanation = (context, type, value) => {
  let lib_type = type.split("_")[0];

  context.setState({
    [`${lib_type}_description`]: value.id,
  });
};
/**
 * submit data
 */
const submitData = (context) => {
  const { addQuizData, onHideAddModal } = context.props;
  const isValidated = handleValidate(context);
  if (isValidated) {
    const quiz_data = {
      ...context.state,
    };
    addQuizData(quiz_data);
    context.setState({
      ...initial_state,
    });
    onHideAddModal();
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
export default connect(mapStateToProps, {
  getExamGroup,
  getLibraryUser,
})(withTranslation("translation")(QuizAdd));
