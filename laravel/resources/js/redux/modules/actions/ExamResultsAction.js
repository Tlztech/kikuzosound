import Api from "../../../common/Api";
import {
  GET_EXAMRESULTS_SUCCESS,
  GET_EXAMRESULTS_FAILED,
  EXAMRESULTS_LOADING,
  GET_CHART_DATA_SUCCESS,
  GET_CHART_DATA_FAILED,
  GET_EXAM_ANAYTICS_MENU_LOADING,
  GET_EXAM_ANAYTICS_MENU_SUCCESS,
  GET_EXAM_ANAYTICS_MENU_FAILED,
} from "../constants/actiontypes";

export const getExamResults = (token, userInfo) => {
  return async (dispatch) => {
    try {
      dispatch(setExamResultsLoading());
      const response = await Api.getExamResults(token, userInfo);
      if (response && response.data.success === "ok") {
        dispatch(getExamResultsSuccess(response.data.result));
      } else {
        dispatch(getExamResultsFailed());
      }
    } catch (e) {
      dispatch(getExamResultsFailed());
      console.log("getExamResults", e);
    }
  };
};

export function setExamResultsLoading() {
  return {
    type: EXAMRESULTS_LOADING,
  };
}

export function getExamResultsSuccess(data) {
  return {
    type: GET_EXAMRESULTS_SUCCESS,
    payload: data,
  };
}

export function getExamResultsFailed() {
  return {
    type: GET_EXAMRESULTS_FAILED,
  };
}

export const getChartAnalyticsData = (token, userInfo) => {
  return async (dispatch) => {
    try {
      dispatch(setExamResultsLoading());
      const response = await Api.getChartAnalyticsData(token, userInfo);
      if (response && response.data.success === "ok") {
        dispatch(
          getChartAnalyticsDataSuccess({
            data: response.data.result,
            chartType: userInfo.chart,
          })
        );
      } else {
        dispatch(getChartAnalyticsDataFailed());
      }
    } catch (e) {
      dispatch(getChartAnalyticsDataFailed());
      console.log("getChartAnalyticsData", e);
    }
  };
};

export function getChartAnalyticsDataSuccess(data) {
  return {
    type: GET_CHART_DATA_SUCCESS,
    payload: data,
  };
}

export function getChartAnalyticsDataFailed() {
  return {
    type: GET_CHART_DATA_FAILED,
  };
}

export const getExamAnalyticsMenu = (token) => {
  return async (dispatch) => {
    try {
      dispatch(setExamAnalyticsMenuLoading());
      const response = await Api.getExamAnalyticMenu(token);
      if (response && response.data.success === "ok") {
        dispatch(getExamAnalyticsMenuSuccess(response.data.result));
      } else {
        dispatch(getExamAnalyticsMenuFailed());
      }
    } catch (e) {
      dispatch(getExamAnalyticsMenuFailed());
      console.log("getExamAnalyticMenu", e);
    }
  };
};

export function setExamAnalyticsMenuLoading() {
  return {
    type: GET_EXAM_ANAYTICS_MENU_LOADING,
  };
}

export function getExamAnalyticsMenuSuccess(data) {
  return {
    type: GET_EXAM_ANAYTICS_MENU_SUCCESS,
    payload: data,
  };
}

export function getExamAnalyticsMenuFailed() {
  return {
    type: GET_EXAM_ANAYTICS_MENU_FAILED,
  };
}
