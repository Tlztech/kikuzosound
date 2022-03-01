import React from "react";

// components
import Table from "../../atoms/Table";
import Div from "../../atoms/Div/index";

//i18n
import { withTranslation } from "react-i18next";

// style
import "./style.css";

const tableHeader = ["filtered", "group_data"];
const subHeader = ["Ranking", "User_name", "Average", "User_name", "Average"];
//===================================================
// Component
//===================================================
class ExamResultUserRankTable extends React.Component {
  render() {
    const { tableData, t } = this.props;
    const tableRows = (tableData && Object.keys(tableData.own_data)) || [];
    return (
      <Div className="molecules-ExamResultUserRankTable-wrapper">
        <Table size="lg">
          <thead>
            <tr className="examResultUserRankHeader">
              <th colSpan={1}></th>
              {tableHeader.map((header, index) => {
                return (
                  <th colSpan={2} key={index}>
                    {t(header)}
                  </th>
                );
              })}
            </tr>
            <tr>
              {subHeader.map((item, index) => {
                return (
                  <th colSpan={1} key={index}>
                    {t(item)}
                  </th>
                );
              })}
            </tr>
          </thead>
          <tbody>
            {tableRows.map((value, index) => {
              const rowIndex = index + 1;
              const filteredItem = tableData.filtered[`rank_${rowIndex}`];
              const ownItem = tableData.own_data[`rank_${rowIndex}`];
              return (
                <React.Fragment key={index + 20}>
                  {index < 5 && (
                    <tr key={index}>
                      <td className="molecules-id-col">{rowIndex}</td>
                      <td>{filteredItem ? filteredItem.name : "-"}</td>
                      <td>{filteredItem ? filteredItem.result : "-"}</td>
                      <td>{ownItem ? ownItem.name : "-"}</td>
                      <td>{ownItem ? ownItem.result : "-"}</td>
                    </tr>
                  )}
                </React.Fragment>
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
export default withTranslation("translation")(ExamResultUserRankTable);
