import React from "react";

// components
import Div from "../../presentational/atoms/Div";
import Menu from "../../container/templates/Menu";
import QuizPack from "../../container/templates/QuizPacks";

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
        <Menu children={<QuizPack data={data} />} pageTitle={"quizpacks"} />
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
