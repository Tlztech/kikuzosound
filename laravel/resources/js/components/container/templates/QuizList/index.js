import React,{ createRef } from "react";
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

//components
import Div from "../../../presentational/atoms/Div";
import AddModal from "../../organisms/QuizAdd";
import QuizListBread from "../../organisms/QuizList";
import Toast from "../../../presentational/molecules/Toast";
import Table from "../../../presentational/atoms/Table";
import AddSearchWithCsv from "../../../container/organisms/AddSearchWithCsv";
import QuizPreview from "../QuizPreview";
import CustomPagination from "../../../presentational/molecules/CustomPagination";
import SortButton from "../../../presentational/molecules/SortButton";

// redux
import { getAusculaide } from "../../../../redux/modules/actions/AusculaideLibraryAction";
import { getStetho } from "../../../../redux/modules/actions/StethoLibraryAction";
import { getEcg } from "../../../../redux/modules/actions/EcgLibraryAction";
import { getInspectionLibrary } from "../../../../redux/modules/actions/InspectionLibraryAction";
import { getUcgLibrary } from "../../../../redux/modules/actions/UcgLibraryAction";
import { getPalpation } from "../../../../redux/modules/actions/PalpationLibraryAction";
import { getXrays } from "../../../../redux/modules/actions/XrayLibraryAction";
import {
  getAllQuizzes,
  addQuizzes,
  getQuizSuccess,
  deleteQuizzes,
  resetQuizzesMessage,
} from "../../../../redux/modules/actions/QuizAction";
import {
  getTableOrder,
  updateSort,
} from "../../../../redux/modules/actions/UserAction";

//css
import "./style.css";

//lib
import { CircularProgress } from "@material-ui/core";

//i18n
import { withTranslation } from "react-i18next";
import i18next from "i18next";

let header = [
  "title",
  "an_illustration",
  "library",
  "content",
  "answer_options",
  "time_limit",
];
//===================================================
// Component
//===================================================
class QuizList extends React.Component {
  _isMounted = false;
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
      lib_items_data: {},
      selectedQuizId: null,
      isPreviewModalVisible: false,
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
    handleLibraryData(this);
  }

  componentDidUpdate() {
    if (this.props.isItemUpdated) {
      this.props.resetQuizzesMessage();
      handleFetchData(this);
    }
  }

  componentWillUnmount() {
    this._isMounted = false;
    this.props.resetQuizzesMessage();
  }

  render() {
    const {
      isAddModalVisible,
      filteredData,
      isPreviewModalVisible,
      selectedQuizId,
      filteredCsvData,
      isDownloadingCsv,
      lib_items_data,
      isSort,
      currentPage
    } = this.state;
    const { userInfo, quizzes_message, totalPage, t } = this.props;
    return (
      <Div className="template-QuizList-wrapper">
        {quizzes_message && quizzes_message.content && (
          <Div className="toast-wrapper">
            <Toast message={quizzes_message} />
          </Div>
        )}

        <AddSearchWithCsv
          onChange={(event) => handleSearchChange(event.target.value, this)}
          onClick={(event) => handleShowAll(false, this)}
          data={filteredCsvData}
          filename="QuizList Data"
          csv_mode="quiz_list_csv_data"
          onAddNewClicked={() => handleAddModalVisible(this, true)}
          handleFetchCsvData={() => handleFetchCsvData(this)}
          isDownloading={isDownloadingCsv}
          setDownloadCsv={(isDownload) => setDownloadCsv(this, isDownload)}
          disabled={Object.keys(lib_items_data).length < 1}
          search_input_ref={this.searchRef}
        />

        <Table
          size="lg"
          className={isSort ? "withPaginationTable" : "withoutPaginationTable"}
        >
          <thead>
            <tr>
              <th style={{ width: "40px" }}></th>
              <th style={{ width: "80px" }}>ID</th>
              {header &&
                header.map((header, index) => {
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
          {Object.keys(this.state.lib_items_data).length != 0 ? (
            <QuizListBread
              data={filteredData}
              deleteItem={(item) => deleteItem(item, this)}
              isUpdated={this.props.isItemUpdated}
              libItems={this.state.lib_items_data}
              updateQuizOrder={(data, id) => sortQuizOrder(data, id, this)}
              handlePreviewModalVisible={(quizId) =>
                handleToggleQuizPreviewModal(quizId, this)
              }
              userInfo={userInfo}
            />
          ) : (
            <tbody>
              <tr>
                <td className="no-data">
                  {
                    <>
                      <CircularProgress />
                    </>
                  }
                </td>
              </tr>
            </tbody>
          )}
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
            addQuizData={(quiz_data) => addQuizData(quiz_data, this)}
            libItems={lib_items_data}
          />
        )}
        {isPreviewModalVisible && (
          <QuizPreview
            isVisible={isPreviewModalVisible}
            quizId={selectedQuizId}
            onHideQuizPreviewModal={() =>
              handleToggleQuizPreviewModal(null, this)
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
 * Toggle preview modal
 * @param {*} quizId
 * @param {*} context
 */
const handleToggleQuizPreviewModal = (quizId, context) => {
  const { isPreviewModalVisible } = context.state;
  context.setState({
    isPreviewModalVisible: !isPreviewModalVisible,
    selectedQuizId: quizId,
  });
};

/**
 * edit Quiz
 * @param {*} data
 * @param {*} info
 * @param {*} context
 */
const sortQuizOrder = async (data, info, context) => {
  context.setState({
    filteredData: { table_data: data, isLoading: false },
    tableData: {
      table_data: JSON.parse(JSON.stringify(data)),
      isLoading: false,
    },
  });
  await context.props.updateSort(
    data,
    { table: "quiz", id: info, page: context.state.currentPage },
    context.props.userToken
  );
};

/**
 * add Quiz data
 * @param {*} data
 * @param {*} context
 */
const addQuizData = async (data, context) => {
  const { userInfo } = context.props;
  await context.props.addQuizzes(
    { ...data, user_id: userInfo.id },
    context.props.userToken
  );
  handleFetchData(context);
};

/**
 * get quizzes list
 * @param {*} context
 */
const handleFetchData = async (context) => {
  context.setState({
    filteredData: {
      table_data: [],
      isLoading: true,
    },
  });
  const { userToken } = context.props;
  const { currentPage, isSort, search_keyword } = context.state;
  let info = JSON.parse(localStorage.getItem("persist:user"));
  let data = JSON.parse(info.userInfo);
  await context.props.getAllQuizzes(userToken, isSort ? "all" : currentPage, search_keyword);
  await context.props.getTableOrder(context.props.userToken, {
    table: "quiz",
    id: data.user.id,
  });
  const response = await context.props.allQuizzesList;
  let obtainedData = [];
  let order = [];
  response &&
    response.map((item) => {
      obtainedData = [
        ...obtainedData,
        {
          id: item.id,
          title: item.title,
          title_en: item.title_en,
          illustration: item.image_path,
          library: item.lib_count,
          content: item.stetho_sounds ? item.stetho_sounds.length : 0,
          answerOptions: item.choices_count,
          timeLimit: item.limit_seconds,
          item: item,
          disp_order: item.disp_order,
          user: item.exam_author && item.exam_author.name,
          role: item.exam_author && item.exam_author.role,
          user_id : item.user_id,
        },
      ];
    });
  let sortedQuiz = obtainedData;
  if (context.props.tableSort.tableSort != "no data") {
    order = JSON.parse(context.props.tableSort.tableSort);
    if (order) {
      obtainedData &&
        obtainedData.map((item) => {
          let thisorder = order.find((order) => item.id == order.quiz_id);
          item.disp_order = thisorder && thisorder.disp_order;
        });
      sortedQuiz = obtainedData.sort(
        (a, b) => new Date(a.disp_order) - new Date(b.disp_order)
      );
    }
  }

  if (context._isMounted) {
    if (currentPage !== "all") {
      context.setState({
        filteredData: { table_data: sortedQuiz },
        tableData: { table_data: JSON.parse(JSON.stringify(obtainedData)) },
        isLoading: false,
      });
    } else {
      context.setState({
        filteredCsvData: JSON.parse(
          JSON.stringify({ table_data: sortedQuiz, isLoading: false })
        ),
        isDownloadingCsv: true,
      });
    }
  }
};

/**
 * delete quiz
 * @param {*} context
 */
const deleteItem = async (item, context) => {
  await context.props.deleteQuizzes(item.id, context.props.userToken);
  if (
    context.props.quizzes_message &&
    context.props.quizzes_message.mode === "error"
  )
    return;
  handleFetchData(context);
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
  // const targetText = target.trim().toLowerCase();
  // const { filteredData, tableData } = context.state;
  // filteredData.table_data = tableData.table_data.filter(
  //   (el) =>
  //     (i18next.language === "ja" &&
  //       el.title.toLowerCase().includes(targetText)) ||
  //     (i18next.language === "en" &&
  //       el.title_en.toLowerCase().includes(targetText)) ||
  //     el.id.toString().includes(targetText)
  // );
  // context.setState({
  //   filteredData,
  // });
};

//fetch library data
const handleLibraryData = async (context) => {
  await context.props.getAusculaide(context.props.userToken, "all");
  await context.props.getStetho(context.props.userToken, "all");
  await context.props.getEcg(context.props.userToken, "all");
  await context.props.getInspectionLibrary(context.props.userToken, "all");
  await context.props.getUcgLibrary(context.props.userToken, "all");
  await context.props.getPalpation(context.props.userToken, "all");
  await context.props.getXrays(context.props.userToken, "all");
  let { ausculaideList } = await context.props.ausculaideLibList;
  let { stethoList } = await context.props.stethoLibList;
  let { xrayList } = await context.props.xrayLibList;
  let { ecg_list } = await context.props.ecgLibList;
  let { ucgList } = await context.props.ucgLibList;
  let { palpation_list } = await context.props.palpationLibList;
  let { inspectionLibraryList } = await context.props.inspectionLibList;
  let ausculaide_list =
    ausculaideList &&
    ausculaideList.map((item) => {
      return {
        id: item.id,
        value: item.title,
        value_en: item.title_en,
      };
    });

  let stetho_list =
    stethoList &&
    stethoList.map((item) => {
      return {
        id: item.id,
        value: item.title,
        value_en: item.title_en,
      };
    });

  let xray_list =
    xrayList &&
    xrayList.map((item) => {
      return {
        id: item.id,
        value: item.title,
        value_en: item.title_en,
      };
    });
  ecg_list =
    ecg_list &&
    ecg_list.map((item) => {
      return {
        id: item.id,
        value: item.title,
        value_en: item.title_en,
      };
    });
  let ucg_list =
    ucgList &&
    ucgList.map((item) => {
      return {
        id: item.id,
        value: item.title,
        value_en: item.title_en,
      };
    });
  palpation_list =
    palpation_list &&
    palpation_list.map((item) => {
      return {
        id: item.id,
        value: item.title,
        value_en: item.title_en,
      };
    });
  let inspection_list =
    inspectionLibraryList &&
    inspectionLibraryList.map((item) => {
      return {
        id: item.id,
        value: item.title,
        value_en: item.title_en,
      };
    });
  let lib_items_data = {
    ausculaide_list: ausculaide_list,
    stetho_list: stetho_list,
    xray_list: xray_list,
    ecg_list: ecg_list,
    ucg_list: ucg_list,
    palpation_list: palpation_list,
    inspection_list: inspection_list,
  };
  if (context._isMounted) {
    context.setState({
      lib_items_data: lib_items_data,
    });
  }
};
//===================================================
// Actions
//===================================================
/**
 * show/hide add modal
 * @param {*} context
 * @param {*} isVisible
 */
const handleAddModalVisible = (context, isVisible) => {
  context.setState({ isAddModalVisible: isVisible });
};

//===================================================
// Redux
//===================================================
const mapStateToProps = (state) => {
  return {
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    userInfo: state.auth.userInfo && state.auth.userInfo.user,
    allQuizzesList: state.quizzes.allQuizzesList,
    quizzes_message: state.quizzes.quizzes_message,
    totalPage: state.quizzes.totalPage,
    isItemUpdated: state.quizzes.updatedItem,

    //libitems
    ausculaideLibList: state.ausculaideLibraryList,
    stethoLibList: state.stethoLibraryList,
    xrayLibList: state.xrayLibraryList,
    ecgLibList: state.ecgLibList,
    ucgLibList: state.ucgLibrary,
    palpationLibList: state.palpationLibList,
    inspectionLibList: state.inspectionLibList,
    tableSort: state.tableSort,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, {
  getAllQuizzes,
  addQuizzes,
  getQuizSuccess,
  deleteQuizzes,
  resetQuizzesMessage,

  //lib items
  getAusculaide,
  getStetho,
  getEcg,
  getInspectionLibrary,
  getUcgLibrary,
  getPalpation,
  getXrays,
  getTableOrder,
  updateSort,
})(withTranslation("translation")(withRouter(QuizList)));
