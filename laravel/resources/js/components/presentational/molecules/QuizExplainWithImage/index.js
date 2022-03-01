import React from "react";

// components
import Div from "../../atoms/Div/index";
import Image from "../../atoms/Image";
import Label from "../../atoms/Label";
import default_icon from "../../../../assets/default_icon.jpg";

// style
import "./style.css";

//===================================================
// Component
//===================================================
class QuizExplainWithImage extends React.Component {
  constructor(props) {
    super(props);
  }
  render() {
    const { answerExplanation, explanationImageUrl } = this.props;
    return (
      <Div className="molecules-quizExplainWithImage-wrapper">
        <Label>{answerExplanation}</Label>
        <Div className="molecules-quizExplain-image-wrapper">
          <Image url={explanationImageUrl || default_icon} />
        </Div>
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default QuizExplainWithImage;
