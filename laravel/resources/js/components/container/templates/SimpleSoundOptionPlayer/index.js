import React from "react";

// Components
import Div from "../../../presentational/atoms/Div";
import Button from "../../../presentational/atoms/Button";
import Label from "../../../presentational/atoms/Label";
import SoundOptionPlayer from "../../organisms/SoundOptionPlayer";
import QuizOptionButtons from "../../../presentational/molecules/QuizOptionButtons";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class SimpleSoundOptionPlayer extends React.Component {
  render() {
    const { quizTitle, title, soundOptions, timerValue } = this.props;
    return (
      <Div className="template-soundOptionPlayer-wrapper">
        <Label className="template-quizTitle">{quizTitle}</Label>
        <Div className="template-soundOption-wrapper">
          <Button mode="finish">Finish</Button>
          <SoundOptionPlayer
            soundOptions={soundOptions}
            title={title}
            timerValue={timerValue}
          />
          <QuizOptionButtons soundOptions={soundOptions} />
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
export default SimpleSoundOptionPlayer;
