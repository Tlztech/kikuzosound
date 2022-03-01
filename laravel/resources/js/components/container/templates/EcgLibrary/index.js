import React,{ createRef } from "react";
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

//components
import Div from "../../../presentational/atoms/Div";
import AddModal from "../../organisms/EcgLibraryAdd";
import EcgLibraryOrganism from "../../organisms/EcgLibrary";
import SortButton from "../../../presentational/molecules/SortButton";
import Toast from "../../../presentational/molecules/Toast";
import Table from "../../../presentational/atoms/Table";
import AddSearchWithCsv from "../../organisms/AddSearchWithCsv";
import EcgPreview from "../../organisms/EcgPreview";
import CustomPagination from "../../../presentational/molecules/CustomPagination";

//redux
import { getLibraryUser } from "../../../../redux/modules/actions/LibraryUserAction";
import {
  getEcg,
  addEcg,
  deleteEcg,
  updateEcg,
  resetEcgMessage,
} from "../../../../redux/modules/actions/EcgLibraryAction";
import {
  updateSort,
  getTableOrder,
} from "../../../../redux/modules/actions/UserAction";

//css
import "./style.css";

// i18next
import { withTranslation } from "react-i18next";
import i18next from "i18next";

let header = [
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
class EcgLibrary extends React.Component {
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
      isSort: false,
      isDownloadingCsv: false,
    };
    this.searchRef = createRef();
  }

  async componentDidMount() {
    await this.props.getLibraryUser(this.props.userToken);
    handleFetchData(this);
  }

  componentWillUnmount() {
    this.props.resetEcgMessage();
  }

  render() {
    const {
      isAddModalVisible,
      isPreviewModalVisible,
      previewItem,
      filteredData,
      filteredCsvData,
      isDownloadingCsv,
      isSort,
      currentPage
    } = this.state;
    const { ecg_message, totalPage } = this.props.ecgList;
    const { userInfo, t } = this.props;
    return (
      <Div className="template-EcgLibrary-wrapper">
        {ecg_message && ecg_message.content && (
          <Div className="toast-wrapper">
            <Toast message={ecg_message} />
          </Div>
        )}

        <AddSearchWithCsv
          onChange={(event) => handleSearchChange(event.target.value, this)}
          onClick={(event) => handleShowAll(false, this)}
          data={filteredCsvData}
          csv_mode="ecg_csv"
          filename="Ecg library Data"
          onAddNewClicked={() => handleAddModalVisible(this, true)}
          handleFetchCsvData={() => handleFetchCsvData(this)}
          isDownloading={isDownloadingCsv}
          setDownloadCsv={(isDownload) => setDownloadCsv(this, isDownload)}
          search_input_ref={this.searchRef}
        />

        <Table
          size="lg"
          className={isSort ? "withPaginationTable" : "withoutPaginationTable"}
        >
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
          <EcgLibraryOrganism
            t={t}
            data={filteredData}
            deleteItem={(item, index) => deleteItem(index, item, this)}
            updateEcgData={(item, index) => updateItem(index, item, this)}
            updateEcgOrder={(data, id) => sortEcgOrder(data, id, this)}
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
          addEcgData={(ecg_data) => addEcgData(ecg_data, this)}
        />

        {isPreviewModalVisible && (
          <EcgPreview
            isVisible={isPreviewModalVisible}
            previewItem={previewItem}
            onHideEcgPreview={() => handleOpenPreviewModal(this, null, false)}
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
 * edit Quiz
 * @param {*} data
 * @param {*} info
 * @param {*} context
 */
const sortEcgOrder = async (data, info, context) => {
  context.setState({
    filteredData: { table_data: data, isLoading: false },
    tableData: {
      table_data: JSON.parse(JSON.stringify(data)),
      isLoading: false,
    },
  });
  await context.props.updateSort(
    data,
    { table: "ECG Library", id: info, page: context.state.currentPage },
    context.props.userToken
  );
};

/**
 * add data
 * @param {*} context
 */
const addEcgData = async (data, context) => {
  const { userInfo, userToken } = context.props;
  await context.props.addEcg({ ...data, user_id: userInfo.id }, userToken);
  handleFetchData(context);
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
  const { userInfo, userToken } = context.props;
  const { isSort, currentPage, search_keyword } = context.state;
  const displayPage = isSort ? "all" : currentPage;
  await context.props.getEcg(userToken, displayPage, search_keyword);
  let ecglist = null;
  await context.props.getTableOrder(userToken, {
    table: "ECG Library",
    id: userInfo.id,
  });

  let { ecg_list } = await context.props.ecgList;
  ecglist = ecg_list.map((item) => {
    return {
      ID: item.id,
      title: item.title,
      soundtype: getItemType(item.type),
      area: item.area ? item.area : item.area_en ? item.title_en : "-",
      normal_abnormal: item.is_normal ? "normal" : "abnormal",
      status: item.status,
      user: item.user && item.user.name,
      role: item.user && item.user.role,
      // user: "-",
      user_id: item.user ? item.user.id : "-",
      public_private: !item.is_public ? "private" : "public",
      updated_at: item.updated_at,
      ecg_explanation: item.description,
      ecg_explanation_en: item.description_en,
      title_en: item.title_en,
      image_path: item.image_path,
      exam_groups: item.exam_groups,
      group_attribute: item.exam_groups.length != 0 ? 0 : 1,
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
  let sortedEcg = ecglist;
  if (context.props.tableSort.tableSort != "no data") {
    order = JSON.parse(context.props.tableSort.tableSort);
    if (order) {
      ecglist.map((item) => {
        let thisorder = order.find((order) => item.ID == order.ecg_id);
        item.disp_order = thisorder && thisorder.disp_order;
      });
      sortedEcg = ecglist.sort(
        (a, b) => new Date(a.disp_order) - new Date(b.disp_order)
      );
    }
  }
  // const param = window.location.search;
  // let drag = param == "?reorder" ? true : context.state.filteredData.dragRow;
  // filteredData.dragRow = drag;
  // context.setState({
  //   tableData: { table_data: sortedEcg },
  //   isLoading: false,
  // });
  // filteredData.table_data = sortedEcg;
  // filteredData.isLoading = false;
  // context.setState({ filteredData });

  if (currentPage !== "all") {
    context.setState({
      filteredData: { table_data: sortedEcg, isLoading: false },
      tableData: {
        table_data: JSON.parse(JSON.stringify(ecglist)),
        isLoading: false,
      },
    });
  } else {
    context.setState({
      filteredCsvData: JSON.parse(
        JSON.stringify({
          table_data: sortedEcg,
          isLoading: false,
        })
      ),
      filteredData: { table_data: filteredData.table_data, isLoading: false },
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
  const { filteredData, tableData } = context.state;
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
  await context.props.deleteEcg(item.ID, context.props.userToken);
  if (context.props.ecgList.ecg_message.mode === "error") return;
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
  const { userInfo, userToken } = context.props;
  await context.props.updateEcg(
    index,
    { ...item, user_id: userInfo.id },
    userToken
  );
  if (context.props.ecgList.ecg_message.mode === "error") return;
  const { filteredData } = context.state;
  context.setState({ filteredData, tableData: filteredData });
  handleFetchData(context);
};

/**
 * get item type
 * @param {*} item_no
 */
const getItemType = (item_no) => {
  switch (item_no) {
    case 1:
      return "lung_sound";
    case 2:
      return "heart_sound";
    case 3:
      return "intestinal_sound";
    case 9:
      return "other";
    default:
      return "-";
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
    libraryUserList: state.LibraryUserList,
    ecgList: state.ecgLibList,
    tableSort: state.tableSort,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, {
  getLibraryUser,
  getEcg,
  addEcg,
  updateEcg,
  deleteEcg,
  resetEcgMessage,
  updateSort,
  getTableOrder,
})(withTranslation("translation")(withRouter(EcgLibrary)));
