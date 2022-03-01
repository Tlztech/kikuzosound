import React from "react";

// components
import Table from "../../atoms/Table";
import Div from "../../atoms/Div/index";

//i18n
import { withTranslation } from "react-i18next";

// style
import "./style.css";

const tableHeader = ["filtered", "group_data"];
const subTableHeader = ["Ranking", "Title", "average", "Title", "average"];

//===================================================
// Component
//===================================================
class ExamResultRankCorrectRateTable extends React.Component {
  render() {
    const { tableData, t } = this.props;
    const tableRows = (tableData && Object.keys(tableData.own_data)) || [];
    const { filtered, own_data } = tableData;
    return (
      <Div className="molecules-ExamResultRankCorrectRateTable-wrapper">
        <Table size="lg">
          <thead>
            <tr className="examResultTableHeader">
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
              {subTableHeader.map((subheader, index) => {
                return (
                  <th colSpan={1} key={index}>
                    {t(subheader)}
                  </th>
                );
              })}
            </tr>
          </thead>
          <tbody>
            {tableRows.map((value, index) => {
              const rowIndex = index + 1;
              const filteredItem = filtered[`rank_${rowIndex}`];
              const ownItem = own_data[`rank_${rowIndex}`];
              return (
                <React.Fragment key={index + 20}>
                  {index < 5 && (
                    <tr key={rowIndex}>
                      <td className="molecules-id-col">{rowIndex}</td>
                      <td>{filteredItem ? filteredItem.name : "-"}</td>
                      <td className="molecules-id-col">
                        {filteredItem ? filteredItem.rate : "-"}
                      </td>
                      {/* <td className="molecules-id-col">
                        {ownItem ? ownItem.id : "-"}
                        </td> */}
                      <td>{ownItem ? ownItem.name : "-"}</td>
                      <td className="molecules-id-col">
                        {ownItem ? ownItem.rate : "-"}
                      </td>
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
export default withTranslation("translation")(ExamResultRankCorrectRateTable);
