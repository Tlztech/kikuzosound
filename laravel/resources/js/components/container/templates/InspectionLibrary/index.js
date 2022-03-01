import React,{ createRef } from "react";
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

//components
import Div from "../../../presentational/atoms/Div";
import AddModal from "../../organisms/InspectionLibraryAdd";
import Table from "../../../presentational/atoms/Table";
import Toast from "../../../presentational/molecules/Toast";
import SortButton from "../../../presentational/molecules/SortButton";
import AddSearchWithCsv from "../../organisms/AddSearchWithCsv";
import InspectionPreview from "../../organisms/InspectionPreview";
import InspectionLibrary from "../../organisms/InspectionLibrary";
import CustomPagination from "../../../presentational/molecules/CustomPagination";

// redux
import {
  getInspectionLibrary,
  deleteInspectionLib,
  resetInspectionMessage,
  resetInspectionSuccess,
} from "../../../../redux/modules/actions/InspectionLibraryAction";
import {
  updateSort,
  getTableOrder,
} from "../../../../redux/modules/actions/UserAction";

//css
import "./style.css";

// i18next
import { withTranslation } from "react-i18next";
import i18next from "i18next";

const header = [
  "title",
  "Auscultation sound type",
  "Auscultation site",
  "normal/abnormal",
  "public/private",
  "Update Date",
  "Author",
];
//===================================================
// Component
//===================================================
class InspectionLIbrary extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      search_keyword:"",
      isAddModalVisible: false,
      filteredData: { table_data: [], isLoading: true, dragRow: false },
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
    handleFetchData(this);
  }

  async componentDidUpdate() {
    if (this.props.addedItem) {
      await this.props.resetInspectionSuccess();
      handleFetchData(this);
    }
  }

  componentWillUnmount() {
    this.props.resetInspectionMessage();
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
    const { inspection_message, userInfo, t, totalPage } = this.props;
    return (
      <Div className="template-InspectionLibrary-wrapper">
        {inspection_message && inspection_message.content && (
          <Div className="toast-wrapper">
            <Toast message={inspection_message} />
          </Div>
        )}

        <AddSearchWithCsv
          onChange={(event) => handleSearchChange(event.target.value, this)}
          onClick={(event) => handleShowAll(false, this)}
          data={filteredCsvData}
          filename="Inspection library Data"
          csv_mode="inspection_csv"
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
                return <th key={index}>{t(`${header}`)}</th>;
              })}
              <th colSpan="2" className="text-center">
                {t("actions")}
              </th>
              {filteredData.dragRow && <th style={{ width: "40px" }}></th>}
            </tr>
          </thead>
          <InspectionLibrary
            t={t}
            data={filteredData}
            deleteItem={(item) => deleteItem(item, this)}
            updateInspectionOrder={(data, id) =>
              sortInspectionOrder(data, id, this)
            }
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
        <AddModal
          isVisible={isAddModalVisible}
          userList={this.props.libraryUserList}
          onHideAddModal={() => handleAddModalVisible(this, false)}
          inputUserData={(event) => getInputUser(event, this)}
        />
        {isPreviewModalVisible && (
          <InspectionPreview
            isVisible={isPreviewModalVisible}
            previewItem={previewItem}
            onHideInspectionPreview={() =>
              handleOpenPreviewModal(this, null, false)
            }
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
const sortInspectionOrder = async (data, info, context) => {
  context.setState({
    filteredData: { table_data: data, isLoading: false },
    tableData: {
      table_data: JSON.parse(JSON.stringify(data)),
      isLoading: false,
    },
  });
  await context.props.updateSort(
    data,
    { table: "Inspection Library", id: info, page: context.state.currentPage },
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
  const { currentPage, isSort, search_keyword  } = context.state;
  await context.props.getInspectionLibrary(
    userToken,
    isSort ? "all" : currentPage,
    search_keyword
  );
  const response = context.props.inspectionLibraryList;
  await context.props.getTableOrder(context.props.userToken, {
    table: "Inspection Library",
    id: userInfo.id,
  });
  let obtainedData = [];
  await response.map((item) => {
    obtainedData = [
      ...obtainedData,
      {
        id: item.id,
        is_video: item.is_video_show,
        title: item.title,
        title_en: item.title_en,
        soundType: item.type,
        site: item.area,
        isNormal: item.is_normal,
        status: item.status,
        // supervisor: item.user && item.user.name,
        supervisor: "-",
        isPublic: item.is_public,
        updatedDate: item.updated_at,
        disp_order: item.disp_order,
        item: item,
        description : item.description,
        description_en : item.description_en,
        user: item.user && item.user.name,
        role: item.user && item.user.role,
        userInfo: userInfo,
        user_id: item.user_id,
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
  let order = [];
  let sortedInspection = obtainedData;
  if (context.props.tableSort.tableSort != "no data") {
    order = JSON.parse(context.props.tableSort.tableSort);
    if (order) {
      obtainedData.map((item) => {
        let thisorder = order.find((order) => item.id == order.inspection_id);
        if (thisorder) item.disp_order = thisorder.disp_order;
        else item.disp_order = "first";
      });
      sortedInspection = obtainedData.sort(
        (a, b) => new Date(a.disp_order) - new Date(b.disp_order)
      );
    }
  }
  // const param = window.location.search;
  // let drag = param == "?reorder" ? true : context.state.filteredData.dragRow;
  if (currentPage !== "all") {
    context.setState({
      filteredData: { table_data: sortedInspection, isLoading: false },
      tableData: {
        table_data: JSON.parse(JSON.stringify(obtainedData)),
        isLoading: false,
      },
    });
  } else {
    context.setState({
      filteredCsvData: JSON.parse(
        JSON.stringify({
          table_data: sortedInspection,
          isLoading: false,
        })
      ),
      filteredData: { table_data: filteredData.table_data, isLoading: false },
      isDownloadingCsv: true,
    });
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
  const { filteredData, tableData } = context.state;
  const search_keyword = target.trim().toLowerCase();
  context.setState({ currentPage: 0 ,search_keyword:search_keyword}, () => {
    handleFetchData(context);
  });
};

/**
 * show/hide add modal
 * @param {*} context
 * @param {*} isVisible
 */
const handleAddModalVisible = (context, isVisible) => {
  context.setState({ isAddModalVisible: isVisible });
};

/**
 * delete inspection library
 * @param {*} context
 */
const deleteItem = async (item, context) => {
  await context.props.deleteInspectionLib(item.id, context.props.userToken);
  if (
    context.props.inspection_message &&
    context.props.inspection_message.mode === "error"
  )
    return;
  handleFetchData(context);
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
    inspectionLibraryList: state.inspectionLibList.inspectionLibraryList,
    totalPage: state.inspectionLibList.totalPage,
    libraryUserList: state.LibraryUserList,
    addedItem: state.inspectionLibList.addedItem,
    inspection_message: state.inspectionLibList.inspection_message,
    tableSort: state.tableSort,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, {
  getInspectionLibrary,
  deleteInspectionLib,
  resetInspectionMessage,
  resetInspectionSuccess,
  updateSort,
  getTableOrder,
})(withTranslation("translation")(withRouter(InspectionLIbrary)));
