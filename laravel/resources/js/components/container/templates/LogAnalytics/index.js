import React from "react";
import ReactDOM from "react-dom";

// libs
import { connect } from "react-redux";

//components
import Div from "../../../presentational/atoms/Div";
import TargetTotalizeTable from "../../../presentational/molecules/TargetTotalizeTable";
import LogAnalyticsIconContainer from "../../../presentational/molecules/LogAnalyticsIconContainer";
import DownloadOptions from "../../../presentational/molecules/DownloadOptions";
import LogAnalyticsTable from "../../organisms/LogAnalyticsTable";
import LogAnalyticsDropdown from "../../organisms/LogAnalyticsDropdown";
import RankingModal from "../../organisms/RankingModal";
import PieChartModal from "../../organisms/PieChartModal";
import DatePicker from "../../organisms/DatePicker.js";
import { oneMonthAgo } from "../../../../common/Date.js";

//redux
import { getLibraryUser } from "../../../../redux/modules/actions/LibraryUserAction";
import {
  getSelectMenu,
  getLogAnalytic,
  getTargetTable,
  getRankingData,
  getPieChartData,
} from "../../../../redux/modules/actions/LogAnalyticAction";

//css
import "./style.css";

//i18
import { withTranslation } from "react-i18next";
import i18next from "i18next";

//===================================================
// Component
//===================================================
class LogAnalytics extends React.Component {
  _isMounted = false;
  constructor(props) {
    super(props);
    this.containerRef = React.createRef();
    this.state = {
      isRankingModalVisible: false,
      isPieChartModalVisible: false,
      dropdownData: {
        user: [],
        univ: [],
        exam: [],
        quiz: [],
        exam_type: [],
        library: [],
      },
      sortedHeader: null,
      isAscending: false,
      initialList: null,
      selectedDropDowns: {
        user: null,
        univ: null,
        exam: null,
        quiz: null,
        exam_type: null,
        library: null,
      },
      filteredList: null,
      originalLogList: null,
      pieChartData: null,
      isTargetLoading: true,
      isLogLoading: true,
      isPieLoading: true,
      isDownloadingCsv: false,
      isDatePickerVisible: false,
      isDateSet: false,
      startDateSet: oneMonthAgo(),
      isStartDatePickerVisible: false,
      endDateSet: new Date(),
      isEndDatePickerVisible: false,
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
      let analytics = this.props.logAnalytic.logAnalyticsList
        ? JSON.parse(JSON.stringify(this.props.logAnalytic.logAnalyticsList))
        : [];
      handleGetDropDownData(analytics, this);
    }
  }

  render() {
    const { userInfo, filteredTargetList, rankingList } = this.props;
    const {
      isRankingModalVisible,
      isPieChartModalVisible,
      dropdownData,
      selectedDropDowns,
      filteredList,
      pieChartData,
      isTargetLoading,
      isLogLoading,
      isPieLoading,
      isDownloadingCsv,
      startDateSet,
      isStartDatePickerVisible,
      endDateSet,
      isEndDatePickerVisible,
    } = this.state;
    return (
      <Div className="template-logAnalytics-wrapper">
        <Div className="template-logAnalytics-main_dropdown_wrapper">
        <LogAnalyticsDropdown
          userInfo={userInfo}
          dropDownData={dropdownData}
          selectedDropDowns={selectedDropDowns}
          handleSelectedDropdownItem={(value, type) =>
            handleSelectDropDownValue(value, type, this)
          }
        />
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
                isEndDatePickerVisible: !isEndDatePickerVisible,
                isStartDatePickerVisible: false,
              })
            }
            handleDateChanged={(item) => {
              this.setState(
                {
                  endDateSet: item,
                  isDateSet: true,
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
        </Div>
        <LogAnalyticsIconContainer
          handleOpenRankingModal={() => handleToggleRankingModal(this)}
          handleOpenPieChartModal={() => handleTogglePieChartModal(this)}
        />
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
        <TargetTotalizeTable
          isTargetLoading={isTargetLoading}
          filteredTargetList={filteredTargetList}
        />
        <Div className="template-logAnalytics-csv">
          <DownloadOptions
            data={{ table_data: filteredList }}
            csv_mode={"target_totalize"}
            filename="Log Analytics Data"
            isDownloading={isDownloadingCsv}
            setDownloadCsv={(isDownload) => setDownloadCsv(this, isDownload)}
            handleFetchCsvData={() => setDownloadCsv(this, true)}
          />
        </Div>
        <LogAnalyticsTable
          tableData={filteredList}
          isLogLoading={isLogLoading}
          handleSortList={(headerItem) => handleSortList(headerItem, this)}
        />
        {isRankingModalVisible && (
          <RankingModal
            rankingList={rankingList}
            isVisible={isRankingModalVisible}
            onHideRankingModal={() => handleToggleRankingModal(this)}
            handleGetRankingData={() => handleFetchRankingModalData(this)}
          />
        )}
        {isPieChartModalVisible && (
          <PieChartModal
            isVisible={isPieChartModalVisible}
            handleFetchPieModalData={() => handleFetchPieModalData(this)}
            onHidePieChartModal={() => handleTogglePieChartModal(this)}
            pieChartData={pieChartData}
            isPieLoading={isPieLoading}
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
 * Get log table data
 * @param {*} context
 */
const handleGetLogTable = async (context) => {
  const { userToken, getLogAnalytic, userInfo } = context.props;
  const { isDateSet, startDateSet, endDateSet, selectedDropDowns } = context.state;
  const startDateValue = dateFormatter(startDateSet);
  const endDateValue = dateFormatter(endDateSet);
  if (context._isMounted) {
    context.setState({
      isLogLoading: true,
    });
  }
  await getLogAnalytic(userToken, {
    page: 0,
    start_date: startDateValue,
    end_date: endDateValue,
    user_id: selectedDropDowns.user || null,
    univ_id: selectedDropDowns.univ || null,
    exam_type: selectedDropDowns.exam_type || null,
    exam_id: selectedDropDowns.exam || null,
    quiz_id: selectedDropDowns.quiz || null,
    library_id: selectedDropDowns.library || null,
  });
  let analytics = context.props.logAnalytic.logAnalyticsList
    ? JSON.parse(JSON.stringify(context.props.logAnalytic.logAnalyticsList))
    : [];
  if (context._isMounted) {
    context.setState({
      initialList: analytics,
    });
  }
  handleSetLogTableData(analytics, context);
};

/**
 * get table data on load component
 * @param {*} context
 */
const handleFetchData = async (context) => {
  const { userToken, getLibraryUser } = context.props;
  await getLibraryUser(userToken);
  handleGetTargetTotalizeTable(context);
  handleGetLogTable(context);
  let analytics = context.props.logAnalytic.logAnalyticsList
    ? JSON.parse(JSON.stringify(context.props.logAnalytic.logAnalyticsList))
    : [];
  handleGetDropDownData(analytics, context);
};

//===================================================
// Functions
//===================================================
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
 * Fetch pie chart data
 * @param {*} context
 */
const handleFetchPieModalData = async (context) => {
  const { userToken, userInfo, getPieChartData } = context.props;
  const { dateRange, isDateSet, startDateSet, endDateSet } = context.state;
  const startDateValue = dateFormatter(startDateSet);
  const endDateValue = dateFormatter(endDateSet);
  if (context._isMounted) {
    context.setState({
      isPieLoading: true,
    });
  }
  await getPieChartData(
    {
      user_univ_id: userInfo.university_id,
      user_login_id: userInfo.id,
      start_date: isDateSet ? startDateValue : null,
      end_date: isDateSet ? endDateValue : null,
    },
    userToken
  );
  handlePieChartData(context);
};

/**
 * Modify pie chart data
 * @param {*} context
 */
const handlePieChartData = (context) => {
  const { pieChartList } = context.props;
  let dataSets = [];
  if (pieChartList) {
    const piechartTitles = Object.keys(pieChartList);
    piechartTitles.map((item) => {
      let filteredData = [];
      let filteredLabels = [];
      filteredLabels = pieChartList[item]
        ? Object.keys(pieChartList[item])
        : [];
      filteredLabels.length > 0 &&
        filteredLabels.map((label) => {
          filteredData = [
            ...filteredData,
            pieChartList[item][label].split("%")[0],
          ];
        });
      const colorList = [
        "#E55E82",
        "#5ba954",
        "#eb8f3d",
        "#e44134",
        "#f6cc55",
        "#4ba2eb",
      ];
      dataSets = [
        ...dataSets,
        {
          title: item,
          data: {
            labels: filteredLabels,
            datasets: [
              {
                data: filteredData,
                backgroundColor: colorList,
                hoverBackgroundColor: colorList,
              },
            ],
          },
        },
      ];
    });
  }
  if (context._isMounted) {
    context.setState({ pieChartData: dataSets, isPieLoading: false });
  }
};

/**
 * Get ranking modal data
 * @param {*} context
 */
const handleFetchRankingModalData = (context) => {
  const { getRankingData, userInfo, userToken } = context.props;
  const { dateRange, isDateSet, startDateSet, endDateSet,selectedDropDowns } = context.state;
  const startDateValue = dateFormatter(startDateSet);
  const endDateValue = dateFormatter(endDateSet);
  getRankingData(
    {
      user_id: selectedDropDowns.user || null,
      univ_id: selectedDropDowns.univ || null,
      exam_type: selectedDropDowns.exam_type || null,
      exam_id: selectedDropDowns.exam || null,
      quiz_id: selectedDropDowns.quiz || null,
      library_id: selectedDropDowns.library || null,
      user_login_id: userInfo.id,
      user_univ_id: userInfo.university_id,
      start_date: startDateValue,
      end_date: endDateValue,
    },
    userToken
  );
};

const handleOutsidePickerClick = (e, context) => {
  context.setState({
    isStartDatePickerVisible: false,
    isEndDatePickerVisible: false,
  });
};

/**
 * set log analytics table list data
 * @param {*} logList
 * @param {*} context
 */
const handleSetLogTableData = async (logList, context) => {
  if (logList) {
    const examTypes = Object.keys(logList);
    const { t, getSelectMenu, userToken } = context.props;
    await getSelectMenu(userToken);
    const menuLibrary =
      context.props.logAnalyticMenu && context.props.logAnalyticMenu.library;
    let tableLists = [];
    examTypes &&
      examTypes.map((type, i) => {
        return logList[type].map((value, index) => {
          let library=[];
          let library_type=[];
          const library_type_strings = ["Stetho","Aus","Palp","ECG","Ins","Xray","UCG"];
          if (type === "library") {
            library = [value.name];
            library_type=[value.library_type];
          }else{
            value.exam_libraries.map((exam_lib,index)=>{
              library.push(exam_lib.title_en);
              library_type.push(library_type_strings[exam_lib.lib_type]);
            });
          }
          tableLists = [
            ...tableLists,
            {
              index: index,
              id: value.id,
              log_id: value.log_id,
              univ: value.univ_name || "-",
              user: value.author || "-",
              exam_type: t(type),
              exam: value.exam_name || "-",
              quiz: value.quiz_name || "-",
              library: library,
              library_type: library_type,
              correct: value.num_correct_exam || 0,
              univ_id: value.univ,
              user_id: value.author_id,
              exam_id: value.exam_id || 0,
              quiz_id: value.quiz_id || 0,
              used_time: value.used_time
            },
          ];
        });
      });
    if (context._isMounted) {
      context.setState({
        filteredList: tableLists,
        originalLogList: JSON.parse(JSON.stringify(tableLists)),
        isLogLoading: false,
      });
    }
  }
};

/**
 * get dropdown list
 * @param {*} analytics
 * @param {*} context
 */
const handleGetDropDownData = async (analytics, context) => {
  const { userToken, getSelectMenu } = context.props;
  await getSelectMenu(userToken);
  const menuExamType =
    context.props.logAnalyticMenu && context.props.logAnalyticMenu.exam_type;
  const menuLibrary =
    context.props.logAnalyticMenu && context.props.logAnalyticMenu.library;
  const menuUser =
    context.props.logAnalyticMenu && context.props.logAnalyticMenu.user;
  const menuUniv =
    context.props.logAnalyticMenu && context.props.logAnalyticMenu.univ;
  const menuExam =
    context.props.logAnalyticMenu && context.props.logAnalyticMenu.exam;
  const menuQuiz =
    context.props.logAnalyticMenu && context.props.logAnalyticMenu.quiz;
  if (context._isMounted) {
    context.setState({
      dropdownData: {
        user: getVal(menuUser, "user"),
        univ: getVal(menuUniv, "univ"),
        exam: getVal(menuExam, "exam"),
        quiz: getVal(menuQuiz, "quiz"),
        exam_type: getVal(menuExamType),
        library: getVal(menuLibrary, "library"),
      },
    });
  }
};

/**
 * get values
 * @param {*} cfilterontext
 */
const getVal = (filter, get) => {
  const data = filter.map((element) => {
    if (get == "library")
      return {
        value: i18next.language === "ja" 
          ? element.title
          : element.title_en,
        id: element.id,
      };
    if (get == "exam")
      return {
        univ: element.univ_id,
        value: i18next.language === "ja" 
        ? element.title : element.title_en,
        id: element.exam_id,
      };
    if (get == "quiz")
      return {
        univ: element.univ_id,
        value: i18next.language === "ja" 
          ? element.title
          : element.title_en,
        id: element.exam_id,
      };
    if (get == "univ") {
      return {
        value: i18next.language === "ja" 
        ? element.name : element.name_en,
        id: element.id,
      };
    }
    if (element.account) {
      return {
        value: element.account.name,
        id: element.account.id,
      };
    } else
      return {
        value: element.value,
        value_jp: element.value_jp,
        id: element.id,
      };
  });
  return data;
};

/**
 * get unique values
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
 * toogle ranking modal
 * @param {*} context
 */
const handleToggleRankingModal = (context) => {
  const { isRankingModalVisible } = context.state;
  context.setState({ isRankingModalVisible: !isRankingModalVisible });
};

/**
 * toogle pie chart modal
 * @param {*} context
 */
const handleTogglePieChartModal = (context) => {
  const { isPieChartModalVisible } = context.state;
  context.setState({ isPieChartModalVisible: !isPieChartModalVisible });
};

/**
 * set dropdown selected values
 * @param {*} value
 * @param {*} type
 * @param {*} context
 */
const handleSelectDropDownValue = (value, type, context) => {

  const { selectedDropDowns, dropdownData} = context.state;
  if (context.props.userInfo.role === 101 && type === "univ") {
    let groupQuiz =
      context.props.logAnalyticMenu.quiz &&
      context.props.logAnalyticMenu.quiz
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
          return { value: i.title, id: i.exam_id};
        });
    let groupExam =
      context.props.logAnalyticMenu.exam &&
      context.props.logAnalyticMenu.exam
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
          return { value: i.title, id: i.exam_id};
        });
    let groupUsers =
      context.props.logAnalyticMenu.user &&
      context.props.logAnalyticMenu.user
        .filter((item) => {
          if (value) return item.account.univ == value;
          else return true;
        })
        .map((i) => {
          return { value: i.account.name, id: i.account.id };
        });
        
    let groupLibrary =
      context.props.logAnalyticMenu.library &&
      context.props.logAnalyticMenu.library
        .filter((item) => {
          if (value) {
            if(item.exam_groups && item.exam_groups.length != 0 ) {
              return item.exam_groups.includes(value);
            }
            else return true;
          }
          else return true;
        })
        .map((i) => {
          return { value: i.title_en, id: i.id};
        });
    context.setState({
      dropdownData: {
        univ: context.state.dropdownData.univ,
        exam_type: context.state.dropdownData.exam_type,
        user: groupUsers,
        exam:  groupExam,
        library: groupLibrary,
        quiz: groupQuiz,
      },
    });
  }
  
  if (selectedDropDowns[type] !== value) {
    context.setState(
      {
        selectedDropDowns: {
          ...selectedDropDowns,
          [type]: value,
        },
      },
      () => {
        handleGetTargetTotalizeTable(context);
        handleGetLogTable(context);
        //handleFilterLogTable(context);
      }
    );
  }
};

/**
 * get target totalize table data
 * @param {*} context
 */
const handleGetTargetTotalizeTable = async (context) => {
  const {
    selectedDropDowns,
    isDateSet,
    startDateSet,
    endDateSet,
  } = context.state;
  const { getTargetTable, userToken, userInfo } = context.props;
  if (context._isMounted) {
    context.setState({
      isTargetLoading: true,
    });
  }
  const startDateValue = dateFormatter(startDateSet);
  const endDateValue = dateFormatter(endDateSet);
  await getTargetTable(
    {
      user_id: selectedDropDowns.user || null,
      univ_id: selectedDropDowns.univ || null,
      exam_type: selectedDropDowns.exam_type || null,
      exam_id: selectedDropDowns.exam || null,
      quiz_id: selectedDropDowns.quiz || null,
      library_id: selectedDropDowns.library || null,
      user_univ_id: userInfo.university_id,
      user_login_id: userInfo.id,
      start_date: startDateValue,
      end_date: endDateValue,
    },
    userToken
  );
  if (context._isMounted) {
    context.setState({
      isTargetLoading: false,
    });
  }
};

/**
 * Filter log table according to dropdown
 * @param {*} context
 */
const handleFilterLogTable = (context) => {
  const { selectedDropDowns, originalLogList } = context.state;
  const filteredList =
    originalLogList &&
    originalLogList.filter((item) => {
      return (
        (selectedDropDowns.user != null
          ? selectedDropDowns.user === parseInt(item.user_id)
          : true) &&
        (selectedDropDowns.univ != null
          ? selectedDropDowns.univ === parseInt(item.univ_id)
          : true) &&
        (selectedDropDowns.exam != null
          ? selectedDropDowns.exam === parseInt(item.exam_id)
          : true) &&
        (selectedDropDowns.quiz != null
          ? selectedDropDowns.quiz === parseInt(item.quiz_id)
          : true) &&
        (selectedDropDowns.exam_type != null
          ? selectedDropDowns.exam_type === parseInt(item.quiz_id)
          : true) &&
        (selectedDropDowns.library != null
          ? selectedDropDowns.library === parseInt(item.library_id)
          : true)
      );
    });
  context.setState({ filteredList });
};

/**
 * Sort list using header
 * @param {*} headerItem
 * @param {*} context
 */
const handleSortList = (headerItem, context) => {
  const { sortedHeader, isAscending } = context.state;
  if (sortedHeader === headerItem) {
    context.setState({ isAscending: !isAscending });
    sortList(headerItem, !isAscending, context);
  } else {
    context.setState({ isAscending: true, sortedHeader: headerItem });
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
  const { filteredList } = context.state;
  let sortedList = [];
  if (isAscending) {
    sortedList = filteredList.sort((a, b) => {
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
    sortedList = filteredList.sort((a, b) => {
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
  context.setState({
    filteredList: sortedList,
  });
};

/**
 * set download csv true / false
 * @param {*} context
 * @param {*} isDownload
 */
const setDownloadCsv = (context, isDownload) => {
  context.setState({ isDownloadingCsv: isDownload });
};

/**
 * Close modal and filter table by date
 * @param {*} context
 */
const handleDateFilter = async (context) => {
  context.setState({
    isEndDatePickerVisible: false,
  });
  handleGetLogTable(context);
  handleGetTargetTotalizeTable(context);
};

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================
/**
 * redux state
 * @param {*} state
 */
const mapStateToProps = (state) => {
  return {
    logAnalytic: state.logAnalytic,
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    userInfo: state.auth.userInfo && state.auth.userInfo.user,
    filteredTargetList: state.logAnalytic.filteredTargetList,
    rankingList: state.logAnalytic.rankingList,
    pieChartList: state.logAnalytic.pieChartList,
    isLoading: state.logAnalytic.isLoading,
    logAnalyticMenu: state.logAnalytic.logAnalyticMenu,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, {
  getSelectMenu,
  getLibraryUser,
  getLogAnalytic,
  getTargetTable,
  getRankingData,
  getPieChartData,
})(withTranslation("translation")(LogAnalytics));
