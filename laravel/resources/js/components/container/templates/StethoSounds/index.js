import React,{ createRef } from "react";
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

//components
import Div from "../../../presentational/atoms/Div";
import AddModal from "../../organisms/StethoLibraryAdd";
import StethoSoundsBread from "../../organisms/StethoSounds";
import Toast from "../../../presentational/molecules/Toast";
import SortButton from "../../../presentational/molecules/SortButton";
import AddSearchWithCsv from "../../organisms/AddSearchWithCsv";
import StethoPreview from "../../organisms/StethoPreview";
import Table from "../../../presentational/atoms/Table";
import CustomPagination from "../../../presentational/molecules/CustomPagination";

//redux
import {
  getStetho,
  addStetho,
  deleteStetho,
  updateStetho,
  resetStethoMessage,
} from "../../../../redux/modules/actions/StethoLibraryAction";
import { getLibraryUser } from "../../../../redux/modules/actions/LibraryUserAction";
import { updateSort } from "../../../../redux/modules/actions/UserAction";
import { getTableOrder } from "../../../../redux/modules/actions/UserAction";

//i18n
import { withTranslation } from "react-i18next";
import i18next from "i18next";

//css
import "./style.css";

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
class StethoSounds extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      search_keyword:"",
      isAddModalVisible: false,
      filteredData: { table_data: [], isLoading: true },
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
    this.props.resetStethoMessage();
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
    const { stetho_message, totalPage } = this.props.stethoLibList;
    const { userInfo, t } = this.props;
    return (
      <Div className="template-Stethosounds-wrapper">
        {stetho_message && stetho_message.content && (
          <Div className="toast-wrapper">
            <Toast message={stetho_message} />
          </Div>
        )}
        <AddSearchWithCsv
          onChange={(event) => handleSearchChange(event.target.value, this)}
          onClick={(event) => handleShowAll(false, this)}
          data={filteredCsvData}
          csv_mode="stetho_csv"
          filename="Stetho sounds Data"
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
                return <th key={index}>{t(`${header}`)}</th>;
              })}
              <th colSpan="2" className="text-center">
                {t("actions")}
              </th>
            </tr>
          </thead>
          <StethoSoundsBread
            t={t}
            data={filteredData}
            deleteItem={(item, index) => deleteItem(index, item, this)}
            updateStethoData={(item, index) => updateItem(index, item, this)}
            updateStethoOrder={(data, id) => sortStethoOrder(data, id, this)}
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
            data={this.props.stethoLibList}
            userList={this.props.libraryUserList}
            onHideAddModal={() => handleAddModalVisible(this, false)}
            inputUserData={(event) => getInputUser(event, this)}
            addStethoData={(stetho_data) => addStethoData(stetho_data, this)}
          />
        )}

        {isPreviewModalVisible && (
          <StethoPreview
            isVisible={isPreviewModalVisible}
            previewItem={previewItem}
            onHideStethoPreview={() =>
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
const sortStethoOrder = async (data, info, context) => {
  context.setState({
    filteredData: { table_data: data, isLoading: false },
    tableData: {
      table_data: JSON.parse(JSON.stringify(data)),
      isLoading: false,
    },
  });
  await context.props.updateSort(
    data,
    { table: "Stetho Sound", id: info, page: context.state.currentPage },
    context.props.userToken
  );
};

/**
 * add stetho data
 * @param {*} data
 * @param {*} context
 */
const addStethoData = async (data, context) => {
  const { userInfo, userToken } = context.props;
  await context.props.addStetho({ ...data, user_id: userInfo.id }, userToken);
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
  const { userToken, userInfo } = context.props;
  const { currentPage, isSort, search_keyword } = context.state;
  await context.props.getStetho(userToken, isSort ? "all" : currentPage, search_keyword);
  let stetho_list = null;
  await context.props.getTableOrder(userToken, {
    table: "Stetho Sound",
    id: userInfo.id,
  });

  let { stethoList } = await context.props.stethoLibList;
  stetho_list = stethoList.map((item) => {
    return {
      ID: item.id,
      title: item.title,
      title_en: item.title_en,
      ausculation_site: item.area,
      ausculation_site_en: item.area_en,
      disease: item.disease,
      disease_en: item.disease_en,
      is_video: item.is_video_show,
      selected_conversion_type: item.conversion_type,
      selected_sound_type: item.type,

      source_desc: item.sub_description,
      source_desc_en: item.sub_description_en,
      // sound_explain : item.
      conversion_type:getConversionType(item.conversion_type),
      soundtype: getItemType(item.type),
      area: item.area ? item.area : item.area_en ? item.title_en : "-",
      normal_abnormal: item.is_normal ? "normal" : "abnormal",
      status: item.status,
      public_private: !item.is_public ? "private" : "public",
      updated_at: item.updated_at,
      user: item.user && item.user.name,
      role: item.user && item.user.role,
      supervisor: item.user && item.user.id,
      title_en: item.title_en,
      description: item.description,
      description_en: item.description_en,
      image_description: item.image_description,
      image_description_en: item.image_description_en,
      content_group: item.content_group,
      sort: item.sort,
      image_list: item.images.filter((item) => item.lang == "ja"),
      image_list_en: item.images.filter((item) => item.lang == "en"),
      supervisor_comment: item.supervisor_comment
        ? item.supervisor_comment
        : "",
      group_attribute: item.exam_groups.length != 0 ? 0 : 1,
      selected_exam_group:
        item.exam_groups != 0
          ? item.exam_groups.map((item) => ({
              id: item.id,
              text: item.name,
            }))
          : [],
      sound_source: item.sound_path,
      userInfo: userInfo,
      user_id: item.user_id,
    };
  });

  let order = [];
  let sortedStetho = stetho_list;
  if (context.props.tableSort.tableSort != "no data") {
    order = JSON.parse(context.props.tableSort.tableSort);
    if (order) {
      stetho_list.map((item) => {
        let thisorder = order.find((order) => item.ID == order.stetho_id);
        item.disp_order = thisorder && thisorder.disp_order;
      });
      sortedStetho = stetho_list.sort(
        (a, b) => new Date(a.disp_order) - new Date(b.disp_order)
      );
    }
  }

  if (currentPage !== "all") {
    context.setState({
      filteredData: {
        table_data: sortedStetho,
        isLoading: false,
      },
      tableData: {
        table_data: JSON.parse(JSON.stringify(stetho_list)),
        isLoading: false,
      },
    });
  } else {
    context.setState({
      filteredCsvData: JSON.parse(
        JSON.stringify({ table_data: sortedStetho, isLoading: false })
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
 * get Conversion Type
 * @param {*} item_no
 */
 const getConversionType = (item_no) => {
  switch (item_no) {
    case 0:
      return "orig_collection";
    case 1:
      return "processing_sound";
    case 2:
      return "artificial_sound";
    default:
      return "-";
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
  await context.props.deleteStetho(item.ID, context.props.userToken);
  if (context.props.stethoLibList.stetho_message.mode === "error") return;
  const { filteredData } = context.state;
  filteredData.table_data.splice(index + 1, 1);
  context.setState({ filteredData, tableData: filteredData });
  handleFetchData(context);
};

/**
 * edit / update item
 * @param {*} index
 * @param {*} item
 * @param {*} context
 */
const updateItem = async (index, item, context) => {
  const { userInfo, userToken } = context.props;
  await context.props.updateStetho(
    { ...item, user_id: userInfo.id },
    userToken,
    index
  );
  if (context.props.stethoLibList.stetho_message.mode === "error") return;
  const { filteredData } = context.state;
  context.setState({ filteredData, tableData: filteredData });
  handleFetchData(context);
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
    stethoLibList: state.stethoLibraryList,
    libraryUserList: state.LibraryUserList,
    tableSort: state.tableSort,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, {
  getStetho,
  getLibraryUser,
  addStetho,
  deleteStetho,
  updateStetho,
  resetStethoMessage,
  updateSort,
  getTableOrder,
})(withTranslation("translation")(withRouter(StethoSounds)));
