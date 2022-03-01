import React,{ createRef } from "react";
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

//components
import Div from "../../../presentational/atoms/Div";
import AddModal from "../../organisms/XrayAdd";
import XrayLibrary from "../../organisms/XrayLibrary";
import Toast from "../../../presentational/molecules/Toast";
import Table from "../../../presentational/atoms/Table";
import AddSearchWithCsv from "../../../container/organisms/AddSearchWithCsv";
import XrayPreview from "../../organisms/XrayPreview";
import CustomPagination from "../../../presentational/molecules/CustomPagination";
import SortButton from "../../../presentational/molecules/SortButton";

// redux
import {
  addXray,
  getXrays,
  deleteXray,
  resetXrayMessage,
} from "../../../../redux/modules/actions/XrayLibraryAction";
import {
  updateSort,
  getTableOrder,
} from "../../../../redux/modules/actions/UserAction";

//i18n
import { withTranslation } from "react-i18next";
import i18next from "i18next";

//css
import "./style.css";

let header = [
  "title",
  "Auscultation sound type",
  "Auscultation site",
  "normal/abnormal",
  "public/private",
  "updated_data_time",
  "Author",
];
//===================================================
// Component
//===================================================
class XrayLIbrary extends React.Component {
  _isMounted = false;
  constructor(props) {
    super(props);
    this.state = {
      search_keyword:"",
      isAddModalVisible: false,
      filteredData: {
        table_data: [],
        isLoading: true,
      },
      tableData: {
        table_data: [],
        isLoading: true,
      },
      isPreviewModalVisible: false,
      previewItem: null,
      currentPage: 0,
      filteredCsvData: {
        table_data: [],
        isLoading: true,
      },
      isDownloadingCsv: false,
      isSort: false,
    };
    this.searchRef = createRef();
  }

  componentDidMount() {
    this._isMounted = true;
    handleFetchData(this);
  }

  async componentDidUpdate() {
    if (this.props.addedItem) {
      await this.props.resetXrayMessage();
      handleFetchData(this);
    }
  }

  componentWillUnmount() {
    this._isMounted = false;
    this.props.resetXrayMessage();
  }

  render() {
    const {
      isAddModalVisible,
      filteredData,
      isPreviewModalVisible,
      previewItem,
      filteredCsvData,
      isDownloadingCsv,
      isSort,
      currentPage
    } = this.state;
    const { xray_message, userInfo, totalPage, t } = this.props;
    return (
      <Div className="template-XrayLibrary-wrapper">
        {xray_message && xray_message.content && (
          <Div className="toast-wrapper">
            <Toast message={xray_message} />
          </Div>
        )}

        <AddSearchWithCsv
          onChange={(event) => handleSearchChange(event.target.value, this)}
          onClick={(event) => handleShowAll(false, this)}
          data={filteredCsvData}
          filename="Xray Data"
          csv_mode="xray_csv"
          onAddNewClicked={() => handleAddModalVisible(this, true)}
          handleFetchCsvData={() => handleFetchCsvData(this)}
          isDownloading={isDownloadingCsv}
          setDownloadCsv={(isDownload) => setDownloadCsv(this, isDownload)}
          search_input_ref={this.searchRef}
        />

        <Table size="lg" className={isSort ? "withPaginationTable" : "withoutPaginationTable"}>
          <thead>
            <tr>
              <th style={{ width: "30px" }}></th>
              <th style={{ width: "60px" }}>ID</th>
              {header.map((header, index) => {
                let colSpan = "1";
                let centerText = "";
                return (
                  <th key={index} colSpan={colSpan} className={centerText}>
                    {t(`${header}`)}
                  </th>
                );
              })}
              <th colSpan="2" className="text-center">
                {t("actions")}
              </th>
            </tr>
          </thead>
          <XrayLibrary
            header={header}
            data={filteredData}
            deleteItem={(item) => deleteItem(item, this)}
            updateXrayOrder={(data, id) => sortXrayOrder(data, id, this)}
            setPreviewModalVisible={(item) =>
              handleOpenPreviewModal(this, item, true)
            }
            userInfo={userInfo}
          />
        </Table>
        {totalPage > 0 && !isSort && (
          <CustomPagination
            currentPage={currentPage}
            totalPage={totalPage}
            onPageChanged={(id) => handleOnPageChanged(id, this)}
          />
        )}
        <Div className="sortButton">
          <SortButton isSort={isSort} onClick={() => handleSortButton(this)} />
        </Div>
        {isAddModalVisible && (
          <AddModal
            isVisible={isAddModalVisible}
            onHideAddModal={() => handleAddModalVisible(this, false)}
            inputUserData={(event) => getInputUser(event, this)}
            addXray={(xray_data) => handleAddXrayData(xray_data, this)}
          />
        )}
        {isPreviewModalVisible && (
          <XrayPreview
            isVisible={isPreviewModalVisible}
            previewItem={previewItem}
            onHideXrayPreview={() => handleOpenPreviewModal(this, null, false)}
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
 * handle sort/unsort
 * @param {*} context
 */
const handleSortButton = (context) => {
  const { isSort } = context.state;
  context.searchRef.current.state.value="";
  context.setState({ isSort: !isSort, currentPage: 0 , search_keyword:""}, () =>
    handleFetchData(context)
  );
};

/**
 * handle show all
 * @param {*} context
 */
 const handleShowAll = (isSorting, context) => {
  const { isSort } = context.state;
  if (isSort != isSorting) {
    context.setState({ isSort: isSorting, currentPage: 0 }, () =>
      handleFetchData(context)
    );
  }
};

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
  handleOnPageChanged("all", context);
};

/**
 * edit Quiz
 * @param {*} data
 * @param {*} info
 * @param {*} context
 */
const sortXrayOrder = async (data, info, context) => {
  context.setState({
    filteredData: {
      table_data: data,
      isLoading: false,
    },
    tableData: {
      table_data: JSON.parse(JSON.stringify(data)),
      isLoading: false,
    },
  });
  await context.props.updateSort(
    data,
    { table: "X-ray Library", id: info, page: context.state.currentPage },
    context.props.userToken
  );
};

/**
 * get table data on load component
 * @param {*} context
 */
const handleFetchData = async (context) => {
  const { filteredData } = context.state;
  context.setState({
    filteredData: {
      table_data: filteredData.table_data,
      isLoading: true,
    },
  });
  const { userToken, userInfo } = context.props;
  const { currentPage, isSort, search_keyword } = context.state;
  await context.props.getXrays(userToken, isSort ? "all" : currentPage, search_keyword);
  const response = context.props.xrayLibraryList;
  await context.props.getTableOrder(context.props.userToken, {
    table: "X-ray Library",
    id: userInfo.id,
  });
  let obtainedData = [];
  let order = null;
  if (response) {
    await response.map((item) => {
      obtainedData = [
        ...obtainedData,
        {
          id: item.id,
          title: item.title,
          title_en: item.title_en,
          soundType: item.type,
          site: item.area,
          isNormal: item.is_normal,
          status: item.status,
          // supervisor: item.user && item.user.name,
          supervisor: "-",
          user_id: item.user && item.user.id,
          isPublic: item.is_public,
          updatedDate: item.updated_at,
          disp_order: item.disp_order,
          image_path: item.image_path,
          item: item,
          user: item.user && item.user.name,
          role: item.user && item.user.role,
          userInfo: userInfo,
          description : item.description,
          description_en : item.description_en,
          exam_groups: item.exam_groups,
          group_attribute: item.exam_groups.length != 0 ? 0 : 1,
          selected_exam_group:
            item.exam_groups.length != 0
              ? item.exam_groups.map((item) => ({
                  id: item.id,
                  text: item.name,
                }))
              : [],
          },
      ];
    });
  }
  let sortedXray = obtainedData;
  if (context.props.tableSort.tableSort != "no data") {
    order = JSON.parse(context.props.tableSort.tableSort);
    if (order) {
      obtainedData.map((item) => {
        let thisorder = order.find((order) => item.id == order.xray_id);
        if (thisorder) item.disp_order = thisorder.disp_order;
        else item.disp_order = "first";
      });
      sortedXray = obtainedData.sort(
        (a, b) => new Date(a.disp_order) - new Date(b.disp_order)
      );
    }
  }
  if (context._isMounted) {
    if (currentPage !== "all") {
      context.setState({
        filteredData: {
          table_data: sortedXray,
          isLoading: false,
        },
        tableData: {
          table_data: JSON.parse(JSON.stringify(obtainedData)),
          isLoading: false,
        },
      });
    } else {
      context.setState({
        filteredCsvData: JSON.parse(
          JSON.stringify({ table_data: sortedXray, isLoading: false })
        ),
        filteredData: {
          table_data: filteredData.table_data,
          isLoading: false,
        },
        isDownloadingCsv: true,
      });
    }
  }
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
};

/**
 * delete xray
 * @param {*} context
 */
const deleteItem = async (item, context) => {
  await context.props.deleteXray(item.id, context.props.userToken);
  if (context.props.xray_message && context.props.xray_message.mode === "error")
    return;
  handleFetchData(context);
};

/**
 * show/hide add modal
 * @param {*} context
 * @param {*} isVisible
 */
const handleAddModalVisible = async (context, isVisible) => {
  context.setState({
    isAddModalVisible: isVisible,
  });
};

/**
 * open preview modal
 * @param {*} context
 * @param {*} item
 * @param {*} isVisible
 */
const handleOpenPreviewModal = (context, item, isVisible) => {
  context.setState({ isPreviewModalVisible: isVisible, previewItem: item });
};

/**
 * add data
 * @param {*} context
 */
 const handleAddXrayData = async (data, context) => {
  const {addXray, userToken, userInfo } = context.props;
  await addXray(userToken, { ...data, user_id: userInfo.id });
  handleFetchData(context);
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
    userInfo: state.auth.userInfo && state.auth.userInfo.user,
    xrayLibraryList: state.xrayLibraryList.xrayList,
    xray_message: state.xrayLibraryList.xray_message,
    addedItem: state.xrayLibraryList.addedItem,
    totalPage: state.xrayLibraryList.totalPage,
    tableSort: state.tableSort,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, {
  addXray,
  getXrays,
  deleteXray,
  resetXrayMessage,
  updateSort,
  getTableOrder,
})(withTranslation("translation")(withRouter(XrayLIbrary)));
