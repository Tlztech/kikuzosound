import React from "react";

// components
import Div from "../../atoms/Div/index";
import Table from "../../atoms/Table/index";
import List from "../../atoms/Li/index";

// style
import "./style.css";

//===================================================
// Component
//===================================================
class UserBoxLayout extends React.Component {
  constructor(props) {
    super(props);
  }
  render() {
    const { header, table_data, items } = this.props;
    return (
      <Div className="molecules-UserBoxLayout-wrapper">
        <Table size="lg" striped="striped" bordered="bordered">
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
        <Div className="user-list mt-4">
          <List items={items} />
        </Div>
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
export default UserBoxLayout;
