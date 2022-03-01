import React,{ createRef } from "react";
import { connect } from "react-redux";

//components
import Div from "../../../presentational/atoms/Div";
import UserBreadTable from "../../organisms/BreadTable";
import SearchInput from "../../../presentational/molecules/SearchInput";
import DownloadOptions from "../../../presentational/molecules/DownloadOptions";
import ActiveDisabledButton from "../../../presentational/molecules/ActiveDisabledButton";
import Toast from "../../../presentational/molecules/Toast";
import CustomPagination from "../../../presentational/molecules/CustomPagination";

// redux
import {
  getAllUsers,
  setUserDisabled,
} from "../../../../redux/modules/actions/UserAction";

//css
import "./style.css";

//i18n
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class UserBread extends React.Component {
  _isMounted = false;
  constructor(props) {
    super(props);
    this.state = {
      isEditModalVisible: false,
      search_keyword:"",
      isActive: true,
      filteredData: {
        table_data: [],
        isLoading: true,
      },
      tableData: {
        table_data: [],
        isLoading: true,
      },
      totalData:{
        table_data: []
      },
      editItem: null,
      isEnabled: false,
      currentPage: 0,
      currentPageFix : 0,
      filteredCsvData: {
        table_data: [],
        isLoading: true,
      },
      isDownloadingCsv: false,
    };
    this.searchRef = createRef();
  }

  componentDidMount() {
    this._isMounted = true;
    handleFetchData(this);
  }

  componentWillUnmount() {
    this._isMounted = false;
  }

  render() {
    const {
      isActive,
      isEditModalVisible,
      editItem,
      isEnabled,
      filteredData,
      filteredCsvData,
      isDownloadingCsv,
      currentPage
    } = this.state;
    const { t, userMessage, usersList } = this.props;
    const { totalPage } = usersList;
    return (
      <Div className="template-UserBread-wrapper">
        {userMessage && userMessage.content && (
          <Div className="toast-wrapper">
            <Toast message={userMessage} />
          </Div>
        )}
        <Div className="userbread-top">
          {this.props.authUser.user.role == 101 && (
            <ActiveDisabledButton
              isActive={isActive}
              onDisabledClicked={() => handleSetActiveDisabled(this, false)}
              onActiveClicked={() => handleSetActiveDisabled(this, true)}
            />
          )}

          <Div className="SearchWithDownloadOption">
            <SearchInput
            search_input_ref={this.searchRef}
              onChange={(event) => handleSearchChange(event.target.value, this)}
            />
            <DownloadOptions
              data={filteredCsvData}
              filename="User Data"
              csv_mode="user_data"
              handleFetchCsvData={() => handleFetchCsvData(this)}
              isDownloading={isDownloadingCsv}
              setDownloadCsv={(isDownload) => setDownloadCsv(this, isDownload)}
            />
          </Div>
        </Div>
        <UserBreadTable
          isActive={isActive}
          data={filteredData}
          no_action={true}
          update={() => handleUpdate()}
        />
        {isActive && totalPage > 0 && (
          <CustomPagination
            totalPage={totalPage}
            onPageChanged={(id) => handlePaginationChange(id, this)}
          />
        )}
        {!isActive && totalPage > 0 && (
          <CustomPagination
            currentPage={currentPage}
            totalPage={totalPage}
            onPageChanged={(id) => handlePaginationChange(id, this)}
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
 * set download csv false
 * @param {*} context
 * @param {*} isDownload
 */
const setDownloadCsv = (context, isDownload) => {
  context.setState({ isDownloadingCsv: isDownload });
};

/**
 * Fetch csv data
 * @param {*} context
 */
const handleFetchCsvData = (context) => {
  context.setState({
    isDownloadingCsv: true,
  });
  handleOnPageChanged("all", context);
};


const handlePaginationChange = ( selectedPage, context ) => {
  context.setState({ currentPageFix: selectedPage }, () => {
    handleOnPageChanged(selectedPage, context);
  });
}

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

/**
 * get table data on load component
 * @param {*} context
 */
const handleFetchData = async (context) => {
  const { filteredData, search_keyword } = context.state;
  context.setState({
    filteredData: {
      table_data: filteredData.table_data,
      isLoading: true,
    },
  });
  const { currentPage, isActive } = context.state;
  await context.props.getAllUsers(
    context.props.userToken,
    currentPage,
    isActive ? 1 : 0,
    search_keyword
  );
  let users = null;
  let { usersList } = context.props.usersList;
  users = usersList.map((item) => {
    let created_at =
      item.created_at == "-0001-11-30 00:00:00"
        ? "Not Available"
        : item.created_at.substring(0, 10);
    return {
      ID: item.id,
      UserName: item.name,
      UserID: item.user,
      MailAddress: item.email,
      University_name: item.university_name,
      CreatedDate: created_at,
      Enabled: item.enabled === 1 ? "Enabled" : "Disabled",
      DisabledDate: item.onetime_key
        ? item.onetime_key.updated_at
        : item.disabled_date,
    };
  });
  if (context._isMounted) {
    if (currentPage !== "all") {
      context.setState({
        tableData: {
          table_data: users,
          isLoading: false,
        },
        filteredData: {
          table_data: users,
          isLoading: false,
        },
      });
    } else {
      context.setState({
        filteredCsvData: JSON.parse(
          JSON.stringify({ table_data: users, isLoading: false })
        ),
        totalData:{
          table_data: users
        },
        filteredData: {
          table_data: filteredData.table_data,
          isLoading: false,
        },
      });
    }
  }
};

/**
 * update child on search click
 */
const handleUpdate = () => {};

/**
 * handle search input on change
 * @param {*} target
 * @param {*} context
 */
const handleSearchChange = (target, context) => {
  const search_keyword = target.trim().toLowerCase();
  context.setState({ currentPage: 0 ,search_keyword:search_keyword}, () => {
    handleFetchData(context);
  });
  // handleOnPageChanged("all", context);
  // const targetText = target.trim().toLowerCase();
  // const { filteredData, totalData } = context.state;
  // filteredData.table_data = totalData.table_data.filter(
  //   (el) =>
  //     el.UserName.toLowerCase().includes(targetText) ||
  //     el.UserID.toLowerCase().includes(targetText) ||
  //     el.ID.toString().includes(targetText) ||
  //     el.MailAddress.toLowerCase().includes(targetText)
  // );
  // context.setState({
  //   filteredData,
  // });
  // if(target.length == 0){
  //   context.setState({
  //     currentPage:context.state.currentPageFix,
  //   });
  //   // console.log(this.props.selectedPage);
  //   handleFetchData(context);
  // }
};

/**
 * Set active or disabled button
 * @param {*} context
 * @param {*} isActive
 */
const handleSetActiveDisabled = (context, isActive) => {
  context.setState(
    {
      isActive: isActive,
      currentPage: 0,
      filteredData: { table_data: [], isLoading: true },
    },
    () => handleFetchData(context)
  );
};

/**
 * show/hide edit modal
 * @param {*} context
 * @param {*} value
 */
const handleEditModalVisible = (context, isVisible, value) => {
  const { isActive } = context.state;
  context.setState({
    isEditModalVisible: isVisible,
    editItem: value,
    isEnabled: isActive,
  });
};

// /**
//  * get selected radio item
//  * @param {*} e
//  * @param {*} context
//  */
// const handleRadioClicked = (e, context) => {
//   const { isEnabled } = context.state;
//   if (isEnabled !== e.target.value) {
//     context.setState({ isEnabled: e.target.value });
//   }
// };

/**
 * Edit user active / disabled status
 * @param {*} context
 */
const handleEnableDisableUser = async (context) => {
  const { isEnabled, editItem } = context.state;
  const { setUserDisabled, userToken } = context.props;
  const initialEnabled = editItem.Enabled === "Enabled" ? true : false;
  if (initialEnabled !== isEnabled) {
    await setUserDisabled(editItem.ID, isEnabled, userToken);
    if (context.props.isUserDisableSuccess) {
      handleEditModalVisible(context, false, null);
      context.setState({ filteredData: { table_data: [], isLoading: true } });
      handleFetchData(context);
    }
  } else {
    handleEditModalVisible(context, false, null);
  }
};
//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================
const mapStateToProps = (state) => {
  return {
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    usersList: state.userManagement,
    userMessage: state.userManagement.userMessage,
    isUserDisableSuccess: state.userManagement.isUserDisableSuccess,
    authUser: state.auth.userInfo,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, { getAllUsers, setUserDisabled })(
  withTranslation("translation")(UserBread)
);
