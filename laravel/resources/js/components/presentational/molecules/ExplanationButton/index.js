import React from "react";

// Components
import Div from "../../atoms/Div";
import Button from "../../atoms/Button";
import Image from "../../atoms/Image";
import P from "../../atoms/P";

//image
import { ArrowRight, ScoreTrue, ScoreFalse } from "../../../../assets";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class ExplanationButton extends React.Component {
  render() {
    const { title, name, isCorrect, onClick } = this.props;
    return (
      <Button
        mode={!isCorrect && "correct"}
        onClick={onClick}
        className="molecules-explanationbutton-container"
      >
        <Div className="molecules-explanationbutton">
          <Image
            url={isCorrect ? ScoreTrue : ScoreFalse}
            className="molecules-explanationbutton-image-score"
          />
          <P>{title}</P>
        </Div>
        <Div className="molecules-explanationbutton">
          <P>{name}</P>
          <Image url={ArrowRight} />
        </Div>
      </Button>
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
export default ExplanationButton;
