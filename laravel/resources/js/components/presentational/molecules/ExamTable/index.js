import React from "react";

// style
import "./style.css";

//i18n
import { withTranslation } from "react-i18next";

let is_loaded = false;
//===================================================
// Component
//===================================================
class ExamTable extends React.Component {
  constructor() {
    super();
  }

  componentWillUnmount() {
    is_loaded = false;
  }

  render() {
    const table_data = this.props.data.table_data
      ? this.props.data.table_data
      : [];
    return (
      <>
        {table_data.length != 0 && !this.props.data.isLoading
          ? getTableData(this, table_data)
          : getEmptyResult(this)}
      </>
    );
  }
}

//===================================================
// Functions
//===================================================
const getTableData = (context, table_data) => {
  if (is_loaded === false) {
    is_loaded = true;
  }
  return (
    <tbody>
      {table_data.map((value, index) => {
        return (
          <tr key={index}>
            <td>{value.id}</td>
            <td>{value.exam_group}</td>
            <td>{value.exam}</td>
            <td>{value.user}</td>
            <td>{value.start_datetime}</td>
            <td>{value.end_datetime}</td>
            <td>{value.used_time}</td>
            <td>{value.answer}</td>
            <td>{value.miss_ok}</td>
          </tr>
        );
      })}
    </tbody>
  );
};

const getEmptyResult = (context) => {
  const { t } = context.props;
  return (
    <tbody>
      {context.props.data.isLoading ? (
        <tr>
          <td className="empty-result">{t("loading")}</td>
        </tr>
      ) : (
        <tr>
          <td className="empty-result">{t("empty_data")}</td>
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
export default withTranslation("translation")(ExamTable);
