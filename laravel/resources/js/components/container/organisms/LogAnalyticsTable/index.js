import React from "react";

// components
import Div from "../../../presentational/atoms/Div";
import Table from "../../../presentational/atoms/Table";
import TextWithIcon from "../../../presentational/molecules/TextWithIcon";

// style
import "./style.css";

//i18n
import { withTranslation } from "react-i18next";

// libs
import { CircularProgress } from "@material-ui/core";

const tableHeader = [
  "group",
  "user",
  "type",
  "exam",
  "quiz",
  "library",
  "library_type",
  "correct",
  "usage_time"
];
//===================================================
// Component
//===================================================
class LogAnalyticsTable extends React.Component {
  render() {
    const { tableData, handleSortList, t } = this.props;
    return (
      <Div className="organism-logAnalyticsTable-wrapper">
        <Table size="lg">
          <thead>
            <tr>
              {tableHeader.map((header, index) => {
                return (
                  <th key={index}>
                    <TextWithIcon
                      index={index}
                      title={t(header)}
                      onClick={() => handleSortList(getHeaderItem(header))}
                    />
                  </th>
                );
              })}
            </tr>
          </thead>
          {tableData && tableData.length != 0
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
  const { tableData, t } = context.props;
  return (
    <tbody>
      {tableData.map((value, index) => {
        return (
          <tr key={index}>
            <td>{value.univ}</td>
            <td>{value.user}</td>
            <td>{value.exam_type}</td>
            <td>{value.exam}</td>
            <td>{value.quiz}</td>
            <td>
              {(value.library.length>0)? (value.library.map((val, index) => (
                <p key={index}>{(value.library.length>1) ? (index+1) + ". ":""}{val}</p>
              ))):"--"}
            </td>
            <td>
              {(value.library_type.length>0)? (value.library_type.map((val, index) => (
                <p key={index}>{(value.library_type.length>1) ? (index+1) + ". ":""}{val}</p>
              ))):"--"}
            </td>
            <td>{value.correct}</td>
            <td>{value.used_time}</td>
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
  const { isLogLoading, t } = context.props;
  return (
    <tbody>
      {!isLogLoading ? (
        <tr>
          <td className="text-center" colSpan={tableHeader.length}>
            {t("empty_data")}
          </td>
        </tr>
      ) : (
        <tr>
          <td className="text-center" colSpan={tableHeader.length}>
            <CircularProgress />
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
      return "univ";
    case "user":
      return "user";
    case "type":
      return "exam_type";
    case "exam":
      return "exam";
    case "quiz":
      return "quiz";
    case "library":
      return "library";
    case "library_type":
      return "library_type";
    case "correct":
      return "correct";
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
export default withTranslation("translation")(LogAnalyticsTable);
