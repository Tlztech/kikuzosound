import React from "react";

// components
import Div from "../../../presentational/atoms/Div";
import Table from "../../../presentational/atoms/Table";
import TextWithIcon from "../../../presentational/molecules/TextWithIcon";

// style
import "./style.css";

// libs
import { CircularProgress } from "@material-ui/core";

//i18n
import { withTranslation } from "react-i18next";

const userTableHeader = [
  "group",
  "user",
  "title",
  "qstn_number",
  "correct_avg",
  "rate",
  "average_study_time_day",
];
const groupTableHeader = [
  "group",
  "title",
  "qstn_number",
  "correct_avg",
  "rate",
  "average_study_time_day",
];

//===================================================
// Component
//===================================================
class ExamAnalyticsTable extends React.Component {
  render() {
    const { data, handleSortTable, t, chartType } = this.props;
    let tableHeader = getHeaderData(chartType);
    return (
      <Div className="organism-examAnalyticsTable-wrapper">
        <Table size="lg">
          <thead>
            <tr>
              {tableHeader.map((header, index) => {
                return (
                  <th key={index}>
                    <TextWithIcon
                      textAlign="left"
                      title={t(header)}
                      index={index}
                      onClick={() => handleSortTable(getHeaderItem(header))}
                    />
                  </th>
                );
              })}
            </tr>
          </thead>
          {data && data.table_data.length != 0
            ? getTableData(data, chartType)
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
 * Return table header
 * @param {*} chartType
 */
const getHeaderData = (chartType) => {
  if (chartType == "group") {
    return groupTableHeader;
  } else {
    return userTableHeader;
  }
};
/**
 * Return table body rows
 * @param {*} data
 */
const getTableData = (data, chartType) => {
  return (
    <tbody className="organism-table-body">
      {data.table_data.map((value, index) => {
        return (
          <tr key={index}>
            <td>{value.group_name}</td>
            {chartType == "user" ? <td>{value.user_name}</td> : null}
            <td>{value.exam_title}</td>
            <td>{value.number_of_question}</td>
            <td>{value.correct_answers}</td>
            <td>{value.rate}</td>
            <td>{value.average_time}</td>
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
  const { t, data } = context.props;
  return (
    <tbody>
      {data && data.isLoading ? (
        <tr>
          <td className="no-data" colSpan={userTableHeader.length}>
            <CircularProgress />
          </td>
        </tr>
      ) : (
        <tr>
          <td className="no-data" colSpan={userTableHeader.length}>
            {t("empty_data")}
          </td>
        </tr>
      )}
    </tbody>
  );
};

/**
 * Get key value for header
 * @param {*} header
 * @returns
 */
const getHeaderItem = (header) => {
  switch (header) {
    case "group":
      return "group_name";
    case "user":
      return "user_name";
    case "title":
      return "exam_title";
    case "qstn_number":
      return "number_of_question";
    case "correct_avg":
      return "correct_answers";
    case "rate":
      return "rate";
    case "average_study_time_day":
      return "average_time";
    default:
      return header;
  }
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
export default withTranslation("translation")(ExamAnalyticsTable);
