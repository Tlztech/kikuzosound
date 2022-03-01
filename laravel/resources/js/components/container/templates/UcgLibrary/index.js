import React,{ createRef } from "react";

// libs
import { withRouter } from "react-router-dom";
import { connect } from "react-redux";

//components
import Div from "../../../presentational/atoms/Div";
import AddModal from "../../organisms/UcgLibraryAdd";
import UcgLibrary from "../../organisms/UcgLibrary";
import Toast from "../../../presentational/molecules/Toast";
import Table from "../../../presentational/atoms/Table";
import AddSearchWithCsv from "../../organisms/AddSearchWithCsv";
import UcgLibraryPreview from "../../organisms/UcgLibraryPreview";
import CustomPagination from "../../../presentational/molecules/CustomPagination";
import SortButton from "../../../presentational/molecules/SortButton";

// redux
import {
  getUcgLibrary,
  addUcgLibrary,
  deleteUcg,
  updateUcg,
  resetUcgMessage,
} from "../../../../redux/modules/actions/UcgLibraryAction";
import {
  updateSort,
  getTableOrder,
} from "../../../../redux/modules/actions/UserAction";
import { getLibraryUser } from "../../../../redux/modules/actions/LibraryUserAction";

//i18n
import { withTranslation } from "react-i18next";
import i18next from "i18next";

//css
import "./style.css";

const header = [
  "Title",
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
class UcgLIbrary extends React.Component {
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

  async componentDidMount() {
    await this.props.getLibraryUser(this.props.userToken);
    handleFetchData(this);
  }

  componentWillUnmount() {
    this.props.resetUcgMessage();
  }

  render() {
    const { ucgList, userInfo, t } = this.props;
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
    const { ucg_message, totalPage } = ucgList;
    return (
      <Div className="template-UcgLIbrary-wrapper">
        {ucg_message && ucg_message.content && (
          <Div className="toast-wrapper">
            <Toast message={ucg_message} />
          </Div>
        )}

        <AddSearchWithCsv
          onChange={(event) => handleSearchChange(event.target.value, this)}
          onClick={(event) => handleShowAll(false, this)}
          data={filteredCsvData}
          onAddNewClicked={() => handleAddModalVisible(this, true)}
          csv_mode="ucg_csv"
          filename="Ucg library Data"
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
              <th colSpan={2} className="text-center">
                {t("actions")}
              </th>
            </tr>
          </thead>
          <UcgLibrary
            header={header}
            t={t}
            data={filteredData}
            deleteItem={(item, index) => deleteItem(index, item, this)}
            updateUcgData={(item, index) => updateItem(index, item, this)}
            updateUcgOrder={(data, id) => sortUcgOrder(data, id, this)}
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
          addUcgData={(ucg_data) => handleAddUcgData(ucg_data, this)}
        />
        {isPreviewModalVisible && (
          <UcgLibraryPreview
            isVisible={isPreviewModalVisible}
            previewItem={previewItem}
            onHideUcgPreview={() => handleOpenPreviewModal(this, null, false)}
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
const sortUcgOrder = async (data, info, context) => {
  context.setState({
    filteredData: { table_data: data, isLoading: false },
    tableData: {
      table_data: JSON.parse(JSON.stringify(data)),
      isLoading: false,
    },
  });
  await context.props.updateSort(
    data,
    { table: "UCG Library", id: info, page: context.state.currentPage },
    context.props.userToken
  );
};

/**
 * add data
 * @param {*} context
 */
const handleAddUcgData = async (data, context) => {
  const { addUcgLibrary, userToken, userInfo } = context.props;
  await addUcgLibrary(userToken, { ...data, user_id: userInfo.id });
  handleFetchData(context);
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
  const { isSort, currentPage, search_keyword } = context.state;
  await context.props.getTableOrder(context.props.userToken, {
    table: "UCG Library",
    id: userInfo.id,
  });
  await context.props.getUcgLibrary(userToken, isSort ? "all" : currentPage, search_keyword);
  let ucglist = null;

  let { ucgList } = await context.props.ucgList;

  ucglist =
    ucgList &&
    ucgList.map((item) => {
      return {
        ID: item.id,
        title: item.title,
        soundtype: getItemType(item.type),
        area: item.area ? item.area : item.area_en ? item.title_en : "-",
        normal_abnormal: item.is_normal ? "normal" : "abnormal",
        status: getStatus(item.status),
        user: item.user && item.user.name,
        // user: "-",
        user_id: item.user ? item.user.id : "-",
        public_private: !item.is_public ? "private" : "public",
        updated_at: item.updated_at,
        ucg_explanation: item.description,
        ucg_explanation_en: item.description_en,
        title_en: item.title_en,
        video_file: item.video_path,
        supervisor_comment: item.supervisor_comment,
        role: item.user && item.user.role,
        group_attribute: item.exam_groups.length != 0 ? 0 : 1,
        disp_order: item.disp_order,
        selected_exam_group:
          item.exam_groups != 0
            ? item.exam_groups.map((item) => ({
                id: item.id,
                text: item.name,
              }))
            : [],
        userInfo: userInfo,
      };
    });
  let order = [];
  let sortedUcg = ucglist;
  if (context.props.tableSort.tableSort != "no data") {
    order = JSON.parse(context.props.tableSort.tableSort);
    if (order) {
      ucglist.map((item) => {
        let thisorder = order.find((order) => item.ID == order.ucg_id);
        item.disp_order = thisorder && thisorder.disp_order;
      });
      sortedUcg = ucglist.sort(
        (a, b) => new Date(a.disp_order) - new Date(b.disp_order)
      );
    }
  }
  // const param = window.location.search;
  // let drag = param == "?reorder" ? true : context.state.filteredData.dragRow;
  // filteredData.table_data = sortedUcg;
  // filteredData.dragRow = drag;
  // filteredData.isLoading = false;
  if (currentPage !== "all") {
    context.setState({
      filteredData: {
        table_data: sortedUcg,
        isLoading: false,
      },
      tableData: {
        table_data: JSON.parse(JSON.stringify(ucglist)),
        isLoading: false,
      },
    });
  } else {
    context.setState({
      filteredCsvData: JSON.parse(
        JSON.stringify({ table_data: sortedUcg, isLoading: false })
      ),
      filteredData: {
        table_data: filteredData.table_data,
        isLoading: false,
      },
      isDownloadingCsv: true,
    });
  }
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
 * handle delete item
 * @param {index} index
 * @param {item} item
 * @param {*} context
 */
const deleteItem = async (index, item, context) => {
  await context.props.deleteUcg(item.ID, context.props.userToken);
  if (context.props.ucgList.ucg_message.mode === "error") return;
  const { filteredData } = context.state;
  filteredData.table_data.splice(index + 1, 1);
  context.setState({ filteredData, tableData: filteredData });
  handleFetchData(context);
};

/**
 * handle update item
 * @param {index} index
 * @param {item} item
 * @param {*} context
 */
const updateItem = async (index, item, context) => {
  const { userToken, userInfo } = context.props;
  await context.props.updateUcg(
    index,
    { ...item, user_id: userInfo.id },
    userToken
  );
  if (context.props.ucgList.ucg_message.mode === "error") return;
  const { filteredData } = context.state;
  // filteredData.table_data.splice(index + 1, 1);
  context.setState({ filteredData, tableData: filteredData });
  handleFetchData(context);
};

const getStatus = (item_type) => {
  switch (item_type) {
    case 0:
      return "Under supervision";
    case 1:
      return "Supervised";
    case 2:
      return "Now open";
    case 3:
      return "Now open (New)";
    default:
      return "unspecified";
  }
};

const getItemType = (item_no) => {
  switch (item_no) {
    case 1:
      return "Lung Sound";
    case 2:
      return "Heart Sound";
    case 3:
      return "Intestinal  Sound";
    case 9:
      return "Other";
    default:
      return "unspecified";
  }
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
    ucgList: state.ucgLibrary,
    libraryUserList: state.LibraryUserList,
    tableSort: state.tableSort,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, {
  getLibraryUser,
  getUcgLibrary,
  addUcgLibrary,
  deleteUcg,
  updateUcg,
  // useHistory,
  resetUcgMessage,
  updateSort,
  getTableOrder,
})(withTranslation("translation")(withRouter(UcgLIbrary)));
