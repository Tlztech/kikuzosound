import React,{ createRef } from "react";

// libs
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

// components
import Div from "../../../presentational/atoms/Div";
import AddModal from "../../organisms/AsculaideLibraryAdd";
import AsculaideLibrary from "../../organisms/AsculaideLibrary";
import Toast from "../../../presentational/molecules/Toast";
import Table from "../../../presentational/atoms/Table";
import AddSearchWithCsv from "../../../container/organisms/AddSearchWithCsv";
import AusculaidePreview from "../../organisms/AusculaidePreview";
import CustomPagination from "../../../presentational/molecules/CustomPagination";
import SortButton from "../../../presentational/molecules/SortButton";

// redux
import {
  getAusculaide,
  addAusculaide,
  deleteAusculaide,
  updateAusculaide,
  resetAusculaideMessage,
} from "../../../../redux/modules/actions/AusculaideLibraryAction";
import { getLibraryUser } from "../../../../redux/modules/actions/LibraryUserAction";
import {
  updateSort,
  getTableOrder,
} from "../../../../redux/modules/actions/UserAction";

// i18n
import { withTranslation } from "react-i18next";

//css
import "./style.css";
import i18next from "i18next";

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
class AsculaideLIbrary extends React.Component {
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
    this.props.resetAusculaideMessage();
  }

  render() {
    const {
      isAddModalVisible,
      filteredCsvData,
      isPreviewModalVisible,
      previewItem,
      isDownloadingCsv,
      isSort,
      currentPage
    } = this.state;
    const { userInfo, t } = this.props;
    const { ausc_message, totalPage } = this.props.ausculaideLibList;
    return (
      <Div className="template-AsculaideLIbrary-wrapper">
        {ausc_message && ausc_message.content && (
          <Div className="toast-wrapper">
            <Toast message={ausc_message} />
          </Div>
        )}

        <AddSearchWithCsv
          onChange={(event) => handleSearchChange(event.target.value, this)}
          onClick={(event) => handleShowAll(false, this)}
          data={filteredCsvData}
          csv_mode="ausculaide_csv"
          filename="Ausculaide library Data"
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
          <AsculaideLibrary
            t={t}
            data={this.state.filteredData}
            deleteItem={(item, index) => deleteItem(index, item, this)}
            updateAusculaideData={(item, index) =>
              updateItem(item, index, this)
            }
            updateAusculaideOrder={(data, id) =>
              sortAusculaideOrder(data, id, this)
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
          data={this.props.ausculaideLibList}
          userList={this.props.libraryUserList}
          onHideAddModal={() => handleAddModalVisible(this, false)}
          inputUserData={(event) => getInputUser(event, this)}
          addAusculaideData={(ausuclaide_data) =>
            addAusculaideData(ausuclaide_data, this)
          }
        />
        {isPreviewModalVisible && (
          <AusculaidePreview
            isVisible={isPreviewModalVisible}
            previewItem={previewItem}
            onHideAusculaidePreview={() =>
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
const sortAusculaideOrder = async (data, info, context) => {
  context.setState({
    filteredData: { table_data: data, isLoading: false },
    tableData: {
      table_data: JSON.parse(JSON.stringify(data)),
      isLoading: false,
    },
  });
  await context.props.updateSort(
    data,
    {
      table: "Ausculaide Library",
      id: info,
      page: context.state.currentPage,
    },
    context.props.userToken
  );
};

/**
 * add ausculaide data
 * @param {*} data
 * @param {*} context
 */
const addAusculaideData = async (data, context) => {
  const { userInfo, userToken } = context.props;
  await context.props.addAusculaide(
    { ...data, user_id: userInfo.id },
    userToken
  );
  handleFetchData(context);
};

/**
 * edit / update item
 * @param {*} item
 * @param {*} index
 * @param {*} context
 */
const updateItem = async (item, index, context) => {
  const { userToken, userInfo } = context.props;
  await context.props.updateAusculaide(
    { ...item, user_id: userInfo.id },
    userToken,
    index
  );
  if (context.props.ausculaideLibList.ausc_message.mode === "error") return;
  const { filteredData } = context.state;
  context.setState({ filteredData, tableData: filteredData });
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
  const { currentPage, isSort, search_keyword } = context.state;
  const { userToken, userInfo } = context.props;
  await context.props.getAusculaide(userToken, isSort ? "all" : currentPage, search_keyword);
  let ausculaide_list = [];
  let { ausculaideList } = await context.props.ausculaideLibList;
  await context.props.getTableOrder(userToken, {
    table: "Ausculaide Library",
    id: userInfo.id,
  });

  ausculaide_list =
    ausculaideList &&
    ausculaideList.map((item) => {
      var asc_configs = item.configuration && JSON.parse(item.configuration);
      return {
        ID: item.id,
        title: item.title,
        title_en: item.title_en,
        soundtype: getItemType(parseInt(item.type)),
        area: item.area ? item.area : item.area_en ? item.title_en : "-",
        normal_abnormal: item.is_normal ? "normal" : "abnormal",
        status: item.status,
        public_private: !item.is_public ? "private" : "public",
        updated_at: item.updated_at,
        created_at: item.created_at,
        user: item.user && item.user.name,
        role: item.user && item.user.role,
        user_id: item.user && item.user.id,
        title_en: item.title_en,
        description: item.description,
        description_en: item.description_en,
        image_description: item.image_description,
        image_description_en: item.image_description_en,
        content_group: item.type,
        sort: item.sort,
        coordinate: item.coordinate,
        supervisor_comment: item.supervisor_comment
          ? item.supervisor_comment
          : "",
        explanatory_image:
          item.images && item.images.filter((img) => img.lang == "ja"),
        explanatory_image_en:
          item.images && item.images.filter((img) => img.lang == "en"),
        body_image: item.body_image,
        body_image_back: item.body_image_back,
        moodle_url: item.moodle_url,
        group_attribute: item.exam_groups.length != 0 ? 0 : 1,
        selected_exam_group:
          item.exam_groups.length != 0
            ? item.exam_groups.map((item) => ({
                id: item.id,
                text: item.name,
              }))
            : [],

        //configuration
        a_x: asc_configs ? asc_configs.a.x : "",
        a_y: asc_configs ? asc_configs.a.y : "",
        a_r: asc_configs ? asc_configs.a.r : "",
        p_x: asc_configs ? asc_configs.p.x : "",
        p_y: asc_configs ? asc_configs.p.y : "",
        p_r: asc_configs ? asc_configs.p.r : "",
        t_x: asc_configs ? asc_configs.t.x : "",
        t_y: asc_configs ? asc_configs.t.y : "",
        t_r: asc_configs ? asc_configs.t.r : "",
        m_x: asc_configs ? asc_configs.m.x : "",
        m_y: asc_configs ? asc_configs.m.y : "",
        m_r: asc_configs ? asc_configs.m.r : "",

        // Shinon 1
        h1_x: asc_configs ? (asc_configs.h1 ? asc_configs.h1.x : "") : "",
        h1_y: asc_configs ? (asc_configs.h1 ? asc_configs.h1.y : "") : "",
        h1_r: asc_configs ? (asc_configs.h1 ? asc_configs.h1.r : "") : "",
        // Shinon 2
        h2_x: asc_configs ? (asc_configs.h2 ? asc_configs.h2.x : "") : "",
        h2_y: asc_configs ? (asc_configs.h2 ? asc_configs.h2.y : "") : "",
        h2_r: asc_configs ? (asc_configs.h2 ? asc_configs.h2.r : "") : "",
        // Shinon 3
        h3_x: asc_configs ? (asc_configs.h3 ? asc_configs.h3.x : "") : "",
        h3_y: asc_configs ? (asc_configs.h3 ? asc_configs.h3.y : "") : "",
        h3_r: asc_configs ? (asc_configs.h3 ? asc_configs.h3.r : "") : "",
        // Shinon 4
        h4_x: asc_configs ? (asc_configs.h4 ? asc_configs.h4.x : "") : "",
        h4_y: asc_configs ? (asc_configs.h4 ? asc_configs.h4.y : "") : "",
        h4_r: asc_configs ? (asc_configs.h4 ? asc_configs.h4.r : "") : "",

        // tracheal 1
        tr1_x: asc_configs ? (asc_configs.tr1 ? asc_configs.tr1.x : "") : "",
        tr1_y: asc_configs ? (asc_configs.tr1 ? asc_configs.tr1.y : "") : "",
        tr1_r: asc_configs ? (asc_configs.tr1 ? asc_configs.tr1.r : "") : "",
        // tracheal 2
        tr2_x: asc_configs ? (asc_configs.tr2 ? asc_configs.tr2.x : "") : "",
        tr2_y: asc_configs ? (asc_configs.tr2 ? asc_configs.tr2.y : "") : "",
        tr2_r: asc_configs ? (asc_configs.tr2 ? asc_configs.tr2.r : "") : "",

        // Bronchial 1
        br1_x: asc_configs ? (asc_configs.br1 ? asc_configs.br1.x : "") : "",
        br1_y: asc_configs ? (asc_configs.br1 ? asc_configs.br1.y : "") : "",
        br1_r: asc_configs ? (asc_configs.br1 ? asc_configs.br1.r : "") : "",
        // Bronchial 2
        br2_x: asc_configs ? (asc_configs.br2 ? asc_configs.br2.x : "") : "",
        br2_y: asc_configs ? (asc_configs.br2 ? asc_configs.br2.y : "") : "",
        br2_r: asc_configs ? (asc_configs.br2 ? asc_configs.br2.r : "") : "",
        // Bronchial 3
        br3_x: asc_configs ? (asc_configs.br3 ? asc_configs.br3.x : "") : "",
        br3_y: asc_configs ? (asc_configs.br3 ? asc_configs.br3.y : "") : "",
        br3_r: asc_configs ? (asc_configs.br3 ? asc_configs.br3.r : "") : "",
        // Bronchial 4
        br4_x: asc_configs ? (asc_configs.br4 ? asc_configs.br4.x : "") : "",
        br4_y: asc_configs ? (asc_configs.br4 ? asc_configs.br4.y : "") : "",
        br4_r: asc_configs ? (asc_configs.br4 ? asc_configs.br4.r : "") : "",

        // Alveolar 1
        ve1_x: asc_configs ? (asc_configs.ve1 ? asc_configs.ve1.x : "") : "",
        ve1_y: asc_configs ? (asc_configs.ve1 ? asc_configs.ve1.y : "") : "",
        ve1_r: asc_configs ? (asc_configs.ve1 ? asc_configs.ve1.r : "") : "",

        // Alveolar 2
        ve2_x: asc_configs ? (asc_configs.ve2 ? asc_configs.ve2.x : "") : "",
        ve2_y: asc_configs ? (asc_configs.ve2 ? asc_configs.ve2.y : "") : "",
        ve2_r: asc_configs ? (asc_configs.ve2 ? asc_configs.ve2.r : "") : "",

        // Alveolar 3
        ve3_x: asc_configs ? (asc_configs.ve3 ? asc_configs.ve3.x : "") : "",
        ve3_y: asc_configs ? (asc_configs.ve3 ? asc_configs.ve3.y : "") : "",
        ve3_r: asc_configs ? (asc_configs.ve3 ? asc_configs.ve3.r : "") : "",

        // Alveolar 4
        ve4_x: asc_configs ? (asc_configs.ve4 ? asc_configs.ve4.x : "") : "",
        ve4_y: asc_configs ? (asc_configs.ve4 ? asc_configs.ve4.y : "") : "",
        ve4_r: asc_configs ? (asc_configs.ve4 ? asc_configs.ve4.r : "") : "",

        // Alveolar 5
        ve5_x: asc_configs ? (asc_configs.ve5 ? asc_configs.ve5.x : "") : "",
        ve5_y: asc_configs ? (asc_configs.ve5 ? asc_configs.ve5.y : "") : "",
        ve5_r: asc_configs ? (asc_configs.ve5 ? asc_configs.ve5.r : "") : "",

        // Alveolar 6
        ve6_x: asc_configs ? (asc_configs.ve6 ? asc_configs.ve6.x : "") : "",
        ve6_y: asc_configs ? (asc_configs.ve6 ? asc_configs.ve6.y : "") : "",
        ve6_r: asc_configs ? (asc_configs.ve6 ? asc_configs.ve6.r : "") : "",

        // Alveolar 7
        ve7_x: asc_configs ? (asc_configs.ve7 ? asc_configs.ve7.x : "") : "",
        ve7_y: asc_configs ? (asc_configs.ve7 ? asc_configs.ve7.y : "") : "",
        ve7_r: asc_configs ? (asc_configs.ve7 ? asc_configs.ve7.r : "") : "",

        // Alveolar 8
        ve8_x: asc_configs ? (asc_configs.ve8 ? asc_configs.ve8.x : "") : "",
        ve8_y: asc_configs ? (asc_configs.ve8 ? asc_configs.ve8.y : "") : "",
        ve8_r: asc_configs ? (asc_configs.ve8 ? asc_configs.ve8.r : "") : "",

        // Alveolar 9
        ve9_x: asc_configs ? (asc_configs.ve9 ? asc_configs.ve9.x : "") : "",
        ve9_y: asc_configs ? (asc_configs.ve9 ? asc_configs.ve9.y : "") : "",
        ve9_r: asc_configs ? (asc_configs.ve9 ? asc_configs.ve9.r : "") : "",

        // Alveolar 10
        ve10_x: asc_configs ? (asc_configs.ve10 ? asc_configs.ve10.x : "") : "",
        ve10_y: asc_configs ? (asc_configs.ve10 ? asc_configs.ve10.y : "") : "",
        ve10_r: asc_configs ? (asc_configs.ve10 ? asc_configs.ve10.r : "") : "",

        // Alveolar 11
        ve11_x: asc_configs ? (asc_configs.ve11 ? asc_configs.ve11.x : "") : "",
        ve11_y: asc_configs ? (asc_configs.ve11 ? asc_configs.ve11.y : "") : "",
        ve11_r: asc_configs ? (asc_configs.ve11 ? asc_configs.ve11.r : "") : "",

        // Alveolar 12
        ve12_x: asc_configs ? (asc_configs.ve12 ? asc_configs.ve12.x : "") : "",
        ve12_y: asc_configs ? (asc_configs.ve12 ? asc_configs.ve12.y : "") : "",
        ve12_r: asc_configs ? (asc_configs.ve12 ? asc_configs.ve12.r : "") : "",

        a_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).a_sound_path,
        pa_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).pa_sound_path,
        p_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).p_sound_path,
        pp_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).pp_sound_path,
        t_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).t_sound_path,
        pt_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).pt_sound_path,
        m_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).m_sound_path,
        pm_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).pm_sound_path,

        // Bronchial
        h1_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).h1_sound_path,
        h2_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).h2_sound_path,
        h3_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).h3_sound_path,
        h4_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).h4_sound_path,

        // tracheal
        tr1_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).tr1_sound_path,
        tr2_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).tr2_sound_path,

        // Bronchial
        br1_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).br1_sound_path,
        br2_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).br2_sound_path,
        br3_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).br3_sound_path,
        br4_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).br4_sound_path,

        // alveolar
        ve1_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).ve1_sound_path,
        ve2_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).ve2_sound_path,
        ve3_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).ve3_sound_path,
        ve4_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).ve4_sound_path,

        ve5_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).ve5_sound_path,
        ve6_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).ve6_sound_path,
        ve7_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).ve7_sound_path,
        ve8_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).ve8_sound_path,

        ve9_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).ve9_sound_path,
        ve10_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).ve10_sound_path,
        ve11_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).ve11_sound_path,
        ve12_sound_file:
          item.sound_path &&
          isJSON(item.sound_path) &&
          JSON.parse(item.sound_path).ve12_sound_path,
        userInfo: userInfo,
      };
    });

  let order = [];
  let sortedAsucalaid = ausculaide_list;
  if (context.props.tableSort.tableSort != "no data") {
    order = JSON.parse(context.props.tableSort.tableSort);
    if (order) {
      ausculaide_list.map((item) => {
        let thisorder = order.find((order) => item.ID == order.ausculaide_id);
        item.disp_order = thisorder && thisorder.disp_order;
      });
      sortedAsucalaid = ausculaide_list.sort(
        (a, b) => new Date(a.disp_order) - new Date(b.disp_order)
      );
    }
  }
  if (currentPage !== "all") {
    context.setState({
      filteredData: { table_data: sortedAsucalaid, isLoading: false },
      tableData: {
        table_data: JSON.parse(JSON.stringify(ausculaide_list)),
        isLoading: false,
      },
    });
  } else {
    context.setState({
      filteredCsvData: JSON.parse(
        JSON.stringify({
          table_data: sortedAsucalaid,
          isLoading: false,
        })
      ),
      filteredData: { table_data: filteredData.table_data, isLoading: false },
      isDownloadingCsv: true,
    });
  }
};

/**
 * get Item Type
 * @param {*} item_no
 */
const getItemType = (item_no) => {
  switch (item_no) {
    case 1:
      return "lung_sound";
    case 2:
      return "heart_sound";
    default:
      return "-";
  }
};

const isJSON = (str) => {
  try {
    JSON.parse(str);
  } catch (e) {
    return false;
  }
  return true;
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
 * delete ausculaide item
 * @param {*} index
 * @param {*} item
 * @param {*} context
 */
const deleteItem = async (index, item, context) => {
  await context.props.deleteAusculaide(item.ID, context.props.userToken);
  if (context.props.ausculaideLibList.ausc_message.mode === "error") return;
  const { filteredData } = context.state;
  filteredData.table_data.splice(index + 1, 1);
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
    ausculaideLibList: state.ausculaideLibraryList,
    libraryUserList: state.LibraryUserList,
    tableSort: state.tableSort,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, {
  getAusculaide,
  getLibraryUser,
  addAusculaide,
  updateAusculaide,
  deleteAusculaide,
  resetAusculaideMessage,
  updateSort,
  getTableOrder,
})(withTranslation("translation")(withRouter(AsculaideLIbrary)));
