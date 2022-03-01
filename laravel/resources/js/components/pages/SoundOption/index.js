import React from "react";

// components
import Div from "../../presentational/atoms/Div";
import SimpleSoundOptionPlayer from "../../container/templates/SimpleSoundOptionPlayer";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class MultipleDetailsPage extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { quizTitle, title, soundOptions, timerValue } = this.props;
    return (
      <Div className="pages-MultipleDetails-wrapper">
        <SimpleSoundOptionPlayer
          quizTitle={quizTitle}
          title={title}
          soundOptions={soundOptions}
          timerValue={timerValue}
        />
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
export default MultipleDetailsPage;
