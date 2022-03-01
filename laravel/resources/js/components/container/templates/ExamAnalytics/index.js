import React from "react";
import ReactDOM from "react-dom";

//libs
import { connect } from "react-redux";

//components
import Div from "../../../presentational/atoms/Div";
import TitleWithDropDown from "../../../presentational/molecules/TitleWithDropDown";
import ExamAnalyticsTable from "../../organisms/ExamAnalyticsTable";
import LineChart from "../../organisms/LineChart";
import Label from "../../../presentational/atoms/Label";
import RadioWithLabel from "../../../presentational/molecules/RadioWithLabel";
import DownloadOptions from "../../../presentational/molecules/DownloadOptions";
import DatePicker from "../../organisms/DatePicker.js";
import { oneMonthAgo } from "../../../../common/Date.js";

// redux
import {
  getExamResults,
  getChartAnalyticsData,
  getExamAnalyticsMenu,
} from "../../../../redux/modules/actions/ExamResultsAction";

//css
import "./style.css";

//i18
import { withTranslation } from "react-i18next";
import i18next from "i18next";
//===================================================
// Component
//===================================================
class ExamAnalytics extends React.Component {
  _isMounted = false;
  constructor(props) {
    super(props);
    this.containerRef = React.createRef();
    this.state = {
      univDataSets: [],
      userDataSets: [],
      chartType: "user",
      dataList: { table_data: [], isLoading: true },
      filteredList: { table_data: [], isLoading: true },
      selected_univ: null,
      selected_user: null,
      selected_type: null,
      selected_title: null,
      passed_title:
        this.props.query_params && this.props.query_params.value
          ? this.props.query_params.value
          : null,
      sortedHeader: null,
      isAscending: false,
      isDownloadingCsv: false,
      dropdownData: {
        group: [],
        users: [],
        type: [],
        title: [],
      },
      dateRange: {
        startDate: new Date(),
        endDate: new Date(),
        key: "selection",
      },
      isDatePickerVisible: false,
      isDateSet: false,
      isStartDatePickerVisible: false,
      isEndDatePickerVisible: false,
      startDateSet: oneMonthAgo(),
      endDateSet: new Date(),
      isChartLoading: true,
    };
  }

  componentDidMount() {
    this._isMounted = true;
    handleFetchData(this);
  }

  componentWillUnmount() {
    this._isMounted = false;
  }

  componentDidUpdate(prevProps) {
    if (this.props.t !== prevProps.t) {
      let analyticsList = this.props.examMenuList
      ? JSON.parse(JSON.stringify(this.props.examMenuList))
      : [];
      handleGetDropDownData(analyticsList, this);
    }
  }

  render() {
    const {
      chartType,
      filteredList,
      univDataSets,
      userDataSets,
      dropdownData,
      isDownloadingCsv,
      isStartDatePickerVisible,
      isEndDatePickerVisible,
      startDateSet,
      endDateSet,
      isChartLoading,
    } = this.state;
    const { t, userInfo } = this.props;
    return (
      <Div className="template-examAnalytics-wrapper">
        <Div>
          <Label className="organism-chart-type">{t("chart_type")}</Label>
          <RadioWithLabel
            title={t("user")}
            name={"chartType"}
            value={"user"}
            defaultChecked={chartType === "user" ? "checked" : ""}
            onClick={(event) => handleSetRadioClicked(event, this)}
          />
          <RadioWithLabel
            title={t("group")}
            name={"chartType"}
            value={"group"}
            defaultChecked={chartType === "group" ? "checked" : ""}
            onClick={(event) => handleSetRadioClicked(event, this)}
          />
        </Div>
        <Div className="templates-dropdown">
          <Div className="templates-dropdown-wrapper">
            {userInfo.role === 101 && (
              <TitleWithDropDown
                dropDownList={[
                  { value: "All", id: null },
                  ...dropdownData.group,
                ]}
                onDropDownListClicked={(value) =>
                  handleSelectedDropdown(value, this, "univ")
                }
                title={t("group")}
              />
            )}
            <Div className="wide-dropdown">
              <TitleWithDropDown
                dropDownList={[
                  { value: "All", id: null },
                  ...dropdownData.users,
                ]}
                onDropDownListClicked={(value) =>
                  handleSelectedDropdown(value, this, "user")
                }
                title={t("user")}
              />
            </Div>
            <TitleWithDropDown
              dropDownList={[{ value: "All", id: null }, ...dropdownData.type]}
              title={t("type")}
              selected={getDefaultFilters(this, "type")}
              onDropDownListClicked={(value) =>
                handleSelectedDropdown(value, this, "type")
              }
            />
            <Div className="wide-dropdown">
              <TitleWithDropDown
                dropDownList={[
                  { value: "All", id: null },
                  ...dropdownData.title,
                ]}
                title={t("title")}
                selected={getDefaultFilters(this, "title")}
                onDropDownListClicked={(value) =>
                  handleSelectedDropdown(value, this, "title")
                }
              />
            </Div>
          </Div>
          <Div className="dateWithDownloadWrapper">
            <Div className="template-datePicker" ref={this.containerRef}>
              <DatePicker
                title="start"
                isPickerVisible={isStartDatePickerVisible}
                initialDate={startDateSet}
                onDatePickerClicked={() =>
                  this.setState({
                    isEndDatePickerVisible: false,
                    isStartDatePickerVisible: !isStartDatePickerVisible,
                  })
                }
                handleDateChanged={(item) => {
                  this.setState({
                    startDateSet: item,
                    isStartDatePickerVisible: false,
                    isEndDatePickerVisible: true,
                  });
                }}
                lastDate={startDateSet}
                handleOutsidePickerClick={(event) =>
                  handleOutsidePickerClick(event, this)
                }
                datePickerdomNode={this.containerRef.current}
              />
              <DatePicker
                title="end"
                isPickerVisible={isEndDatePickerVisible}
                initialDate={endDateSet}
                onDatePickerClicked={() =>
                  this.setState({
                    isStartDatePickerVisible: false,
                    isEndDatePickerVisible: !isEndDatePickerVisible,
                  })
                }
                handleDateChanged={(item) => {
                  this.setState(
                    {
                      endDateSet: item,
                      isDateSet: true,
                      isChartLoading: true,
                    },
                    () => handleDateFilter(this)
                  );
                }}
                lastDate={startDateSet}
                handleOutsidePickerClick={(event) =>
                  handleOutsidePickerClick(event, this)
                }
                datePickerdomNode={this.containerRef.current}
              />
            </Div>
            <DownloadOptions
              data={{ table_data: filteredList }}
              csv_mode={"exam_analytics_csv"}
              filename="Exam Analytics Data"
              isDownloading={isDownloadingCsv}
              setDownloadCsv={(isDownload) => setDownloadCsv(this, isDownload)}
              handleFetchCsvData={() => setDownloadCsv(this, true)}
              chartType={chartType}
            />
          </Div>
        </Div>
        {/* <CustomDatePicker
              onPickerClicked={() =>
                this.setState({
                  isDatePickerVisible: !isDatePickerVisible,
                })
              }
              isDatePickerVisible={isDatePickerVisible}
              handleRangeChanged={(item) =>
                this.setState({ dateRange: item.selection, isDateSet: true })
              }
              dateRange={dateRange}
              handleClosePicker={() => handleFilterByDate(this)}
            /> */}
        <ExamAnalyticsTable
          data={filteredList}
          handleSortTable={(header) => handleSortTable(header, this)}
          chartType={chartType}
        />
        <LineChart
          onRadioClick={(e) => handleSetRadioClicked(e, this)}
          no_legend={true}
          datasets={chartType === "group" ? univDataSets : userDataSets}
          chartType={chartType}
          isChartLoading={
            isChartLoading || isStartDatePickerVisible || isEndDatePickerVisible
          }
        />
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================
const handleOutsidePickerClick = (e, context) => {
  context.setState({
    isStartDatePickerVisible: false,
    isEndDatePickerVisible: false,
  });
};

/**
 * get selected radio item
 * @param {*} e
 * @param {*} context
 */
const handleSetRadioClicked = (e, context) => {
  const { chartType } = context.state;
  context.setState({ isChartLoading: true });
  if (chartType !== e.target.value) {
    context.setState({ chartType: e.target.value }, () => {
      handleFetchChartData(context);
    });
  }
  handleSetAnalyticsValues(context, context.state.chartType);
};

/**
 * Set state from selected item from dropdown
 * @param {*} value
 * @param {*} context
 */
const handleSelectedDropdown = (value, context, type) => {
  if (context.props.userInfo.role === 101 && type === "univ") {
    let groupUsers =
      context.props.examMenuList.users &&
      context.props.examMenuList.users
        .filter((item) => {
          if (value) return item.univ == value;
          else return true;
        })
        .map((i) => {
          return { value: i.user_name, id: i.user_id };
        });
    let groupTitles =
      context.props.examMenuList.title &&
      context.props.examMenuList.title
        .filter((item) => {
          if (value) {
            if(item.univ_id && item.univ_id.length != 0 ) {
              return item.univ_id.includes(value);
            }
            else return true;
          }
          else return true;
        })
        .map((i) => {
          return { value: i.exam_title, id: i.exam_id};
        });
    console.log(groupTitles);
    context.setState({
      dropdownData: {
        group: context.state.dropdownData.group,
        type: context.state.dropdownData.type,
        title: groupTitles.length
          ? groupTitles
          : context.state.dropdownData.title,
        users: groupUsers.length
          ? groupUsers
          : context.state.dropdownData.users,
      },
    });
  }
  context.setState(
    {
      [`selected_${type}`]: value,
      isChartLoading: true,
    },
    () => {
      handleSelectedDropdownFilter(context);
      // const univResponse = handleUnivGraphData(context);
      // const userResponse = handleUserGraphData(context);
      // context.setState({
      //   univDataSets: univResponse,
      //   userDataSets: userResponse,
      // });
      handleFetchChartData(context);
    }
  );
};

/**
 * Set selected item from dropdown
 * @param {*} value
 * @param {*} context
 */
const handleSelectedDropdownFilter = async(context) => {
  // const {
  //   dataList,
  //   selected_univ,
  //   selected_user,
  //   selected_type,
  //   selected_title,
  // } = context.state;
  // const table_data = dataList.table_data.filter(
  //   (item) =>
  //     (selected_univ != null ? item.group_id == selected_univ : true) &&
  //     (selected_user != null ? item.user_id == selected_user : true) &&
  //     (selected_type != null ? item.type_id == selected_type : true) &&
  //     (selected_title != null ? item.exam_id == selected_title : true)
  // );
  // context.setState({
  //   filteredList: { table_data: table_data, isLoading: false },
  // });
  await handleFetchTableData(context);
  await handleSetAnalyticsValues(context);
};

/**
 * Fetch exam analytics data
 * @param {*} context
 */
const handleFetchData = async (context) => {
  const { getExamAnalyticsMenu, userToken } = context.props;
  if(context.props.query_params) {
    const value = context.props.query_params.value ? "1_"+context.props.query_params.value : null;
    context.setState(
      {
        selected_type:1,
        selected_title: value,
        startDateSet: new Date(context.props.query_params.created),
      },
      async() => {
        await handleSelectedDropdownFilter(context);
        await handleFetchChartData(context);
      }
    );
  }else{
    await handleFetchTableData(context);
    await handleFetchChartData(context);
  }
  await getExamAnalyticsMenu(userToken);
  await handleSetAnalyticsValues(context);
};

/**
 * Set values in state
 * @param {*} context
 */
const handleSetAnalyticsValues = async (context, chart) => {
  if (context._isMounted) {
    const {
      examAnalyticsList,
      examAnalyticsGroup,
      examMenuList,
    } = context.props;
    var data = chart === "group" ? examAnalyticsList : examAnalyticsGroup;
    const originalList = data ? JSON.parse(JSON.stringify(data)) : [];
    // const {
    //   examAnalyticsList,
    //  examAnalyticsGroup,
    //  examMenuList,
    // } = context.props;

    // var data = chart === "group" ? examAnalyticsList : examAnalyticsGroup;
    // const originalList = data ? JSON.parse(JSON.stringify(data)) : [];
    let analyticsList = examMenuList
      ? JSON.parse(JSON.stringify(examMenuList))
      : [];
    await handleGetDropDownData(analyticsList, context);
    const tableList = getTableList(originalList, context);
    context.setState({
      dataList: {
        table_data: JSON.parse(JSON.stringify(tableList)),
        isLoading: false,
      },
      filteredList: { table_data: tableList, isLoading: false },
    });
  }
};

/**
 * Get date in YYYY/MM/DD format
 * @param {*} item
 * @returns
 */
const dateFormatter = (item) => {
  var month = item.getMonth() + 1;
  if (item.getMonth() < 9) month = "0" + (item.getMonth() + 1);
  const result = item.getFullYear() + "/" + month + "/" + item.getDate();
  return result;
};
/**
 * Fetch exam log data
 * @param {*} context
 */
const handleFetchTableData = async (context) => {
  const { getExamResults, userToken, userInfo } = context.props;
  const {
    selected_univ,
    selected_user,
    selected_type,
    selected_title,
    startDateSet,
    endDateSet,
  } = context.state;
  const startDateValue = dateFormatter(startDateSet);
  const endDateValue = dateFormatter(endDateSet);
  await getExamResults(userToken, {
    user_univ_id: userInfo.university_id,
    user_login_id: userInfo.id,
    page: 0,
    user_id: selected_user,
    exam_id: selected_title,
    univ_id: selected_univ,
    start_date: startDateValue,
    end_date: endDateValue,
    type: selected_type,
  });
};

/**
 * Fetch chart data
 * @param {*} context
 */
const handleFetchChartData = async (context) => {
  const { userToken, getChartAnalyticsData, userInfo } = context.props;
  const {
    chartType,
    univDataSets,
    userDataSets,
    selected_univ,
    selected_user,
    selected_type,
    endDateSet,
    startDateSet,
    selected_title,
  } = context.state;
  const startDateValue = dateFormatter(startDateSet);
  const endDateValue = dateFormatter(endDateSet);
  await getChartAnalyticsData(userToken, {
    user_univ_id: userInfo.university_id,
    user_login_id: userInfo.id,
    chart: chartType,
    univ_id: selected_univ,
    user_id: selected_user,
    type: selected_type,
    type_title: selected_title,
    srt_date: startDateValue,
    end_date: endDateValue,
  });
  if (context._isMounted) {
    const univResponse =
      chartType === "group" ? handleUnivGraphData(context) : univDataSets;
    const userResponse =
      chartType === "user" ? handleUserGraphData(context) : userDataSets;
    context.setState({
      univDataSets: univResponse,
      userDataSets: userResponse,
      isChartLoading: false,
    });
  }
};

/**
 * get dropdown list
 * @param {*} analytics
 * @param {*} context
 */
const handleGetDropDownData = async (analytics, context) => {
  let group = [];
  let users = [];
  let type = [];
  let title = [];
  if (analytics.group) {
    analytics.group.map((item) =>
      group.push({ value: item.group_name, id: item.group_id })
    );
  }
  if (analytics.users) {
    analytics.users.map((item) => {
      users.push({ value: item.user_name, id: item.user_id });
    });
  }
  if (analytics.type) {
    analytics.type.map((item) => type.push({ value: item.type, id: item.id }));
  }
  if (analytics.title) {
    analytics.title.map((item) => {
      if (item.exam_id)
        title.push({ value: i18next.language === "ja" ? item.exam_title : item.exam_title_en, id: item.exam_id });
      if (item.quiz_id)
        title.push({ value: i18next.language === "ja" ? item.quiz_title : item.quiz_title_en, id: item.quiz_id });
    });
  }
  if (context._isMounted) {
    context.setState({
      dropdownData: {
        group: await filteredUnique(group),
        users: await filteredUnique(users),
        type: await filteredUnique(type),
        title: await filteredUnique(title),
      },
    });
  }
};

/**
 * get user graph data
 * @param {*} context
 */
const handleUserGraphData = (context) => {
  const chartData = context.props.chartDataList;
  const { dropdownData, selected_user } = context.state;
  const userData = chartData && chartData.user;
  const selectedUser = dropdownData.users.find(
    (item) => item.id === selected_user
  );
  return handleGetModifiedChartData(
    userData,
    selectedUser ? selectedUser.id : null
  );
};

/**
 * get univ graph data
 * @param {*} context
 */
const handleUnivGraphData = (context) => {
  const chartData = context.props.chartDataList;
  const { dropdownData, selected_univ } = context.state;
  const univData = chartData && chartData.group;
  const selectedUniv = dropdownData.group.find(
    (item) => item.id === selected_univ
  );
  return handleGetModifiedChartData(
    univData,
    selectedUniv ? selectedUniv.id : null
  );
};

/**
 * Get chart data values
 * @param {*} userData
 * @returns
 */
const handleGetModifiedChartData = (userData, selectedItem) => {
  const chartData = userData && Object.keys(userData);
  let allUsers = [];
  chartData &&
    chartData.map((label, index) => {
      const randomColor = getRandomColor();
      allUsers = [
        ...allUsers,
        {
          ...userData[label],
          data: [userData[label]["data"]],
          backgroundColor: randomColor,
          borderColor: randomColor,
          pointRadius: 4,
          pointHoverRadius: 8,
        },
      ];
    });
  let uniqueUsers = allUsers;
  // if (selectedItem !== null) {
  //   uniqueUsers = allUsers.filter((item) => item.id === selectedItem);
  // }
  return uniqueUsers;
};

/**
 * get Random color
 *
 */
const getRandomColor = () => {
  for (let i = 0; i < 7; i++) {
    let letters = "0123456789ABCDEF";
    let color = "#";
    for (let j = 0; j < 6; j++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
  }
};

/**
 * get table data
 * @param {*} originalList
 * @returns
 */
const getTableList = (originalList) => {
  let tableList = [];
  originalList.map((value) => {
    tableList = [
      ...tableList,
      {
        group_name: value.group_name,
        user_name: value.user_name,
        exam_title: value.exam_title || "-",
        number_of_question: value.number_of_question || 0,
        correct_answers: value.correct_answers || 0,
        average_time: value.average_time,
        rate: value.rate,
        user_id: value.user_id,
        log_id: value.log_id,
        group_id: value.group_id,
        exam_id: value.exam_id,
        type_id: value.type,
      },
    ];
  });
  return tableList;
};

/**
 *
 * @param {*} cfilterontext
 */
const filteredUnique = (filter) => {
  let data = filter.reduce((accumulator, current) => {
    if (!accumulator.some((x) => x.id === current.id)) {
      accumulator.push(current);
    }
    return accumulator;
  }, []);
  return data;
};

/**
 * sort table using header item
 * @param {*} headerItem
 * @param {*} context
 */
const handleSortTable = (headerItem, context) => {
  const { sortedHeader, isAscending } = context.state;
  if (sortedHeader === headerItem) {
    context.setState({ isAscending: !isAscending, isChartLoading: true });
    sortList(headerItem, !isAscending, context);
  } else {
    context.setState({
      isAscending: true,
      sortedHeader: headerItem,
      isChartLoading: true,
    });
    sortList(headerItem, true, context);
  }
};

/**
 * sort ascending or descending
 * @param {*} headerItem
 * @param {*} isAscending
 * @param {*} context
 */
const sortList = (headerItem, isAscending, context) => {
  const {
    filteredList: { table_data },
  } = context.state;
  let sortedList = [];
  if (isAscending) {
    sortedList = table_data.sort((a, b) => {
      let itemA = isNaN(a[headerItem])
        ? a[headerItem].toUpperCase()
        : a[headerItem];
      let itemB = isNaN(b[headerItem])
        ? b[headerItem].toUpperCase()
        : b[headerItem];
      if (itemA > itemB) {
        return 1;
      }
      if (itemA < itemB) {
        return -1;
      }
      return 0;
    });
  } else {
    sortedList = table_data.sort((a, b) => {
      let itemA = isNaN(a[headerItem])
        ? a[headerItem].toUpperCase()
        : a[headerItem];
      let itemB = isNaN(b[headerItem])
        ? b[headerItem].toUpperCase()
        : b[headerItem];
      if (itemA > itemB) {
        return -1;
      }
      if (itemA < itemB) {
        return +1;
      }
      return 0;
    });
  }
  context.setState(
    {
      filteredList: { isLoading: false, table_data: sortedList },
    },
    () => context.setState({ isChartLoading: false })
  );
};

/**
 * set download csv true / false
 * @param {*} context
 * @param {*} isDownload
 */
const setDownloadCsv = (context, isDownload) => {
  const { isChartLoading } = context.state;
  context.setState({
    isDownloadingCsv: isDownload,
    isChartLoading: !isChartLoading,
  });
};

/**
 * Close modal and filter table data
 * @param {*} context
 */
// const handleFilterByDate = async (context) => {
//   const { isDatePickerVisible, dateRange } = context.state;
//   if (isDatePickerVisible) {
//     context.setState({ isDatePickerVisible: false });
//     await handleFetchTableData(context);
//     handleSetAnalyticsValues(context);
//   }
// };

/**
 * Close modal and filter table by date
 * @param {*} context
 */
const handleDateFilter = async (context) => {
  context.setState({
    isEndDatePickerVisible: false,
    filteredList: { table_data: [], isLoading: true },
  });
  await handleFetchTableData(context);
  handleSetAnalyticsValues(context);
  handleFetchChartData(context);
};

/**
 * get type value
 * @param {*} type
 * @param {*} context
 */
const getType = (type, context) => {
  const { t } = context.props;
  switch (type) {
    case t("Exam"):
      return 1;
    case t("Quizzes"):
      return 2;
    default:
      return null;
  }
};
/**
 * get default filters
 * @param {*} context
 * @param {*} type
 */
const getDefaultFilters = (context, type) => {
  let selected = null;
  switch (type) {
    case "title":
      selected = context.state.dropdownData.title.find(
        ({ id }) => id === context.state.selected_title
      );
      return selected ? selected.value : null;
    case "type":
      selected = context.state.dropdownData.type.find(
        ({ id }) => id === context.state.selected_type
      );
      return selected ? selected.value : null;
    default:
      return null;
  }
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
    examAnalyticsList: state.examResults.examResultList,
    examAnalyticsGroup: state.examResults.grouptList,
    chartDataList: state.examResults.chartDataList,
    examMenuList: state.examResults.examMenuList,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, {
  getExamResults,
  getChartAnalyticsData,
  getExamAnalyticsMenu,
})(withTranslation("translation")(ExamAnalytics));
