import React,{ createRef } from "react";
import { connect } from "react-redux";

//components
import Div from "../../../presentational/atoms/Div";
import AddModal from "../../organisms/QuizPackAdd";
import Button from "../../../presentational/atoms/Button/index";
import QuizPackBread from "../../organisms/QuizPackBread";
import SearchInput from "../../../presentational/molecules/SearchInput";
import DownloadOptions from "../../../presentational/molecules/DownloadOptions";
import Table from "../../../presentational/atoms/Table/index";
import Toast from "../../../presentational/molecules/Toast/index";
import CustomPagination from "../../../presentational/molecules/CustomPagination";
import SortButton from "../../../presentational/molecules/SortButton";
import ChoiceSelection from "../ChoiceSelection";
import AddSearchWithCsv from "../../../container/organisms/AddSearchWithCsv";

//redux
import {
  getQuizPack,
  addQuizPack,
  deleteQuizPack,
  updateQuizPack,
  resetQuizPackMessage,
} from "../../../../redux/modules/actions/QuizPackAction";
import {
  updateSort,
  getTableOrder,
} from "../../../../redux/modules/actions/UserAction";
//css
import "./style.css";

//redux
import { getExamGroup } from "../../../../redux/modules/actions/ExamGroupAction";
import { getQuizzes } from "../../../../redux/modules/actions/QuizAction";

//i18n
import { withTranslation } from "react-i18next";

const header = [
  "title_jp",
  "title_en",
  "description_quiz_jp",
  "description_en",
  "qstn_format",
  "no_0f_question",
  "public/private",
];
//===================================================
// Component
//===================================================
class QuizPacks extends React.Component {
  _isMounted = false;
  constructor(props) {
    super(props);
    this.state = {
      search_keyword:"",
      isQuizPreviewVisible: false,
      selectedQuizPackId: null,
      isAddModalVisible: false,
      filteredData: {
        table_data: [],
        isLoading: true,
      },
      tableData: {
        table_data: [],
        isLoading: true,
      },
      exam_groups: [],
      quizzes: [],
      currentPage: 0,
      isSort: false,
    };
    this.searchRef = createRef();
  }

  componentDidMount() {
    this._isMounted = true;
    updateData(this);
    handleExamGroupAndQuiz(this);
  }

  componentWillUnmount() {
    this._isMounted = false;
    this.props.resetQuizPackMessage();
  }

  render() {
    const { isSort } = this.state;
    const { isAddModalVisible, filteredData, isQuizPreviewVisible, selectedQuizPackId, currentPage } = this.state;
    const { quizpack_msg, totalPage } = this.props.quizPackList;
    const { t } = this.props;
    const { userInfo } = this.props;
    return (
      <Div className="template-QuizPacks-wrapper">
        {quizpack_msg && quizpack_msg.content && (
          <Div className="toast-wrapper">
            <Toast message={quizpack_msg} />
          </Div>
        )}
        <AddSearchWithCsv
            onChange={(event) => handleSearchChange(event.target.value, this)}
            onClick={(event) => handleShowAll(false, this)}
            //data={filteredCsvData}
            onAddNewClicked={() => handleAddModalVisible(this, true)}
            csv_mode="quiz_packs_csv_data"
            filename="QuizPack Data"
            //handleFetchCsvData={() => handleFetchCsvData(this)}
            //isDownloading={isDownloadingCsv}
            //setDownloadCsv={(isDownload) => setDownloadCsv(this, isDownload)}
            search_input_ref={this.searchRef}
        />
        <Table size="lg">
          <thead>
            <tr>
              <th style={{ width: "50px" }}>ID</th>
              {header.map((header, index) => {
                return <th key={index}>{t(`${header}`)}</th>;
              })}
              <th colSpan="2" className="text-center">
                {t("translation:actions")}
              </th>
            </tr>
          </thead>
          {this.state.exam_groups.length != 0 &&
            this.state.quizzes.length != 0 && (
              <QuizPackBread
                data={filteredData}
                deleteItem={(item) => deleteItem(item, this)}
                updateQuizPack={(item, index) => updateItem(index, item, this)}
                updateOrder={(data, id) => sortOrder(data, id, this)}
                exam_groups={this.state.exam_groups}
                quiz_list={this.state.quizzes}
                userInfo={userInfo}
                setQuizPreviewModalVisible={(quizPackId) =>
                  setQuizPreviewModalVisible(quizPackId, this)
                }
              />
            )}
        </Table>
        {totalPage > 0 && !isSort && (
          <CustomPagination
            totalPage={totalPage}
            currentPage={currentPage}
            onPageChanged={(id) => handleOnPageChanged(id, this)}
          />
        )}
        <Div className="sortButton">
          <SortButton
            isSort={isSort}
            onClick={() => handleSortButton(this)}
          />
        </Div>
        {this.state.exam_groups.length != 0 &&
          this.state.quizzes.length != 0 && (
            <AddModal
              isVisible={isAddModalVisible}
              onHideAddModal={() => handleAddModalVisible(this, false)}
              inputUserData={(event) => getInputUser(event, this)}
              addQuizPackData={(data) => addQuizPackData(data, this)}
              exam_groups={this.state.exam_groups}
              quiz_list={this.state.quizzes}
            />
          )}
          {isQuizPreviewVisible && (
          <ChoiceSelection
            isVisible={isQuizPreviewVisible}
            quizPackId={selectedQuizPackId}
            onHideQuizPreviewModal={() =>
              setQuizPreviewModalVisible(null, this)
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
    updateData(context)
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
      updateData(context)
    );
  }
};

const sortOrder = async (data, info, context) => {
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
    { table: "quiz pack", id: info, page: context.state.currentPage },
    context.props.userToken
  );
};

/**
 * pagination
 * @param {*} selectedPage
 * @param {*} context
 */
 const handleOnPageChanged = (selectedPage, context) => {
  context.setState({ currentPage: selectedPage }, () => {
    updateData(context);
  });
};
/**
 * get table data on load component
 * @param {*} context
 */
 const updateData = async (context) => {
  context.setState({
    filteredData: {
      table_data: [],
      isLoading: true,
      itemIsDeleted: false,
    },
  });
  const { currentPage, isSort, search_keyword} = context.state;
  const { userToken } = context.props;

  await context.props.getQuizPack(userToken, isSort ? "all" : currentPage, search_keyword);
  handleFetchData(context);
};

/**
 * get table data on load component
 * @param {*} context
 */
const handleFetchData = async (context) => {

  const { currentPage, isSort } = context.state;
  const { userToken } = context.props;
  const { userInfo } = context.props;

  await context.props.getTableOrder(userToken, {
    table: "quiz pack",
    id: userInfo.id,
  });
  let quizPacks = null;
  let { quizPackList } = context.props.quizPackList;
  if (quizPackList && quizPackList.length != 0) {
    quizPacks = quizPackList.map((item) => {
      return {
        ID: item.id,
        title_en: item.title_en,
        title: item.title,
        color: item.title_color,
        description: item.description,
        description_en: item.description_en,
        question_format: item.quiz_order_type ? "random" : "fixed",
        number_of_questions: item.max_quiz_count,
        release: item.is_public ? "public" : "private",
        lang: item.lang,
        group_attribute: item.exam_groups.length != 0 ? 0 : 1,
        selected_exam_group:
          item.exam_groups != 0
            ? item.exam_groups.map((item) => ({
                id: item.id,
                text: item.name,
              }))
            : [],
        selected_quiz:
          item.quizzes != 0
            ? item.quizzes.map((item) => ({
                id: item.id,
                value: item.title,
              }))
            : [],
        icon_path: item.icon_path,
        user_id: item.user_id
      };
    });
  }

  let order = [];
  let sortedQuizpack = quizPacks;

  if (context.props.tableSort.tableSort != "no data") {
    order = JSON.parse(context.props.tableSort.tableSort);
    if (order) {
      quizPacks.map((item) => {
        let thisorder = order.find((order) => item.ID == order.quizPackId);
        item.disp_order = thisorder && thisorder.disp_order;
      });
      sortedQuizpack = quizPacks.sort(
        (a, b) => new Date(a.disp_order) - new Date(b.disp_order)
      );
    }
  }

  if (currentPage !== "all") {
    context.setState({
      filteredData: { table_data: sortedQuizpack, isLoading: false },
      tableData: {
        table_data: JSON.parse(JSON.stringify(quizPacks)),
        isLoading: false,
      },
    });
  } else {
    context.setState({
      filteredCsvData: JSON.parse(
        JSON.stringify({
          table_data: sortedQuizpack,
          isLoading: false,
        })
      ),
      filteredData: { table_data: filteredData.table_data, isLoading: false },
      isDownloadingCsv: true,
    });
  }
};

const handleExamGroupAndQuiz = async (context) => {
  await context.props.getExamGroup(context.props.userToken);
  await context.props.getQuizzes(context.props.userToken);
  let exam_groups = [];
  let quizzes = [];
  if (context.props.examGroup && !context.props.examGroup.isLoading) {
    exam_groups = [
      ...context.props.examGroup.examGroupList.map((exam_group) => ({
        id: exam_group.id,
        text: exam_group.name,
      })),
    ];
  }

  if (context.props.quizzes && !context.props.quizzes.isLoading) {
    quizzes = [
      ...context.props.quizzes.quizList.map((list) => ({
        id: list.id,
        value: list.title ? list.title : list.title_en,
      })),
    ];
  }
  context.setState({
    exam_groups,
    quizzes,
  });
};
/**
 * handle search input on change
 * @param {*} target
 * @param {*} context
 */
const handleSearchChange = (target, context) => {
  const targetText = target.trim().toLowerCase();
  const { filteredData, tableData } = context.state;
  const search_keyword = target.trim().toLowerCase();
  context.setState({ currentPage: 0 ,search_keyword:search_keyword}, () => {
    updateData(context);
  });
};

/**
 * add quiz pack data
 * @param {*} data
 * @param {*} context
 */
const addQuizPackData = async (data, context) => {
  await context.props.addQuizPack(data, context.props.userToken);
  updateData(context);
};

/**
 * delete quiz pack item
 * @param {*} item
 * @param {*} context
 */
const deleteItem = async (item, context) => {
  await context.props.deleteQuizPack(item.ID, context.props.userToken);
  if (
    context.props.quizPackMessage &&
    context.props.quizPackMessage.mode === "error"
  )
    return;
    updateData(context);
};

/**
 * edit/update item
 * @param {*} index
 * @param {*} item
 * @param {*} context
 */
const updateItem = async (index, item, context) => {
  await context.props.updateQuizPack(item, context.props.userToken, index);
  if (
    context.props.quizPackMessage &&
    context.props.quizPackMessage.mode === "error"
  )
    return;
    updateData(context);
};

/**
 * show/hide add modal
 * @param {*} context
 * @param {*} isVisible
 */
const handleAddModalVisible = (context, isVisible) => {
  context.setState({ isAddModalVisible: isVisible });
};

//===================================================
// Actions
//===================================================
/**
 * toggle preview modal
 * @param {*} quizPackId
 * @param {*} context
 */
 const setQuizPreviewModalVisible = (quizPackId, context) => {
  const { isQuizPreviewVisible } = context.state;
  context.setState({
    isQuizPreviewVisible: !isQuizPreviewVisible,
    selectedQuizPackId: quizPackId,
  });
};
//===================================================
// Redux
//===================================================
const mapStateToProps = (state) => {
  return {
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    userInfo: state.auth.userInfo && state.auth.userInfo.user,
    quizPackList: state.quizPackList,
    quizPackMessage: state.quizPackList.quizpack_msg,
    examGroup: state.examGroup,
    quizzes: state.quizzes,
    tableSort: state.tableSort,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, {
  getQuizPack,
  addQuizPack,
  deleteQuizPack,
  updateSort,
  getTableOrder,
  updateQuizPack,
  resetQuizPackMessage,
  getExamGroup,
  getQuizzes,
})(withTranslation("translation")(QuizPacks));
