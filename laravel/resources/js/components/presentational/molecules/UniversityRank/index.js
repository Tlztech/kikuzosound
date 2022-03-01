import React from "react";

// redux

// components
import Label from "../../atoms/Label/index";
import Table from "../../atoms/Table";
import Div from "../../atoms/Div/index";
import ToggleBar from "../../atoms/ToggleBar/index";

// bootstrap
import { Col, Row } from "react-bootstrap";

// style
import "./style.css";

//===================================================
// Component
//===================================================
class UniversityRank extends React.Component {
  constructor(props) {
    super(props);
  }
  render() {
    return (
      <Div className="molecules-UniversityRank-wrapper">
        <Table size="md">
          <thead>
            <tr>
              <th>{this.props.label1}</th>
              <th>{this.props.label2}</th>
              <th>{this.props.label3}</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </Table>
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
export default UniversityRank;
