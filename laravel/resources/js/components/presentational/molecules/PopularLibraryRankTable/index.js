import React from "react";

// components
import Table from "../../atoms/Table";
import Div from "../../atoms/Div";

//i18n
import { withTranslation } from "react-i18next";

// style
import "./style.css";

const tableHeader = ["filtered", "all_own_group_ranking"];
const subTableHeader = [
  "ranking",
  "title",
  "time",
  "title",
  "time",
];

//===================================================
// Component
//===================================================
class PopularLibraryRankTable extends React.Component {
  render() {
    const { tableData, t } = this.props;
    const tableRows = (tableData && Object.keys(tableData.own_data)) || [];
    return (
      <Div className="molecules-PopularLibraryRankTable-wrapper">
        <Table size="lg">
          <thead>
            <tr className="popularLibraryTableHeader">
              <th></th>
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
          {tableData && tableData.length != 0
            ? getTableData(tableData, tableRows, this, subTableHeader)
            : getEmptyResult(this)}
        </Table>
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================
/**
 * Return table body rows
 * @param {*} tableData
 * @param {*} tableRows
 */
const getTableData = (tableData, tableRows, context) => {
  const { filtered, own_data, system_data } = tableData;
  const { t } = context.props;
  return (
    <tbody>
      {tableRows &&
        tableRows.map((value, index) => {
          const rowIndex = index + 1;
          const filteredItem = filtered[`rank_${rowIndex}`];
          const ownItem = own_data[`rank_${rowIndex}`];
          return (
            <React.Fragment key={index + 20}>
              {index < 5 && (
                <tr key={index}>
                  <td>{index + 1}</td>
                  <td>{(filteredItem && filteredItem.name) || "-"}</td>
                  <td>
                    {(filteredItem && toHHMMSS(filteredItem.total_time)) || "-"}
                  </td>
                  <td>{(ownItem && ownItem.name) || "-"}</td>
                  <td>{(ownItem && toHHMMSS(ownItem.total_time)) || "-"}</td>
                </tr>
              )}
            </React.Fragment>
          );
        })}
    </tbody>
  );
};

/**
 * Display empty if no data
 * @param {*} context
 */
const getEmptyResult = (context) => {
  const { t, tableData } = context.props;
  return (
    <tbody>
      {!tableData && (
        <tr>
          <td className="text-center" colSpan={tableHeader.length}>
            {t("empty_data")}
          </td>
        </tr>
      )}
    </tbody>
  );
};

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
export default withTranslation("translation")(PopularLibraryRankTable);
