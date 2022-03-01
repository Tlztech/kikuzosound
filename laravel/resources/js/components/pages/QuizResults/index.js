import React from "react";

// components
import Div from "../../presentational/atoms/Div";
import QuizResults from "../../container/templates/QuizResults";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class QuizResultsPage extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { history } = this.props;
    return (
      <Div className="pages-QuizResults-wrapper">
        <QuizResults history={history} />
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
export default QuizResultsPage;
