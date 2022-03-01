import React from "react";

// Components
import Div from "../../../presentational/atoms/Div";
import Button from "../../../presentational/atoms/Button";
import Label from "../../../presentational/atoms/Label";
import SoundOptionsExplanationOrganism from "../../organisms/SoundOptionsExplanation";
import FinalAnswerText from "../../../presentational/molecules/FinalAnswerText";
import QuizExplainWithImage from "../../../presentational/molecules/QuizExplainWithImage";

// css
import "./style.css";

//===================================================
// Component
//===================================================
const soundOptions = [
  { id: 1, title: "Sound 1", url: "http://abc.com/a.mp3" },
  { id: 2, title: "Sound 2", url: "http://abc.com/a.mp3", isSelected: true },
  { id: 3, title: "Sound 3", url: "http://abc.com/a.mp3" },
  { id: 4, title: "Sound 4", url: "http://abc.com/a.mp3" },
];

class SoundOptionExplanation extends React.Component {
  render() {
    const { explanationImageUrl } = this.props;
    return (
      <Div className="template-soundOptionExplanation-wrapper">
        <Label className="template-quizTitle">{"Quiz Lungs Sound"}</Label>
        <Div className="template-soundOption-wrapper">
          <Div className="template-button-title-wrapper">
            <Button mode="cancel">Back</Button>
            <Label>No5</Label>
          </Div>
          <SoundOptionsExplanationOrganism
            soundOptions={soundOptions}
            title={"Lungs Sound"}
          />
          <FinalAnswerText
            correctAnswer="This was the correct answer."
            myAnswer="This was your answer."
          />
          <QuizExplainWithImage
            answerExplanation="This is the explanation to the answer of the question above. Read this explanation carefully."
            explanationImageUrl={explanationImageUrl}
          />
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
export default SoundOptionExplanation;
