import Api from "../../../common/Api";
import {
  GET_LOGANALYTICSRESULT_SUCCESS_N,
  GET_LOGANALYTICSRESULT_FAILED_N,
  LOGANALYTICSRESULT_LOADING_N,
  FILTER_LOG_ANALYTICS_SUCCESS,
  FILTER_LOG_ANALYTICS_FAILED,
  GET_RANKING_DATA_SUCCESS,
  GET_RANKING_DATA_FAILED,
  GET_PIE_CHART_DATA_SUCCESS,
  GET_PIE_CHART_DATA_FAILED,
  GET_SELECT_MENU_LOADING,
  GET_SELECT_MENU_SUCCESS,
  GET_SELECT_MENU_FAILED,
} from "../constants/actiontypes";

export const getSelectMenu = (token) => {
  return async (dispatch) => {
    try {
      dispatch(getSelectMenuLoading());
      const response = await Api.getSelectMenus(token);
      if (response && response.data.success === "ok") {
        dispatch(getSelectMenuSuccess(response.data.result));
      } else {
        dispatch(getSelectMenuFailed());
      }
    } catch (e) {
      dispatch(getSelectMenuFailed());
      console.log("getSelectMenu", e);
    }
  };
};

export function getSelectMenuLoading() {
  return {
    type: GET_SELECT_MENU_LOADING,
  };
}

export function getSelectMenuSuccess(data) {
  return {
    type: GET_SELECT_MENU_SUCCESS,
    payload: data,
  };
}

export function getSelectMenuFailed() {
  return {
    type: GET_SELECT_MENU_FAILED,
  };
}

export const getLogAnalytic = (token, data) => {
  return async (dispatch) => {
    try {
      dispatch(setLogAnalyticLoading());
      const response = await Api.getLogAnalytics(token, data);
      if (response && response.data.success === "ok")
        dispatch(getLogAnalyticSuccess(response.data));
      else dispatch(getLogAnalyticFailed());
    } catch (e) {
      dispatch(getLogAnalyticFailed());
      console.log("getLogAnalytic", e);
    }
  };
};

export function setLogAnalyticLoading() {
  return {
    type: LOGANALYTICSRESULT_LOADING_N,
  };
}

export function getLogAnalyticSuccess(data) {
  return {
    type: GET_LOGANALYTICSRESULT_SUCCESS_N,
    payload: data,
  };
}

export function getLogAnalyticFailed() {
  return {
    type: GET_LOGANALYTICSRESULT_FAILED_N,
  };
}

export const getTargetTable = (params, token) => {
  return async (dispatch) => {
    try {
      dispatch(setLogAnalyticLoading());
      const response = await Api.filterLogAnalytics(params, token);
      if (response && response.data.success === "ok")
        dispatch(filterLogAnalyticsSuccess(response.data.result));
      else dispatch(filterLogAnalyticsFailed());
    } catch (e) {
      dispatch(filterLogAnalyticsFailed());
      console.log("filter log analytics failed", e);
    }
  };
};

export function filterLogAnalyticsSuccess(data) {
  return {
    type: FILTER_LOG_ANALYTICS_SUCCESS,
    payload: data,
  };
}

export function filterLogAnalyticsFailed() {
  return {
    type: FILTER_LOG_ANALYTICS_FAILED,
  };
}

export const getRankingData = (params, token) => {
  return async (dispatch) => {
    try {
      dispatch(setLogAnalyticLoading());
      const response = await Api.getRankingData(params, token);
      if (response && response.data.success === "ok")
        dispatch(getRankingDataSuccess(response.data.result));
      else dispatch(getRankingDataFailed());
    } catch (e) {
      dispatch(getRankingDataFailed());
      console.log("get ranking data failed", e);
    }
  };
};

export function getRankingDataSuccess(data) {
  return {
    type: GET_RANKING_DATA_SUCCESS,
    payload: data,
  };
}

export function getRankingDataFailed() {
  return {
    type: GET_RANKING_DATA_FAILED,
  };
}

export const getPieChartData = (params, token) => {
  return async (dispatch) => {
    try {
      dispatch(setLogAnalyticLoading());
      const response = await Api.getPieChartData(params, token);
      if (response && response.data.success === "ok")
        dispatch(getPieChartDataSuccess(response.data.result));
      else dispatch(getPieChartDataFailed());
    } catch (e) {
      dispatch(getPieChartDataFailed());
      console.log("get pie chart data failed", e);
    }
  };
};

export function getPieChartDataSuccess(data) {
  return {
    type: GET_PIE_CHART_DATA_SUCCESS,
    payload: data,
  };
}

export function getPieChartDataFailed() {
  return {
    type: GET_PIE_CHART_DATA_FAILED,
  };
}
