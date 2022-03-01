import React from "react";

// components
import Table from "../../atoms/Table";
import Div from "../../atoms/Div/index";

//i18n
import { withTranslation } from "react-i18next";

// style
import "./style.css";

const tableHeader = ["filtered", "group_data"];
const subHeader = [
  "Ranking",
  "User_name",
  "Total",
  "User_name",
  "Study_time_total",
];
//===================================================
// Component
//===================================================
class LearningTimeRankingTable extends React.Component {
  render() {
    const { tableData, t } = this.props;
    const tableRows = (tableData && Object.keys(tableData.own)) || [];
    return (
      <Div className="molecules-LearningTimeRankingTable-wrapper">
        <Table size="lg">
          <thead>
            <tr className="learningRankHeader">
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
              {subHeader.map((subheader, index) => {
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
              const { filtered, own } = tableData;
              const rowIndex = index + 1;
              const filteredItem = filtered[`rank_${rowIndex}`];
              const ownItem = own[`rank_${rowIndex}`];
              return (
                <React.Fragment key={index + 20}>
                  {index < 5 && (
                    <tr key={index}>
                      <td className="molecules-id-col">{index + 1}</td>
                      <td className="molecules-id-col">
                        {filteredItem ? filteredItem && filteredItem.name : "-"}
                      </td>
                      <td className="molecules-id-col">
                        {filteredItem ? toHHMMSS(filteredItem.total_time) : "-"}
                      </td>
                      <td className="molecules-id-col">
                        {ownItem ? ownItem && ownItem.name : "-"}
                      </td>
                      <td className="molecules-id-col">
                        {ownItem ? toHHMMSS(ownItem.total_time) : "-"}
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
/**
 * Change to HourMinSec format
 * @param {*} seconds
 */
const toHHMMSS = (secs) => {
  const sec_num = parseInt(secs, 10);
  const hours = Math.floor(sec_num / 3600);
  const minutes = Math.floor(sec_num / 60) % 60;
  const seconds = sec_num % 60;
  return [hours, minutes, seconds]
    .map((v) => (v < 10 ? "0" + v : v))
    .filter((v, i) => v !== "00" || i > 0)
    .join(":");
};

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default withTranslation("translation")(LearningTimeRankingTable);
