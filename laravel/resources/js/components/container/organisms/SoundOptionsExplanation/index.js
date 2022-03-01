import React from "react";

// Components
import Div from "../../../presentational/atoms/Div";
import Label from "../../../presentational/atoms/Label";
import SoundWithLabel from "../../../presentational/molecules/SoundWithLabel";
import QuizOptionButtons from "../../../presentational/molecules/QuizOptionButtons";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class SoundOptionExplanation extends React.Component {
  render() {
    const { title, soundOptions } = this.props;
    return (
      <Div className="organism-soundOptionExplanation-container">
        <Label>{title}</Label>
        <Div className="organism-soundOptionExplanation-options">
          {soundOptions.map((item, index) => (
            <SoundWithLabel key={index} />
          ))}
        </Div>
        <QuizOptionButtons soundOptions={soundOptions} isExplanation={true} />
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
