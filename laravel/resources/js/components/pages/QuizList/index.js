import React from "react";

// components
import Div from "../../presentational/atoms/Div";
import Menu from "../../container/templates/Menu";
import QuizList from "../../container/templates/QuizList";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class QuizPacks extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { data } = this.props;
    return (
      <Div className="page-QuickPacksManagement-wrapper">
        <Menu
          children={<QuizList data={data} />}
          pageTitle={"quizzes_sidebar"}
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
export default QuizPacks;
