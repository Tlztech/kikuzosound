import React from "react";

// Components
import Div from "../../../presentational/atoms/Div";
import P from "../../../presentational/atoms/P";
import Br from "../../../presentational/atoms/Br";
import Button from "../../../presentational/atoms/Button";
import Image from "../../../presentational/atoms/Image";
import Label from "../../../presentational/atoms/Label";

// images
import {
  LineOrange,
  QuizDescriptionImage,
  QuizDescriptionImageEN,
  QuizStartImage,
  QuizStartImageEN,
} from "../../../../assets";

// css
import "./style.css";

// i18next
import i18next from "i18next";
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class QuizStartScreen extends React.Component {
  render() {
    const { onStartClicked, onCloseClicked, quizTitle, t } = this.props;
    return (
      <Div className="organism-quizStartScreen-wrapper">
        <Div className="organism-topbar-container">
          <Label className="organism-quizTitle">{quizTitle}</Label>
          <Button
            className="organism-close-button"
            mode="quiz-options"
            onClick={onCloseClicked}
          >
            {t("close")}
          </Button>
        </Div>
        <Div className="organism-quizStartScreen-container">
          <Div className="organism-image-button-container">
            <Image className="organism-orange-line" url={LineOrange} />
            <Image
              onClick={onStartClicked && onStartClicked}
              className="organism-start-button-image"
              url={
                i18next.language === "ja" ? QuizStartImage : QuizStartImageEN
              }
            />
            <Image className="organism-orange-line" url={LineOrange} />
          </Div>
          <Div className="organism-image-text-wrapper">
            <Div className="organism-description-image">
              <Image
                url={
                  i18next.language === "ja"
                    ? QuizDescriptionImage
                    : QuizDescriptionImageEN
                }
              />
            </Div>
            <P>
              {t("quiz_start_description1")}
              <Br />
              <Br />
              {t("quiz_start_description2")}
            </P>
          </Div>
        </Div>
      </Div>
    );
  }
}

//===================================================
// functions
//===================================================

//===================================================
// actions
//===================================================

//===================================================
// redux
//===================================================

//===================================================
// export
//===================================================
export default withTranslation("translation")(QuizStartScreen);
