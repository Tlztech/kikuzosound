import React from "react";
import { connect } from "react-redux";

// components
import Div from "../../../presentational/atoms/Div";
import Toast from "../../../presentational/molecules/Toast";
import HomeList from "../../organisms/HomeList";
import Label from "../../../presentational/atoms/Label";
import CustomPagination from "../../../presentational/molecules/CustomPagination";

//css
import "./style.css";

// redux
import { setToast } from "../../../../redux/modules/actions/LoginAction";
import { getHomeData } from "../../../../redux/modules/actions/HomeAction";

// i18next
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================

class Home extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      currentPage : 0,
    }
  }

  componentDidMount() {
    handleFetchData(this);
  }

  componentWillUnmount() {
    this.props.setToast();
  }

  render() {
    const { toaststate, t, HomeDataList, changePasswordMessage, totalPage } = this.props;
    let message;
    toaststate
      ? (message = {
          mode: "success",
          content: "login_successfully",
        })
      : (message = {});
    return (
      <Div className="templates-home-wrapper">
        <Div className="toast-wrapper">
          <Toast message={message} />
        </Div>
        {changePasswordMessage && changePasswordMessage.content && (
          <Div className="toast-wrapper">
            <Toast message={changePasswordMessage} />
          </Div>
        )}
        <Label className="templates-home-title">
          {t("maintenance_information")}
        </Label>
        <HomeList data={HomeDataList} />
        {totalPage > 0 && (
          <CustomPagination
            currentPage={this.state.currentPage}
            totalPage={totalPage}
            onPageChanged={(id) => handleOnPageChanged(id, this)}
          />
        )}
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================

/**
 * get Home data on load component
 * @param {*} context
 */
const handleFetchData = async (context) => {
  const { userToken } = context.props;
  await context.props.getHomeData(userToken,context.state.currentPage);
};

/**
 * pagination
 * @param {*} selectedPage
 * @param {*} context
 */
 const handleOnPageChanged = (selectedPage, context) => {
  context.setState({ currentPage: selectedPage }, () => {
    handleFetchData(context);
  });
};
//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================
const mapStateToProps = (state) => {
  return {
    toaststate: state.auth.toast,
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    HomeDataList: state.HomeDataList,
    totalPage: state.HomeDataList.totalPage,
    xray_message: state.xrayLibraryList.xray_message,
    changePasswordMessage: state.changePassword.message,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, { setToast, getHomeData })(
  withTranslation("translation")(Home)
);
