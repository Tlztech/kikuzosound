import React,{ createRef } from "react";
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

//components
import Div from "../../../presentational/atoms/Div";
import AddModal from "../../organisms/PalpationLibraryAdd";
import PalpationLibraryOrganism from "../../organisms/PalpationLibrary";
import Toast from "../../../presentational/molecules/Toast";
import Table from "../../../presentational/atoms/Table";
import { getLibraryUser } from "../../../../redux/modules/actions/LibraryUserAction";
import AddSearchWithCsv from "../../organisms/AddSearchWithCsv";
import PalpationPreview from "../../organisms/PalpationPreview";
import CustomPagination from "../../../presentational/molecules/CustomPagination";
import SortButton from "../../../presentational/molecules/SortButton";

//redux
import {
  getPalpation,
  addPalpation,
  updatePalpation,
  deletePalpation,
  resetPalpationMessage,
} from "../../../../redux/modules/actions/PalpationLibraryAction";
import { updateSort } from "../../../../redux/modules/actions/UserAction";
import { getTableOrder } from "../../../../redux/modules/actions/UserAction";

//i18
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
class PalpationLibrary extends React.Component {
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
    this.props.resetPalpationMessage();
  }

  render() {
    const {
      isAddModalVisible,
      filteredData,
      filteredCsvData,
      isPreviewModalVisible,
      previewItem,
      isDownloadingCsv,
      isSort,
      currentPage
    } = this.state;
    const { userInfo, t, palpationList } = this.props;
    const { palpation_message, totalPage } = palpationList;
    return (
      <Div className="template-PalpationLibrary-wrapper">
        {palpation_message && palpation_message.content && (
          <Div className="toast-wrapper">
            <Toast message={palpation_message} />
          </Div>
        )}

        <AddSearchWithCsv
          onChange={(event) => handleSearchChange(event.target.value, this)}
          onClick={(event) => handleShowAll(false, this)}
          data={filteredCsvData}
          onAddNewClicked={() => handleAddModalVisible(this, true)}
          csv_mode="palpation_csv"
          filename="Palpation library Data"
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
                return <th key={index}>{t(`${header}`)}</th>;
              })}
              <th colSpan="2" className="text-center">
                {t("actions")}
              </th>
            </tr>
          </thead>
          <PalpationLibraryOrganism
            data={filteredData}
            deleteItem={(item, index) => deleteItem(index, item, this)}
            editPalpationData={(item, index) => updateItem(index, item, this)}
            updatePalpationOrder={(data, id) =>
              sortPalpationOrder(data, id, this)
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
          addPalpationData={(palpation_data) =>
            addPalpationData(palpation_data, this)
          }
        />
        {isPreviewModalVisible && (
          <PalpationPreview
            isVisible={isPreviewModalVisible}
            previewItem={previewItem}
            onHidePalpationPreview={() =>
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
const sortPalpationOrder = async (data, info, context) => {
  context.setState({
    filteredData: { table_data: data, isLoading: false },
    tableData: {
      table_data: JSON.parse(JSON.stringify(data)),
      isLoading: false,
    },
  });
  await context.props.updateSort(
    data,
    { table: "Palpation Library", id: info, page: context.state.currentPage },
    context.props.userToken
  );
};

/**
 * get table data on load component
 * @param {*} data
 * @param {*} context
 */
const addPalpationData = async (data, context) => {
  const { userInfo, userToken } = context.props;
  await context.props.addPalpation(
    { ...data, user_id: userInfo.id },
    userToken
  );
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
  const { currentPage, isSort, search_keyword  } = context.state;
  await context.props.getPalpation(userToken, isSort ? "all" : currentPage, search_keyword);
  let palpationlist = null;
  await context.props.getTableOrder(userToken, {
    table: "Palpation Library",
    id: userInfo.id,
  });

  let { palpation_list } = await context.props.palpationList;
  palpationlist = palpation_list.map((item) => {
    return {
      ID: item.id,
      is_video: item.is_video_show,
      title: item.title,
      soundtype: getItemType(item.type),
      area: item.area ? item.area : item.area_en ? item.title_en : "-",
      normal_abnormal: item.is_normal ? "normal" : "abnormal",
      status: item.status,
      user: item.user ? item.user.name : "-",
      role: item.user ? item.user.role: "-",
      // user: "-",
      user_id: item.user ? item.user.id : "-",
      public_private: !item.is_public ? "private" : "public",
      updated_at: item.updated_at,
      description: item.description,
      description_en: item.description_en,
      title_en: item.title_en,
      sound_file: item.sound_path,
      video_file: item.video_path,
      supervisor_comment: item.supervisor_comment,
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
  let sortedPalpation = palpationlist;
  if (context.props.tableSort.tableSort != "no data") {
    order = JSON.parse(context.props.tableSort.tableSort);
    if (order) {
      palpationlist.map((item) => {
        let thisorder = order.find((order) => item.ID == order.palpation_id);
        item.disp_order = thisorder && thisorder.disp_order;
      });
      sortedPalpation = palpationlist.sort(
        (a, b) => new Date(a.disp_order) - new Date(b.disp_order)
      );
    }
  }

  if (currentPage !== "all") {
    context.setState({
      filteredData: { table_data: sortedPalpation, isLoading: false },
      tableData: {
        table_data: JSON.parse(JSON.stringify(palpationlist)),
        isLoading: false,
      },
    });
  } else {
    context.setState({
      filteredCsvData: JSON.parse(
        JSON.stringify({
          table_data: sortedPalpation,
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
 * delete item
 * @param {*} index
 * @param {*} item
 * @param {*} context
 */
const deleteItem = async (index, item, context) => {
  await context.props.deletePalpation(item.ID, context.props.userToken);
  if (context.props.palpationList.palpation_message.mode === "error") return;
  const { filteredData } = context.state;
  filteredData.table_data.splice(index + 1, 1);
  context.setState({ filteredData, tableData: filteredData });
  handleFetchData(context);
};

/**
 * update / edit item
 * @param {*} index
 * @param {*} item
 * @param {*} context
 */
const updateItem = async (index, item, context) => {
  const { userInfo, userToken } = context.props;
  await context.props.updatePalpation(
    { ...item, user_id: userInfo.id },
    userToken,
    index
  );
  if (context.props.palpationList.palpation_message.mode === "error") return;
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
    libraryUserList: state.LibraryUserList,
    palpationList: state.palpationLibList,
    tableSort: state.tableSort,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, {
  getLibraryUser,
  getPalpation,
  addPalpation,
  updatePalpation,
  deletePalpation,
  resetPalpationMessage,
  updateSort,
  getTableOrder,
})(withTranslation("translation")(withRouter(PalpationLibrary)));
