import React from "react";

// Components
import Div from "../../../presentational/atoms/Div";
import Button from "../../../presentational/atoms/Button";
import Label from "../../../presentational/atoms/Label";
import P from "../../../presentational/atoms/P/index";
import DropdownLabel from "../../../presentational/molecules/DropdownWithLabel";
import ImageSlider from "../../../presentational/molecules/ImageSlider";
import MediaComponent from "../../../presentational/molecules/Media/index";
import InputWithSubmit from "../../../presentational/molecules/InputWithSubmit";

// css
import "./style.css";

// i18next
import i18next from "i18next";
import { withTranslation } from "react-i18next";

const getInitialState = {
  selectedLibrary: null,
  libraryQuizChoices: [{ id: "", value: "what_is_your_diagonis" }],
  libraryItemsList: null,
  selectedImageIndex: 0,
  submittedLibraries: [],
  selectedAnswer: null,
  uniqueLibraryItems: null,
};
//===================================================
// Component
//===================================================
class MultiplePreChoice extends React.Component {
  constructor(props) {
    super(props);
    this.state = getInitialState;
  }

  componentDidMount() {
    handleGetLibraryItems(this);
  }

  componentDidUpdate() {
    const {
      isOpenFromDetail,
      handleSetMultipleQuizFalse,
      isTimerCompleted,
    } = this.props;
    if (isOpenFromDetail) {
      handleSetMultipleQuizFalse();
      handleGetLibraryItems(this);
      handleSetQuizState(this);
    }
    if (isTimerCompleted) {
      handleSetMultipleQuizFalse();
      handleResetQuizState(this);
    }
  }

  render() {
    const { quizTitle, quiz_number, quizItem, timeLimit, t } = this.props;
    const {
      selectedLibrary,
      libraryQuizChoices,
      libraryItemsList,
      selectedImageIndex,
      submittedLibraries,
      selectedAnswer,
      uniqueLibraryItems,
    } = this.state;
    const selectedLanguage = i18next.language;
    return (
      <Div className="organisms-MultiplePreChoice-wrapper">
        <Label className="MultiplePreChoice-quizTitle">{quizTitle}</Label>
        <Div className="MultiplePreChoice-options-wrapper">
          <Label className="organism-quizNumber">{quiz_number}</Label>
          <Div className="MultiplePreChoice-options-container">
            {timeLimit > 0 && (
              <Div className="organism-sound-timer">
                <Label className="organism-timer-text">{timeLimit}</Label>
              </Div>
            )}
            <Label className="organisms-quiz-question">
              {selectedLanguage === "ja"
                ? quizItem.question
                : quizItem.question_en}
            </Label>
            <Div className="row multiple-quiz-wrapper">
              <Div className="multiple-quiz-container">
                <Label>{t(selectedLibrary)}</Label>
                {libraryItemsList && selectedLibrary === "ausculaide" && (
                  <ImageSlider
                    url={`${libraryItemsList[selectedImageIndex].body_image}`}
                    totalImages={libraryItemsList.length}
                    selectedIndex={selectedImageIndex}
                    onNextIconClicked={() => handleNextIconClicked(this)}
                    onPreviousIconClicked={() =>
                      handlePreviousIconClicked(this)
                    }
                  />
                )}
                {libraryItemsList &&
                  selectedLibrary === "stetho" &&
                  libraryItemsList.map((libItem, index) => (
                    <Div key={index} className="organism-sound-video-container">
                      <MediaComponent file={libItem.sound_path} type="sound" />
                    </Div>
                  ))}
                {libraryItemsList &&
                  selectedLibrary === "palpation" &&
                  libraryItemsList.map((libItem, index) => (
                    <Div key={index} className="organism-sound-video-container">
                      <MediaComponent file={libItem.sound_path} type="sound" />
                      <MediaComponent
                        file={libItem.video_path}
                        type="video"
                        width={200}
                        height={150}
                      />
                    </Div>
                  ))}
                {libraryItemsList && selectedLibrary === "ecg" && (
                  <ImageSlider
                    url={`${libraryItemsList[selectedImageIndex].body_image}`}
                    totalImages={libraryItemsList.length}
                    selectedIndex={selectedImageIndex}
                    onNextIconClicked={() => handleNextIconClicked(this)}
                    onPreviousIconClicked={() =>
                      handlePreviousIconClicked(this)
                    }
                  />
                )}
                {libraryItemsList &&
                  selectedLibrary === "inspection" &&
                  libraryItemsList.map((libItem, index) => (
                    <Div key={index} className="organism-sound-video-container">
                      <MediaComponent file={libItem.sound_path} type="sound" />
                      <MediaComponent
                        file={libItem.video_path}
                        type="video"
                        width={200}
                        height={150}
                      />
                    </Div>
                  ))}
                {libraryItemsList && selectedLibrary === "xray" && (
                  <ImageSlider
                    url={`${libraryItemsList[selectedImageIndex].body_image}`}
                    totalImages={libraryItemsList.length}
                    selectedIndex={selectedImageIndex}
                    onNextIconClicked={() => handleNextIconClicked(this)}
                    onPreviousIconClicked={() =>
                      handlePreviousIconClicked(this)
                    }
                  />
                )}
                {libraryItemsList &&
                  selectedLibrary === "ucg" &&
                  libraryItemsList.map((libItem, index) => (
                    <Div key={index} className="organism-sound-video-container">
                      <MediaComponent
                        file={libItem.video_path}
                        type="video"
                        width={200}
                        height={150}
                      />
                    </Div>
                  ))}
              </Div>
              <Div className="multiple-quiz-history">
                <Div className="quiz-history-wrapper">
                  <Div className="history-title">
                    <P className="prechoice-title">{t("medical_history")}</P>
                  </Div>
                  <Div className="history-content">
                    <P className="organism-age-gender-text">
                      {quizItem.case_age}&nbsp;
                      {t(getGender(quizItem.case_gender))}
                    </P>
                    <Div
                      dangerouslySetInnerHTML={{
                        __html:
                          selectedLanguage === "ja"
                            ? quizItem.case
                            : quizItem.case_en,
                      }}
                    />
                  </Div>
                </Div>
                <Div className="library-button">
                  <P className="prechoice-title">Clinical Examination</P>
                  <Div className="library-button-wrapper">
                    {uniqueLibraryItems &&
                      uniqueLibraryItems.map((item, index) => {
                        const isLibrarySelected =
                          selectedLibrary === getLibraryType(item);
                        const isLibrarySubmitted =
                          submittedLibraries &&
                          submittedLibraries.find(
                            (el) => el.libItem === getLibraryType(item)
                          );
                        return (
                          <Button
                            key={index}
                            mode={"choice"}
                            class_style={
                              isLibrarySelected
                                ? "organism-ChoiceButtonWithIcon-selected"
                                : isLibrarySubmitted
                                ? "organism-ChoiceButtonWithIcon-submited"
                                : "organism-ChoiceButtonWithIcon"
                            }
                            onClick={() =>
                              handleSelectLibraryItem(
                                this,
                                item,
                                quizItem.is_optional == 1
                              )
                            }
                          >
                            {t(getLibraryType(item))}
                            {isLibrarySubmitted && " ✔ "}
                          </Button>
                        );
                      })}
                  </Div>
                </Div>
              </Div>
            </Div>
          </Div>
          {selectedLibrary && quizItem.is_optional == 1 && (
            <Div className="organisms-multipleDetails-dropdown">
              <DropdownLabel
                label={t("observation")}
                dropdown_items={libraryQuizChoices}
                value={parseInt(selectedAnswer) || libraryQuizChoices[0].id}
                onChange={(event) => handleDropdownItemChange(event, this)}
                onSubmitClicked={() => handleSubmitClicked(this, true)}
              />
            </Div>
          )}
          {selectedLibrary && quizItem.is_optional == 0 && (
            <InputWithSubmit
              name={`${selectedLibrary}writtenAnswer`}
              value={this.state[`${selectedLibrary}writtenAnswer`] || ""}
              onChange={(e) => handleTextChange(e, this)}
              onClick={() => handleSubmitClicked(this, false)}
            />
          )}
        </Div>
      </Div>
    );
  }
}

//===================================================
// functions
//===================================================
/**
 * get Library items
 * @param {*} context
 */
const handleGetLibraryItems = (context) => {
  const { quizItem } = context.props;
  const uniqueLibraryItems = quizItem.stetho_sounds && [
    ...new Set(quizItem.stetho_sounds.map((item) => item.lib_type)),
  ];
  context.setState({
    uniqueLibraryItems,
    unsubmittedItems: JSON.parse(JSON.stringify(uniqueLibraryItems)),
  });
};

/**
 * get gender value
 * @param {*} index
 */
const getGender = (index) => {
  switch (parseInt(index)) {
    case 0:
      return "male";
    case 1:
      return "female";
    default:
      return "male";
  }
};

/**
 * Get library item name
 * @param {*} libType
 */
const getLibraryType = (libType) => {
  switch (libType) {
    case 1:
      return "ausculaide";
    case 2:
      return "palpation";
    case 3:
      return "ecg";
    case 4:
      return "inspection";
    case 5:
      return "xray";
    case 6:
      return "ucg";
    case 0:
      return "stetho";
    default:
      return "";
  }
};

/**
 * Set title and get choices for that library item
 * @param {*} context
 * @param {*} libType
 * @param {*} isOptional
 */
const handleSelectLibraryItem = (context, libType, isOptional) => {
  const libraryName = getLibraryType(libType);
  const { selectedLibrary, submittedLibraries } = context.state;
  const { quizItem } = context.props;
  const libraryItemsList =
    quizItem.stetho_sounds &&
    quizItem.stetho_sounds.filter((item) => item.lib_type == libType);
  const quizChoices =
    quizItem.quiz_choices &&
    quizItem.quiz_choices.filter((item) => item.lib_type == libType);
  let modifiedQuizChoices = [];
  quizChoices.forEach((element) => {
    modifiedQuizChoices = [
      ...modifiedQuizChoices,
      { value: element.title, value_en: element.title_en, ...element },
    ];
  });
  context.setState({
    selectedLibrary: libraryName,
    libraryQuizChoices: [
      { id: "", value: "what_is_your_diagonis" },
      ...modifiedQuizChoices,
    ],
    libraryItemsList: libraryItemsList,
    selectedImageIndex: 0,
    selectedAnswer: null,
  });
  if (selectedLibrary) {
    const submitItem =
      submittedLibraries.length > 0 &&
      submittedLibraries.find((item) => item.libItem === libraryName);
    if (submitItem) {
      context.setState({ selectedAnswer: submitItem.answer });
    } else {
      if (isOptional) {
        handleResetSelectTag();
      }
    }
  }
};

/**
 * Get selected dropdown item
 * @param {*} event
 * @param {*} context
 */
const handleDropdownItemChange = (event, context) => {
  context.setState({ selectedAnswer: event.target.value });
};

/**
 * Open next image
 * @param {*} context
 */
const handleNextIconClicked = (context) => {
  const { selectedImageIndex, libraryItemsList } = context.state;
  if (selectedImageIndex < libraryItemsList.length - 1) {
    context.setState({ selectedImageIndex: selectedImageIndex + 1 });
  }
};

/**
 * Open previous image
 * @param {*} context
 */
const handlePreviousIconClicked = (context) => {
  const { selectedImageIndex } = context.state;
  if (selectedImageIndex > 0) {
    context.setState({ selectedImageIndex: selectedImageIndex - 1 });
  }
};

/**
 * Get submitted item
 * @param {*} context
 * @param {*} isOptional
 */
const handleSubmitClicked = (context, isOptional) => {
  const {
    selectedLibrary,
    submittedLibraries,
    selectedAnswer,
    unsubmittedItems,
  } = context.state;
  // if (!selectedAnswer) {
  //   return;
  // }
  const remainingUnsubmitted = unsubmittedItems.filter(
    (item) => getLibraryType(item) !== selectedLibrary
  );
  let initialSubmitted =
    submittedLibraries.length > 0
      ? submittedLibraries.filter((item) => item.libItem !== selectedLibrary)
      : [];
  const totalSubmittedLibraries = [
    ...initialSubmitted,
    {
      libItem: selectedLibrary,
      isOptional: isOptional,
      answer: isOptional
        ? selectedAnswer
        : context.state[`${selectedLibrary}writtenAnswer`],
    },
  ];
  context.setState({
    submittedLibraries: totalSubmittedLibraries,
    unsubmittedItems: remainingUnsubmitted,
  });
  if (remainingUnsubmitted.length > 0) {
    context.setState(
      {
        selectedAnswer: null,
      },
      () =>
        handleSelectLibraryItem(context, remainingUnsubmitted[0], isOptional)
    );
  } else {
    context.props.handleAllMultipleAnswersSubmitted(totalSubmittedLibraries, {
      ...context.state,
      submittedLibraries: totalSubmittedLibraries,
      unsubmittedItems: remainingUnsubmitted,
    });
  }
};

/**
 * reset select to default
 */
const handleResetSelectTag = () => {
  let dropDown = document.getElementById("selectDropdown");
  dropDown.selectedIndex = 0;
};

/**
 * Set initial quiz states
 * @param {*} context
 */
const handleSetQuizState = (context) => {
  const { quizItem } = context.props;
  context.setState(
    {
      ...context.props.multipleQuizState,
    },
    () =>
      handleSelectLibraryItem(
        context,
        context.props.selectedLibrary,
        quizItem.is_optional == 1
      )
  );
};

/**
 * Set initial quiz states
 * @param {*} context
 */
const handleResetQuizState = (context) => {
  context.setState(
    {
      ...getInitialState,
    },
    () => handleGetLibraryItems(context)
  );
};

/**
 * Get written answer
 * @param {*} e
 * @param {*} context
 */
const handleTextChange = (e, context) => {
  context.setState({ [e.target.name]: e.target.value });
};
//===================================================
// actions
//===================================================

//===================================================
// redux
//===================================================

//===================================================
// export
//===================================================
export default withTranslation("translation")(MultiplePreChoice);
