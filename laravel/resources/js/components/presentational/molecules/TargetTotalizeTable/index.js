import React from "react";

// components
import Div from "../../../presentational/atoms/Div";
import Table from "../../../presentational/atoms/Table";
import Label from "../../../presentational/atoms/Label";

// style
import "./style.css";

//i18n
import { withTranslation } from "react-i18next";

// libs
import { CircularProgress } from "@material-ui/core";

const tableHeader = ["", "filtered", "group_data", "all_data"];

//===================================================
// Component
//===================================================
class TargetTotalizeTable extends React.Component {
  render() {
    const { filteredTargetList, isTargetLoading, t } = this.props;
    return (
      <Div className="molecules-targetTotalizeTable-wrapper">
        <Label className="molecules-targetTotalize-title">
          {t("aggregate_data")}
        </Label>
        <Table size="lg">
          <thead>
            <tr>
              {tableHeader.map((header, index) => {
                return <th key={index}>{(index==0)?header : t(header)}</th>;
              })}
            </tr>
          </thead>
          {!isTargetLoading &&
          filteredTargetList &&
          Object.keys(filteredTargetList).length != 0
            ? getTableData(this)
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
 * @param {*} context
 */
const getTableData = (context) => {
  const { filteredTargetList, t } = context.props;
  const rowTitle = Object.keys(filteredTargetList);
  return (
    <tbody>
      {rowTitle &&
        rowTitle.map((value, index) => {
          return (
            <tr key={index}>
              <td className="molecules-targetTotalize-rowTitle">{t(value)}</td>
              <td>{filteredTargetList[value]["Filtered"]}</td>
              <td>{filteredTargetList[value]["Own Data"]}</td>
              <td>{filteredTargetList[value]["System Data"]}</td>
            </tr>
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
  const { t, isTargetLoading } = context.props;
  return (
    <tbody>
      {!isTargetLoading ? (
        <tr>
          <td className="text-center" colSpan={tableHeader.length}>
            {t("empty_data")}
          </td>
        </tr>
      ) : (
        <tr>
          <td className="text-center" colSpan={tableHeader.length}>
            < CircularProgress />
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
export default withTranslation("translation")(TargetTotalizeTable);
