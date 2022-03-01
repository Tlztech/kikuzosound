import React from "react";

// components
import Div from "../../presentational/atoms/Div";
import Menu from "../../container/templates/Menu";
import ExamAnalyticsTemplate from "../../container/templates/ExamAnalytics";

//css
import "./style.css";

//===================================================
// Component
//===================================================
class ExamAnalytics extends React.Component {
  componentDidUpdate() {
    if (this.props.location.state) {
      this.props.history.push("/");
      this.props.history.push("/analytics");
    }
  }
  render() {
    const { data } = this.props;
    let analytics_params = this.props.location.state;
    return (
      <Div className="page-examAnalyticsManagement-wrapper">
        <Menu
          children={
            <ExamAnalyticsTemplate
              data={data}
              query_params={analytics_params}
            />
          }
          pageTitle={"test_analysis"}
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
export default ExamAnalytics;
