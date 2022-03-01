import React from "react";

// bootstrap
import "bootstrap/dist/css/bootstrap.min.css";
import Menu from "../../container/templates/Menu";
import ExamManagement from "../../container/templates/ExamManagement";
import Div from "../../presentational/atoms/Div";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class ExamManagePage extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { data, tasks, history } = this.props;
    return (
      <Div className="page-ExamManagePage-wrapper">
        <Menu
          children={
            <ExamManagement data={data} tasks={tasks} history={history} />
          }
          pageTitle={"exam_management"}
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
export default ExamManagePage;
