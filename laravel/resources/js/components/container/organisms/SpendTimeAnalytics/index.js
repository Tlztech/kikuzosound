import React from "react";

// bootstrap
import { Container, Row, Col } from "react-bootstrap";

// components
import Div from "../../../presentational/atoms/Div";
import PieChart from "../../../presentational/molecules/PieChart";
import SpendTimeRanking from "../../../presentational/molecules/SpendTimeRanking";
import LogAnalyticsDropDown from "../../../presentational/molecules/LogAnalyticsDropDown";

// style
import "./style.css";

//===================================================
// Component
//===================================================
class SpendTimeAnalytics extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { title, data } = this.props;
    return (
      <Container>
        <Div className="organisms-SpendTimeAnalytics-wrapper">
          <LogAnalyticsDropDown />
          <Div className="chart-wrapper">
            <Col md={7}>
              <PieChart title={title} data={data} />
            </Col>
            <Col md={4}>
              <SpendTimeRanking />
            </Col>
          </Div>
        </Div>
      </Container>
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
export default SpendTimeAnalytics;
