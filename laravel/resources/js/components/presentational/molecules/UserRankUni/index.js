import React from "react";

// redux

// components
import Table from "../../atoms/Table";
import Div from "../../atoms/Div/index";

// bootstrap

// style
import "./style.css";

//===================================================
// Component
//===================================================
class UserRankUni extends React.Component {
  constructor(props) {
    super(props);
  }
  render() {
    const { header, table_data } = this.props;
    return (
      <Div className="molecules-UserRankUni-wrapper">
        <Table size="sm" bordered="bordered" striped="striped">
          <thead>
            <tr>
              {header.map((header, index) => {
                return <th key={index}>{header}</th>;
              })}
            </tr>
          </thead>
          <tbody>
            {table_data.map((value, index) => {
              return (
                <tr key={index}>
                  <td>{value.id}</td>
                  <td>{value.user_name}</td>
                  <td>{value.user_point}</td>
                </tr>
              );
            })}
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
export default UserRankUni;
