import React from "react";
import { connect } from "react-redux";

//components
import Div from "../../../presentational/atoms/Div";
import DropdownTable from "../../organisms/DropdownTable";
import Chart from "../../../presentational/molecules/Chart";
import DownloadOptions from "../../../presentational/molecules/DownloadOptions";
import SearchInput from "../../../presentational/molecules/SearchInput/index";

//css
import "./style.css";

//redux
import { getExams } from "../../../../redux/modules/actions/ExamAction";
import { getAllUsers } from "../../../../redux/modules/actions/UserAction";
import { getExamGroup } from "../../../../redux/modules/actions/ExamGroupAction";
import { getExamResults } from "../../../../redux/modules/actions/ExamResultsAction";

// i18next
import { withTranslation } from "react-i18next";

const table_header = [
  "ID",
  "exam_group",
  "exam",
  "user",
  "start_date_time",
  "end_date_time",
  "used_time",
  "answer",
  "ok/miss",
];
//===================================================
// Component
//===================================================
class GraphTable extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      table_data_updated: {
        //data for csv download
        table_data: [],
        isLoading: true,
      },

      filteredData: { table_data: [], isLoading: true }, //data for passing to drodown table component
      tableData: {
        table_data: [],
        isLoading: true,
      },
      data: [],
    };
  }

  async componentDidMount() {
    await this.props.getExams(this.props.userToken);
    await this.props.getAllUsers(this.props.userToken);
    await this.props.getExamGroup(this.props.userToken);
    await this.props.getExamResults(this.props.userToken);
    handleFetchData(this);
  }

  render() {
    const { table_data_updated, filteredData, data } = this.state;
    const { t } = this.props;
    const updateFilteredData = (updatedData) => {
      this.setState({
        table_data_updated: {
          table_data: updatedData,
        },
      });
    };

    return (
      <Div className="template-GraphTable-wrapper">
        <Div className="TestAnalysis-top">
          <Div className="SearchWithDownloadOption">
            <SearchInput onChange={(e) => handleChange(e, this)} />
            <DownloadOptions
              data={table_data_updated}
              csv_mode="test-analytics"
              filename="Exam Analytics"
            />
          </Div>
        </Div>
        <DropdownTable
          t={t}
          table_header={table_header}
          data={filteredData}
          onUpdatedData={updateFilteredData}
          updateChart={(value) => updatingChart(this, value)}
          exam_id={this.props.exam_id}
          searchText={this.state.searchText}
        />
        <Chart chartData={data} />
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================

/**
 * update chart data
 * @param {*} context
 * @param {*} value
 */
const updatingChart = (context, value) => {
  const data = [];
  value.map((value) => {
    data.push({
      exam_group: value.exam_group,
      UserA_answer: value.answer,
      UserB_answer: value.answer,
    });
  });
  context.setState({
    data: data,
    table_data_updated: value,
  });
};

/**
 * get table data
 * @param {*} context
 */
const handleFetchData = (context) => {
  const { filteredData } = context.state;
  let examsResults = [];
  if (context.props.examResults.examResultList) {
    examsResults = context.props.examResults.examResultList
      .map((item) => {
        const university = context.props.examGroup.examGroupList.find(
          (univ) =>
            univ.id ===
            (item.exam
              ? item.exam.hasOwnProperty("university_id")
                ? item.exam.university_id
                : ""
              : "")
        );
        return {
          id: item.id,
          exam_group: item.exam ? (university ? university.name : "") : "",
          university_id: item.exam
            ? item.exam.hasOwnProperty("university_id")
              ? item.exam.university_id
              : null
            : null,
          exam: item.exam ? item.exam.name : "",
          exam_data: item.exam,
          user_data: item.account,
          user: item.account.user,
          start_datetime: item.start_time,
          end_datetime: item.finish_time,
          used_time: item.used_time,
          answer: item.quiz_choice ? item.quiz_choice.title : "",
          miss_ok: item.quiz_choice
            ? Boolean(item.quiz_choice.is_correct)
              ? "ok"
              : "miss"
            : "no answer",
        };
      })
      .filter((i) => i.exam_data && i.user_data);
  }
  context.setState({
    tableData: {
      table_data: JSON.parse(JSON.stringify(examsResults)),
      isLoading: false,
    },
    table_data_updated: { table_data: examsResults, isLoading: false },
  });
  filteredData.table_data = examsResults;
  filteredData.isLoading = false;
  context.setState({ filteredData });
};

/**
 * filter data on text change.
 * @param {*} e
 * @param {*} context
 */
const handleChange = (e, context) => {
  const searchText = e.target.value.trim().toLowerCase();
  // const { tableData, filteredData } = context.state;
  // filteredData.table_data = tableData.table_data.filter(
  //   (item) =>
  //     item.id.toString().includes(searchText) ||
  //     item.exam_group.toLowerCase().includes(searchText) ||
  //     item.exam.toLowerCase().includes(searchText) ||
  //     item.user.toLowerCase().includes(searchText) ||
  //     item.miss_ok.toLowerCase().includes(searchText)
  // );
  // let table_data_updated = filteredData;
  // context.setState({ filteredData, table_data_updated });
  context.setState({ searchText });
};

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================
const mapStateToProps = (state) => {
  return {
    exams: state.exams,
    users: state.userManagement,
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    examGroup: state.examGroup,
    examResults: state.examResults,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, {
  getExams,
  getAllUsers,
  getExamGroup,
  getExamResults,
})(withTranslation("translation")(GraphTable));
