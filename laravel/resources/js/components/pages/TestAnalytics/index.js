import React from "react";

// components
import Div from "../../presentational/atoms/Div";
import Menu from "../../container/templates/Menu";
import GraphTable from "../../container/templates/GraphTable/index";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class TestAnalytics extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      exam_id: "",
    };
  }

  componentDidMount() {
    this.setState({
      exam_id:
        this.props.location && this.props.location.state
          ? this.props.location.state.value
          : "",
    });
    history.replaceState(null, "");
  }

  render() {
    return (
      <Div className="page-TestAnalytics-wrapper">
        <Menu
          children={<GraphTable exam_id={this.state.exam_id} />}
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
export default TestAnalytics;
