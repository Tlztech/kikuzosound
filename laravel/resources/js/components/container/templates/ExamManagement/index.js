import React,{ createRef } from "react";

// libs
import { withRouter } from "react-router-dom";
import { connect } from "react-redux";

// components
import Table from "../../../presentational/atoms/Table";
import Div from "../../../presentational/atoms/Div";
import ExamTable from "../../../container/organisms/ExamTable";
import Toast from "../../../presentational/molecules/Toast";
import AddModal from "../../../container/organisms/ExamManageAdd";
import AddSearchWithCsv from "../../../container/organisms/AddSearchWithCsv";
import ChoiceSelection from "../ChoiceSelection";
import CustomPagination from "../../../presentational/molecules/CustomPagination";
import SortButton from "../../../presentational/molecules/SortButton";

//redux
import {
  getExams,
  addExam,
  deleteExam,
  updateExam,
  resetExamMessage,
} from "../../../../redux/modules/actions/ExamAction";
import {
  updateSort,
  getTableOrder,
} from "../../../../redux/modules/actions/UserAction";
import { getQuizPackIndex } from "../../../../redux/modules/actions/QuizPackAction";
import { getQuizzes } from "../../../../redux/modules/actions/QuizAction";

//i18n
import { withTranslation } from "react-i18next";
import i18next from "i18next";

// css
import "./style.css";

const header = [
  "exam_title",
  "stepofexam",
  "created_data_time",
  "updated_data_time",
  "type",
  "public/private",
  "analytics",
  "Author",
];

//===================================================
// Component
//===================================================
class ExamManagement extends React.Component {
  _isMounted = false;
  constructor(props) {
    super(props);
    this.state = {
      toAdd: {},
      isAddModalVisible: false,
      search_keyword:"",
      filteredData: {
        table_data: [],
        isLoading: true,
        itemIsDeleted: false,
      },
      filteredCsvData: {
        table_data: [],
        isLoading: true,
      },
      newAddExamId: null,
      tableData: {
        table_data: [],
        isLoading: true,
      },
      quizzes_data: [],
      filtered_quizzes_data: [],
      isQuizPreviewVisible: false,
      selectedQuizPackId: null,
      currentPage: 0,
      isDownloadingCsv: false,
      isSort: false,
    };
    this.searchRef = createRef();
  }

  componentDidMount() {
    this._isMounted = true;
    updateData(this);
  }

  componentWillUnmount() {
    this._isMounted = false;
    this.props.resetExamMessage();
  }

  render() {
    const {
      isAddModalVisible,
      filteredData,
      isQuizPreviewVisible,
      selectedQuizPackId,
      filteredCsvData,
      isDownloadingCsv,
      isSort,
      currentPage
    } = this.state;
    const { message, totalPage } = this.props.examsList;
    const { userInfo, history, t } = this.props;
    return (
        <Div className="template-ExamMangement-wrapper">
          {message && message.content && (
            <Div className="toast-wrapper">
              <Toast message={message} />
            </Div>
          )}

          <AddSearchWithCsv
            onChange={(event) => handleSearchChange(event.target.value, this)}
            onClick={(event) => handleShowAll(false, this)}
            data={filteredCsvData}
            onAddNewClicked={() => handleAddModalVisible(this, true)}
            csv_mode="exam_csv"
            filename="Exam Management Data"
            handleFetchCsvData={() => handleFetchCsvData(this)}
            isDownloading={isDownloadingCsv}
            setDownloadCsv={(isDownload) => setDownloadCsv(this, isDownload)}
            search_input_ref={this.searchRef}
          />

          <Table
            size="lg"
            className={
              isSort ? "withPaginationTable" : "withoutPaginationTable"
            }
          >
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
            <ExamTable
              data={filteredData}
              history={history}
              deleteItem={(item, index) => deleteItem(item, index, this)}
              updateExamData={(updatedExam, updatedQuiz) =>
                handleEditItem(updatedExam, updatedQuiz, this)
              }
              updateOrder={(data, id) => sortOrder(data, id, this)}
              setQuizPreviewModalVisible={(quizPackId) =>
                setQuizPreviewModalVisible(quizPackId, this)
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
            <SortButton
              isSort={isSort}
              onClick={() => handleSortButton(this)}
            />
          </Div>
          {isAddModalVisible && (
            <AddModal
              exams={this.state.filtered_quizzes_data}
              isVisible={isAddModalVisible}
              onHideAddModal={() => handleAddModalVisible(this, false)}
              addExamData={(newExam, newQuiz) =>
                addNewData(this, newExam, newQuiz)
              }
              onSearchFilter={(keyword, childContext) =>
                handleSearchFilter(this, keyword, childContext)
              }
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

/**
 * edit exam
 * @param {*} data
 * @param {*} info
 * @param {*} context
 */
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
    { table: "exam management", id: info, page: context.state.currentPage },
    context.props.userToken
  );
};

/**
 * set pagination index
 * @param {*} selectedPage
 * @param {*} context
 */
const handleOnPageChanged = (selectedPage, context) => {
  context.setState({ currentPage: selectedPage }, () => {
    updateData(context);
  });
};

/**
 * edit exam
 * @param {*} item
 * @param {*} context
 */
const handleEditItem = async (updatedExam, updatedQuiz, context) => {
  let newExamResult = context.props.examsList.newExamResult || null;
  const { userInfo, userToken } = context.props;
  await context.props.updateExam(
    updatedExam,
    updatedQuiz,
    userToken,
    userInfo.id,
    newExamResult
  );
  if (context.props.examsList.message.mode === "error") return;
  updateData(context);
  //  updatedExam.examId = updatedExam.examId || newExamResult ? newExamResult.exam.id: null;
  // let index = context.state.tableData.table_data.findIndex(
  //   (item) => item.quizPackId === updatedExam.quizPackId
  // );
  // updatedExam.Step_of_Exam = updatedExam.Step_of_Exam.map((item, key) => {
  //   key = key + 1;
  //   return {
  //     id: context.state.quizzes_data.find((quiz) => quiz.id === item).id,
  //     title:
  //       key +
  //       "." +
  //       context.state.quizzes_data.find((quiz) => quiz.id === item).taskName,
  //     question: context.state.quizzes_data.find((quiz) => quiz.id == item)
  //       .question,
  //     quiz_choices: context.state.quizzes_data
  //       .find((quiz) => quiz.id == item)
  //       .quiz_choices.map((choice) => {
  //         return {
  //           is_correct_answer: choice.is_correct,
  //           title: choice.title != "" ? choice.title : choice.title_en,
  //         };
  //       })
  //       .filter((choice) => choice.title_en != ""),
  //   };
  // });
  // filteredData.table_data[index] = {
  //   ...updatedExam,
  //   ...updatedQuiz,
  // };
  // context.setState({ filteredData });
  // context.setState({ tableData: { table_data: filteredData.table_data } });
};

/**
 * edit exam
 * @param {*} item
 * @param {*} context
 */
// const handleSearchFilter = async (context, keyword, childContext) => {
//   const { quizzes_data } = context.state;
//   let filterd_quiz_data = quizzes_data.filter((quiz) =>
//     quiz.taskName.includes(keyword)
//   );
//   //exclude selected quizzes
//   let excludeToFilter = quizzes_data.filter((item) => {
//     return childContext.state.steps_of_exam.includes(item.id);
//   });
//   //get unique values
//   filterd_quiz_data = excludeToFilter
//     .concat(filterd_quiz_data)
//     .filter((value, index, self) => {
//       return self.indexOf(value) === index;
//     });
//   // console.log('handleSearchFilter', filterd_quiz_data)
//   context.setState({
//     filtered_quizzes_data: filterd_quiz_data,
//   });
// };

/**
 * set addmodal visibility
 *
 * @param {*} context
 * @param {*} isVisible
 */
const handleAddModalVisible = (context, isModalOpen) => {
  context.setState({ isAddModalVisible: isModalOpen });
  // console.log('handleAddModalVisible', context.state.quizzes_data)

  //set filtered data to all quiz data
  if (!isModalOpen) {
    context.setState({ filtered_quizzes_data: context.state.quizzes_data });
  }
};

/**
 * add new exam data
 * @param {*} context
 * @param {*} newExam
 */
const addNewData = async (context, newExam, newQuiz) => {
  const { userInfo } = context.props;
  await context.props.addExam(
    { ...newExam, user_id: userInfo.id },
    newQuiz,
    context.props.userToken
  );
  if (context.props.examsList.message.mode === "error") return;
  // const { tableData, filteredData } = context.state;
  updateData(context);
  // newExam.Step_of_Exam = newExam.Step_of_Exam.map((item, index) => {
  //   index = index + 1;
  //   return {
  //     id: context.state.quizzes_data.find((quiz) => quiz.id === item).id,
  //     title:
  //       index +
  //       "." +
  //       context.state.quizzes_data.find((quiz) => quiz.id === item).taskName,
  //     question: context.state.quizzes_data.find((quiz) => quiz.id == item)
  //       .question,
  //     quiz_choices: context.state.quizzes_data
  //       .find((quiz) => quiz.id == item)
  //       .quiz_choices.map((choice) => {
  //         return {
  //           is_correct_answer: choice.is_correct,
  //           title: choice.title != "" ? choice.title : choice.title_en,
  //         };
  //       })
  //       .filter((choice) => choice.title_en != ""),
  //   };
  // });

  // let newExamResult = context.props.examsList.newExamResult || null;
  // if (newExamResult) {
  //   newExam["examId"] = newExamResult.exam ? newExamResult.exam.id : null;
  //   newExam["quizPackId"] = newExamResult.quiz_pack.id;
  // }

  // let exam = {
  //   ...newExam,
  //   ...newQuiz,
  // };

  // filteredData.table_data = [exam, ...tableData.table_data];
  // context.setState({ filteredData });
  // context.setState({ tableData: { table_data: filteredData.table_data } });
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
const handleFetchCsvData = async (context) => {
  const { userToken } = context.props;
  await context.props.getExams(userToken, "all");
  handleFetchData(context, "all");
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
  await context.props.getQuizzes(userToken);
  await context.props.getExams(userToken, isSort ? "all" : currentPage, search_keyword);
  // await context.props.getQuizPackIndex(userToken);
  handleFetchData(context, currentPage);
};

/**
 * get table data on load component
 * @param {*} context
 * @param {*} pagination
 */
const handleFetchData = async (context, pagination) => {
  //for quiz
  // const { quizPackListIndex, isLoading } = context.props.quizPackList;
  const { userInfo } = context.props;
  await context.props.getTableOrder(context.props.userToken, {
    table: "exam management",
    id: userInfo.id,
  });
  const { quizList, isLoading } = context.props.quizzes;
  let quizzes_data = [];
  if (!isLoading) {
    quizzes_data = quizList
      ? quizList.map((item) => {
          return {
            id: item.id,
            taskName: item.title,
            taskName_EN: item.title_en,
            type: "examLists",
            question: item.question_en,
            quiz_choices: item.quiz_choices,
            user: item.user && item.user.name,
            role: item && item.user && item.user.role,
            userInfo: userInfo,
          };
        })
      : [];
    if (context._isMounted) {
      context.setState({
        quizzes_data: JSON.parse(JSON.stringify(quizzes_data)),
        filtered_quizzes_data: quizzes_data,
      });
    }
  }

  //for exams
  // const { filteredData, tableData, tableSort } = context.state;
  let exams = context.props.examsList
    ? JSON.parse(JSON.stringify(context.props.examsList.examList))
    : [];
  // const { tableSort } = context.props.tableSort
  let order = [];
  let sortedExams = exams
    ? exams.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at))
    : [];
  if (context.props.tableSort.tableSort != "no data") {
    order = JSON.parse(context.props.tableSort.tableSort);
    if (order) {
      exams.map((item) => {
        let thisorder = order.find(
          (singleOrder) => item.id == singleOrder.quizPackId
        );
        if (thisorder) item.disp_order = thisorder.disp_order;
        else item.disp_order = "firs";
        if (item.exams) {
          if (item.exams.length != 0) {
            item.author = null;
            if (item.exams[0].exam_author)
              item.author = item.exams[0].exam_author;
          } else item.author = null;
        }
      });
      sortedExams = exams.sort(
        (a, b) => new Date(a.disp_order) - new Date(b.disp_order)
      );
    }
  }
  exams = sortedExams.map((item, key) => {
    let type = null;
    if (Boolean(item.is_public && item.exams.length != 0)) {
      type = 1; // both
    } else if (!Boolean(item.is_public)) {
      type = 2; // exam only
    } else {
      type = 3; // quizzes only
    }
    return {
      //exam
      examId: item.id,
      quizPackId: item.quiz_pack.id,
      // Title: item.title || item.exams.name,
      Step_of_Exam: item.quiz_pack.quizzes
        .map((quiz, index) => {
          return {
            id: quiz.id,
            title: quiz.pivot.disp_order + 1 + "." + quiz.title,
            disp_order: quiz.pivot.disp_order,
            title_en: quiz.pivot.disp_order + 1 + "." + quiz.title_en,
            question: quiz.question_en,
            quiz_choices: quiz.quiz_choices.map((choice) => {
              return {
                is_correct_answer: choice.is_correct,
                title: choice.title_en,
                id: choice.id,
              };
            }),
          };
        })
        .sort((a, b) => a.disp_order - b.disp_order),
      Created: item.created_at,
      exams: item.exams,
      Updated: item.updated_at,
      Enable_Disable: item.is_public == 0 ? "disabled" : "enabled",
      Analytics: "test",
      Edit: "teste",
      Delete: "teset",
      description: item.quiz_pack.description,
      description_en: item.quiz_pack.description_en,
      disp_order: item.disp_order,
      icon_path: item.quiz_pack.icon_path,
      is_public: item.is_public,
      lang: item.lang,
      max_quiz_count: item.quiz_pack.max_quiz_count,
      quiz_order_type: item.quiz_order_type,
      title_color: item.title_color,
      title_en: item.name,
      title_jp: item.name_jp,
      destination_mail: item.result_destination_email,
      exam_release: item.is_publish,
      author: item.author,
      type_exam: type === 1 || type === 2 ? true : false,
      type_quizzes: type === 1 || type === 3 ? true : false,
      user_id: item.user_id,
    };
  });
  if (context.props.tableSort.tableSort != "no data") {
    order = JSON.parse(context.props.tableSort.tableSort);
    if (order) {
      exams.map((item) => {
        let thisorder = order.find(
          (singleOrder) => item.id == singleOrder.quizPackId
        );
        if (thisorder) item.disp_order = thisorder.disp_order;
        else item.disp_order = "first";
      });
      sortedExams = exams.sort(
        (a, b) => new Date(a.disp_order) - new Date(b.disp_order)
      );
    }
  }
  // const param = window.location.search;
  // let drag = param == "?reorder" ? true : context.state.filteredData.dragRow;
  // filteredData.dragRow = drag;
  // filteredData.table_data = exams;
  // filteredData.isLoading = false;

  if (context._isMounted) {
    if (pagination !== "all") {
      context.setState({
        tableData: JSON.parse(
          JSON.stringify({ table_data: exams, isLoading: false })
        ),
        filteredData: JSON.parse(
          JSON.stringify({ table_data: exams, isLoading: false })
        ),
      });
    } else {
      context.setState({
        filteredCsvData: JSON.parse(
          JSON.stringify({ table_data: exams, isLoading: false })
        ),
        isDownloadingCsv: true,
      });
    }
  }
};

/**
 * handle search input on change
 * @param {*} target
 * @param {*} context
 */
const handleSearchChange = async(target, context) => {
  const { filteredData, tableData } = context.state;
  const search_keyword = target.trim().toLowerCase();
  context.setState({ currentPage: 0 ,search_keyword:search_keyword}, () => {
    updateData(context);
  });
};

//===================================================
// Actions
//===================================================
/**
 * delete data row from table
 * @param {*} index
 * @param {*} context
 */
const deleteItem = async (item, index, context) => {
  await context.props.deleteExam(item.examId, context.props.userToken);
  if (
    context.props.examsList &&
    context.props.examsList.message.mode === "error"
  )
    return;
  const { tableData, filteredData } = context.state;
  tableData.table_data.splice(index, 1);
  filteredData.itemIsDeleted = true;
  context.setState({
    tableData,
    filteredData: JSON.parse(JSON.stringify(tableData)),
  });
};

//===================================================
// Redux
//===================================================
const mapStateToProps = (state) => {
  return {
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    userInfo: state.auth.userInfo && state.auth.userInfo.user,
    examsList: state.exams,
    quizzes: state.quizzes,
    quizPackList: state.quizPackList,
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    tableSort: state.tableSort,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, {
  getExams,
  addExam,
  deleteExam,
  updateSort,
  getTableOrder,
  resetExamMessage,
  updateExam,
  getQuizPackIndex,
  getQuizzes,
})(withTranslation("translation")(withRouter(ExamManagement)));
