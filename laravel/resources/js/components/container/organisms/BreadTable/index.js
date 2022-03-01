import React from "react";
import { connect } from "react-redux";

// components
import Div from "../../../presentational/atoms/Div";
import Table from "../../../presentational/atoms/Table";
import Button from "../../../presentational/atoms/Button";

//lib
import { CircularProgress } from "@material-ui/core";

// style
import "./style.css";

//i18n
import { withTranslation } from "react-i18next";

const header = [
  "user_name",
  "group",
  "user_id",
  "mail_address",
  "created_data_time",
];
//===================================================
// Component
//===================================================
class BreadTable extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      editItem: {
        ID: "",
        UserName: "",
        UserID: "",
        MailAddress: "",
      },
    };
  }

  render() {
    const { data } = this.props;
    const table_data = data && data.table_data ? data.table_data : [];
    const { t, isActive } = this.props;
    console.log("table data", table_data);
    return (
      <Div className="molecules-userTable-wrapper">
        <Table size="lg">
          <thead>
            <tr>
              <th style={{ width: "80px" }}>ID</th>
              {header.map((header, index) => {
                return <th key={index}>{t(`${header}`)}</th>;
              })}
              {!isActive && <th>{t("disabled_date")}</th>}
            </tr>
          </thead>
          {table_data.length != 0 && !data.isLoading
            ? getTableData(this, table_data)
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
 * Return table data
 * @param {*} context
 * @param {*} table_data
 */
const getTableData = (context, table_data) => {
  const { t, isActive } = context.props;
  let data;
  data = table_data;
  if (!data || data.length === 0) {
    return getEmptyResult(context);
  }
  return (
    <tbody>
      {data.map((value, index) => {
        return (
          <tr key={index}>
            <td style={{ width: "80px" }}>{value.ID}</td>
            <td>{value.UserName}</td>
            <td>{value.University_name}</td>
            <td>{value.UserID}</td>
            <td>{value.MailAddress}</td>
            <td>{value.CreatedDate}</td>
            {!isActive && <td>{value.DisabledDate || "-"}</td>}
            {/* {this.props.authUser.user.role == 101 && ( */}
            {/* <td>
                <Button
                  mode="active"
                  onClick={() => handleEditModalVisible(context, value)}
                >
                  {t("edit")}
                </Button>
              </td> */}
          </tr>
        );
      })}
    </tbody>
  );
};

/**
 * Display empty or loading messing
 * @param {*} context
 */
const getEmptyResult = (context) => {
  const { t, data } = context.props;
  return (
    <tbody>
      {data && data.isLoading ? (
        <tr>
          <td className="text-center" colSpan={header.length + 2}>
            <CircularProgress />
          </td>
        </tr>
      ) : (
        <tr>
          <td className="text-center" colSpan={header.length + 2}>
            {t("empty_data")}
          </td>
        </tr>
      )}
    </tbody>
  );
};

/**
 * show/hide edit modal
 * @param {*} context
 * @param {*} value
 */
const handleEditModalVisible = (context, value) => {
  context.props.setEditModalVisible(value);
};

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================
const mapStateToProps = (state) => {
  return {
    isUserDisableSuccess: state.userManagement.isUserDisableSuccess,
  };
};
//===================================================
// Export
//===================================================
export default connect(mapStateToProps)(
  withTranslation("translation")(BreadTable)
);
