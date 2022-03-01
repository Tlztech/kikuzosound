import React from "react";

// components
import Div from "../../../presentational/atoms/Div";
import Table from "../../../presentational/atoms/Table";

// style
import "./style.css";

//i18n
import { withTranslation } from "react-i18next";

const tableHeader = ["filtered", "all_own_group_ranking"];
const subTableHeader = [
  "ranking",
  "title",
  "no_of_answer",
  "title",
  "no_of_answer",
];

//===================================================
// Component
//===================================================
class PopularQuizRankingTable extends React.Component {
  render() {
    const { tableData, t } = this.props;
    const tableRows = (tableData && Object.keys(tableData.own_data)) || [];
    return (
      <Div className="molecule-popularQuizRankingTable-wrapper">
        <Table size="lg">
          <thead>
            <tr className="popularQuizLibraryTableHeader">
              <th></th>
              {tableHeader.map((header, index) => {
                return (
                  <th key={index} colSpan={2}>
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
            ? getTableData(tableData, tableRows, this)
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
                <tr key={rowIndex}>
                  <td>{rowIndex}</td>
                  <td>{filteredItem ? filteredItem.name : "-"}</td>
                  <td>{filteredItem ? filteredItem.total_browsed : "-"}</td>
                  <td>{ownItem ? ownItem.name : "-"}</td>
                  <td>{ownItem ? ownItem.total_browsed : "-"}</td>
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
  const { t } = context.props;
  return (
    <tbody>
      {data && data.isLoading ? (
        <tr>
          <td className="text-center" colSpan={tableHeader.length}>
            {t("loading")}
          </td>
        </tr>
      ) : (
        <tr>
          <td className="text-center" colSpan={tableHeader.length}>
            {t("empty_data")}
          </td>
        </tr>
      )}
    </tbody>
  );
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
export default withTranslation("translation")(PopularQuizRankingTable);
